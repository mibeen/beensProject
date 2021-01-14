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




<script src="/js/nx.midslider/nx.midslider.js?ver=180806"></script>
<script>
$(function(){

  var option = {
  	'autoplay' : <?php echo $is_autoplay; ?>,
  	'interval' : <?php echo $autoplay_interval; ?>,
  	'speed' : <?php echo $speed; ?>
  }

  var midSlider = new mid_slider('#<?php echo $widget_id;?>', option);

})
</script>

<div class="slider-visible" id="<?php echo $widget_id;?>" >
	<div class="mid-slider">
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
			<li class="whatson<?php echo($i)?>"><a href="<?php echo $list[$i]['wr_link1'];?>" <?php echo $target;?> ><img <?php echo $img_src;?> alt="" caption1="<?php echo strip_tags($list[$i]['subject'])?>" caption2="<?php echo strip_tags($list[$i]['wr_content'])?>"></a></li>
			<?php $k++;} ?>

		</ul>
	</div>
</div>
<div class="mid-slider-caption">
	<p class="title">&nbsp;</p>
	<p class="sub">&nbsp;</p>
	<button class="btn_left"><img src="/thema/hub/assets/images/slide_arrow.png" alt="이전"></button>
	<button class="btn_right"><img src="/thema/hub/assets/images/slide_arrow.png" alt="다음"></button>
</div>

<?php if($setup_href) { ?>
<div class="btn-wset text-center p10">
	<a href="<?php echo $setup_href;?>" class="win_memo">
		<span class="text-muted font-12"><i class="fa fa-cog"></i> 위젯설정</span>
	</a>
</div>
<?php } ?>