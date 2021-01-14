<?php
    $sub_menu = "990700";
    include_once('./_common.php');

    auth_check($auth[$sub_menu], 'r');

    # 날짜 기본
    $year = clean_xss_tags($_GET['year']);
    
    if($year == ''){
        # 파라메터가 없으면 이번 해
        $year = date('Y')."-01-01";
    }else{
        # 파라메터가 있으면 해당 파라메터대로.
        //$year = date('Y-m-d', strtotime($year));
        $year = date($year."-01-01");
    }
    
    
    #우리동네 학습공간 실적
    $rowCnt = 365;
    ###################################################################################################
    for ($i=0 ; $i<$rowCnt ; $i++){
        ${"todayYearMonth_".$i} = date("Y-m-d", strtotime("+".$i." days", strtotime($year)));
        $array_todayYear[$i] =    date("Y.m.d", strtotime("+".$i." days", strtotime($year)));
        
        #가입자
        ${"sql_joinuser_month_".$i}       = "SELECT count(*) as cnt FROM gill.NX_EVENT_JOIN where EJ_WDATE like '%".${"todayYearMonth_".$i}."%'";
        ${"result_joinuser_month_".$i} = sql_query(${"sql_joinuser_month_".$i});
        while($row = sql_fetch_array(${"result_joinuser_month_".$i})){
            $array_joinuser[$i] = $row['cnt'];
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
	header("Content-Disposition: attachment; fileName=".iconv('utf-8', 'euc-kr', '모집정보_실적')."_" . date("ymd") . ".csv");
	header("Content-Description: PHP" . phpversion() . " Generated Data");
	echo("\xEF\xBB\xBF");





	# header
	#echo F_CSVSTR("Date.");
	# header
	echo F_CSVSTR("날짜/일");
	echo "," . F_CSVSTR("신청인원");
	
	echo("\r\n");
	for($i=0; $i < $rowCnt ; $i++){
	    echo F_CSVSTR($array_todayYear[$i]);
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
