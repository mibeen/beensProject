<?php
	include_once("./_common.php");


	# set : variables
	$url = $_GET['url'];
	$C_IDX = $_GET['C_IDX'];


	# re-define
	$C_IDX = (is_numeric($C_IDX)) ? (int)$C_IDX : "";


	$sql = "Select C.GROUP_IDX, C.mb_id, C.C_REPLY"
		."	From NX_COMMENT As C"
		."	Where C.C_DDATE is null And C.C_IDX = '" . mres($C_IDX) . "'"
		;
	$row = sql_query($sql);
	if (sql_num_rows($row) <= 0) {
		alert("잘못 들어오셨습니다.");
	}
	$rs1 = sql_fetch_array($row);
	$GROUP_IDX = $rs1['GROUP_IDX'];
	$mb_id = $rs1['mb_id'];
	$C_REPLY = $rs1['C_REPLY'];


	if(!$is_admin && $member['mb_id'] != $rs1['mb_id']) {
		alert("본인이 쓴 글만 삭제할 수 있습니다.");
	}


	# delete : NX_COMMENT
	$sql = "Update NX_COMMENT Set"
		. " C_DDATE = now()"
		. ", C_DIP = inet_aton('" . mres($_SERVER['REMOTE_ADDR']) . "')"
		. " Where C_DDATE is null And GROUP_IDX = '" . mres($GROUP_IDX) . "' And C_REPLY like '" . mres($C_REPLY) . "%'";

	sql_query($sql);


	goto_url($url);
?>
