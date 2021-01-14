<?php
$sub_menu = "100200";
include_once('./_common.php');

if ($is_admin != 'super')
    alert('최고관리자만 접근 가능합니다.');

$mb = get_member($mb_id);
if (!$mb['mb_id'])
    alert('존재하는 회원아이디가 아닙니다.');

check_admin_token();

$sql = " insert into {$g5['auth_table']}
            set mb_id   = '{$_POST['mb_id']}',
                au_menu = '{$_POST['au_menu']}',
                au_auth = '{$_POST['r']},{$_POST['w']},{$_POST['d']}',
                au_mb_id = '" . mres($member['mb_id']) . "',
                au_datetime = now(),
                au_ip = inet_aton('" . mres($_SERVER['REMOTE_ADDR']) . "')"
                ;
$result = sql_query($sql, FALSE);
if (!$result) {
    $sql = " update {$g5['auth_table']}
                set au_auth = '{$_POST['r']},{$_POST['w']},{$_POST['d']}',
                    au_mb_id = '" . mres($member['mb_id']) . "',
                    au_datetime = now(),
                    au_ip = inet_aton('" . mres($_SERVER['REMOTE_ADDR']) . "')
              where mb_id   = '{$_POST['mb_id']}'
                and au_menu = '{$_POST['au_menu']}' ";
    sql_query($sql);
}

//sql_query(" OPTIMIZE TABLE `$g5['auth_table']` ");

goto_url('./auth_list.php?'.$qstr);
?>
