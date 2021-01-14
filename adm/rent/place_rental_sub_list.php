<?php
	$sub_menu = "990200";
	include_once('./_common.php');
	include_once(G5_LIB_PATH.'/thumbnail.lib.php'); //썸네일 라이브러리 로딩

	auth_check($auth[$sub_menu], 'r');

	$bo_table = 'place_rental_sub';

	if (isset($_REQUEST['PM_IDX']) && $_REQUEST['PM_IDX']) {
		$PM_IDX = preg_replace('/[^0-9]/', '', $PM_IDX);
	} else {
		alert("잘못된 접근입니다.");
	}

	$sql_common = " from {$g5['place_rental_sub_table']} a ";
	$sql_search = " where PS_DDATE is null and PM_IDX = {$PM_IDX}";

	if (isset($_REQUEST['PS_GUBUN']) && $_REQUEST['PS_GUBUN'])  {
		$PS_GUBUN = trim($_REQUEST['PS_GUBUN']);
		$PS_GUBUN = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\/\^\*\s]/", "", $PS_GUBUN);
		if ($PS_GUBUN)
			$sql_search .= " and a.PS_GUBUN = '{$PS_GUBUN}'";
	}


	if (!$sst) {
		$sst  = "a.PS_IDX";
		$sod = "asc";
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

	$g5['title'] = '강의실 / 숙소 관리';
	include_once(G5_ADMIN_PATH.'/admin.head.php');

	$colspan = 5;
?>

<ul class="nx-tab1">
	<li><a href="./place_rental_sub_list.php?PM_IDX=<?php echo $PM_IDX?>" <?php if($PS_GUBUN != 'A' && $PS_GUBUN != 'B') echo("class='aon'");?>>강의실/숙소</a></li>
	<li><a href="./place_rental_sub_list.php?PM_IDX=<?php echo $PM_IDX?>&amp;PS_GUBUN=A" <?php if($PS_GUBUN == 'A') echo("class='aon'");?>>강의실만</a></li>
	<li><a href="./place_rental_sub_list.php?PM_IDX=<?php echo $PM_IDX?>&amp;PS_GUBUN=B" <?php if($PS_GUBUN == 'B') echo("class='aon'");?>>숙소만</a></li>
</ul>

<div class="taR mb10">
	<a href="./place_rental_sub_form.php?PM_IDX=<?php echo $PM_IDX?>" id="bo_add" class="nx-btn-b2">강의실 / 숙소 추가</a>
	<a href="./place_rental_list.php" class="nx-btn-b3">뒤로</a>
</div>

<form name="fboardlist" id="fboardlist" action="./place_rental_sub_update.php" onsubmit="return fboardlist_submit(this);" method="post">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="token" value="<?php echo $token ?>">
<input type="hidden" name="PM_IDX" value="<?php echo $PM_IDX ?>">

<table border="0" cellpadding="0" cellspacing="0" class="nx-t-list1">
	<caption><?php echo $g5['title']; ?> 목록</caption>
	<colgroup>
		<col width="10%"><col width="140"><col width=""><col width="10%"><col width="10%">
	</colgroup>
	<thead>
	<tr>
		<?php /* (TEMP) 리스트에서 수정기능 사용안함
		<th>
			<label for="chkall" class="sound_only">게시판 전체</label>
			<input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
		</th>
		*/ ?>
		<th>구분</a></th>
		<th>이미지</th>
		<th>강의실명(숙소명)</a></th>
		<th>관리</th>
		<th>예약</th>
	</tr>
	</thead>
	<tbody>
	<?php
	$PS_GUBUN = isset($_GET['PS_GUBUN']) ? '&amp;PS_GUBUN=' . clean_xss_tags(trim($_GET['PS_GUBUN'])) : '';
	for ($i=0; $row=sql_fetch_array($result); $i++) {
		$one_update = '<a href="./place_rental_sub_form.php?w=u&amp;PS_IDX='.$row['PS_IDX'].'&amp;PM_IDX='.$PM_IDX.'&amp;'.$qstr.$PS_GUBUN.'" class="nx-btn-s3">수정</a>';

		
		$sql = " select bf_file, bf_source from {$g5['board_file_table']} where bo_table = '{$bo_table}' and wr_id = '{$row['PS_IDX']}'";
		$place_file_result = sql_query($sql);
		$place_file = sql_fetch_array($place_file_result);

		# 썸네일 생성
		$thumb = thumbnail($place_file['bf_file'], G5_PATH."/data/file/place_rental_sub", G5_PATH."/data/file/place_rental_sub", 112, 63, true);
		$himg_str = '<img src="/data/file/place_rental_sub/'.$thumb.'" alt="'.htmlspecialchars($place_file['bf_source']).'" class="img" />';
	?>

	<tr>
		<?php /* (TEMP) 리스트에서 수정기능 사용안함
		<td>
			<label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo get_text($row['PS_NAME']) ?></label>
			<input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
			<input type="hidden" name="PS_IDX[<?php echo $i ?>]" value="<?php echo $row['PS_IDX'] ?>">
		</td>
		*/ ?>
		<td>
			<?php echo(($row['PS_GUBUN'] == 'A') ? '강의실' : '숙소')?>
			<?php /* (TEMP) 리스트에서 수정기능 사용안함
			<label for="bo_subject_<?php echo $i; ?>" class="sound_only">구분<strong class="sound_only"> 필수</strong></label>
			<select name="PS_GUBUN[<?php echo $i ?>]" id="PS_GUBUN<?php echo $i ?>" required>
				<option value="">구분 선택</option>
				<option value="A" <?php if($row['PS_GUBUN'] == 'A') { echo('selected'); }?>>강의실</option>
				<option value="B" <?php if($row['PS_GUBUN'] == 'B') { echo('selected'); }?>>숙소</option>
			</select>
			*/ ?>
		</td>
		<td>
			<?php echo $himg_str?>
		</td>
		<td class="taL">
			<span class="color-tit"><?php echo get_text($row['PS_NAME']) ?></span>
			<?php /* (TEMP) 리스트에서 수정기능 사용안함
			<label for="bo_subject_<?php echo $i; ?>" class="sound_only">강의실명(숙소명)<strong class="sound_only"> 필수</strong></label>
			<input type="text" name="PS_NAME[<?php echo $i ?>]" value="<?php echo get_text($row['PS_NAME']) ?>" id="PS_NAME<?php echo $i ?>" required class="required frm_input PS_NAME full_input" size="10">
			*/ ?>
		</td>
		<td>
			<?php echo $one_update ?>
		</td>
		<td>
			<a href="./place_rental_req_list.php?PM_IDX=<?php echo($PM_IDX)?>&PS_IDX=<?php echo $row['PS_IDX']?>" class="nx-btn-s2">예약 확인</a>
		</td>
	</tr>
	<?php
	}
	if ($i == 0)
		echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>';
	?>
	</tbody>
</table>

<div class="taR mt10">
	<?php /* (TEMP) 리스트에서 수정기능 사용안함
	<input type="submit" name="act_button" value="선택수정" onclick="document.pressed=this.value">
	<?php if ($is_admin == 'super') { ?>
	<input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value">
	<?php } ?>
	*/ ?>
	<a href="./place_rental_list.php" class="nx-btn-b3">뒤로</a>
</div>

</form>

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
