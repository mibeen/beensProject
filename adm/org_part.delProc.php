<?php
	$sub_menu = "990300";
	include_once('_common.php');

	auth_check($auth[$sub_menu], "d");


	# set : variables
	$OP_IDX = $_POST['OP_IDX'];

	
	# re-define
	$OP_IDX = CHK_NUMBER($OP_IDX);
	

	# chk : rfv.
	if ($OP_IDX <= 0) {
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>"필수 항목이 누락되었습니다."
		));
	}


	# get data
	$db1 = sql_fetch("Select OP.OP_PARENT_IDX, OP.OP_SEQ, Count(OP.OP_IDX) as cnt From ORG_PART As OP Where OP.OP_DDATE is null And OP.OP_IDX = '" . mres($OP_IDX) . "' Limit 1");

	$OP_PARENT_IDX = $db1['OP_PARENT_IDX'];
	$SEQ = $db1['OP_SEQ'];

	if ($db1['cnt'] <= 0) {
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>"요청한 항목이 존재하지 않습니다."
		));
	}
	unset($db1);


	$basic_wh = "Where OP_DDATE is null And OP_PARENT_IDX = '" . mres($OP_PARENT_IDX) . "'";


	# update : 전달된 SEQ 이상인 정보 -1
	$sql = "Update ORG_PART Set"
		." 		OP_SEQ = OP_SEQ - 1"
		." 	{$basic_wh}"
		."		And OP_SEQ > '" . mres($SEQ) . "'"
		."		And OP_IDX != '" . mres($OP_IDX) . "'"
		." 	Order By OP_SEQ Asc"
		;
	sql_query($sql);


	# delete : master record
	$sql = "Update ORG_PART Set OP_DDATE = now(), OP_DIP = inet_aton('" . mres($_SERVER['REMOTE_ADDR']) . "') Where OP_IDX = '" . mres($OP_IDX) . "' Limit 1";
	sql_query($sql);


	# re-direct
	$reDir = "org_part.list.php";


	echo_json(array(
		'success'=>(boolean)true, 
		'redir'=>$reDir
	));
?>
