<?php
if (!defined('_GNUBOARD_')) exit; //개별 페이지 접근 불가

$wset['thumb_w'] = $wset['thumb_h'] = 10;

// 추출
//$list = miso_widget_list($wset, $widget_path, $widget_url);
//$list_cnt = count($list);

?>

<?php if($is_slider == 'slider') { // 슬라이더 타입일 경우 ?>

	<div class="full-zone">
		<ul class="bxslider title-basic en">
			<li class="item" style="background-image : url('<?php echo $bg_img;?>');">
				<?php echo $raster;?>
				<div class="at-container">
					&nbsp;
				</div>
			</li>
		</ul>
	</div>

<?php } else { ?>

	<?php echo $raster;?>
	<div class="at-container">
		<div class="img-tbl">
			<div class="img-cell full-zone">
				<ul class="bxslider title-basic en">
					<li class="item">
						&nbsp;
					</li>
				</ul>
			</div>
		</div>
	</div>

<?php } ?>