<?php
if (!defined('_GNUBOARD_')) exit; //개별 페이지 접근 불가

// 썸네일
$wset['thumb_w'] = ($wset['thumb_w'] == "") ? 400 : (int)$wset['thumb_w'];
$wset['thumb_h'] = ($wset['thumb_h'] == "") ? 0 : (int)$wset['thumb_h'];

$is_org = false;
if(!$wset['thumb_w']) {
	$is_org = true;
	$wset['thumb_w'] = 10;
	$wset['thumb_h'] = 10;
}

// 추출
$list = miso_widget_list($wset, $widget_path, $widget_url);
$list_cnt = count($list);

// 이미지
$img_w = ($wset['img_w']) ? ' style="width:'.$wset['img_w'].' !important;"' : '';

?>

<div class="post post-headline post-right small-nav left-nav">
<ul class="<?php echo $slider_name;?>">
<li class="item">
<?php
// 리스트
for ($i=0; $i < $list_cnt; $i++) { 

	// 이미지
	$bg = substr($list[$i]['rank'], -1);
	$bgcolor = $bgimg = '';
	if($list[$i]['img']['src']) {
		$bgimg = ($is_org) ? $list[$i]['img']['org'] : $list[$i]['img']['src'];
		$bgimg = ' style="background-image: url(\''.$bgimg.'\');"';
	} else {
		$bgcolor = ' bg-'.$bg;
	}
?>
	<?php if($i > 0 && $i%$is_sero == 0) { ?>
		</li>
		<li class="item">
	<?php } ?>
	<div class="item-box">
		<div class="img-box img-bg<?php echo $bgcolor;?>"<?php echo $bgimg;?>>
			<div class="img-item">
				<?php echo $raster;?>
				<?php echo $list[$i]['label'];?>
				<div class="img-content en"<?php echo $img_w;?>>
					<a href="<?php echo $list[$i]['img_href'];?>"<?php echo $list[$i]['img_target'];?>>
						<div class="img-info">
							<?php echo date("Y.m.d", $list[$i]['date']);?>
							<?php if($list[$i]['comment']) { ?>
								&nbsp;
								<i class="fa fa-comment"></i>
								<?php echo $list[$i]['comment'];?>
							<?php } ?>
						</div>
						<h3>
							<?php echo $list[$i]['bullet'];?>
							<?php echo $list[$i]['subject'];?>
						</h3>
						<p class="text-right">자세히보기</p>
					</a>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
<?php if(!$list_cnt) { ?>
	<div class="img-box bg-navy">
		<div class="img-item">
			<div class="img-content text-center">
				게시물이 없습니다.
			</div>
		</div>
	</div>
<?php } ?>
</li>
</ul>
</div>