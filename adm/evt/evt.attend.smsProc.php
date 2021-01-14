<?php
	include_once('./_common.php');
	include_once('./evt.err.php'); 	# $sub_menu = ($EP_IDX > 0) ? '980100' : '990100'

	if ($ret = auth_check($auth[$sub_menu], "w", true, true)) {
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>$ret
		));
	}


	# set : variables
	$EM_IDX = $_POST['EM_IDX'];
	$t_idxs = $_POST['t_idxs'];
	$ESH_TITLE = $_POST['ESH_TITLE'];
	$ESH_CONT = $_POST['ESH_CONT'];


	# re-define
	$EM_IDX = CHK_NUMBER($EM_IDX);


	# chk : rfv.
	if ($t_idxs == '') {
		echo_json(array(
			'success'=>(boolean)false,
			'msg'=>"대상 정보가 전달되지 않았습니다."
		));
	}


	# wh : 승인된 신청자 중에서 보임
	$wh = "Where EJ.EJ_DDATE is null And EJ.EM_IDX = '" . mres($EM_IDX) . "' And EJ.EJ_STATUS = '2'";


	# wh : sms 발송 대상 참석자 목록
	$_tstr = array();
	$_ts = explode('|', $t_idxs);
	for ($i = 0; $i < Count($_ts); $i++) {
		$_t = $_ts[$i];
		if (CHK_NUMBER($_t) <= 0) continue;

		$_tstr[] = $_t;
	}

	$t_wh = '';
	if (Count($_tstr) > 0) {
		$t_wh = "'" . implode("','", $_tstr) . "'";
		$wh .= " And EJ.EJ_IDX in ({$t_wh})";
	}


	$sql = "Select EJ.EJ_IDX, EJ.EJ_NAME, EJ.EJ_MOBILE, EJ.mb_id"
		."	From NX_EVENT_JOIN As EJ"
		."		Inner Join NX_EVENT_MASTER As EM On EM.EM_IDX = EJ.EM_IDX"
		."			And EM.EM_DDATE is null"
		."	{$wh}"
		."	Order By EJ.EJ_IDX Desc"
		;
	$db1 = sql_query($sql);

	if (sql_num_rows($db1) <= 0) {
		unset($db1);
		echo_json(array(
			'success'=>(boolean)false,
			'msg'=>"대상 정보가 존재하지 않습니다."
		));
	}


	# sms plugin include
	include_once(G5_PLUGIN_PATH.'/nx_sms/class.DR_SMS.php');
	

	$ESH_TARGET = '';
	while ($rs1 = sql_fetch_array($db1))
	{
		if ($rs1['EJ_MOBILE'] == '') continue;
		#----- SMS 발송 //DR_SMS
		DR_SMS::SEND(array(
			'SCHEDULE_TYPE'=>'0',
			'SMS_MSG'=>$ESH_CONT,
			'CALLEE_NO'=>str_replace('-', '', $rs1['EJ_MOBILE'])
		));

		if ($ESH_TARGET != '') $ESH_TARGET .= '|';
		$ESH_TARGET .= $rs1['mb_id'] . '^' . $rs1['EJ_NAME'] . '^' . str_replace('-', '', $rs1['EJ_MOBILE']);
	}


	# insert : sms history
	$sql = "Insert Into NX_EVT_SMS_HIST("
		."EM_IDX"
		.", ESH_TARGET"
		.", ESH_TITLE"
		.", ESH_CONT"
		.", ESH_WDATE"
		.", ESH_WIP"
		.") values("
		."'" . mres($EM_IDX) . "'"
		.", '" . mres($ESH_TARGET) . "'"
		.", '" . mres($ESH_TITLE) . "'"
		.", '" . mres($ESH_CONT) . "'"
		.", now()"
		.", inet_aton('" . mres($_SERVER['REMOTE_ADDR']) . "')"
		.")"
		;
	sql_query($sql);


	echo_json(array(
		'success'=>(boolean)true
	));
?>
