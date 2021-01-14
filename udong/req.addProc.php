<?php
include_once('./_common.php');

/*==========================
$w == a : 답변
$w == r : 추가질문
$w == u : 수정
==========================*/

if($is_guest) {
    alert_script("로그인 후 이용 가능 합니다.", "parent.$('#viewModal').modal('hide');");
}

function validateDate($date, $format = 'Y-m-d H:i')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}


# set : variables
$year = $_POST['year'];
$month = $_POST['month'];
$day = $_POST['day'];
$lp_idx = $_POST['lp_idx'];
$lr_idx = $_POST['lr_idx'];

$sdate = $_POST['sdate'];
$edate = $_POST['edate'];
$lr_tel1 = $_POST['lr_tel1'];
$lr_tel2 = $_POST['lr_tel2'];
$lr_tel3 = $_POST['lr_tel3'];
$lr_email = $_POST['lr_email'];
$lr_p_cnt = $_POST['lr_p_cnt'];
$lr_usage = $_POST['lr_usage'];
$lr_cont = $_POST['lr_cont'];


# re-define
$year = (CHK_NUMBER($year) > 0) ? (int)$year : '';
$month = (CHK_NUMBER($month) > 0) ? (int)$month : '';
$day = (CHK_NUMBER($day) > 0) ? (int)$day : '';
$lp_idx = (CHK_NUMBER($lp_idx) > 0) ? (int)$lp_idx : '';
$lr_idx = (CHK_NUMBER($lr_idx) > 0) ? (int)$lr_idx : '';
$lr_tel = $lr_tel1."-".$lr_tel2."-".$lr_tel3;
if ($lr_tel == '--') $lr_tel = "";
$lr_p_cnt = CHK_NUMBER($lr_p_cnt);
$lr_usage = trim($lr_usage);
$lr_cont = substr(trim($lr_cont),0,65536);
$lr_cont = preg_replace("#[\\\]+$#", "", $lr_cont);


# chk : rfv.
if($lp_idx == '' || $year == '' || $month == '' || $day == '') {
    alert_script('잘못된 접근입니다.', "parent.$('#viewModal').modal('hide');");
}


$sdate = date('Y-m-d H:i', strtotime($year . '-' . $month . '-' . $day . ' ' . $_POST['sdate']));
if(validateDate($sdate) && $_POST['sdate'] != '') {
    $lr_sdate = $sdate;
}
else {
    alert('시작시간 정보를 선택해 주세요.');
}

$edate = date('Y-m-d H:i', strtotime($year . '-' . $month . '-' . $day . ' ' . $_POST['edate']));
if(validateDate($edate) && $_POST['edate'] != '') {
    $lr_edate = $edate;
}
else {
    alert('종료시간 정보를 선택해 주세요.');
}

// date check
$sql = "select count(*)"
    ."  from local_place_req"
    ."  where lr_ddate is null"
    ."      And ("
    ."          (lr_sdate between cast('" . mres($lr_sdate) . "' as datetime) And date_add(cast('" . mres($lr_edate) . "' as datetime), interval -1 hour))"
    ."          Or (lr_edate between date_add(cast('" . mres($lr_sdate) . "' as datetime), interval 1 hour) AND cast('" . mres($lr_edate) . "' as datetime))"
    ."      )"
    ."      And lr_edate <> '" . mres($lr_sdate) . "'"
    ."      And lr_status in('A','B')"
    ."      And lp_idx = '" . mres($lp_idx) ."'"
    ."      And lr_idx <> '" . mres($lr_idx) ."'"
    ;
$date_check_result = sql_fetch($sql);
if($date_check_result['count(*)'] > 0) {
    alert('같은 시간에 이미 등록된 예약이 있습니다.');
}

$lr_sdate = $sdate;
$lr_edate = $edate;


// 090710
if (substr_count($lr_cont, '&#') > 50) {
    alert('내용에 올바르지 않은 코드가 다수 포함되어 있습니다.');
    exit;
}

$upload_max_filesize = ini_get('upload_max_filesize');

if (empty($_POST)) {
    alert("파일 또는 글내용의 크기가 서버에서 설정한 값을 넘어 오류가 발생하였습니다.\\npost_max_size=".ini_get('post_max_size')." , upload_max_filesize=".$upload_max_filesize."\\n게시판관리자 또는 서버관리자에게 문의 바랍니다.");
}


if($w == 'u') {
    $sql = " select * from local_place_req where lr_idx = '" . mres($lr_idx) ."'";
    if(!$is_admin) {
        $sql .= " and mb_id = '" . mres($member['mb_id']) ."'";
    }

    $write = sql_fetch($sql);

    if($w == 'u') {
        if(!$write['lr_idx'])
            alert('게시글이 존재하지 않습니다.\\n삭제되었거나 자신의 글이 아닌 경우입니다.');

        if(!$is_admin) {
            if($write['mb_id'] != $member['mb_id'])
                alert('게시글을 수정할 권한이 없습니다.\\n\\n올바른 방법으로 이용해 주십시오.', G5_URL);

            if($write['lr_status'] == 'B') 
                alert('승인중인 게시물은 수정할 수 없습니다.');
        }
    }
}

