<?php
$sub_menu = '100311';
include_once('./_common.php');

auth_check($auth[$sub_menu], "r");

$g5['title'] = "메인슬라이드 관리 ";
include_once('./admin.head.php');
?>
<iframe src="<?php echo G5_URL."/main_slide.php"; ?>" frameborder="0" scrolling="no" style="width:100%; height:700px;"></iframe>


<?php
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
