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
	$NM_CODE = $_POST['NM_CODE'];
	$NM_NAME = $_POST['NM_NAME'];


	# re-define
	$NM_CODE = trim($NM_CODE);
	$NM_NAME = trim($NM_NAME);


	$sql = "Select NM.NM_IDX"
		."	From NX_NEWSLETTER_MEMBER As NM"
		."	Where NM.NM_DDATE is null And NM.NM_CODE = '" . mres($NM_CODE) . "' And NM.NM_NAME = '" . mres($NM_NAME) . "'"
		;
	$row = sql_query($sql);
	if (sql_num_rows($row) <= 0) {
		echo_json(array(
			'success'=>(boolean)true,
			'msg'=>"구독이 이미 취소되었습니다.",
			'redir'=>'../'
		));
	}
	$rs1 = sql_fetch_array($row);
	$NM_IDX = $rs1['NM_IDX'];


	# update : NX_NEWSLETTER_MEMBER
	$sql = "Update NX_NEWSLETTER_MEMBER Set"
		. " NM_DDATE = now()"
		. ", NM_DIP = inet_aton('" . mres($_SERVER['REMOTE_ADDR']) . "')"
		. " Where NM_DDATE is null And NM_IDX = '" . mres($NM_IDX) . "'";

	sql_query($sql);


	echo_json(array(
		'success'=>(boolean)true,
		'msg'=>"구독이 취소되었습니다.\r\n그동안 이용해 주셔서 감사합니다.",
		'redir'=>'../'
	));
?>
