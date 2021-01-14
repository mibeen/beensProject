<?php
	$sub_menu = "990100";
	include_once('./_common.php');

	auth_check($auth[$sub_menu], "w");


	# set : variables
	$EM_IDX = $_POST['EM_IDX'];
	$ENT_IDX = $_POST['ENT_IDX'];


	# re-define
	$EM_IDX = CHK_NUMBER($EM_IDX);
	$ENT_IDX = CHK_NUMBER($ENT_IDX);


	#----- chk : rfv.
	$rfv = array();
	if ($EM_IDX < 0) $rfv[] = ['str'=>'잘못된 접근 입니다.'];
	if ($ENT_IDX < 0) $rfv[] = ['str'=>'잘못된 접근 입니다.'];

	for ($i = 0; $i < Count($rfv); $i++)
	{
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>$rfv[$i]
		));
	}
	#####


	# chk data : master
	$db1 = sql_fetch("Select Count(EM_IDX) as cnt From NX_EVENT_MASTER Where EM_DDATE is null And EM_IDX = '" . mres($EM_IDX) . "'");
	if ($db1['cnt'] <= 0) {
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>"요청한 항목이 존재하지 않습니다."
		));
	}
	unset($db1);


	# chk data : template
	$db1 = sql_fetch("Select Count(ENT_IDX) as cnt From NX_EVENT_NAMETAG_TEMPLATE Where ENT_DDATE is null And ENT_IDX = '" . mres($ENT_IDX) . "'");
	if ($db1['cnt'] <= 0) {
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>"요청한 항목이 존재하지 않습니다."
		));
	}
	unset($db1);


	$sql = "Update NX_EVENT_MASTER Set"
		." ENT_IDX = '" . mres($ENT_IDX) . "'"
		.", EM_MDATE = now()"
		.", EM_MIP = inet_aton('" . mres($_SERVER['REMOTE_ADDR']) . "')"
		." Where EM_IDX = '" . mres($EM_IDX) . "'"
		." Limit 1"
		;
	sql_query($sql);


	echo_json(array(
		'success'=>(boolean)true, 
		'redir'=>'evt.list.php',
		'msg'=>"저장되었습니다."
	));
?>
