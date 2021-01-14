<?php
    $sub_menu = "990700";
    include_once('./_common.php');

    auth_check($auth[$sub_menu], 'r');



    # 개시판별 통계
    $rowCnt = 30;
    $rowCnt = strtotime(date(Ymd)) - strtotime("20170102");
    $rowCnt = ceil($rowCnt / (60*60*24*30));
    ###################################################################################################
    for ($i=0; $i<$rowCnt; $i++){
        ${"todayYearMonth_".$i} = date("Y-m", strtotime("-".$i." months"));
        $array_todayYear[$i] = date("Y m", strtotime("-".$i." months"));
        #가입자
        ${"sql_joinuser_month_".$i}       = "SELECT count(*) as cnt FROM gill.g5_member WHERE mb_datetime like '%".${"todayYearMonth_".$i}."%'";
        ${"result_joinuser_month_".$i} = sql_query(${"sql_joinuser_month_".$i});
        while($row = sql_fetch_array(${"result_joinuser_month_".$i})){
            $array_joinuser[$i] = $row['cnt'];
        }
        #방문자
        ${"sql_loginuser_month_".$i}       = "SELECT count(*) as cnt FROM gill.g5_member WHERE 1=1  AND mb_today_login like '%".${"todayYearMonth_".$i}."%'";
        ${"result_loginuser_month_".$i} = sql_query(${"sql_loginuser_month_".$i});
        while($row = sql_fetch_array(${"result_loginuser_month_".$i})){
            $array_loginuser[$i] = $row['cnt'];
        }
        #접속자
        ${"sql_conuser_month_".$i}       = "SELECT sum(vs_count) as cnt FROM g5_visit_sum WHERE vs_date like '%".${"todayYearMonth_".$i}."%'";
        ${"result_conuser_month_".$i} = sql_query(${"sql_conuser_month_".$i});
        while($row = sql_fetch_array(${"result_conuser_month_".$i})){
            $array_conuser[$i] = (($row['cnt']==null) ? '0' : $row['cnt']);
        } 
    }
    ###################################################################################################



	/*	CSV 용 string 으로 변환
		- 전달된 값에 '"' 를 더함
		- 전달된 값의 '"' 를 '""' 로 치환
	*/
	function F_CSVSTR($v)
	{
		if ($v == '') return;

		$ret = "\"" . str_replace("\"", "\"\"", $v) . "\"";
		return $ret;
	}


	header("Content-Type: text/csv; charset=utf-8");
	header("Content-Disposition: attachment; fileName=".iconv('utf-8', 'euc-kr', '월별 방문_접속_가입자 통계')."_" . date("ymd") . ".csv");
	header("Content-Description: PHP" . phpversion() . " Generated Data");
	echo("\xEF\xBB\xBF");





	# header
	#echo F_CSVSTR("Date.");
	# header
	echo F_CSVSTR("날짜/월");
	echo "," . F_CSVSTR("방문자");
	echo "," . F_CSVSTR("접속자");
	echo "," . F_CSVSTR("가입자");
	
	echo("\r\n");


	for($i=0; $i < $rowCnt ; $i++){
	    #echo F_CSVSTR($i + 1);
	    echo F_CSVSTR($array_todayYear[$i]);
	    echo "," . F_CSVSTR($array_conuser[$i]);
	    echo "," . F_CSVSTR($array_loginuser[$i]);
	    echo "," . F_CSVSTR($array_joinuser[$i]);
	    
	    echo("\r\n");
	    
	}

	exit;

    function createDateRangeArray($strDateFrom,$strDateTo) {
      // takes two dates formatted as YYYY-MM-DD and creates an
      // inclusive array of the dates between the from and to dates.

      // could test validity of dates here but I’m already doing
      // that in the main script

        $aryRange  = array();

        $iDateFrom = mktime(1,0,0,substr($strDateFrom,5,2),     substr($strDateFrom,8,2),substr($strDateFrom,0,4));
        $iDateTo   = mktime(1,0,0,substr($strDateTo,5,2),     substr($strDateTo,8,2),substr($strDateTo,0,4));

        if ($iDateTo >= $iDateFrom) {
            array_push($aryRange, date('Y-m-d', $iDateFrom)); // first entry

            while ($iDateFrom < $iDateTo) {
                $iDateFrom += 86400; // add 24 hours
                array_push($aryRange, date('Y-m-d', $iDateFrom));
            }
        }
        return $aryRange;
    }
?>
