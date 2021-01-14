<?php
	include_once './_common.php';
	include_once('./evt.err.php'); 	# $sub_menu = ($EP_IDX > 0) ? '980100' : '990100'
	if (!defined('_GNUBOARD_')) exit;
	if ($ret = auth_check($auth[$sub_menu], "d", true, true)) {
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>$ret
		));
	}


	# set : variables
	$EM_IDX = $_POST['EM_IDX'];
	$FI_RNDCODE = $_POST['FI_RNDCODE'];
	$FI_IDX = $_POST['FI_IDX'];


	# re-define : variables
	$EM_IDX = CHK_NUMBER($EM_IDX);
	$FI_IDX = CHK_NUMBER($FI_IDX);


	# chk : rfv.
	if (($EM_IDX <= 0 && $FI_RNDCODE == '') || $FI_IDX <= 0) {
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>"필수 항목이 누락되었습니다."
		));
	}


	# get data
	$sql = "Select FI.FI_DEFAULT_YN"
		. "		From NX_EVENT_FORM_ITEM As FI"
		. "		{$wh} And FI.FI_IDX = '" . mres($FI_IDX) . "' limit 1"
		;
	$db1 = sql_query($sql);

	if (sql_num_rows($db1) == 0)
	{
		unset($db1);
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>"요청하신 정보가 존재하지 않습니다."
		));
	}

	$rs1 = sql_fetch_array($db1);

	$FI_DEFAULT_YN = $rs1["FI_DEFAULT_YN"];

	unset($db1, $rs1);


	if($FI_DEFAULT_YN == "Y") {
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>"일치하는 정보가 존재하지 않습니다."
		));
	}


	# update : NX_EVENT_FORM_ITEM > FI_DDATE
	$sql = "Update NX_EVENT_FORM_ITEM Set FI_DDATE = now(), FI_DIP = inet_aton('" . mres($_SERVER["REMOTE_ADDR"]) . "') Where FI_IDX = '" . mres($FI_IDX) . "' limit 1";
	sql_query($sql);


	$sql = "Select FI.FI_IDX"
		. "		From NX_EVENT_FORM_ITEM As FI"
		. "		{$wh}"
		. "			And (FI.EM_IDX = '" . mres($EM_IDX) . "' or FI.FI_RNDCODE = '" . mres($FI_RNDCODE) . "')"
		. "		Order By FI.FI_SEQ Asc, FI.FI_IDX Desc"
		;
	$db1 = sql_query($sql);

	$FI_SEQ = 0;
	while($rs1 = sql_fetch_array($db1)) {
		$FI_SEQ++;

		# update : NX_EVENT_FORM_ITEM
		$sql = "Update NX_EVENT_FORM_ITEM Set"
			. " FI_SEQ = '" . mres($FI_SEQ) . "'"
			. " Where FI_IDX = '" . mres($rs1["FI_IDX"]) . "' limit 1"
			;
		sql_query($sql);
	}


	# re-direct
	$reDir = "evt.form.item.list.php?".$epTail."EM_IDX={$EM_IDX}&FI_RNDCODE={$FI_RNDCODE}";


	echo_json(array(
		'success'=>(boolean)true, 
		'redir'=>$reDir
	));
?>
