<?php
	$sub_menu = "990400";
	include_once('./_common.php');

	if ($ret = auth_check($auth[$sub_menu], "r", true, true)) {
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>$ret
		));
	}


	# set : variables
	$MODE = $_POST['MODE'];

	$NS_TITLE = $_POST['NS_TITLE'];
	$NS_CONTENT = $_POST['NS_CONTENT'];


	# re-define
	$MODE = (in_array($MODE, array('SEND'))) ? (string)$MODE : "";

	$NS_TITLE = trim($NS_TITLE);
	$NS_CONTENT = trim($NS_CONTENT);


	#----- chk : rfv.
	$rfv = array();
	if ($NS_TITLE == '') $rfv[] = ['str'=>'제목을 입력해 주세요.'];
	if ($NS_CONTENT == '') $rfv[] = ['str'=>'내용을 입력해 주세요.'];

	for ($i = 0; $i < Count($rfv); $i++)
	{
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>$rfv[$i]
		));
	}
	#####


	$sql = "Select Count(*) As cnt"
		."	From NX_NEWSLETTER_MEMBER As NM"
		."	Where NM.NM_DDATE is null"
		;
	$row = sql_fetch($sql);
	$total_count = $row['cnt'];

	if($total_count <= 0) {
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>'구독자가 없습니다.'
		));
	}


	# insert : NX_NEWSLETTER_SEND
	$sql = "Insert Into NX_NEWSLETTER_SEND("
		."NS_TITLE"
		.", NS_CONTENT"
		.", NS_STATUS"
		.", NS_WDATE"
		.", NS_WIP"
		.") values("
		."'" . mres($NS_TITLE) . "'"
		.", '" . mres($NS_CONTENT) . "'"
		.", 'A'"
		.", now()"
		.", inet_aton('" . mres($_SERVER['REMOTE_ADDR']) . "')"
		.")"
		;
	sql_query($sql);

	$NS_IDX = sql_insert_id();


	# insert : NX_NEWSLETTER_TARGET
	$sql = "Insert Into NX_NEWSLETTER_TARGET("
		."NS_IDX"
		.", NM_IDX"
		.", NT_EMAIL"
		.")"
		." Select"
		." '" . mres($NS_IDX) . "'"
		.", NM_IDX"
		.", NM_EMAIL"
		." From NX_NEWSLETTER_MEMBER Where NM_DDATE is null"
		;
	sql_query($sql);


	if($MODE == "SEND") {
		include_once(G5_PLUGIN_PATH.'/nx_mail/class.NX_MAIL.php');

		$body = $NS_CONTENT;

		$sendmail = new NX_MAIL(array(
			'from_email'=>$config['cf_admin_email'],
			'charset'=>'utf-8',
			'ctype'=>'text/html'
		));

		$from = '=?' . strtoupper('utf-8') . '?B?' . base64_encode($config['cf_admin_email_name']) . '?=';
		$subject = '=?' . strtoupper('utf-8') . '?B?' . base64_encode($NS_TITLE) . '?=';

		F_NEWSLETTER_SEND($NS_IDX, $sendmail, $from, $subject, $body);
	}


	echo_json(array(
		'success'=>(boolean)true, 
		'redir'=>'newsletter.list.php'
	));
?>
