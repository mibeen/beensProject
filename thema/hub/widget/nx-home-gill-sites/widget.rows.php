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

// 스타일
$is_center = (isset($wset['center']) && $wset['center']) ? ' text-center' : ''; 
$is_bold = (isset($wset['bold']) && $wset['bold']) ? true : false; 

// owl-hide : 모양유지용 프레임
if($list_cnt) {
?>
	<div class="owl-carousel">
	<?php 
	for ($i=0; $i < $list_cnt; $i++) { 

		//라벨 체크
		$wr_label = '';
		$is_lock = false;
		if ($list[$i]['secret'] || $list[$i]['is_lock']) {
			$is_lock = true;
			$wr_label = '<div class="label-cap bg-orange">Lock</div>';	
		} else if ($wset['rank']) {
			$wr_label = '<div class="label-cap bg-'.$wset['rank'].'">Top'.$rank.'</div>';
			$rank++;
		} else if ($list[$i]['new']) {
			$wr_label = '<div class="label-cap bg-'.$is_new.'">New</div>';	
		}

		// 링크이동
		$target = '';
		if($is_link_target && $list[$i]['wr_link1']) {
			$target = $is_link_target;
			$list[$i]['href'] = $list[$i]['link_href'][1];
		}

		//볼드체
		if($is_bold) {
			$list[$i]['subject'] = '<b>'.$list[$i]['subject'].'</b>';
		}

		// Lazy
		$img_src = ($is_lazy) ? 'data-src="'.$list[$i]['img']['src'].'" class="lazyOwl"' : 'src="'.$list[$i]['img']['src'].'"';
	?>
		<div class="item" style="width:240px;">
			<div class="post-image">
				<a href="<?php echo(($list[$i]['wr_link1']) ? $list[$i]['wr_link1'] : 'javascript:void(0);')?>" target="_blank">
					<div class="text-wrap1">
						<div class="text-wrap2">
							<div class="tit"><?php echo $list[$i]['subject'];?></div>
							<div class="ct"><?php echo apms_cut_text($list[$i]['content'], 80);?></div>
						</div>
					</div>
					<div class="img-wrap">
						<div class="img-item">
							<img <?php echo $img_src;?> alt="<?php echo $list[$i]['img']['alt'];?>">
						</div>
					</div>
				</a>
			</div>
		</div>
	<?php } ?>
	</div>
<?php } else { ?>
	<div class="post-none">
		등록된 글이 없습니다.
	</div>
<?php } ?>