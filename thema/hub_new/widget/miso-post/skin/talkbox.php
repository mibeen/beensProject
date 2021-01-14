<?php
if (!defined('_GNUBOARD_')) exit; //개별 페이지 접근 불가

// 썸네일 크기고정
$wset['thumb_w'] = $wset['thumb_h'] = 50;

// 추출
$list = miso_widget_list($wset, $widget_path, $widget_url);
$list_cnt = count($list);

if(!$list_cnt && $is_ajax) return;

?>
<?php if(!$is_ajax) { ?>
<ul class="post post-talkbox">
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
		<div class="talk-box-wrap">
			<div class="pull-left">
				<span class="talker-photo"><?php echo $photo;?></span>
			</div>
			<div class="talk-box talk-right">
				<div class="talk-bubble">
					<div class="img-content">
						<?php if($wset['comment']) { ?>
							<div>
								<b><?php echo $list[$i]['name'];?></b>
								&nbsp;
								<?php echo apms_date($list[$i]['date'], '', 'before', 'm.d', 'Y.m.d'); ?>
							</div>
							<a href="<?php echo $list[$i]['href'];?>"<?php echo $list[$i]['target'];?>>
								<p class="font-13 <?php echo $line;?>">
									<?php echo $list[$i]['icon'];?>
									<?php echo $list[$i]['bullet'];?>
									<?php echo cut_str($list[$i]['content'], 200);?>
								</p>
							</a>
						<?php } else { ?>
							<a href="<?php echo $list[$i]['href'];?>"<?php echo $list[$i]['target'];?>>
								<strong class="ellipsis">
									<?php echo $list[$i]['icon'];?>
									<?php echo $list[$i]['bullet'];?>
									<?php echo $list[$i]['subject'];?>
								</strong>
							</a>	
							<p class="font-12">
								<?php echo $list[$i]['name'];?>
								<?php if($list[$i]['comment']) { ?>
									<span class="count">
										+<?php echo $list[$i]['comment'];?>
									</span>
								<?php } ?>
								&nbsp;
								<?php echo apms_date($list[$i]['date'], '', 'before', 'm.d', 'Y.m.d'); ?>
							</p>
							<p class="font-13 <?php echo $line;?>">
								<?php echo apms_cut_text($list[$i]['content'], 200);?>
							</p>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
<?php if(!$list_cnt) { ?>
	<div class="item-box">
		<div class="talk-box-wrap">
			<div class="pull-left">
				<span class="talker-photo"><i class="fa fa-user"></i></span>
			</div>
			<div class="talk-box talk-right">
				<div class="talk-bubble">
					<div class="img-content">
						게시물이 없습니다.
					</div>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
</li>
<?php if(!$is_ajax) { ?>
</ul>
<?php } ?>