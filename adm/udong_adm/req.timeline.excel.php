<?php
	$sub_menu = "960300";
	include_once('./_common.php');
    
	if ($member['mb_level'] == 3) {
	    $auth['960300'] = 'r';
	}
	auth_check($auth[$sub_menu], "r");


	# 관리자 개인정보 접근이력 기록
	nx_privacy_log('excel', 'local_place_req');


	#----- wh
	$wh = " Where lr.lr_ddate is null ";

	if ($sc_lr_sdate != '') {
	    $wh .= " And lr.lr_sdate >= '" . mres($sc_lr_sdate) . "'";
	}
	if ($sc_lr_edate != '') {
	    $wh .= " And lr.lr_edate < DATE_ADD('" . mres($sc_lr_edate) . "', INTERVAL 1 DAY)";
	}

	if ($stx) {
	    $wh .= " And lp.lp_name like '%" . mres($stx) . "%'";
	}

	if($suy != '') {
	    $wh .= " And lr.lr_status = '" . mres($suy) . "'";
	}

	if($splace != '') {
	    $wh .= " And la.la_idx = '" . mres($splace) . "'";
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


	#----- get : 예약 목록
	$sql = "Select lr.* 
                , lp.lp_idx, lp.lp_name
                , la.la_idx, la.la_name
                , M.mb_nick
            From local_place_req As lr
                Inner Join local_place As lp On lp.lp_idx = lr.lp_idx 
                    And lp.lp_ddate is null
                Inner Join local_place_area As la On la.la_idx = lp.la_idx 
                    And la.la_ddate is null
                Inner Join g5_member As M On M.mb_id = lr.mb_id
            {$wh}
            Order By lr.lr_sdate Desc"
    ;
	$db1 = sql_query($sql);

	if (sql_num_rows($db1) <= 0) {
		F_script("예약내역이 없습니다.", "history.back();");
	}
	else
	{
		header("Content-Type: text/csv; charset=utf-8");
		header("Content-Disposition: attachment; fileName=".iconv('utf-8', 'euc-kr', '학습공간 예약목록')."_" . date("ymd") . ".csv");
		header("Content-Description: PHP" . phpversion() . " Generated Data");
		echo("\xEF\xBB\xBF");

		
		$str_lr_status = ['A'=>'신청', 'B'=>'승인', 'C'=>'미승인', 'D'=>'승인취소'];


		# header
		echo F_CSVSTR("NO.");
		echo "," . F_CSVSTR("지역명");
		echo "," . F_CSVSTR("시설명");
		echo "," . F_CSVSTR("신청자");
		echo "," . F_CSVSTR("신청자 ID");
		echo "," . F_CSVSTR("휴대폰");
		echo "," . F_CSVSTR("이메일");
		echo "," . F_CSVSTR("시작일시");
		echo "," . F_CSVSTR("종료일시");
		echo "," . F_CSVSTR("인원");
		echo "," . F_CSVSTR("이용목적");
		echo "," . F_CSVSTR("요구사항");
		echo "," . F_CSVSTR("진행상태");
		echo "," . F_CSVSTR("신청일");

		echo("\r\n");


		$s = 0;
		while ($rs1 = sql_fetch_array($db1))
		{
			echo F_CSVSTR($s + 1);
			echo "," . F_CSVSTR(F_hsc($rs1['la_name']));
			echo "," . F_CSVSTR(F_hsc($rs1['lp_name']));
			echo "," . F_CSVSTR(F_hsc($rs1['mb_nick']));
			echo "," . F_CSVSTR(F_hsc($rs1['mb_id']));
			echo "," . F_CSVSTR(F_hsc($rs1['lr_tel']));
			echo "," . F_CSVSTR(F_hsc($rs1['lr_email']));
			echo "," . F_CSVSTR(F_hsc($rs1['lr_sdate']));
			echo "," . F_CSVSTR(F_hsc($rs1['lr_edate']));
			echo "," . F_CSVSTR(F_hsc($rs1['lr_p_cnt']));
			echo "," . F_CSVSTR(F_hsc($rs1['lr_usage']));
			echo "," . F_CSVSTR(F_hsc($rs1['lr_cont']));
			echo "," . F_CSVSTR($str_lr_status[$rs1['lr_status']]);
			echo "," . F_CSVSTR($rs1['lr_wdate']);


			echo("\r\n");

			$s++;
		}
	}
	exit;
?>
