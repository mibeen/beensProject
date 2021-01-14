<?php
	$sub_menu = "990300";
	include_once('_common.php');

	include_once('org_part.err.php');

	auth_check($auth[$sub_menu], "w");

	$g5[title] = "조직도-부서";
	include_once('admin.head.php');


	$OP_IDX = $_GET['OP_IDX'];


	# get data
	$sql = "Select OP.* From ORG_PART As OP Where OP.OP_DDATE is null And OP.OP_IDX = '" . mres($OP_IDX) . "' Limit 1";
	$db1 = sql_query($sql);
	$db_op = sql_fetch_array($db1);
?>

<form id="frmEdit" name="frmEdit" enctype="multipart/form-data" method="post" onsubmit="return false;">
<input type="submit" style="width:1px;height:1px;position:absolute;top:-10000px;left:-10000px" />
<input type="hidden" id="SC_OP_PARENT_IDX" name="SC_OP_PARENT_IDX" value="<?php echo($SC_OP_PARENT_IDX)?>" />
<input type="hidden" id="SC_WORD" name="SC_WORD" value="<?php echo($SC_WORD)?>" />
<input type="hidden" id="OP_IDX" name="OP_IDX" value="<?php echo($db_op['OP_IDX'])?>" />

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
					<select id="OP_PARENT_IDX" name="OP_PARENT_IDX" style="min-width: 140px" onchange="get_seq()">
						<?php
						$sql = "Select OP.OP_IDX, OP.OP_NAME"
							."		From ORG_PART As OP"
							."	Where OP.OP_DDATE is null And OP.OP_PARENT_IDX is null And OP.OP_PARENT2_IDX is not null"
							."	Order By OP.OP_SEQ Asc"
							;
						$db2 = sql_query($sql);
						while ($rs2 = sql_fetch_array($db2)) {
							?>
						<option value="<?php echo($rs2['OP_IDX'])?>"<?php if ($rs2['OP_IDX'] == $db_op['OP_PARENT_IDX']) echo(' selected');?>><?php echo(F_hsc($rs2['OP_NAME']))?></option>
							<?php
						}
						?>
						<option value="35" <?php if ($db_op['OP_PARENT_IDX'] == "35") echo('selected');?>>무소속</option>
					</select>
					<span class="ico_select"></span>
				</span>
			</td>
		</tr>
		<tr>
			<th>순서</th>
			<td>
				<span class="nx-slt">
					<select id="OP_SEQ" name="OP_SEQ">
						<?php
						$sql = "Select OP.OP_IDX"
							."		From ORG_PART As OP"
							."	Where OP.OP_DDATE is null And OP.OP_PARENT_IDX = '" . mres($db_op['OP_PARENT_IDX']) . "'"
							;
						$db2 = sql_query($sql);
						$s = 1;
						while ($rs2 = sql_fetch_array($db2)) {
							?>
						<option value="<?php echo($s)?>" <?php if ($db_op['OP_SEQ'] == $s) echo('selected');?>><?php echo($s)?></option>
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
			<th>부서명</th>
			<td><input type="text" id="OP_NAME" name="OP_NAME" value="<?php echo(F_hsc($db_op['OP_NAME']))?>" maxlength="100" class="nx-ips1 wm" /></td>
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
	window.location.href="org_part.list.php?<?php echo($phpTail)?>";
}
var onclOnsu = function() {
	if ($('#OP_SEQ').val() == '') {
		alert("순서 정보를 입력해 주세요.");
		$('#OP_SEQ').focus();
		return;
	}

	if ($('#OP_PARENT_IDX').val() == '') {
		alert("본부명 정보를 입력해 주세요.");
		$('#OP_PARENT_IDX').focus();
		return;
	}
	
	if ($('#OP_NAME').val() == '') {
		alert("부서명 정보를 입력해 주세요.");
		$('#OP_NAME').focus();
		return;
	}

	if (confirm("입력하신 정보로 진행하시겠습니까?")) {
		var f = new FormData($('#frmEdit')[0]);

		$.ajax({
			url: 'org_part.editProc.php',
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
			alert(a.responseText);
		});
	}
}
var onclDel = function()
{
	if (confirm("삭제된 정보는 복구가 불가능 합니다.\n\n삭제하시겠습니까?"))
	{
		var form = new FormData();
		form.append('OP_IDX', $('#OP_IDX').val());

		$.ajax({
			url: 'org_part.delProc.php', 
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
			if (json.redir) window.location.href = json.redir+'?SC_OP_PARENT_IDX='+$('#SC_OP_PARENT_IDX').val();

		})
		.fail(function(a, b, c) {
			alert(a.responseText);
		});
	}
}

function get_seq() {
	$.ajax({
		url: 'org_part.seq.php', type: 'POST', dataType: 'json',
		data: {OP_PARENT_IDX: $('#OP_PARENT_IDX').val()}
	})
	.done(function(json) {
		if (!json.success) {
			if (json.msg) alert(json.msg);
		}
		else if (json.success) {
			if (json.itms)
			{
				$("#OP_SEQ").html('');

				for (var i = 1; i <= json.itms.length; i++)
				{
					$("#OP_SEQ").append('<option value="' + i + '">' + i + '</option>');
				}
			} else {
				$("#OP_SEQ").html('<option value="1">1</option>');
			}
		}
		else {
			alert("처리 도중 오류가 발생하였습니다.");
		}
	})
	.fail(function(a, b, c) {
		alert(a.statusText+' ('+a.status+')\n\n'+a.responseText);
		//alert("Request failed: " + a.responseText);
	});
}
//]]>
</script>

<?php
	//마지막 인클루드
	include_once G5_ADMIN_PATH."/admin.tail.php";
?>
