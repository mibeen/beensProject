<?php
	$sub_menu = "990300";
	include_once('_common.php');

	if ($ret = auth_check($auth[$sub_menu], "w", true, true)) {
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>$ret
		));
	}


	# set : variables
	$OP_PARENT_IDX = $_POST['OP_PARENT_IDX'];
	$OP_IDX = $_POST['OP_IDX'];


	# re-define
	if (CHK_NUMBER($OP_PARENT_IDX) == 0) $OP_GUBUN = $OP_PARENT_IDX;
	else $OP_GUBUN = 'E';
	$OP_PARENT_IDX = CHK_NUMBER($OP_PARENT_IDX);
	$OP_IDX = CHK_NUMBER($OP_IDX);


	if ($OP_PARENT_IDX > 0 && $OP_IDX == 0) {
		$basic_wh = "Where OS_DDATE is null And OP_PARENT_IDX = '" . mres($OP_PARENT_IDX) . "' And OP_GUBUN = 'D'";
	} else if ($OP_GUBUN == 'E') {
		$basic_wh = "Where OS_DDATE is null And OP_IDX = '" . mres($OP_IDX) . "'";
	} else {
		$basic_wh = "Where OS_DDATE is null And OP_GUBUN = '" . mres($OP_GUBUN) . "'";
	}


	$sql = "Select OS_IDX"
		."		From ORG_STAFF"
		."	{$basic_wh}"
		;
	$db1 = sql_query($sql);

	$ret = array();
	$ret['success'] = (boolean)true;
	while ($rs1 = sql_fetch_array($db1)) {
		$ret['itms'][] = array(
			'OS_IDX' => $rs1['OS_IDX']
			);
	} unset($rs1, $db1);


	echo_json($ret);
?>