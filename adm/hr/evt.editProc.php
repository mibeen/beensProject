<?php
	include_once('./_common.php');
	include_once('./hr.err.php'); 	# $sub_menu = ($EP_IDX > 0) ? '980100' : '990100'

	if ($ret = auth_check($auth[$sub_menu], "w", true, true)) {
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>$ret
		));
	}


	# set : variables
	$EM_IDX = $_POST['EM_IDX'];
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
	
	$EM_S_DATE1 = $_POST['EM_S_DATE1'];
	$EM_S_DATE2 = $_POST['EM_S_DATE2'];
	$EM_S_DATE3 = $_POST['EM_S_DATE3'];
	$EM_S_TIME1 = $_POST['EM_S_TIME1'];
	$EM_S_TIME2 = $_POST['EM_S_TIME2'];

	$EM_E_DATE1 = $_POST['EM_E_DATE1'];
	$EM_E_DATE2 = $_POST['EM_E_DATE2'];
	$EM_E_DATE3 = $_POST['EM_E_DATE3'];
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
	$EM_IDX = CHK_NUMBER($EM_IDX);
	$EC_IDX = CHK_NUMBER($EC_IDX);
	$EM_TYPE = ((int)$EM_TYPE >= 1 && (int)$EM_TYPE <= 2) ? (int)$EM_TYPE : '';
	
	$EM_CG_TEL = "{$EM_CG_TEL1}-{$EM_CG_TEL2}-{$EM_CG_TEL3}";
	if ($EM_CG_TEL == '--') $EM_CG_TEL = '';
	
	$EM_JOIN_TYPE = ((int)$EM_JOIN_TYPE >= 1 && (int)$EM_JOIN_TYPE <= 3) ? (int)$EM_JOIN_TYPE : '';
	$EM_NOTI_EMAIL = F_YN($EM_NOTI_EMAIL,'N');
	$EM_NOTI_SMS = F_YN($EM_NOTI_SMS,'N');
	$EM_CERT_TITLE = trim($EM_CERT_TITLE);
	$EM_CERT_ORG_YN = F_YN($EM_CERT_ORG_YN,'Y');
	$EM_REQUIRE_BIRTH_YN = F_YN($EM_REQUIRE_BIRTH_YN,'N');
	$EM_OPEN_YN = F_YN($EM_OPEN_YN,'Y');
	
	$EM_S_DATE = "{$EM_S_DATE1}-{$EM_S_DATE2}-{$EM_S_DATE3}";
	if ($EM_S_DATE == '--') $EM_S_DATE = '';
	
	if ((int)$EM_S_TIME1 < 10) $EM_S_TIME1 = "0{$EM_S_TIME1}";
	if ((int)$EM_S_TIME2 < 10) $EM_S_TIME2 = "0{$EM_S_TIME2}";
	$EM_S_TIME = "{$EM_S_TIME1}:{$EM_S_TIME2}";
	if ($EM_S_TIME == ':') $EM_S_TIME = '';

	$EM_E_DATE = "{$EM_E_DATE1}-{$EM_E_DATE2}-{$EM_E_DATE3}";
	if ($EM_E_DATE == '--') $EM_E_DATE = '';
	
	if ((int)$EM_E_TIME1 < 10) $EM_E_TIME1 = "0{$EM_E_TIME1}";
	if ((int)$EM_E_TIME2 < 10) $EM_E_TIME2 = "0{$EM_E_TIME2}";
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
	if ($EM_IDX <= 0
	 || $EM_TITLE == '' || $EM_TYPE == ''
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
		    //'msg'=>'$EM_IDX:'.$EM_IDX.' / $EM_TITLE:'.$EM_TITLE.' / $EM_TYPE:'.$EM_TYPE.' / EM_CG_NAME:'.$EM_CG_NAME.' / $EM_CG_TEL:'.$EM_CG_TEL.' / $EM_CG_EMAIL:'.$EM_CG_EMAIL.' / $EM_JOIN_TYPE:'.$EM_JOIN_TYPE.' / $EM_OPEN_YN:'.$EM_OPEN_YN.' / $EM_S_DATE:'.$EM_S_DATE.' / $EM_S_TIME:'.$EM_S_TIME.' / $EM_E_DATE:'.$EM_E_DATE.' / $EM_E_TIME:'.$EM_E_TIME.' / $EM_JOIN_S_DATE:'.$EM_JOIN_S_DATE.' / $EM_JOIN_S_TIME:'.$EM_JOIN_S_TIME.' / $EM_JOIN_E_DATE:'.$EM_JOIN_E_DATE.' / $EM_JOIN_E_TIME:'.$EM_JOIN_E_TIME.' / $FI_RNDCODE:'.$FI_RNDCODE
		));
	}


	# get data
	$db1 = sql_fetch("Select EM_IDX, EM_S_DATE, EM_JOIN_PREFIX From NX_EVENT_HR_MASTER As EM Where EM.EM_DDATE is null And EM.EM_IDX = '" . mres($EM_IDX) . "' Limit 1");
	if (is_null($db1['EM_IDX'])) {
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>"요청한 항목이 존재하지 않습니다."
		));
	}
	$DB_EM = $db1;
	unset($db1);


	# 행사 시작일 변경의 경우
	if ($DB_EM['EM_S_DATE'] != $EM_S_DATE)
	{
		# 접수된 인원이 있을 경우 행사 시작일 변경 불가
		# 신청번호가 행사 시작일 기준으로 생성되기 때문
		# get : 접수된 인원 count
		$row = sql_fetch("Select Count(EJ_IDX) As cnt From NX_EVENT_HR_JOIN Where EJ_DDATE is null And EM_IDX = '" . mres($EM_IDX) . "'");
		if ($row['cnt'] > 0) {
			echo_json(array(
				'success'=>(boolean)false, 
				'msg'=>"신청한 대상이 있을 경우 '행사 시작일' 을 변경할 수 없습니다."
			));
		}
		unset($row);


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
	}
	else {
		$EM_JOIN_PREFIX = $DB_EM['EM_JOIN_PREFIX'];
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


        // 삭제에 체크가 되어있다면 파일을 삭제합니다.
        if ($_POST['em_file_del'][$i] == 'Y') {
            $upload[$i]['del_check'] = true;

            $row = sql_fetch(" select bf_file from {$g5['board_file_table']} where bo_table = '{$bo_table}' and wr_id = '{$EM_IDX}' and bf_no = '{$i}' ");
            @unlink(G5_DATA_PATH.'/file/'.$bo_table.'/'.$row['bf_file']);
            
            // 썸네일삭제
            if(preg_match("/\.({$config['cf_image_extension']})$/i", $row['bf_file'])) {
                delete_board_thumbnail($bo_table, $row['bf_file']);
            }
        }
        else {
	        $upload[$i]['del_check'] = false;
        }


	    $tmp_file  = $_FILES['em_file']['tmp_name'][$i];
	    $filesize  = $_FILES['em_file']['size'][$i];
	    $filename  = $_FILES['em_file']['name'][$i];
	    $filename  = get_safe_filename($filename);

	    // 서버에 설정된 값보다 큰파일을 업로드 한다면
	    if ($filename) {
	        if ($_FILES['bf_file']['error'][$i] == 1) {
	            $file_upload_msg .= '\"'.$filename.'\" 파일의 용량이 서버에 설정('.$upload_max_filesize.')된 값보다 크므로 업로드 할 수 없습니다.\\n';
	            continue;
	        }
	        else if ($_FILES['bf_file']['error'][$i] != 0) {
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


	        // 4.00.11 - 글답변에서 파일 업로드시 원글의 파일이 삭제되는 오류를 수정
            // 존재하는 파일이 있다면 삭제합니다.
            $row = sql_fetch(" select bf_file from {$g5['board_file_table']} where bo_table = '$bo_table' and wr_id = '$EM_IDX' and bf_no = '$i' ");
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
	        $error_code = move_uploaded_file($tmp_file, $dest_file) or die($_FILES['bf_file']['error'][$i]);

	        // 올라간 파일의 퍼미션을 변경합니다.
	        chmod($dest_file, G5_FILE_PERMISSION);
	    }
	}


	# update : event master
	$sql = "Update NX_EVENT_HR_MASTER Set"
		." EC_IDX = " . (($EC_IDX > 0) ? "'" . mres($EC_IDX) . "'" : 'null')
		.", EP_IDX = " . (($EP_IDX > 0) ? "'" . mres($EP_IDX) . "'" : 'null')
		.", EM_TITLE = '" . mres($EM_TITLE) . "'"
		.", EM_CONT = '" . mres($EM_CONT) . "'"
		.", EM_TYPE = '" . mres($EM_TYPE) . "'"
		.", EM_CG_NAME = '" . mres($EM_CG_NAME) . "'"
		.", EM_CG_TEL = '" . mres($EM_CG_TEL) . "'"
		.", EM_CG_EMAIL = '" . mres($EM_CG_EMAIL) . "'"
		.", EM_CERT_TITLE = " . (($EM_CERT_TITLE != '') ? "'" . mres($EM_CERT_TITLE) . "'" : 'null')
		.", EM_CERT_TIME = " . (($EM_CERT_TIME != '') ? "'" . mres($EM_CERT_TIME) . "'" : 'null')
		.", EM_CERT_MINUTE = " . (($EM_CERT_MINUTE != '') ? "'" . mres($EM_CERT_MINUTE) . "'" : 'null')
		.", EM_CERT_ORG_YN = '" . mres($EM_CERT_ORG_YN) . "'"
		.", EM_REQUIRE_BIRTH_YN = '" . mres($EM_REQUIRE_BIRTH_YN) . "'"
		.", EM_S_DATE = '" . mres($EM_S_DATE) . "'"
		.", EM_E_DATE = '" . mres($EM_E_DATE) . "'"
		.", EM_S_TIME = '" . mres($EM_S_TIME) . "'"
		.", EM_E_TIME = '" . mres($EM_E_TIME) . "'"
		.", EM_JOIN_TYPE = '" . mres($EM_JOIN_TYPE) . "'"
		.", EM_JOIN_S_DATE = '" . mres($EM_JOIN_S_DATE) . "'"
		.", EM_JOIN_E_DATE = '" . mres($EM_JOIN_E_DATE) . "'"
		.", EM_JOIN_MAX = '" . mres($EM_JOIN_MAX) . "'"
		.", EM_ADDR = '" . mres($EM_ADDR) . "'"
		.", EM_JOIN_PREFIX = '" . mres($EM_JOIN_PREFIX) . "'"
		.", EM_NOTI_EMAIL = '" . mres($EM_NOTI_EMAIL) . "'"
		.", EM_NOTI_SMS = '" . mres($EM_NOTI_SMS) . "'"
		.", EM_OPEN_YN = '" . mres($EM_OPEN_YN) . "'"
		.", EM_MDATE = now()"
		//.", EM_MIP = inet_aton('" . mres($_SERVER['REMOTE_ADDR']) . "')"
	       .", EM_MIP = '8.8.8.8'"
		." Where EM_IDX = '" . mres($EM_IDX) . "'"
		." Limit 1"
		;
	sql_query($sql);
    

	/* upload file fk update */
	for ($i=0; $i<count($upload); $i++)
	{
	    if (!get_magic_quotes_gpc()) {
	        $upload[$i]['source'] = addslashes($upload[$i]['source']);
	    }

        $row = sql_fetch(" select count(*) as cnt from {$g5['board_file_table']} where bo_table = '{$bo_table}' and wr_id = '{$EM_IDX}' and bf_no = '{$i}' ");
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
                                    and wr_id = '{$EM_IDX}'
                                    and bf_no = '{$i}' ";
                sql_query($sql);
            }
            else
            {
                $sql = " update {$g5['board_file_table']}
                            set bf_content = '{$bf_content[$i]}'
                            where bo_table = '{$bo_table}'
                                      and wr_id = '{$EM_IDX}'
                                      and bf_no = '{$i}' ";
                sql_query($sql);
            }
        }
        else
        {
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
	}


	# 달력에 등록된 행사 수정
	include_once G5_PLUGIN_PATH . '/nx/class.NX_EVT_CAL.php';
	NX_EVT_CAL::SET_EDIT(array(
		'EM_IDX'=>(int)$EM_IDX
		, 'EM_TITLE'=>$EM_TITLE
		, 'EM_S_DATE'=>$EM_S_DATE
		, 'EM_E_DATE'=>$EM_E_DATE
		, 'EM_CONT'=>$EM_CONT
	));


	echo_json(array(
		'success'=>(boolean)true, 
		'redir'=>'evt.list.php?'.$epTail, 
		'msg'=>"저장되었습니다."
	));
?>
