<?php
if (!defined('_GNUBOARD_')) exit; //개별 페이지 접근 불가

// Owl Carousel
//apms_script('owlcarousel');

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

$is_autoplay = (isset($wset['auto']) && ($wset['auto'] > 0 || $wset['auto'] == "0")) ? $wset['auto'] : 3000;
$is_speed = (isset($wset['speed']) && $wset['speed'] > 0) ? $wset['speed'] : 0;
if(G5_IS_MOBILE) {
	$is_lazy = false;
} else {
	$is_lazy = (isset($wset['lazy']) && $wset['lazy']) ? true : false;
}

$wset['thumb_w'] = (isset($wset['thumb_w']) && $wset['thumb_w'] > 0) ? $wset['thumb_w'] : 400;
$wset['thumb_h'] = (isset($wset['thumb_h']) && $wset['thumb_h'] > 0) ? $wset['thumb_h'] : 225;
$img_h = apms_img_height($wset['thumb_w'], $wset['thumb_h'], '56.25');

$wset['line'] = (isset($wset['line']) && $wset['line'] > 0) ? $wset['line'] : 1;
$line_height = 20 * $wset['line'];
if($wset['line'] > 1) $line_height = $line_height + 4;

// 간격
$gap = (isset($wset['gap']) && ($wset['gap'] > 0 || $wset['gap'] == "0")) ? $wset['gap'] : 15;

// 가로수
$item = (isset($wset['item']) && $wset['item'] > 0) ? $wset['item'] : 5;

// 기본수
$lg = $md = $sm = $xs = $item;

// 랜덤아이디
$widget_id = apms_id(); 

# 펼침시 가로사이즈
$autowidth_size = ($wset['autowidth_size'] > 0) ? $wset['autowidth_size'] : $wset['thumb_w'];
?>
<div id="<?php echo $widget_id;?>" class="nx-slider<?php if(isset($wset['nav']) && $wset['nav']) {echo(' nav-'.$wset['nav_size']);}?><?php if($wset['hover'] != '') {echo(' hover-'.$wset['hover']);}?>">
	<?php 
	if ($wset['dot']) {
		?>
		<div id="<?php echo $widget_id;?>-dots" class="nx-slider-dots-container"></div>
		<?php
	}
	?>
	<?php
		if($wset['cache'] > 0) { // 캐시적용시
			echo apms_widget_cache($widget_path.'/widget.rows.php', $wname, $wid, $wset);
		} else {
			include($widget_path.'/widget.rows.php');
		}
	?>
</div>
<?php if($setup_href) { ?>
	<div class="btn-wset text-center p10" style="padding-top: 30px;">
		<a href="<?php echo $setup_href;?>" class="win_memo">
			<span class="text-muted font-12"><i class="fa fa-cog"></i> 위젯설정</span>
		</a>
	</div>
<?php } ?>