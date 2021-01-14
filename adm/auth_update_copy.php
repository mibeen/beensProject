<?php
$sub_menu = "100200";
include_once('./_common.php');

if ($is_admin != 'super')
    alert('최고관리자만 접근 가능합니다.');

$mb_copy = $_REQUEST['mb_id_copy'];
$mb_me = $_REQUEST['mb_id_me'];

$mb_c = get_member($mb_copy);
if (!$mb_c['mb_id'])
    alert('존재하는 회원아이디가 아닙니다.');


$mb_m = get_member($mb_me);
if (!$mb_m['mb_id'])
    alert('존재하는 회원아이디가 아닙니다.');

check_admin_token();

$sql = " insert into {$g5['auth_table']}
		select '{$mb_me}' as mb_id, 
				au_menu, 
				au_auth,
				'" . mres($member['mb_id']) . "',
				now(),
				inet_aton('" . mres($_SERVER['REMOTE_ADDR']) . "') 
		from g5_auth where mb_id = '{$mb_copy}' ";
$result = sql_query($sql, FALSE);


//sql_query(" OPTIMIZE TABLE `$g5['auth_table']` ");
alert("복사완료");
goto_url('./auth_list.php?'.$qstr);
?>
