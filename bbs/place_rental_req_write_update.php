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

$msg = array();

if (isset($_POST['year']) && $_POST['year']) {
    $qstr_year = preg_replace('/[^0-9]/', '', $year);
} else {
    $qstr_year = date('Y');
}

if (isset($_POST['month']) && $_POST['month']) {
    $qstr_month = preg_replace('/[^0-9]/', '', $month);
} else {
    $qstr_month = date('m');
}


// pm_id check
if (isset($_POST['pm_id']) && $_POST['pm_id']) {
	$pm_id = preg_replace('/[^0-9]/', '', $pm_id);
} else {
    $pm_id = '';
}

// ps_id check
if (isset($_POST['ps_id']) && $_POST['ps_id']) {
	$ps_id = preg_replace('/[^0-9]/', '', $ps_id);
} else {
    $ps_id = '';
}

// ps_id check
if (isset($_POST['pr_id']) && $_POST['pr_id']) {
    $pr_id = preg_replace('/[^0-9]/', '', $pr_id);
} else {
    $pr_id = '';
}

// gubun check
if (isset($_POST['gubun']) && $_POST['gubun']) {
    if($gubun == 'A' || $gubun == 'B') {}
    else {
        $gubun = '';
    }
} else {
    $gubun = '';
}

if(!$pm_id || !$ps_id || !$gubun) {
    alert_script('잘못된 접근입니다.', "parent.$('#viewModal').modal('hide');");
}

if($gubun == 'A') {
    $sdate = date('Y-m-d H:i', strtotime($_POST['year'] . '-' . $_POST['month'] . '-' . $_POST['day'] . ' ' . $_POST['sdate']));
    if(validateDate($sdate) && $_POST['sdate'] != '') {
        $PR_SDATE = $sdate;
    }
    else {
        alert('시작시간 정보를 선택해 주세요.');
    }

    $edate = date('Y-m-d H:i', strtotime($_POST['year'] . '-' . $_POST['month'] . '-' . $_POST['day'] . ' ' . $_POST['edate']));
    if(validateDate($edate) && $_POST['edate'] != '') {
        $PR_EDATE = $edate;
    }
    else {
        alert('종료시간 정보를 선택해 주세요.');
    }

    // date check
    $sql = "select count(*)"
        ."  from {$g5['place_rental_req_table']}"
        ."  where PR_DDATE is null"
        ."      And ("
        ."          (PR_SDATE between cast('{$PR_SDATE}' as datetime) And date_add(cast('{$PR_EDATE}' as datetime), interval -1 hour))"
        ."          Or (PR_EDATE between date_add(cast('{$PR_SDATE}' as datetime), interval 1 hour) AND cast('{$PR_EDATE}' as datetime))"
        ."      )"
        ."      And PR_EDATE <> '{$PR_SDATE}'"
        ."      And PR_STATUS in('A','B')"
        ."      And PR_IDX <> '$pr_id'"
        ."      And PS_IDX = '$ps_id'"
        ;
    $date_check_result = sql_fetch($sql);
    if($date_check_result['count(*)'] > 0) {
        alert('같은 시간에 이미 등록된 예약이 있습니다.');
    }
}
else if($gubun == 'B') {
    // get last day
    $today = date('Y-m-d', strtotime($year . '-' . $month . '-1'));
    $last_day = date("t", strtotime($today));

    $sdate = date('Y-m-d H:i', strtotime($_POST['year'] . '-' . $_POST['month'] . '-' . $_POST['day'] . ' 00:00'));
    if(validateDate($sdate)) {
        $PR_SDATE = $sdate;
    }

    $t_year = intval($_POST['year']);
    $t_month = intval($_POST['month']);
    $t_day = intval($_POST['day']) + intval($_POST['edate'] - 1);
    if($t_day > $last_day) {
        $t_day = $t_day - $last_day;
        $t_month++;
    }
    if($t_month > 12) {
        $t_month = $t_month - 12;
        $t_year++;
    }

    $edate = date('Y-m-d H:i', strtotime($t_year . '-' . $t_month . '-' . $t_day . ' 00:00'));
    if(validateDate($edate) && $_POST['edate'] != '') {
        $PR_EDATE = $edate;
    }
    else {
        $msg[] = '<strong>기간</strong>을 선택하세요.';
    }

    // date check
    $sql = "Select count(*)"
        ."    From {$g5['place_rental_req_table']}"
        ."    Where PR_DDATE is null"
        ."        And ("
        ."          (cast(PR_SDATE as date) between cast('{$PR_SDATE}' as date) AND cast('{$PR_EDATE}' as date))"
        ."          Or (cast(PR_EDATE as date) between cast('{$PR_SDATE}' as date) AND cast('{$PR_EDATE}' as date))"
        ."        )"
        ."        And cast(PR_EDATE as date) <> cast('{$PR_SDATE}' as date)"
        ."        And PR_STATUS in('A','B')"
        ."        And PR_IDX <> '$pr_id'"
        ."        And PS_IDX = '$ps_id'"
        ;
    $date_check_result = sql_fetch($sql);
    
    if($date_check_result['count(*)'] > 0) {
        alert('같은 기간에 이미 등록된 예약이 있습니다.');
    }

    // real date
    $r_t_year = intval($_POST['year']);
    $r_t_month = intval($_POST['month']);
    $r_t_day = intval($_POST['day']) + intval($_POST['edate']);
    if($r_t_day > $last_day) {
        $r_t_day = $r_t_day - $last_day;
        $r_t_month++;
    }
    if($r_t_month > 12) {
        $r_t_month = $r_t_month - 12;
        $r_t_year++;
    }

    $edate = date('Y-m-d H:i', strtotime($r_t_year . '-' . $r_t_month . '-' . $r_t_day . ' 00:00'));
}

