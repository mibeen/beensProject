<?php
	$sub_menu = "960100";
	include_once('./_common.php');
	include_once('./place.err.php');
	include_once(G5_LIB_PATH.'/thumbnail.lib.php'); //썸네일 라이브러리 로딩


	# 시설 관리자 권한 부여
	if ($member['mb_level'] == 3) {
	    $auth['960100'] = 'r';
	    $auth['960200'] = 'r';
	    $auth['960300'] = 'r';
	}

	auth_check($auth[$sub_menu], 'r');


	$sql_common = " from local_place lp ";
	$wh = " where lp.lp_ddate is null";

	//if ($member['mb_level'] == 3) {
	//	$wh .= " And lp.mb_id = '" . mres($member['mb_id']) . "'";
	//}

	if ($stx) {
		if($sfl != '') {
			$wh .= " and ( ";
			$wh .= " ($sfl like '%$stx%') ";
			$wh .= " ) ";
		}
		else if($sfl == '') {
			$wh .= " and ( ";
			$wh .= " (lp_name like '%$stx%') || ";
			$wh .= " (lp_charge like '%$stx%') || ";
			$wh .= " (lp_tel like '%$stx%') || ";
			$wh .= " (lp_email like '%$stx%') || ";
			$wh .= " (lp_address like '%$stx%') ";
			$wh .= " ) ";
		}
	}

	if($suy != '') {
		$wh .= " and lp.lp_use_yn = '{$suy}'";
	}

	if($splace != '') {
		$wh .= " and lp.la_idx = {$splace}";
	}

	$sql_order = " order by lp.lp_idx Desc ";

	$sql = " select count(*) as cnt {$sql_common} {$wh} {$sql_order} ";
	$row = sql_fetch($sql);
	$total_count = $row['cnt'];

	$rows = 20;
	$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
	if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
	$from_record = ($page - 1) * $rows; // 시작 열을 구함

	$sql = " select * {$sql_common} {$wh} {$sql_order} limit {$from_record}, {$rows} ";
	$result = sql_query($sql);


	$sql = "Select * from local_place_area where la_ddate is null";
	$area_result_temp = sql_query($sql);
	$area_result = array();
	for ($i=0; $row=sql_fetch_array($area_result_temp); $i++) {
		$area_result[] = $row;
	}
	$j_area = count($area_result);


	$g5['title'] = '학습공간 관리_UDONGADM';
	include_once(G5_ADMIN_PATH.'/admin.head.php');
?>

<?php
if ($member['mb_level'] =3 ) {
	?>
<form name="fsearch" id="fsearch" method="get">
<input type="submit" style="width:1px;height:1px;position:absolute;top:-10000px;left:-10000px" />

<div class="sch_box">
	<div class="sch_opt1">
		<div class="sch_opt2">
			<span class="nx-slt" data-tit="지역">
				<select name="splace" id="splace" style="min-width: 140px;">
					<option value="">전체</option>
				<?php
				for ($i=0; $i < $j_area; $i++) {
					$row = $area_result[$i];
					?>
					<option value="<?php echo $row['la_idx']?>" <?php if($row['la_idx'] == $splace) { echo('selected'); }?>><?php echo $row['la_name']?></option>
				<?php } ?>
				</select>
				<span class="ico_select"></span>
			</span>

			<!-- <div class="res_wdtmh" style="height:10px;"></div> -->

			<span class="nx-slt" data-tit="상태">
				<select name="suy" id="suy" style="min-width: 140px;">
					<option value="">전체</option>
					<option value="Y" <?php if($suy == 'Y') { echo('selected'); }?>>On</option>
					<option value="N" <?php if($suy == 'N') { echo('selected'); }?>>Off</option>
				</select>
				<span class="ico_select"></span>
			</span>

			<span class="nx-slt" data-tit="검색범위">
				<select name="sfl" id="sfl" style="min-width: 140px;">
					<option value="">전체</option>
					<option value="lp_name" <?php if($sfl == 'lp_name') { echo('selected'); }?>>장소명</option>
					<option value="lp_charge" <?php if($sfl == 'lp_charge') { echo('selected'); }?>>담당자</option>
					<option value="lp_tel" <?php if($sfl == 'lp_tel') { echo('selected'); }?>>대표번호</option>
					<option value="lp_email" <?php if($sfl == 'lp_email') { echo('selected'); }?>>대표메일</option>
					<option value="lp_address" <?php if($sfl == 'lp_address') { echo('selected'); }?>>주소</option>
				</select>
				<span class="ico_select"></span>
			</span>

			<span class="sch_ipt wm2">
				<input type="text" name="stx" id="stx" value="<?php echo $stx ?>" class="nx-ips1">
			</span>
		</div>
	</div>

	<a href="javascript:void(0);" onclick="$('#fsearch').submit();" class="btn_sch"><span class="ico_search2"></span></a>
</div>
</form>
<script>
$(function(){
	<?php /* form input에서 enter 입력시 Token ajax 호출이 일어나, 해당 이벤트를 무력화. */ ?>
	$('#stx').on('keydown', function(e){
		if(e.keyCode == '13'){
			$('#fsearch').submit();
			e.preventDefault();
		}
	})
})
</script>
	<?php
}
?>

