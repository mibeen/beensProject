<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

//날짜선택기
apms_script('datepicker');

$tcolor = (isset($boset['tcolor']) && $boset['tcolor']) ? $boset['tcolor'] : 'orangered';

?>

<div class="list-today">
	<div class="media">
		<div class="date-box pull-left hidden-xs" style="margin-right:15px;">
			<div class="bg-<?php echo $tcolor;?> text-center" style="padding:12px;">
				<i class="fa fa-calendar-check-o fa-3x"></i>
			</div>
			<div class="date-icon">
				<?php echo date("Y.m.d", G5_SERVER_TIME);?>
			</div>
		</div>
		<div class="media-body">
			<ol class="today-do">
			<?php
				// Today
				$i = 0;
				$chk_today = $b_year.sprintf("%02d",$b_mon).sprintf("%02d",$b_day);
				$result = sql_query("select * from $write_table where wr_is_comment = '0' and left(wr_1,8) <= '{$chk_today}' and left(wr_2,8) >= '{$chk_today}' order by wr_id asc");
				while ($row1 = sql_fetch_array($result)) {
					$row = get_list($row1, $board, $board_skin_url, G5_IS_MOBILE ? $board['bo_mobile_subject_len'] : $board['bo_subject_len']);
					$row['href'] = G5_BBS_URL.'/board.php?bo_table='.$bo_table.'&amp;wr_id='.$row['wr_id'].$qstr;

					// 링크이동
					$row['target'] = '';
					if($is_link_target && $row['wr_link1']) {
						$row['target'] = $is_link_target;
						$row['href'] = $row['link_href'][1];
					}
			?>
				<li>
					<a href="<?php echo $row['href'];?>"<?php echo $row['target'];?><?php echo $is_modal_js;?>>
						<span<?php echo ($row['wr_3']) ? ' class="'.$row['wr_3'].'"' : '';?>>
							<?php echo apms_fa($row['as_icon']);?>
							<?php echo $row['subject'] ;?>
						</span>
						<?php if($row['wr_comment']) { ?>
							<span class="count orangered"><?php echo $row['wr_comment'];?></span>
						<?php } ?>
					</a>
				</li>
			<?php $i++; } ?>
			</ol>

			<?php if(!$i) { ?>
				<p><?php echo $b_year;?>년 <?php echo sprintf("%02d",$b_mon);?>월 <?php echo sprintf("%02d",$b_day);?>일 오늘 일정은 없습니다.</p>
			<?php } ?>

			<?php if($notice_count > 0) { //공지사항 
				// 링크이동
				$list[$i]['target'] = '';
				if($is_link_target && !$list[$i]['is_notice'] && $list[$i]['wr_link1']) {
					$list[$i]['target'] = $is_link_target;
					$list[$i]['href'] = $list[$i]['link_href'][1];
				}
			?>
				<ul class="list-notice">
					<?php for ($i=0; $i < $list_cnt; $i++) { if(!$list[$i]['is_notice']) break; //공지가 아니면 끝냄 ?>
						 <li>
							 <a href="<?php echo $list[$i]['href'];?>"<?php echo $list[$i]['target'];?><?php echo $is_modal_js;?>>
								<span class="pull-right text-muted hidden-xs">
									&nbsp;
									<i class="fa fa-clock-o lightgray"></i>
									<?php echo date("Y.m.d", $list[$i]['date']);?>
								</span>
								[알림]
								<?php echo get_text($list[$i]['wr_subject']);?>
								<?php if($list[$i]['wr_comment']) { ?>
									<span class="count orangered"><?php echo $list[$i]['wr_comment'];?></span>
								<?php } ?>
							</a>
						</li>
					<?php } ?>
				</ul>
			<?php } ?>
		</div>
	</div>
</div>

<div class="clearfix"></div>

<div class="pull-left hidden-xs">
	<h3 class="no-margin">
		<a href="<?php echo G5_BBS_URL;?>/board.php?bo_table=<?php echo $bo_table;?>" title="이번달">
			<i class="fa fa-calendar-check-o"></i> <?php echo $year;?>.<?php echo $month;?>
		</a>
	</h3>
</div>
<div class="pull-right">
	<form class="form-inline text-center no-margin">
		<span class="input-group input-group-sm date" id="schedule_datepicker">
			<span class="input-group-addon">
				<span class="fa fa-calendar-check-o fa-lg"></span>
			</span>
			<input type="text" class="form-control input-sm" id="schedule_datepicker2">
			<span class="input-group-btn">
				<a role="button" class="btn btn-gray btn-sm" href="<?php echo G5_BBS_URL;?>/board.php?bo_table=<?php echo $bo_table;?>&amp;year=<?php echo $year_prev;?>&amp;month=<?php echo $month_prev;?><?php echo $sca_qstr;?>">
					<i class="fa fa-angle-left fa-lg"></i>
				</a>
				<a role="button" class="btn btn-gray btn-sm" href="<?php echo G5_BBS_URL;?>/board.php?bo_table=<?php echo $bo_table;?>&amp;year=<?php echo $year_next;?>&amp;month=<?php echo $month_next;?><?php echo $sca_qstr;?>">
					<i class="fa fa-angle-right fa-lg"></i>
				</a>
			</span>
		</span>
	</form>
	<div class="h15"></div>
	<script type="text/javascript">
		function schedule_month() {
			var url;
			var selDate = $("#schedule_datepicker2").val();
			var strDate = selDate.split('-');

			if(strDate[1].substr(0,1) == "0") {
				strDate[1] = strDate[1].substr(1,1);
			}

			url = g5_bbs_url + '/board.php?bo_table=' + g5_bo_table + '&year=' + strDate[0] + '&month=' + strDate[1];

			if(g5_sca) 
				url += '&amp;sca=' + encodeURIComponent(g5_sca);

			document.location.href = url;
		}

		$(function () {
			$('#schedule_datepicker').datetimepicker({
				viewMode: 'months',
				dayViewHeaderFormat: "YYYY년 MMMM",
				defaultDate: "<?php echo $year;?>-<?php echo sprintf("%02d",$month);?>",
				format: 'YYYY-MM',
				locale: 'ko'
			});

			$('#schedule_datepicker').on("dp.change",function (e) {
				schedule_month();
			});

		});
	</script>
</div>
<div class="clearfix"></div>

<?php
// 카테고리
if($is_category) 
	include_once($board_skin_path.'/category.skin.php'); // 카테고리

unset($list);
?>
