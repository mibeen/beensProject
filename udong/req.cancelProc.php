<?php
include_once('./_common.php');


if($is_guest) {
    alert_script("로그인 후 이용 가능 합니다.", "parent.$('#viewModal').modal('hide');");
}


# set : variables
$lp_idx = $_POST['lp_idx'];
$lr_idx = $_POST['lr_idx'];


# re-define
$lp_idx = (CHK_NUMBER($lp_idx) > 0) ? (int)$lp_idx : '';
$lr_idx = (CHK_NUMBER($lr_idx) > 0) ? (int)$lr_idx : '';


# chk : rfv.
if($lp_idx == '') {
    alert_script('잘못된 접근입니다.', "parent.$('#viewModal').modal('hide');");
}


$sql = "Select lr.*
				, lp.lp_name
				, M.mb_nick 
                , MM.mb_nick As mng_mb_nick, MM.mb_email, MM.mb_hp
			From local_place_req As lr
				Inner Join g5_member As M On M.mb_id = lr.mb_id
				Inner Join local_place As lp On lp.lp_idx = lr.lp_Idx
				Left Join g5_member As MM On MM.mb_id = lp.mb_id
			Where lr.lr_idx = '" . mres($lr_idx) ."'
				And lr.mb_id = '" . mres($member['mb_id']) . "'"
	;
$LR = sql_fetch($sql);

if(!$LR['lr_idx'])
    alert('예약이 존재하지 않습니다.\\n삭제되었거나 자신의 예약이 아닌 경우입니다.');

if($LR['mb_id'] != $member['mb_id'])
    alert('예약을 취소할 권한이 없습니다.\\n\\n올바른 방법으로 이용해 주십시오.', G5_URL);


$sql = "Update local_place_req
            Set lr_dip          = '{$_SERVER['REMOTE_ADDR']}',
                lr_ddate        = '".G5_TIME_YMDHIS."' ";
$sql .= " Where lr_idx = '" . mres($lr_idx) . "'";
sql_query($sql);


#----- email/sms
$timestamp_sdate = strtotime($LR['lr_sdate']);
$timestamp_edate = strtotime($LR['lr_edate']);

$_notice_tit = ' ' . $LR['mb_nick'] . '님이 ';
$_notice_tit .= (($LR['lr_status'] == 'B') ? '승인된' : '승인 대기중인') . ' 학습공간 이용 예약을 취소했습니다.';
$_notice_tit .= ' 예약시간: ' . date('n/j', $timestamp_sdate) . '(' . F_Week(date('w', $timestamp_sdate)) . ') ';
$_notice_tit .= date('H:i', $timestamp_sdate) . '~' . date('H:i', $timestamp_edate);

$_email_date = date('Y-m-d H:i', $timestamp_sdate) . ' ~ ' . date('H:i', $timestamp_edate);
$_status = ($LR['lr_status'] == 'B') ? '승인' : '신청 (승인 대기)' ;


# mail body
include 'mail.body.cancel.php';

$mailVal = ['title'=> $_notice_tit, 'cont'=>$_t];
$smsVal = ['cont'=> $_notice_tit];
#####


#----- email 발송
include_once(G5_LIB_PATH.'/mailer.lib.php');

$m_cont = str_replace('[#mng_mb_nick#]', $LR['mng_mb_nick'], $mailVal['cont']);
$m_cont = str_replace('[#lp_name#]', $LR['lp_name'], $m_cont);
$m_cont = str_replace('[#mb_nick#]', $LR['mb_nick'], $m_cont);
$m_cont = str_replace('[#lr_tel#]', $LR['lr_tel'], $m_cont);
$m_cont = str_replace('[#lr_email#]', $LR['lr_email'], $m_cont);
$m_cont = str_replace('[#DATE#]', $_email_date, $m_cont);
$m_cont = str_replace('[#STATUS#]', $_status, $m_cont);
$m_cont = str_replace('[#lr_usage#]', $LR['lr_usage'], $m_cont);
$m_cont = str_replace('[#lr_p_cnt#]', $LR['lr_p_cnt'], $m_cont);
$m_cont = str_replace('[#lr_cont#]', $LR['lr_cont'], $m_cont);
$m_cont = str_replace('[#LINK#]', (($_SERVER['HTTPS'] == 'on') ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/udong-mng/', $m_cont);

mailer($config['cf_admin_email_name'], $config['cf_admin_email'], $LR['mb_email'], $mailVal['title'], $m_cont, 1);

# 테스트용
// include_once(G5_PLUGIN_PATH.'/nx_mail/nx.mail.directsend.php');
// SendMailDirectsend($mailVal['title'], $m_cont, $config['cf_admin_email'], $LR['mb_email']);
#####


#----- SMS 발송  //DR_SMS
include_once(G5_PLUGIN_PATH.'/nx_sms/class.DR_SMS.php');

DR_SMS::SEND(array(
    'SCHEDULE_TYPE' => '0',
    'SMS_MSG' => $smsVal['cont'],
    'CALLEE_NO' => str_replace('-', '', $LR['mb_hp'])
));
#####


echo_json(['success'=>(Boolean)true, 'msg'=>'예약이 정상적으로 취소되었습니다.']);
?>