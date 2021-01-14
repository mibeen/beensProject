<?php
if (!defined('_GNUBOARD_')) exit; //개별 페이지 접근 불가

// Lightbox
if($wset['lightbox']) {
	apms_script('lightbox');
}

// 모달창
$wset['modal_js'] = ($wset['modal'] == "1") ? apms_script('modal') : '';

// 모드
switch($wset['mode']) {
	case '1'	: $is_mode = 'more'; break;
	case '2'	: $is_mode = 'infinite'; break;
	default		: $is_mode = ''; break;
}

// 써클
$is_circle = ($wset['round'] == "1") ? true : false;

// 메이슨리
$is_masonry = ($wset['masonry']) ? true : false;
$is_masonry_img = false;
if ($wset['masonry'] == "2" && !$is_circle && !$wset['comment']) {
	$is_masonry_img = true;
	$wset['heights'] = 1; //높이 1로 고정
}

// 모드출력
if($is_mode || $is_masonry) {
 	apms_script('imagesloaded');
}
if($is_masonry) {
 	apms_script('masonry');
}
if($is_mode) {
	apms_script('infinite');

	// 더보기 링크
	$more_href = $widget_url.'/widget.rows.php?thema='.urlencode(THEMA).'&amp;wname='.urlencode($wname).'&amp;wid='.urlencode($wid);
	if($opt) $more_href .= '&amp;opt='.urlencode($opt);
	if($mopt) $more_href .= '&amp;mopt='.urlencode($mopt);
	if(isset($wdir) && $wdir) $more_href .= '&amp;wdir='.urlencode($wdir);
	if(isset($add) && $add) $more_href .= '&amp;add='.urlencode($add);
	$more_href .= '&amp;page=2';
}

//add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$widget_url.'/widget.css">', 0);

// 기본설정
$is_ajax = false;
if($wset['items']) {
	list($wset['item'], $wset['lg'], $wset['md'],$wset['sm'],$wset['xs']) = explode(",", $wset['items']);
}
if(!$wset['item']) $wset['item'] = 1;

// 높이
if($is_circle) {
	$wset['heights'] = '100%';
}
if($wset['heights']) {
	list($wset['height'], $wset['hl'], $wset['hm'], $wset['hs'], $wset['hx']) = explode(",", $wset['heights']);
}
if(!$wset['height']) $wset['height'] = '75%';

// 스킨 불러오기
if(!$wset['skin']) $wset['skin'] = 'basic';

// 마진(간격)
$margin = ($wset['margin'] == "") ? 10 : (int)$wset['margin'];
$gap = ($wset['gap'] == "") ? $margin : $wset['gap'];

// 댓글
$is_comment = ($wset['comment']) ? true : false;

// 라인
$wset['line'] = (!$wset['line']) ? 1 : (int)$wset['line'];
$wset['lineh'] = (!$wset['lineh']) ? 20 : (int)$wset['lineh'];

// CSS
$css = '';
$css .= ($is_comment) ? ' is-comment' : '';
$css .= ($is_masonry_img) ? ' is-masonry' : '';
if($wset['round']) {
	$css .= ($is_circle) ? ' is-circle' : ' is-round';
} else {
	$css .= ' is-box';
}

// 위젯아이디
$widget_id = apms_id(); 
$wset['wid'] = $wid;

?>
<style>
	/*#<?php echo $widget_id;?> .post { margin-right:<?php echo $margin * (-1);?>px; margin-bottom:<?php echo $gap * (-1);?>px; }*/
	#<?php echo $widget_id;?> .item { width:<?php echo apms_img_width($wset['item']);?>%; }
	#<?php echo $widget_id;?> .item-box { margin-right:<?php echo $margin;?>px; margin-bottom:<?php echo $gap;?>px; }
	<?php if(!$is_masonry_img) { ?>
	#<?php echo $widget_id;?> .img-box { padding-bottom:<?php echo $wset['height'];?>; }
	<?php } ?>
	<?php if($wset['round'] && !$is_circle) { ?>
	#<?php echo $widget_id;?> .img-box,
	#<?php echo $widget_id;?> .img-item { border-radius:<?php echo $wset['round'];?>px; -webkit-border-radius: <?php echo $wset['round'];?>px; -moz-border-radius: <?php echo $wset['round'];?>px; }
	<?php } ?>
	<?php if($wset['line'] > 1) { ?>
	#<?php echo $widget_id;?> .ellipsis-line { -webkit-line-clamp: <?php echo $wset['line'];?>; line-height: <?php echo $wset['lineh'];?>px; <?php echo ($is_masonry_img) ? 'max-' : '';?>height: <?php echo ($wset['lineh'] * $wset['line']);?>px; }
	<?php } ?>
	<?php if(_RESPONSIVE_) { // 반응형일 때만 작동	?>
		<?php if($wset['lg'] || $wset['hl']) { ?>
		@media (max-width:1199px) {
			<?php if($wset['lg']) { ?>
			.responsive #<?php echo $widget_id;?> .item { width:<?php echo apms_img_width($wset['lg']);?>%; } 
			<?php } ?>
			<?php if($wset['hl']) { ?>
			.responsive #<?php echo $widget_id;?> .img-box { padding-bottom:<?php echo $wset['hl'];?>; }
			<?php } ?>
		}
		<?php } ?>
		<?php if($wset['md'] || $wset['hm']) { ?>
		@media (max-width:991px) {
			<?php if($wset['md']) { ?>
			.responsive #<?php echo $widget_id;?> .item { width:<?php echo apms_img_width($wset['md']);?>%; } 
			<?php } ?>
			<?php if($wset['hm']) { ?>
			.responsive #<?php echo $widget_id;?> .img-box { padding-bottom:<?php echo $wset['hm'];?>; }
			<?php } ?>
		}
		<?php } ?>
		<?php if($wset['sm'] || $wset['hs']) { ?>
		@media (max-width:767px) {
			<?php if($wset['sm']) { ?>
			.responsive #<?php echo $widget_id;?> .item { width:<?php echo apms_img_width($wset['sm']);?>%; } 
			<?php } ?>
			<?php if($wset['hs']) { ?>
			.responsive #<?php echo $widget_id;?> .img-box { padding-bottom:<?php echo $wset['hs'];?>; }
			<?php } ?>
		}
		<?php } ?>
		<?php if($wset['xs'] || $wset['hx']) { ?>
		@media (max-width:480px) {
			<?php if($wset['xs']) { ?>
			.responsive #<?php echo $widget_id;?> .item { width:<?php echo apms_img_width($wset['xs']);?>%; } 
			<?php } ?>
			<?php if($wset['hx']) { ?>
			.responsive #<?php echo $widget_id;?> .img-box { padding-bottom:<?php echo $wset['hx'];?>; }
			<?php } ?>
		}
		<?php } ?>
	<?php } ?>
