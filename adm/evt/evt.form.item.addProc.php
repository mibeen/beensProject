<?php
	include_once './_common.php';
	include_once('./evt.err.php'); 	# $sub_menu = ($EP_IDX > 0) ? '980100' : '990100'
	if (!defined('_GNUBOARD_')) exit;
	if ($ret = auth_check($auth[$sub_menu], "r", true, true)) {
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>$ret
		));
	}


	# set : variables
	$EM_IDX = $_POST['EM_IDX'];
	$FI_RNDCODE = $_POST['FI_RNDCODE'];

	$FI_KIND = $_POST['FI_KIND'];
	$FI_NAME = $_POST['FI_NAME'];
	$FI_EXPL = $_POST['FI_EXPL'];
	$FI_REQ_YN = $_POST['FI_REQ_YN'];
	$FI_COND_YN = $_POST['FI_COND_YN'];
	$FI_USE_YN = $_POST['FI_USE_YN'];
	$FI_TYPE_A = $_POST['FI_TYPE_A'];
	$FI_TYPE_E = $_POST['FI_TYPE_E'];
	$FI_EXT_TYPE = $_POST['FI_EXT_TYPE'];
	$FI_MAX_SIZE = $_POST['FI_MAX_SIZE'];


	# re-define : variables
	$EM_IDX = CHK_NUMBER($EM_IDX);

	$FI_KIND = (NX_EVENT_FORM_BUILDER::GET_FI_KIND_TO_STR($FI_KIND) != '') ? "{$FI_KIND}" : "A";
	$FI_REQ_YN = F_YN($FI_REQ_YN);
	$FI_COND_YN = F_YN($FI_COND_YN);
	$FI_USE_YN = F_YN($FI_USE_YN);
	$FI_EXT_TYPE = strtolower($FI_EXT_TYPE);
	$FI_MAX_SIZE = CHK_NUMBER($FI_MAX_SIZE);
	if ($FI_KIND == 'G' && ($FI_MAX_SIZE > 0 && $FI_MAX_SIZE < 1000)) {
		$FI_MAX_SIZE = (int)$FI_MAX_SIZE * (1024*1024);
	}
	else {
		$FI_MAX_SIZE = (int)'0';
	}

	if ($FI_KIND == 'A') { 			# 한줄텍스트
		$FI_TYPE = (NX_EVENT_FORM_BUILDER::GET_FI_TYPE_TO_STR($FI_KIND, $FI_TYPE_A) != '') ? "{$FI_TYPE_A}" : "";
	} else if ($FI_KIND == 'E') { 	# 날짜
		$FI_TYPE = (NX_EVENT_FORM_BUILDER::GET_FI_TYPE_TO_STR($FI_KIND, $FI_TYPE_E) != '') ? "{$FI_TYPE_E}" : "";
	} else {
		$FI_TYPE = "";
	}


	# chk : rfv.
	$rfvs = array(
		array('f'=> 'FI_NAME', 'str'=>"항목이름")
		);

	if ($FI_KIND == 'A') { 			# 한줄텍스트
		array_push($rfvs, array('f'=>'FI_TYPE', 'str'=>"항목타입"));
	}

	for ($i = 0; $i < Count($rfvs); $i++)
	{
		$rfv = $rfvs[$i];
		if (${$rfv['f']} == "") {
			echo_json(array(
				'success'=>(boolean)false, 
				'msg'=>"필수 항목이 누락되었습니다.\n\n누락항목 : " . $rfv['str']
			));
			break;
		}
	}
	unset($rfvs, $rfv);


	# wh
	if ($EM_IDX > 0) {
		$wh .= " And FI.EM_IDX = '" . mres($EM_IDX) . "'";
	}
	else {
		$wh .= " And FI.FI_RNDCODE = '" . mres($FI_RNDCODE) . "'";
	}


	$sql = "Select ifnull(max(FI_SEQ), 0) As cnt1"
		. "		From NX_EVENT_FORM_ITEM As FI"
		. "		{$wh}";

	$db1 = sql_query($sql);
	$rs1 = sql_fetch_array($db1);
	$FI_SEQ = $rs1['cnt1'] + 1;
	unset($db1, $rs1);


	# insert : NX_EVENT_FORM_ITEM
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
		. ", " . (($EM_IDX > 0) ? "'" . mres($EM_IDX) . "'" : 'null')
		. ", '" . mres($FI_RNDCODE) . "'"
		. ", '" . mres($FI_SEQ) . "'"
		. ", '" . mres($FI_KIND) . "'"
		. ", '" . mres($FI_NAME) . "'"
		. ", '" . mres($FI_EXPL) . "'"
		. ", '" . mres($FI_REQ_YN) . "'"
		. ", '" . mres($FI_COND_YN) . "'"
		. ", '" . mres($FI_USE_YN) . "'"
		. ", 'N'"
		. ", '" . mres($FI_TYPE) . "'"
		. ", '" . mres($FI_EXT_TYPE) . "'"
		. ", '" . mres($FI_MAX_SIZE) . "'"
		. ", now()"
		. ", inet_aton('" . mres($_SERVER["REMOTE_ADDR"]) . "')"
		. ")"
		;
	sql_query($sql);


	# get : FI_IDX
	$FI_IDX = sql_insert_id();


	if($FI_KIND == "C" || $FI_KIND == "D")
	{
		$FO_IDX = $_POST["FO_IDX"];
		$FO_VAL = $_POST["FO_VAL"];


		for($i = 0; $i < count($FO_IDX); $i++)
		{
			# insert : NX_EVENT_FORM_OPT
			$sql = "Insert Into NX_EVENT_FORM_OPT("
				. "FI_IDX"
				. ", FO_SEQ"
				. ", FO_VAL"
				. ", FO_WDATE"
				. ", FO_WIP"
				. ") values("
				. "'" . mres($FI_IDX) . "'"
				. ", '" . mres($i + 1) . "'"
				. ", '" . mres($FO_VAL[$i]) . "'"
				. ", now()"
				. ", inet_aton('" . mres($_SERVER["REMOTE_ADDR"]) . "')"
				. ")"
				;
			sql_query($sql);
		}
	}


	# delete : NX_EVENT_FORM_COND
	$sql = "Delete From NX_EVENT_FORM_COND Where FI_IDX = '" . mres($FI_IDX) . "'";
	sql_query($sql);


	# 테이블 최적화
	$sql = "Optimize Table NX_EVENT_FORM_COND";
	sql_query($sql);


	if($FI_COND_YN == "Y")
	{
		$FC_FI_IDX = $_POST["FC_FI_IDX"];
		$FC_FO_IDX = $_POST["FC_FO_IDX"];
		$cnt = 0;


		if(is_array($FC_FI_IDX)) {
			foreach($FC_FI_IDX as $key1 => $val1) {
				if($val1 != "" && is_array($FC_FO_IDX[$key1])) {
					foreach($FC_FO_IDX[$key1] as $key2 => $val2) {
						if($val2 != "") {
							# insert : NX_EVENT_FORM_COND
							$sql = "Insert Into NX_EVENT_FORM_COND("
								. "FI_IDX"
								. ", FC_FI_IDX"
								. ", FC_FO_IDX"
								. ") Values("
								. "'" . mres($FI_IDX) . "'"
								. ", '" . mres($val1) . "'"
								. ", '" . mres($val2) . "'"
								. ")"
								;
							sql_query($sql);

							$cnt++;
						}
					}
				}
			}
		}


		if($cnt == 0) {
			# update : NX_EVENT_FORM_ITEM
			$sql = "Update NX_EVENT_FORM_ITEM Set"
				. " FI_COND_YN = 'N'"
				. " Where FI_IDX = '" . mres($FI_IDX) . "' limit 1"
				;
			sql_query($sql);
		}
	}


	# re-direct
	$reDir = "evt.form.item.list.php?".$epTail."EM_IDX=".$EM_IDX."&FI_RNDCODE=".$FI_RNDCODE;


	echo_json(array(
		'success'=>(boolean)true, 
		'redir'=>$reDir
	));
?>
