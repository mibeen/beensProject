<?php
	include_once('./_common.php');
	include_once('./evt.err.php'); 	# $sub_menu = ($EP_IDX > 0) ? '980100' : '990100'

	
	# set : variables
	$EM_IDX = $_POST['EM_IDX'];
	$ESM_S_DATE = $_POST['ESM_S_DATE'];
	$ESM_E_DATE = $_POST['ESM_E_DATE'];
	$nums = $_POST['nums'];


	# re-define
	$EM_IDX = CHK_NUMBER($EM_IDX);


	#----- chk : rfv.
	$rfv = array();
	if ($EM_IDX <= 0) $rfv[] = "잘못된 접근 입니다.";
	if ($ESM_S_DATE == '') $rfv[] = "접수기간(시작일) 정보를 입력해 주세요.";
	if ($ESM_E_DATE == '') $rfv[] = "접수기간(종료일) 정보를 입력해 주세요.";
	if ($nums == '') $rfv[] = "잘못된 접근 입니다.";

	for ($i = 0; $i < Count($rfv); $i++)
	{
		echo_json(array(
			'success'=>(boolean)false,
			'msg'=>$rfv[$i]
		));
	}
	#####


	# chk : event main record
	$row = sql_fetch("Select Count(EM_IDX) as cnt From NX_EVENT_MASTER Where EM_DDATE is null And EM_IDX = '" . mres($EM_IDX) . "'");
	if ($row['cnt'] <= 0) {
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>"잘못된 접근 입니다.",
			'redir'=>'evt.list.php?'.$epTail
		));
	}


	# insert/update : master record
	$sql = "Insert Into NX_EVT_SATI_MA("
		."EM_IDX"
		.", mb_id"
		.", ESM_S_DATE"
		.", ESM_E_DATE"
		.") values("
		."'" . mres($EM_IDX) . "'"
		.", '" . mres($member['mb_id']) . "'"
		.", '" . mres($ESM_S_DATE) . "'"
		.", '" . mres($ESM_E_DATE) . "'"
		.") On Duplicate key Update"
		." ESM_S_DATE = '" . mres($ESM_S_DATE) . "'"
		.", ESM_E_DATE = '" . mres($ESM_E_DATE) . "'"
		;
	sql_query($sql);


	# 전체 항목 pk 를 배열에 담음
	$sql = "Select ESD_IDX From NX_EVT_SATI_DE Where EM_IDX = '" . mres($EM_IDX) . "' Order By ESD_IDX Asc";
	$db1 = sql_query($sql);

	$db_pks = array();
	while ($rs1 = sql_fetch_array($db1)) {
		$db_pks[] = $rs1['ESD_IDX'];
	}


	$_t = explode('|', $nums);
	for ($i = 0; $i < Count($_t); $i++)
	{
		$num = $_t[$i];
		if ($num == '') continue;


		# set : variables
		$ESD_IDX = $_POST["ESD_IDX_{$num}"];
		$ESD_QUES = $_POST["ESD_QUES_{$num}"];


		# re-define
		$ESD_IDX = CHK_NUMBER($ESD_IDX);


		# update
		if ($ESD_IDX > 0) {
			$sql = "Update NX_EVT_SATI_DE Set"
				."		ESD_QUES = '" . mres($ESD_QUES) . "'"
				."		, ESD_MDATE = now()"
				."		, ESD_MIP = inet_aton('" . mres($_SERVER['REMOTE_ADDR']) . "')"
				."	Where EM_IDX = '" . mres($EM_IDX) . "'"
				."		And ESD_IDX = '" . mres($ESD_IDX) . "'"
				."	Limit 1"
				;

			# 전체 pk 배열에서 해당 value 제거
			if (($key = array_search($ESD_IDX, $db_pks)) !== false) {
				array_splice($db_pks, $key, 1);
			}
		}
		# insert
		else {
			$sql = "Insert Into NX_EVT_SATI_DE("
				."EM_IDX"
				.", mb_id"
				.", ESD_QUES"
				.", ESD_WDATE"
				.", ESD_WIP"
				.") values("
				."'" . mres($EM_IDX) . "'"
				.", '" . mres($member['mb_id']) . "'"
				.", '" . mres($ESD_QUES) . "'"
				.", now()"
				.", inet_aton('" . mres($_SERVER['REMOTE_ADDR']) . "')"
				.")"
				;
		}

		sql_query($sql);
	}


	# 삭제 대상 deldate update
	for ($i = 0; $i < Count($db_pks); $i++)
	{
		$sql = "Update NX_EVT_SATI_DE Set"
			." ESD_DDATE = now()"
			.", ESD_DIP = inet_aton('" . mres($_SERVER['REMOTE_ADDR']) . "')"
			." Where EM_IDX = '" . mres($EM_IDX) . "'"
			." And ESD_IDX = '" . mres($db_pks[$i]) . "'"
			." Limit 1"
			;
		sql_query($sql);
	}


	echo_json(array(
		'success'=>(boolean)true, 
		'msg'=>"저장 되었습니다."
	));
?>