if($w == '') {
    $sql = " insert into local_place_req
                set lp_idx           = '" . mres($lp_idx) . "',
                    mb_id           = '" . mres($member['mb_id']) . "',
                    lr_sdate        = '" . mres($lr_sdate) . "',
                    lr_edate        = '" . mres($lr_edate) . "',
                    lr_p_cnt        = '" . mres($lr_p_cnt) . "',
                    lr_tel          = '" . mres($lr_tel) . "',
                    lr_email      	= '" . mres($lr_email) . "',
                    lr_usage         = '" . mres($lr_usage) . "',
                    lr_cont         = '" . mres($lr_cont) . "',
                    lr_wip          = '{$_SERVER['REMOTE_ADDR']}',
                    lr_wdate        = '".G5_TIME_YMDHIS."' ";
    sql_query($sql);

} else if($w == 'u') {
    $sql = " update local_place_req
                set lr_sdate    = '" . mres($lr_sdate) . "',
                    lr_edate    = '" . mres($lr_edate) . "',
                    lr_p_cnt    = '" . mres($lr_p_cnt) . "',
                    lr_tel      = '" . mres($lr_tel) . "',
                    lr_email      = '" . mres($lr_email) . "',
                    lr_usage      = '" . mres($lr_usage) . "',
                    lr_cont     = '" . mres($lr_cont) . "',
                    lr_mip          = '{$_SERVER['REMOTE_ADDR']}',
                    lr_mdate        = '".G5_TIME_YMDHIS."' ";
    $sql .= " where lr_idx = '" . mres($lr_idx) . "'";

    sql_query($sql);
}


#----- email/sms
if ($w == '') {     // 등록일때만
    $timestamp_sdate = strtotime($lr_sdate);
    $timestamp_edate = strtotime($lr_edate);

    $_notice_tit = $member['mb_nick'] . '님의 학습공간 이용 신청이 접수되었습니다.';
    $_notice_tit .= ' 예약시간: ' . date('n/j', $timestamp_sdate) . '(' . F_Week(date('w', $timestamp_sdate)) . ') ';
    $_notice_tit .= date('H:i', $timestamp_sdate) . '~' . date('H:i', $timestamp_edate);

    $_email_date = date('Y-m-d H:i', $timestamp_sdate) . ' ~ ' . date('H:i', $timestamp_edate);


    # get : local_place 
    $sql = "Select lp.lp_name
                    , M.mb_nick, M.mb_email, M.mb_hp
                From local_place As lp
                    Left Join g5_member As M On M.mb_id = lp.mb_id
                Where lp.lp_ddate is null
                    And lp.lp_idx = '" . mres($lp_idx) . "'
                Limit 1"
        ;
    $db_lp = sql_fetch($sql);


    if (!is_null($db_lp['lp_name'])) {
        # mail body
        include 'mail.body.apply.php';

        $mailVal = ['title'=> $_notice_tit, 'cont'=>$_t];
        $smsVal = ['cont'=> $_notice_tit];
        #####


        #----- email 발송
        include_once(G5_LIB_PATH.'/mailer.lib.php');

        $m_cont = str_replace('[#mng_mb_nick#]', $db_lp['mb_nick'], $mailVal['cont']);
        $m_cont = str_replace('[#lp_name#]', $db_lp['lp_name'], $m_cont);
        $m_cont = str_replace('[#mb_nick#]', $member['mb_nick'], $m_cont);
        $m_cont = str_replace('[#lr_tel#]', $lr_tel, $m_cont);
        $m_cont = str_replace('[#lr_email#]', $lr_email, $m_cont);
        $m_cont = str_replace('[#DATE#]', $_email_date, $m_cont);
        $m_cont = str_replace('[#lr_usage#]', $lr_usage, $m_cont);
        $m_cont = str_replace('[#lr_p_cnt#]', $lr_p_cnt, $m_cont);
        $m_cont = str_replace('[#lr_cont#]', $lr_cont, $m_cont);
        $m_cont = str_replace('[#LINK#]', (($_SERVER['HTTPS'] == 'on') ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/udong-mng/', $m_cont);

        mailer($config['cf_admin_email_name'], $config['cf_admin_email'], $db_lp['mb_email'], $mailVal['title'], $m_cont, 1);

        # 테스트용
        // include_once(G5_PLUGIN_PATH.'/nx_mail/nx.mail.directsend.php');
        // SendMailDirectsend($mailVal['title'], $m_cont, $config['cf_admin_email'], $db_lp['mb_email']);
        #####


        #----- SMS 발송  //DR_SMS
        include_once(G5_PLUGIN_PATH.'/nx_sms/class.DR_SMS.php');
        
        DR_SMS::SEND(array(
            'SCHEDULE_TYPE' => '0',
            'SMS_MSG' => $smsVal['cont'],
            'CALLEE_NO' => str_replace('-', '', $db_lp['mb_hp'])
        ));
        #####
    }
}


$result_url = './req.list.php?lp_idx='.$lp_idx.'&year='.$year.'&month='.$month.preg_replace('/^&amp;/', '?', $qstr);

if ($w == 'u') {
    alert_script('수정이 완료되었습니다.', "parent.location.href='{$result_url}';");
}
else {
    alert_script('신청이 완료되었습니다.\r신청현황은 [마이페이지]-[우리동네학습공간]에서 확인하실 수 있습니다.', "parent.location.href='{$result_url}';");
}
?>