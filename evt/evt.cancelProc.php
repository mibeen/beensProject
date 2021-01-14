<?php
	include_once("./_common.php");


	# set : variables
	$EM_IDX = $_POST['EM_IDX'];


	# re-define
	$EM_IDX = CHK_NUMBER($EM_IDX);


	#----- chk : rfv.
	$rfv = array();
	
	if (is_null($member['mb_id'])) $rfv[] = "로그인 후 신청 가능 합니다.";
	if ($EM_IDX <= 0) $rfv[] = "잘못된 접근 입니다.";
	
	for ($i = 0; $i < Count($rfv); $i++) {
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>$rfv[$i]
		));
	}
	#####


	# 신청 이력 조회
	$row = sql_fetch(
		"Select EJ.EJ_IDX, EJ.EJ_NAME, EJ.EJ_EMAIL, EJ.EJ_MOBILE"
		."		, EM.EM_JOIN_TYPE, EM.EM_NOTI_EMAIL, EM.EM_NOTI_SMS, EM.EM_TITLE"
		."		, FL.bf_file, bf_source"
		."	From NX_EVENT_JOIN As EJ"
		."		Inner Join NX_EVENT_MASTER As EM On EM.EM_IDX = EJ.EM_IDX"
		."			And EM.EM_DDATE is null"
		."		Left Join {$g5['board_file_table']} As FL On FL.bo_table = 'NX_EVENT_MASTER' And FL.wr_id = EM.EM_IDX And FL.bf_no = '1'"
		."	Where EJ.EJ_DDATE is null"
		."		And EJ.EM_IDX = '" . mres($EM_IDX) . "'"
		."		And EJ.mb_id = '" . mres($member['mb_id']) . "'"
		."	Order By EJ.EJ_IDX Desc"
		."	Limit 1"
		);
	if (is_null($row['EJ_IDX']))
	{
		unset($row);
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>"신청 이력이 존재하지 않습니다."
		));
	}
	$DB_EVT = $row;
	unset($row);
	

	include "inc.evt.cancel.php";


	#----- email 발송
	if ($DB_EVT['EM_NOTI_EMAIL'] == 'Y')
	{
		#----- 이미지 디렉토리와, 썸네일 대상 디렉토리
		include_once(G5_LIB_PATH.'/thumbnail.lib.php'); //썸네일 라이브러리 로딩

		$s_path = G5_PATH . "/data/file/NX_EVENT_MASTER"; 
		$t_path = G5_PATH . "/data/file/NX_EVENT_MASTER"; 

		# 썸네일 생성
		$thumb = thumbnail($DB_EVT['bf_file'], $s_path, $t_path, 704, 396, true);
		#####


		include_once(G5_LIB_PATH.'/mailer.lib.php');

		# mail title
		$m_subject = $DB_EVT['EJ_NAME'] . ' 온라인 신청이 취소되었습니다.';

		# mail body
		include 'mail.body.cancel.php';
		$m_cont = str_replace('[#MAIN_IMG#]', (($_SERVER['HTTPS'] == 'on') ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/data/file/NX_EVENT_MASTER/'.$thumb, $_t);
		$m_cont = str_replace('[#EM_TITLE#]', $DB_EVT['EM_TITLE'], $m_cont);
		$m_cont = str_replace('[#LINK#]', (($_SERVER['HTTPS'] == 'on') ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/evt/evt.read.php?EM_IDX=' . $EM_IDX, $m_cont);
		
		mailer($config['cf_admin_email_name'], $config['cf_admin_email'], $DB_EVT['EJ_EMAIL'], $m_subject, $m_cont, 1);
		
		unset($m_subject, $m_cont);
		unset($s_path, $t_path, $thumb);
	}
	#####


	#----- SMS 발송 
	if ($DB_EVT['EM_NOTI_SMS'] == 'Y')
	{
		include_once(G5_PLUGIN_PATH.'/nx_sms/class.DR_SMS.php');
		
		DR_SMS::SEND(array(
			'SCHEDULE_TYPE' => '0',
			'SMS_MSG' => $DB_EVT['EJ_NAME'] . ' 온라인 신청이 취소되었습니다.',
			'CALLEE_NO' => $DB_EVT['EJ_MOBILE']
		));
	}
	#####


	echo_json(array(
		'success'=>(boolean)true,
		'msg'=>"행사신청이 취소되었습니다.",
		'redir'=>'evt.read.php?EM_IDX='.$EM_IDX
	));
?>
