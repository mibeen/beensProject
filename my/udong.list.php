<?php
	include_once("../common.php");
	include_once(G5_LIB_PATH.'/nx.lib.php');
	include_once(G5_LIB_PATH.'/thumbnail.lib.php'); //썸네일 라이브러리 로딩


	# 로그인 chk
	if ($member['mb_id'] == '') {
		header("/");
		exit();
	}


	$qstr = '';


	# wh
	$wh = "Where lr.lr_ddate is null And lr.mb_id = '" . mres($member['mb_id']) . "'";


	$sql = "Select Count(lr.lr_idx) as cnt"
		."	From local_place_req As lr"
		."		Inner Join local_place As lp On lp.lp_idx = lr.lp_idx"
		."			And lp.lp_ddate is null"
		."		Left Join local_place_area As la On la.la_idx = lp.la_idx"
		."			And la.la_ddate is null"
		."		Left Join {$g5['board_file_table']} As FL On FL.bo_table = 'local_place' And FL.wr_id = lp.lp_idx And FL.bf_no = '0'"
		."	{$wh}"
		;
	$row = sql_fetch($sql);
	$total_count = $row['cnt'];
	$rows = $config['cf_page_rows'];
	$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
	if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
	$from_record = ($page - 1) * $rows; // 시작 열을 구함


	$sql = "Select lr.*"
		."		, lp.lp_idx, lp.lp_name"
		."		, la.la_name"
		."		, FL.bf_file, FL.bf_source"
		."	From local_place_req As lr"
		."		Inner Join local_place As lp On lp.lp_idx = lr.lp_idx"
		."			And lp.lp_ddate is null"
		."		Left Join local_place_area As la On la.la_idx = lp.la_idx"
		."			And la.la_ddate is null"
		."		Left Join {$g5['board_file_table']} As FL On FL.bo_table = 'local_place' And FL.wr_id = lp.lp_idx And FL.bf_no = '0'"
		."	{$wh}"
		."	Order By lr.lr_idx Desc"
		."	Limit {$from_record}, {$rows}"
		;
	$db_lr = sql_query($sql);


	include_once('../bbs/_head.php');


	$is_modal_js = apms_script('modal_pop');
?>
<p class="nx_page_tit">우리동네 학습공간</p>

<ul class="my_coron_lst">
	<?php
	if (sql_num_rows($db_lr) <= 0) {
		echo '<li class="nodata">우리동네 학습공간 예약 정보가 없습니다.</li>';
	}
	else {
		$s = 0;
		while ($rs1 = sql_fetch_array($db_lr))
		{
			$_link = G5_URL.'/udong/req.read.php?lp_idx='.$rs1['lp_idx'].'&lr_idx='.$rs1['lr_idx']
				;
			?>
	<li>
		<a href="<?php echo($_link)?>" data-modal-title="공간이용 신청정보" <?php echo($is_modal_js)?>><?php unset($_link);?>
			<div class="img_wrap1"><?php
				# 썸네일 생성
				$thumb = thumbnail($rs1['bf_file'], G5_PATH."/data/file/local_place", G5_PATH."/data/file/local_place", 112, 63, true);
				
				if (!is_null($thumb)) {
					echo '<img src="/data/file/local_place/'.$thumb.'" alt="'.F_hsc($rs1['bf_source']).'" class="img" />';
				}
				else {
					echo 'no image';
				}
				unset($thumb);
			?></div>
			<div class="cont_wrap">
				<div class="tit_wrap">
					<span class="region"><?php echo F_hsc($rs1['la_name'])?></span>
					<span class="tit"><?php echo F_hsc($rs1['lp_name'])?></span>
				</div>
				<div class="detail_wrap">
					<span class="term"><span class="dsIB">시간:</span> <span class="dsIB"><?php
						echo date('Y-m-d H:i', strtotime($rs1['lr_sdate']));
						echo ' ~ ';
						echo date('H:i', strtotime($rs1['lr_edate']));
					?></span></span>
				</div>
				<?php
				switch ($rs1['lr_status']) {
					case 'A':
						echo '<span class="state apply">신청</span>';
						break;
					case 'B':
						echo '<span class="state approve">승인</span>';
						break;
					case 'C':
						echo '<span class="state hold">미승인</span>';
						break;
					case 'D':
						echo '<span class="state cancel">승인취소</span>';
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
		<ul class="pagination pagination-sm en">
			<?php echo apms_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, './udong.list.php?'.$qstr.'&amp;page=');?>
		</ul>
	</div>
</div>

<?php 
	# 메뉴 표시에 사용할 변수 
	$_gr_id = 'nx05';  // 게시판 그룹 ID - 현재 메뉴표시에 사용
	$pid = ($pid) ? $pid : 'myudong';   // Page ID

	// include "../page/inc.page.menu.php";	 // 마이페이지에선 사용안함

	include_once('../bbs/_tail.php');
?>