<?php
	include_once("./_common.php");
	include_once G5_PLUGIN_PATH . '/nx/class.NX_LOGIN_FAIL.php';

	
	$mb_no = $_POST['mb_no'];


	$ret = LOGIN_FAIL::SET_DEL(array('mb_no' => $mb_no));


	echo_json($ret);
?>