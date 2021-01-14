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

?>

<div class="post post-title">
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
		<div class="img-box img-bg<?php //echo $bgcolor;?>"<?php echo $bgimg;?>>
			<div class="img-item">
				<?php echo $raster;?>
				<?php echo $list[$i]['label'];?>
				<div class="img-tbl">
					<div class="img-cell">
						<div class="img-content en">
							<a href="<?php echo $list[$i]['img_href'];?>"<?php echo $list[$i]['img_target'];?>>
								<div class="img-bar">
									<?php if($list[$i]['subject']) { ?>
										<h3 class="img-title">
											<?php echo $list[$i]['bullet'];?>
											<?php echo $list[$i]['subject'];?>
										</h3>
									<?php } ?>
									<?php if($list[$i]['subject'] && $list[$i]['content']) { ?>
										<hr class="img-hr bg-<?php echo $bg;?>">
									<?php } ?>
									<?php if($list[$i]['content']) { ?>
										<p class="img-text">
											<?php echo $list[$i]['content'];?>
										</p>
									<?php } ?>
								</div>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
<?php if(!$list_cnt) { ?>
	<div class="img-box bg-light">
		<div class="img-item">
			<div class="img-tbl">
				<div class="img-cell text-center text-muted">
					게시물이 없습니다.
				</div>
			</div>
		</div>
	</div>
<?php } ?>
</li>
</ul>
</div>