$PR_SDATE = $sdate;
$PR_EDATE = $edate;
$PR_P_CNT = $_POST['p_cnt'];

$PR_TEL = '';
if (isset($_POST['tel1']) && isset($_POST['tel2']) && isset($_POST['tel3']) && $_POST['tel1'] != '' && $_POST['tel2'] != '' && $_POST['tel3'] != '') {
	$PR_TEL = $_POST['tel1']."-".$_POST['tel2']."-".$_POST['tel3'];
}
if ($PR_TEL == '') {
    alert('연락처 정보를 입력해 주세요.');
}

$PR_CONT = '';
if (isset($_POST['cont'])) {
    $PR_CONT = substr(trim($_POST['cont']),0,65536);
    $PR_CONT = preg_replace("#[\\\]+$#", "", $PR_CONT);
}

if (!empty($msg)) {
    $msg = implode('<br>', $msg);
    alert($msg);
}

// 090710
if (substr_count($PR_CONT, '&#') > 50) {
    alert('내용에 올바르지 않은 코드가 다수 포함되어 있습니다.');
    exit;
}

$upload_max_filesize = ini_get('upload_max_filesize');

if (empty($_POST)) {
    alert("파일 또는 글내용의 크기가 서버에서 설정한 값을 넘어 오류가 발생하였습니다.\\npost_max_size=".ini_get('post_max_size')." , upload_max_filesize=".$upload_max_filesize."\\n게시판관리자 또는 서버관리자에게 문의 바랍니다.");
}


if($w == 'u') {
    $sql = " select * from {$g5['place_rental_req_table']} where PR_IDX = '$pr_id' ";
    if(!$is_admin) {
        $sql .= " and mb_no = '{$member['mb_no']}' ";
    }

    $write = sql_fetch($sql);

    if($w == 'u') {
        if(!$write['PR_IDX'])
            alert('게시글이 존재하지 않습니다.\\n삭제되었거나 자신의 글이 아닌 경우입니다.');

        if(!$is_admin) {
            if($write['mb_no'] != $member['mb_no'])
                alert('게시글을 수정할 권한이 없습니다.\\n\\n올바른 방법으로 이용해 주십시오.', G5_URL);

            if($write['PR_STATUS'] == 'B') 
                alert('승인중인 게시물은 수정할 수 없습니다.');
        }
    }
}

if($w == '') {
    $sql = " insert into {$g5['place_rental_req_table']}
                set PS_IDX          = '$ps_id',
                    mb_no           = '{$member['mb_no']}',
                    PR_SDATE        = '$PR_SDATE',
                    PR_EDATE        = '$PR_EDATE',
                    PR_P_CNT        = '$PR_P_CNT',
                    PR_TEL      	= '$PR_TEL',
                    PR_CONT         = '$PR_CONT',
                    PR_WIP          = '{$_SERVER['REMOTE_ADDR']}',
                    PR_WDATE        = '".G5_TIME_YMDHIS."' ";
    sql_query($sql);

} else if($w == 'u') {
    $sql = " update {$g5['place_rental_req_table']}
                set PS_IDX		= '$ps_id',
                	PR_SDATE    = '$PR_SDATE',
                    PR_EDATE    = '$PR_EDATE',
                    PR_P_CNT    = '$PR_P_CNT',
                    PR_TEL      = '$PR_TEL',
                    PR_CONT     = '$PR_CONT' ";
    $sql .= " where PR_IDX = '$pr_id' ";
    sql_query($sql);
}

$result_url = G5_BBS_URL.'/place_rental_req_list.php?pm_id='.$pm_id.'&ps_id='.$ps_id.'&year='.$qstr_year.'&month='.$qstr_month.preg_replace('/^&amp;/', '?', $qstr);

alert_script('신청이 완료되었습니다.', "parent.location.href='{$result_url}';");
?>