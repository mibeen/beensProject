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

<ul class="post post-sp">
<li class="item">
<div class="item-box">
<?php
// 리스트
$z = 0;
for ($i=0; $i < $list_cnt; $i++) { 
?>
	<?php if($i > 0 && $i%$is_sero == 0) { ?>
		</div>
		</li>
		<li class="item">
		<div class="item-box">
	<?php } ?>
	<?php if($i%$is_sero == 0) { // 이미지 
		// 이미지
		$bgcolor = $bgimg = '';
		if($imgs[$z]['img']['src']) {
			$bgimg = ($is_org) ? $imgs[$z]['img']['org'] : $imgs[$z]['img']['src'];
			$bgimg = ' style="background-image: url(\''.$bgimg.'\');"';
		} else {
			$bgcolor = ' bg-'.substr($imgs[$z]['rank'], -1);
		}
	?>
		<a href="<?php echo $imgs[$z]['img_href'];?>"<?php echo $imgs[$z]['img_target'];?>>
			<div class="img-box img-bg<?php echo $bgcolor;?>"<?php echo $bgimg;?>>
				<div class="img-item">
					<?php echo $raster;?>
					<?php //echo $imgs[$z]['label'];?>
					<span class="sr-only"><?php echo $imgs[$z]['subject'];?></span>
				</div>
			</div>
			<div class="sp-subj ellipsis">
				<span class="pull-right">
					<?php if($imgs[$z]['comment']) { ?>
						<span class="count">
							+<?php echo $imgs[$z]['comment'];?>
						</span>
					<?php } ?>
					&nbsp;
					<span class="font-12">
						<?php echo apms_date($imgs[$z]['date'], 'orangered', 'H:i', 'm.d', 'm.d'); ?>
					</span>
				</span>
				<?php //echo $imgs[$z]['icon'];?>
				<?php echo $imgs[$z]['bullet'];?>
				<b><?php echo $imgs[$z]['subject'];?></b>
			</div>
		</a> 
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
</div>
</li>
</ul>