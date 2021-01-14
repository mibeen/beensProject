<?php
	include_once('./_common.php');
	include_once('./evt.err.php'); 	# $sub_menu = ($EP_IDX > 0) ? '980100' : '990100'
	include_once(G5_LIB_PATH.'/mailer.lib.php');
	include_once(G5_PLUGIN_PATH.'/nx_sms/class.DR_SMS.php');


	auth_check($auth[$sub_menu], "w");

	
	# set : variables
	$m = $_POST['m'];
	$EM_IDX = $_POST['EM_IDX'];
	$v = $_POST['v'];


	# re-define
	$m = (in_array($m, array('1','2','all'))) ? (string)$m : '';
	$EM_IDX = CHK_NUMBER($EM_IDX);


	# chk : rfv.
	if ($EM_IDX <= 0 || (in_array($m, array('1','2')) && $v == '')) {
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>"필수 항목이 누락되었습니다."
		));
	}


	# get : event master
	$sql = "Select EM.EM_IDX, EM.EM_TITLE, EM.EM_NOTI_EMAIL, EM.EM_NOTI_SMS"
		."		, FL.bf_file, bf_source"
		."	From NX_EVENT_MASTER As EM"
		."		Left Join {$g5['board_file_table']} As FL On FL.bo_table = 'NX_EVENT_MASTER' And FL.wr_id = EM.EM_IDX And FL.bf_no = '1'"
		."	Where EM.EM_DDATE is null"
		."		And EM.EM_IDX = '" . mres($EM_IDX) . "'"
		."	Limit 1"
		;
	$db1 = sql_fetch($sql);
	if (is_null($db1['EM_IDX'])) {
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>"존재하지 않는 정보 입니다."
		));
	}
	$DB_EVT = $db1;
	unset($db1);



	#----- 메일 발송시에 사용하기 위해서 썸네일 return
	include_once(G5_LIB_PATH.'/thumbnail.lib.php'); //썸네일 라이브러리 로딩

	$s_path = G5_PATH . "/data/file/NX_EVENT_MASTER"; 
	$t_path = G5_PATH . "/data/file/NX_EVENT_MASTER"; 

	# 썸네일 생성
	$thumb = thumbnail($DB_EVT['bf_file'], $s_path, $t_path, 704, 396, true);
	#####


	#----- 승인 메일 내용
	include '../../evt/mail.body.accept.php';

	$mailVal = ['title'=> '[#EJ_NAME#] 온라인 신청이 확정 되었습니다.', 'cont'=>$_t];
	$smsVal = ['cont'=> '[#EJ_NAME#] 온라인 신청이 확정되었습니다. (등록코드 : [#EJ_JOIN_CODE#])'];
	#####


	# 일괄승인
	if ($m == 'all')
	{
		#----- get : 일괄승인 대상의 신청 정보
		$sql = "Select EJ_IDX, EJ_NAME, EJ_EMAIL, EJ_JOIN_CODE, EJ_MOBILE From NX_EVENT_JOIN Where EJ_DDATE is null And EM_IDX = '" . mres($EM_IDX) . "' And EJ_STATUS = '1' Order By EJ_IDX Asc";
		$db1 = sql_query($sql);

		while ($rs1 = sql_fetch_array($db1))
		{
			# 승인 처리
			$sql = "Update NX_EVENT_JOIN Set EJ_STATUS = '2' Where EJ_IDX = '" . mres($rs1['EJ_IDX']) . "' Limit 1";
			sql_query($sql);


			# mail 발송
			if ($DB_EVT['EM_NOTI_EMAIL'] == 'Y')
			{
				# email 주소가 있을 경우만
				if ($rs1['EJ_EMAIL'] != '') {
					$m_subject = str_replace('[#EJ_NAME#]', $rs1['EJ_NAME'], $mailVal['title']);
					
					$m_cont = str_replace('[#EJ_NAME#]', $rs1['EJ_NAME'], $mailVal['cont']);
					$m_cont = str_replace('[#MAIN_IMG#]', (($_SERVER['HTTPS'] == 'on') ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/data/file/NX_EVENT_MASTER/'.$thumb, $m_cont);
					$m_cont = str_replace('[#EM_TITLE#]', $DB_EVT['EM_TITLE'], $m_cont);
					$m_cont = str_replace('[#EJ_JOIN_CODE#]', $rs1['EJ_JOIN_CODE'], $m_cont);
					$m_cont = str_replace('[#LINK#]', (($_SERVER['HTTPS'] == 'on') ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/evt/evt.read.php?EM_IDX=' . $EM_IDX, $m_cont);

					mailer($config['cf_admin_email_name'], $config['cf_admin_email'], $rs1['EJ_EMAIL'], $m_subject, $m_cont, 1);
				}
			}


			# sms 발송 //DR_SMS
			if ($DB_EVT['EM_NOTI_SMS'] == 'Y')
			{
			    DR_SMS::SEND(array(
					'SCHEDULE_TYPE' => '0',
					'SMS_MSG' => str_replace(array('[#EJ_NAME#]', '[#EJ_JOIN_CODE#]'), array($rs1['EJ_NAME'], $rs1['EJ_JOIN_CODE']), $smsVal['cont']),
					'CALLEE_NO' => $rs1['EJ_MOBILE']
				));
			}
		}
		unset($db1, $rs1);
	}
	else if (in_array($m, array('1','2')))
	{
		$_ts = explode('|', $v);
		$_v = '';
		for ($i = 0; $i < Count($_ts); $i++) {
			$_t = $_ts[$i];
			if ($_t == '' || (int)$_t <= 0) continue;

			if ($_v != '') $_v .= ',';
			$_v .= "'".$_t."'";
		}

		# 선택 미승인
		if ($m == '1') {
			$sql = "Update NX_EVENT_JOIN Set EJ_STATUS = '1' Where EJ_DDATE is null And EM_IDX = '" . mres($EM_IDX) . "' And EJ_IDX in ({$_v})";
			sql_query($sql);
		}
		# 선택 승인
		else if ($m == '2') {
			#----- get : 전달된 대상의 신청 정보
			$sql = "Select EJ_IDX, EJ_NAME, EJ_EMAIL, EJ_JOIN_CODE, EJ_MOBILE From NX_EVENT_JOIN Where EJ_DDATE is null And EM_IDX = '" . mres($EM_IDX) . "' And EJ_STATUS = '1' And EJ_IDX in ({$_v})";
			$db1 = sql_query($sql);

			$jinfo = [];
			while ($rs1 = sql_fetch_array($db1)) {
				$jinfo[$rs1['EJ_IDX']] = array(
					'EJ_NAME'=>$rs1['EJ_NAME']
					, 'EJ_EMAIL'=>$rs1['EJ_EMAIL']
					, 'EJ_JOIN_CODE'=>$rs1['EJ_JOIN_CODE']
					, 'EJ_MOBILE'=>$rs1['EJ_MOBILE']
				);
			}
			unset($db1, $rs1);
			#####


			$_ts = explode('|', $v);
			for ($i = 0; $i < Count($_ts); $i++)
			{
				$_t = $_ts[$i];
				if ((int)$_t <= 0) continue;


				# 승인 처리
				$sql = "Update NX_EVENT_JOIN Set EJ_STATUS = '2' Where EJ_DDATE is null And EM_IDX = '" . mres($EM_IDX) . "' And EJ_IDX = '" . mres($_t) . "' Limit 1";
				sql_query($sql);


				# mail 발송
				if ($DB_EVT['EM_NOTI_EMAIL'] == 'Y')
				{
					# email 주소가 있을 경우만
					if (!is_null($jinfo[$_t]['EJ_EMAIL'])) {
						$m_subject = str_replace('[#EJ_NAME#]', $jinfo[$_t]['EJ_NAME'], $mailVal['title']);
						
						$m_cont = str_replace('[#EJ_NAME#]', $jinfo[$_t]['EJ_NAME'], $mailVal['cont']);
						$m_cont = str_replace('[#MAIN_IMG#]', (($_SERVER['HTTPS'] == 'on') ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/data/file/NX_EVENT_MASTER/'.$thumb, $m_cont);
						$m_cont = str_replace('[#EM_TITLE#]', $DB_EVT['EM_TITLE'], $m_cont);
						$m_cont = str_replace('[#EJ_JOIN_CODE#]', $jinfo[$_t]['EJ_JOIN_CODE'], $m_cont);
						$m_cont = str_replace('[#LINK#]', (($_SERVER['HTTPS'] == 'on') ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/evt/evt.read.php?EM_IDX=' . $EM_IDX, $m_cont);

						mailer($config['cf_admin_email_name'], $config['cf_admin_email'], $jinfo[$_t]['EJ_EMAIL'], $m_subject, $m_cont, 1);
					}
				}


				# sms 발송  //DR_SMS
				if ($DB_EVT['EM_NOTI_SMS'] == 'Y')
				{
				    DR_SMS::SEND(array(
						'SCHEDULE_TYPE' => '0',
						'SMS_MSG' => str_replace(array('[#EJ_NAME#]', '[#EJ_JOIN_CODE#]'), array($jinfo[$_t]['EJ_NAME'], $jinfo[$_t]['EJ_JOIN_CODE']), $smsVal['cont']),
						'CALLEE_NO' => $jinfo[$_t]['EJ_MOBILE']
					));
				}
			}
		}
	}


	echo_json(array(
		'success'=>(boolean)true, 
		'redir'=>'evt.join.list.php?'.$epTail.'EM_IDX='.$EM_IDX
	));
?>
