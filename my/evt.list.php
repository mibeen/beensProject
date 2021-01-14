<?php
	include_once("../common.php");
	include_once(G5_LIB_PATH.'/nx.lib.php');
	include_once(G5_LIB_PATH.'/apms.thema.lib.php');
	include_once(G5_LIB_PATH.'/thumbnail.lib.php'); //썸네일 라이브러리 로딩


	$g5['title'] = $fm['fm_subject'];


	# 로그인 chk
	if ($member['mb_id'] == '') {
		header("/");
		exit();
	}


	include_once('../bbs/_head.php');


	# set : variables
	$stx = $_GET['stx'];


	$qstr = '';


	# wh
	$wh = "Where EJ.EJ_DDATE is null And EJ.mb_id = '" . mres($member['mb_id']) . "'";

	if ($stx != '') {
		$wh .= " And EM.EM_TITLE like '%" . mres($stx) . "%'";
		$qstr .= '&amp;stx='.urlencode($stx);
	}


	$sql = "Select Count(EJ.EJ_IDX) As cnt"
		."	From NX_EVENT_JOIN As EJ"
		."		Inner Join NX_EVENT_MASTER As EM On EM.EM_IDX = EJ.EM_IDX"
		."			And EM.EM_DDATE is null"
		."		Left Join NX_EVT_SATI_MA As ESM On ESM.EM_IDX = EJ.EM_IDX"
		."		Left Join {$g5['board_file_table']} As FL On FL.bo_table = 'NX_EVENT_MASTER' And FL.wr_id = EM.EM_IDX And FL.bf_no = '0'"
		."	{$wh}"
		;
	$row = sql_fetch($sql);
	$total_count = $row['cnt'];
	$rows = $config['cf_page_rows'];
	$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
	if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
	$from_record = ($page - 1) * $rows; // 시작 열을 구함


	$sql = "Select EJ.*"
		."		, EM.EM_TITLE, EM.EM_S_DATE, EM.EM_E_DATE, EM.EM_S_TIME, EM.EM_E_TIME, EM.EM_JOIN_MAX"
		."		, EM.EM_JOIN_S_DATE, EM.EM_JOIN_E_DATE"
		."		, FL.bf_file, bf_source"
		."		, ESM.ESM_S_DATE, ESM.ESM_E_DATE"
		."	From NX_EVENT_JOIN As EJ"
		."		Inner Join NX_EVENT_MASTER As EM On EM.EM_IDX = EJ.EM_IDX"
		."			And EM.EM_DDATE is null"
		."		Left Join NX_EVT_SATI_MA As ESM On ESM.EM_IDX = EJ.EM_IDX"
		."		Left Join {$g5['board_file_table']} As FL On FL.bo_table = 'NX_EVENT_MASTER' And FL.wr_id = EM.EM_IDX And FL.bf_no = '0'"
		."	{$wh}"
		."	Order By EJ.EJ_IDX Desc"
		."	Limit {$from_record}, {$rows}"
		;
	$row = sql_query($sql);


	$is_modal_js = apms_script('modal_pop');
?>

<p class="nx_page_tit">모집</p>

<div class="sch_bx r_mh">
	<form id="frmSch" name="frmSch" method="get" action="<?php echo($_SERVER['PHP_SELF'])?>">
	<input type="submit" style="width:1px;height:1px;position:absolute;top:-10000px;left:-10000px" />

	<div class="sch_btm">
		<span class="sch_ipt r_mv">
			<label for="stx" class="blind">검색 입력창</label>
			<input type="text" id="stx" name="stx" value="<?php echo(F_hsc($stx))?>" title="리스트내 검색 입력창" />
			<a href="javascript:void(0);" onclick="$('#frmSch').submit();" class="sch_btn"><span class="blind">리스트 내 검색 버튼</span><span class="ico_search2"></span></a>
		</span>
	</div>

	</form>
</div>

