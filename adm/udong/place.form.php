<?php
	$sub_menu = "970100";
	include_once('./_common.php');
	include_once('./place.err.php');
	include_once(G5_EDITOR_LIB);
	include_once(G5_LIB_PATH.'/thumbnail.lib.php'); //썸네일 라이브러리 로딩


	# 시설 관리자 권한 부여
	if ($member['mb_level'] == 4) {
		$auth[$sub_menu] = 'r, w';
	}

	auth_check($auth[$sub_menu], 'w');


	$bo_table = 'local_place';

	if (isset($_REQUEST['lp_idx']) && $_REQUEST['lp_idx']) {
		$lp_idx = preg_replace('/[^0-9]/', '', $lp_idx);
	} else {
		$lp_idx = '';
	}

	$added_wh = '';


	# 시설 관리자일때는 lp_idx 필수 : 수정만 가능
	if ($member['mb_level'] == 4) {
		if ($lp_idx == '') {
			F_script("잘못된 접근입니다.", "history.back();");
		}

		$added_wh = " And mb_id = '" . mres($member['mb_id']) . "'";
	}


	$sql = "Select * From
			local_place_area
			Where la_ddate is null"
		;
	$DB_area = sql_query($sql);

	if($lp_idx != '') {
		$sql = "Select * From
				local_place
				Where lp_ddate is null
					and lp_idx = '" . mres($lp_idx) . "'
					{$added_wh}
				limit 1"
			;
		$place = sql_fetch($sql);

		if (is_null($place['lp_idx'])) {
			unset($place);
			F_script("잘못된 접근입니다.", 'history.back();');
		}


		$sql = "Select bf_file, bf_source From
				{$g5['board_file_table']}
				Where bo_table = '" . mres($bo_table) . "'
					and wr_id = '" . mres($lp_idx) . "'"
			;
		$place_file_result = sql_query($sql);
	}
	else {
		$place = array();
	}


	$html_title = '학습공간';

	if ($place['lp_idx'] == '') {
		$html_title .= ' 등록';
		$w = '';
	}
	else if ($place['lp_idx'] == $lp_idx) {
		$html_title .= ' 수정';
		$w = 'u';
	}


	$g5['title'] = $html_title;
	include_once (G5_ADMIN_PATH.'/admin.head.php');
?>

<form name="fboardform" id="fboardform" action="./place.formProc.php" method="post" enctype="multipart/form-data">
<input type="hidden" name="w" value="<?php echo $w ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="splace" value="<?php echo $splace ?>">
<input type="hidden" name="suy" value="<?php echo $suy ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="lp_idx" id="lp_idx" value="<?php echo $place['lp_idx'] ?>">
<input type="hidden" name="token" value="">

<h3 class="nx-tit1 lh30 mb" style="margin-top:0px"><a href="./place.list.php?<?php echo($qstr)?>" class="nx-btn-b3 fR ml15">뒤로</a>장소 등록</h3>

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
			<select name="la_idx" id="la_idx" required>
				<option value="">지역 선택</option>
			<?php
			for ($i=0; $row=sql_fetch_array($DB_area); $i++) { ?>
				<option value="<?php echo $row['la_idx']?>" <?php if($row['la_idx'] == $place['la_idx']) { echo('selected'); }?>><?php echo $row['la_name']?></option>
			<?php } ?>
			</select>
			<span class="ico_select"></span>
		</span>
	</td>
</tr>

<tr style="<?php echo ($member['mb_level'] > 5) ? '' : 'display: none' ?>">
	<th scope="row"><label for="lp_name">장소명<strong class="sound_only">필수</strong></label></th>
	<td colspan="2">
		<input type="text" name="lp_name" value="<?php echo get_text($place['lp_name']) ?>" id="lp_name" required class="nx-ips1 wm required" size="80" maxlength="120">
	</td>
</tr>


<tr style="<?php echo ($member['mb_level'] > 5) ? '' : 'display: none' ?>">
	<th scope="row"><label for="mb_id">시설관리자 ID</label></th>
	<td colspan="2">
		<input type="text" id="mb_id" name="mb_id" value="<?php echo get_text($place['mb_id']) ?>" class="nx-ips1 ws mr10" maxlength="20">
		<span class="nx-tip dsIB vaM">한개만 지정 가능합니다.</span>
	</td>
</tr>

<tr>
	<th scope="row"><label for="lp_tel">대표번호</label></th>
	<td colspan="2">
		<input type="text" name="lp_tel" value="<?php echo get_text($place['lp_tel']) ?>" id="lp_tel" class="nx-ips1 ws" size="80" maxlength="120">
	</td>
</tr>

<tr>
	<th scope="row"><label for="lp_email">대표메일</label></th>
	<td colspan="2">
		<input type="text" name="lp_email" value="<?php echo get_text($place['lp_email']) ?>" id="lp_email" class="nx-ips1 wm" size="80" maxlength="120">
	</td>
</tr>

<tr>
	<th scope="row"><label for="lp_address">주소<strong class="sound_only">필수</strong></label></th>
	<td colspan="2">
		<input type="text" name="lp_address" value="<?php echo get_text($place['lp_address']) ?>" id="lp_address" required class="required nx-ips1 wl" size="80" maxlength="120">
	</td>