<?php
if ($member['mb_level'] == 3) {
	?>
<div class="ofH mb10">
	<div class="fL">
	<?php if ($is_admin) {
		?>
		<a href="./place.area.php" class="nx-btn-b2">지역 편집</a>
		<?php
	}
	?>
		<a href="javascript:ExcelDownload();" class="nx-btn-b1">엑셀 다운로드</a>
	</div>
	<div class="fR">
		<a href="./place.mng.excel.php" class="nx-btn-b1">시설관리자 일괄연결</a>
		<a href="./place.form.php" class="nx-btn-b2">장소 추가</a>
	</div>
</div>
	<?php
}
?>

<ul class="nx-place-list" style="">
	<?php
	for ($i=0; $row=sql_fetch_array($result); $i++) {
		$la_idx = '';
		$la_name = '';

		for ($ii = 0; $ii < $j_area; $ii++) {
			$row_area = $area_result[$ii];
			if($row_area['la_idx'] == $row['la_idx']) {
				$la_idx = $row_area['la_idx'];
				$la_name = $row_area['la_name'];
				$lp_name = $row['lp_name'];
				break;
			}
		}


		$sql = " select bf_file, bf_source from {$g5['board_file_table']} where bo_table = 'local_place' and wr_id = '{$row['lp_idx']}'";
		$place_file_result = sql_query($sql);
		$place_file = sql_fetch_array($place_file_result);

		# 썸네일 생성
		$thumb = thumbnail($place_file['bf_file'], G5_PATH."/data/file/local_place", G5_PATH."/data/file/local_place", 320, 180, true);
		if (!is_null($thumb)) {
			$himg_str = '<img src="/data/file/local_place/'.$thumb.'" alt="'.htmlspecialchars($place_file['bf_source']).'" class="img" />';
		}
		else {
			$himg_str = '';
		}
		?>
	<li>
		<a href="./place.req.list.php?<?php echo($qstr)?>lp_idx=<?php echo($row['lp_idx'])?>" class="inner">
			<div class="img-wrap1">
				<div class="img-wrap2">
					<?php echo $himg_str?>
				</div>
			</div>
			<div class="txt-wrap">
				<div class="tit"><?php echo($lp_name)?></div>
				<div class="info">
					<span class="cate"><?php echo($la_name)?></span>
				</div>
			</div>
		</a>
		<div class="btn-area">
			<a href="./place.req.list.php?<?php echo($qstr)?>lp_idx=<?php echo($row['lp_idx'])?>">예약현황</a>
			<a href="./place.form.php?lp_idx=<?php echo $row['lp_idx']?>&amp;<?php echo $qstr?>">장소 수정</a>
		</div>
	</li>
	<?php }

	if($i == 0) { ?>
	<li class="nodata">등록된 장소가 없습니다.</li>
	<?php } ?>
</ul>

<?php
	$qstr .= "&amp;page=";

	$pagelist = get_paging($config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?".$qstr);
	echo $pagelist;
?>

<script>
function ExcelDownload() {
	if(confirm('학습공간 내역 엑셀 파일을 다운로드 하시겠습니까?')) {
		window.location.href = "place.list.excel.php?<?php echo($qstr)?>";
	}
	return;
}
</script>

<?php
include_once(G5_ADMIN_PATH.'/admin.tail.php');
?>
