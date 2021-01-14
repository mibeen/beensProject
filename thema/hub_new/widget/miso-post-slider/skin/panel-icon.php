<?php
if (!defined('_GNUBOARD_')) exit; //개별 페이지 접근 불가

// 썸네일
$wset['thumb_w'] = $wset['thumb_h'] = '';

// 추출
$list = miso_widget_list($wset, $widget_path, $widget_url);
$list_cnt = count($list);

?>

<div class="post post-panel-icon">
<ul class="<?php echo $slider_name;?>">
<li class="item">
<?php
// 리스트
for ($i=0; $i < $list_cnt; $i++) { 

	// 아이콘
	$icon = ($list[$i]['as_icon']) ? apms_fa($list[$i]['as_icon']) : '<i class="fa fa-home"></i>';

	// 컬러
	$hcolor = substr($list[$i]['rank'], -1);

?>
	<?php if($i > 0 && $i%$is_sero == 0) { ?>
		</li>
		<li class="item">
	<?php } ?>
	<div class="item-box">
		<div class="item-content bg-h<?php echo $hcolor;?> en">
			<div class="img-icon">
				<?php echo $icon;?>
			</div>
			<a href="<?php echo $list[$i]['href'];?>"<?php echo $list[$i]['target'];?>>
				<h3><?php echo $list[$i]['subject'];?></h3>
			</a>
			<hr class="bg-<?php echo $hcolor;?>" />
			<p><?php echo $list[$i]['content']; ?></p>
		</div>
	</div>
<?php } ?>
<?php if(!$list_cnt) { ?>
	<div class="img-item bg-light">
		<div class="img-tbl">
			<div class="img-cell text-muted">
				게시물이 없습니다.
			</div>
		</div>
	</div>
<?php } ?>
</li>
</ul>
</div>