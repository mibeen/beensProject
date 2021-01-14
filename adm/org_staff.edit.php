<?php
	$sub_menu = "990310";
	include_once('_common.php');

	include_once('org_staff.err.php');

	auth_check($auth[$sub_menu], "w");

	$g5[title] = "조직도-직원";
	include_once('admin.head.php');


	$OS_IDX = $_GET['OS_IDX'];


	# get data
	$sql = "Select OS.*"
		. "		From ORG_STAFF As OS"
		. "	Where OS.OS_DDATE is null And OS.OS_IDX = '" . mres($OS_IDX) . "'"
		. "	Limit 1"
		;
	$db1 = sql_query($sql);
	$db_os = sql_fetch_array($db1);
?>

<form id="frmEdit" name="frmEdit" enctype="multipart/form-data" method="post" onsubmit="return false;">
<input type="submit" style="width:1px;height:1px;position:absolute;top:-10000px;left:-10000px" />
<input type="hidden" id="SC_OP_PARENT_IDX" name="SC_OP_PARENT_IDX" value="<?php echo($SC_OP_PARENT_IDX)?>" />
<input type="hidden" id="SC_OP_IDX" name="SC_OP_IDX" value="<?php echo($SC_OP_IDX)?>" />
<input type="hidden" id="SC_WORD" name="SC_WORD" value="<?php echo($SC_WORD)?>" />
<input type="hidden" id="OS_IDX" name="OS_IDX" value="<?php echo($db_os['OS_IDX'])?>" />

<div class="taR mb10">
	<a href="javascript:onclCancel();" class="nx-btn-b3">뒤로</a>
</div>

<table border="0" cellpadding="0" cellspacing="0" class="nx-t-read1">
	<colgroup>
		<col width="130"><col width="">
	</colgroup>
	<tbody>
		<tr>
			<th>본부</th>
			<td>
				<span class="nx-slt">
					<select id="OP_PARENT_IDX" name="OP_PARENT_IDX" style="min-width: 140px" onchange="get_cate();">
						<option value="S"<?php if ($db_os['OP_GUBUN'] == "S") echo(' selected');?>>이사장</option>
						<option value="A"<?php if ($db_os['OP_GUBUN'] == "A") echo(' selected');?>>이사회</option>
						<option value="B"<?php if ($db_os['OP_GUBUN'] == "B") echo(' selected');?>>원장</option>
						<option value="C"<?php if ($db_os['OP_GUBUN'] == "C") echo(' selected');?>>GSEEK 캠퍼스 단장</option>
						<?php
						$sql = "Select OP.OP_IDX, OP.OP_NAME"
							."		From ORG_PART As OP"
							."	Where OP.OP_DDATE is null And OP.OP_PARENT_IDX is null And OP.OP_PARENT2_IDX is not null"
							."	Order By OP.OP_SEQ Asc"
							;
						$db2 = sql_query($sql);
						while ($rs2 = sql_fetch_array($db2)) {
							?>
						<option value="<?php echo($rs2['OP_IDX'])?>"<?php if ($rs2['OP_IDX'] == $db_os['OP_PARENT_IDX']) echo(' selected');?>><?php echo(F_hsc($rs2['OP_NAME']))?></option>
							<?php
						}
						?>
						<option value="35"<?php if ($db_os['OP_PARENT_IDX'] == "35") echo(' selected');?>>무소속</option>
					</select>
					<span class="ico_select"></span>
				</span>
			</td>
		</tr>
		<tr id="tr_OP_IDX"<?php if (CHK_NUMBER($db_os['OP_PARENT_IDX']) == 0) echo(' style="display:none"')?>>
			<th>부서</th>
			<td>
				<span class="nx-slt">
					<select id="OP_IDX" name="OP_IDX" style="min-width: 140px" onchange="get_seq();">
						<option value="0">본부장</option>
						<?php
						$sql = "Select OP.OP_IDX, OP.OP_NAME"
							."		From ORG_PART As OP"
							."	Where OP.OP_DDATE is null"
							."		And OP.OP_PARENT_IDX = '" . mres($db_os['OP_PARENT_IDX']) . "'"
							."	Order By OP.OP_SEQ Asc, OP.OP_IDX Asc"
							;
						$db2 = sql_query($sql);
						while ($rs2 = sql_fetch_array($db2)) {
							?>
						<option value="<?php echo($rs2['OP_IDX'])?>"<?php if ($rs2['OP_IDX'] == $db_os['OP_IDX']) echo(' selected');?>><?php echo(F_hsc($rs2['OP_NAME']))?></option>
							<?php
						}
						?>
					</select>
					<span class="ico_select"></span>
				</span>
			</td>
		</tr>
		<tr>
			<th>순서</th>
			<td>
				<span class="nx-slt">
					<select id="OS_SEQ" name="OS_SEQ">
						<?php
						$sql = "Select OS.OS_IDX"
							."		From ORG_STAFF As OS"
							."	Where OS_DDATE is null And OP_IDX = '" . mres($db_os['OP_IDX']) . "'"
							;
						$db2 = sql_query($sql);
						$s = 1;
						while ($rs2 = sql_fetch_array($db2)) {
							?>
						<option value="<?php echo($s)?>" <?php if ($db_os['OS_SEQ'] == $s) echo('selected');?>><?php echo($s)?></option>
							<?php
							$s++;
						}
						?>
					</select>
					<span class="ico_select"></span>
				</span>
			</td>
		</tr>
		<tr>
			<th>직위</th>
			<td><input type="text" id="OS_POSITION" name="OS_POSITION" value="<?php echo(F_hsc($db_os['OS_POSITION']))?>" maxlength="100" class="nx-ips1 ws" /></td>
		</tr>
		<tr>
			<th>이름</th>
			<td><input type="text" id="OS_NAME" name="OS_NAME" value="<?php echo(F_hsc($db_os['OS_NAME']))?>" maxlength="100" class="nx-ips1 ws" /></td>
		</tr>
		<tr>
			<th>담당업무</th>
			<td><input type="text" id="OS_WORK" name="OS_WORK" value="<?php echo(F_hsc($db_os['OS_WORK']))?>" maxlength="200" class="nx-ips1 wl" /></td>
		</tr>
		<tr>
			<th>연락처</th>
			<td><input type="text" id="OS_TEL" name="OS_TEL" value="<?php echo(F_hsc($db_os['OS_TEL']))?>" maxlength="20" class="nx-ips1 ws" /></td>
		</tr>
		<tr>
			<th>이메일</th>
			<td><input type="text" id="OS_EMAIL" name="OS_EMAIL" value="<?php echo(F_hsc($db_os['OS_EMAIL']))?>" maxlength="100" class="nx-ips1 wm" /></td>
		</tr>
	</tbody>
