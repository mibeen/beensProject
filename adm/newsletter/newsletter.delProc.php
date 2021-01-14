<?php
	$sub_menu = "990400";
	include_once('./_common.php');

	if ($ret = auth_check($auth[$sub_menu], "r", true, true)) {
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>$ret
		));
	}


	# set : variables
	$NS_IDX = $_POST['NS_IDX'];


	# re-define
	$NS_IDX = (is_numeric($NS_IDX)) ? (int)$NS_IDX : "";


	$sql = "Select (Select Count(*) From NX_NEWSLETTER_TARGET Where NS_IDX = NS.NS_IDX And NT_SDATE > '') As NT_SUCCESS_CNT"
		."	From NX_NEWSLETTER_SEND As NS"
		."	Where NS.NS_IDX = '" . mres($NS_IDX) . "'"
		;
	$row = sql_query($sql);
	if (sql_num_rows($row) <= 0) {
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>"요청하신 정보가 존재하지 않습니다."
		));
	}
	$rs1 = sql_fetch_array($row);
	$NT_SUCCESS_CNT = $rs1['NT_SUCCESS_CNT'];


	if($NT_SUCCESS_CNT != 0) {
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>"1개 이상 발송된 뉴스레터는 삭제할 수 없습니다."
		));
	}


	# delete : NX_NEWSLETTER_TARGET
	$sql = "Delete From NX_NEWSLETTER_TARGET Where NS_IDX = '" . mres($NS_IDX) . "'";
	sql_query($sql);


	# delete : NX_NEWSLETTER_SEND
	$sql = "Delete From NX_NEWSLETTER_SEND Where NS_IDX = '" . mres($NS_IDX) . "'";
	sql_query($sql);


	echo_json(array(
		'success'=>(boolean)true
	));
?>
