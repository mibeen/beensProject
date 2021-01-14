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

if(!$list_cnt && $is_ajax) return;

// 이미지
$img_w = ($wset['img_w']) ? ' width:'.$wset['img_w'].';' : '';

?>
<?php if(!$is_ajax) { ?>
<ul class="post post-webzine">
<?php } ?>
<li class="item">
<?php
// 리스트
for ($i=0; $i < $list_cnt; $i++) { 

	// 이미지
	$bgcolor = $bgimg = '';
	if($list[$i]['img']['src']) {
		$bgimg = ($is_org) ? $list[$i]['img']['org'] : $list[$i]['img']['src'];
		$bgimg = ' style="background-image: url(\''.$bgimg.'\');'.$img_w.'"';
	} else {
		$bgcolor = ' bg-'.substr($list[$i]['rank'], -1);
	}

?>
	<?php if($i > 0 && $i%$is_sero == 0) { ?>
		</li>
		<li class="item">
	<?php } ?>
	<div class="item-box">
		<div class="img-tbl">
			<?php if($bgimg) { ?>
				<div class="img-cell img-bg<?php echo $bgcolor;?>"<?php echo $bgimg;?>>
					<a href="<?php echo $list[$i]['img_href'];?>"<?php echo $list[$i]['img_target'];?>>
						<div class="img-layer">
							<?php echo $raster;?>
							&nbsp;
						</div>
					</a>						
				</div>
			<?php } ?>
			<div class="img-cell img-content">
				<a href="<?php echo $list[$i]['href'];?>"<?php echo $list[$i]['target'];?>>
					<strong class="ellipsis">
						<?php echo $list[$i]['icon'];?>
						<?php echo $list[$i]['bullet'];?>
						<?php echo $list[$i]['subject'];?>
					</strong>
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
				<div class="font-13 <?php echo $line;?>">
					<?php echo apms_cut_text($list[$i]['content'], 200);?>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
<?php if(!$list_cnt) { ?>
	<div class="item-box">
		<div class="img-tbl bg-light">
			<div class="img-cell img-content text-center">
				게시물이 없습니다.
			</div>
		</div>
	</div>
<?php } ?>
</li>

<?php if(!$is_ajax) { ?>
</ul>
<?php } ?>