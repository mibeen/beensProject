<?php
    $sub_menu = "990700";
    include_once('./_common.php');

    auth_check($auth[$sub_menu], 'r');



    # 개시판별 통계
    ###################################################################################################
    # 전체 유저
    $sql_Alluser       = "SELECT count(*) as cnt FROM gill.g5_member";
    $result_Alluser = sql_query($sql_Alluser);
    $row_Alluser = sql_fetch_array($result_Alluser);
    $cnt_Alluser = $row_Alluser['cnt'];
    
    # 활성화 유저 (현재 기준 1년안에 로그인 유저)
    $sql_Onuser       = "SELECT count(*) as cnt FROM gill.g5_member WHERE mb_today_login >= SUBDATE(NOW(), INTERVAL 1 YEAR)";
    $result_Onuser = sql_query($sql_Onuser);
    $row_Onuser = sql_fetch_array($result_Onuser);
    $cnt_Onuser = $row_Onuser['cnt'];
    
    # 휴면 유저 (1년 이상 미접속)
    $sql_Offuser       = "SELECT count(*) as cnt FROM gill.g5_member WHERE mb_today_login < SUBDATE(NOW(), INTERVAL 1 YEAR)";
    $result_Offuser = sql_query($sql_Offuser);
    $row_Offuser = sql_fetch_array($result_Offuser);
    $cnt_Offuser = $row_Offuser['cnt'];
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
	header("Content-Disposition: attachment; fileName=".iconv('utf-8', 'euc-kr', '회원통계(활성_휴먼)')."_" . date("ymd") . ".csv");
	header("Content-Description: PHP" . phpversion() . " Generated Data");
	echo("\xEF\xBB\xBF");





	# header
	#echo F_CSVSTR("Date.");
	# header
	echo F_CSVSTR("종류");
	echo "," . F_CSVSTR("인원");
	
	echo("\r\n");

    echo F_CSVSTR("전체 회원");
    echo "," . F_CSVSTR($cnt_Alluser);
    echo("\r\n");
    	    
    echo F_CSVSTR("활성 회원");
    echo "," . F_CSVSTR($cnt_Onuser);
    echo("\r\n");
    
    echo F_CSVSTR("휴먼 회원");
    echo "," . F_CSVSTR($cnt_Offuser);
    echo("\r\n");

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
