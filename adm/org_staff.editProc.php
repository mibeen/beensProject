<?php
	$sub_menu = "990310";
	include_once('_common.php');

	include_once('org_staff.err.php');

	if ($ret = auth_check($auth[$sub_menu], "w", true, true)) {
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>$ret
		));
	}


	# set : variables
	$OS_IDX = $_POST['OS_IDX'];
	$OP_PARENT_IDX = $_POST['OP_PARENT_IDX'];
	$OP_IDX = $_POST['OP_IDX'];
	$OS_SEQ = $_POST['OS_SEQ'];
	$OS_POSITION = $_POST['OS_POSITION'];
	$OS_NAME = $_POST['OS_NAME'];
	$OS_WORK = $_POST['OS_WORK'];
	$OS_TEL = $_POST['OS_TEL'];
	$OS_EMAIL = $_POST['OS_EMAIL'];

	
	# re-define
	$OS_IDX = CHK_NUMBER($OS_IDX);
	$OP_PARENT_IDX = trim($OP_PARENT_IDX);
	$OP_IDX = CHK_NUMBER($OP_IDX);
	if ($OP_PARENT_IDX == 0) $OP_GUBUN = $OP_PARENT_IDX;
	else if (in_array($OP_PARENT_IDX, array('S', 'A', 'B', 'C'))) $OP_GUBUN = $OP_PARENT_IDX;
	else if ($OP_IDX == 0) $OP_GUBUN = '';
	else $OP_GUBUN = 'E';
	$OS_SEQ = CHK_NUMBER($OS_SEQ);
	$OS_POSITION = trim($OS_POSITION);
	$OS_NAME = trim($OS_NAME);
	$OS_WORK = trim($OS_WORK);
	$OS_TEL = trim($OS_TEL);
	$OS_EMAIL = trim($OS_EMAIL);


	# chk : rfv.
	if ($OS_IDX == '' || $OS_SEQ == '' || $OS_POSITION == '' || $OS_NAME == '') {
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>"필수 항목이 누락되었습니다."
		));
	}


	# get : OP_GUBUN
	if (!$OP_GUBUN || $OP_GUBUN == '') {
		$sql = "Select OP_GUBUN"
			." 		From ORG_PART"
			." 	Where OP_DDATE is null"
			."		And OP_IDX = '" . mres($OP_PARENT_IDX) . "'"
			." 	limit 0, 1"
			;
		$db1 = sql_query($sql);

		$rs1 = sql_fetch_array($db1);

		$OP_GUBUN = $rs1['OP_GUBUN'];

		unset($db1, $rs1);
	}


	# record chk
	$row = sql_fetch("Select OS_SEQ From ORG_STAFF Where OS_IDX = '" . mres($OS_IDX) . "'");
	$SEQ = $row['OS_SEQ'];
	unset($row);


	if ($OP_PARENT_IDX > 0 && $OP_IDX == 0) {
		$basic_wh = "Where OS_DDATE is null And OP_PARENT_IDX = '" . mres($OP_PARENT_IDX) . "' And OP_GUBUN = 'D'";
	} else if ($OP_GUBUN == 'E') {
		$basic_wh = "Where OS_DDATE is null And OP_IDX = '" . mres($OP_IDX) . "'";
	} else {
		$basic_wh = "Where OS_DDATE is null And OP_GUBUN = '" . mres($OP_GUBUN) . "'";
	}


	# 지정된 순번이 기존것보다 작을 경우
	if ($OS_SEQ < $SEQ)
	{
		# update : 전달된 SEQ 이상인 정보 +1
		$sql = "Update ORG_STAFF Set"
			." 		OS_SEQ = OS_SEQ + 1"
			." 	{$basic_wh}"
			."		And OS_SEQ >= '" . mres($OS_SEQ) . "' And OS_SEQ <= '" . mres($SEQ) . "'"
			."		And OS_IDX != '" . mres($OS_IDX) . "'"
			." 	Order By OS_SEQ Asc"
			;
		sql_query($sql);
	}
	# 전달된 순번이 기존것보다 클 경우
	else if ($OS_SEQ > $SEQ)
	{
		# update : 전달된 SEQ 이상인 정보 -1
		$sql = "Update ORG_STAFF Set"
			." 		OS_SEQ = OS_SEQ - 1"
			." 	{$basic_wh}"
			."		And OS_SEQ >= '" . mres($SEQ) . "' And OS_SEQ <= '" . mres($OS_SEQ) . "'"
			."		And OS_IDX != '" . mres($OS_IDX) . "'"
			." 	Order By OS_SEQ Asc"
			;
		sql_query($sql);
	}


	# insert
	$sql = "Update ORG_STAFF Set"
		." OP_GUBUN = '" . mres($OP_GUBUN) . "'"
		.", OP_PARENT_IDX = '" . mres($OP_PARENT_IDX) . "'"
		.", OP_IDX = '" . mres($OP_IDX) . "'"
		.", OS_SEQ = '" . mres($OS_SEQ) . "'"
		.", OS_POSITION = '" . mres($OS_POSITION) . "'"
		.", OS_NAME = '" . mres($OS_NAME) . "'"
		.", OS_WORK = '" . mres($OS_WORK) . "'"
		.", OS_TEL = '" . mres($OS_TEL) . "'"
		.", OS_EMAIL = '" . mres($OS_EMAIL) . "'"
		.", OS_MDATE = now()"
		.", OS_MIP = inet_aton('" . mres($_SERVER['REMOTE_ADDR']) . "')"
		." Where OS_IDX = '" . mres($OS_IDX) . "'"
		." Limit 1"
		;
	sql_query($sql);


	echo_json(array(
		'success'=>(boolean)true, 
		'redir'=>'org_staff.list.php?'.$phpTail
	));
?>
