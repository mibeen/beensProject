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

if(!$list_cnt && $is_ajax) return;

?>

<?php if(!$is_ajax) { ?>
<ul class="post post-img">
<?php } ?>
<li class="item">
<?php
// 리스트
for ($i=0; $i < $list_cnt; $i++) { 

	// 이미지
	$img = $bgcolor = $bgimg = '';
	if($list[$i]['img']['src']) {
		$tmpimg = ($is_org) ? $list[$i]['img']['org'] : $list[$i]['img']['src'];
		if($is_masonry_img) {
			$img = '<img src="'.$tmpimg.'" alt="" class="img-masonry">';
		} else {
			$bgimg = ' style="background-image: url(\''.$tmpimg.'\');"';
		}
	} else {
		$bgcolor = ' bg-'.substr($list[$i]['rank'], -1);
	}
?>

	<?php if($i > 0 && $i%$is_sero == 0) { ?>
		</li>
		<li class="item">
	<?php } ?>
	<div class="item-box">
		<a href="<?php echo $list[$i]['img_href'];?>"<?php echo $list[$i]['img_target'];?>>
			<div class="img-box img-bg<?php echo $bgcolor;?>"<?php echo $bgimg;?>>
				<div class="img-item">
					<?php echo $img;?>
					<?php echo $raster;?>
					<?php echo $list[$i]['label'];?>
					<?php if($wset['comment']) { // 댓글
						$photo = apms_photo_url($list[$i]['mb_id']);
						$photo = ($photo) ? '<img src="'.$photo.'">' : '<i class="fa fa-user"></i>';
					?>
						<div class="img-tbl">
							<div class="img-cell">
								<div class="img-photo">
									<?php echo $photo; ?>
								</div>
								<p class="<?php echo $line;?>">
									<?php echo $list[$i]['bullet'];?>
									<?php echo cut_str($list[$i]['content'], 200);?>
								</p>
								<div class="font-12">
									<?php echo $list[$i]['name'];?>
									&nbsp;
									<?php echo apms_date($list[$i]['date'], '', 'before', 'm.d', 'Y.m.d'); ?>
								</div>
							</div>
						</div>
					<?php } else { ?>
						<span class="sr-only">
							<?php echo $list[$i]['bullet'];?>
							<?php echo $list[$i]['subject'];?>
						</span>
					<?php } ?>
				</div>
			</div>
		</a> 
	</div>
<?php } ?>
<?php if(!$list_cnt) { ?>
	<div class="item-box">
		<div class="img-item bg-light">
			<div class="img-tbl">
				<div class="img-cell text-muted">
					게시물이 없습니다.
				</div>
			</div>
		</div>
	</div>
<?php } ?>
</li>
<?php if(!$is_ajax) { ?>
</ul>
<?php } ?>