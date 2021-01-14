<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가 

// 위젯 대표아이디 설정
$wid = 'CSB';

// 게시판 제목 폰트 설정
$font = 'font-16 en';

// 게시판 제목 하단라인컬러 설정 - red, blue, green, orangered, black, orange, yellow, navy, violet, deepblue, crimson..
$line = 'navy';

?>
<style>
	/*.widget-side .div-title-underbar { margin-bottom:15px; }*/
	.widget-side .div-title-underbar span { padding-bottom:4px; }
	.widget-side .div-title-underbar span b { font-weight:500; }
	/*.widget-box { margin-bottom:25px; }*/
</style>

<div class="widget-side">

	<?php 
	// 카테고리 체크
	$side_category = apms_widget('basic-category');
	if($side_category) { 
	?>
	<div class="widget-box">
		<?php echo $side_category;?>
	</div>
	<?php } ?>

	<div class="widget-box">
		<?php #echo apms_widget('nx-post-image-banner', $wid.'-wm101'); ?>
	</div>
</div>