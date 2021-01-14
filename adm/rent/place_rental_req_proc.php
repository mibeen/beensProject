<?php
	include_once('./_common.php');

    if ($ret = auth_check($auth[$sub_menu], "w,d", true, true)) {
        echo_json(array(
            'success'=>(boolean)false, 
            'msg'=>$ret
        ));
    }


	# set : variables
	$PM_IDX = $_POST['pm_idx'];
	$PS_IDX = $_POST['ps_idx'];
	$PR_IDX = $_POST['pr_idx'];
	$MODE = $_POST['m'];


	# re-define
	$PM_IDX = preg_replace('/[^0-9]/', '', $PM_IDX);
	$PS_IDX = preg_replace('/[^0-9]/', '', $PS_IDX);
	$PR_IDX = preg_replace('/[^0-9]/', '', $PR_IDX);
	$MODE = (in_array($MODE, array('B','C','D'))) ? (string)$MODE : '';


	# chk : rfv.
	if ($PM_IDX == '' || $PS_IDX == '' || $PR_IDX == '' || $MODE == '') {
		echo_json(array(
			'success'=>(boolean)false,
			'msg'=>"필수 항목이 누락되었습니다."
		));
	}


	# get : record
    $db_pr = sql_fetch(
    	"Select PR.*"
    	."		, PS.PS_GUBUN, PS.PS_NAME"
    	."		, PA.PA_NAME"
    	."		, M.mb_nick"
    	."	From PLACE_RENTAL_REQ As PR"
    	."		Inner Join PLACE_RENTAL_SUB As PS On PS.PS_IDX = PR.PS_IDX"
    	."			And PS.PS_DDATE is null"
    	."		Inner Join PLACE_RENTAL_MASTER As PM On PM.PM_IDX = PS.PM_IDX"
    	."			And PM.PM_DDATE is null"
    	."		Inner Join PLACE_RENTAL_AREA As PA On PA.PA_IDX = PM.PA_IDX"
    	."			And PA.PA_DDATE is null"
    	."		Inner Join {$g5['member_table']} As M On M.mb_no = PR.mb_no"
    	."	Where PR.PR_DDATE is null"
    	."		And PR.PS_IDX = '{$PS_IDX}'"
    	."		And PR.PR_IDX = '{$PR_IDX}'"
    	."	Order By PR.PR_IDX Desc"
    	."	Limit 1"
    );
    if (is_null($db_pr['PR_IDX'])) {
    	unset($db_pr);
    	echo_json(array(
    		'success'=>(boolean)false,
    		'msg'=>"존재하지 않는 정보 입니다."
    	));
    }

    
    # 전달된 상태와 기존 상태가 같을 경우
    if ($MODE == $db_pr['PR_STATUS']) {
    	echo_json(array(
    		'success'=>(boolean)false,
    		'msg'=>"잘못된 접근 입니다."
    	));
    }


    # PS_GUBNU
    switch ($db_pr['PS_GUBUN']) {
        case 'A':
            $_ps_gubun = '강의실';
            break;
        case 'B':
            $_ps_gubun = '숙소';
            break;
        default:
            exit;
            break;
    }


    # sms 발송 내용
    $_msg = "";


    # 승인	
    if ($MODE == 'B') {
    	$sql = "Update PLACE_RENTAL_REQ Set PR_STATUS = 'B' Where PR_IDX = '{$PR_IDX}' Limit 1";
    	sql_query($sql);

        $_msg = "{$_ps_gubun} 예약이 승인되었습니다. 감사합니다.";
    }
    # 보류
    else if ($MODE == 'C') {
    	$sql = "Update PLACE_RENTAL_REQ Set PR_STATUS = 'C' Where PR_IDX = '{$PR_IDX}' Limit 1";
    	sql_query($sql);
    }
    # 삭제
    else if ($MODE == 'D') {
    	$sql = "Update PLACE_RENTAL_REQ Set PR_DDATE = now(), PR_DIP = '{$_SERVER['REMOTE_ADDR']}' Where PR_IDX = '{$PR_IDX}' Limit 1";
    	sql_query($sql);

        $_msg = "{$_ps_gubun} 예약이 취소되었습니다. 감사합니다.";

        # 관리자 개인정보 접근이력 기록
        nx_privacy_log('delete', 'PLACE_RENTAL_REQ', $_POST['PR_IDX']);
    }


    # sms 발송
    if ($_msg != "") {
        include_once(G5_PLUGIN_PATH.'/nx_sms/class.DR_SMS.php');
        
        DR_SMS::SEND(array(
            'SCHEDULE_TYPE' => '0',
            'SMS_MSG' => $_msg,
            'CALLEE_NO' => $db_pr['PR_TEL']
        ));
    }


    echo_json(array(
    	'success'=>(boolean)true
    ));
?>