<ul class="event_lst">
	<?php
	if (sql_num_rows($row) <= 0) {
		echo '<li class="nodata">신청된 행사가 없습니다.</li>';
	}
	else {
		while ($rs1 = sql_fetch_array($row))
		{
			# 마감된 행사는 하단의 버튼에 링크 걸림
			$_link = ($rs1['EM_JOIN_E_DATE'] < date('Y-m-d H:i')) ? 'javascript:void(0)' : '../evt/evt.read.php?'.$qstr.'&EM_IDX='.$rs1['EM_IDX'];
			?>
	<li>
		<div class="inner">
			<div class="img_wrap1">
				<div class="img_wrap2">
					<a href="<?php echo($_link)?>"><?php unset($_link)?>
					<?php
					#이미지 디렉토리와, 썸네일 대상 디렉토리
					$s_path = G5_PATH . "/data/file/NX_EVENT_MASTER"; 
					$t_path = G5_PATH . "/data/file/NX_EVENT_MASTER"; 

					# 썸네일 생성
					$thumb = thumbnail($rs1['bf_file'], $s_path, $t_path, 456, 456, true);
					if (!is_null($thumb)) {
						echo '<img src="/data/file/NX_EVENT_MASTER/'.$thumb.'" alt="'.F_hsc($rs1['bf_source']).'" class="img" />';
					}
					else {
						echo '<img src="'.G5_URL.'/img/no_img.jpg'.'" alt="'.F_hsc($rs1['bf_source']).'" class="img" />';
					}

					unset($s_path, $t_path, $thumb);
					?>
					</a>
				</div>
			</div>
			<div class="status"><?php
				if ($rs1['EM_JOIN_S_DATE'] <= date('Y-m-d H:i') && $rs1['EM_JOIN_E_DATE'] >= date('Y-m-d H:i')) echo '<span class="receipt">접수중</span>';
				else if ($rs1['EM_JOIN_E_DATE'] < date('Y-m-d H:i')) echo '<span class="end">마감</span>';
				else echo '<span class="ready">준비중</span>';
			?></div>
			<p class="tit"><?php echo(F_hsc($rs1['EM_TITLE']))?></p>
			<dl class="ppl">
				<dt>인원 :</dt>
				<dd><?php echo(($rs1['EM_JOIN_MAX'] > 0) ? number_format($rs1['EM_JOIN_MAX']).'명' : '제한없음')?></dd>
			</dl>
			<dl class="date">
				<dt>날짜</dt>
				<dd><?php
					echo F_hsc(str_replace('-','.', $rs1['EM_S_DATE']));
					
					# 시작일 = 종료일 일 경우 시작일만 표시
					if ($rs1['EM_S_DATE'] != $rs1['EM_E_DATE']) {
						echo ' ~ ' . F_hsc(str_replace('-', '.', $rs1['EM_E_DATE']));
					}
				?></dd>
			</dl>
			<div class="btn_wrap">
				<?php
				# 접수기간 종료 + 행사기간 종료 = '확인증 인쇄/만족도조사' 버튼 보임
				$_bo = false;
				$EM_E_DATETIME = new DateTime($rs1['EM_E_DATE'] . ' ' . $rs1['EM_E_TIME']);
				if ($rs1['EM_JOIN_E_DATE'] < date('Y-m-d H:i') && $EM_E_DATETIME->format('Y-m-d H:i') < date('Y-m-d H:i'))
				{
					# 승인 + 참석
					if ($rs1['EJ_STATUS'] == '2' && ($rs1['EJ_JOIN_CHK1'] == 'Y' && $rs1['EJ_JOIN_CHK2'] == 'Y'))
					{
						echo '<a class="print" href="javascript:void(0)" onclick="evt.cert({em_idx:\''.$rs1['EM_IDX'].'\',ej_idx:\''.$rs1['EJ_IDX'].'\'})">확인증 인쇄</a>';
						$_bo = true;


						# 만족도 조사기간일 경우 '만족도조사' 버튼 보임
						if ($rs1['ESM_S_DATE'] <= date('Y-m-d') && $rs1['ESM_E_DATE'] >= date('Y-m-d')) {
							echo '<a class="poll" href="javascript:void(0)" onclick="evt.sati({em_idx:\''.$rs1['EM_IDX'].'\',ej_idx:\''.$rs1['EJ_IDX'].'\'})">만족도조사</a>';
						}
					}
				}
				
				if (!$_bo) {
					echo '<a class="info" href="../evt/evt.join.read.php?EM_IDX='.$rs1['EM_IDX'].'" data-modal-title="신청 내역" '.$is_modal_js.'>신청 내역 보기</a>';
				}
				?>
			</div>
		</div>
	</li>
			<?php
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

<div style="display:none">
	<form id="frmSati" name="frmSati" onsubmit="return false">
		<input type="hidden" id="sati_em_idx" name="EM_IDX" value="" />
		<input type="hidden" id="sati_ej_idx" name="EJ_IDX" value="" />
	</form>
</div>


<script>
//<![CDATA[
var evt = {
	cert: function(o) {
		if (confirm("수료증을 인쇄 하시겠습니까?")) {
			var def = {em_idx: '', ej_idx: ''};
			var o = $.extend({}, def, o);
			
			if ((isNaN(o.em_idx) || parseInt(o.em_idx) <= 0) || (isNaN(o.ej_idx) || parseInt(o.ej_idx) <= 0)) return;

			window.open("", "evt_cert", "width=640, height=900, scrollbars=yes");

			var f = document.frmSati;
			f.action = '../evt/evt.cert.outer.php';
			f.method = 'post';
			f.target = 'evt_cert';
			f.EM_IDX.value = o.em_idx;
			f.EJ_IDX.value = o.ej_idx;
			f.submit();
		}
	}
	, sati: function(o) {
		if (confirm("만족도 조사를 진행하시겠습니까?")) {
			var def = {em_idx: '', ej_idx: ''};
			var o = $.extend({}, def, o);
			
			if ((isNaN(o.em_idx) || parseInt(o.em_idx) <= 0) || (isNaN(o.ej_idx) || parseInt(o.ej_idx) <= 0)) return;

			window.open("" ,"evt_sati", "width=600, height=600, scrollbars=yes"); 
			
			var f = document.frmSati;
			f.action = '../evt/evt.sati.php';
			f.method = 'post';
			f.target = 'evt_sati';
			f.EM_IDX.value = o.em_idx;
			f.EJ_IDX.value = o.ej_idx;
			f.submit();
		}
	}
}
//]]>
</script>
<?php 
	# 메뉴 표시에 사용할 변수 
	$_gr_id = 'nx05';  // 게시판 그룹 ID - 현재 메뉴표시에 사용
	$pid = ($pid) ? $pid : 'myevt';   // Page ID

	// include "../page/inc.page.menu.php";	 // 마이페이지에선 사용안함

	include_once('../bbs/_tail.php');
?>