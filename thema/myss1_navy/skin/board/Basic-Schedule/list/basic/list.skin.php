<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$list_skin_url.'/list.css" media="screen">', 0);

// 헤드스킨
$head_class = '';
if(isset($boset['hskin']) && $boset['hskin']) {
	add_stylesheet('<link rel="stylesheet" href="'.G5_CSS_URL.'/head/'.$boset['hskin'].'.css" media="screen">', 0);
} else {
	$head_class = (isset($boset['hcolor']) && $boset['hcolor']) ? ' border-'.$boset['hcolor'] : ' border-black';
}

// 요일
$yoil = array("토", "일", "월", "화", "수", "목", "금");

?>
<div class="list-board list-calendar">
	<div class="list-head div-head<?php echo $head_class;?>">
		<span class="red">일요일</span>
		<span>월요일</span>
		<span>화요일</span>
		<span>수요일</span>
		<span>목요일</span>
		<span>금요일</span>
		<span class="blue">토요일</span>
	</div>
	<ul class="list-body">
	<?php
		$cday = 1;
		$sel_mon = sprintf("%02d",$month);
		$now_month = $year.$sel_mon;
		$sca_sql = ($sca) ? "and ca_name = '".$sca."'" : "";
		$result = sql_query("select * from $write_table where wr_is_comment = '0' and left(wr_1,6) <= '{$now_month}' and left(wr_2,6) >= '{$now_month}' $sca_sql order by wr_id asc");
		while ($row = sql_fetch_array($result)) {

			$start_day = (substr($row['wr_1'],0,6) <  $now_month) ? 1 : substr($row['wr_1'],6,2);
			$start_day= (int)$start_day;

			$end_day = (substr($row['wr_2'],0,6) >  $now_month) ? $lastday[$month] : substr($row['wr_2'],6,2);
			$end_day= (int)$end_day;

			$row2 = get_list($row, $board, $board_skin_url, G5_IS_MOBILE ? $board['bo_mobile_subject_len'] : $board['bo_subject_len']);

			for ($i = $start_day; $i <= $end_day; $i++) {
				$list[$i][] = $row2;
			}
		}

		$temp = 7 - (($lastday[$month]+$dayoftheweek)%7);

		if($temp == 7) $temp = 0;
			
		$lastcount = $lastday[$month]+$dayoftheweek + $temp;

		for ($iz = 1; $iz <= $lastcount; $iz++) {

			$is_today = ($b_year == $year && $b_mon == $month && $b_day == $cday) ? true : false;

			#$daytext = ($is_today) ? '<span class="font-14 '.$tcolor.' en"><i class="fa fa-calendar-check-o fa-lg"></i> Today</span>' : $cday;
			$daytext = ($is_today) ? '<span class="font-14 '.$tcolor.' en">'.date('j').'</span>' : $cday;

			$daycolor = '';
			$dayweek = $iz%7; 
			if($dayweek == 1) {
				echo '<li class="list-item">'.PHP_EOL;
				$daycolor = ' red';
			} else if($dayweek == 0) {
				$daycolor = ' blue';
			} 

			$do_cnt = count($list[$cday]);

			if($dayoftheweek < $iz && $iz <= $lastday[$month]+$dayoftheweek) {
				$fr_date = $year.sprintf("%02d",$month).sprintf("%02d",$cday);
			?>
				<div class="media<?php echo ($is_today) ? ' bg-today' : '';?> no-margin">
					<a class="media-date"<?php echo ($write_href) ? ' href="'.$write_href.'&amp;fr_date='.$fr_date.'&amp;to_date='.$fr_date.$sca_qstr.'"' : '';?>>
						<span class="font-14 en<?php echo $daycolor;?>">
							<span class="hidden-xs"><?php echo $daytext;?></span>
							<span class="visible-xs"><?php echo $month;?>.<?php echo sprintf("%02d",$cday);?>(<?php echo $yoil[$dayweek];?>)</span>
						</span>
					</a>
					<?php if($do_cnt > 0) { ?>
						<div class="media-body">
							<ul class="do-list">
							<?php for($i = 0; $i < $do_cnt; $i++) { 
								// 링크이동
								$list[$cday][$i]['target'] = '';
								if($is_link_target && $list[$cday][$i]['wr_link1']) {
									$list[$cday][$i]['target'] = $is_link_target;
									$list[$cday][$i]['href'] = $list[$cday][$i]['link_href'][1];
								}
							?>
								<li>
									<a href="<?php echo $list[$cday][$i]['href'];?>"<?php echo $list[$cday][$i]['target'];?><?php echo $is_modal_js;?>>
										<span<?php echo ($list[$cday][$i]['wr_3']) ? ' class="'.$list[$cday][$i]['wr_3'].'"' : '';?>>
											<?php echo apms_fa($list[$cday][$i]['as_icon']);?>
											<?php echo $list[$cday][$i]['subject'] ;?>
										</span>
										<?php if($list[$cday][$i]['wr_comment']) { ?>
											<span class="count orangered"><?php echo $list[$cday][$i]['wr_comment'];?></span>
										<?php } ?>
									</a>
								</li>
							<?php } ?>
							</ul>
						</div>
					<?php } ?>
				</div>
			<?php
				$cday++;
			} else { 
				echo '<div class="hidden-xs"></div>'.PHP_EOL; 
			}

			if($iz%7 == 0) echo '</li>'.PHP_EOL;
		}
	?>	
	</ul>
</div>