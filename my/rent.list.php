<?php
	include_once("../common.php");
	include_once(G5_LIB_PATH.'/nx.lib.php');
	include_once(G5_LIB_PATH.'/apms.thema.lib.php');
	include_once(G5_LIB_PATH.'/thumbnail.lib.php'); //썸네일 라이브러리 로딩


	# 로그인 chk
	if ($member['mb_id'] == '') {
		header("/");
		exit();
	}


	$qstr = '';


	# wh
	$wh = "Where PR.PR_DDATE is null And PR.mb_no = '" . mres($member['mb_no']) . "'";

	if ($stx != '') {
		$wh .= " And EM.EM_TITLE like '%" . mres($stx) . "%'";
		$qstr .= '&amp;stx='.urlencode($stx);
	}


	$sql = "Select Count(PR.PR_IDX) as cnt"
		."	From PLACE_RENTAL_REQ As PR"
		."		Inner Join PLACE_RENTAL_SUB As PS On PS.PS_IDX = PR.PS_IDX"
		."			And PS.PS_DDATE is null"
		."		Inner Join PLACE_RENTAL_MASTER As PM On PM.PM_IDX = PS.PM_IDX"
		."			And PM.PM_DDATE is null"
		."		Left Join PLACE_RENTAL_AREA As PA On PA.PA_IDX = PM.PA_IDX"
		."			And PA.PA_DDATE is null"
		."		Left Join {$g5['board_file_table']} As FL On FL.bo_table = 'place_rental_sub' And FL.wr_id = PS.PS_IDX And FL.bf_no = '0'"
		."	{$wh}"
		;
	$row = sql_fetch($sql);
	$total_count = $row['cnt'];
	$rows = $config['cf_page_rows'];
	$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
	if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
	$from_record = ($page - 1) * $rows; // 시작 열을 구함


	$sql = "Select PR.*"
		."		, PS.PS_GUBUN, PS.PS_NAME"
		."		, PM.PM_IDX, PM.PM_NAME"
		."		, PA.PA_NAME"
		."		, FL.bf_file, bf_source"
		."	From PLACE_RENTAL_REQ As PR"
		."		Inner Join PLACE_RENTAL_SUB As PS On PS.PS_IDX = PR.PS_IDX"
		."			And PS.PS_DDATE is null"
		."		Inner Join PLACE_RENTAL_MASTER As PM On PM.PM_IDX = PS.PM_IDX"
		."			And PM.PM_DDATE is null"
		."		Left Join PLACE_RENTAL_AREA As PA On PA.PA_IDX = PM.PA_IDX"
		."			And PA.PA_DDATE is null"
		."		Left Join {$g5['board_file_table']} As FL On FL.bo_table = 'place_rental_sub' And FL.wr_id = PS.PS_IDX And FL.bf_no = '0'"
		."	{$wh}"
		."	Order By PR.PR_IDX Desc"
		."	Limit {$from_record}, {$rows}"
		;
	$db_pr = sql_query($sql);


	include_once('../bbs/_head.php');


	$is_modal_js = apms_script('modal_pop');
?>
<p class="nx_page_tit">대관</p>

