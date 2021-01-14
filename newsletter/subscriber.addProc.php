<?php
	include_once("./_common.php");
	include_once(G5_CAPTCHA_PATH.'/captcha.lib.php');


	if(!chk_captcha()) {
		echo_json(array(
			'success'=>(boolean)false,
			'msg'=>"자동등록방지 숫자가 틀렸습니다."
		));
	}


	# set : variables
	$NM_NAME = $_POST['NM_NAME'];
	$NM_EMAIL = $_POST['NM_EMAIL'];


	# re-define
	$NM_NAME = trim($NM_NAME);
	$NM_EMAIL = trim($NM_EMAIL);


	#----- chk : rfv.
	$rfv = array();
	if ($NM_NAME == '') $rfv[] = ['str'=>'이름을 입력해 주세요.'];
	if ($NM_EMAIL == '') $rfv[] = ['str'=>'이메일을 입력해 주세요.'];

	for ($i = 0; $i < Count($rfv); $i++)
	{
		echo_json(array(
			'success'=>(boolean)false,
			'msg'=>$rfv[$i]
		));
	}
	#####


	$sql = "Select NM.NM_IDX"
		."	From NX_NEWSLETTER_MEMBER As NM"
		."	Where NM.NM_DDATE is null And NM.NM_EMAIL = '" . mres($NM_EMAIL) . "'"
		;
	$row = sql_query($sql);
	if($rs1 = sql_fetch_array($row)) {
		$NM_IDX = $rs1['NM_IDX'];


		echo_json(array(
			'success'=>(boolean)false,
			'msg'=>"이미 등록된 이메일입니다."
		));
	}


	mt_srand((double)microtime() * 1000000);

	$NM_CODE = "";
	$F_bo = true;

	while($F_bo) {
		for($i = 1; $i <= 32; $i++) {
			$tmp = mt_rand(0, 35);

			if($tmp < 10) {
				$NM_CODE .= chr($tmp + 48);
			} else {
				$NM_CODE .= chr($tmp + 87);
			}
		}

		$sql = "Select Count(*) From NX_NEWSLETTER_MEMBER Where NM_CODE = '" . mres($NM_CODE) . "'";
		$db2 = sql_fetch($sql);
		$rs2 = sql_fetch_array($db2);

		if($rs2[0] > 0) {
			$NM_CODE = "";
		} else {
			$F_bo = false;
		}

		unset($db2, $rs2);
	}


	# insert : NX_NEWSLETTER_MEMBER
	$sql = "Insert Into NX_NEWSLETTER_MEMBER("
		."NM_CODE"
		.", NM_NAME"
		.", NM_EMAIL"
		.", NM_WDATE"
		.", NM_WIP"
		.") values("
		."'" . mres($NM_CODE) . "'"
		.", '" . mres($NM_NAME) . "'"
		.", '" . mres($NM_EMAIL) . "'"
		.", now()"
		.", inet_aton('" . mres($_SERVER['REMOTE_ADDR']) . "')"
		.")"
		;
	sql_query($sql);


	echo_json(array(
		'success'=>(boolean)true,
		'msg'=>"구독이 신청되었습니다.",
		'redir'=>'../'
	));
?>
