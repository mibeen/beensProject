<?php

$sub_menu = '100310';
include_once('./_common.php');

#if ($w == "u" || $w == "d")
#    check_demo();

if ($w == 'd')
    auth_check($auth[$sub_menu], "d");
else
    auth_check($auth[$sub_menu], "w");

check_admin_token();

$id     = isset($_POST['id']) ? clean_xss_tags(trim($_POST['id'])) : 0;

$title   = clean_xss_tags(trim($_POST['title']));
$url     = clean_xss_tags(trim($_POST['src']));
$attach  = clean_xss_tags(trim($_POST['attach']));
$width   = clean_xss_tags(trim($_POST['width']));
$height  = clean_xss_tags(trim($_POST['height']));
$padding = clean_xss_tags(trim($_POST['padding']));

$attachment = "A";
switch ($attach) {
    case 'ori':
        $attachment = 'A';
        break;
    case 'full':
        $attachment = 'B';
        break;
    case 'repeat':
        $attachment = 'C';
        break;

    default:
        $attachment = 'A';
        break;
}

if(!isset($title) || strlen($title) < 1){
    alert('값을 입력해 주세요!');
    die();
}

$sql_common = "
    title      = '{$title}',
    url        = '{$url}',
    attachment = '{$attachment}',
    width      = '{$width}',
    height     = '{$height}',
    template   = '{$template}',
    padding   = '{$padding}'
";

if($w == "")
{
    $sql_common .= ",
        created_by = '".$member['mb_no']."',
        deleted_at = '0000-00-00 00:00:00',
        deleted_by = ''
    ";

    $sql = " insert {$g5['new_temp_table']} set $sql_common ";
    sql_query($sql, true);

    $id = sql_insert_id();
}
else if ($w == "u")
{
    $sql = " update {$g5['new_temp_table']} set $sql_common where id = '$id' ";
    sql_query($sql);
}
else if ($w == "d")
{
    $sql = " delete from {$g5['new_temp_table']} where id = '$id' ";
    sql_query($sql);
}

if ($w == "d")
{
    goto_url('./newtemplist.php');
}
else
{
    goto_url("./newtempform.php?w=u&amp;id=$id");
}
?>
