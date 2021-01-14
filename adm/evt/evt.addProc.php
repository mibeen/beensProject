<?php
	include_once('./_common.php');
	include_once('./evt.err.php'); 	# $sub_menu = ($EP_IDX > 0) ? '980100' : '990100'

	if ($ret = auth_check($auth[$sub_menu], "w", true, true)) {
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>$ret
		));
	}


	# set : variables
	$EC_IDX = $_POST['EC_IDX'];
	$EM_TITLE = $_POST['EM_TITLE'];
	$EM_TYPE = $_POST['EM_TYPE'];
	$EM_CG_NAME = $_POST['EM_CG_NAME'];
	$EM_CG_TEL1 = $_POST['EM_CG_TEL1'];
	$EM_CG_TEL2 = $_POST['EM_CG_TEL2'];
	$EM_CG_TEL3 = $_POST['EM_CG_TEL3'];
	$EM_CG_EMAIL = $_POST['EM_CG_EMAIL'];
	$EM_JOIN_TYPE = $_POST['EM_JOIN_TYPE'];
	$EM_NOTI_EMAIL = $_POST['EM_NOTI_EMAIL'];
	$EM_NOTI_SMS = $_POST['EM_NOTI_SMS'];
	$EM_CERT_TITLE = $_POST['EM_CERT_TITLE'];
	$EM_CERT_ORG_YN = $_POST['EM_CERT_ORG_YN'];
	$EM_REQUIRE_BIRTH_YN = $_POST['EM_REQUIRE_BIRTH_YN'];
	$EM_OPEN_YN = $_POST['EM_OPEN_YN'];
	
	// $EM_S_DATE1 = $_POST['EM_S_DATE1'];
	// $EM_S_DATE2 = $_POST['EM_S_DATE2'];
	// $EM_S_DATE3 = $_POST['EM_S_DATE3'];
	$EM_S_DATE = $_POST['EM_S_DATE'];
	$EM_S_TIME1 = $_POST['EM_S_TIME1'];
	$EM_S_TIME2 = $_POST['EM_S_TIME2'];

	$EM_E_DATE = $_POST['EM_E_DATE'];
	// $EM_E_DATE1 = $_POST['EM_E_DATE1'];
	// $EM_E_DATE2 = $_POST['EM_E_DATE2'];
	// $EM_E_DATE3 = $_POST['EM_E_DATE3'];
	$EM_E_TIME1 = $_POST['EM_E_TIME1'];
	$EM_E_TIME2 = $_POST['EM_E_TIME2'];
	$EM_CERT_TIME = $_POST['EM_CERT_TIME'];
	$EM_CERT_MINUTE = $_POST['EM_CERT_MINUTE'];

	$EM_JOIN_S_DATE1 = $_POST['EM_JOIN_S_DATE1'];
	$EM_JOIN_S_DATE2 = $_POST['EM_JOIN_S_DATE2'];
	$EM_JOIN_S_DATE3 = $_POST['EM_JOIN_S_DATE3'];
	$EM_JOIN_S_TIME1 = $_POST['EM_JOIN_S_TIME1'];
	$EM_JOIN_S_TIME2 = $_POST['EM_JOIN_S_TIME2'];

	$EM_JOIN_E_DATE1 = $_POST['EM_JOIN_E_DATE1'];
	$EM_JOIN_E_DATE2 = $_POST['EM_JOIN_E_DATE2'];
	$EM_JOIN_E_DATE3 = $_POST['EM_JOIN_E_DATE3'];
	$EM_JOIN_E_TIME1 = $_POST['EM_JOIN_E_TIME1'];
	$EM_JOIN_E_TIME2 = $_POST['EM_JOIN_E_TIME2'];

	$EM_JOIN_MAX = $_POST['EM_JOIN_MAX'];
	$EM_ADDR = $_POST['EM_ADDR'];
	$EM_CONT = $_POST['EM_CONT'];
	$FI_RNDCODE = $_POST['FI_RNDCODE'];

	
	# re-define
	$EC_IDX = CHK_NUMBER($EC_IDX);
	$EM_TYPE = ((int)$EM_TYPE >= 1 && (int)$EM_TYPE <= 2) ? (int)$EM_TYPE : '';
	
	$EM_CG_TEL = "{$EM_CG_TEL1}-{$EM_CG_TEL2}-{$EM_CG_TEL3}";
	if ($EM_CG_TEL == '--') $EM_CG_TEL = '';
	
	$EM_JOIN_TYPE = ((int)$EM_JOIN_TYPE >= 1 && (int)$EM_JOIN_TYPE <= 2) ? (int)$EM_JOIN_TYPE : '';
	$EM_NOTI_EMAIL = F_YN($EM_NOTI_EMAIL,'N');
	$EM_NOTI_SMS = F_YN($EM_NOTI_SMS,'Y');
	$EM_CERT_TITLE = trim($EM_CERT_TITLE);
	$EM_CERT_ORG_YN = F_YN($EM_CERT_ORG_YN,'Y');
	$EM_REQUIRE_BIRTH_YN = F_YN($EM_REQUIRE_BIRTH_YN,'N');
	$EM_OPEN_YN = F_YN($EM_OPEN_YN,'Y');
	
	// $EM_S_DATE = "{$EM_S_DATE1}-{$EM_S_DATE2}-{$EM_S_DATE3}";
	if ($EM_S_DATE == '--') $EM_S_DATE = '';
	
	$EM_S_TIME1 = sprintf('%02d', (int)$EM_S_TIME1);
	$EM_S_TIME2 = sprintf('%02d', (int)$EM_S_TIME2);
	$EM_S_TIME = "{$EM_S_TIME1}:{$EM_S_TIME2}";
	if ($EM_S_TIME == ':') $EM_S_TIME = '';

	// $EM_E_DATE = "{$EM_E_DATE1}-{$EM_E_DATE2}-{$EM_E_DATE3}";
	if ($EM_E_DATE == '--') $EM_E_DATE = '';
	
	$EM_E_TIME1 = sprintf('%02d', (int)$EM_E_TIME1);
	$EM_E_TIME2 = sprintf('%02d', (int)$EM_E_TIME2);
	$EM_E_TIME = "{$EM_E_TIME1}:{$EM_E_TIME2}";
	if ($EM_E_TIME == ':') $EM_E_TIME = '';

	$EM_CERT_TIME = ((int)$EM_CERT_TIME >= 1) ? (int)$EM_CERT_TIME : '';
	$EM_CERT_MINUTE = ((int)$EM_CERT_MINUTE >= 1) ? (int)$EM_CERT_MINUTE : '';

	$EM_JOIN_S_DATE = "{$EM_JOIN_S_DATE1}-{$EM_JOIN_S_DATE2}-{$EM_JOIN_S_DATE3}";
	if ($EM_JOIN_S_DATE == '--') $EM_JOIN_S_DATE = '';

	$EM_JOIN_S_TIME1 = sprintf('%02d', (int)$EM_JOIN_S_TIME1);
	$EM_JOIN_S_TIME2 = sprintf('%02d', (int)$EM_JOIN_S_TIME2);
	$EM_JOIN_S_TIME = "{$EM_JOIN_S_TIME1}:{$EM_JOIN_S_TIME2}";
	if ($EM_JOIN_S_TIME == ':') $EM_JOIN_S_TIME = '';

	$EM_JOIN_E_DATE = "{$EM_JOIN_E_DATE1}-{$EM_JOIN_E_DATE2}-{$EM_JOIN_E_DATE3}";
	if ($EM_JOIN_E_DATE == '--') $EM_JOIN_E_DATE = '';

	$EM_JOIN_E_TIME1 = sprintf('%02d', (int)$EM_JOIN_E_TIME1);
	$EM_JOIN_E_TIME2 = sprintf('%02d', (int)$EM_JOIN_E_TIME2);
	$EM_JOIN_E_TIME = "{$EM_JOIN_E_TIME1}:{$EM_JOIN_E_TIME2}";
	if ($EM_JOIN_E_TIME == ':') $EM_JOIN_E_TIME = '';

	if ($EM_JOIN_S_DATE != '') $EM_JOIN_S_DATE .= ' ' . $EM_JOIN_S_TIME;
	if ($EM_JOIN_E_DATE != '') $EM_JOIN_E_DATE .= ' ' . $EM_JOIN_E_TIME;

	$EM_JOIN_MAX = CHK_NUMBER($EM_JOIN_MAX);


	# chk : rfv.
	if ($EM_TITLE == '' || $EM_TYPE == ''
	 || $EM_CG_NAME == '' || $EM_CG_TEL == '' || $EM_CG_EMAIL == ''
	 || $EM_JOIN_TYPE == ''
	 || $EM_OPEN_YN == ''
	 || $EM_S_DATE == '' || $EM_S_TIME == ''
	 || $EM_E_DATE == '' || $EM_E_TIME == ''
	 || $EM_JOIN_S_DATE == '' || $EM_JOIN_S_TIME == ''
	 || $EM_JOIN_E_DATE == '' || $EM_JOIN_E_TIME == ''
	 || $FI_RNDCODE == ''
	) {
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>"필수 항목이 누락되었습니다."
		));
	}


	# chk : rfv.
	if ($EM_JOIN_MAX > 99999999) {
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>"제한인원이 허용된 범위를 초과하였습니다."
		));
	}


	$upload_max_filesize = ini_get('upload_max_filesize');

	if (empty($_POST)) {
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>"파일 또는 글내용의 크기가 서버에서 설정한 값을 넘어 오류가 발생하였습니다.\npost_max_size=".ini_get('post_max_size')." , upload_max_filesize=".$upload_max_filesize."\n게시판관리자 또는 서버관리자에게 문의 바랍니다."
		));
	}


	$bo_table = "NX_EVENT_MASTER";


	// 디렉토리가 없다면 생성합니다. (퍼미션도 변경하구요.)
	@mkdir(G5_DATA_PATH.'/file/'.$bo_table, G5_DIR_PERMISSION);
	@chmod(G5_DATA_PATH.'/file/'.$bo_table, G5_DIR_PERMISSION);


	$chars_array = array_merge(range(0,9), range('a','z'), range('A','Z'));

	// 가변 파일 업로드
	$file_upload_msg = '';
	$upload = array();
	for ($i=0; $i<count($_FILES['em_file']['name']); $i++)
	{
		$upload[$i]['file']     = '';
		$upload[$i]['source']   = '';
		$upload[$i]['filesize'] = 0;
		$upload[$i]['image']    = array();
		$upload[$i]['image'][0] = '';
		$upload[$i]['image'][1] = '';
		$upload[$i]['image'][2] = '';

		$upload[$i]['del_check'] = false;

		$tmp_file  = $_FILES['em_file']['tmp_name'][$i];
		$filesize  = $_FILES['em_file']['size'][$i];
		$filename  = $_FILES['em_file']['name'][$i];
		$filename  = get_safe_filename($filename);

		// 서버에 설정된 값보다 큰파일을 업로드 한다면
		if ($filename) {
			if ($_FILES['em_file']['error'][$i] == 1) {
				$file_upload_msg .= '\"'.$filename.'\" 파일의 용량이 서버에 설정('.$upload_max_filesize.')된 값보다 크므로 업로드 할 수 없습니다.\\n';
				continue;
			}
			else if ($_FILES['em_file']['error'][$i] != 0) {
				$file_upload_msg .= '\"'.$filename.'\" 파일이 정상적으로 업로드 되지 않았습니다.\\n';
				continue;
			}
		}

		if (is_uploaded_file($tmp_file))
		{
		    // 아래 확장자만 업로드 가능
		    // gill-039.평진원 Editor 보안취약점 조치
		    // 1.2. 화이트 리스트 방식의 업로드 파일의 확장자 체크
		    if (!preg_match("/\.(".G5_FILE_UPDATE_WHITE_LIST.")/i", $filename)) {
		        $file_upload_msg .= '업로드가 제한된 파일 확장자 입니다.\\n';
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

			$upload[$i]['image'] = $timg;

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
			$error_code = move_uploaded_file($tmp_file, $dest_file) or die($_FILES['em_file']['error'][$i]);

			// 올라간 파일의 퍼미션을 변경합니다.
			chmod($dest_file, G5_FILE_PERMISSION);
		}
	}


	# get EM_JOIN_PREFIX 정보
	$ret = NX_EVT_MA::GET_JOIN_PREFIX($EM_S_DATE);
	if (!$ret['success']) {
		echo_json($ret);
	}
	$EM_JOIN_PREFIX = $ret['EM_JOIN_PREFIX'];
	unset($ret);


	# chk : EM_JOIN_PREFIX
	if ($EM_JOIN_PREFIX == '') {
		echo_json(array(
			'success'=>(boolean)false,
			'msg'=>"행사참석 번호 접두어 생성오류\n\n시스템 관리자에게 문의해 주세요."
		));
	}


	# insert : event master
	$sql = "Insert Into NX_EVENT_MASTER("
		."EC_IDX"
		.", EP_IDX"
		.", mb_id"
		.", EM_TITLE"
		.", EM_CONT"
		.", EM_TYPE"
		.", EM_CG_NAME"
		.", EM_CG_TEL"
		.", EM_CG_EMAIL"
		.", EM_CERT_TITLE"
		.", EM_CERT_TIME"
		.", EM_CERT_MINUTE"
		.", EM_CERT_ORG_YN"
		.", EM_REQUIRE_BIRTH_YN"
		.", EM_S_DATE"
		.", EM_E_DATE"
		.", EM_S_TIME"
		.", EM_E_TIME"
		.", EM_JOIN_TYPE"
		.", EM_JOIN_S_DATE"
		.", EM_JOIN_E_DATE"
		.", EM_JOIN_MAX"
		.", EM_JOIN_PREFIX"
		.", EM_ADDR"
		.", EM_NOTI_EMAIL"
		.", EM_NOTI_SMS"
		.", EM_OPEN_YN"
		.", EM_WDATE"
		.", EM_WIP"
		.") values("
		. (($EC_IDX > 0) ? "'" . mres($EC_IDX) . "'" : 'null')
		.", " . (($EP_IDX > 0) ? "'" . mres($EP_IDX) . "'" : 'null')
		.", '" . mres($member['mb_id']) . "'"
		.", '" . mres($EM_TITLE) . "'"
		.", '" . mres($EM_CONT) . "'"
		.", '" . mres($EM_TYPE) . "'"
		.", '" . mres($EM_CG_NAME) . "'"
		.", '" . mres($EM_CG_TEL) . "'"
		.", '" . mres($EM_CG_EMAIL) . "'"
		.", " . (($EM_CERT_TITLE != '') ? "'" . mres($EM_CERT_TITLE) . "'" : 'null')
		.", " . (($EM_CERT_TIME != '') ? "'" . mres($EM_CERT_TIME) . "'" : 'null')
		.", " . (($EM_CERT_MINUTE != '') ? "'" . mres($EM_CERT_MINUTE) . "'" : 'null')
		.", '" . mres($EM_CERT_ORG_YN) . "'"
		.", '" . mres($EM_REQUIRE_BIRTH_YN) . "'"
		.", '" . mres($EM_S_DATE) . "'"
		.", '" . mres($EM_E_DATE) . "'"
		.", '" . mres($EM_S_TIME) . "'"
		.", '" . mres($EM_E_TIME) . "'"
		.", '" . mres($EM_JOIN_TYPE) . "'"
		.", '" . mres($EM_JOIN_S_DATE) . "'"
		.", '" . mres($EM_JOIN_E_DATE) . "'"
		.", '" . mres($EM_JOIN_MAX) . "'"
		.", '" . mres($EM_JOIN_PREFIX) . "'"
		.", '" . mres($EM_ADDR) . "'"
		.", '" . mres($EM_NOTI_EMAIL) . "'"
		.", '" . mres($EM_NOTI_SMS) . "'"
		.", '" . mres($EM_OPEN_YN) . "'"
		.", now()"
		.", inet_aton('" . mres($_SERVER['REMOTE_ADDR']) . "')"
		.")"
		;
	sql_query($sql);


	# set : EM_IDX
	$EM_IDX = sql_insert_id();


	/* upload file fk update */
	for ($i=0; $i<count($upload); $i++)
	{
		if (!get_magic_quotes_gpc()) {
			$upload[$i]['source'] = addslashes($upload[$i]['source']);
		}

		$sql = " insert into {$g5['board_file_table']}
					set bo_table = '{$bo_table}',
						 wr_id = '{$EM_IDX}',
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


	# update XX_FORM_ITEM
	$sql = "Update NX_EVENT_FORM_ITEM Set EM_IDX = '" . mres($EM_IDX) . "' Where FI_DDATE is null And EM_IDX is null And FI_RNDCODE = '" . mres($FI_RNDCODE) . "'";
	sql_query($sql);


	# 달력에 행사 등록
	include_once G5_PLUGIN_PATH . '/nx/class.NX_EVT_CAL.php';
	NX_EVT_CAL::SET_ADD(array(
		'EM_IDX'=>(int)$EM_IDX
		, 'EM_TITLE'=>$EM_TITLE
		, 'EM_S_DATE'=>$EM_S_DATE
		, 'EM_E_DATE'=>$EM_E_DATE
		, 'EM_CONT'=>$EM_CONT
	));
	

	echo_json(array(
		'success'=>(boolean)true, 
		'redir'=>'evt.list.php?'.$epTail
	));
?>
