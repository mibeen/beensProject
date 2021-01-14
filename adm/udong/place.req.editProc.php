<?php
    $sub_menu = "970100";
	include_once('./_common.php');
    include_once('./place.req.err.php');

    if ($ret = auth_check($auth[$sub_menu], "w,d", true, true)) {
        echo_json(array(
            'success'=>(boolean)false, 
            'msg'=>$ret
        ));
    }


	# set : variables
	$lp_idx = $_POST['lp_idx'];
    $lr_idx = $_POST['lr_idx'];
    $lr_manager_notice = $_POST['lr_manager_notice'];
	$lr_cancel_reason = $_POST['lr_cancel_reason'];
	$MODE = $_POST['m'];


	# re-define
	$lp_idx = preg_replace('/[^0-9]/', '', $lp_idx);
	$lr_idx = preg_replace('/[^0-9]/', '', $lr_idx);
    $lr_manager_notice = trim($lr_manager_notice);
    $lr_cancel_reason = (in_array($lr_cancel_reason, array('A', 'B'))) ? $lr_cancel_reason : '' ;
	$MODE = (in_array($MODE, array('B','C','D'))) ? (string)$MODE : '';


	# chk : rfv.
	if ($lp_idx == '' || $lr_idx == '' || $MODE == '' || ($MODE == 'D' && $lr_cancel_reason == '')) {
		echo_json(array(
			'success'=>(boolean)false,
			'msg'=>"필수 항목이 누락되었습니다."
		));
	}


	# get : record
    $db_lr = sql_fetch(
    	"Select lr.*"
    	."		, lp.lp_name"
    	."		, la.la_name"
    	."		, M.mb_nick"
    	."	From local_place_req As lr"
    	."		Inner Join local_place As lp On lp.lp_idx = lr.lp_idx"
    	."			And lp.lp_ddate is null"
    	."		Inner Join local_place_area As la On la.la_idx = lp.la_idx"
    	."			And la.la_ddate is null"
    	."		Inner Join {$g5['member_table']} As M On M.mb_id = lr.mb_id"
    	."	Where lr.lr_ddate is null"
    	."		And lr.lp_idx = '" . mres($lp_idx) . "'"
    	."		And lr.lr_idx = '" . mres($lr_idx) . "'"
    	."	Limit 1"
    );
    if (is_null($db_lr['lr_idx'])) {
    	unset($db_lr);
    	echo_json(array(
    		'success'=>(boolean)false,
    		'msg'=>"존재하지 않는 정보 입니다."
    	));
    }

    
    # 전달된 상태와 기존 상태가 같을 경우
    if ($MODE == $db_lr['lr_status']) {
    	echo_json(array(
    		'success'=>(boolean)false,
    		'msg'=>"잘못된 접근 입니다."
    	));
    }


    # 승인	
    if ($MODE == 'B') {
    	$sql = "Update local_place_req 
                    Set lr_status = 'B' 
                        , lr_manager_notice = '" . mres($lr_manager_notice) . "'
                        , lr_bdate = now()
                        , lr_bip = '{$_SERVER['REMOTE_ADDR']}'
                    Where lr_idx = '" . mres($lr_idx) . "' Limit 1";
    	sql_query($sql);
    }
    # 미승인
    else if ($MODE == 'C') {
    	$sql = "Update local_place_req 
                    Set lr_status = 'C'
                        , lr_cdate = now()
                        , lr_cip = '{$_SERVER['REMOTE_ADDR']}'
                    Where lr_idx = '" . mres($lr_idx) . "' Limit 1";
    	sql_query($sql);
    }
    # 승인취소
    else if ($MODE == 'D') {
        $sql = "Update local_place_req 
                    Set lr_status = 'D' 
                        , lr_cancel_reason = '" . mres($lr_cancel_reason) . "'
                        , lr_cdate = now()
                        , lr_cip = '{$_SERVER['REMOTE_ADDR']}'
                    Where lr_idx = '" . mres($lr_idx) . "' Limit 1";
        sql_query($sql);
    }
    /*
    # 삭제
    else if ($MODE == 'D') {
    	$sql = "Update local_place_req Set lr_ddate = now(), lr_dip = '{$_SERVER['REMOTE_ADDR']}' Where lr_idx = '{$lr_idx}' Limit 1";
    	sql_query($sql);

        $_msg = "공간이용 예약이 취소되었습니다. 감사합니다.";

        # 관리자 개인정보 접근이력 기록
        nx_privacy_log('delete', 'local_place_req', $_POST['lr_idx']);
    }
    */


    #----- email/sms
    $timestamp_sdate = strtotime($db_lr['lr_sdate']);
    $timestamp_edate = strtotime($db_lr['lr_edate']);
    $_str_status = ['B'=>'승인', 'C'=>'미승인', 'D'=>'승인취소'];
    $_email_date = date('Y-m-d H:i', $timestamp_sdate) . ' ~ ' . date('H:i', $timestamp_edate);
    $_cancel_reason = ($lr_cancel_reason == 'A') ? '일정변경 필요' : '시설이용 불가';


    # 메일 제목 / SMS 내용
    $_notice_tit = $db_lr['lp_name'] . ' 학습공간 이용 신청이 ' . $_str_status[$MODE] . '되었습니다.';
    if ($MODE == 'B') {
        $_notice_tit .= ' 예약시간: ' . date('n/j', $timestamp_sdate) . '(' . F_Week(date('w', $timestamp_sdate)) . ') ';
        $_notice_tit .= date('H:i', $timestamp_sdate) . '~' . date('H:i', $timestamp_edate);
    }

    $_msg_cont = $_notice_tit;
    if ($MODE == 'B') {
        $_msg_cont .= ' ' . $lr_manager_notice;
    }
    if ($MODE == 'D') {
        $_msg_cont .= ' 승인취소사유: ' . $_cancel_reason;
    }


    # mail body
    if ($MODE == 'B') {
        include 'mail.body.accept.php';
    }
    else if ($MODE == 'C') {
        include 'mail.body.reject.php';
    }
    else if ($MODE == 'D') {
        include 'mail.body.cancel.php';
    }

    $mailVal = ['title'=> $_notice_tit, 'cont'=>$_t];
    $smsVal = ['cont'=> $_msg_cont];
    #####


    #----- email 발송
    include_once(G5_LIB_PATH.'/mailer.lib.php');

    $m_cont = str_replace('[#mb_nick#]', $db_lr['mb_nick'], $mailVal['cont']);
    $m_cont = str_replace('[#lp_name#]', $db_lr['lp_name'], $m_cont);
    $m_cont = str_replace('[#lr_tel#]', $db_lr['lr_tel'], $m_cont);
    $m_cont = str_replace('[#lr_email#]', $db_lr['lr_email'], $m_cont);
    $m_cont = str_replace('[#DATE#]', $_email_date, $m_cont);
    $m_cont = str_replace('[#lr_usage#]', $db_lr['lr_usage'], $m_cont);
    $m_cont = str_replace('[#lr_p_cnt#]', $db_lr['lr_p_cnt'], $m_cont);
    $m_cont = str_replace('[#lr_cont#]', $db_lr['lr_cont'], $m_cont);

    if ($MODE == 'B') {
        $m_cont = str_replace('[#lr_manager_notice#]', $lr_manager_notice, $m_cont);
    }
    else if ($MODE == 'D') {
        $m_cont = str_replace('[#CANCEL_REASON#]', $_cancel_reason, $m_cont);
    }

    mailer($config['cf_admin_email_name'], $config['cf_admin_email'], $db_lr['lr_email'], $mailVal['title'], $m_cont, 1);

    # 테스트용
    // include_once(G5_PLUGIN_PATH.'/nx_mail/nx.mail.directsend.php');
    // SendMailDirectsend($mailVal['title'], $m_cont, $config['cf_admin_email'], $db_lr['lr_email']);
    #####


    #----- SMS 발송 //DR_SMS
    include_once(G5_PLUGIN_PATH.'/nx_sms/class.DR_SMS.php');
    
    DR_SMS::SEND(array(
        'SCHEDULE_TYPE' => '0',
        'SMS_MSG' => $smsVal['cont'],
        'CALLEE_NO' => str_replace('-', '', $db_lr['lr_tel'])
    ));
    #####


    echo_json(array(
    	'success'=>(boolean)true
    ));
?>
