<?php
include_once('./_common.php');
include_once G5_LIB_PATH . '/nx.lib.php';
include_once G5_PLUGIN_PATH . '/nx/class.NX_LOGIN_FAIL.php';

$g5['title'] = "로그인 검사";    

$mb_id       = trim($_POST['mb_id']);
$mb_password = trim($_POST['mb_password']);

if (!$mb_id || !$mb_password)
    alert('회원아이디나 비밀번호가 공백이면 안됩니다.');

$mb = get_member($mb_id);


# get : 비밀번호 암호화 방식 
$sql = "Select mb_password_type From g5_member Where mb_id = '" . mres($mb_id) . "' Limit 1";
$rs1 = sql_fetch($sql);
$PW_TYPE = $rs1['mb_password_type'];


// 가입된 회원이 아니다. 비밀번호가 틀리다. 라는 메세지를 따로 보여주지 않는 이유는
// 회원아이디를 입력해 보고 맞으면 또 비밀번호를 입력해보는 경우를 방지하기 위해서입니다.
// 불법사용자의 경우 회원아이디가 틀린지, 비밀번호가 틀린지를 알기까지는 많은 시간이 소요되기 때문입니다.
if (!$mb['mb_id'] || ($PW_TYPE == 'A' && !check_password($mb_password, $mb['mb_password'])) || ($PW_TYPE == 'B' && F_xenc($mb_password) != $mb['mb_password'])) {
    
    // 로그인 5회 실패시 30분간 접속금지 추가
    if (($PW_TYPE == 'A' && !check_password($mb_password, $mb['mb_password'])) || ($PW_TYPE == 'B' && F_xenc($mb_password) != $mb['mb_password'])) {
        $LOGIN_FAIL = LOGIN_FAIL::GET_READ(array('mb_no'=>$mb['mb_no']));
        if($LOGIN_FAIL['success']) {
            if($LOGIN_FAIL['LF_CNT'] >= 4) {
               
                // 슈퍼어드민인 경우 시간 제한 대신 회원 정보에 등록된 휴대폰 번호로 SMS 발송
                if (is_admin($mb['mb_id']) == 'super') {
                    LOGIN_FAIL::SET_UPDATE(array('mb_no'=>$mb['mb_no']));

                    $LF_CNT = $LOGIN_FAIL['LF_CNT'] + 1;

                    $msg = "비밀번호를 5회 이상 틀렸습니다.";

                    // 실패 5회마다 SMS 발송
                    if ($LF_CNT%5 == 0) {
                        include_once(G5_PLUGIN_PATH.'/nx_sms/class.DR_SMS.php');

                        // 휴대폰 번호가 있는 경우만
                        if ($mb['mb_hp'] != null && $mb['mb_hp'] != '') {
                            $to_hp = preg_replace("/[^0-9]/", "", $mb['mb_hp']);

                            // 휴대폰 번호 유효성 체크
                            if (preg_match("/^[0-9]{10,11}$/", $to_hp)) {
                                $sms_content = '경기도 평생교육진흥원 관리자 계정의 로그인 시도가 있었으며, ' . $LF_CNT . '회 연속 실패했습니다. '
                                    . "최종 로그인 시도 ip : " . $_SERVER['REMOTE_ADDR'];

                                DR_SMS::SEND(array('SCHEDULE_TYPE'=>'0', 'SMS_MSG'=>$sms_content, 'CALLEE_NO'=>$to_hp));

                                $msg .= "\\n" . "등록된 휴대폰 번호로 알림 SMS를 발송합니다.";
                            }
                        }
                    }

                    alert($msg);
                }
                else {
                    LOGIN_FAIL::SET_UPDATE(array('mb_no'=>$mb['mb_no']));
                    alert("비밀번호를 5회 이상 틀렸습니다.\\n" . ceil(((strtotime($LOGIN_FAIL['LF_WDATE']) + 1800) - time()) / 60) . "분 뒤에 다시 시도해 주세요.");
                }
            }
        }

        LOGIN_FAIL::SET_ADD(array('mb_no'=>$mb['mb_no']));
    }

    alert('가입된 회원아이디가 아니거나 비밀번호가 틀립니다.\\n비밀번호는 대소문자를 구분합니다.');
}

// 정보 맞을때 5회 이상 로그인 실패 상태인지 확인
$LOGIN_FAIL = LOGIN_FAIL::GET_READ(array('mb_no'=>$mb['mb_no']));
if($LOGIN_FAIL['success']) {
    if($LOGIN_FAIL['LF_CNT'] >= 5) {
        // 슈퍼어드민이 아닌 경우에만 시간 제한.
        if (is_admin($mb['mb_id']) != 'super') {
            alert("비밀번호를 5회 이상 틀렸습니다.\\n" . ceil(((strtotime($LOGIN_FAIL['LF_WDATE']) + 1800) - time()) / 60) . "분 뒤에 다시 시도해 주세요.");
        }
    }
}

// 차단된 아이디인가?
if ($mb['mb_intercept_date'] && $mb['mb_intercept_date'] <= date("Ymd", G5_SERVER_TIME)) {
    $date = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})/", "\\1년 \\2월 \\3일", $mb['mb_intercept_date']);
    alert('회원님의 아이디는 접근이 금지되어 있습니다.\n처리일 : '.$date);
}

