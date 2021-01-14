<?php
# set : variables
$ma_target = $_POST['ma_target'];


# re-define : variables
$ma_target = (in_array($ma_target, array('A', 'B'))) ? $ma_target : 'A';


$sub_menu = ($ma_target == 'A') ? "200310" : "990500";
include_once('./_common.php');

if ($w == 'u' || $w == 'd')
    check_demo();

auth_check($auth[$sub_menu], 'w');

check_admin_token();

if ($w == '')
{
    $sql = " insert nx_sms
                set ma_id = '{$_POST['ma_id']}',
                     ma_subject = '{$_POST['ma_subject']}',
                     ma_content = '{$_POST['ma_content']}',
                     ma_target = '" . mres($ma_target) . "',
                     ma_time = '".G5_TIME_YMDHIS."',
                     ma_ip = '{$_SERVER['REMOTE_ADDR']}' ";
    sql_query($sql);
}
else if ($w == 'u')
{
    $sql = " update nx_sms
                set ma_subject = '{$_POST['ma_subject']}',
                     ma_content = '{$_POST['ma_content']}',
                     ma_target = '" . mres($ma_target) . "',
                     ma_time = '".G5_TIME_YMDHIS."',
                     ma_ip = '{$_SERVER['REMOTE_ADDR']}'
                where ma_id = '{$_POST['ma_id']}' ";
    sql_query($sql);
}
else if ($w == 'd')
{
	$sql = " delete from nx_sms where ma_id = '{$_POST['ma_id']}' ";
    sql_query($sql);
}

if ($ma_target == 'A') goto_url('./sms_list.php');
else goto_url('./sms_list2.php');
?>
