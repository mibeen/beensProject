<?php
$sub_menu = "990200";
include_once('./_common.php');

check_demo();

if (isset($_REQUEST['PM_IDX']) && $_REQUEST['PM_IDX']) {
    $PM_IDX = preg_replace('/[^0-9]/', '', $PM_IDX);
} else {
    alert("잘못된 접근입니다.");
}

if (!count($_POST['chk'])) {
    alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");
}

$c_ip = $_SERVER['REMOTE_ADDR'];

if ($_POST['act_button'] == "선택수정") {

    auth_check($auth[$sub_menu], 'w');

    for ($i=0; $i<count($_POST['chk']); $i++) {

        // 실제 번호를 넘김
        $k = $_POST['chk'][$i];

        $sql = " update {$g5['place_rental_sub_table']}
                    set PS_GUBUN            = '{$_POST['PS_GUBUN'][$k]}',
                        PS_NAME             = '{$_POST['PS_NAME'][$k]}',
                        PS_MDATE            = now(),
                        PS_MIP              = '{$c_ip}'
                  where PS_IDX              = '{$_POST['PS_IDX'][$k]}' ";
        sql_query($sql);
    }

} else if ($_POST['act_button'] == "선택삭제") {

    if ($is_admin != 'super')
        alert('장소 삭제는 최고관리자만 가능합니다.');

    auth_check($auth[$sub_menu], 'd');

    check_admin_token();

    // _PLACE_DELETE_ 상수를 선언해야 board_delete.inc.php 가 정상 작동함
    define('_PLACE_SUB_DELETE_', true);

    for ($i=0; $i<count($_POST['chk']); $i++) {
        // 실제 번호를 넘김
        $k = $_POST['chk'][$i];

        // include 전에 $bo_table 값을 반드시 넘겨야 함
        $tmp_PS_IDX = trim($_POST['PS_IDX'][$k]);
        include ('./place_rental_sub_delete.inc.php');
    }
} 

goto_url('./place_rental_sub_list.php?PM_IDX='.$PM_IDX."&amp;".$qstr);
?>
