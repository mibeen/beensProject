<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

// 선택옵션으로 인해 셀합치기가 가변적으로 변함
$colspan = 6;

if ($is_checkbox) $colspan++;

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$qa_skin_url.'/style.css">', 0);

//날짜선택기
apms_script('datepicker');

$is_modal_detail = apms_script('modal');
$is_modal_js = apms_script('modal_pop');
?>
<div class="data_ct">
	<p class="nx_page_tit">대관 정보</p>
	<div class="coron_reserve_wrap mt40">
		<div class="info">
			<p class="region"><?php echo $view['PA_NAME'] . " " . $view['PM_NAME']; ?></p>
			<p class="tit"><?php echo $view['PS_NAME']; ?></p>
			<div class="img_wrap">
				<?php
				$_bo_table = 'place_rental_sub';

				# 썸네일 생성
				$thumb = thumbnail($view['bf_file'], G5_PATH."/data/file/{$_bo_table}", G5_PATH."/data/file/{$_bo_table}", 480, 270, true);

				if (!is_null($thumb)) {
					echo '<img src="/data/file/'.$_bo_table.'/'.$thumb.'" alt="'.htmlspecialchars($view['bf_source']).'" class="img" />';
				}
				else {
					echo '<div class="empty_img"></div>';
				}
				unset($thumb, $_bo_table);
				?>
				<a href="<?php echo $detail_href?>" class="btn_detail" data-modal-title="<?php echo $view['PA_GUBUN_TITLE']?>소개" <?php echo($is_modal_detail)?>><?php echo $view['PA_GUBUN_TITLE']?>소개 &gt;</a>
			</div>
		</div>

		<div class="lst_wrap">
			<div>
				<div class="go_list">
					<!-- <a href="<?php echo $list_href?>" class="nx_btn5">뒤로</a> -->
					<a role="button" href="<?php echo $list_href?>" class="btn btn-black btn-sm">
						<i class="fa fa-chevron-left "></i><span class="hidden-xs"> 뒤로</span>
					</a>
				</div>
				<div id="date-area" class="date">
					<span class="vaM"><?php echo $year?>년 <?php echo $month?>월</span> 
					<a href="./place_rental_req_list.php?pm_id=<?php echo $pm_id?>&amp;ps_id=<?php echo $ps_id?>&amp;year=<?php echo $prev_year?>&amp;month=<?php echo $prev_month?>"><</a>
					<span id="req_datepicker" class="dsIB psR input-group">
						<span class="input-group-addon">
							<img src="../thema/hub/assets/imgs/ico/ico_calendar.png" alt="" class="vaM" />
						</span>
						<input type="hidden" id="req_datepicker2"">
					</span>
					<a href="./place_rental_req_list.php?pm_id=<?php echo $pm_id?>&amp;ps_id=<?php echo $ps_id?>&amp;year=<?php echo $next_year?>&amp;month=<?php echo $next_month?>">></a>
				</div>
			</div>
			<table cellspacing="0" cellpadding="0" border="0" class="lst">
				<colgroup>
					<col width="70px" /><col width="" /><col width="100px" />
				</colgroup>
				<?php
				$next_show_yn_hide = 0;
				$rowspan = 0;
				$today = date('Y-m-d');
				$tomorrow = date('Y-m-d',strtotime($today . "+1 days"));
				$now_hour = date('G');
				$req_timetable = array();
				$j = count($day_list);
				$week_array = array('', '월', '화', '수', '목', '금', '토', '일');

				for ($i = 0; $i < $j; $i++) {
					$idx = ($i + 1);
					$day = $idx;
					if(strlen($day) == 1) { $day = "0".$idx; }

					$holiday_yn = 0;
					$week = date('Y-m-d', strtotime($year . "-" . $month . "-" . $idx));
					$week = date('N', strtotime($week));
					if($week == 7) {
						$holiday_yn = 1;
					}
					$week = $week_array[$week];
					if($week != '') {
						$day = $day . " (" . $week . ")";
					}

					$result_req_row = $day_list[$i];

					$c_date = date('Y-m-d', strtotime($year . '-' . $month . '-' . $idx));

					$show_link_yn = true;

					# 오늘 포함 이전날짜 신청 불가, 18시 이후에는 익일 신청 불가
					echo('<script>console.log("'.$now_hour.'")</script>');
					if($today >= $c_date || ($tomorrow == $c_date && $now_hour >= 18)) {
						$show_link_yn = false;
					}

					if($view['PS_GUBUN'] == 'B' && $last_req_edate != '' && $idx <= $last_req_edate - 1) {
						$show_link_yn = false;
					}

					if($next_show_yn_hide > 1) {
						// $next_show_yn_hide--; // 아래 td 보여주는 부분에서 -1
						$show_link_yn = false;
					}

					if($view['PS_GUBUN'] == 'B') {
						$req_timetable[$idx] = 1;
					}
					?>
				<tr>
					<td align="center" <?php if($holiday_yn == 1) { echo('class="holiday"'); } ?>><?php echo $day; ?></td>
					<?php
					// $next_show_yn_hide가 남아있으면 위에서 rowspan했기 때문에 td를 추가할 필요 없음
					if ($next_show_yn_hide > 1) {
						$next_show_yn_hide--;
					}
					else {
						echo '<td';
						for ($ii=0; $row=sql_fetch_array($result_req_row); $ii++)
						{
							if($ii == 0) { 
								if($view['PS_GUBUN'] == 'B') {
									$req_sdate = date('Y-m-d', strtotime($row['PR_SDATE']));
									$req_edate = date('Y-m-d', strtotime($row['PR_EDATE']));
									$req_edate_show = $req_edate;
									
									$date1 = new DateTime($req_sdate);
									$date2 = new DateTime($req_edate);
									$req_edate = $date1->diff($date2)->days;
									if ($req_edate > 1) $rowspan = $req_edate;
								}
								if ($rowspan > 1) echo ' rowspan="'.$rowspan.'">';
								else echo '>'; // td를 닫음
								echo '<ul>';
							}

							$req_PR_IDX = $row['PR_IDX'];
							$req_status = $row['PR_STATUS'];
							$req_p_cnt = $row['PR_P_CNT'];
							$req_tel = $row['PR_TEL'];
							$req_info = $row['PR_CONT'];
							$req_mb_no = $row['mb_no'];

							if($view['PS_GUBUN'] == 'A') {
								$req_sdate = date('H:i', strtotime($row['PR_SDATE']));
								$req_edate = date('H:i', strtotime($row['PR_EDATE']));
								$req_edate_show = $req_edate;

								$timetable_sdate = date('G', strtotime($row['PR_SDATE']));
								$timetable_edate = date('G', strtotime($row['PR_EDATE']));

								if($req_status == 'A' || $req_status == 'B') {
									$req_timetable[$idx][] = array('sdate' => $timetable_sdate, 'edate' => $timetable_edate);
								}
							}
							else if($view['PS_GUBUN'] == 'B') {
								/* (TEMP) 셀 병합 이슈로 인해 위에서 설정함
								$req_sdate = date('Y-m-d', strtotime($row['PR_SDATE']));
								$req_edate = date('Y-m-d', strtotime($row['PR_EDATE']));
								$req_edate_show = $req_edate;
								
								$date1 = new DateTime($req_sdate);
								$date2 = new DateTime($req_edate);
								$req_edate = $date1->diff($date2)->days;
								*/

								if($req_edate > 1) {
									$next_show_yn_hide = $req_edate;
								}

								if($req_status == 'A' || $req_status == 'B') {
									$req_timetable[$idx] = 0;
									$show_link_yn = false;
								}
								else {
									$req_timetable[$idx] = 1;
								}
							}

							# 관리자가 아니면 자신의 이름만 보임
							$t_mb_nick = 'OOO';
							if (!$member['admin']) {
								if ($row['mb_no'] == $member['mb_no']) {
									$t_mb_nick = $member['mb_nick'];
								}
							}
							else {
								$t_member = sql_fetch(" select mb_nick, mb_no from {$g5['member_table']} where mb_no = TRIM('$req_mb_no') ");
								$t_mb_nick = $t_member['mb_nick'];
							}
							
							$req_title = ($ii+1) . '. ' . $t_mb_nick . ' (' . $req_sdate . '~' . $req_edate_show . ')';

							$req_status_title = '';
							if($req_status == 'A') { 
								$req_status_title = '신청'; 
								$req_status_str = 'apply';
							}
							else if($req_status == 'B') { 
								$req_status_title = '승인';
								$req_status_str = 'approve';
							}
							else if($req_status == 'C') {
								$req_status_title = '보류';
								$req_status_str = 'hold';
							}

							$is_mine = false;
							$link_href_tag = '<span class="data ' . $req_status_str . ' req_list_link req_list_type_'.$req_status.'"><span class="s1">'.$req_title.'</span><span class="s2">'.$req_status_title.'</span></span>';
							
							# 자신의 글만 상세/수정 가능
							if ($row['mb_no'] == $member['mb_no'])
							{
								$is_mine = true;
								

								$_link = './place_rental_req_view.php?pm_id='.$pm_id.'&ps_id='.$ps_id.'&pr_id='.$req_PR_IDX;


								$link_href_tag = '<a class="data ' . $req_status_str. '" href="'.$_link.'" class="req_list_link req_list_type_'.$req_status.'" data-modal-title="강의실 신청정보" '.$is_modal_js.'><span class="s1">'.$req_title.'</span><span class="s2">'.$req_status_title.'</span></a>';
							}

							echo '<li>'.$link_href_tag.'</li>';
						}
						
						if($ii == 0) {
						  echo '>'; // td를 닫음
							echo '<span>신청현황이 없습니다.</span>';
						}
						else {
							echo '</ul>';
						}
						echo '</td>';
						?>
						<?php
						# 이전 td가 rowspan 했다면 rowspan 처리
						if ($rowspan > 1) {
							echo '<td rowspan="'.$rowspan.'">';
							$rowspan = 0;
						}
						else {
							echo '<td>';
						}
						if($show_link_yn) {
							echo '<a href="./place_rental_req_write.php?pm_id='.$pm_id.'&ps_id='.$ps_id.'&year='.$year.'&month='.$month.'&day='.$idx.'" class="btn_reserve" data-modal-title="신청하기" '.$is_modal_js.'>신청하기</a>';
						}
						echo '</td>';
					}
					?>
				</tr>
					<?php
				}
				?>
			</table>
		</div>
	</div>
</div>

<script>
function schedule_change() {
	var url;
	var selDate = $("#req_datepicker2").val();
	var strDate = selDate.split('-');

	if(strDate[1].substr(0,1) == "0") {
		strDate[1] = strDate[1].substr(1,1);
	}

	url = '?pm_id=<?php echo($pm_id)?>&ps_id=<?php echo($ps_id)?>&year=' + strDate[0] + '&month=' + strDate[1];

	document.location.href = url;
}

$(function () {
	$('#req_datepicker').datetimepicker({
		viewMode: 'months',
		dayViewHeaderFormat: "YYYY년 MMMM",
		defaultDate: "<?php echo $year;?>-<?php echo sprintf("%02d",$month);?>",
		format: 'YYYY-MM',
		locale: 'ko',
		widgetParent: $('#date-area'),
		widgetPositioning: {
			horizontal: 'left',
			vertical: 'auto'
		}
	});

	$('#req_datepicker').on("dp.change",function (e) {
		schedule_change();
	});
});
</script>