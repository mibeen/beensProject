<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

$formlist = array();
$formlist = get_skin_dir('write', $board_skin_path);

// 등록폼 불러오기
if(isset($write['wr_10']) && $write['wr_10']) { 
	list($tmp_form, $page_file, $page_skin, $page_head, $page_color, $page_type, $page_wide) = explode("|", get_text($write['wr_10']));
	if($tmp_form && in_array($tmp_form, $formlist)) {
		$page_form = $tmp_form;
	}
}

if(!isset($page_form) || !$page_form) {
	include_once($board_skin_path.'/write.form.skin.php');
	return;
}

$skinlist = array();
$headlist = array();

$skinlist = get_skin_dir('page', G5_SKIN_PATH);
$headlist = get_skin_dir('header', G5_SKIN_PATH);

$write_skin_url = $board_skin_url.'/write/'.$page_form;
$write_skin_path = $board_skin_path.'/write/'.$page_form;

?>
<?php if($is_dhtml_editor) { ?>
	<style>
		#wr_content { border:0; display:none; }
	</style>
<?php } ?>
<div id="bo_w" class="write-wrap<?php echo (G5_IS_MOBILE) ? ' font-14' : '';?>">
<?php @include_once($write_skin_path.'/write.skin.php'); ?>
</div>
