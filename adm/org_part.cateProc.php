<?php
	$sub_menu = "990300";
	include_once('_common.php');

	
	# set : variables
	$m = $_POST['m'];
	$OP_IDX = $_POST['OP_IDX'];
	$OP_SEQ = $_POST['OP_SEQ'];
	$OP_NAME = $_POST['OP_NAME'];


	# re-define
	$m = (in_array($m, array('add','edit','del'))) ? (string)$m : '';
	$OP_IDX = CHK_NUMBER($OP_IDX);
	$OP_SEQ = CHK_NUMBER($OP_SEQ);


	# chk : rfv.
	if ($m == 'add') {
		auth_check($auth[$sub_menu], "w");


		if ($OP_NAME == '') {
			echo_json(array(
				'success'=>(boolean)false, 
				'msg'=>"필수 항목이 누락되었습니다."
			));
		}


		# record chk
		$row = sql_fetch("Select Count(OP_IDX) As cnt From ORG_PART Where OP_DDATE is null And OP_PARENT_IDX is null And OP_PARENT2_IDX is not null");
		$cnt = $row['cnt'];
		unset($row);


		# insert
		$sql = "Insert Into ORG_PART("
			."OP_GUBUN"
			.", OP_PARENT2_IDX"
			.", OP_SEQ"
			.", OP_NAME"
			.", OP_WDATE"
			.", OP_WIP"
			.") values("
			."'D'"
			.", 12"
			.", " . mres($cnt + 1)
			.", '" . mres($OP_NAME) . "'"
			.", now()"
			.", inet_aton('" . mres($_SERVER['REMOTE_ADDR']) . "')"
			.")"
			;
		sql_query($sql);
	}
	else if ($m == 'edit' || $m == 'del')
	{
		if ($OP_IDX <= 0) {
			echo_json(array(
				'success'=>(boolean)false, 
				'msg'=>"필수 항목이 누락되었습니다."
			));
		}


		# record chk
		$row = sql_fetch("Select Count(OP_IDX) As cnt From ORG_PART Where OP_IDX = '" . mres($OP_IDX) . "'");
		if ($row['cnt'] <= 0) {
			echo_json(array(
				'success'=>(boolean)false, 
				'msg'=>"일치하는 정보가 존재하지 않습니다."
			));
		}
		unset($row);


		# record chk
		$row = sql_fetch("Select OP_SEQ From ORG_PART Where OP_IDX = '" . mres($OP_IDX) . "'");
		$SEQ = $row['OP_SEQ'];
		unset($row);


		$basic_wh = "Where OP_DDATE is null And OP_PARENT_IDX is null And OP_PARENT2_IDX is not null";


		if ($m == 'edit') {
			auth_check($auth[$sub_menu], "w");


			if ($OP_SEQ == '' || $OP_NAME == '') {
				echo_json(array(
					'success'=>(boolean)false, 
					'msg'=>"필수 항목이 누락되었습니다."
				));
			}


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


			# update
			$sql = "Update ORG_PART Set"
				." OP_SEQ = '" . mres($OP_SEQ) . "'"
				.", OP_NAME = '" . mres($OP_NAME) . "'"
				.", OP_MDATE = now()"
				.", OP_MIP = inet_aton('" . mres($_SERVER['REMOTE_ADDR']) . "')"
				." Where OP_IDX = '" . mres($OP_IDX) . "'"
				." Limit 1"
				;
			sql_query($sql);
		}
		else if ($m == 'del') {
			auth_check($auth[$sub_menu], "d");


			# update : 전달된 SEQ 이상인 정보 -1
			$sql = "Update ORG_PART Set"
				." 		OP_SEQ = OP_SEQ - 1"
				." 	{$basic_wh}"
				."		And OP_SEQ > '" . mres($SEQ) . "'"
				."		And OP_IDX != '" . mres($OP_IDX) . "'"
				." 	Order By OP_SEQ Asc"
				;
			sql_query($sql);


			# delete
			$sql = "Update ORG_PART Set OP_DDATE = now(), OP_DIP = inet_aton('" . mres($_SERVER['REMOTE_ADDR']) . "') Where OP_IDX = '" . mres($OP_IDX) . "' Limit 1";
			sql_query($sql);
		}
	}
	else {
		exit();
	}


	echo_json(array(
		'success'=>(boolean)true,
		'redir'=>'org_part.cate.php'
	));
?>
