<?php
if (!defined('_GNUBOARD_')) exit; //개별 페이지 접근 불가

// 추출하기
$wset['image'] = 1; //이미지글만 추출
$list          = apms_board_rows($wset);
$list_cnt      = count($list);

$rank          = apms_rank_offset($wset['rows'], $wset['page']);

// 날짜
$is_date       = (isset($wset['date']) && $wset['date']) ? true : false;
$is_dtype      = (isset($wset['dtype']) && $wset['dtype']) ? $wset['dtype'] : 'm.d';
$is_dtxt       = (isset($wset['dtxt']) && $wset['dtxt']) ? true : false;

// 새글
$is_new        = (isset($wset['new']) && $wset['new']) ? $wset['new'] : 'red'; 

// 분류
$is_cate       = (isset($wset['cate']) && $wset['cate']) ? true : false;

// 동영상아이콘
$is_vicon      = (isset($wset['vicon']) && $wset['vicon']) ? '' : '<i class="fa fa-play-circle-o post-vicon"></i>'; 

// 스타일
$is_center     = (isset($wset['center']) && $wset['center']) ? ' text-center' : ''; 
$is_bold       = (isset($wset['bold']) && $wset['bold']) ? true : false; 

// 그림자
$shadow_in     = '';
$shadow_out = (isset($wset['shadow']) && $wset['shadow']) ? apms_shadow($wset['shadow']) : '';
if($shadow_out && isset($wset['inshadow']) && $wset['inshadow']) {
	$shadow_in = '<div class="in-shadow">'.$shadow_out.'</div>';
	$shadow_out = '';	
}

//속도
$speed = isset($wset['speed']) && (int) $wset['speed'] > 0 ? (int) $wset['speed'] : 000;
$is_autoplay = (isset($wset['auto']) && $wset['auto'] > 0) ? 1 : 0;
$autoplay_interval = (isset($wset['auto']) && ($wset['auto'] > 0 || $wset['auto'] == "0")) ? $wset['auto'] : 3000;
?>




<!-- <script src="/js/nx.midslider/nx.midslider.js?ver=180806"></script> -->
<?php #echo $widget_path; ?>
<link rel="stylesheet" type="text/css" href="<?php echo $widget_url; ?>/slick/slick.css"/>
<script type="text/javascript" src="<?php echo $widget_url; ?>/slick/slick.min.js"></script>
<script>
$(function(){

	$("#<?php echo $widget_id; ?> ul").slick({
		infinite: true,
		slidesToShow: 5,
		slidesToScroll: 1,
		arrows: false,
		autoplay: true,
		dots : false,
		responsive: [
			{
			  breakpoint: 1024,
			  settings: {
			    slidesToShow: 3,
			    slidesToScroll: 3,
			    infinite: true,
			    dots: false
			  }
			},
			{
			  breakpoint: 600,
			  settings: {
			    slidesToShow: 2,
			    slidesToScroll: 2
			  }
			},
			{
			  breakpoint: 480,
			  settings: {
			    slidesToShow: 1,
			    slidesToScroll: 1
			  }
			}
		]
	});

})
</script>
<style>
	/** Common Stylesheet */
	.widget-whatson li { -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; }
	.widget-whatson a { display: block; background: #d2d2d2; }
	.widget-whatson .img-wrap { position: relative; overflow: hidden; padding-bottom: 56.5%; }
	.widget-whatson .img-wrap img { position: absolute; width: 100%; top: 0; left: 0; }
	.widget-whatson .txt-wrap { padding: 15px; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; }
	.widget-whatson .title { text-align: center; margin-bottom: 5px; overflow: hidden; text-overflow: ellipsis; display: block;  display: -webkit-box; -webkit-line-clamp: 2;  -webkit-box-orient: vertical; line-height: 20px; height: 40px; font-size: 14px; }
	.widget-whatson .text { display: none; text-align: center; padding: 5px 0; font-size: 12px; color: #666; -ms-text-overflow: ellipsis; text-overflow: ellipsis; overflow: hidden; white-space: nowrap; }


	/** Specific Stylesheet */
	#<?php echo $widget_id; ?> li { padding: 15px; }
	<?php 
	if ( $list_cnt > 0 ) {
		?>
		#<?php echo $widget_id; ?> { margin-top: -110px; }
		<?php
	}
	?>
	@media screen and (max-width: 768px) {
		#<?php echo $widget_id; ?> { margin-top: 10px; }
		.widget-whatson a { background: #FFF; }
		.widget-whatson .txt-wrap { border: 1px solid #DDD; border-top: 0; }
	}
</style>

<div class="slider-visible" id="<?php echo $widget_id;?>" >
	<div class="widget-whatson">
		<ul>

		<?php for ($i=0; $i < $list_cnt; $i++) { 

		//라벨 체크
		$wr_label = '';
		$is_lock = false;
		if ($list[$i]['secret'] || $list[$i]['is_lock']) {
			$is_lock = true;
			$wr_label = '<div class="label-cap bg-orange">Lock</div>';	
		} else if ($wset['rank']) {
			$wr_label = '<div class="label-cap bg-'.$wset['rank'].'">Top'.$rank.'</div>';
			$rank++;
		} else if ($list[$i]['new']) {
			$wr_label = '<div class="label-cap bg-'.$is_new.'">New</div>';	
		}

		// 링크이동
		$target = '';
		if($is_link_target && $list[$i]['wr_link1']) {
			$target = $is_link_target;
			$list[$i]['href'] = $list[$i]['link_href'][1];
		}

		//볼드체
		if($is_bold) {
			$list[$i]['subject'] = '<b>'.$list[$i]['subject'].'</b>';
		}

		// Lazy
		$img_src = ($is_lazy) ? 'data-src="'.$list[$i]['img']['src'].'" class="lazyOwl"' : 'src="'.$list[$i]['img']['src'].'"';

		if( strpos($list[$i]['wr_link1'], 'http') == false ){
			if( strlen($list[$i]['wr_link1']) < 1 ){
				$list[$i][$wr_link1] = 'javascript:void(0)';
				#continue;
			}
			//$list[$i]['wr_link1'] = 'http' . '\:' . '//' . $list[$i]['wr_link1'];
		}

			?>
			<li class="whatson<?php echo($i)?>">
				<a href="<?php echo $list[$i]['wr_link1'];?>" <?php echo $target;?> >
					<div class="img-wrap"><img <?php echo $img_src;?> alt="" caption1="<?php echo strip_tags($list[$i]['subject'])?>" caption2="<?php echo strip_tags($list[$i]['wr_content'])?>"></div>
					<div class="txt-wrap">
						<p class="title"><?php echo strip_tags($list[$i]['subject']) ?></p>
						<p class="text"><?php echo strip_tags($list[$i]['wr_datetime']) ?></p>
					</div>
				</a>
			</li>
			<?php $k++;} ?>

		</ul>
	</div>
</div>

<?php if($setup_href) { ?>
<div class="btn-wset text-center p10">
	<a href="<?php echo $setup_href;?>" class="win_memo">
		<span class="text-muted font-12"><i class="fa fa-cog"></i> 위젯설정</span>
	</a>
</div>
<?php } ?>