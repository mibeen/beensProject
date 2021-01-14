<?php
# set : variables
$ma_target = $_POST['ma_target'];


# re-define : variables
$ma_target = (in_array($ma_target, array('A', 'B'))) ? $ma_target : 'A';


$sub_menu = ($ma_target == 'A') ? "200310" : "990500";
include_once('./_common.php');

check_demo();

auth_check($auth[$sub_menu], 'd');

check_admin_token();

$count = count($_POST['chk']);

if(!$count)
    alert('삭제할 SMS목록을 1개이상 선택해 주세요.');

for($i=0; $i<$count; $i++) {
    $ma_id = $_POST['chk'][$i];

    $sql = " delete from nx_sms where ma_id = '$ma_id' ";
    sql_query($sql);
}

if ($ma_target == 'A') goto_url('./sms_list.php');
else goto_url('./sms_list2.php');
?>