<?php
	$sub_menu = "990200";
	include_once('./_common.php');


	# set : variables
	$PM_IDX = $_POST['pm_idx'];
	$PS_IDX = $_POST['ps_idx'];


	# re-define
	$PM_IDX = preg_replace('/[^0-9]/', '', $PM_IDX);
	$PS_IDX = preg_replace('/[^0-9]/', '', $PS_IDX);


	# chk : rfv.
	if ($PM_IDX == '' || $PS_IDX == '') {
		echo_json(array(
			'success'=>(boolean)false,
			'msg'=>"필수 항목이 누락되었습니다."
		));
	}


	# chk : record exists
	$row = sql_fetch(
		"Select Count(PS_IDX) As cnt"
		."	From PLACE_RENTAL_SUB"
		."	Where PS_DDATE is null"
		."		And PM_IDX = '{$PM_IDX}'"
	);
	if ($row['cnt'] <= 0) {
		unset($row);
		echo_json(array(
			'success'=>(boolean)false,
			'msg'=>"존재하지 않는 정보 입니다."
		));
	}
	unset($row);


	#----- delete : files
	$_bo_table = 'place_rental_sub';

	$sql = "Select * From {$g5['board_file_table']} Where bo_table = '{$_bo_table}' And wr_id = '{$PS_IDX}' Order By bf_no Asc";
	$db1 = sql_query($sql);

	$s = 0;
	while ($rs1 = sql_fetch_array($db1)) {
		@unlink(G5_DATA_PATH.'/file/'.$_bo_table.'/'.$rs1['bf_file']);

		// 썸네일삭제
		if(preg_match("/\.({$config['cf_image_extension']})$/i", $rs1['bf_file'])) {
		    delete_board_thumbnail($_bo_table, $rs1['bf_file']);
		}

		$s++;
	}

	if ($s > 0)
	{
		# delete file record
		$sql = "Delete From {$g5['board_file_table']} Where bo_table = '{$_bo_table}' And wr_id = '{$PS_IDX}'";
		sql_query($sql);

		$sql = "Optimize table `{$g5['board_file_table']}`";
		sql_query($sql);
	}

	unset($_bo_table);
	#####


	# update : del date
	$sql = "Update PLACE_RENTAL_SUB Set"
		." PS_DDATE = now()"
		.", PS_DIP = '{$_SERVER['REMOTE_ADDR']}'"
		." Where PS_IDX = '{$PS_IDX}'"
		." Limit 1"
		;
	sql_query($sql);


	echo_json(array(
		'success'=>(boolean)true,
		'msg'=>"삭제되었습니다.",
		'redir'=>'./place_rental_sub_list.php?PM_IDX='.$PM_IDX
	));
?>
