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
	// $pre_EM_IDX = $_REQUEST['EM_IDX'];
	$pre_EM_IDX = $_REQUEST['EM_IDX'];


	# rfv : chk
	if ($pre_EM_IDX == "") {
		echo_json(array(
			'success'=>(boolean)false,
			'msg'=>"존재하지 않는 정보 입니다. 복제에 실패했습니다."
		));
	}

	$sql = "Select EM.*"
		."	From NX_EVENT_MASTER As EM"
		."	Where EM.EM_DDATE is null"
		."		And EM.EM_IDX = '" . mres($pre_EM_IDX) . "'"
		."	Limit 1"
		;
	$rs1 = sql_fetch($sql);
	if (is_null($rs1['EM_IDX'])) {
		unset($rs1);
		echo_json(array(
			'success'=>(boolean)false,
			'msg'=>"존재하지 않는 정보 입니다. 복제에 실패했습니다."
		));
	}


	# get EM_JOIN_PREFIX 정보
	$ret = NX_EVT_MA::GET_JOIN_PREFIX($rs1['EM_S_DATE']);
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
		. (($rs1['EC_IDX'] > 0) ? "'" . mres($rs1['EC_IDX']) . "'" : 'null')
		.", " . (($rs1['EP_IDX'] > 0) ? "'" . mres($rs1['EP_IDX']) . "'" : 'null')
		.", '" . mres($member['mb_id']) . "'"
		.", '[복제] " . mres($rs1['EM_TITLE']) . "'"
		.", '" . mres($rs1['EM_CONT']) . "'"
		.", '" . mres($rs1['EM_TYPE']) . "'"
		.", '" . mres($rs1['EM_CG_NAME']) . "'"
		.", '" . mres($rs1['EM_CG_TEL']) . "'"
		.", '" . mres($rs1['EM_CG_EMAIL']) . "'"
		.", " . (($rs1['EM_CERT_TITLE'] != '') ? "'" . mres($rs1['EM_CERT_TITLE']) . "'" : 'null')
		.", " . (($rs1['EM_CERT_TIME'] != '') ? "'" . mres($rs1['EM_CERT_TIME']) . "'" : 'null')
		.", " . (($rs1['EM_CERT_MINUTE'] != '') ? "'" . mres($rs1['EM_CERT_MINUTE']) . "'" : 'null')
		.", '" . mres($rs1['EM_CERT_ORG_YN']) . "'"
		.", '" . mres($rs1['EM_REQUIRE_BIRTH_YN']) . "'"
		.", '" . mres($rs1['EM_S_DATE']) . "'"
		.", '" . mres($rs1['EM_E_DATE']) . "'"
		.", '" . mres($rs1['EM_S_TIME']) . "'"
		.", '" . mres($rs1['EM_E_TIME']) . "'"
		.", '" . mres($rs1['EM_JOIN_TYPE']) . "'"
		.", '" . mres($rs1['EM_JOIN_S_DATE']) . "'"
		.", '" . mres($rs1['EM_JOIN_E_DATE']) . "'"
		.", '" . mres($rs1['EM_JOIN_MAX']) . "'"
		.", '" . mres($EM_JOIN_PREFIX) . "'"
		.", '" . mres($rs1['EM_ADDR']) . "'"
		.", '" . mres($rs1['EM_NOTI_EMAIL']) . "'"
		.", '" . mres($rs1['EM_NOTI_SMS']) . "'"
		.", 'N'"
		.", now()"
		.", inet_aton('" . mres($_SERVER['REMOTE_ADDR']) . "')"
		.")"
		;
	sql_query($sql);


	# set : EM_IDX
	$EM_IDX = sql_insert_id();


	#---- 파일
	$bo_table = "NX_EVENT_MASTER";


	# 디렉토리가 없다면 생성 + 퍼미션 변경
	@mkdir(G5_DATA_PATH.'/file/'.$bo_table, G5_DIR_PERMISSION);
	@chmod(G5_DATA_PATH.'/file/'.$bo_table, G5_DIR_PERMISSION);


	$chars_array = array_merge(range(0,9), range('a','z'), range('A','Z'));


	# copy : file + file record
	$_files = get_file($bo_table, $pre_EM_IDX);
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
				.", '" . mres($EM_IDX) . "'"
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
				." And wr_id = '" . mres($pre_EM_IDX) . "'"
				." And bf_no = '" . mres($i) . "'"
				." Order By bf_no Asc"
				;
			sql_query($sql);
		}
	}
	#####


	#---- 폼빌더
	# COND 연결을 위해 복제전, 후 IDX를 key:value 페어로 배열에 저장.
	$arr_FI_IDX = array();
	$arr_FO_IDX = array();


	# insert : NX_EVENT_FORM_ITEM
	$sql = "Select *"
		."	From NX_EVENT_FORM_ITEM"
		."	Where FI_DDATE is null And EM_IDX = '" . mres($pre_EM_IDX) . "'"
		;
	$db2 = sql_query($sql);

	$FI_RNDCODE = uniqid();
	while ($rs2 = sql_fetch_array($db2)) {
		$sql = "Insert Into NX_EVENT_FORM_ITEM("
			. "mb_id"
			. ", EM_IDX"
			. ", FI_RNDCODE"
			. ", FI_SEQ"
			. ", FI_KIND"
			. ", FI_NAME"
			. ", FI_EXPL"
			. ", FI_REQ_YN"
			. ", FI_COND_YN"
			. ", FI_USE_YN"
			. ", FI_DEFAULT_YN"
			. ", FI_TYPE"
			. ", FI_EXT_TYPE"
			. ", FI_MAX_SIZE"
			. ", FI_WDATE"
			. ", FI_WIP"
			. ") values("
			. "'" . mres($member['mb_id']) . "'"
			. ", '" . mres($EM_IDX) . "'"
			. ", '" . mres($FI_RNDCODE) . "'"
			. ", '" . mres($rs2['FI_SEQ']) . "'"
			. ", '" . mres($rs2['FI_KIND']) . "'"
			. ", '" . mres($rs2['FI_NAME']) . "'"
			. ", '" . mres($rs2['FI_EXPL']) . "'"
			. ", '" . mres($rs2['FI_REQ_YN']) . "'"
			. ", '" . mres($rs2['FI_COND_YN']) . "'"
			. ", '" . mres($rs2['FI_USE_YN']) . "'"
			. ", 'N'"
			. ", '" . mres($rs2['FI_TYPE']) . "'"
			. ", '" . mres($rs2['FI_EXT_TYPE']) . "'"
			. ", '" . mres($rs2['FI_MAX_SIZE']) . "'"
			. ", now()"
			. ", inet_aton('" . mres($_SERVER["REMOTE_ADDR"]) . "')"
			. ")"
			;
		sql_query($sql);

		$_FI_IDX = sql_insert_id();


		$arr_FI_IDX[$rs2['FI_IDX']] = $_FI_IDX;


		# insert : NX_EVENT_FORM_OPT
		$sql = "Select *"
			."	From NX_EVENT_FORM_OPT"
			."	Where FO_DDATE is null And FI_IDX = '" . mres($rs2['FI_IDX']) . "'"
			;
		$db_FO = sql_query($sql);

		if (sql_num_rows($db_FO) >= 1) {
			while ($rs_FO = sql_fetch_array($db_FO)) {
				$sql = "Insert Into NX_EVENT_FORM_OPT("
					. "FI_IDX"
					. ", FO_SEQ"
					. ", FO_VAL"
					. ", FO_WDATE"
					. ", FO_WIP"
					. ") values("
					. "'" . mres($_FI_IDX) . "'"
					. ", '" . mres($rs_FO['FO_SEQ']) . "'"
					. ", '" . mres($rs_FO['FO_VAL']) . "'"
					. ", now()"
					. ", inet_aton('" . mres($_SERVER["REMOTE_ADDR"]) . "')"
					. ")"
					;
				sql_query($sql);

				$_FO_IDX = sql_insert_id();


				$arr_FO_IDX[$rs_FO['FO_IDX']] = $_FO_IDX;
			}
		}


		# insert : NX_EVENT_FORM_COND
		$sql = "Select *"
			."	From NX_EVENT_FORM_COND"
			."	Where FI_IDX = '" . mres($rs2['FI_IDX']) . "'"
			;
		$db_FC = sql_query($sql);

		if (sql_num_rows($db_FC) >= 1) {
			while ($rs_FC = sql_fetch_array($db_FC)) {
				$sql = "Insert Into NX_EVENT_FORM_COND("
					. "FI_IDX"
					. ", FC_FI_IDX"
					. ", FC_FO_IDX"
					. ") Values("
					. "'" . mres($_FI_IDX) . "'"
					. ", '" . mres($arr_FI_IDX[$rs_FC['FC_FI_IDX']]) . "'"
					. ", '" . mres($arr_FO_IDX[$rs_FC['FC_FO_IDX']]) . "'"
					. ")"
					;
				sql_query($sql);
			}
		}
	}
	unset($db2, $rs2);
	unset($db_FO, $rs_FO);
	unset($db_FC, $rs_FC);
	unset($arr_FI_IDX, $arr_FO_IDX);
	#####


	# 달력에 행사 등록
	include_once G5_PLUGIN_PATH . '/nx/class.NX_EVT_CAL.php';
	NX_EVT_CAL::SET_ADD(array(
		'EM_IDX'=>(int)$EM_IDX
		, 'EM_TITLE'=>$rs1['EM_TITLE']
		, 'EM_S_DATE'=>$rs1['EM_S_DATE']
		, 'EM_E_DATE'=>$rs1['EM_E_DATE']
		, 'EM_CONT'=>$rs1['EM_CONT']
	));


	# insert : NX_EVENT_NAMETAG_TEMPLATE
	$sql = "Select *"
		."	From NX_EVENT_NAMETAG_TEMPLATE"
		."	Where ENT_DDATE is null And EM_IDX = '" . mres($pre_EM_IDX) . "'"
		."	Limit 1"
		;
	$rs2 = sql_fetch($sql);

	if (!is_null($rs2['ENT_IDX'])) {
		$IS_COPY_MASTER = 'Y';
		$ENT_IDX = $rs2['ENT_IDX'];

		include "evt.nametag.tpl.copyProc.php";
	}
	unset($rs2);


	#---- 만족도 조사
	# insert : NX_EVT_SATI_MA
	$sql = "Select *"
		."	From NX_EVT_SATI_MA"
		."	Where EM_IDX = '" . mres($pre_EM_IDX) . "'"
		."	Limit 1"
		;	
	$rs2 = sql_fetch($sql);

	if (!is_null($rs2['EM_IDX'])) {
		$sql = "Insert Into NX_EVT_SATI_MA("
			."EM_IDX"
			.", mb_id"
			.", ESM_S_DATE"
			.", ESM_E_DATE"
			.") values("
			."'" . mres($EM_IDX) . "'"
			.", '" . mres($member['mb_id']) . "'"
			.", '" . mres($rs2['ESM_S_DATE']) . "'"
			.", '" . mres($rs2['ESM_E_DATE']) . "'"
			.")"
			;
		sql_query($sql);


		# insert : NX_EVT_SATI_DE
		$sql = "Select *"
			."	From NX_EVT_SATI_DE"
			."	Where EM_IDX = '" . mres($pre_EM_IDX) . "'"
			;	
		$db_SA = sql_query($sql);

		if (sql_num_rows($db_SA) >= 1) {
			while ($rs_SA = sql_fetch_array($db_SA)) {
				$sql = "Insert Into NX_EVT_SATI_DE("
					."EM_IDX"
					.", mb_id"
					.", ESD_QUES"
					.", ESD_WDATE"
					.", ESD_WIP"
					.") values("
					."'" . mres($EM_IDX) . "'"
					.", '" . mres($member['mb_id']) . "'"
					.", '" . mres($rs_SA['ESD_QUES']) . "'"
					.", now()"
					.", inet_aton('" . mres($_SERVER['REMOTE_ADDR']) . "')"
					.")"
					;
				sql_query($sql);
			}
		}
	}
	#####


	echo_json(array(
		'success'=>(boolean)true, 
		'redir'=>'evt.list.php?'.$epTail, 
		'msg'=>"정상적으로 복제되었습니다."
	));
?>