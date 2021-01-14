<?php
if (!defined('_GNUBOARD_')) exit; //개별 페이지 접근 불가

// bxslider
apms_script('bxslider');

// Lightbox
if($wset['lightbox']) {
	apms_script('lightbox');
}

//add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$widget_url.'/widget.css">', 0);

// 스킨 불러오기
if(!$wset['skin']) $wset['skin'] = 'basic';

// 스킨 설정값
$pager_custom = '';
if(is_file($widget_path.'/skin/config/'.$wset['skin'].'.config.php')) {
	include ($widget_path.'/skin/config/'.$wset['skin'].'.config.php');
}

// 모달창
$wset['modal_js'] = ($wset['modal'] == "1") ? apms_script('modal') : '';

// 높이
if($wset['round'] == "1") {
	$wset['heights'] = '100%';
}

if($wset['heights']) {
	list($wset['height'], $wset['hl'], $wset['hm'], $wset['hs'], $wset['hx']) = explode(",", $wset['heights']);
}

if(!$wset['height']) $wset['height'] = '75%';

// 세로수
$is_sero = (int)$wset['sero'];
if(!$is_sero) $is_sero = 1;

// 마진(간격)
$margin = ($wset['margin'] == "") ? 10 : (int)$wset['margin'];

// 댓글
$is_comment = ($wset['comment']) ? true : false;

// 라인
$wset['line'] = (!$wset['line']) ? 1 : (int)$wset['line'];
$wset['lineh'] = (!$wset['lineh']) ? 20 : (int)$wset['lineh'];

// CSS
$css = '';
if($wset['nav'] == "1") {
	$css .= ' nav-top';
} else if($wset['nav'] == "3") {
	$css .= ' nav-bottom';
} else {
	$css .= ' nav-middle';
}
$css .= ($wset['dot'] && $wset['in']) ? ' in-dots' : '';
$css .= ($is_comment) ? ' is-comment' : '';
if($wset['round']) {
	$css .= ($wset['round'] == "1") ? ' is-circle' : ' is-round';
} else {
	$css .= ' is-box';
}

// 아이디
$widget_id = apms_id(); 
$wset['wid'] = $wid;

?>
<style>
	#<?php echo $widget_id;?> .img-box { padding-bottom:<?php echo $wset['height'];?>; }
	<?php if($is_sero > 1) { 
		$gap = ($wset['gap'] == "") ? $margin : $wset['gap'];
	?>
	#<?php echo $widget_id;?> .post { margin-bottom:-<?php echo $gap;?>px; }
	#<?php echo $widget_id;?> .item-box { margin-bottom:<?php echo $gap;?>px; }
	<?php } ?>
	<?php if($wset['round'] && $wset['round'] != "1") { ?>
	#<?php echo $widget_id;?> .img-box,
	#<?php echo $widget_id;?> .img-item { border-radius:<?php echo $wset['round'];?>px; -webkit-border-radius: <?php echo $wset['round'];?>px; -moz-border-radius: <?php echo $wset['round'];?>px; }
	<?php } ?>
	<?php if($wset['dot']) { ?>
	#<?php echo $widget_id;?> .bx-wrapper .bx-pager.bx-default-pager a.active { background:<?php echo apms_color($wset['dot']);?>; }
	<?php } ?>
	<?php if($wset['line'] > 1) { ?>
	#<?php echo $widget_id;?> .ellipsis-line { -webkit-line-clamp: <?php echo $wset['line'];?>; line-height: <?php echo $wset['lineh'];?>px; <?php echo ($wset['linef']) ? 'max-' : '';?>height: <?php echo ($wset['lineh'] * $wset['line']);?>px; }
	<?php } ?>
	<?php if(_RESPONSIVE_) { //반응형일 때만 작동 ?>
		<?php if($wset['hl']) { ?>
		@media (max-width:1199px) { 
			.responsive #<?php echo $widget_id;?> .img-box { padding-bottom:<?php echo $wset['hl'];?>; }
		}
		<?php } ?>
		<?php if($wset['hm']) { ?>
		@media (max-width:991px) { 
			.responsive #<?php echo $widget_id;?> .img-box { padding-bottom:<?php echo $wset['hm'];?>; }
		}
		<?php } ?>
		<?php if($wset['hs']) { ?>
		@media (max-width:767px) { 
			.responsive #<?php echo $widget_id;?> .img-box { padding-bottom:<?php echo $wset['hs'];?>; }
		}
		<?php } ?>
		<?php if($wset['hx']) { ?>
		@media (max-width:480px) { 
			.responsive #<?php echo $widget_id;?> .img-box { padding-bottom:<?php echo $wset['hx'];?>; }
		}
		<?php } ?>
	<?php } ?>
</style>
<div id="<?php echo $widget_id;?>" class="miso-post-bxslider <?php echo $css;?>">
<?php 
	if($wset['cache'] > 0) { // 캐시적용시
		echo apms_widget_cache($widget_path.'/widget.rows.php', $wname, $wid, $wset);
	} else {
		include($widget_path.'/widget.rows.php');
	}
?>
</div>
<?php if($setup_href) { ?>
	<div class="btn-wset text-center p10 font-12">
		<a href="<?php echo $setup_href;?>" class="win_memo">
			<span class="text-muted"><i class="fa fa-cog"></i> 위젯설정</span>
		</a>
	</div>
<?php } ?>
<script>
$(document).ready(function(){
	$('#<?php echo $widget_id;?> .bxslider').bxSlider({
		mode:'<?php echo ($wset['mode']) ? $wset['mode'] : 'horizontal';?>',
		slideMargin:<?php echo $margin;?>,
		pager:<?php echo ($wset['dot']) ? 'true' : 'false';?>,
		controls:<?php echo ($wset['nav']) ? 'true' : 'false';?>,
		infiniteLoop:<?php echo ($wset['loop']) ? 'true' : 'false';?>,
		auto:<?php echo ($wset['auto']) ? 'true' : 'false';?>,
		autoHover : <?php echo ($wset['hover']) ? 'false' : 'true';?>,
		prevText:'<i class="fa fa-angle-left"></i>',
		nextText:'<i class="fa fa-angle-right"></i>',
		<?php if($pager_custom) { ?>
			pagerCustom:'<?php echo $pager_custom;?>',
		<?php } ?>
		adaptiveHeight: true
	});
});
</script>
<?php
// 비우기
unset($list);
unset($wset);
?>