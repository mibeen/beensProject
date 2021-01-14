<?php
	include_once('./_common.php');
	include_once('./evt.err.php'); 	# $sub_menu = ($EP_IDX > 0) ? '980100' : '990100'

	if ($ret = auth_check($auth[$sub_menu], "w", true, true)) {
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>$ret
		));
	}


	$IS_COPY_MASTER = (isset($IS_COPY_MASTER) && $IS_COPY_MASTER == 'Y') ? $IS_COPY_MASTER : 'N';

	# 명찰 템플릿만 복제
	if ($IS_COPY_MASTER != 'Y') {
		# set : variables
		$EM_IDX = $_POST['EM_IDX'];
		$ENT_IDX = $_POST['ENT_IDX'];


		# re-define
		$EM_IDX = CHK_NUMBER($EM_IDX);
		$ENT_IDX = CHK_NUMBER($ENT_IDX);
	}


	#----- chk : rfv.
	$rfv = array();
	if ($ENT_IDX < 0) $rfv[] = ['str'=>'잘못된 접근 입니다.'];

	for ($i = 0; $i < Count($rfv); $i++)
	{
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>$rfv[$i]
		));
	}
	#####


	# get data
	$db1 = sql_fetch("Select Count(ENT_IDX) as cnt From NX_EVENT_NAMETAG_TEMPLATE Where ENT_DDATE is null And ENT_IDX = '" . mres($ENT_IDX) . "'");
	if ($db1['cnt'] <= 0) {
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>"요청한 항목이 존재하지 않습니다."
		));
	}
	unset($db1);


	#-----
	# em_idx 가 넘어왔을 경우 기존의 정보가 있는지 확인하여 삭제 처리
	if ($EM_IDX > 0)
	{
		$sql = "Select ENT_IDX From NX_EVENT_NAMETAG_TEMPLATE Where ENT_DDATE is null And ENT_IDX != '" . mres($ENT_IDX) . "' And EM_IDX = '" . mres($EM_IDX) . "' Order By ENT_IDX Asc";
		$db1 = sql_query($sql);

		$bo_table = "NX_EVENT_NAMETAG_TEMPLATE";

		while ($rs1 = sql_fetch_array($db1))
		{
			#----- delete : files
			$sql = "Select * From {$g5['board_file_table']} Where bo_table = '" . mres($bo_table) . "' And wr_id = '" . mres($rs1['ENT_IDX']) . "' Order By bf_no Asc";
			$db2 = sql_query($sql);

			$s = 0;
			while ($rs2 = sql_fetch_array($db2)) {
				@unlink(G5_DATA_PATH.'/file/'.$bo_table.'/'.$rs2['bf_file']);

				// 썸네일삭제
				if(preg_match("/\.({$config['cf_image_extension']})$/i", $rs2['bf_file'])) {
				    delete_board_thumbnail($bo_table, $rs2['bf_file']);
				}

				$s++;
			}
			unset($rs2, $db2);

			if ($s > 0)
			{
				# delete file record
				$sql = "Delete From {$g5['board_file_table']} Where bo_table = '" . mres($bo_table) . "' And wr_id = '" . mres($rs1['ENT_IDX']) . "'";
				sql_query($sql);

				$sql = "Optimize table `{$g5['board_file_table']}`";
				sql_query($sql);
			}
			#####


			# update : deldate
			$sql = "Update NX_EVENT_NAMETAG_TEMPLATE Set ENT_DDATE = now(), ENT_DIP = inet_aton('" . mres($_SERVER['REMOTE_ADDR']) . "') Where ENT_IDX = '" . mres($rs1['ENT_IDX']) . "' Limit 1";
			sql_query($sql);
		}
		unset($rs1, $db1);
	}
	#####


	# copy : template record
	$sql = "Insert Into NX_EVENT_NAMETAG_TEMPLATE("
		."EM_IDX"
		.", ENT_TITLE"
		.", ENT_WIDTH"
		.", ENT_HEIGHT"
		.", ENT_F1_KIND"
		.", ENT_F1_SIZE"
		.", ENT_F1_LEFT"
		.", ENT_F1_RIGHT"
		.", ENT_F1_COLOR"
		.", ENT_F1_BOLD"
		.", ENT_F1_UNDERLINE"
		.", ENT_F1_ALIGN"
		.", ENT_F1_Y"
		.", ENT_F2_KIND"
		.", ENT_F2_SIZE"
		.", ENT_F2_LEFT"
		.", ENT_F2_RIGHT"
		.", ENT_F2_COLOR"
		.", ENT_F2_BOLD"
		.", ENT_F2_UNDERLINE"
		.", ENT_F2_ALIGN"
		.", ENT_F2_Y"
		.", ENT_F3_KIND"
		.", ENT_F3_SIZE"
		.", ENT_F3_LEFT"
		.", ENT_F3_RIGHT"
		.", ENT_F3_COLOR"
		.", ENT_F3_BOLD"
		.", ENT_F3_UNDERLINE"
		.", ENT_F3_ALIGN"
		.", ENT_F3_Y"
		.", ENT_F4_SIZE"
		.", ENT_F4_LEFT"
		.", ENT_F4_RIGHT"
		.", ENT_F4_COLOR"
		.", ENT_F4_BOLD"
		.", ENT_F4_UNDERLINE"
		.", ENT_F4_ALIGN"
		.", ENT_F4_Y"
		.", ENT_WDATE"
		.", ENT_WIP"
		.")"
		." Select " . (($EM_IDX > 0) ? "'".mres($EM_IDX)."'" : 'null')
		.", concat(ENT_TITLE, '(복제)')"
		.", ENT_WIDTH"
		.", ENT_HEIGHT"
		.", ENT_F1_KIND"
		.", ENT_F1_SIZE"
		.", ENT_F1_LEFT"
		.", ENT_F1_RIGHT"
		.", ENT_F1_COLOR"
		.", ENT_F1_BOLD"
		.", ENT_F1_UNDERLINE"
		.", ENT_F1_ALIGN"
		.", ENT_F1_Y"
		.", ENT_F2_KIND"
		.", ENT_F2_SIZE"
		.", ENT_F2_LEFT"
		.", ENT_F2_RIGHT"
		.", ENT_F2_COLOR"
		.", ENT_F2_BOLD"
		.", ENT_F2_UNDERLINE"
		.", ENT_F2_ALIGN"
		.", ENT_F2_Y"
		.", ENT_F3_KIND"
		.", ENT_F3_SIZE"
		.", ENT_F3_LEFT"
		.", ENT_F3_RIGHT"
		.", ENT_F3_COLOR"
		.", ENT_F3_BOLD"
		.", ENT_F3_UNDERLINE"
		.", ENT_F3_ALIGN"
		.", ENT_F3_Y"
		.", ENT_F4_SIZE"
		.", ENT_F4_LEFT"
		.", ENT_F4_RIGHT"
		.", ENT_F4_COLOR"
		.", ENT_F4_BOLD"
		.", ENT_F4_UNDERLINE"
		.", ENT_F4_ALIGN"
		.", ENT_F4_Y"
		.", now()"
		.", inet_aton('" . mres($_SERVER['REMOTE_ADDR']) . "')"
		." From NX_EVENT_NAMETAG_TEMPLATE"
		." Where ENT_IDX = '" . mres($ENT_IDX) . "'"
		." Order By ENT_IDX Desc"
		." Limit 1"
		;
	sql_query($sql);


	# set : ENT_IDX
	$NEW_ENT_IDX = sql_insert_id();


	$bo_table = "NX_EVENT_NAMETAG_TEMPLATE";


	# 디렉토리가 없다면 생성 + 퍼미션 변경
	@mkdir(G5_DATA_PATH.'/file/'.$bo_table, G5_DIR_PERMISSION);
	@chmod(G5_DATA_PATH.'/file/'.$bo_table, G5_DIR_PERMISSION);


	$chars_array = array_merge(range(0,9), range('a','z'), range('A','Z'));


	# copy : file + file record
	$_files = get_file($bo_table, $ENT_IDX);
	for ($i = 0; $i < Count($_files); $i++)
	{
		$_file = $_files[$i];
		if (!$_file['file']) continue;

		if (file_exists(G5_DATA_PATH.'/file/'.$bo_table.'/'.$_file['file']))
		{
			shuffle($chars_array); $shuffle = implode('', $chars_array);

			$from_file = G5_DATA_PATH.'/file/'.$bo_table.'/'.$_file['file'];

			$filename = abs(ip2long($_SERVER['REMOTE_ADDR'])).'_'.substr($shuffle,0,8).'_'.replace_filename($_file['source']);

			$to_file = G5_DATA_PATH.'/file/'.$bo_table.'/'.$filename;

			# file copy
			copy($from_file, $to_file);


			# copy : file record
			$sql = "Insert Into {$g5['board_file_table']}("
				."bo_table"
				.", wr_id"
				.", bf_no"
				.", bf_source"
				.", bf_file"
				.", bf_content"
				.", bf_download"
				.", bf_filesize"
				.", bf_width"
				.", bf_height"
				.", bf_type"
				.", bf_datetime"
				.")"
				." Select "
				."bo_table"
				.", '" . mres($NEW_ENT_IDX) . "'"
				.", bf_no"
				.", bf_source"
				.", '" . mres($filename) . "'"
				.", bf_content"
				.", '0'"
				.", bf_filesize"
				.", bf_width"
				.", bf_height"
				.", bf_type"
				.", '" . G5_TIME_YMDHIS . "'"
				." From {$g5['board_file_table']}"
				." Where bo_table = '" . mres($bo_table) . "'"
				." And wr_id = '" . mres($ENT_IDX) . "'"
				." And bf_no = '" . mres($i) . "'"
				." Order By bf_no Asc"
				;
			sql_query($sql);
		}
	}


	if ($IS_COPY_MASTER != 'Y') {
		echo_json(array(
			'success'=>(boolean)true, 
			'redir'=>'evt.nametag.tpl.list.php?'.$epTail,
			'msg'=>(($EM_IDX > 0) ? "" : "복제되었습니다.")
		));
	}
?>
