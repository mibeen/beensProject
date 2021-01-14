<?php
	include_once('./_common.php');
	include_once('./evt.err.php'); 	# $sub_menu = ($EP_IDX > 0) ? '980100' : '990100'

	auth_check($auth[$sub_menu], "r");


	# chk : rfv.
	if ($EM_IDX <= 0) {
		header("location:evt.list.php?".$epTail);
		exit();
	}


	#----- get : 설문 항목 배열
	$sql = "Select ES_IDX, ES_QUESTION"
		." 	From NX_EVENT_SATISFY"
		." 	Where ES_DDATE is null"
		."		And EM_IDX = '" . mres($EM_IDX) . "'"
		." 	Order By ES_IDX Asc"
		;
	$db1 = sql_query($sql);

	$es_val = array();
	while ($rs1 = sql_fetch_array($db1)) {
		$es_val[$rs1['ES_IDX']] = $rs1['ES_QUESTION'];
	}
	#####


	#----- 설문 항목 답안 배열
	$sql = "Select EJ_IDX, ES_IDX, EV_VAL From NX_EVENT_SATISFY_VAL Where EV_DDATE is null And EM_IDX = '" . mres($EM_IDX) . "' Group By EJ_IDX, ES_IDX, EV_VAL";
	$db1 = sql_query($sql);

	$ev_val = array();
	while ($rs1 = sql_fetch_array($db1)) {
		$ev_val[$rs1['EJ_IDX'].'_'.$rs1['ES_IDX']] = $rs1['EV_VAL'];
	}
	#####


	header("Content-Type: text/csv; charset=utf-8");
	header("Content-Disposition: attachment; fileName=".iconv('utf-8', 'euc-kr', '참여자별_만족도')."_" . date("ymd") . ".csv");
	header("Content-Description: PHP" . phpversion() . " Generated Data");
	echo("\xEF\xBB\xBF");


	/*	CSV 용 string 으로 변환
		- 전달된 값에 '"' 를 더함
		- 전달된 값의 '"' 를 '""' 로 치환
	*/
	function F_CSVSTR($v)
	{
		if ($v == '') return;

		$ret = "\"" . str_replace("\"", "\"\"", $v) . "\"";
		return $ret;
	}


	# header
	echo F_CSVSTR("NO.");
	echo "," . F_CSVSTR("참석자명");
	echo "," . F_CSVSTR("접수번호");

	foreach ($es_val as $key => $value) {
		echo "," . F_CSVSTR(F_hsc($value));
	}

	echo("\r\n");


	# data
	# get : 설문참여자
	$sql = "Select EJ.EJ_IDX, EJ.EJ_NAME, EJ.EJ_JOIN_CODE"
		."	From NX_EVENT_JOIN As EJ"
		."		Inner Join ("
		."			Select EJ_IDX, Count(*) As cnt From NX_EVENT_SATISFY_VAL Where EV_DDATE is null And EM_IDX = '" . mres($EM_IDX) . "' Group By EJ_IDX"
		."		) As a On a.EJ_IDX = EJ.EJ_IDX"
		."	Where EJ.EJ_DDATE is null"
		."	Order By EJ.EJ_IDX Asc"
		;
	$db1 = sql_query($sql);

	$s = 0;
	while ($rs1 = sql_fetch_array($db1))
	{
		echo F_CSVSTR($s + 1);
		echo "," . F_CSVSTR(F_hsc($rs1['EJ_NAME']));
		echo "," . F_CSVSTR(F_hsc($rs1['EJ_JOIN_CODE']));

		foreach ($es_val as $key => $value) {
			if (!is_null($ev_val[$rs1['EJ_IDX'].'_'.$key])) {
				echo "," . F_CSVSTR(F_hsc($ev_val[$rs1['EJ_IDX'].'_'.$key]));
			}
			else {
				echo ",\" \"";
			}
		}
		$s++;
	}
	exit;
?>
