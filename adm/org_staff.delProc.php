<?php
	$sub_menu = "990310";
	include_once('_common.php');

	if ($ret = auth_check($auth[$sub_menu], "d", true, true)) {
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>$ret
		));
	}


	# set : variables
	$OS_IDX = $_POST['OS_IDX'];

	
	# re-define
	$OS_IDX = CHK_NUMBER($OS_IDX);
	

	# chk : rfv.
	if ($OS_IDX <= 0) {
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>"필수 항목이 누락되었습니다."
		));
	}


	# get data
	$db1 = sql_fetch("Select OS.OS_SEQ, OS.OP_IDX, Count(OS.OS_IDX) as cnt From ORG_STAFF As OS Where OS.OS_DDATE is null And OS.OS_IDX = '" . mres($OS_IDX) . "' Limit 1");

	$OP_IDX = $db1['OP_IDX'];
	$SEQ = $db1['OS_SEQ'];

	if ($db1['cnt'] <= 0) {
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>"요청한 항목이 존재하지 않습니다."
		));
	}
	unset($db1);


	$basic_wh = "Where OS_DDATE is null And OP_IDX = '" . mres($OP_IDX) . "'";


	# update : 전달된 SEQ 이상인 정보 -1
	$sql = "Update ORG_STAFF Set"
		." 		OS_SEQ = OS_SEQ - 1"
		." 	{$basic_wh}"
		."		And OS_SEQ > '" . mres($SEQ) . "'"
		."		And OS_IDX != '" . mres($OS_IDX) . "'"
		." 	Order By OS_SEQ Asc"
		;
	sql_query($sql);


	# delete : master record
	$sql = "Update ORG_STAFF Set OS_DDATE = now(), OS_DIP = inet_aton('" . mres($_SERVER['REMOTE_ADDR']) . "') Where OS_IDX = '" . mres($OS_IDX) . "' Limit 1";
	sql_query($sql);


	# re-direct
	$reDir = "org_staff.list.php";


	echo_json(array(
		'success'=>(boolean)true, 
		'redir'=>$reDir
	));
?>