</style>

<?php if($is_mode == 'infinite' && $setup_href) { ?>
	<div class="btn-wset text-center p10">
		<a href="<?php echo $setup_href;?>" class="win_memo">
			<span class="text-muted font-12"><i class="fa fa-cog"></i> 위젯설정</span>
		</a>
	</div>
<?php } ?>

<div id="<?php echo $widget_id;?>" class="miso-post <?php echo $css;?>">
	<?php 
		if(!$is_mode && $wset['cache'] > 0) { // 캐시적용시
			echo apms_widget_cache($widget_path.'/widget.rows.php', $wname, $wid, $wset);
		} else {
			include($widget_path.'/widget.rows.php');
		}
	?>
	<div class="clearfix"></div>
	<?php if($is_mode) { ?>
		<div id="<?php echo $widget_id;?>-nav">
			<a href="<?php echo $more_href;?>"></a>
		</div>
		<?php if($is_mode == 'more') { ?>
			<div id="<?php echo $widget_id;?>-more" class="more-post">
				<a href="#morepost" title="더보기">
					<?php echo ($wset['micon']) ? apms_fa($wset['micon']) : '<i class="fa fa-arrow-circle-down"></i>'; // 더보기 아이콘 ?>
				</a>
			</div>
		<?php } ?>
	<?php } ?>
</div>

<?php if($setup_href) { ?>
	<div class="btn-wset text-center p10">
		<a href="<?php echo $setup_href;?>" class="win_memo">
			<span class="text-muted font-12"><i class="fa fa-cog"></i> 위젯설정</span>
		</a>
	</div>
<?php } ?>

<?php if($is_mode || $is_masonry) { ?>
<script>
	$(function(){
		var $<?php echo $widget_id;?> = $('#<?php echo $widget_id;?> .post');
		<?php if($is_masonry) { ?>
		$<?php echo $widget_id;?>.imagesLoaded(function(){
			$<?php echo $widget_id;?>.masonry({
				columnWidth : '.item',
				itemSelector : '.item',
				isAnimated: true
			});
		});
		<?php } ?>
		<?php if($is_mode) { ?>
		$<?php echo $widget_id;?>.infinitescroll({
			navSelector  : '#<?php echo $widget_id;?>-nav', 
			nextSelector : '#<?php echo $widget_id;?>-nav a',
			itemSelector : '.item', 
			loading: {
				msgText: '로딩 중...',
				finishedMsg: '더이상 게시물이 없습니다.',
				img: '<?php echo APMS_PLUGIN_URL;?>/img/loader.gif',
			}
		}, function( newElements ) {
			<?php if($is_masonry) { ?>
			var $newElems = $( newElements ).css({ opacity: 1 });
			$newElems.imagesLoaded(function(){
				$<?php echo $widget_id;?>.masonry('appended', $newElems, true);
			});
			<?php } else { ?>
			var $newElems = $( newElements ).css({ opacity: 0 });
			$newElems.imagesLoaded(function(){
				$newElems.animate({ opacity: 1 });
				$<?php echo $widget_id;?>.append($newElems);
			});
			<?php } ?>
		});
		<?php if($is_mode == 'more') { ?>
		$(window).unbind('#<?php echo $widget_id;?> .infscr');
		$('#<?php echo $widget_id;?>-more a').click(function(){
		   $<?php echo $widget_id;?>.infinitescroll('retrieve');
		   $('#<?php echo $widget_id;?>-nav').show();
			return false;
		});
		$(document).ajaxError(function(e,xhr,opt){
			if(xhr.status==404) $('#<?php echo $widget_id;?>-nav').remove();
		});
		<?php } ?>
		<?php } ?>
	});
</script>
<?php } ?>

<?php
// 비우기
unset($list);
unset($wset);
?>
