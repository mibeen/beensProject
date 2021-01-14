<?php
	include_once("../common.php");
	include_once(G5_LIB_PATH.'/nx.lib.php');


	$_POST    = array_map_deep('stripslashes',  $_POST);
	$_GET     = array_map_deep('stripslashes',  $_GET);
	$_COOKIE  = array_map_deep('stripslashes',  $_COOKIE);
	$_REQUEST = array_map_deep('stripslashes',  $_REQUEST);


	$_file_table = 'local_place';


	# 예약 시간 설정

	$time_start = ['value'=>0,    'hour'=>0, 'minute'=>0, 'text'=>'0:00'];        // 시간 기본 범위 시작 (0 * 60)
    $time_end   = ['value'=>1435, 'hour'=>18, 'minute'=>0, 'text'=>'23:50'];      // 시간 기본 범위 종료 (24 * 60)
	$time_min   = ['value'=>540,  'hour'=>9, 'minute'=>0, 'text'=>'9:00'];        // 예약시작 시간 (9 * 60)
    $time_max   = ['value'=>1080, 'hour'=>18, 'minute'=>0, 'text'=>'18:00'];      // 예약마감 시간 (18 * 60)
    $time_val_gap = $time_end['value'] - $time_start['value'];
    $time_step = 10;        // 최소 단위 (분)
?>
