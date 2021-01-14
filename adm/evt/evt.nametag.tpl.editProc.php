<?php
	include_once('./_common.php');
	include_once('./evt.err.php'); 	# $sub_menu = ($EP_IDX > 0) ? '980100' : '990100'

	if ($ret = auth_check($auth[$sub_menu], "w", true, true)) {
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>$ret
		));
	}


	/*	maxSize
		300dpi 기준 12x18cm (cm)
		72dpi 기준 340x510 (px)
	*/
	$maxSize = ['ww'=>(int)120, 'hh'=>(int)180];


	# set : variables
	$EM_IDX = $_POST['EM_IDX'];
	$ENT_IDX = $_POST['ENT_IDX'];
	$ENT_TITLE = $_POST['ENT_TITLE'];
	$ENT_WIDTH = $_POST['ENT_WIDTH'];
	$ENT_HEIGHT = $_POST['ENT_HEIGHT'];

	$ENT_F1_KIND = $_POST['ENT_F1_KIND'];
	$ENT_F1_SIZE = $_POST['ENT_F1_SIZE'];
	$ENT_F1_LEFT = $_POST['ENT_F1_LEFT'];
	$ENT_F1_RIGHT = $_POST['ENT_F1_RIGHT'];
	$ENT_F1_COLOR = $_POST['ENT_F1_COLOR'];
	$ENT_F1_BOLD = $_POST['ENT_F1_BOLD'];
	$ENT_F1_UNDERLINE = $_POST['ENT_F1_UNDERLINE'];
	$ENT_F1_ALIGN = $_POST['ENT_F1_ALIGN'];
	$ENT_F1_Y = $_POST['ENT_F1_Y'];

	$ENT_F2_KIND = $_POST['ENT_F2_KIND'];
	$ENT_F2_SIZE = $_POST['ENT_F2_SIZE'];
	$ENT_F2_LEFT = $_POST['ENT_F2_LEFT'];
	$ENT_F2_RIGHT = $_POST['ENT_F2_RIGHT'];
	$ENT_F2_COLOR = $_POST['ENT_F2_COLOR'];
	$ENT_F2_BOLD = $_POST['ENT_F2_BOLD'];
	$ENT_F2_UNDERLINE = $_POST['ENT_F2_UNDERLINE'];
	$ENT_F2_ALIGN = $_POST['ENT_F2_ALIGN'];
	$ENT_F2_Y = $_POST['ENT_F2_Y'];

	$ENT_F3_KIND = $_POST['ENT_F3_KIND'];
	$ENT_F3_SIZE = $_POST['ENT_F3_SIZE'];
	$ENT_F3_LEFT = $_POST['ENT_F3_LEFT'];
	$ENT_F3_RIGHT = $_POST['ENT_F3_RIGHT'];
	$ENT_F3_COLOR = $_POST['ENT_F3_COLOR'];
	$ENT_F3_BOLD = $_POST['ENT_F3_BOLD'];
	$ENT_F3_UNDERLINE = $_POST['ENT_F3_UNDERLINE'];
	$ENT_F3_ALIGN = $_POST['ENT_F3_ALIGN'];
	$ENT_F3_Y = $_POST['ENT_F3_Y'];

	$ENT_F4_SIZE = $_POST['ENT_F4_SIZE'];
	$ENT_F4_LEFT = $_POST['ENT_F4_LEFT'];
	$ENT_F4_RIGHT = $_POST['ENT_F4_RIGHT'];
	$ENT_F4_COLOR = $_POST['ENT_F4_COLOR'];
	$ENT_F4_BOLD = $_POST['ENT_F4_BOLD'];
	$ENT_F4_UNDERLINE = $_POST['ENT_F4_UNDERLINE'];
	$ENT_F4_ALIGN = $_POST['ENT_F4_ALIGN'];
	$ENT_F4_Y = $_POST['ENT_F4_Y'];


	# re-define
	$EM_IDX = CHK_NUMBER($EM_IDX);
	$ENT_IDX = CHK_NUMBER($ENT_IDX);
	$ENT_WIDTH = CHK_NUMBER($ENT_WIDTH);
	$ENT_HEIGHT = CHK_NUMBER($ENT_HEIGHT);

	$ENT_F1_KIND = (in_array($ENT_F1_KIND, array('NAME','MOBILE','EMAIL','ORG','EM_TITLE'))) ? (string)$ENT_F1_KIND : '';
	$ENT_F1_SIZE = CHK_NUMBER($ENT_F1_SIZE);
	$ENT_F1_LEFT = CHK_NUMBER($ENT_F1_LEFT);
	$ENT_F1_RIGHT = CHK_NUMBER($ENT_F1_RIGHT);
	$ENT_F1_COLOR = str_replace('#', '', $ENT_F1_COLOR);
	$ENT_F1_BOLD = F_YN($ENT_F1_BOLD, 'N');
	$ENT_F1_UNDERLINE = F_YN($ENT_F1_UNDERLINE, 'N');
	$ENT_F1_ALIGN = (in_array($ENT_F1_ALIGN, array('LEFT','CENTER','RIGHT'))) ? (string)$ENT_F1_ALIGN : '';
	$ENT_F1_Y = CHK_NUMBER($ENT_F1_Y);

	$ENT_F2_KIND = (in_array($ENT_F2_KIND, array('NAME','MOBILE','EMAIL','ORG','EM_TITLE'))) ? (string)$ENT_F2_KIND : '';
	$ENT_F2_SIZE = CHK_NUMBER($ENT_F2_SIZE);
	$ENT_F2_LEFT = CHK_NUMBER($ENT_F2_LEFT);
	$ENT_F2_RIGHT = CHK_NUMBER($ENT_F2_RIGHT);
	$ENT_F2_COLOR = str_replace('#', '', $ENT_F2_COLOR);
	$ENT_F2_BOLD = F_YN($ENT_F2_BOLD, 'N');
	$ENT_F2_UNDERLINE = F_YN($ENT_F2_UNDERLINE, 'N');
	$ENT_F2_ALIGN = (in_array($ENT_F2_ALIGN, array('LEFT','CENTER','RIGHT'))) ? (string)$ENT_F2_ALIGN : '';
	$ENT_F2_Y = CHK_NUMBER($ENT_F2_Y);

	$ENT_F3_KIND = (in_array($ENT_F3_KIND, array('NAME','MOBILE','EMAIL','ORG','EM_TITLE'))) ? (string)$ENT_F3_KIND : '';
	$ENT_F3_SIZE = CHK_NUMBER($ENT_F3_SIZE);
	$ENT_F3_LEFT = CHK_NUMBER($ENT_F3_LEFT);
	$ENT_F3_RIGHT = CHK_NUMBER($ENT_F3_RIGHT);
	$ENT_F3_COLOR = str_replace('#', '', $ENT_F3_COLOR);
	$ENT_F3_BOLD = F_YN($ENT_F3_BOLD, 'N');
	$ENT_F3_UNDERLINE = F_YN($ENT_F3_UNDERLINE, 'N');
	$ENT_F3_ALIGN = (in_array($ENT_F3_ALIGN, array('LEFT','CENTER','RIGHT'))) ? (string)$ENT_F3_ALIGN : '';
	$ENT_F3_Y = CHK_NUMBER($ENT_F3_Y);

	$ENT_F4_SIZE = CHK_NUMBER($ENT_F4_SIZE);
	$ENT_F4_LEFT = CHK_NUMBER($ENT_F4_LEFT);
	$ENT_F4_RIGHT = CHK_NUMBER($ENT_F4_RIGHT);
	$ENT_F4_COLOR = str_replace('#', '', $ENT_F4_COLOR);
	$ENT_F4_BOLD = F_YN($ENT_F4_BOLD, 'N');
	$ENT_F4_UNDERLINE = F_YN($ENT_F4_UNDERLINE, 'N');
	$ENT_F4_ALIGN = (in_array($ENT_F4_ALIGN, array('LEFT','CENTER','RIGHT'))) ? (string)$ENT_F4_ALIGN : '';
	$ENT_F4_Y = CHK_NUMBER($ENT_F4_Y);


	#----- chk : rfv.
	$rfv = array();
	if ($ENT_IDX < 0) $rfv[] = ['str'=>'잘못된 접근 입니다.'];
	if ($ENT_WIDTH < 35 || $ENT_WIDTH > $maxSize['ww']) { $ENT_WIDTH = ''; $rfv[] = ['str'=>'사이즈(가로) 정보를 바르게 입력해 주세요.']; }
	if ($ENT_HEIGHT < 35 || $ENT_HEIGHT > $maxSize['hh']) { $ENT_HEIGHT = ''; $rfv[] = ['str'=>'사이즈(세로) 정보를 바르게 입력해 주세요.']; }

	// if ($ENT_F1_KIND == '') $rfv[] = ['str'=>'A 내용 항목을 선택해 주세요.'];
	if ($ENT_F1_SIZE > 127) $rfv[] = ['str'=>'A 내용 크기 정보가 잘못되었습니다.'];
	if ($ENT_F1_LEFT > 127) $rfv[] = ['str'=>'A 내용 크기 정보가 잘못되었습니다.'];
	if ($ENT_F1_RIGHT > 127) $rfv[] = ['str'=>'A 내용 크기 정보가 잘못되었습니다.'];
	if (strlen($ENT_F1_COLOR) < 3) $rfv[] = ['str'=>'색상값은 3자리 이상 입력해 주세요.'];

	// if ($ENT_F2_KIND == '') $rfv[] = ['str'=>'B 내용 항목을 선택해 주세요.'];
	if ($ENT_F2_SIZE > 127) $rfv[] = ['str'=>'B 내용 크기 정보가 잘못되었습니다.'];
	if ($ENT_F2_LEFT > 127) $rfv[] = ['str'=>'B 내용 크기 정보가 잘못되었습니다.'];
	if ($ENT_F2_RIGHT > 127) $rfv[] = ['str'=>'B 내용 크기 정보가 잘못되었습니다.'];
	if (strlen($ENT_F2_COLOR) < 3) $rfv[] = ['str'=>'색상값은 3자리 이상 입력해 주세요.'];

	// if ($ENT_F3_KIND == '') $rfv[] = ['str'=>'C 내용 항목을 선택해 주세요.'];
	if ($ENT_F3_SIZE > 127) $rfv[] = ['str'=>'C 내용 크기 정보가 잘못되었습니다.'];
	if ($ENT_F3_LEFT > 127) $rfv[] = ['str'=>'C 내용 크기 정보가 잘못되었습니다.'];
	if ($ENT_F3_RIGHT > 127) $rfv[] = ['str'=>'C 내용 크기 정보가 잘못되었습니다.'];
	if (strlen($ENT_F3_COLOR) < 3) $rfv[] = ['str'=>'색상값은 3자리 이상 입력해 주세요.'];

	if ($ENT_F4_SIZE > 127) $rfv[] = ['str'=>'코드 크기 정보가 잘못되었습니다.'];
	if ($ENT_F4_LEFT > 127) $rfv[] = ['str'=>'코드 크기 정보가 잘못되었습니다.'];
	if ($ENT_F4_RIGHT > 127) $rfv[] = ['str'=>'코드 크기 정보가 잘못되었습니다.'];
	if (strlen($ENT_F4_COLOR) < 3) $rfv[] = ['str'=>'색상값은 3자리 이상 입력해 주세요.'];

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


	$upload_max_filesize = ini_get('upload_max_filesize');

	if (empty($_POST)) {
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>"파일 또는 글내용의 크기가 서버에서 설정한 값을 넘어 오류가 발생하였습니다.\npost_max_size=".ini_get('post_max_size')." , upload_max_filesize=".$upload_max_filesize."\n게시판관리자 또는 서버관리자에게 문의 바랍니다."
		));
	}


	$bo_table = "NX_EVENT_NAMETAG_TEMPLATE";


	# 디렉토리가 없다면 생성 + 퍼미션 변경
	@mkdir(G5_DATA_PATH.'/file/'.$bo_table, G5_DIR_PERMISSION);
	@chmod(G5_DATA_PATH.'/file/'.$bo_table, G5_DIR_PERMISSION);


	$chars_array = array_merge(range(0,9), range('a','z'), range('A','Z'));

	// 가변 파일 업로드
	$file_upload_msg = '';
	$upload = array();
	for ($i=0; $i<count($_FILES['ent_file']['name']); $i++)
	{
		$upload[$i]['file']     = '';
		$upload[$i]['source']   = '';
		$upload[$i]['filesize'] = 0;
		$upload[$i]['image']    = array();
		$upload[$i]['image'][0] = '';
		$upload[$i]['image'][1] = '';
		$upload[$i]['image'][2] = '';


        # 삭제에 checked 일 경우 파일 삭제
        if ($_POST['ent_file_del'][$i] == 'Y') {
            $upload[$i]['del_check'] = true;

            $row = sql_fetch(" select bf_file from {$g5['board_file_table']} where bo_table = '" . mres($bo_table) . "' and wr_id = '" . mres($ENT_IDX) . "' and bf_no = '" . mres($i) . "' ");
            @unlink(G5_DATA_PATH.'/file/'.$bo_table.'/'.$row['bf_file']);
            
            // 썸네일삭제
            if(preg_match("/\.({$config['cf_image_extension']})$/i", $row['bf_file'])) {
                delete_board_thumbnail($bo_table, $row['bf_file']);
            }
        }
        else {
	        $upload[$i]['del_check'] = false;
        }


		$tmp_file  = $_FILES['ent_file']['tmp_name'][$i];
		$filesize  = $_FILES['ent_file']['size'][$i];
		$filename  = $_FILES['ent_file']['name'][$i];
		$filename  = get_safe_filename($filename);

		// 서버에 설정된 값보다 큰파일을 업로드 한다면
		if ($filename) {
			if ($_FILES['ent_file']['error'][$i] == 1) {
				$file_upload_msg .= '\"'.$filename.'\" 파일의 용량이 서버에 설정('.$upload_max_filesize.')된 값보다 크므로 업로드 할 수 없습니다.\\n';
				continue;
			}
			else if ($_FILES['ent_file']['error'][$i] != 0) {
				$file_upload_msg .= '\"'.$filename.'\" 파일이 정상적으로 업로드 되지 않았습니다.\\n';
				continue;
			}
		}

		if (is_uploaded_file($tmp_file))
		{

			// 파일 확장자 추출 및 검사
			$filename = $_FILES['ent_file']['name'][$i];
			$ext = substr(strrchr($filename,"."), 1);
			$ext = strtolower($ext);
			
			if ($ext != 'jpeg' && $ext != 'jpg'){

				echo_json(array(
					'success'=>(boolean)false, 
					'msg'=>"jpg/jpeg 파일만 등록할 수 있습니다."
				));
				exit;
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


			$upload[$i]['image'] = $timg;


	        // 4.00.11 - 글답변에서 파일 업로드시 원글의 파일이 삭제되는 오류를 수정
	        // 존재하는 파일이 있다면 삭제합니다.
	        $row = sql_fetch(" select bf_file from {$g5['board_file_table']} where bo_table = '" . mres($bo_table) . "' and wr_id = '" . mres($ENT_IDX) . "' and bf_no = '" . mres($i) . "' ");
	        @unlink(G5_DATA_PATH.'/file/'.$bo_table.'/'.$row['bf_file']);
	        
	        // 이미지파일이면 썸네일삭제
	        if(preg_match("/\.({$config['cf_image_extension']})$/i", $row['bf_file'])) {
	            delete_board_thumbnail($bo_table, $row['bf_file']);
	        }


			// 프로그램 원래 파일명
			$upload[$i]['source'] = $filename;
			$upload[$i]['filesize'] = $filesize;

			// 아래의 문자열이 들어간 파일은 -x 를 붙여서 웹경로를 알더라도 실행을 하지 못하도록 함
			$filename = preg_replace("/\.(php|phtm|htm|cgi|pl|exe|jsp|asp|inc)/i", "$0-x", $filename);

			shuffle($chars_array);
			$shuffle = implode('', $chars_array);

			// 첨부파일 첨부시 첨부파일명에 공백이 포함되어 있으면 일부 PC에서 보이지 않거나 다운로드 되지 않는 현상이 있습니다. (길상여의 님 090925)
			$upload[$i]['file'] = abs(ip2long($_SERVER['REMOTE_ADDR'])).'_'.substr($shuffle,0,8).'_'.replace_filename($filename);

			$dest_file = G5_DATA_PATH.'/file/'.$bo_table.'/'.$upload[$i]['file'];

			// 업로드가 안된다면 에러메세지 출력하고 죽어버립니다.
			$error_code = move_uploaded_file($tmp_file, $dest_file) or die($_FILES['ent_file']['error'][$i]);

			// 올라간 파일의 퍼미션을 변경합니다.
			chmod($dest_file, G5_FILE_PERMISSION);
		}
	}


	# update : template
	$sql = "Update NX_EVENT_NAMETAG_TEMPLATE Set"
		." ENT_WIDTH = '" . mres($ENT_WIDTH) . "'";

	# em_idx 가 없을 경우만 ent_title 수정
	if ($EM_IDX <= 0) {
		$sql .= ", ENT_TITLE = '" . mres($ENT_TITLE) . "'";
	}
		
	$sql .= ''
		.", ENT_HEIGHT = '" . mres($ENT_HEIGHT) . "'"
		.", ENT_F1_KIND = '" . mres($ENT_F1_KIND) . "'"
		.", ENT_F1_SIZE = '" . mres($ENT_F1_SIZE) . "'"
		.", ENT_F1_LEFT = '" . mres($ENT_F1_LEFT) . "'"
		.", ENT_F1_RIGHT = '" . mres($ENT_F1_RIGHT) . "'"
		.", ENT_F1_COLOR = '" . mres($ENT_F1_COLOR) . "'"
		.", ENT_F1_BOLD = '" . mres($ENT_F1_BOLD) . "'"
		.", ENT_F1_UNDERLINE = '" . mres($ENT_F1_UNDERLINE) . "'"
		.", ENT_F1_ALIGN = '" . mres($ENT_F1_ALIGN) . "'"
		.", ENT_F1_Y = '" . mres($ENT_F1_Y) . "'"
		.", ENT_F2_KIND = '" . mres($ENT_F2_KIND) . "'"
		.", ENT_F2_SIZE = '" . mres($ENT_F2_SIZE) . "'"
		.", ENT_F2_LEFT = '" . mres($ENT_F2_LEFT) . "'"
		.", ENT_F2_RIGHT = '" . mres($ENT_F2_RIGHT) . "'"
		.", ENT_F2_COLOR = '" . mres($ENT_F2_COLOR) . "'"
		.", ENT_F2_BOLD = '" . mres($ENT_F2_BOLD) . "'"
		.", ENT_F2_UNDERLINE = '" . mres($ENT_F2_UNDERLINE) . "'"
		.", ENT_F2_ALIGN = '" . mres($ENT_F2_ALIGN) . "'"
		.", ENT_F2_Y = '" . mres($ENT_F2_Y) . "'"
		.", ENT_F3_KIND = '" . mres($ENT_F3_KIND) . "'"
		.", ENT_F3_SIZE = '" . mres($ENT_F3_SIZE) . "'"
		.", ENT_F3_LEFT = '" . mres($ENT_F3_LEFT) . "'"
		.", ENT_F3_RIGHT = '" . mres($ENT_F3_RIGHT) . "'"
		.", ENT_F3_COLOR = '" . mres($ENT_F3_COLOR) . "'"
		.", ENT_F3_BOLD = '" . mres($ENT_F3_BOLD) . "'"
		.", ENT_F3_UNDERLINE = '" . mres($ENT_F3_UNDERLINE) . "'"
		.", ENT_F3_ALIGN = '" . mres($ENT_F3_ALIGN) . "'"
		.", ENT_F3_Y = '" . mres($ENT_F3_Y) . "'"
		.", ENT_F4_SIZE = '" . mres($ENT_F4_SIZE) . "'"
		.", ENT_F4_LEFT = '" . mres($ENT_F4_LEFT) . "'"
		.", ENT_F4_RIGHT = '" . mres($ENT_F4_RIGHT) . "'"
		.", ENT_F4_COLOR = '" . mres($ENT_F4_COLOR) . "'"
		.", ENT_F4_BOLD = '" . mres($ENT_F4_BOLD) . "'"
		.", ENT_F4_UNDERLINE = '" . mres($ENT_F4_UNDERLINE) . "'"
		.", ENT_F4_ALIGN = '" . mres($ENT_F4_ALIGN) . "'"
		.", ENT_F4_Y = '" . mres($ENT_F4_Y) . "'"
		.", ENT_MDATE = now()"
		.", ENT_MIP = inet_aton('" . mres($_SERVER['REMOTE_ADDR']) . "')"
		." Where ENT_IDX = '" . mres($ENT_IDX) . "'"
		." Limit 1"
		;
	sql_query($sql);


	/* upload file fk update */
	for ($i=0; $i<count($upload); $i++)
	{
	    if (!get_magic_quotes_gpc()) {
	        $upload[$i]['source'] = addslashes($upload[$i]['source']);
	    }

        $row = sql_fetch(" select count(*) as cnt from {$g5['board_file_table']} where bo_table = '" . mres($bo_table) . "' and wr_id = '" . mres($ENT_IDX) . "' and bf_no = '" . mres($i) . "' ");
        if ($row['cnt'])
        {
            // 삭제에 체크가 있거나 파일이 있다면 업데이트를 합니다.
            // 그렇지 않다면 내용만 업데이트 합니다.
    		// 날짜는 업데이트 하지 않습니다.
            if ($upload[$i]['del_check'] || $upload[$i]['file'])
            {
                $sql = " update {$g5['board_file_table']}
                            set bf_source = '{$upload[$i]['source']}',
                                 bf_file = '{$upload[$i]['file']}',
                                 bf_content = '{$bf_content[$i]}',
                                 bf_filesize = '{$upload[$i]['filesize']}',
                                 bf_width = '{$upload[$i]['image']['0']}',
                                 bf_height = '{$upload[$i]['image']['1']}',
                                 bf_type = '{$upload[$i]['image']['2']}',
                                 bf_datetime = '".G5_TIME_YMDHIS."'
                          where bo_table = '{$bo_table}'
                                    and wr_id = '{$ENT_IDX}'
                                    and bf_no = '{$i}' ";
                sql_query($sql);
            }
            else
            {
                $sql = " update {$g5['board_file_table']}
                            set bf_content = '{$bf_content[$i]}'
                            where bo_table = '{$bo_table}'
                                      and wr_id = '{$ENT_IDX}'
                                      and bf_no = '{$i}' ";
                sql_query($sql);
            }
        }
        else
        {
	        $sql = " insert into {$g5['board_file_table']}
	                    set bo_table = '{$bo_table}',
	                         wr_id = '{$ENT_IDX}',
	                         bf_no = '{$i}',
	                         bf_source = '{$upload[$i]['source']}',
	                         bf_file = '{$upload[$i]['file']}',
	                         bf_content = '{$bf_content[$i]}',
	                         bf_download = 0,
	                         bf_filesize = '{$upload[$i]['filesize']}',
	                         bf_width = '{$upload[$i]['image']['0']}',
	                         bf_height = '{$upload[$i]['image']['1']}',
	                         bf_type = '{$upload[$i]['image']['2']}',
	                         bf_datetime = '".G5_TIME_YMDHIS."' ";
	        sql_query($sql);
		}
	}


	echo_json(array(
		'success'=>(boolean)true, 
		'redir'=> (($EM_IDX > 0) ? 'evt.nametag.php?'.$epTail.'EM_IDX='.$EM_IDX : 'evt.nametag.tpl.list.php'),
		'msg'=>"저장되었습니다."
	));
?>
