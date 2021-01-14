<?php
	include_once("./_common.php");


	# set : variables
	$EM_IDX = $_POST['EM_IDX'];
	$EJ_IDX = $_POST['EJ_IDX'];
	$nums = $_POST['nums'];


	# re-define
	$EM_IDX = CHK_NUMBER($EM_IDX);
	$EJ_IDX = CHK_NUMBER($EJ_IDX);


	# chk : rfv.
	if ($EM_IDX <= 0 || $nums == '') {
		echo_json(array(
			'success'=>(boolean)false,
			'msg'=>"잘못된 접근 입니다."
		));
	}


	/*	입력값 검증
		- 모든 항목의 값이 넘어왔을 경우만 등록
	*/
	$arrVal = array();

	$num_cnt = 0;
	$_ts = explode('|', $nums);
	for ($i = 0; $i < Count($_ts); $i++)
	{
		$num = $_ts[$i];
		if ($num == '') continue;
		$num_cnt++;


		$ESD_IDX = $_POST['ESD_IDX_'.$num];
		$ESV_VAL = $_POST['ESV_VAL_'.$num];

		if (CHK_NUMBER($ESD_IDX) <= 0 || CHK_NUMBER($ESV_VAL) <= 0) continue;

		$arrVal[] = array(
			'ESD_IDX'=>(int)$ESD_IDX, 
			'ESV_VAL'=>(int)$ESV_VAL
		);
	}


	# 입력 받아야 할 값과 입력한 값의 갯수 비교
	if ($num_cnt != Count($arrVal)) {
		echo_json(array(
			'success'=>(boolean)false,
			'msg'=>"모든 항목을 체크 하셔야 등록 가능 합니다."
		));
	}


	# 함께 저장되는 답변을 rndcode 로 묶음
	$rndcode = uniqid();


	for ($i = 0; $i < Count($arrVal); $i++)
	{
		$_t = $arrVal[$i];
		
		# insert
		$sql = "Insert Into NX_EVT_SATI_VAL("
			."EM_IDX"
			.", ESD_IDX"
			.", EJ_IDX"
			.", mb_id"
			.", ESV_RNDCODE"
			.", ESV_VAL"
			.", ESV_WDATE"
			.", ESV_WIP"
			.") values("
			."'" . mres($EM_IDX) . "'"
			.", '" . mres($_t['ESD_IDX']) . "'"
			.", " . (($EJ_IDX > 0) ? "'" . mres($EJ_IDX) . "'" : 'null')
			.", '" . mres($member['mb_id']) . "'"
			.", '" . mres($rndcode) . "'"
			.", '" . mres($_t['ESV_VAL']) . "'"
			.", now()"
			.", inet_aton('" . mres($_SERVER['REMOTE_ADDR']) . "')"
			.")"
			;
		sql_query($sql);
	}


	/*	같은 만족도는 한번만 응할수 있도록 cookie 생성
		- cookie 로 생성하여 검사 함으로, cookie 삭제 후 다시 만족도 조사에 응하는 부분은 의도적으로 막지 않음
	*/
	setcookie('evt_join', ($_COOKIE['evt_join'] . '|' . $EM_IDX), (time()+(60*60*24)));


	echo_json(array(
		'success'=>(boolean)true,
		'msg'=>"저장되었습니다.\n\n감사합니다."
	));
?>
