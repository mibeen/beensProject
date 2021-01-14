<?php
	$sub_menu = "990200";
	include_once('./_common.php');
	include_once(G5_EDITOR_LIB);
	include_once(G5_LIB_PATH.'/thumbnail.lib.php'); //썸네일 라이브러리 로딩

	auth_check($auth[$sub_menu], 'w');

	$bo_table = 'place_rental_sub';

	if (isset($_REQUEST['PM_IDX']) && $_REQUEST['PM_IDX']) {
		$PM_IDX = preg_replace('/[^0-9]/', '', $PM_IDX);
	} else {
		alert("잘못된 접근입니다.");
	}

	if (isset($_REQUEST['PS_IDX']) && $_REQUEST['PS_IDX']) {
		$PS_IDX = preg_replace('/[^0-9]/', '', $PS_IDX);
	} else {
		$PS_IDX = '';
	}

	if($PM_IDX != '') {
		$sql = " select * from {$g5['place_rental_sub_table']} where PS_DDATE is null and PS_IDX = '$PS_IDX' limit 1";
		$result = sql_query($sql);
		$place=sql_fetch_array($result);

		$sql = " select bf_file, bf_source from {$g5['board_file_table']} where bo_table = '{$bo_table}' and wr_id = '{$PS_IDX}'";
		$place_file_result = sql_query($sql);
	}
	else {
		$place = array();
	}

	$html_title = '강의실 / 숙소';

	$required = "";
	$readonly = "";
	if ($place['PS_IDX'] == '') {

		$html_title .= ' 등록';

		$required = 'required';
		$required_valid = 'alnum_';
		$sound_only = '<strong class="sound_only">필수</strong>';

	} else if ($place['PS_IDX'] == $PS_IDX) {

		$html_title .= ' 수정';

		$readonly = 'readonly';

	}

	if ($is_admin != 'super') {
		$is_admin = is_admin($member['mb_id']);
	}

	$g5['title'] = $html_title;
	include_once (G5_ADMIN_PATH.'/admin.head.php');

	$pg_anchor = '<ul class="anchor">
		<li><a href="#anc_bo_basic">기본 설정</a></li>
		<li><a href="#anc_bo_auth">권한 설정</a></li>
		<li><a href="#anc_bo_function">기능 설정</a></li>
		<li><a href="#anc_bo_design">디자인/양식</a></li>
		<li><a href="#anc_bo_point">포인트 설정</a></li>
		<li><a href="#anc_bo_extra">여분필드</a></li>
	</ul>';

	$PS_GUBUN = isset($_GET['PS_GUBUN']) ? '&amp;PS_GUBUN=' . clean_xss_tags(trim($_GET['PS_GUBUN'])) : '';

	$frm_submit = '
	<div class="ofH mt10">
		<div class="fL">
			<a href="javascript:void(0)" onclick="rent.del()" class="nx-btn-b4">삭제</a>
		</div>
		<div class="fR">
			<input type="submit" value="확인" class="nx-btn-b2" accesskey="s">
			<a href="./place_rental_sub_list.php?PM_IDX='.$PM_IDX.'&amp;'.$qstr.$PS_GUBUN.'" class="nx-btn-b3">목록</a>
		</div>
	</div>
	';
?>

<form name="fboardform" id="fboardform" action="./place_rental_sub_form_update.php" onsubmit="return fboardform_submit(this)" method="post" enctype="multipart/form-data">
<input type="hidden" name="w" value="<?php echo $w ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="PS_IDX" value="<?php echo $place['PS_IDX'] ?>">
<input type="hidden" name="PM_IDX" value="<?php echo $PM_IDX?>">
<input type="hidden" name="token" value="">

<table border="0" cellpadding="0" cellspacing="0" class="nx-t-read1">
<caption>강의실 / 숙소 기본</caption>
<colgroup>
	<col width="150"><col width="">
</colgroup>
<tbody>
<tr>
	<th><label for="bo_table">구분<?php echo $sound_only ?></label></th>
	<td>
		<span class="nx-slt">
			<select name="PS_GUBUN" id="PS_GUBUN" required>
				<option value="">구분 선택</option>
				<option value="A" <?php if($place['PS_GUBUN'] == 'A') { echo('selected'); }?>>강의실</option>
				<option value="B" <?php if($place['PS_GUBUN'] == 'B') { echo('selected'); }?>>숙소</option>
			</select>
			<span class="ico_select"></span>
		</span>
	</td>
</tr>

<tr>
	<th><label for="PS_NAME">강의실명(숙소명)<strong class="sound_only">필수</strong></label></th>
	<td>
		<input type="text" name="PS_NAME" value="<?php echo get_text($place['PS_NAME']) ?>" id="PS_NAME" required class="nx-ips1 required" size="80" maxlength="120">
	</td>
</tr>

<tr>
	<th><label for="PS_INFO">정보</label></th>
	<td>
		<?php echo editor_html("PS_INFO", get_text($place['PS_INFO'], 0)); ?>
	</td>
</tr>

<?php for($i = 1; $i <= 5; $i++) { ?>
<tr>
	<th><label for="bf_file">이미지 <?php echo $i?> <?php if($i == 1) {echo('(목록에 사용)');}?></label></th>
	<td>
		<input type="file" name="bf_file[]" id="bf_file" class="frm_file frm_input">
		<?php
		$place_file = sql_fetch_array($place_file_result);
		

		if(isset($place_file) && $place_file['bf_file'] != '') {
			# 썸네일 생성
			$thumb = thumbnail($place_file['bf_file'], G5_PATH."/data/file/place_rental_sub", G5_PATH."/data/file/place_rental_sub", 320, 180, true);
			$himg_str = '<img src="/data/file/place_rental_sub/'.$thumb.'" alt="'.htmlspecialchars($place_file['bf_source']).'" class="img" />';
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

<?php echo $frm_submit; ?>

</form>

<script>
//<![CDATA[
function fboardform_submit(f)
{
	var _t = $('#PS_GUBUN');
	if (_t.val() == '') {
		alert("구분 정보를 선택해 주세요.");
		_t.focus();
		return false;
	}

	var _t = $('#PS_NAME');
	if (_t.val() == '') {
		alert("강의실명(숙소명) 정보를 입력해 주세요.");
		_t.focus();
		return false;
	}

	<?php echo get_editor_js("PS_INFO"); ?>

	return true;
}


var rent = {
	del: function() {
		if (confirm("삭제된 정보는 복구가 불가능 합니다.\n\n삭제 하시겠습니까?")) {
			var f = new FormData();
			f.append('pm_idx', document.getElementById('fboardform').PM_IDX.value);
			f.append('ps_idx', document.getElementById('fboardform').PS_IDX.value);

			$.ajax({
				url: './place_rental_sub_del.php',
				type: 'POST',
				dataType: 'json',
				data: f,
				processData: false,
				contentType: false
			})
			.done(function(json) {
				if (!json.success) {
					if (json.msg) alert(json.msg);
					return;
				}

				if (json.msg) alert(json.msg);
				if (json.redir) window.location.href = json.redir;
			})
			.fail(function(a, b, c) {
				alert(a.statusText+' ('+a.status+')\n\n'+a.responseText);
			});
		}
	}
}
//]]>
</script>

<?php
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
