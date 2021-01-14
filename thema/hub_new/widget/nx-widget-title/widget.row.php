<?php
if (!defined('_GNUBOARD_')) exit; //개별 페이지 접근 불가

// 추출하기
$wset['image'] = 1; //이미지글만 추출
$list = apms_board_rows($wset);
$list_cnt = count($list);

echo "asdjkfla;jkldf;a";

$rank = apms_rank_offset($wset['rows'], $wset['page']);

// 날짜
$is_date = (isset($wset['date']) && $wset['date']) ? true : false;
$is_dtype = (isset($wset['dtype']) && $wset['dtype']) ? $wset['dtype'] : 'm.d';
$is_dtxt = (isset($wset['dtxt']) && $wset['dtxt']) ? true : false;

// 새글
$is_new = (isset($wset['new']) && $wset['new']) ? $wset['new'] : 'red'; 

// 분류
$is_cate = (isset($wset['cate']) && $wset['cate']) ? true : false;

// 글내용 - 줄이 1줄보다 크고
/* (TEMP)
$is_cont = ($wset['line'] > 1) ? true : false; 
$is_details = ($is_cont) ? '' : ' no-margin'; 
*/

// 동영상아이콘
$is_vicon = (isset($wset['vicon']) && $wset['vicon']) ? '' : '<i class="fa fa-play-circle-o post-vicon"></i>'; 

// 스타일
$is_center = (isset($wset['center']) && $wset['center']) ? ' text-center' : ''; 
$is_bold = (isset($wset['bold']) && $wset['bold']) ? true : false; 

// 그림자
$shadow_in = '';
$shadow_out = (isset($wset['shadow']) && $wset['shadow']) ? apms_shadow($wset['shadow']) : '';
if($shadow_out && isset($wset['inshadow']) && $wset['inshadow']) {
	$shadow_in = '<div class="in-shadow">'.$shadow_out.'</div>';
	$shadow_out = '';	
}

// owl-hide : 모양유지용 프레임
?>
<script>
$(function(){
	$('#<?php echo $widget_id;?> ul').bxSlider({
		'autoControls' : false,
	    'controls' : false,
	    'pause' : <?php echo $interval;?>,
	    'mode' : '<?php echo $mode; ?>',
	    'auto' : <?php echo $interval == 0 ? 'false' : 'true' ?>,
	    'pager' : <?php echo $nav ?>
	});
})
</script>

<style>
#<?php echo $widget_id;?> .bx-wrapper .bx-viewport {
	box-shadow: 0;
}
@media screen and (min-width: 1221px){
	#<?php echo $widget_id;?> ul img{width: 100%; max-height: 438px; overflow: hidden;}	
}
</style>


<div id="<?php echo $widget_id;?>" class="widget-slider">
	<ul>
		
	<?php for ($i=0; $i < $list_cnt; $i++) { ?>
		<li><a href="<?php echo $list[$i]['link'];?>" <?php echo $list[$i]['target'];?>><img src="<?php echo $list[$i]['img'];?>" alt="<?php echo $list[$i]['caption'] ?>"></a></li>
	<?php $k++;} ?>

	</ul>

	<?php if($setup_href) { ?>
	<div class="btn-wset text-center p10">
		<a href="<?php echo $setup_href;?>" class="win_memo">
			<span class="text-muted font-12"><i class="fa fa-cog"></i> 위젯설정</span>
		</a>
	</div>
<?php } ?>
</div>