<?php
$sub_menu = "990200";
include_once('./_common.php');

check_demo();

if (isset($_REQUEST['year']) && $_REQUEST['year']) {
    $year = preg_replace('/[^0-9]/', '', $year);
} else {
    $year = date('Y');
}

if (isset($_REQUEST['month']) && $_REQUEST['month']) {
    $month = preg_replace('/[^0-9]/', '', $month);
} else {
    $month = date('m');
}

if (isset($_REQUEST['PM_IDX']) && $_REQUEST['PM_IDX']) {
    $PM_IDX = preg_replace('/[^0-9]/', '', $PM_IDX);
} else {
    alert("잘못된 접근입니다.");
}

if (isset($_REQUEST['PS_IDX']) && $_REQUEST['PS_IDX']) {
    $PS_IDX = preg_replace('/[^0-9]/', '', $PS_IDX);
} else {
    alert("잘못된 접근입니다.");
}

if (isset($_REQUEST['PR_IDX']) && $_REQUEST['PR_IDX']) {
    $PR_IDX = preg_replace('/[^0-9]/', '', $PR_IDX);
} else {
    alert("잘못된 접근입니다.");
}

$c_ip = $_SERVER['REMOTE_ADDR'];

if ($_POST['act_button'] == "승인하기") {
    if ($is_admin != 'super')
        alert('승인하기는 최고관리자만 가능합니다.');

    auth_check($auth[$sub_menu], 'w');

    $sql = " update {$g5['place_rental_req_table']}
                set PR_STATUS           = 'B'
              where PR_IDX              = '{$_POST['PR_IDX']}' ";
    sql_query($sql);

} else if ($_POST['act_button'] == "보류하기") {
    if ($is_admin != 'super')
        alert('삭제하기는 최고관리자만 가능합니다.');

    auth_check($auth[$sub_menu], 'd');

    check_admin_token();

    $sql = " update {$g5['place_rental_req_table']}
                set PR_STATUS           = 'C'
              where PR_IDX              = '{$_POST['PR_IDX']}' ";
    sql_query($sql);
} else if ($_POST['act_button'] == "삭제하기") {
    if ($is_admin != 'super')
        alert('삭제하기는 최고관리자만 가능합니다.');

    auth_check($auth[$sub_menu], 'd');

    check_admin_token();

    $sql = " update {$g5['place_rental_req_table']}
                set PR_STATUS           = 'D'
              where PR_IDX              = '{$_POST['PR_IDX']}' ";
    sql_query($sql);


    # 관리자 개인정보 접근이력 기록
    nx_privacy_log('delete', 'PLACE_RENTAL_REQ', $_POST['PR_IDX']);
} 

goto_url('./place_rental_req_list.php?PM_IDX='.$PM_IDX.'&PS_IDX='.$PS_IDX."&amp;year=".$year."&amp;month=".$month."&amp;".$qstr);
?>