</table>

<div class="mt10" style="overflow:hidden">
	<div class="fL">
		<a href="javascript:onclDel();" class="nx-btn-b4">삭제</a>
	</div>
	<div class="fR">
		<a href="javascript:onclOnsu();" class="nx-btn-b2">등록</a>
		<a href="javascript:onclCancel();" class="nx-btn-b3">뒤로</a>
	</div>
</div>

</form>

<script>
//<![CDATA[
var onclCancel = function() {
	window.location.href="org_staff.list.php?<?php echo($phpTail)?>";
}
var onclOnsu = function() {
	if ($('#OS_SEQ').val() == '') {
		alert("순서 정보를 선택해 주세요.");
		$('#OS_SEQ').focus();
		return;
	}

	if ($('#OP_PARENT_IDX').val() == '') {
		alert("본부 정보를 선택해 주세요.");
		$('#OP_PARENT_IDX').focus();
		return;
	}

	if ($('#OS_POSITION').val() == '') {
		alert("직위 정보를 입력해 주세요.");
		$('#OS_POSITION').focus();
		return;
	}

	if ($('#OS_NAME').val() == '') {
		alert("이름 정보를 입력해 주세요.");
		$('#OS_NAME').focus();
		return;
	}

	if (confirm("입력하신 정보로 진행하시겠습니까?")) {
		var f = new FormData($('#frmEdit')[0]);

		$.ajax({
			url: 'org_staff.editProc.php',
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
			// alert(a.responseText);
		});
	}
}
var onclDel = function()
{
	if (confirm("삭제된 정보는 복구가 불가능 합니다.\n\n삭제하시겠습니까?"))
	{
		var form = new FormData();
		form.append('OS_IDX', $('#OS_IDX').val());

		$.ajax({
			url: 'org_staff.delProc.php', 
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
			if (json.redir) window.location.href = json.redir+'?SC_OP_PARENT_IDX='+$('#SC_OP_PARENT_IDX').val()+'&SC_OP_IDX='+$('#SC_OP_IDX').val();

		})
		.fail(function(a, b, c) {
			alert(a.statusText+' ('+a.status+')\n\n'+a.responseText);
			// alert(a.responseText);
		});
	}
}
function get_cate() {
	if (isNaN($('#OP_PARENT_IDX').val())) {
		$('#tr_OP_IDX').hide();
	}
	else {
		$('#tr_OP_IDX').show();

		$.ajax({
			url: 'org_part.cate.list.php', type: 'POST', dataType: 'json',
			data: {OP_IDX: $('#OP_PARENT_IDX').val()}
		})
		.done(function(json) {
			if (!json.success) {
				if (json.msg) alert(json.msg);
			}
			else if (json.success) {
				if ($("#OP_PARENT_IDX").val() == 35) $("#OP_IDX").html('');
				else $("#OP_IDX").html('').append('<option value="">본부장</option>');

				if (json.itms)
				{
					for (var i = 0; i < json.itms.length; i++)
					{
						itm = json.itms[i];

						$("#OP_IDX").append('<option value="' + itm.OP_IDX + '">' + itm.OP_NAME + '</option>');
					}
				}
			}
			else {
				alert("처리 도중 오류가 발생하였습니다.");
			}
		})
		.fail(function(a, b, c) {
			alert(a.statusText+' ('+a.status+')\n\n'+a.responseText);
			// alert("Request failed: " + a.responseText);
		});
	}

	setTimeout(function() {
		get_seq();
	}, 100);
}
function get_seq(OP_PARENT_IDX, OP_IDX) {
	$.ajax({
		url: 'org_staff.seq.php', type: 'POST', dataType: 'json',
		data: {OP_PARENT_IDX: $('#OP_PARENT_IDX').val(), OP_IDX: $('#OP_IDX').val()}
	})
	.done(function(json) {
		if (!json.success) {
			if (json.msg) alert(json.msg);
		}
		else if (json.success) {
			if (json.itms)
			{
				$("#OS_SEQ").html('');

				for (var i = 1; i <= json.itms.length; i++)
				{
					$("#OS_SEQ").append('<option value="' + i + '">' + i + '</option>');
				}
			} else {
				$("#OS_SEQ").html('<option value="1">1</option>');
			}
		}
		else {
			alert("처리 도중 오류가 발생하였습니다.");
		}
	})
	.fail(function(a, b, c) {
		alert(a.statusText+' ('+a.status+')\n\n'+a.responseText);
		// alert("Request failed: " + a.responseText);
	});
}
//]]>
</script>

<?php
	//마지막 인클루드
	include_once G5_ADMIN_PATH."/admin.tail.php";
?>
