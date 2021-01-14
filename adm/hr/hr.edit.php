<?php
	$sub_menu = "985100";
	include_once('./_common.php');
	include_once('./hr.err.php');
	include_once G5_EDITOR_PATH.'/'.$config['cf_editor'].'/editor.lib.php';

	auth_check($auth[$sub_menu], "w");


	# get data
	$sql = "Select EP.*"
		. " , (Select GROUP_CONCAT(DISTINCT mb_id SEPARATOR ', ') From NX_EVENT_HR_MEMBER Where EP_IDX = " . mres($EP_IDX) . " Group By EP_IDX) As EPM_MEMB"
		. " From NX_EVENT_HR As EP"
		. " Where EP.EP_DDATE is null And EP.EP_IDX = '" . mres($EP_IDX) . "' Limit 1";
	$db1 = sql_query($sql);
	$db_ep = sql_fetch_array($db1);


	$g5[title] = "교육강사 수정";
	include_once('../admin.head.php');
?>

<form id="frmEdit" name="frmEdit" method="post" onsubmit="return false;">
<input type="submit" style="width:1px;height:1px;position:absolute;top:-10000px;left:-10000px" />
<input type="hidden" id="EP_IDX" name="EP_IDX" value="<?php echo($EP_IDX)?>" />
<input type="hidden" name="sc_ep_s_date" value="<?php echo($sc_ep_s_date)?>" />
<input type="hidden" name="sc_ep_e_date" value="<?php echo($sc_ep_e_date)?>" />
<input type="hidden" name="ord" value="<?php echo($ord)?>" />
<input type="hidden" name="stx" value="<?php echo($stx)?>" />
<input type="hidden" name="page" value="<?php echo($page)?>" />

<div class="taR mb10">
	<a href="javascript:onclCancel();" class="nx-btn-b3">뒤로</a>
</div>

<table border="0" cellpadding="0" cellspacing="0" class="nx-t-read1">
	<colgroup>
		<col width="130"><col width="">
	</colgroup>
	<tbody>
		<tr>
			<th><span class="red">*</span>제목</th>
			<td><input type="text" id="EP_TITLE" name="EP_TITLE" maxlength="100" class="nx-ips1 wl" value="<?php echo(F_hsc($db_ep['EP_TITLE']))?>" /></td>
		</tr>

		<?php
		$_t = explode('-', $db_ep['EP_S_DATE']);
		$EP_S_DATE1 = $_t[0];
		$EP_S_DATE2 = $_t[1];
		$EP_S_DATE3 = $_t[2];
		unset($_t);
		?>
		<tr>
			<th><span class="red">*</span>사업 시작일</th>
			<td>
				<span class="dsIB mr15">
					<span class="nx-slt">
						<select id="EP_S_DATE1" name="EP_S_DATE1">
							<option value="">년</option>
							<?php
							for ($i = (date('Y') - 1); $i <= (date('Y') + 5); $i++) {
								echo '<option value="'.(int)$i.'" '.(($i == $EP_S_DATE1) ? 'selected' : '').'>'.(int)$i.'</option>';
							}
							?>
						</select>
						<span class="ico_select"></span>
					</span>
					<span class="nx-slt">
						<select id="EP_S_DATE2" name="EP_S_DATE2">
							<option value="">월</option>
							<?php
							for ($i = 1; $i <= 12; $i++) {
								$_i = ($i < 10) ? ('0'.$i) : $i;
								echo '<option value="'.(int)$i.'" '.(($i == $EP_S_DATE2) ? 'selected' : '').'>'.(int)$i.'</option>';
							}
							?>
						</select>
						<span class="ico_select"></span>
					</span>
					<span class="nx-slt">
						<select id="EP_S_DATE3" name="EP_S_DATE3">
							<option value="">일</option>
							<?php
							for ($i = 1; $i <= 31; $i++) {
								$_i = ($i < 10) ? ('0'.$i) : $i;
								echo '<option value="'.(int)$i.'" '.(($i == $EP_S_DATE3) ? 'selected' : '').'>'.(int)$i.'</option>';
							}
							?>
						</select>
						<span class="ico_select"></span>
					</span>
				</span>
			</td>
		</tr>

		<?php
		$_t = explode('-', $db_ep['EP_E_DATE']);
		$EP_E_DATE1 = $_t[0];
		$EP_E_DATE2 = $_t[1];
		$EP_E_DATE3 = $_t[2];
		unset($_t);
		?>
		<tr>
			<th><span class="red">*</span>사업 종료일</th>
			<td>
				<span class="dsIB mr15">
					<span class="nx-slt">
						<select id="EP_E_DATE1" name="EP_E_DATE1">
							<option value="">년</option>
							<?php
							for ($i = (date('Y') - 1); $i <= (date('Y') + 5); $i++) {
								echo '<option value="'.(int)$i.'" '.(($i == $EP_E_DATE1) ? 'selected' : '').'>'.(int)$i.'</option>';
							}
							?>
						</select>
						<span class="ico_select"></span>
					</span>
					<span class="nx-slt">
						<select id="EP_E_DATE2" name="EP_E_DATE2">
							<option value="">월</option>
							<?php
							for ($i = 1; $i <= 12; $i++) {
								$_i = ($i < 10) ? ('0'.$i) : $i;
								echo '<option value="'.(int)$i.'" '.(($i == $EP_E_DATE2) ? 'selected' : '').'>'.(int)$i.'</option>';
							}
							?>
						</select>
						<span class="ico_select"></span>
					</span>
					<span class="nx-slt">
						<select id="EP_E_DATE3" name="EP_E_DATE3">
							<option value="">일</option>
							<?php
							for ($i = 1; $i <= 31; $i++) {
								$_i = ($i < 10) ? ('0'.$i) : $i;
								echo '<option value="'.(int)$i.'" '.(($i == $EP_E_DATE3) ? 'selected' : '').'>'.(int)$i.'</option>';
							}
							?>
						</select>
						<span class="ico_select"></span>
					</span>
				</span>
			</td>
		</tr>
		<tr>
			<th><span class="red">*</span>접근 가능 계정</th>
			<td>
				<?php echo help("복수 등록 시 콤마(,)로 구분하여 등록해주세요.") ?>
				<input type="text" id="EPM_MEMB" name="EPM_MEMB" maxlength="255" class="nx-ips1 wl" value="<?php echo(F_hsc($db_ep['EPM_MEMB']))?>" />
			</td>
		</tr>
		<tr>
			<th>비고</th>
			<td>
				<textarea id="EP_MEMO" name="EP_MEMO" class="nx-ips1" style="min-height:200px;"><?php echo(F_hsc($db_ep['EP_MEMO']))?></textarea>
			</td>
		</tr>
	</tbody>
