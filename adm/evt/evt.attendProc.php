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
	$m = $_POST['m'];
	$EM_IDX = $_POST['EM_IDX'];
	$EJ_IDX = $_POST['EJ_IDX'];
	$EJ_JOIN_CHK = $_POST['EJ_JOIN_CHK'];
	$EJ_MEMO = $_POST['EJ_MEMO'];
	$v = $_POST['v'];


	# re-define
	$m = (in_array($m, array('Y','N','1','2','allY','allN','edit'))) ? (string)$m : '';
	$EM_IDX = CHK_NUMBER($EM_IDX);
	$EJ_IDX = CHK_NUMBER($EJ_IDX);
	$EJ_JOIN_CHK = F_YN($EJ_JOIN_CHK, 'N');


	# chk : rfv.
	if ($EM_IDX <= 0 || $m == '' || (in_array($m, array('Y','N')) && $EJ_IDX <= 0) || (in_array($m, array('1','2')) && $v == '')) {
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>"필수 항목이 누락되었습니다."
		));
	}


	if (in_array($m, array('1','2'))) {
		$_ts = explode('|', $v);
		$_v = '';
		for ($i = 0; $i < Count($_ts); $i++) {
			$_t = $_ts[$i];
			if ($_t == '' || (int)$_t <= 0) continue;

			if ($_v != '') $_v .= ',';
			$_v .= "'".$_t."'";
		}
		
		# 선택 미승인
		if ($m == '1') {
			$sql = "Update NX_EVENT_JOIN Set"
				."		EJ_JOIN_CHK1 = 'N'"
				."		, EJ_JOIN_CHK2 = 'N'"
				."	Where EJ_DDATE is null"
				."		And EM_IDX = '" . mres($EM_IDX) . "'"
				."		And EJ_IDX in ({$_v})"
				;
			sql_query($sql);
		}
		# 선택 승인
		else if ($m == '2') {
			$sql = "Update NX_EVENT_JOIN Set"
				."		EJ_JOIN_CHK1 = 'Y'"
				."		, EJ_JOIN_CHK2 = 'Y'"
				."	Where EJ_DDATE is null"
				."		And EM_IDX = '" . mres($EM_IDX) . "'"
				."		And EJ_IDX in ({$_v})"
				;
			sql_query($sql);
		}
	}
	# 미참석 처리
	else if ($m == 'N') {
		$sql = "Update NX_EVENT_JOIN Set"
			."		EJ_JOIN_CHK1 = 'N'"
			."		, EJ_JOIN_CHK2 = 'N'"
			."	Where EJ_DDATE is null"
			."		And EM_IDX = '" . mres($EM_IDX) . "'"
			."		And EJ_IDX = '" . mres($EJ_IDX) . "'"
			."	Limit 1"
			;
		sql_query($sql);
	}
	# 참석 처리
	else if ($m == 'Y') {
		$sql = "Update NX_EVENT_JOIN Set"
			." 		EJ_JOIN_CHK1 = 'Y'"
			."		, EJ_JOIN_CHK2 = 'Y'"
			."	Where EJ_DDATE is null"
			."		And EM_IDX = '" . mres($EM_IDX) . "'"
			."		And EJ_IDX = '" . mres($EJ_IDX) . "'"
			."	Limit 1"
			;
		sql_query($sql);
	}
	# 일괄 참석 처리
	else if ($m == 'allY') {
		$sql = "Update NX_EVENT_JOIN Set"
			." 		EJ_JOIN_CHK1 = 'Y'"
			."		, EJ_JOIN_CHK2 = 'Y'"
			."	Where EJ_DDATE is null"
			."		And EM_IDX = '" . mres($EM_IDX) . "'"
			;
		sql_query($sql);
	}
	# 일괄 참석 취소
	else if ($m == 'allN') {
		$sql = "Update NX_EVENT_JOIN Set"
			." 		EJ_JOIN_CHK1 = 'N'"
			."		, EJ_JOIN_CHK2 = 'N'"
			."	Where EJ_DDATE is null"
			."		And EM_IDX = '" . mres($EM_IDX) . "'"
			;
		sql_query($sql);
	}
	# 수정
	else if ($m == 'edit') {
		$sql = "Update NX_EVENT_JOIN Set"
			."		EJ_JOIN_CHK1 = '" . mres($EJ_JOIN_CHK) . "'"
			."		, EJ_JOIN_CHK2 = '" . mres($EJ_JOIN_CHK) . "'"
			."		, EJ_MEMO = '" . mres($EJ_MEMO) . "'"
			." 	Where EJ_DDATE is null"
			." 		And EM_IDX = '" . mres($EM_IDX) . "'"
			."		And EJ_IDX = '" . mres($EJ_IDX) . "'"
			."	Limit 1"
			;
		sql_query($sql);
	}



	echo_json(array(
		'success'=>(boolean)true, 
		'redir'=>'evt.attend.list.php?'.$epTail.'EM_IDX='.$EM_IDX
	));
?>
