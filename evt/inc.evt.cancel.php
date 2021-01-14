<?php
	if (!defined('_GNUBOARD_')) exit;
	if ((int)$EM_IDX <= 0) exit;
	if ((int)$DB_EVT['EJ_IDX'] <= 0) exit;


	/*	첨부파일 삭제
		- bo_table, wr_id, bf_no 가 primary 로 잡혀 있기 때문에 삭제
	*/
	$_bo_table = 'NX_EVENT_FORM_VAL';

	$sql = "Select FI.FI_IDX"
		."		, FL.bf_file"
		."	From NX_EVENT_FORM_ITEM As FI"
		."		Left Join g5_board_file As FL On FL.bo_table = '" . mres($_bo_table) . "'"
		."			And FL.wr_id = FI.FI_IDX"
		."	Where FI.FI_DDATE is null"
		."		And FI.FI_USE_YN = 'Y'"
		."		And FI.FI_KIND = 'G'"
		."		And FI.EM_IDX = '" . mres($EM_IDX) . "'"
		."	Order By FL.bf_no Asc"
		;
	$db1 = sql_query($sql);

	$s = 0;
	while ($rs1 = sql_fetch_array($db1)) {
		@unlink(G5_DATA_PATH.'/file/'.$_bo_table.'/'.$rs1['bf_file']);

		# 썸네일삭제
		if(preg_match("/\.({$config['cf_image_extension']})$/i", $rs1['bf_file'])) {
		    delete_board_thumbnail($_bo_table, $rs1['bf_file']);
		}

		# delete file record
		$sql = "Delete From {$g5['board_file_table']} Where bo_table = '" . mres($_bo_table) . "' And wr_id = '" . mres($rs1['FI_IDX']) . "'";
		sql_query($sql);

		$s++;
	}

	if ($s > 0)
	{
		$sql = "Optimize table `{$g5['board_file_table']}`";
		sql_query($sql);
	}

	unset($_bo_table);
	#####	


	# 폼 입력항목 삭제
	$sql = "Update NX_EVENT_FORM_VAL Set"
		." FV_DDATE = now()"
		.", FV_DIP = inet_aton('" . mres($_SERVER['REMOTE_ADDR']) . "')"
		." Where EM_IDX = '" . mres($EM_IDX) . "' And EJ_IDX = '" . mres($DB_EVT['EJ_IDX']) . "'"
		;
	sql_query($sql);


	# 신청 취소
	$sql = "Update NX_EVENT_JOIN Set"
		." EJ_DDATE = now()"
		.", EJ_DIP = inet_aton('" . mres($_SERVER['REMOTE_ADDR']) . "')"
		." Where EJ_IDX = '" . mres($DB_EVT['EJ_IDX']) . "'"
		." Limit 1"
		;
	sql_query($sql);
?>