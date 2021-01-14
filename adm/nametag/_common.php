<?php
	define('G5_IS_ADMIN', true);
	include_once ('../../common.php');
	include_once(G5_ADMIN_PATH.'/admin.lib.php');
	include_once(G5_ADMIN_PATH.'/admin.nx.lib.php');


	$_POST    = array_map_deep('stripslashes',  $_POST);
	$_GET     = array_map_deep('stripslashes',  $_GET);
	$_COOKIE  = array_map_deep('stripslashes',  $_COOKIE);
	$_REQUEST = array_map_deep('stripslashes',  $_REQUEST);
?>
