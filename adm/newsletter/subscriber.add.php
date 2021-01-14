<?php
	$sub_menu = "990410";
	include_once('./_common.php');

	auth_check($auth[$sub_menu], "r");


	$g5[title] = "뉴스레터 구독자";
	include_once('../admin.head.php');
	include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');
?>
<script>
//<![CDATA[
function onsu_Add() {
	if($("#NM_NAME").val().replace(/(^\s*)|(\s*$)/g, "") == "") {
		alert("이름을 입력해 주세요.");
		$("#NM_NAME").focus();
		return false;
	}

	if($("#NM_EMAIL").val().replace(/(^\s*)|(\s*$)/g, "") == "") {
		alert("이메일을 입력해 주세요.");
		$("#NM_EMAIL").focus();
		return false;
	}

	if(confirm("저장하시겠습니까?")) {
		var f = new FormData($("#frmAdd")[0]);

		$.ajax({
			url: 'subscriber.addProc.php',
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

	return false;
}

function oncl_Add() {
	$("#frmAdd").submit();
}
//]]>
</script>

<div class="taR mb10">
	<a href="subscriber.list.php?<?php echo($phpTail)?>page=<?php echo($page)?>" class="nx-btn-b3">뒤로</a>
</div>

<form id="frmAdd" name="frmAdd" enctype="multipart/form-data" onsubmit="return onsu_Add();">
	<input type="submit" style="width:1px;height:1px;position:absolute;top:-10000px;left:-10000px" />

	<table border="0" cellpadding="0" cellspacing="0" class="nx-t-read1">
		<colgroup>
			<col width="130"><col width="">
		</colgroup>
		<tbody>
			<tr>
				<th>이름</th>
				<td><input type="text" id="NM_NAME" name="NM_NAME" maxlength="200" class="nx-ips1 ws" /></td>
			</tr>
			<tr>
				<th>이메일</th>
				<td><input type="text" id="NM_EMAIL" name="NM_EMAIL" maxlength="200" class="nx-ips1 wl" /></td>
			</tr>
		</tbody>
	</table>

	<div class="taR mt10">
		<a href="javascript:oncl_Add();" class="nx-btn-b2">저장</a>
		<a href="subscriber.list.php?<?php echo($phpTail)?>page=<?php echo($page)?>" class="nx-btn-b3">뒤로</a>
	</div>
</form>

<?php
	//마지막 인클루드
	include_once G5_ADMIN_PATH."/admin.tail.php";
?>