<ul class="my_coron_lst">
	<?php
	if (sql_num_rows($db_pr) <= 0) {
		echo '<li class="nodata">대관 예약 정보가 없습니다.</li>';
	}
	else {
		$s = 0;
		while ($rs1 = sql_fetch_array($db_pr))
		{
			$_link = '../bbs/place_rental_req_view.php?pm_id='.$rs1['PM_IDX'].'&ps_id='.$rs1['PS_IDX'].'&pr_id='.$rs1['PR_IDX']
				// .'&year='.date('Y', strtotime($rs1['PR_SDATE']))
				// .'&month='.date('m', strtotime($rs1['PR_SDATE']))
				;
			?>
	<li>
		<a href="<?php echo($_link)?>" data-modal-title="<?php if ($rs1['PS_GUBUN'] == 'A') { echo '강의실'; } else { echo '숙소'; } ?> 신청정보" <?php echo($is_modal_js)?>><?php unset($_link);?>
			<div class="img_wrap1"><?php
				# 썸네일 생성
				$thumb = thumbnail($rs1['bf_file'], G5_PATH."/data/file/place_rental_sub", G5_PATH."/data/file/place_rental_sub", 112, 63, true);
				
				if (!is_null($thumb)) {
					echo '<img src="/data/file/place_rental_sub/'.$thumb.'" alt="'.F_hsc($rs1['bf_source']).'" class="img" />';
				}
				else {
					echo 'no image';
				}
				unset($thumb);
			?></div>
			<div class="cont_wrap">
				<div class="tit_wrap">
					<span class="region"><?php echo F_hsc($rs1['PA_NAME'])?></span>
					<span class="tit"><?php echo F_hsc($rs1['PM_NAME'])?></span>
				</div>
				<div class="detail_wrap">
					<span class="type"><?php if ($rs1['PS_GUBUN'] == 'A') { echo '강의실'; } else { echo '숙소'; } ?></span>
					<span class="room"><?php echo F_hsc($rs1['PS_NAME'])?></span>
					<span class="term"><span class="dsIB">기간:</span> <span class="dsIB"><?php
						# 강의실
						if ($rs1['PS_GUBUN'] == 'A') {
							echo date('Y-m-d', strtotime($rs1['PR_SDATE']));
							echo ' '.date('H', strtotime($rs1['PR_SDATE']));
							echo '시 ~ ';
							echo date('H', strtotime($rs1['PR_EDATE']));
							echo '시';
						}
						# 숙소
						else if ($rs1['PS_GUBUN'] == 'B') {
							echo date('Y-m-d', strtotime($rs1['PR_SDATE']));
							echo ' ~ ';
							echo date('Y-m-d', strtotime($rs1['PR_EDATE']));
						}
					?></span></span>
				</div>
				<?php
				switch ($rs1['PR_STATUS']) {
					case 'A':
						echo '<span class="state apply">신청</span>';
						break;
					case 'B':
						echo '<span class="state approve">승인</span>';
						break;
					case 'C':
						echo '<span class="state hold">보류</span>';
						break;
					default:
						break;
				}
				?>
			</div>
		</a>
	</li>
			<?php
			$s++;
		}
	}
	?>
</ul>

<div class="pagenate_wrap">
	<div class="list-page text-center">
		<?php
		$prev_part_href = '';
		$next_part_href = '';
		if ($ord || $stx) {
			$patterns = array('#&amp;page=[0-9]*#', '#&amp;spt=[0-9\-]*#');

			$prev_spt = $spt - $config['cf_search_part'];
			if (isset($min_spt) && $prev_spt >= $min_spt) {
				$qstr1 = preg_replace($patterns, '', $qstr);
				$prev_part_href = './evt.list.php?'.$qstr1.'&amp;page=1';
			}

			$next_spt = $spt + $config['cf_search_part'];
			if ($next_spt < 0) {
				$qstr1 = preg_replace($patterns, '', $qstr);
				$next_part_href = './evt.list.php?'.$qstr1.'&amp;page=1';
			}
		}
		?>
		<ul class="pagination pagination-sm en">
			<?php if($prev_part_href) { ?>
				<li><a href="<?php echo $prev_part_href;?>">이전검색</a></li>
			<?php } ?>
			<?php echo apms_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, './evt.list.php?'.$qstr.'&amp;page=');?>
			<?php if($next_part_href) { ?>
				<li><a href="<?php echo $next_part_href;?>">다음검색</a></li>
			<?php } ?>
		</ul>
	</div>
</div>

<?php 
	# 메뉴 표시에 사용할 변수 
	$_gr_id = 'nx05';  // 게시판 그룹 ID - 현재 메뉴표시에 사용
	$pid = ($pid) ? $pid : 'myrent';   // Page ID

	// include "../page/inc.page.menu.php";	 // 마이페이지에선 사용안함

	include_once('../bbs/_tail.php');
?>