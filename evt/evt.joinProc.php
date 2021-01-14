<?php
	include_once("./_common.php");


	# set : variables
	$EM_IDX = $_POST['EM_IDX'];
	$EJ_NAME = $_POST['EJ_NAME'];
	$EJ_MOBILE1 = $_POST['EJ_MOBILE1'];
	$EJ_MOBILE2 = $_POST['EJ_MOBILE2'];
	$EJ_MOBILE3 = $_POST['EJ_MOBILE3'];
	$EJ_EMAIL = $_POST['EJ_EMAIL'];
	$EJ_ORG = $_POST['EJ_ORG'];
	$EM_REQUIRE_BIRTH_YN = $_POST['EM_REQUIRE_BIRTH_YN'];

	$EJ_BIRTH_YY = $_POST['EJ_BIRTH_YY'];
	$EJ_BIRTH_MM = $_POST['EJ_BIRTH_MM'];
	$EJ_BIRTH_DD = $_POST['EJ_BIRTH_DD'];

	$REJOIN = $_POST['REJOIN'];


	# re-define
	$EM_IDX = CHK_NUMBER($EM_IDX);
	$EJ_MOBILE = "{$EJ_MOBILE1}-{$EJ_MOBILE2}-{$EJ_MOBILE3}";
	if ($EJ_MOBILE == '--') $EJ_MOBILE = "";

	$EJ_BIRTH = "{$EJ_BIRTH_YY}-{$EJ_BIRTH_MM}-{$EJ_BIRTH_DD}";
	if (!preg_match('/[0-9]{4}-[0-9]{2}-[0-9]{2}/', $EJ_BIRTH)) $EJ_BIRTH = "";


	#----- chk : rfv.
	$rfv = array();
	
	if ($member['mb_id'] == '') $rfv[] = "로그인 후 신청 가능 합니다.";
	if ($EM_IDX <= 0) $rfv[] = "잘못된 접근 입니다.";
	if ($EJ_MOBILE == '') $rfv[] = "연락처 정보를 입력해 주세요.";
	if ($EJ_EMAIL == '') $rfv[] = "이메일 정보를 입력해 주세요.";
	if ($EJ_ORG == '') $rfv[] = "소속 정보를 입력해 주세요.";
	if ($EM_REQUIRE_BIRTH_YN == 'Y' && $EJ_BIRTH == '') $rfv[] = "생년월일을 입력해 주세요.";

	for ($i = 0; $i < Count($rfv); $i++) {
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>$rfv[$i]
		));
	}
	#####


	/*
	CNT1 : 전체 신청자
	CNT2 : 승인 신청자
	CNT3 : 승인 + 참석 신청자
	CNT4 : 내가 신청했는지 여부
	*/	
	# chk : record
	$rs1 = sql_fetch(
		"Select EM.EM_IDX, EM.EM_S_DATE, EM.EM_JOIN_PREFIX, EM.EM_JOIN_TYPE, EM.EM_JOIN_MAX, EM.EM_TITLE, EM.EM_CG_TEL"
		."		, EM.EM_JOIN_S_DATE, EM.EM_JOIN_E_DATE, EM.EM_NOTI_EMAIL, EM.EM_NOTI_SMS"
		."		, (Select Count(EJ_IDX) From NX_EVENT_JOIN Where EM_IDX = EM.EM_IDX And EJ_DDATE is null) As CNT1"
		."		, (Select Count(EJ_IDX) From NX_EVENT_JOIN Where EM_IDX = EM.EM_IDX And EJ_DDATE is null And EJ_STATUS = '2') As CNT2"
		."		, (Select Count(EJ_IDX) From NX_EVENT_JOIN Where EM_IDX = EM.EM_IDX And EJ_DDATE is null And EJ_STATUS = '2' And (EJ_JOIN_CHK1 = 'Y' And EJ_JOIN_CHK2 = 'Y')) As CNT3"
		."		, (Select Count(EJ_IDX) From NX_EVENT_JOIN Where EM_IDX = EM.EM_IDX And EJ_DDATE is null And mb_id = '" . mres($member['mb_id']) . "') As CNT4"
		."		, (Select EJ_IDX From NX_EVENT_JOIN Where EM_IDX = EM.EM_IDX And EJ_DDATE is null And mb_id = '" . mres($member['mb_id']) . "' Order By EJ_IDX Desc Limit 1) As EJ_IDX"
		."		, FL.bf_file, bf_source"
		."	From NX_EVENT_MASTER As EM"
		."		Left Join {$g5['board_file_table']} As FL On FL.bo_table = 'NX_EVENT_MASTER' And FL.wr_id = EM.EM_IDX And FL.bf_no = '1'"
		."	Where EM.EM_DDATE is null"
		."		And EM.EM_IDX = '" . mres($EM_IDX) . "'"
		."	Limit 1"
	);
	if (is_null($rs1['EM_IDX'])) {
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>"존재하지 않는 정보 입니다."
		));
	}
	$DB_EVT = $rs1;
	unset($rs1);


	#----- 신청방식에 따른 rfv chk.
	# 선착순
	if ($DB_EVT['EM_JOIN_TYPE'] == '1') {
		# 자동승인된 인원이 인원제한에 도달했을 경우 신청 불가
		/*if ($DB_EVT['EM_JOIN_MAX'] <= $DB_EVT['CNT2']) {
			echo_json(array(
				'success'=>(boolean)false,
				'msg'=>"최대 인원이 신청되어, 추가 신청할 수 없습니다."
			));
		}*/

		# 자동승인된 인원이 인원제한에 도달했을 경우 추가 신청자는 대기 상태로 표시됨
		if ($DB_EVT['EM_JOIN_MAX'] <= $DB_EVT['CNT2']) {
			# 미승인 상태 등록
			$EJ_STATUS = '1';
		}
		else {
			# 승인 상태 등록
			$EJ_STATUS = '2';
		}
	}
	else {
		# 미승인 상태 등록
		$EJ_STATUS = '1';
	}
	#####


	#----- 접수기간 chk.
	# 접수기간 = 신청하기
	if ($DB_EVT['EM_JOIN_S_DATE'] <= date('Y-m-d H:i') && $DB_EVT['EM_JOIN_E_DATE'] >= date('Y-m-d H:i')) {
		# 한번만 접수 가능
		if ($DB_EVT['CNT4'] > 0 && $REJOIN != 'Y') {
			echo_json(array(
				'success'=>(boolean)false,
				'msg'=>"이미 신청한 행사 입니다."
			));
		}
	}
	# 접수기간 종료 = 마감
	else if ($DB_EVT['EM_JOIN_E_DATE'] < date('Y-m-d H:i')) {
		echo_json(array(
			'success'=>(boolean)false,
			'msg'=>"행사가 마감 되었습니다."
		));
	}
	# 기타 = 접수준비중
	else {
		echo_json(array(
			'success'=>(boolean)false,
			'msg'=>"접수 준비중 입니다."
		));
	}
	#####


	$_EM_S_DATE = substr(str_replace('-', '', $DB_EVT['EM_S_DATE']), 2, 6);


	#----- get : 신청자 max
	$row = sql_fetch(
		"Select (ifnull(max(convert(substring(EJ_JOIN_CODE, " . (8 + strlen($DB_EVT['EM_JOIN_PREFIX'])) . "), unsigned)), '000') + 1) As max"
		."	From NX_EVENT_JOIN"
		."	Where EM_IDX = '" . mres($EM_IDX) . "'"
		." 		And EJ_JOIN_CODE like '" . mres($_EM_S_DATE.'-'.$DB_EVT['EM_JOIN_PREFIX']) . "%'"
	);
	
	if ($row['max'] < 10) $EJ_MAX = '00'.$row['max'];
	else if ($row['max'] < 100) $EJ_MAX = '0'.$row['max'];
	else $EJ_MAX = $row['max'];
	unset($row);
	#####


	# 폼빌더 내용 입력 검증
	include "evt.join.form.itemRfv.php";


	# 재신청 - 기존 정보 삭제
	if ($DB_EVT['CNT4'] > 0 && $REJOIN == 'Y' && (int)$DB_EVT['EJ_IDX'] > 0) {
		include "inc.evt.cancel.php";
	}


	# 접수번호 생성
	$EJ_JOIN_CODE = $_EM_S_DATE . '-' . $DB_EVT['EM_JOIN_PREFIX'] . $EJ_MAX;


	# 접수번호 rfv.
	# 최소 구성 (171103-A001)
	if (strlen($EJ_JOIN_CODE) < 11) {
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>"접수번호 생성 오류 입니다.\n\n시스템 관리자에게 문의해 주세요."
		));
	}


	# insert : 행사신청
	$sql = "Insert Into NX_EVENT_JOIN("
		."EM_IDX"
		.", mb_id"
		.", EJ_NAME"
		.", EJ_JOIN_CODE"
		.", EJ_MOBILE"
		.", EJ_EMAIL"
		.", EJ_ORG"
		.", EJ_BIRTH"
		.", EJ_STATUS"
		.", EJ_WDATE"
		.", EJ_WIP"
		.") values("
		."'" . mres($EM_IDX) . "'"
		.", '" . mres($member['mb_id']) . "'"
		.", '" . mres($EJ_NAME) . "'"
		.", '" . mres($EJ_JOIN_CODE) . "'"
		.", '" . mres($EJ_MOBILE) . "'"
		.", '" . mres($EJ_EMAIL) . "'"
		.", '" . mres($EJ_ORG) . "'"
		.", " . (($EJ_BIRTH != '') ? "'" . mres($EJ_BIRTH) . "'" : 'null')
		.", '" . mres($EJ_STATUS) . "'"
		.", now()"
		.", inet_aton('" . mres($_SERVER['REMOTE_ADDR']) . "')"
		.")"
		;
	sql_query($sql);


	# set : EJ_IDX
	$EJ_IDX = sql_insert_id();


	# EJ_IDX chk
	if ((int)$EJ_IDX <= 0) {
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>"접수번호 생성 오류 입니다.\n\n시스템 관리자에게 문의해 주세요."
		));
	}


	include "evt.join.form.itemProc.php";


	#----- 메일 발송시에 사용하기 위해서 썸네일 return
	include_once(G5_LIB_PATH.'/thumbnail.lib.php'); //썸네일 라이브러리 로딩

	$s_path = G5_PATH . "/data/file/NX_EVENT_MASTER"; 
	$t_path = G5_PATH . "/data/file/NX_EVENT_MASTER"; 

	# 썸네일 생성
	$thumb = thumbnail($DB_EVT['bf_file'], $s_path, $t_path, 704, 396, true);
	#####

	
	# 자동 승인
	if ($DB_EVT['EM_JOIN_TYPE'] == '1')
	{
		# mail body
		include 'mail.body.accept.php';

		$mailVal = ['title'=> $EJ_NAME . ' 온라인 신청이 확정 되었습니다.', 'cont'=>$_t];
		$smsVal = ['cont'=> $EJ_NAME . ' 온라인 신청이 확정되었습니다. (등록코드 : '.$EJ_JOIN_CODE.')'];
	}
	# 관리자 승인 일 경우 '접수' email/sms 발송
	else {
		# mail body
		include 'mail.body.apply.php';
		
		$mailVal = ['title'=> $EJ_NAME . ' 온라인 신청이 완료 되었습니다.', 'cont'=>$_t];
		$smsVal = ['cont'=> $EJ_NAME . ' 온라인 신청이 완료 되었습니다. (등록코드 : '.$EJ_JOIN_CODE.')'];
	}


	#----- email 발송
	if ($DB_EVT['EM_NOTI_EMAIL'] == 'Y')
	{
		include_once(G5_LIB_PATH.'/mailer.lib.php');

		$m_cont = str_replace('[#MAIN_IMG#]', (($_SERVER['HTTPS'] == 'on') ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/data/file/NX_EVENT_MASTER/'.$thumb, $mailVal['cont']);
		$m_cont = str_replace('[#EM_TITLE#]', $DB_EVT['EM_TITLE'], $m_cont);
		$m_cont = str_replace('[#EJ_JOIN_CODE#]', $EJ_JOIN_CODE, $m_cont);
		$m_cont = str_replace('[#LINK#]', (($_SERVER['HTTPS'] == 'on') ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/evt/evt.read.php?EM_IDX=' . $EM_IDX, $m_cont);

		mailer($config['cf_admin_email_name'], $config['cf_admin_email'], $EJ_EMAIL, $mailVal['title'], $m_cont, 1);
	}
	#####


	#----- SMS 발송 //DR_SMS
	if ($DB_EVT['EM_NOTI_SMS'] == 'Y')
	{
		include_once(G5_PLUGIN_PATH.'/nx_sms/class.DR_SMS.php');
		
		DR_SMS::SEND(array(
			'SCHEDULE_TYPE' => '0',
			'SMS_MSG' => $smsVal['cont'],
			'CALLEE_NO' => $EJ_MOBILE
		));
	}
	#####


	/*	행사 담당자에게 SMS 알림
		- 첫 1명일때 알림
		- 10명 단위로 알림
		- 인원제한이 있을 경우 인원 충족시 알림
	*/
	$_cnt = (int)($DB_EVT['CNT1'] + 1);
	$_msg = '';
	
	# 첫 1명일때
	if ($_cnt == '1') {
		$_msg = utf8_strcut($DB_EVT['EM_TITLE'], 20)."\r\n행사신청이 접수되었습니다.\r\n현재신청자 : ".number_format($_cnt).'명';
	}
	# 10명 단위
	else if ($_cnt % 10 == 0) {
		$_msg = utf8_strcut($DB_EVT['EM_TITLE'], 20)."\r\n행사신청이 접수되었습니다.\r\n현재신청자 : ".number_format($_cnt).'명';
	}
	# 제한인원 충족시
	else if ($DB_EVT['EM_JOIN_MAX'] == $_cnt) {
		$_msg = utf8_strcut($DB_EVT['EM_TITLE'], 20)."\r\n행사신청이 접수되었습니다.\r\n지정된 인원 신청이 완료되었습니다.";
	}

	if ($_msg != '') {
		include_once(G5_PLUGIN_PATH.'/nx_sms/class.DR_SMS.php');
		//DR_SMS
		DR_SMS::SEND(array(
			'SCHEDULE_TYPE' => '0',
			'SMS_MSG' => $_msg,
			'CALLEE_NO' => $DB_EVT['EM_CG_TEL']
		));
	}
	#####


	echo_json(array(
		'success'=>(boolean)true, 
		'msg'=>"신청이 완료되었습니다.",
		'redir'=>'evt.read.php?EM_IDX='.$EM_IDX
	));
?>
