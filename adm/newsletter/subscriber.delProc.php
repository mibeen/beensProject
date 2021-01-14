<?php
	$sub_menu = "990410";
	include_once('./_common.php');

	auth_check($auth[$sub_menu], "r");


	# set : variables
	$NM_IDX = $_POST['NM_IDX'];


	# re-define
	$NM_IDX = explode(",", $NM_IDX);


	foreach($NM_IDX as $key => $val) {
		$sql = "Select Count(*) As cnt"
			."	From NX_NEWSLETTER_MEMBER As NM"
			."	Where NM.NM_DDATE is null And NM.NM_IDX = '" . mres($val) . "'"
			;
		$row = sql_query($sql);
		$rs1 = sql_fetch_array($row);

		if($rs1['cnt'] > 0) {
			# delete : NX_NEWSLETTER_MEMBER
			$sql = "Update NX_NEWSLETTER_MEMBER Set"
				. " NM_DDATE = now()"
				. ", NM_DIP = inet_aton('" . mres($_SERVER['REMOTE_ADDR']) . "')"
				. " Where NM_DDATE is null And NM_IDX = '" . mres($val) . "'";

			sql_query($sql);
		}
	}


	# 관리자 개인정보 접근이력 기록
	nx_privacy_log('delete', 'NX_NEWSLETTER_MEMBER');


	echo_json(array(
		'success'=>(boolean)true
	));
?>
