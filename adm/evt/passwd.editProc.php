<?php
	include_once('_common.php');


	# set : variables
	$mb_password = trim($_POST['mb_password']);
	$new_password = trim($_POST['new_password']);
	$new_password_re = trim($_POST['new_password_re']);


	# chk : rfv.
	if(isset($_SESSION['ss_mb_id']))
	    $mb_id = trim($_SESSION['ss_mb_id']);
	else
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>"잘못된 접근입니다"
		));

	if(!$mb_id)
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>"정상적으로 로그인되어있지 않습니다."
		));

	$mb = get_member($mb_id);

	if ($mb_password == ''
	 || $new_password == ''
	 || $new_password_re == ''
	) {
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>"필요한 정보가 없습니다."
		));
	}


	# get : 비밀번호 암호화 방식 
	$sql = "Select mb_password_type From g5_member Where mb_id = '" . mres($mb_id) . "' Limit 1";
	$rs1 = sql_fetch($sql);
	$PW_TYPE = $rs1['mb_password_type'];


	if ($PW_TYPE == 'A') {
		# mysql password()
		if (!check_password($mb_password, $mb['mb_password'])) {
			echo_json(array(
				'success'=>(boolean)false, 
				'msg'=>"현재 비밀번호가 틀렸습니다."
			));
		}
	}
	else {
		# SHA-512
		if (F_xenc($mb_password) != $mb['mb_password']) {
			echo_json(array(
				'success'=>(boolean)false, 
				'msg'=>"현재 비밀번호가 틀렸습니다."
			));
		}
	}

	if ($mb_password == $new_password) {
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>"현재 사용중인 비밀번호로 변경할 수 없습니다."
		));
	}

	if ($new_password != $new_password_re) {
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>"새 비밀번호와 새 비밀번호 확인이 다릅니다."
		));
	}


	# update : member info
    $sql = " update {$g5['member_table']}
                set mb_password = '" . mres(F_xenc($new_password)) . "',
                	mb_password_date = now(),
                	mb_password_type = 'B'
              where mb_id = '{$mb_id}' 
              limit 1";
    sql_query($sql);


    echo_json(array(
			'success'=>(boolean)true, 
			'msg'=>"비밀번호가 정상적으로 변경되었습니다. 다시 로그인해 주세요.",
			'redir'=>G5_URL . '/bbs/logout.php?url=/project/login.php'
		));
?>