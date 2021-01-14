<?php
	$sub_menu = "970100";
	include_once('./_common.php');

	auth_check($auth[$sub_menu], "r");


	#----- wh
	$wh = " Where lp.lp_ddate is null ";

	if ($stx) {
		if($sfl != '') {
			$wh .= " and ( ";
			$wh .= " ($sfl like '%$stx%') ";
			$wh .= " ) ";
		}
		else if($sfl == '') {
			$wh .= " and ( ";
			$wh .= " (lp_name like '%$stx%') || ";
			$wh .= " (lp_charge like '%$stx%') || ";
			$wh .= " (lp_tel like '%$stx%') || ";
			$wh .= " (lp_email like '%$stx%') || ";
			$wh .= " (lp_address like '%$stx%') ";
			$wh .= " ) ";
		}
	}

	if($suy != '') {
		$wh .= " and lp.lp_use_yn = '{$suy}'";
	}

	if($splace != '') {
		$wh .= " and lp.la_idx = {$splace}";
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


	#----- get : 장소 목록
	$sql = "Select lp.* 
					, la.la_name
				from local_place as lp
					Inner Join local_place_area as la on la.la_idx = lp.la_idx
						and la.la_ddate is null
	            {$wh}
	            Order By la.la_idx Asc, lp.lp_name Asc"
    ;
	$db1 = sql_query($sql);

	if (sql_num_rows($db1) <= 0) {
		F_script("장소내역이 없습니다.", "history.back();");
	}
	else
	{
		header("Content-Type: text/csv; charset=utf-8");
		header("Content-Disposition: attachment; fileName=".iconv('utf-8', 'euc-kr', '학습공간 장소목록')."_" . date("ymd") . ".csv");
		header("Content-Description: PHP" . phpversion() . " Generated Data");
		echo("\xEF\xBB\xBF");

		
		# header
		echo F_CSVSTR("NO.");
		echo "," . F_CSVSTR("장소고유번호");
		echo "," . F_CSVSTR("지역명");
		echo "," . F_CSVSTR("시설명");
		echo "," . F_CSVSTR("시설관리자 ID");
		echo "," . F_CSVSTR("대표번호");
		echo "," . F_CSVSTR("대표메일");
		echo "," . F_CSVSTR("주소");
		echo "," . F_CSVSTR("활성화여부");
		echo "," . F_CSVSTR("등록일");

		echo("\r\n");


		$s = 0;
		while ($rs1 = sql_fetch_array($db1))
		{
			echo F_CSVSTR($s + 1);
			echo "," . F_CSVSTR(F_hsc($rs1['lp_idx']));
			echo "," . F_CSVSTR(F_hsc($rs1['la_name']));
			echo "," . F_CSVSTR(F_hsc($rs1['lp_name']));
			echo "," . F_CSVSTR(F_hsc($rs1['mb_id']));
			echo "," . F_CSVSTR(F_hsc($rs1['lp_tel']));
			echo "," . F_CSVSTR(F_hsc($rs1['lp_email']));
			echo "," . F_CSVSTR(F_hsc($rs1['lp_address']));
			echo "," . F_CSVSTR(F_YN($rs1['lp_use_yn']));
			echo "," . F_CSVSTR($rs1['lp_wdate']);


			echo("\r\n");

			$s++;
		}
	}
	exit;
?>
