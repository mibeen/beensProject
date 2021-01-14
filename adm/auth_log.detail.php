<?php
	$sub_menu = "100210";
	include_once('./_common.php');


	# 최고관리자만 열람 가능
	if ($is_admin != 'super') {
		alert("최고관리자만 열람 가능합니다." ,G5_ADMIN_URL);
	}


	# set : variables
	$mb_id = $_POST['mb_id'];
	$au_datetime = $_POST['au_datetime'];
	$cnt = $_POST['cnt'];


	# re-define
	$mb_id = trim($mb_id);
	$au_datetime = trim($au_datetime);
	$cnt = (CHK_NUMBER($cnt) > 0) ? (int)$cnt : "";


	# set: $wh
	$wh = "Where mb_id = '" . mres($mb_id) . "'";

	if ($au_datetime != "") {
		$wh .= " And au_datetime = '" . mres($au_datetime) . "'";
	}
	else {
		$wh .= " And au_datetime is null";
	}


	$sql = "("
		."		Select au_menu, au_auth"
		."			From g5_auth"
		."			{$wh}"
		."	)"
		."	UNION ALL"
		."	("
		."		Select au_menu, au_auth"
		."			From g5_auth_log"
		."			{$wh}"
		."	)"
		."	Order By au_menu Asc"
		;
	$db1 = sql_query($sql);


	$ret = array();
	$ret['success'] = true;
	$ret['itms'] = array();
	while ($rs1 = sql_fetch_array($db1)) {
		$sql = "Select au_auth"
			."		From g5_auth_log"
			."		Where mb_id = '" . mres($mb_id) . "'"
			."			And (au_datetime < '" . mres($au_datetime) . "' Or au_datetime is null)"
			."			And au_menu = '" . mres($rs1['au_menu']) . "'"
			."		Order By au_datetime Desc"
			."		Limit 0, 1"
			;
		$db2 = sql_query($sql);

		# g5_auth_log 에 없으면 g5_auth 에서 조회
		if (sql_num_rows($db2) <= 0) {
			$sql = "Select au_auth"
				."		From g5_auth_log"
				."		Where mb_id = '" . mres($mb_id) . "'"
				."			And (au_datetime < '" . mres($au_datetime) . "' Or au_datetime is null)"
				."			And au_menu = '" . mres($rs1['au_menu']) . "'"
				."		Order By au_datetime Desc"
				."		Limit 0, 1"
				;
			$db2 = sql_query($sql);
		}

		$rs2 = sql_fetch_array($db2);


		$ret['itms'][] = array(
			'au_menu' => get_menu_name($rs1['au_menu'])
			, 'before_auth' => ($rs2['au_auth'] != '') ? $rs2['au_auth'] : '권한없음'
			, 'after_auth' => ($rs1['au_auth'] != '') ? $rs1['au_auth'] : '권한없음'
			);
	}


	echo_json($ret);
?>
