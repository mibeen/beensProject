<?php
	$sub_menu = "990310";
	include_once('_common.php');

	if ($ret = auth_check($auth[$sub_menu], "r", true, true)) {
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>$ret
		));
	}


	$OP_IDX = $_POST['OP_IDX'];


	$sql = "Select OP.OP_IDX, OP.OP_NAME"
		."		From ORG_PART As OP"
		."	Where OP.OP_DDATE is null And OP.OP_PARENT_IDX = '" . mres($OP_IDX) . "'"
		."	Order By OP.OP_SEQ Asc, OP.OP_IDX Asc"
		;
	$db1 = sql_query($sql);

	$ret = array();
	$ret['success'] = (boolean)true;
	while ($rs1 = sql_fetch_array($db1)) {
		$ret['itms'][] = array(
			'OP_IDX' => $rs1['OP_IDX'], 
			'OP_NAME' => F_hsc($rs1['OP_NAME'])
			);
	} unset($rs1, $db1);


	echo_json($ret);
?>
