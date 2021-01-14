<?php
	$sub_menu = "980100";
	include_once('./_common.php');
	include_once('./project.err.php');
	include_once G5_EDITOR_PATH.'/'.$config['cf_editor'].'/editor.lib.php';

	auth_check($auth[$sub_menu], "w");


	$g5[title] = "공모사업 등록";
	include_once('../admin.head.php');
?>

<link rel="stylesheet" href="/js/jquery-ui-1.12.1.custom/jquery-ui.css">
<script src="/js/jquery-ui-1.12.1.custom/jquery-ui.js"></script>
<script src="/js/jquery-ui-1.12.1.custom/datepicker-ko.js"></script>

<form id="frmAdd" name="frmAdd" method="post" onsubmit="return false;">
<input type="submit" style="width:1px;height:1px;position:absolute;top:-10000px;left:-10000px" />

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
			<td><input type="text" id="EP_TITLE" name="EP_TITLE" maxlength="100" class="nx-ips1 wl" /></td>
		</tr>
		<tr>
			<th><span class="red">*</span>사업 시작일</th>
			<td>
				<!-- <span class="dsIB mr15">
					<span class="nx-slt">
						<select id="EP_S_DATE1" name="EP_S_DATE1">
							<option value="">년</option>
							<?php
							for ($i = (date('Y') - 1); $i <= (date('Y') + 5); $i++) {
								echo '<option value="'.(int)$i.'">'.(int)$i.'</option>';
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
								echo '<option value="'.$_i.'">'.$_i.'</option>';
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
								echo '<option value="'.$_i.'">'.$_i.'</option>';
							}
							?>
						</select>
						<span class="ico_select"></span>
					</span>
				</span> -->
				<input type="text" name="EP_S_DATE" id="EP_S_DATE" value="" class="nx-ips1 ws">
			</td>
		</tr>
		<tr>
			<th><span class="red">*</span>사업 종료일</th>
			<td>
				<!-- <span class="dsIB mr15">
					<span class="nx-slt">
						<select id="EP_E_DATE1" name="EP_E_DATE1">
							<option value="">년</option>
							<?php
							for ($i = (date('Y') - 1); $i <= (date('Y') + 5); $i++) {
								echo '<option value="'.(int)$i.'">'.(int)$i.'</option>';
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
								echo '<option value="'.$_i.'">'.$_i.'</option>';
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
								echo '<option value="'.$_i.'">'.$_i.'</option>';
							}
							?>
						</select>
						<span class="ico_select"></span>
					</span>
				</span> -->
				<input type="text" name="EP_E_DATE" id="EP_E_DATE" value="" class="nx-ips1 ws">

				<script>
				$(function() {
					$.datepicker.setDefaults( $.datepicker.regional[ "ko" ] );

					var dateFormat= "yy-mm-dd",
						from = $( "#EP_S_DATE" )
								.datepicker({
									defaultDate: "+1w",
									changeMonth: true,
									numberOfMonths: 1,
									dateFormat: "yy-mm-dd"

								})
								.on( "change", function() {
									to.datepicker( "option", "minDate", getDate( this ) );
								}),
						to = $( "#EP_E_DATE" ).datepicker({
									defaultDate: "+1w",
									changeMonth: true,
									numberOfMonths: 1,
									dateFormat: "yy-mm-dd"

								})
								.on( "change", function() {
									from.datepicker( "option", "maxDate", getDate( this ) );
								});

					function getDate( element ) {
						var date;
						try {
							date = $.datepicker.parseDate( dateFormat, element.value );
						} catch( error ) {
							date = null;
						}

						return date;
					}
					
				})
				</script>
			</td>
		</tr>
		<tr>
			<th><span class="red">*</span>접근 가능 계정</th>
			<td>
				<?php echo help("복수 등록 시 콤마(,)로 구분하여 등록해주세요.") ?>
				<input type="text" id="EPM_MEMB" name="EPM_MEMB" maxlength="255" class="nx-ips1 wl" />
			</td>
		</tr>
		<tr>
			<th>비고</th>
			<td>
				<textarea id="EP_MEMO" name="EP_MEMO" class="nx-ips1" style="min-height:200px;"></textarea>
			</td>
		</tr>
	</tbody>
</table>

<div class="ofH mt10">
	<div class="fR">
		<a href="javascript:onclOnsu();" class="nx-btn-b2">등록</a>
		<a href="javascript:onclCancel();" class="nx-btn-b3">뒤로</a>	
	</div>
</div>

</form>

<script>
//<![CDATA[
var onclCancel = function() {
	window.location.href="project.list.php";
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
		var f = new FormData($('#frmAdd')[0]);

		$.ajax({
			url: 'project.addProc.php',
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
