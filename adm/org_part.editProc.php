<?php
	$sub_menu = "990300";
	include_once('_common.php');

	include_once('org_part.err.php');

	auth_check($auth[$sub_menu], "w");


	# set : variables
	$OP_IDX = $_POST['OP_IDX'];
	$OP_SEQ = $_POST['OP_SEQ'];
	$OP_PARENT_IDX = $_POST['OP_PARENT_IDX'];
	$OP_NAME = $_POST['OP_NAME'];

	
	# re-define
	$OP_IDX = CHK_NUMBER($OP_IDX);
	$OP_SEQ = CHK_NUMBER($OP_SEQ);
	$OP_PARENT_IDX = CHK_NUMBER($OP_PARENT_IDX);
	$OP_NAME = trim($OP_NAME);


	# chk : rfv.
	if ($OP_IDX == '' || $OP_SEQ == '' || $OP_PARENT_IDX == '' || $OP_NAME == '') {
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>"필수 항목이 누락되었습니다."
		));
	}


	# record chk
	$row = sql_fetch("Select OP_PARENT_IDX, OP_SEQ From ORG_PART Where OP_IDX = '" . mres($OP_IDX) . "'");
	$PARENT_IDX = $row['OP_PARENT_IDX'];
	$SEQ = $row['OP_SEQ'];
	unset($row);


	$basic_wh = "Where OP_DDATE is null And OP_PARENT_IDX = '" . mres($OP_PARENT_IDX) . "'";


	# 지정된 순번이 기존것보다 작을 경우
	if ($OP_SEQ < $SEQ)
	{
		# update : 전달된 SEQ 이상인 정보 +1
		$sql = "Update ORG_PART Set"
			." 		OP_SEQ = OP_SEQ + 1"
			." 	{$basic_wh}"
			."		And OP_SEQ >= '" . mres($OP_SEQ) . "' And OP_SEQ <= '" . mres($SEQ) . "'"
			."		And OP_IDX != '" . mres($OP_IDX) . "'"
			." 	Order By OP_SEQ Asc"
			;
		sql_query($sql);
	}
	# 전달된 순번이 기존것보다 클 경우
	else if ($OP_SEQ > $SEQ)
	{
		# update : 전달된 SEQ 이상인 정보 -1
		$sql = "Update ORG_PART Set"
			." 		OP_SEQ = OP_SEQ - 1"
			." 	{$basic_wh}"
			."		And OP_SEQ >= '" . mres($SEQ) . "' And OP_SEQ <= '" . mres($OP_SEQ) . "'"
			."		And OP_IDX != '" . mres($OP_IDX) . "'"
			." 	Order By OP_SEQ Asc"
			;
		sql_query($sql);
	}


	# insert
	$sql = "Update ORG_PART Set"
		." OP_PARENT_IDX = '" . mres($OP_PARENT_IDX) . "'"
		.", OP_SEQ = '" . mres($OP_SEQ) . "'"
		.", OP_NAME = '" . mres($OP_NAME) . "'"
		.", OP_MDATE = now()"
		.", OP_MIP = inet_aton('" . mres($_SERVER['REMOTE_ADDR']) . "')"
		." Where OP_IDX = '" . mres($OP_IDX) . "'"
		." Limit 1"
		;
	sql_query($sql);
	
	
	# 부서의 본부가 바뀐 경우
	if($PARENT_IDX != mres($OP_PARENT_IDX)) {
	    
	    # 해당 부서의 직원들의 본부도 변경
	    $sql = "UPDATE ORG_STAFF "
	        ."  SET    OP_PARENT_IDX = '" . mres($OP_PARENT_IDX) . "'"
	        ."  WHERE  OS_DDATE IS NULL "
	        ."    AND  OP_PARENT_IDX = '" . mres($PARENT_IDX) . "'"
	        ."    AND  OP_IDX = '" . mres($OP_IDX) . "'"
	        ;
	    sql_query($sql);
	}


	echo_json(array(
		'success'=>(boolean)true, 
		'redir'=>'org_part.list.php?'.$phpTail
	));
?>
