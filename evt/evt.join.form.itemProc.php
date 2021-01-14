<?php
	include_once("./_common.php");


	if (!defined('_GNUBOARD_')) exit;
	if ((int)$_POST['EM_IDX'] <= 0) exit;
	if (is_null($member['mb_id'])) exit;


	#---------- FORM BUILDER 기존 입력 정보 삭제 처리 ----------#
	$sql = "Delete From NX_EVENT_FORM_VAL Where mb_id = '" . mres($member['mb_id']) . "' And EM_IDX = '" . mres($EM_IDX) . "' And EJ_IDX = '" . mres($EJ_IDX) . "'";
	sql_query($sql);


	# 테이블 최적화
	$sql = "Optimize Table NX_EVENT_FORM_VAL";
	sql_query($sql);
	#---------- FORM BUILDER 기존 입력 정보 삭제 처리 ----------#


	#---------- FORM BUILDER 로 생성한 값 입력 ----------#
	for ($i = 0; $i < Count($FB); $i++)
	{
		$FI_IDX = $FB[$i]['FI_IDX'];
		$FI_KIND = $FB[$i]['FI_KIND'];
		$FI_TYPE = $FB[$i]['FI_TYPE'];
		$FI_EXT_TYPE = $FB[$i]['FI_EXT_TYPE'];
		$FI_MAX_SIZE = $FB[$i]['FI_MAX_SIZE'];
		$FO_IDX = $FB[$i]['FO_IDX'];
		$FV_VAL = $FB[$i]['FV_VAL'];
		$FV_EXT1 = $FB[$i]['FV_EXT1'];
		$FV_EXT2 = $FB[$i]['FV_EXT2'];

		# re-define
		$FO_IDX = (is_numeric($FO_IDX) && (int)$FO_IDX > 0) ? (int)$FO_IDX : "";
		$FI_MAX_SIZE = (is_numeric($FI_MAX_SIZE) && (int)$FI_MAX_SIZE > 0) ? (int)$FI_MAX_SIZE : (int)'0';
		$FV_VAL = trim($FV_VAL);
		$FV_EXT1 = trim($FV_EXT1);
		$FV_EXT2 = trim($FV_EXT2);

		# case : 값이 있을 경우만
		if ($FI_KIND == 'G' || $FO_IDX != "" || $FV_VAL != "")
		{
			# set : FV_FILEYN
			$FV_FILEYN = "N";


			# set : FV_RNDCODE
			$FV_RNDCODE = uniqid();


			#----- file
			if ($FI_KIND == 'G')
			{
				$upload_max_filesize = ini_get('upload_max_filesize');

				if (empty($_POST)) {
					echo_json(array(
						'success'=>(boolean)false, 
						'msg'=>"파일 또는 글내용의 크기가 서버에서 설정한 값을 넘어 오류가 발생하였습니다.\npost_max_size=".ini_get('post_max_size')." , upload_max_filesize=".$upload_max_filesize."\n게시판관리자 또는 서버관리자에게 문의 바랍니다."
					));
				}


				$bo_table = "NX_EVENT_FORM_VAL";


				// 디렉토리가 없다면 생성합니다. (퍼미션도 변경하구요.)
				@mkdir(G5_DATA_PATH.'/file/'.$bo_table, G5_DIR_PERMISSION);
				@chmod(G5_DATA_PATH.'/file/'.$bo_table, G5_DIR_PERMISSION);


				$chars_array = array_merge(range(0,9), range('a','z'), range('A','Z'));

				// 가변 파일 업로드
				$file_upload_msg = '';
				$upload = array();
				for ($k = 0; $k < Count($_FILES["IPT_{$FI_IDX}"]['name']); $k++)
				{
					if ($_FILES["IPT_{$FI_IDX}"]['name'][$k] == '') continue;
					
					$upload[$k]['file']     = '';
					$upload[$k]['source']   = '';
					$upload[$k]['filesize'] = 0;
					$upload[$k]['image']    = array();
					$upload[$k]['image'][0] = '';
					$upload[$k]['image'][1] = '';
					$upload[$k]['image'][2] = '';

					$upload[$k]['del_check'] = false;

					$tmp_file  = $_FILES["IPT_{$FI_IDX}"]['tmp_name'][$k];
					$filesize  = $_FILES["IPT_{$FI_IDX}"]['size'][$k];
					$filename  = $_FILES["IPT_{$FI_IDX}"]['name'][$k];
					$filename  = get_safe_filename($filename);

					
					#----- 허용된 확장자 chk
					if ($FI_EXT_TYPE != '')
					{
						$_t = explode(',', strtolower($FI_EXT_TYPE));

						if (!in_array(strtolower(substr(strrchr($filename,"."),1)), $_t)) {
							$file_upload_msg .= "\"".$filename."\" 파일은 허용되지 않은 파일 타입입니다.".$FI_EXT_TYPE."\n";
							continue;
						}
					}
					#####


					#----- 허용된 파일크기 chk
					if ($FI_MAX_SIZE > 0 && $filesize > $FI_MAX_SIZE) {
						$file_upload_msg .= "\"".$filename."\" 파일의 허용 용량이 초과 되었습니다.\n";
						continue;
					}
					#####


					// 서버에 설정된 값보다 큰파일을 업로드 한다면
					if ($filename) {
						if ($_FILES["IPT_{$FI_IDX}"]['error'][$k] == 1) {
							$file_upload_msg .= "\"".$filename."\" 파일의 용량이 서버에 설정(".$upload_max_filesize.")된 값보다 크므로 업로드 할 수 없습니다.\n";
							continue;
						}
						else if ($_FILES["IPT_{$FI_IDX}"]['error'][$k] != 0) {
							$file_upload_msg .= "\"".$filename."\" 파일이 정상적으로 업로드 되지 않았습니다.\n";
							continue;
						}
					}

					if (is_uploaded_file($tmp_file))
					{
				        // 아래 확장자만 업로드 가능
				        // gill-039.평진원 Editor 보안취약점 조치
				        // 1.2. 화이트 리스트 방식의 업로드 파일의 확장자 체크
				        if (!preg_match("/\.(".G5_FILE_UPDATE_WHITE_LIST.")/i", $filename)) {
				            $file_upload_msg .= '업로드가 제한된 파일 확장자 입니다.\n';
				            continue;
				        }

						//=================================================================\
						// 090714
						// 이미지나 플래시 파일에 악성코드를 심어 업로드 하는 경우를 방지
						// 에러메세지는 출력하지 않는다.
						//-----------------------------------------------------------------
						$timg = @getimagesize($tmp_file);
						// image type
						if ( preg_match("/\.({$config['cf_image_extension']})$/i", $filename) ||
							 preg_match("/\.({$config['cf_flash_extension']})$/i", $filename) ) {
							if ($timg['2'] < 1 || $timg['2'] > 16)
								continue;
						}
						//=================================================================

						$upload[$k]['image'] = $timg;

						// 프로그램 원래 파일명
						$upload[$k]['source'] = $filename;
						$upload[$k]['filesize'] = $filesize;

						// 아래의 문자열이 들어간 파일은 -x 를 붙여서 웹경로를 알더라도 실행을 하지 못하도록 함
						$filename = preg_replace("/\.(php|phtm|htm|cgi|pl|exe|jsp|asp|inc)/i", "$0-x", $filename);

						shuffle($chars_array);
						$shuffle = implode('', $chars_array);

						// 첨부파일 첨부시 첨부파일명에 공백이 포함되어 있으면 일부 PC에서 보이지 않거나 다운로드 되지 않는 현상이 있습니다. (길상여의 님 090925)
						$upload[$k]['file'] = abs(ip2long($_SERVER['REMOTE_ADDR'])).'_'.substr($shuffle,0,8).'_'.replace_filename($filename);

						$dest_file = G5_DATA_PATH.'/file/'.$bo_table.'/'.$upload[$k]['file'];

						// 업로드가 안된다면 에러메세지 출력하고 죽어버립니다.
						$error_code = move_uploaded_file($tmp_file, $dest_file) or die($_FILES["IPT_{$FI_IDX}"]['error'][$k]);

						// 올라간 파일의 퍼미션을 변경합니다.
						chmod($dest_file, G5_FILE_PERMISSION);
					}
				}


				if ($file_upload_msg != '')
				{
					# 이미 생성된 행사참여 신청 record 삭제
					$sql = "Delete From NX_EVENT_JOIN Where EM_IDX = '" . mres($EM_IDX) . "' And EJ_IDX = '" . mres($EJ_IDX) . "' Limit 1";
					sql_query($sql);

					echo_json(array(
						'success'=>(boolean)false,
						'msg'=>$file_upload_msg
					));
				}


				/* upload file fk update */
				for ($k = 0; $k < Count($upload); $k++)
				{
					if (!get_magic_quotes_gpc()) {
						$upload[$k]['source'] = addslashes($upload[$k]['source']);
					}

					$sql = " insert into {$g5['board_file_table']}
								set bo_table = '{$bo_table}',
									 wr_id = '{$EJ_IDX}',
									 bf_no = '{$FI_IDX}',
									 bf_source = '{$upload[$k]['source']}',
									 bf_file = '{$upload[$k]['file']}',
									 bf_content = '{$bf_content[$k]}',
									 bf_download = 0,
									 bf_filesize = '{$upload[$k]['filesize']}',
									 bf_width = '{$upload[$k]['image']['0']}',
									 bf_height = '{$upload[$k]['image']['1']}',
									 bf_type = '{$upload[$k]['image']['2']}',
									 bf_datetime = '".G5_TIME_YMDHIS."' ";
					sql_query($sql);
				}
			}
			#####


			# insert
			$sql = "Insert Ignore Into NX_EVENT_FORM_VAL("
				. "mb_id"
				. ", EM_IDX"
				. ", EJ_IDX"
				. ", FI_IDX"
				. ", FO_IDX"
				. ", FV_VAL"
				. ", FV_EXT1"
				. ", FV_EXT2"
				. ", FV_SIZE"
				. ", FV_FILEYN"
				. ", FV_RNDCODE"
				. ", FV_WDATE"
				. ", FV_WIP"
				. ") values("
				. "'" . mres($member['mb_id']) . "'"
				. ", '" . mres($EM_IDX) . "'"
				. ", '" . mres($EJ_IDX) . "'"
				. ", '" . mres($FI_IDX) . "'"
				. ", '" . mres($FO_IDX) . "'"
				. ", '" . mres($FV_VAL) . "'"
				. ", '" . mres($FV_EXT1) . "'"
				. ", '" . mres($FV_EXT2) . "'"
				. ", '" . mres(strlen($FV_VAL . $FV_EXT1 . $FV_EXT2)) . "'"
				. ", '" . mres($FV_FILEYN) . "'"
				. ", '" . mres($FV_RNDCODE) . "'"
				. ", now()"
				. ", inet_aton('" . mres($_SERVER['REMOTE_ADDR']) . "')"
				. ")"
				;
			sql_query($sql);
		}
	}
	#---------- FORM BUILDER 로 생성한 값 입력 ----------#
?>
