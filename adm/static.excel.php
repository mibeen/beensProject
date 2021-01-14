<?php
    $sub_menu = "990700";
    include_once('./_common.php');

    auth_check($auth[$sub_menu], 'r');


	#----- wh


    # 날짜 기본
    $FROM = clean_xss_tags($_GET['from']);
    $TO   = clean_xss_tags($_GET['to']);
    $dayArray = [];

    if($FROM == '' && $TO == ''){
        # 파라메터가 없으면 이번 달로 지정
        $FROM = date('Y-m-') . '01';
        $TO   = date('Y-m-d');
    }else{
        # 파라메터가 있으면 해당 파라메터대로.
        $FROM = date('Y-m-d', strtotime($FROM));
        $TO   = date('Y-m-d', strtotime($TO));
    }


    $dayArray = createDateRangeArray($FROM, $TO);
    $day = [];


    # 키와 배열을 바꾼다.
    for($i=0; $i < count($dayArray); $i++){
        $day[$dayArray[$i]] = (int)0;
    }


    # 게시판 개수
    $sql = "SELECT *
            FROM g5_board
            WHERE bo_skin IN('Basic-Board')
                And bo_table NOT IN ('roller1', 'banner1', 'side_banner1', 'rolling_logo', 'main_banner', 'whatson', 'temp', 'ethical_edu') ";
    $db1 = sql_query($sql) or die('sql error');
    $cnt = sql_num_rows($db1);
    $array = [];
    while($row = sql_fetch_array($db1)){
        $array[] = [
              'name' => $row['bo_subject']
            , 'table' => 'g5_write_' . $row['bo_table']
            , 'day' => $day
        ];
    }
    unset($row);


    # 개시판별 통계
    for ($i=0; $i < count($array); $i++) {
        $sql = "SELECT date_format(wr_datetime, '%Y-%m-%d') as date, count(wr_id) as cnt FROM {$array[$i]['table']}
                WHERE date_format(wr_datetime, '%Y-%m-%d') >= '{$FROM}' And date_format(wr_datetime, '%Y-%m-%d') <= '{$TO}'
                    AND wr_is_comment = 0
                Group by date_format(wr_datetime, '%Y-%m-%d')
                ";
        $db1 = sql_query($sql);
        while($row = sql_fetch_array($db1)){
            $array[$i]['day'][$row['date']] = $row['cnt'];
        }
        unset($row, $db1, $sql);
    }

	#####


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
	header("Content-Disposition: attachment; fileName=".iconv('utf-8', 'euc-kr', '게시판 통계')."_" . date("ymd") . ".csv");
	header("Content-Description: PHP" . phpversion() . " Generated Data");
	echo("\xEF\xBB\xBF");





	# header
	echo F_CSVSTR("Date.");
    for($i=0; $i < count($array); $i++){
        echo "," . F_CSVSTR($array[$i]['name']);
    }

	echo("\r\n");


    for($i=0; $i < count($dayArray); $i++){
        echo F_CSVSTR($dayArray[$i]);
        for($i2=0; $i2 < count($array); $i2++){
            $int = $array[$i2]['day'][$dayArray[$i]] > 0 ? $array[$i2]['day'][$dayArray[$i]] : 0;
            echo "," . F_hsc($int);
        }

        echo("\r\n");
    }


	// $s = 0;
	// while ($rs1 = sql_fetch_array($db1))
	// {
	// 	echo F_CSVSTR($s + 1);
	// 	echo "," . F_CSVSTR(F_hsc($rs1['lp_idx']));
	// 	echo "," . F_CSVSTR(F_hsc($rs1['la_name']));
	// 	echo "," . F_CSVSTR(F_hsc($rs1['lp_name']));
	// 	echo "," . F_CSVSTR(F_hsc($rs1['mb_id']));
	// 	echo "," . F_CSVSTR(F_hsc($rs1['lp_tel']));
	// 	echo "," . F_CSVSTR(F_hsc($rs1['lp_email']));
	// 	echo "," . F_CSVSTR(F_hsc($rs1['lp_address']));
	// 	echo "," . F_CSVSTR(F_YN($rs1['lp_use_yn']));
	// 	echo "," . F_CSVSTR($rs1['lp_wdate']);
    //
    //
	// 	echo("\r\n");
    //
	// 	$s++;
	// }
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
