<?php
	$sub_menu = "990300";
	include_once('_common.php');

	include_once('org_part.err.php');

	auth_check($auth[$sub_menu], "w");

	$g5[title] = "조직도-부서";
	include_once('admin.head.php');
?>

<form id="frmAdd" name="frmAdd" enctype="multipart/form-data" method="post" onsubmit="return false;">
<input type="submit" style="width:1px;height:1px;position:absolute;top:-10000px;left:-10000px" />
<input type="hidden" id="SC_OP_PARENT_IDX" name="SC_OP_PARENT_IDX" value="<?php echo($SC_OP_PARENT_IDX)?>" />
<input type="hidden" id="SC_WORD" name="SC_WORD" value="<?php echo($SC_WORD)?>" />

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
					<select id="OP_PARENT_IDX" name="OP_PARENT_IDX" style="min-width: 140px" onchange="get_seq();">
						<option value="">선택해주세요</option>
						<?php
						$sql = "Select OP.OP_IDX, OP.OP_NAME"
							."		From ORG_PART As OP"
							."	Where OP.OP_DDATE is null And OP.OP_PARENT_IDX is null And OP.OP_PARENT2_IDX is not null"
							."	Order By OP.OP_SEQ Asc, OP.OP_IDX Asc"
							;
						$db2 = sql_query($sql);
						while ($rs2 = sql_fetch_array($db2)) {
							?>
						<option value="<?php echo($rs2['OP_IDX'])?>"><?php echo(F_hsc($rs2['OP_NAME']))?></option>
							<?php
						}
						?>
						<option value="35">무소속</option>
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
					</select>
					<span class="ico_select"></span>
				</span>
			</td>
		</tr>
		<tr>
			<th>부서명</th>
			<td><input type="text" id="OP_NAME" name="OP_NAME" maxlength="100" class="nx-ips1 wm" /></td>
		</tr>
	</tbody>
</table>

<div class="taR mt10">
	<a href="javascript:onclOnsu();" class="nx-btn-b2">등록</a>
	<!-- <a href="javascript:void(0);" onclick="onclOnsu();" class="nx-btn-b2">등록</a> -->
	<a href="javascript:onclCancel();" class="nx-btn-b3">뒤로</a>
</div>

</form>

<script>
//<![CDATA[
var onclCancel = function() {
	window.location.href="org_part.list.php?<?php echo($phpTail)?>";
}
var onclOnsu = function() {
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
		var f = new FormData($('#frmAdd')[0]);

		$.ajax({
			url: 'org_part.addProc.php',
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

				for (var i = 1; i <= json.itms.length + 1; i++)
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
