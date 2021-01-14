<?php
	$sub_menu = "990200";
	include_once('./_common.php');
	include_once(G5_EDITOR_LIB);
	include_once(G5_LIB_PATH.'/thumbnail.lib.php'); //썸네일 라이브러리 로딩

	auth_check($auth[$sub_menu], 'w');

	$bo_table = 'place_rental';

	if (isset($_REQUEST['PM_IDX']) && $_REQUEST['PM_IDX']) {
		$PM_IDX = preg_replace('/[^0-9]/', '', $PM_IDX);
	} else {
		$PM_IDX = '';
	}

	$sql = " select * from {$g5['place_rental_area_table']} where PA_DDATE is null";
	$area_result = sql_query($sql);

	if($PM_IDX != '') {
		$sql = " select * from {$g5['place_rental_master_table']} where PM_DDATE is null and PM_IDX = '$PM_IDX' limit 1";
		$result = sql_query($sql);
		$place=sql_fetch_array($result);

		$sql = " select bf_file, bf_source from {$g5['board_file_table']} where bo_table = '{$bo_table}' and wr_id = '{$PM_IDX}'";
		$place_file_result = sql_query($sql);
	}
	else {
		$place = array();
	}

	$html_title = '장소';

	$required = "";
	$readonly = "";
	if ($place['PM_IDX'] == '') {

		$html_title .= ' 등록';

		$required = 'required';
		$required_valid = 'alnum_';
		$sound_only = '<strong class="sound_only">필수</strong>';

		$w = '';

	} else if ($place['PM_IDX'] == $PM_IDX) {

		$html_title .= ' 수정';

		$readonly = 'readonly';

		$w = 'u';
	}

	if ($is_admin != 'super') {
		$is_admin = is_admin($member['mb_id']);
	}

	$g5['title'] = $html_title;
	include_once (G5_ADMIN_PATH.'/admin.head.php');
?>

<form name="fboardform" id="fboardform" action="./place_rental_form_update.php" onsubmit="return fboardform_submit(this)" method="post" enctype="multipart/form-data">
<input type="hidden" name="w" value="<?php echo $w ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="splace" value="<?php echo $splace ?>">
<input type="hidden" name="suy" value="<?php echo $suy ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="PM_IDX" value="<?php echo $place['PM_IDX'] ?>">
<input type="hidden" name="token" value="">
<input type="hidden" name="w" value="<?php echo $w ?>">

<h3 class="nx-tit1 lh30 mb" style="margin-top:0px"><a href="./place_rental_list.php" class="nx-btn-b3 fR ml15">뒤로</a>장소 등록</h3>

<table border="0" cellpadding="0" cellspacing="0" class="nx-t-read1">
<caption>장소 기본</caption>
<colgroup>
	<col width="130"><col width="">
</colgroup>
<tbody>
<tr>
	<th scope="row">지역</th>
	<td colspan="2">
		<span class="nx-slt">
			<select name="PA_IDX" id="PA_IDX" required>
				<option value="">지역 선택</option>
			<?php
			for ($i=0; $row=sql_fetch_array($area_result); $i++) { ?>
				<option value="<?php echo $row['PA_IDX']?>" <?php if($row['PA_IDX'] == $place['PA_IDX']) { echo('selected'); }?>><?php echo $row['PA_NAME']?></option>
			<?php } ?>
			</select>
			<span class="ico_select"></span>
		</span>
	</td>
</tr>

<tr>
	<th scope="row"><label for="PM_NAME">장소명<strong class="sound_only">필수</strong></label></th>
	<td colspan="2">
		<input type="text" name="PM_NAME" value="<?php echo get_text($place['PM_NAME']) ?>" id="PM_NAME" required class="nx-ips1 wm required" size="80" maxlength="120">
	</td>
</tr>

<tr>
	<th scope="row"><label for="PM_CHARGE">담당자</label></th>
	<td colspan="2">
		<input type="text" name="PM_CHARGE" value="<?php echo get_text($place['PM_CHARGE']) ?>" id="PM_CHARGE" class="nx-ips1 ws" size="80" maxlength="120">
	</td>
</tr>

<tr>
	<th scope="row"><label for="PM_TEL">대표번호</label></th>
	<td colspan="2">
		<input type="text" name="PM_TEL" value="<?php echo get_text($place['PM_TEL']) ?>" id="PM_TEL" class="nx-ips1 ws" size="80" maxlength="120">
	</td>
</tr>

<tr>
	<th scope="row"><label for="PM_EMAIL">대표메일</label></th>
	<td colspan="2">
		<input type="text" name="PM_EMAIL" value="<?php echo get_text($place['PM_EMAIL']) ?>" id="PM_EMAIL" class="nx-ips1 wm" size="80" maxlength="120">
	</td>
</tr>

<tr>
	<th scope="row"><label for="PM_ADDRESS">주소<strong class="sound_only">필수</strong></label></th>
	<td colspan="2">
		<input type="text" name="PM_ADDRESS" value="<?php echo get_text($place['PM_ADDRESS']) ?>" id="PM_ADDRESS" required class="required nx-ips1 wl" size="80" maxlength="120">
	</td>
