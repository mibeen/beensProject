<?php
if (!defined('_GNUBOARD_')) exit; //개별 페이지 접근 불가

// 추출하기
$wset['image'] = 1; //이미지글만 추출

$list = apms_board_rows($wset);
$list_cnt = count($list);

$rank = apms_rank_offset($wset['rows'], $wset['page']);

// 날짜
$is_date = (isset($wset['date']) && $wset['date']) ? true : false;
$is_dtype = (isset($wset['dtype']) && $wset['dtype']) ? $wset['dtype'] : 'm.d';
$is_dtxt = (isset($wset['dtxt']) && $wset['dtxt']) ? true : false;

// 새글
$is_new = (isset($wset['new']) && $wset['new']) ? $wset['new'] : 'red'; 

// 분류
$is_cate = (isset($wset['cate']) && $wset['cate']) ? true : false;

// 글내용 - 줄이 1줄보다 크고
/* (TEMP)
$is_cont = ($wset['line'] > 1) ? true : false; 
$is_details = ($is_cont) ? '' : ' no-margin'; 
*/

// 동영상아이콘
$is_vicon = (isset($wset['vicon']) && $wset['vicon']) ? '' : '<i class="fa fa-play-circle-o post-vicon"></i>'; 

// 스타일
$is_center = (isset($wset['center']) && $wset['center']) ? ' text-center' : ''; 
$is_bold = (isset($wset['bold']) && $wset['bold']) ? true : false; 

// 그림자
$shadow_in = '';
$shadow_out = (isset($wset['shadow']) && $wset['shadow']) ? apms_shadow($wset['shadow']) : '';
if($shadow_out && isset($wset['inshadow']) && $wset['inshadow']) {
	$shadow_in = '<div class="in-shadow">'.$shadow_out.'</div>';
	$shadow_out = '';	
}

// owl-hide : 모양유지용 프레임
?>

<style>
	.sec-ion li { font-size: 16px; }
</style>

<div class="col-md-6 col-sm-6 col-20">
	<div class="row row-20">
		<div class="sec-ion">
			<ul>
				<li>
					<a href="<?php echo $wset['url_01']; ?>" target="<?php echo ($wset['target_01'] == '1') ? '_blank' : '_self'; ?>" >
						<i class="<?php echo $wset['ico_01'] ?>"></i>
						<span><?php echo $wset['name_01']; ?></span>
					</a>						
				</li>
				<li>
					<a href="<?php echo $wset['url_02']; ?>" target="<?php echo ($wset['target_02'] == '1') ? '_blank' : '_self'; ?>" >
						<i class="<?php echo $wset['ico_02'] ?>"></i>
						<span><?php echo $wset['name_02']; ?></span>
					</a>
				</li>
				<li>
					<a href="<?php echo $wset['url_03']; ?>" target="<?php echo ($wset['target_03'] == '1') ? '_blank' : '_self'; ?>" >
						<i class="<?php echo $wset['ico_03'] ?>"></i>
						<span><?php echo $wset['name_03']; ?></span>
					</a>
				</li>
				<li>
					<a href="<?php echo $wset['url_04']; ?>" target="<?php echo ($wset['target_04'] == '1') ? '_blank' : '_self'; ?>" >
						<i class="<?php echo $wset['ico_04'] ?>"></i>
						<span><?php echo $wset['name_04']; ?></span>
					</a>
				</li>
			</ul>
			<div class="clearfix"></div>
		</div>
	</div>
</div>

<div class="col-md-6 col-sm-6 col-20">
	<div class="row row-20">
		<div class="sec-ion">
			<ul>
				<li>
					<a href="<?php echo $wset['url_05']; ?>" target="<?php echo ($wset['target_05'] == '1') ? '_blank' : '_self'; ?>" >
						<i class="<?php echo $wset['ico_05'] ?>"></i>
						<span><?php echo $wset['name_05']; ?></span>
					</a>
				</li>
				<li>
					<a href="<?php echo $wset['url_06']; ?>" target="<?php echo ($wset['target_06'] == '1') ? '_blank' : '_self'; ?>" >
						<i class="<?php echo $wset['ico_06'] ?>"></i>
						<span><?php echo $wset['name_06']; ?></span>
					</a>
				</li>
				<li>
					<a href="<?php echo $wset['url_07']; ?>" target="<?php echo ($wset['target_07'] == '1') ? '_blank' : '_self'; ?>" >
						<i class="<?php echo $wset['ico_07'] ?>"></i>
						<span><?php echo $wset['name_07']; ?></span>
					</a>
				</li>
				<li>
					<a href="<?php echo $wset['url_08']; ?>" target="<?php echo ($wset['target_08'] == '1') ? '_blank' : '_self'; ?>" >
						<i class="<?php echo $wset['ico_08'] ?>"></i>
						<span><?php echo $wset['name_08']; ?></span>
					</a>
				</li>
			</ul>
			<div class="clearfix"></div>
		</div>
	</div>
</div>