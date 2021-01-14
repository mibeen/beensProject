<?php
if (!defined('_GNUBOARD_')) exit; //개별 페이지 접근 불가

// 썸네일
$wset['thumb_w'] = $wset['thumb_h'] = 50;

// 추출
$list = miso_widget_list($wset, $widget_path, $widget_url);
$list_cnt = count($list);

if(!$list_cnt && $is_ajax) return;

?>
<?php if(!$is_ajax) { ?>
<ul class="post post-list">
<?php } ?>
<li class="item">
<?php
// 리스트
for ($i=0; $i < $list_cnt; $i++) { 

	// 포토
	$photo = ($list[$i]['as_icon']) ? apms_fa(apms_emo($list[$i]['as_icon'])) : '';
	if(!$photo) {
		$photo = apms_photo_url($list[$i]['mb_id']);
		if($photo) {
			$photo = '<img src="'.$photo.'">';
		} else {
			$photo = ($list[$i]['img']['src']) ? '<img src="'.$list[$i]['img']['src'].'">' : '<i class="fa fa-user"></i>';
		}
	}

?>
	<?php if($i > 0 && $i%$is_sero == 0) { ?>
		</li>
		<li class="item">
	<?php } ?>
	<div class="item-box">
		<div class="img-tbl">
			<div class="img-cell img-photo">
				<?php echo $photo;?>
			</div>
			<div class="img-cell">
				<a href="<?php echo $list[$i]['href'];?>"<?php echo $list[$i]['target'];?> class="ellipsis">
					<?php echo $list[$i]['icon'];?>
					<?php echo $list[$i]['bullet'];?>
					<?php echo $list[$i]['subject'];?>
				</a>
				<p class="font-12 ellipsis">
					<?php echo $list[$i]['name'];?>
					<?php if($list[$i]['comment']) { ?>
						<span class="count">
							+<?php echo $list[$i]['comment'];?>
						</span>
					<?php } ?>
					&nbsp;
					<?php echo apms_date($list[$i]['date'], '', 'before', 'm.d', 'Y.m.d'); ?>
				</p>
			</div>
		</div>
	</div>
<?php } ?>
<?php if(!$list_cnt) { ?>
	<div class="img-tbl bg-light">
		<div class="img-cell img-content text-center">
			게시물이 없습니다.
		</div>
	</div>
<?php } ?>
</li>
<?php if(!$is_ajax) { ?>
</ul>
<?php } ?>