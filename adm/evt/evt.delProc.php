<?php
	include_once('./_common.php');
	include_once('./evt.err.php'); 	# $sub_menu = ($EP_IDX > 0) ? '980100' : '990100'

	if ($ret = auth_check($auth[$sub_menu], "d", true, true)) {
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>$ret
		));
	}


	# set : variables
	$EM_IDX = $_POST['EM_IDX'];

	
	# re-define
	$EM_IDX = CHK_NUMBER($EM_IDX);
	

	# chk : rfv.
	if ($EM_IDX <= 0) {
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>"필수 항목이 누락되었습니다."
		));
	}


	# get data
	$db1 = sql_fetch("Select Count(EM.EM_IDX) as cnt From NX_EVENT_MASTER As EM Where EM.EM_DDATE is null And EM.EM_IDX = '" . mres($EM_IDX) . "' Limit 1");
	if ($db1['cnt'] <= 0) {
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>"요청한 항목이 존재하지 않습니다."
		));
	}
	unset($db1);


	# delete : form item
	NX_EVENT_FORM_BUILDER::SET_DEL(array('EM_IDX'=>$EM_IDX));


	$bo_table = "NX_EVENT_MASTER";


	#----- delete : files
	$sql = "Select * From {$g5['board_file_table']} Where bo_table = '" . mres($bo_table) . "' And wr_id = '" . mres($EM_IDX) . "' Order By bf_no Asc";
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
		$sql = "Delete From {$g5['board_file_table']} Where bo_table = '" . mres($bo_table) . "' And wr_id = '" . mres($EM_IDX) . "'";
		sql_query($sql);

		$sql = "Optimize table `{$g5['board_file_table']}`";
		sql_query($sql);
	}
	#####


	# delete : master record
	$sql = "Delete From NX_EVENT_MASTER Where EM_IDX = '" . mres($EM_IDX) . "' Limit 1";
	sql_query($sql);


	# 달력에 등록된 행사 수정
	include_once G5_PLUGIN_PATH . '/nx/class.NX_EVT_CAL.php';
	NX_EVT_CAL::SET_DEL(array(
		'EM_IDX'=>(int)$EM_IDX
	));


	# re-direct
	$reDir = "evt.list.php?".$epTail;


	echo_json(array(
		'success'=>(boolean)true, 
		'redir'=>$reDir
	));
?>
