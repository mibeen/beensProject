<?php
if (!defined('_GNUBOARD_')) exit; //개별 페이지 접근 불가

//add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$widget_url.'/widget.css">', 0);

// 링크 열기
$wset['modal'] = (isset($wset['modal'])) ? $wset['modal'] : '';
$is_modal_js = $is_link_target = '';
if($wset['modal'] == "1") { //모달
	$is_modal_js = apms_script('modal');
} else if($wset['modal'] == "2") { //링크#1
	$is_link_target = ' target="_blank"';
}

?>
<div class="basic-post-list">
	<?php 
		if($wset['cache'] > 0) { // 캐시적용시
			echo apms_widget_cache($widget_path.'/widget.rows.php', $wname, $wid, $wset);
		} else {
			include($widget_path.'/widget.rows.php');
		}
	?>
</div>
<?php if($setup_href) { ?>
	<div class="btn-wset text-center p10">
		<a href="<?php echo $setup_href;?>" class="win_memo">
			<span class="text-muted"><i class="fa fa-cog"></i> 위젯설정</span>
		</a>
	</div>
<?php } ?>