// 탈퇴한 아이디인가?
if ($mb['mb_leave_date'] && $mb['mb_leave_date'] <= date("Ymd", G5_SERVER_TIME)) {
    $date = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})/", "\\1년 \\2월 \\3일", $mb['mb_leave_date']);
    alert('탈퇴한 아이디이므로 접근하실 수 없습니다.\n탈퇴일 : '.$date);
}

if ($config['cf_use_email_certify'] && !preg_match("/[1-9]/", $mb['mb_email_certify'])) {
    $ckey = md5($mb['mb_ip'].$mb['mb_datetime']);
    confirm("{$mb['mb_email']} 메일로 메일인증을 받으셔야 로그인 가능합니다. 다른 메일주소로 변경하여 인증하시려면 취소를 클릭하시기 바랍니다.", G5_URL, G5_BBS_URL.'/register_email.php?mb_id='.$mb_id.'&ckey='.$ckey);
}

@include_once($member_skin_path.'/login_check.skin.php');

// 회원아이디 세션 생성
set_session('ss_mb_id', $mb['mb_id']);
// FLASH XSS 공격에 대응하기 위하여 회원의 고유키를 생성해 놓는다. 관리자에서 검사함 - 110106
set_session('ss_mb_key', md5($mb['mb_datetime'] . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']));


// 로그인 실패 기록 삭제
LOGIN_FAIL::SET_DEL(array('mb_no'=>$mb['mb_no']));


// 포인트 체크
if($config['cf_use_point']) {
    $sum_point = get_point_sum($mb['mb_id']);

    $sql= " update {$g5['member_table']} set mb_point = '$sum_point' where mb_id = '{$mb['mb_id']}' ";
    sql_query($sql);
}

// 3.26
// 아이디 쿠키에 한달간 저장
if ($auto_login) {
    // 3.27
    // 자동로그인 ---------------------------
    // 쿠키 한달간 저장
    $key = md5($_SERVER['SERVER_ADDR'] . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'] . $mb['mb_password']);
    set_session('ck_mb_id', $mb['mb_id']);
    set_session('ck_auto', $key);
    // 자동로그인 end ---------------------------
} else {
    set_session('ck_mb_id', '');
    set_session('ck_auto', '');
}


if ($url) {
    // url 체크
    check_url_host($url);

    $link = urldecode($url);
    // 2003-06-14 추가 (다른 변수들을 넘겨주기 위함)
    if (preg_match("/\?/", $link))
        $split= "&amp;";
    else
        $split= "?";

    // $_POST 배열변수에서 아래의 이름을 가지지 않은 것만 넘김
    foreach($_POST as $key=>$value) {
        if ($key != 'mb_id' && $key != 'mb_password' && $key != 'x' && $key != 'y' && $key != 'url') {
            $link .= "$split$key=$value";
            $split = "&amp;";
        }
    }
} else  {
    $link = G5_URL;
}

// 내글반응 체크
if(isset($mb['as_response'])) {
    $row = sql_fetch(" select count(*) as cnt from {$g5['apms_response']} where mb_id = '{$mb['mb_id']}' and confirm <> '1' ", false);
    if($mb['as_response'] != $row['cnt']) {
        sql_query(" update {$g5['member_table']} set as_response = '{$row['cnt']}' where mb_id = '{$mb['mb_id']}' ", false);
    }
}

// 쪽지체크
if(isset($mb['as_memo'])) {
    $row = sql_fetch(" select count(*) as cnt from {$g5['memo_table']} where me_recv_mb_id = '{$mb['mb_id']}' and me_read_datetime = '0000-00-00 00:00:00' ");
    if($mb['as_memo'] != $row['cnt']) {
        sql_query(" update {$g5['member_table']} set as_memo = '{$row['cnt']}' where mb_id = '{$mb['mb_id']}' ", false);
    }
}


# 암호화 방식 A이면 B로 변경
if ($PW_TYPE == 'A') {
    $sql = " update {$g5['member_table']}
                set mb_password = '" . mres(F_xenc($mb_password)) . "',
                    mb_password_type = 'B'
              where mb_id = '{$mb_id}' 
              limit 1";
    sql_query($sql);
}


# 로그인 로그 저장
$sql = "Insert Into LOGIN_LOG("
    . "mb_no"
    . ", LL_WDATE"
    . ", LL_WIP"
    .") Values("
    ."'" . mres($mb['mb_no']) . "'"
    .", now()"
    .", inet_aton('" . mres($_SERVER['REMOTE_ADDR']) . "')"
    .")"
    ;
sql_query($sql);


# 비밀번호 변경 90일 초과 or 초기 비밀번호인 경우 비밀번호 변경 페이지로 이동.
$sql = "Select mb_password_date From {$g5['member_table']} Where mb_id = '{$mb['mb_id']}'";
$row = sql_fetch($sql);
$pw_date = $row['mb_password_date'];

$limit_date = date('Y-m-d', strtotime("-90 days"));

if($pw_date == '0000-00-00' || $pw_date == ''){
    alert(
        '초기 비밀번호를 변경 후 사용해주세요.'
        , G5_ADMIN_URL.'/passwd.php'
    );
} else if($pw_date < $limit_date) {
    alert(
        '비밀번호를 변경한지 90일이 지났습니다. 비밀번호를 변경해주세요.'
        , G5_ADMIN_URL.'/passwd.php'
    );
}


goto_url($link);
?>