</tr>

<tr>
	<th scope="row"><label for="PM_INFO">정보</label></th>
	<td>
		<?php
		$placehold_txt = "※ 한글(hwp)에 있는 것을 그대로 복사해 붙여 넣으면 웹사이트에 예상하지 못한 영향을 줄 수 있습니다. 한글에서 복사한 내용을 메모장 등에 한번 옮겼다가 에디터에 붙여 주세요.<br>※ 에디터에서 경기천년체를 사용할 수 있도록 글꼴 추가했습니다. 글꼴 선택 목록을 확인해 주세요.";
		?>
		<?php echo editor_html("PM_INFO", (($place['PM_INFO'] != '') ? get_text($place['PM_INFO'], 0) : $placehold_txt )); ?>
	</td>
</tr>

<tr>
	<th scope="row"><label for="PM_USE_YN">On Off<strong class="sound_only">필수</strong></label></th>
	<td colspan="2">
		<input type="radio" name="PM_USE_YN" id="PM_USE_YN_Y" value="Y" required <?php if($place['PM_USE_YN'] == 'Y') echo('checked');?> class="radio1"><label for="PM_USE_YN_Y"><span class="radbox"><span></span></span><span class="txt">On</span></label>
		&nbsp;&nbsp;&nbsp;
		<input type="radio" name="PM_USE_YN" id="PM_USE_YN_N" value="N" required <?php if($place['PM_USE_YN'] == 'N') echo('checked');?> class="radio1"><label for="PM_USE_YN_N"><span class="radbox"><span></span></span><span class="txt">Off</span></label>
	</td>
</tr>

<?php for($i = 1; $i <= 5; $i++) { ?>
<tr>
	<th scope="row"><label for="bf_file">이미지 <?php echo $i?><?php if($i == 1) {echo(' (목록에 사용)');}?></label></th>
	<td colspan="2">
		<input type="file" name="bf_file[]" id="bf_file" class="frm_file frm_input">
		<?php
		$place_file = sql_fetch_array($place_file_result);


		if(isset($place_file) && $place_file['bf_file'] != '') {
			# 썸네일 생성
			$thumb = thumbnail($place_file['bf_file'], G5_PATH."/data/file/place_rental", G5_PATH."/data/file/place_rental", 320, 180, true);
			$himg_str = '<img src="/data/file/place_rental/'.$thumb.'" alt="'.htmlspecialchars($place_file['bf_source']).'" class="img" />';
		}
		else {
			$himg_str = '';
		}


		if ($himg_str) {
			echo '<div class="banner_or_img">';
			echo $himg_str;
			echo '<input type="checkbox" name="bf_file_del' . ($i-1) . '" value="1" id="bf_file_del' . ($i-1) . '" style="margin-left:10px"> <label for="bf_file_del' . ($i-1) . '">파일삭제</label>';
			echo '</div>';
		}
		?>
	</td>
</tr>
<?php } ?>

</tbody>
</table>

<div class="ofH mt10">
	<div class="fR">
		<input type="submit" value="확인" class="nx-btn-b2" accesskey="s">
		<a href="./place_rental_list.php?<?php echo($qstr)?>" class="nx-btn-b3">목록</a>
	</div>	
	<?php
	if($w != '') {
		?>
		<div class="fL">
			<input formnovalidate type="submit" name="act_button" class="nx-btn-b4" value="삭제" onclick="document.pressed=this.value">
		</div>
		<?php
	}
	?>
</div>

</form>

<script>
function fboardform_submit(f)
{
	
	var _t = $('#PA_IDX');
	if (_t.val() == '') {
		alert("지역 정보를 선택해 주세요.");
		_t.focus();
		return false;
	}

	var _t = $('#PM_NAME');
	if (_t.val() == '') {
		alert("장소명 정보를 입력해 주세요.");
		_t.focus();
		return false;
	}

	var _t = $('#PM_CHARGE');
	if (_t.val() == '') {
		alert("담당자 정보를 입력해 주세요.");
		_t.focus();
		return false;
	}

	var _t = $('#PM_TEL');
	if (_t.val() == '') {
		alert("대표번호 정보를 입력해 주세요.");
		_t.focus();
		return false;
	}

	var _t = $('#PM_EMAIL');
	if (_t.val() == '') {
		alert("대표메일 정보를 입력해 주세요.");
		_t.focus();
		return false;
	}

	var _t = $('#PM_ADDRESS');
	if (_t.val() == '') {
		alert("주소 정보를 입력해 주세요.");
		_t.focus();
		return false;
	}

	if ($(':radio[name="PM_USE_YN"]:checked').length <= 0) {
		alert("On/Off 정보를 선택해 주세요.");
		$('#PM_USE_YN_Y').focus();
		return false;
	}

	<?php echo get_editor_js("PM_INFO"); ?>
	
	if(document.pressed == "삭제") {
		if(!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
			return false;
		}
	}

	return true;
}
</script>

<?php
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
