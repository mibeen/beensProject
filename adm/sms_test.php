<?php
$sub_menu = "200310";
include_once('./_common.php');
include_once(G5_PLUGIN_PATH.'/nx_sms/class.DR_SMS.php');

if (!$config['cf_email_use'])
    alert('환경설정에서 \'메일발송 사용\'에 체크하셔야 SMS를 발송할 수 있습니다.');

include_once(G5_LIB_PATH.'/mailer.lib.php');

auth_check($auth[$sub_menu], 'w');

check_demo();

$g5['title'] = '회원SMS 테스트';

$name = get_text($member['mb_name']);
$nick = $member['mb_nick'];
$mb_id = $member['mb_id'];
$mb_hp = $member['mb_hp'];

if(preg_match("/[^0-9]/", $mb_hp)) {
	$sql = "select ma_subject, ma_content from nx_sms where ma_id = '{$ma_id}' ";
	$ma = sql_fetch($sql);

	$subject = $ma['ma_subject'];

	$content = $ma['ma_content'];
	$content = preg_replace("/{이름}/", $name, $content);
	$content = preg_replace("/{닉네임}/", $nick, $content);
	$content = preg_replace("/{회원아이디}/", $mb_id, $content);
	$content = preg_replace("/{휴대폰번호}/", $mb_hp, $content);
    
	DR_SMS::SEND(array('SCHEDULE_TYPE'=>'0', 'SMS_MSG'=>$content, 'CALLEE_NO'=>$member['mb_hp']));
}

alert($member['mb_nick'].'('.$member['mb_hp'].')님께 테스트 SMS를 발송하였습니다. 확인하여 주십시오.');
?>