</tr>

<tr>
	<th scope="row"><label for="lp_intro">소개</label></th>
	<td>
		<?php
		$placehold_txt = "※ 한글(hwp)에 있는 것을 그대로 복사해 붙여 넣으면 웹사이트에 예상하지 못한 영향을 줄 수 있습니다. 한글에서 복사한 내용을 메모장 등에 한번 옮겼다가 에디터에 붙여 주세요.<br>※ 에디터에서 경기천년체를 사용할 수 있도록 글꼴 추가했습니다. 글꼴 선택 목록을 확인해 주세요.";
		?>
		<?php echo editor_html("lp_intro", (($place['lp_intro'] != '') ? get_text($place['lp_intro'], 0) : $placehold_txt )); ?>
	</td>
</tr>

<tr>
	<th scope="row"><label for="lp_info">정보</label></th>
	<td>
		<?php
		$placehold_txt = "※ 한글(hwp)에 있는 것을 그대로 복사해 붙여 넣으면 웹사이트에 예상하지 못한 영향을 줄 수 있습니다. 한글에서 복사한 내용을 메모장 등에 한번 옮겼다가 에디터에 붙여 주세요.<br>※ 에디터에서 경기천년체를 사용할 수 있도록 글꼴 추가했습니다. 글꼴 선택 목록을 확인해 주세요.";
		?>
		<?php echo editor_html("lp_info", (($place['lp_info'] != '') ? get_text($place['lp_info'], 0) : $placehold_txt )); ?>
	</td>
</tr>

<tr>
	<th scope="row"><label for="lp_use_yn">On Off<strong class="sound_only">필수</strong></label></th>
	<td colspan="2">
		<input type="radio" name="lp_use_yn" id="lp_use_yn_Y" value="Y" required <?php if($place['lp_use_yn'] == 'Y') echo('checked');?> class="radio1"><label for="lp_use_yn_Y"><span class="radbox"><span></span></span><span class="txt">On</span></label>
		&nbsp;&nbsp;&nbsp;
		<input type="radio" name="lp_use_yn" id="lp_use_yn_N" value="N" required <?php if($place['lp_use_yn'] == 'N') echo('checked');?> class="radio1"><label for="lp_use_yn_N"><span class="radbox"><span></span></span><span class="txt">Off</span></label>
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
			$thumb = thumbnail($place_file['bf_file'], G5_PATH."/data/file/local_place", G5_PATH."/data/file/local_place", 320, 180, true);
			$himg_str = '<img src="/data/file/local_place/'.$thumb.'" alt="'.htmlspecialchars($place_file['bf_source']).'" class="img" />';
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
	<?php
	# 시설관리자는 삭제 불가
	if($w != '' && $member['mb_level'] > 5) {
		?>
		<div class="fL">
			<a href="javascript:onclDel();" class="nx-btn-b4">삭제</a>
			<!-- <input formnovalidate type="submit" name="act_button" class="nx-btn-b4" value="삭제" onclick="document.pressed=this.value"> -->
		</div>
		<?php
	}
	?>
	<div class="fR">
		<!-- <input type="submit" value="확인" class="nx-btn-b2" accesskey="s"> -->
		<a href="javascript:onsu();" class="nx-btn-b2">확인</a>
		<a href="./place.list.php?<?php echo($qstr)?>" class="nx-btn-b3">목록</a>
	</div>
</div>

</form>

<script>
var onclDel = function() {
	if (confirm("삭제된 정보는 복구가 불가능 합니다.\n\n삭제하시겠습니까?"))
	{
		var form = new FormData();
		form.append('lp_idx', $('#lp_idx').val());

		$.ajax({
			url: 'place.delProc.php',
			type: 'POST',
			dataType: 'json',
			data: form,
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
			// alert(a.statusText+' ('+a.status+')');
			alert(a.responseText);
		});
	}
}

function onsu() {
	var _t = $('#la_idx');
	if (_t.val() == '') {
		alert("지역 정보를 선택해 주세요.");
		_t.focus();
		return;
	}

	var _t = $('#lp_name');
	if (_t.val() == '') {
		alert("장소명 정보를 입력해 주세요.");
		_t.focus();
		return;
	}

	/*
	var _t = $('#mb_id');
	if (_t.val() == '') {
		alert("시설관리자 ID 정보를 입력해 주세요.");
		_t.focus();
		return;
	}
	*/

	var _t = $('#lp_tel');
	if (_t.val() == '') {
		alert("대표번호 정보를 입력해 주세요.");
		_t.focus();
		return;
	}

	var _t = $('#lp_email');
	if (_t.val() == '') {
		alert("대표메일 정보를 입력해 주세요.");
		_t.focus();
		return;
	}

	var _t = $('#lp_address');
	if (_t.val() == '') {
		alert("주소 정보를 입력해 주세요.");
		_t.focus();
		return;
	}

	if ($(':radio[name="lp_use_yn"]:checked').length <= 0) {
		alert("On/Off 정보를 선택해 주세요.");
		$('#lp_use_yn_Y').focus();
		return;
	}

	<?php echo get_editor_js("lp_intro"); ?>
	<?php echo get_editor_js("lp_info"); ?>

	document.fboardform.submit();

	return;
}
</script>

<?php
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
