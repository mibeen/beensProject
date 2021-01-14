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

if(_RESPONSIVE_) { //반응형일 때만 작동: item개수, margin
	$lgg = (isset($wset['lgg']) && ($wset['lgg'] > 0 || $wset['lgg'] == "0")) ? $wset['lgg'] : $gap;

	$mdg = (isset($wset['mdg']) && ($wset['mdg'] > 0 || $wset['mdg'] == "0")) ? $wset['mdg'] : $lgg;

	$smg = (isset($wset['smg']) && ($wset['smg'] > 0 || $wset['smg'] == "0")) ? $wset['smg'] : $mdg;

	$xsg = (isset($wset['xsg']) && ($wset['xsg'] > 0 || $wset['xsg'] == "0")) ? $wset['xsg'] : $smg;
}

?>
<style>
	#<?php echo $widget_id;?> .owl-stage { margin: 0 auto; }
	#<?php echo $widget_id;?> .post-subject { height:<?php echo $line_height;?>px; }
	#<?php echo $widget_id;?> .img-wrap { padding-bottom:<?php echo $img_h;?>%; }
</style>
<div id="<?php echo $widget_id;?>" class="nx-home-sites<?php if(isset($wset['nav']) && $wset['nav']) {echo(' nav-'.$wset['nav_size']);}?>">
	<?php 
	if ($wset['dot']) {
		?>
		<div id="<?php echo $widget_id;?>-dots" class="nx-home-sites-dots-container"></div>
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
	<div class="btn-wset text-center p10">
		<a href="<?php echo $setup_href;?>" class="win_memo">
			<span class="text-muted font-12"><i class="fa fa-cog"></i> 위젯설정</span>
		</a>
	</div>
<?php } ?>
<script>
$(document).ready(function(){
	$('#<?php echo $widget_id;?> .owl-carousel').owlCarousel({
		items:5,
		loop:true,
		autoWidth:true,
		<?php if($is_autoplay > 0) { ?>
			autoplay:true,
			autoplayTimeout:<?php echo $is_autoplay; ?>,
		<?php } ?>
		<?php if($is_speed) { ?>
			smartSpeed:<?php echo $is_speed; ?>,
		<?php } ?>
		<?php if($is_lazy) { ?>
			lazyLoad : true,
		<?php } ?>
		dots:<?php echo ($wset['dot']) ? 'true' : 'false';?>,
		dotsContainer:'#<?php echo $widget_id;?>-dots',
		dotClass:'nx-home-sites-dot',
		<?php if(isset($wset['nav']) && $wset['nav']) { ?> 
		nav:true,
		navContainerClass:'nav-container',
		navClass:['nav-l-<?php echo($wset['nav_size'])?>', 'nav-r-<?php echo($wset['nav_size'])?>'],
		navText:['<img src="<?php echo($widget_url)?>/nav_l_<?php echo($wset['nav_size'])?>.png" alt="이전">','<img src="<?php echo($widget_url)?>/nav_r_<?php echo($wset['nav_size'])?>.png" alt="다음">'],
		<?php } ?>
		responsiveClass:true,
	    responsive:{
	        0:{
	            margin:<?php echo $xsg;?>
	        },
	        480:{
	            margin:<?php echo $smg;?>
	        },
	        768:{
	            margin:<?php echo $mdg;?>
	        },
	        992:{
	            margin:<?php echo $lgg;?>
	        },
	        1200:{
	            margin:<?php echo $gap;?>
	        }
	    }
	});
});
</script>