<?php
	$sub_menu = "980100";
	include_once('./_common.php');
	include_once('./project.err.php');

	if ($ret = auth_check($auth[$sub_menu], "w", true, true)) {
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>$ret
		));
	}


	# set : variables
	$EP_TITLE = $_POST['EP_TITLE'];

	// $EP_S_DATE1 = $_POST['EP_S_DATE1'];
	// $EP_S_DATE2 = $_POST['EP_S_DATE2'];
	// $EP_S_DATE3 = $_POST['EP_S_DATE3'];

	// $EP_E_DATE1 = $_POST['EP_E_DATE1'];
	// $EP_E_DATE2 = $_POST['EP_E_DATE2'];
	// $EP_E_DATE3 = $_POST['EP_E_DATE3'];

	$EP_MEMO = $_POST['EP_MEMO'];
	
	$EPM_MEMB = $_POST['EPM_MEMB'];


	# re-define
	$EP_TITLE = trim($EP_TITLE);
	
	$EP_S_DATE = $_POST["EP_S_DATE"];
	// $EP_S_DATE = "{$EP_S_DATE1}-{$EP_S_DATE2}-{$EP_S_DATE3}";
	if ($EP_S_DATE == '--') $EP_S_DATE = '';
	
	$EP_E_DATE = $_POST["EP_E_DATE"];
	// $EP_E_DATE = "{$EP_E_DATE1}-{$EP_E_DATE2}-{$EP_E_DATE3}";
	if ($EP_E_DATE == '--') $EP_E_DATE = '';
	
	$EP_MEMO = trim($EP_MEMO);

	$EPM_MEMB = explode(',', $EPM_MEMB);
	$_EPM_MEMB = array();
	for ($i = 0; $i < Count($EPM_MEMB); $i++) {
		if (trim($EPM_MEMB[$i]) != '') array_push($_EPM_MEMB, trim($EPM_MEMB[$i]));
	}
	unset($EPM_MEMB);


	# chk : rfv.
	if ($EP_TITLE == ''
	 || $EP_S_DATE == ''
	 || $EP_E_DATE == ''
	 || Count($_EPM_MEMB) <= 0
	) {
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>"필수 항목이 누락되었습니다."
		));
	}


	# insert : event project
	$sql = "Insert Into NX_EVENT_PROJECT("
		."mb_id"
		.", EP_TITLE"
		.", EP_S_DATE"
		.", EP_E_DATE"
		.", EP_MEMO"
		.", EP_WDATE"
		.", EP_WIP"
		.") values("
		."'" . mres($member['mb_id']) . "'"
		.", '" . mres($EP_TITLE) . "'"
		.", '" . mres($EP_S_DATE) . "'"
		.", '" . mres($EP_E_DATE) . "'"
		.", '" . mres($EP_MEMO) . "'"
		.", now()"
		.", inet_aton('" . mres($_SERVER['REMOTE_ADDR']) . "')"
		.")"
		;
	sql_query($sql, 2);


	# set : EP_IDX
	$EP_IDX = sql_insert_id();


	# insert : event project member
	for ($i = 0; $i < Count($_EPM_MEMB); $i++) {
		$sql = "Insert Into NX_EVENT_PROJECT_MEMBER("
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
		'redir'=>'project.list.php'
	));
?>
