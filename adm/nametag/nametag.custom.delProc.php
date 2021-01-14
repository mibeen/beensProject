<?php
	$sub_menu = "990600";
	include_once('./_common.php');
	include_once('./nametag.custom.err.php');

	if ($ret = auth_check($auth[$sub_menu], "d", true, true)) {
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>$ret
		));
	}


	# set : variables
	$ENC_IDX = $_POST['ENC_IDX'];
	$ENT_IDX = $_POST['ENT_IDX'];

	
	# re-define
	$ENC_IDX = CHK_NUMBER($ENC_IDX);
	$ENT_IDX = CHK_NUMBER($ENT_IDX);
	

	# chk : rfv.
	if ($ENT_IDX <= 0) {
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>"필수 항목이 누락되었습니다."
		));
	}


	# get data
	$db1 = sql_fetch("Select Count(ENT_IDX) as cnt From NX_EVENT_NAMETAG_TEMPLATE Where ENT_DDATE is null And ENT_IDX = '" . mres($ENT_IDX) . "'");
	if ($db1['cnt'] <= 0) {
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>"요청한 항목이 존재하지 않습니다."
		));
	}
	unset($db1);


	$bo_table = "NX_EVENT_NAMETAG_TEMPLATE";


	#----- delete : files
	$sql = "Select * From {$g5['board_file_table']} Where bo_table = '" . mres($bo_table) . "' And wr_id = '" . mres($ENT_IDX) . "' Order By bf_no Asc";
	$db1 = sql_query($sql);

	$s = 0;
	while ($rs1 = sql_fetch_array($db1)) {
		@unlink(G5_DATA_PATH.'/file/'.$bo_table.'/'.$rs1['bf_file']);

		// 썸네일삭제
		if(preg_match("/\.({$config['cf_image_extension']})$/i", $rs1['bf_file'])) {
		    delete_board_thumbnail($bo_table, $rs1['bf_file']);
		}

		$s++;
	}

	if ($s > 0)
	{
		# delete file record
		$sql = "Delete From {$g5['board_file_table']} Where bo_table = '" . mres($bo_table) . "' And wr_id = '" . mres($ENT_IDX) . "'";
		sql_query($sql);

		$sql = "Optimize table `{$g5['board_file_table']}`";
		sql_query($sql);
	}
	#####


	# update : deldate
	$sql = "Update NX_EVENT_NAMETAG_TEMPLATE Set ENT_DDATE = now(), ENT_DIP = inet_aton('" . mres($_SERVER['REMOTE_ADDR']) . "') Where ENT_IDX = '" . mres($ENT_IDX) . "' Limit 1";
	sql_query($sql);


	$sql = "Update NX_EVENT_NAMETAG_CUSTOM Set ENC_DDATE = now(), ENC_DIP = inet_aton('" . mres($_SERVER['REMOTE_ADDR']) . "') Where ENC_IDX = '" . mres($ENC_IDX) . "' Limit 1";
	sql_query($sql);


	echo_json(array(
		'success'=>(boolean)true, 
		'redir'=>'nametag.custom.list.php'
	));
?>
