<?php
	$sub_menu = "990100";
	include_once('./_common.php');

	
	# set : variables
	$m = $_POST['m'];
	$EC_IDX = $_POST['EC_IDX'];
	$EC_NAME = $_POST['EC_NAME'];


	# re-define
	$m = (in_array($m, array('add','edit','del'))) ? (string)$m : '';
	$EC_IDX = CHK_NUMBER($EC_IDX);


	# chk : rfv.
	if ($m == 'add') {
		auth_check($auth[$sub_menu], "w");


		if ($EC_NAME == '') {
			echo_json(array(
				'success'=>(boolean)false, 
				'msg'=>"필수 항목이 누락되었습니다."
			));
		}


		# get : seq
		$row = sql_fetch("Select ifnull(max(EC_SEQ), 0) as max From NX_EVENT_CATE");
		$EC_SEQ = ($row['max'] + 1);
		unset($row);


		# insert
		$sql = "Insert Into NX_EVENT_CATE(mb_id, EC_SEQ, EC_NAME, EC_WDATE, EC_WIP) values("
			."'" . mres($member['mb_id']) . "'"
			.", '" . mres($EC_SEQ) . "'"
			.", '" . mres($EC_NAME) . "'"
			.", now()"
			.", inet_aton('" . mres($_SERVER['REMOTE_ADDR']) . "')"
			.")"
		;
		sql_query($sql);
	}
	else if ($m == 'edit' || $m == 'del')
	{
		if ($EC_IDX <= 0) {
			echo_json(array(
				'success'=>(boolean)false, 
				'msg'=>"필수 항목이 누락되었습니다."
			));
		}


		# record chk
		$row = sql_fetch("Select EC_SEQ From NX_EVENT_CATE Where EC_IDX = '" . mres($EC_IDX) . "'");
		if (is_null($row['EC_SEQ'])) {
			echo_json(array(
				'success'=>(boolean)false, 
				'msg'=>"일치하는 정보가 존재하지 않습니다."
			));
		}
		$db_ec = $row;
		unset($row);



		if ($m == 'edit') {
			auth_check($auth[$sub_menu], "w");


			if ($EC_NAME == '') {
				echo_json(array(
					'success'=>(boolean)false, 
					'msg'=>"필수 항목이 누락되었습니다."
				));
			}


			# update
			$sql = "Update NX_EVENT_CATE Set"
				." EC_NAME = '" . mres($EC_NAME) . "'"
				.", EC_MDATE = now()"
				.", EC_MIP = inet_aton('" . mres($_SERVER['REMOTE_ADDR']) . "')"
				." Where EC_IDX = '" . mres($EC_IDX) . "'"
				." Limit 1"
			;
			sql_query($sql);
		}
		else if ($m == 'del') {
			auth_check($auth[$sub_menu], "d");


			# seq 당기기
			$sql = "Update NX_EVENT_CATE Set EC_SEQ = EC_SEQ - 1 Where EC_IDX != '" . mres($EC_IDX) . "' And EC_SEQ > '" . mres($db_ec['EC_SEQ']) . "'";
			sql_query($sql);


			# delete
			$sql = "Delete From NX_EVENT_CATE Where EC_IDX = '" . mres($EC_IDX) . "' Limit 1";
			sql_query($sql);
		}
	}
	else {
		exit();
	}


	echo_json(array(
		'success'=>(boolean)true,
		'redir'=>'evt.cate.php'
	));
?>
