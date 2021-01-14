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


	# re-define
	$OP_PARENT_IDX = CHK_NUMBER($OP_PARENT_IDX);


	$sql = "Select OP.OP_IDX"
		."		From ORG_PART As OP"
		."	Where OP.OP_DDATE is null And OP.OP_PARENT_IDX = '" . mres($OP_PARENT_IDX) . "'"
		;
	$db1 = sql_query($sql);

	$ret = array();
	$ret['success'] = (boolean)true;
	while ($rs1 = sql_fetch_array($db1)) {
		$ret['itms'][] = array(
			'OP_IDX' => $rs1['OP_IDX']
			);
	} unset($rs1, $db1);


	echo_json($ret);
?>
