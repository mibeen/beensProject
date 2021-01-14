<?php
if (!defined('_GNUBOARD_')) exit; //개별 페이지 접근 불가

// 썸네일 없음
$wset['thumb_w'] = $wset['thumb_h'] = '';

// 추출
$list = miso_widget_list($wset, $widget_path, $widget_url);
$list_cnt = count($list);

?>

<div class="post post-list post-margin">
<ul class="<?php echo $slider_name;?>">
<li class="item">
<?php
// 리스트
for ($i=0; $i < $list_cnt; $i++) { 
?>
	<?php if($i > 0 && $i%$is_sero == 0) { ?>
		</li>
		<li class="item">
	<?php } ?>
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
			<?php echo $list[$i]['icon'];?>
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