</table>

<div class="ofH mt10">
	<div class="fR">
		<a href="javascript:onclOnsu();" class="nx-btn-b2">저장</a>
		<a href="javascript:onclCancel();" class="nx-btn-b3">뒤로</a>	
	</div>
</div>

</form>

<script>
//<![CDATA[
var onclCancel = function() {
	window.location.href="hr.list.php?<?php echo($phpTail)?>";
}
var onclOnsu = function() {
	if ($('#EP_TITLE').val() == '') {
		alert("제목 정보를 입력해 주세요.");
		$('#EP_TITLE').focus(); return;
	}

	var _t = $('#EP_S_DATE1');
	if (_t.val() == '') {
		alert("사업 시작일 정보를 선택해 주세요.");
		_t.focus(); return;
	}
	var _t = $('#EP_S_DATE2');
	if (_t.val() == '') {
		alert("사업 시작일 정보를 선택해 주세요.");
		_t.focus(); return;
	}
	var _t = $('#EP_S_DATE3');
	if (_t.val() == '') {
		alert("사업 시작일 정보를 선택해 주세요.");
		_t.focus(); return;
	}

	var _t = $('#EP_E_DATE1');
	if (_t.val() == '') {
		alert("사업 종료일 정보를 선택해 주세요.");
		_t.focus(); return;
	}
	var _t = $('#EP_E_DATE2');
	if (_t.val() == '') {
		alert("사업 종료일 정보를 선택해 주세요.");
		_t.focus(); return;
	}
	var _t = $('#EP_E_DATE3');
	if (_t.val() == '') {
		alert("사업 종료일 정보를 선택해 주세요.");
		_t.focus(); return;
	}

	if ($('#EPM_MEMB').val() == '') {
		alert("접근 가능 계정 정보를 입력해 주세요.");
		$('#EPM_MEMB').focus(); return;
	}

	if (confirm("입력하신 정보로 진행하시겠습니까?")) {
		var f = new FormData($('#frmEdit')[0]);

		$.ajax({
			url: 'hr.editProc.php',
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
			//alert(a.responseText);
		});
	}
	return;
}
//]]>
</script>

<?php
	//마지막 인클루드
	include_once G5_ADMIN_PATH."/admin.tail.php";
?>
