<?php
if (!defined('_GNUBOARD_')) exit; //개별 페이지 접근 불가

if(!$wset['slider']) {
	$wset['slider'] = 2;
	for($i=1; $i <= 2; $i++) {
		$wset['use'.$i] = 1;
		$wset['cl'.$i] = 'center';
		$wset['img'.$i] = $widget_url.'/img/title.jpg';
		$wset['cs'.$i] = 'title';
		$wset['cf'.$i] = 'white';
		$wset['cc'.$i] = 'black';
		$wset['caption'.$i] = '위젯설정에서 사용할 타이틀을 등록해 주세요.';
	}
}

// 높이
$height = (isset($wset['height']) && $wset['height']) ? $wset['height'] : '400px';
$lg = (isset($wset['lg']) && $wset['lg']) ? $wset['lg'] : '';
$md = (isset($wset['md']) && $wset['md']) ? $wset['md'] : '';
$sm = (isset($wset['sm']) && $wset['sm']) ? $wset['sm'] : '';
$xs = (isset($wset['xs']) && $wset['xs']) ? $wset['xs'] : '';

// 효과
$effect = apms_carousel_effect($wset['effect']);

// 간격
if($wset['auto'] == "") {
	$wset['auto'] = 3000;
}
$interval = apms_carousel_interval($wset['auto']);

// 작은화살표
$is_small = (isset($wset['arrow']) && $wset['arrow']) ? ' div-carousel' : '';

$list = array();

// 슬라이더
$k=0;
for ($i=1; $i <= $wset['slider']; $i++) {

	if(!$wset['use'.$i]) continue; // 사용하지 않으면 건너뜀

	$list[$k]['cl'] = ($wset['cl'.$i]) ? ' background-position:'.$wset['cl'.$i].' center;' : '';
	$list[$k]['img'] = $wset['img'.$i];
	$list[$k]['link'] = ($wset['link'.$i]) ? $wset['link'.$i] : 'javascript:;';
	$list[$k]['target'] = ($wset['target'.$i]) ? ' target="'.$wset['target'.$i].'"' : '';
	$list[$k]['label'] = $wset['label'.$i];
	$list[$k]['txt'] = $wset['txt'.$i];
	$list[$k]['cs'] = $wset['cs'.$i];
	$list[$k]['caption'] = $wset['caption'.$i];
	$list[$k]['cf'] = $wset['cf'.$i];
	$list[$k]['cc'] = $wset['cc'.$i];
	$k++;
}

$list_cnt = count($list);

// 랜덤
if($wset['rdm'] && $list_cnt) shuffle($list);

// 랜덤아이디
$widget_id = apms_id(); 

?>



<script>
$(function(){
	$('#<?php echo $widget_id;?> ul').bxSlider({
		'autoControls' : true,
	    'controls' : false,
	    'pause' : <?php echo $interval;?>
	});
})
</script>


<div id="<?php echo $widget_id;?>" class="widget-slider">
	<ul>
		
	<?php for ($i=0; $i < $list_cnt; $i++) { ?>
		<li><a href="<?php echo $list[$i]['link'];?>" <?php echo $list[$i]['target'];?>><img src="<?php echo $list[$i]['img'];?>" alt=""></a></li>
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
