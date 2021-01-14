<?php
	$sub_menu = "985100";
	include_once('./_common.php');
	include_once('./hr.err.php');

	if ($ret = auth_check($auth[$sub_menu], "w", true, true)) {
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>$ret
		));
	}


	# set : variables
	$EP_IDX = $_POST['EP_IDX'];
	$EP_TITLE = $_POST['EP_TITLE'];

	$EP_S_DATE1 = $_POST['EP_S_DATE1'];
	$EP_S_DATE2 = $_POST['EP_S_DATE2'];
	$EP_S_DATE3 = $_POST['EP_S_DATE3'];

	$EP_E_DATE1 = $_POST['EP_E_DATE1'];
	$EP_E_DATE2 = $_POST['EP_E_DATE2'];
	$EP_E_DATE3 = $_POST['EP_E_DATE3'];

	$EP_MEMO = $_POST['EP_MEMO'];

	$EPM_MEMB = $_POST['EPM_MEMB'];


	# re-define
	$EP_IDX = CHK_NUMBER($EP_IDX);
	$EP_TITLE = trim($EP_TITLE);
	
	$EP_S_DATE = "{$EP_S_DATE1}-{$EP_S_DATE2}-{$EP_S_DATE3}";
	if ($EP_S_DATE == '--') $EP_S_DATE = '';
	
	$EP_E_DATE = "{$EP_E_DATE1}-{$EP_E_DATE2}-{$EP_E_DATE3}";
	if ($EP_E_DATE == '--') $EP_E_DATE = '';
	
	$EP_MEMO = trim($EP_MEMO);

	$EPM_MEMB = explode(',', $EPM_MEMB);
	$_EPM_MEMB = array();
	for ($i = 0; $i < Count($EPM_MEMB); $i++) {
		if (trim($EPM_MEMB[$i]) != '') array_push($_EPM_MEMB, trim($EPM_MEMB[$i]));
	}
	unset($EPM_MEMB);


	# chk : rfv.
	if ($EP_IDX <= 0
	 || $EP_TITLE == ''
	 || $EP_S_DATE == ''
	 || $EP_E_DATE == ''
	 || Count($_EPM_MEMB) <= 0
	) {
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>"필수 항목이 누락되었습니다."
		));
	}


	# get data
	$db1 = sql_fetch("Select EP.EP_IDX From NX_EVENT_HR As EP Where EP.EP_DDATE is null And EP.EP_IDX = '" . mres($EP_IDX) . "' Limit 1");
	if (is_null($db1['EP_IDX'])) {
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>"요청한 항목이 존재하지 않습니다."
		));
	}
	unset($db1);


	# insert : event project
	$sql = "Update NX_EVENT_HR Set"
		." EP_TITLE = '" . mres($EP_TITLE) . "'"
		.", EP_S_DATE = '" . mres($EP_S_DATE) . "'"
		.", EP_E_DATE = '" . mres($EP_E_DATE) . "'"
		.", EP_MEMO = '" . mres($EP_MEMO) . "'"
		.", EP_MDATE = now()"
		.", EP_MIP = inet_aton('" . mres($_SERVER['REMOTE_ADDR']) . "')"
		." Where EP_IDX = '" . mres($EP_IDX) . "'"
		." Limit 1"
		;
	sql_query($sql);


	# delete : event project member
	$sql = "Delete From NX_EVENT_HR_MEMBER Where EP_IDX = '" . mres($EP_IDX) . "'";
	sql_query($sql);


	# insert : event project member
	for ($i = 0; $i < Count($_EPM_MEMB); $i++) {
		$sql = "Insert Into NX_EVENT_HR_MEMBER("
			."EP_IDX"
			.", mb_id"
			.") values("
			."'" . mres($EP_IDX) . "'"
			.", '" . mres($_EPM_MEMB[$i]) . "'"
			.")"
			;
		sql_query($sql);
	}


	echo_json(array(
		'success'=>(boolean)true, 
		'redir'=>'hr.list.php?'.$phpTail
	));
?>
