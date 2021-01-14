<?php
if (!defined('_GNUBOARD_')) exit; //개별 페이지 접근 불가

// 썸네일
$wset['thumb_w'] = ($wset['thumb_w'] == "") ? 200 : (int)$wset['thumb_w'];
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
$img_w = ($wset['img_w']) ? ' width:'.$wset['img_w'].';' : '';

$imgs = array();
$i = 0;
for($j=0; $j < $list_cnt; $j++) {
	if($list[$j]['img']['src']) {
		$imgs[$i] = $list[$j];
		$i++;
	}
}

// 랜덤 이미지
if($i > 1) {
	shuffle($imgs);
}

?>

<div class="post post-sp post-sp3">
<ul class="<?php echo $slider_name;?>">
<li class="item">
<?php
// 리스트
$z = 0;
for ($i=0; $i < $list_cnt; $i++) { 
?>
	<?php if($i > 0 && $i%$is_sero == 0) { ?>
		</li>
		<li class="item">
	<?php } ?>
	<?php if($i%$is_sero == 0) { // 이미지 
		// 이미지
		$bgcolor = $bgimg = '';
		if($list[$i]['img']['src']) {
			$bgimg = ($is_org) ? $list[$i]['img']['org'] : $list[$i]['img']['src'];
			$bgimg = ' style="background-image: url(\''.$bgimg.'\');'.$img_w.'"';
		} else {
			$bgcolor = ' bg-'.substr($list[$i]['rank'], -1);
		}
	?>
		<div class="item-box">
			<div class="img-tbl">
				<?php if($bgimg) { ?>
					<div class="img-cell img-bg<?php echo $bgcolor;?>"<?php echo $bgimg;?>>
						<a href="<?php echo $imgs[$i]['img_href'];?>"<?php echo $imgs[$i]['img_target'];?>>
							<div class="img-layer">
								<?php echo $raster;?>
								&nbsp;
							</div>
						</a>						
					</div>
				<?php } ?>
				<div class="img-cell img-content">
					<a href="<?php echo $imgs[$i]['href'];?>"<?php echo $imgs[$i]['target'];?>>
						<strong class="ellipsis">
							<?php //echo $imgs[$i]['icon'];?>
							<?php echo $imgs[$i]['bullet'];?>
							<?php echo $imgs[$i]['subject'];?>
						</strong>
					</a>
					<p class="ellipsis font-12">
						<?php echo $imgs[$i]['name'];?>
						<?php if($imgs[$i]['comment']) { ?>
							<span class="count">
								+<?php echo $imgs[$i]['comment'];?>
							</span>
						<?php } ?>
						&nbsp;
						<?php echo apms_date($imgs[$i]['date'], '', 'before', 'm.d', 'Y.m.d'); ?>
					</p>
					<div class="font-13 <?php echo $line;?>">
						<?php echo apms_cut_text($imgs[$i]['content'], 200);?>
					</div>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
	<?php $z++; } ?>
		<a href="<?php echo $list[$i]['href'];?>"<?php echo $list[$i]['target'];?> class="ellipsis">
			<span class="pull-right">
				<?php if($list[$i]['comment']) { ?>
					<span class="count">
						+<?php echo $list[$i]['comment'];?>
					</span>
				<?php } ?>
				&nbsp;
				<span class="font-12">
					<?php echo apms_date($list[$i]['date'], 'orangered', 'H:i', 'm.d', 'm.d'); ?>
				</span>
			</span>
			<?php echo $imgs[$i]['icon'];?>
			<?php echo $list[$i]['bullet'];?>
			<?php echo $list[$i]['subject'];?>
		</a> 
<?php } ?>
<?php if(!$list_cnt) { ?>
	<a class="ellipsis text-muted">
		게시물이 없습니다.
	</a>
<?php } ?>
</li>
</ul>
</div>