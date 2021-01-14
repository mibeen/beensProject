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
	$NS_IDX = $_POST['NS_IDX'];


	# re-define
	$NS_IDX = (is_numeric($NS_IDX)) ? (int)$NS_IDX : "";


	$sql = "Select NS.NS_TITLE, NS.NS_CONTENT"
		."	From NX_NEWSLETTER_SEND As NS"
		."	Where NS.NS_IDX = '" . mres($NS_IDX) . "'"
		;
	$row = sql_query($sql);
	if (sql_num_rows($row) <= 0) {
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>"요청하신 정보가 존재하지 않습니다."
		));
	}
	$rs1 = sql_fetch_array($row);
	$NS_TITLE = $rs1['NS_TITLE'];
	$NS_CONTENT = $rs1['NS_CONTENT'];

	
	include_once(G5_PLUGIN_PATH.'/nx_mail/nx.mail.directsend.php');

	$subject = $NS_TITLE;
	$body = $NS_CONTENT;


	F_NEWSLETTER_SEND($NS_IDX, null, $config['cf_admin_email'], $subject, $body);


	echo_json(array(
		'success'=>(boolean)true
	));
?>
