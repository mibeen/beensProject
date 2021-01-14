<?php
	$sub_menu = "990300";
	include_once('_common.php');

	include_once('org_part.err.php');

	auth_check($auth[$sub_menu], "w");


	# set : variables
	$OP_PARENT_IDX = $_POST['OP_PARENT_IDX'];
	$OP_SEQ = $_POST['OP_SEQ'];
	$OP_NAME = $_POST['OP_NAME'];

	
	# re-define
	$OP_PARENT_IDX = CHK_NUMBER($OP_PARENT_IDX);
	$OP_SEQ = CHK_NUMBER($OP_SEQ);
	$OP_NAME = trim($OP_NAME);


	# chk : rfv.
	if ($OP_PARENT_IDX == '' || $OP_SEQ == '' || $OP_NAME == '') {
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>"필수 항목이 누락되었습니다."
		));
	}


	$basic_wh = "Where OP_DDATE is null And OP_PARENT_IDX = '" . mres($OP_PARENT_IDX) . "'";


	# update : 전달된 SEQ 이상인 정보 +1
	$sql = "Update ORG_PART Set"
		." 		OP_SEQ = OP_SEQ + 1"
		." 	{$basic_wh}"
		."		And OP_SEQ >= '" . mres($OP_SEQ) . "'"
		." 	Order By OP_SEQ Asc"
		;
	sql_query($sql);


	# insert
	$sql = "Insert Into ORG_PART("
		."OP_GUBUN"
		.", OP_PARENT_IDX"
		.", OP_SEQ"
		.", OP_NAME"
		.", OP_WDATE"
		.", OP_WIP"
		.") values("
		."'E'"
		.", '" . mres($OP_PARENT_IDX) . "'"
		.", '" . mres($OP_SEQ) . "'"
		.", '" . mres($OP_NAME) . "'"
		.", now()"
		.", inet_aton('" . mres($_SERVER['REMOTE_ADDR']) . "')"
		.")"
		;
	sql_query($sql);


	echo_json(array(
		'success'=>(boolean)true, 
		'redir'=>'org_part.list.php?'.$phpTail
	));
?>
