<?php
	$sub_menu = "990200";
	include_once('./_common.php');
	include_once(G5_LIB_PATH.'/thumbnail.lib.php'); //썸네일 라이브러리 로딩

	auth_check($auth[$sub_menu], 'r');

	$bo_table = 'place_rental';

	$sql = " select * from {$g5['place_rental_area_table']} where PA_DDATE is null";
	$area_result_temp = sql_query($sql);
	$area_result = array();
	for ($i=0; $row=sql_fetch_array($area_result_temp); $i++) {
		$area_result[] = $row;
	}
	$j_area = count($area_result);

	$sql_common = " from {$g5['place_rental_master_table']} a ";
	$sql_search = " where (1) ";

	if ($stx) {
		if($sfl != '') {
			$sql_search .= " and ( ";
			$sql_search .= " ($sfl like '%$stx%') ";
			$sql_search .= " ) ";
		}
		else if($sfl == '') {
			$sql_search .= " and ( ";
			$sql_search .= " (PM_NAME like '%$stx%') || ";
			$sql_search .= " (PM_CHARGE like '%$stx%') || ";
			$sql_search .= " (PM_TEL like '%$stx%') || ";
			$sql_search .= " (PM_EMAIL like '%$stx%') || ";
			$sql_search .= " (PM_ADDRESS like '%$stx%') ";
			$sql_search .= " ) ";
		}
	}

	if($suy != '') {
		$sql_search .= " and a.PM_USE_YN = '{$suy}'";
	}

	if($splace != '') {
		$sql_search .= " and a.PA_IDX = {$splace}";
	}

	$sst = "a.PM_IDX";
	$sod = "Desc";
	if (!$sst) {
		$sst  = "a.PA_IDX";
		$sod = "Asc";
	}
	$sql_order = " order by $sst $sod ";

	$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_order} ";
	$row = sql_fetch($sql);
	$total_count = $row['cnt'];

	$rows = $config['cf_page_rows'];
	$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
	if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
	$from_record = ($page - 1) * $rows; // 시작 열을 구함

	$sql = " select * {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
	$result = sql_query($sql);

	$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';

	$g5['title'] = '대관관리';
	include_once(G5_ADMIN_PATH.'/admin.head.php');

	$colspan = 15;
?>

<ul class="nx-tab1">
	<li><a href="./place_rental_list.php" class="aon">장소 목록</a></li>
	<li><a href="./place_rental_req_all_list.php">예약 현황</a></li>
</ul>

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
					<option value="<?php echo $row['PA_IDX']?>" <?php if($row['PA_IDX'] == $splace) { echo('selected'); }?>><?php echo $row['PA_NAME']?></option>
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
					<option value="PM_NAME" <?php if($sfl == 'PM_NAME') { echo('selected'); }?>>장소명</option>
					<option value="PM_CHARGE" <?php if($sfl == 'PM_CHARGE') { echo('selected'); }?>>담당자</option>
					<option value="PM_TEL" <?php if($sfl == 'PM_TEL') { echo('selected'); }?>>대표번호</option>
					<option value="PM_EMAIL" <?php if($sfl == 'PM_EMAIL') { echo('selected'); }?>>대표메일</option>
					<option value="PM_ADDRESS" <?php if($sfl == 'PM_ADDRESS') { echo('selected'); }?>>주소</option>
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

<div class="ofH mb10">
	<div class="fL">
		<a href="./place_list.php" class="nx-btn-b2">지역 편집</a>
	</div>
	<div class="fR">
		<a href="./place_rental_form.php" id="bo_add" class="nx-btn-b2">장소 추가</a>
	</div>
</div>

<ul class="nx-place-list" style="">
		<?php
		for ($i=0; $row=sql_fetch_array($result); $i++) { 
			$PA_IDX = '';
			$PA_NAME = '';

			for ($ii = 0; $ii < $j_area; $ii++) { 
				$row_area = $area_result[$ii];
				if($row_area['PA_IDX'] == $row['PA_IDX']) {
					$PA_IDX = $row_area['PA_IDX'];
					$PA_NAME = $row_area['PA_NAME'];
					$PM_NAME = $row['PM_NAME'];
					break;
				}
			}

			
			$sql = " select bf_file, bf_source from {$g5['board_file_table']} where bo_table = '{$bo_table}' and wr_id = '{$row['PM_IDX']}'";
			$place_file_result = sql_query($sql);
			$place_file = sql_fetch_array($place_file_result);

			# 썸네일 생성
			$thumb = thumbnail($place_file['bf_file'], G5_PATH."/data/file/place_rental", G5_PATH."/data/file/place_rental", 320, 180, true);
			if (!is_null($thumb)) {
				$himg_str = '<img src="/data/file/place_rental/'.$thumb.'" alt="'.htmlspecialchars($place_file['bf_source']).'" class="img" />';
			}
			else {
				$himg_str = '<img src="/img/no_img_udonglist.jpg" alt="" class="img">';
			}


			$sql = " select count(*) as cnt from {$g5['place_rental_sub_table']} where PS_DDATE is null and PS_GUBUN = 'A' and PM_IDX = " . $row['PM_IDX'];
			$row_sub = sql_fetch($sql);
			$gubun_a_count = $row_sub['cnt'];

			$sql = " select count(*) as cnt from {$g5['place_rental_sub_table']} where PS_DDATE is null and PS_GUBUN = 'B' and PM_IDX = " . $row['PM_IDX'];
			$row_sub = sql_fetch($sql);
			$gubun_b_count = $row_sub['cnt'];
			?>
		<li>
			<a href="./place_rental_sub_list.php?PM_IDX=<?php echo($row['PM_IDX'])?>" class="inner">
				<div class="img-wrap1">
					<div class="img-wrap2">
						<?php echo $himg_str?>
					</div>
				</div>
				<div class="txt-wrap">
					<div class="tit"><?php echo($PM_NAME)?></div>
					<div class="info">
						<span class="cate"><?php echo($PA_NAME)?></span>
						<span class="room">강의실 <?php echo $gubun_a_count?> | 숙소 <?php echo $gubun_b_count?></span>
					</div>
				</div>
			</a>
			<div class="btn-area">
				<a href="./place_rental_sub_list.php?PM_IDX=<?php echo($row['PM_IDX'])?>">강의실 / 숙소</a>
				<a href="./place_rental_form.php?PM_IDX=<?php echo $row['PM_IDX']?>&amp;<?php echo $qstr?>">장소 수정</a>
			</div>
		</li>
		<?php } 

		if($i == 0) { ?>
		<li class="nodata">등록된 장소가 없습니다.</li>
		<?php } ?>
	</ul>
<script>
function fboardlist_submit(f)
{
	if (!is_checked("chk[]")) {
		alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
		return false;
	}

	if(document.pressed == "선택삭제") {
		if(!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
			return false;
		}
	}

	return true;
}
</script>

<?php
include_once(G5_ADMIN_PATH.'/admin.tail.php');
?>
