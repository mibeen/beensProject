<?php
	$sub_menu = "990400";
	include_once('./_common.php');

	auth_check($auth[$sub_menu], "r");


	$sql = "Select Count(*) As cnt"
		."	From NX_NEWSLETTER_MEMBER As NM"
		."	Where NM.NM_DDATE is null"
		;
	$row = sql_fetch($sql);
	$total_count = $row['cnt'];


	$g5[title] = "뉴스레터 발송";
	include_once('../admin.head.php');
?>
<script>
//<![CDATA[
function onsu_Add() {
	if($("#NS_TITLE").val().replace(/(^\s*)|(\s*$)/g, "") == "") {
		alert("제목을 입력해 주세요.");
		$("#NS_TITLE").focus();
		return false;
	}

	if($("#NS_CONTENT").val().replace(/(^\s*)|(\s*$)/g, "") == "") {
		alert("내용을 입력해 주세요.");
		$("#NS_CONTENT").focus();
		return false;
	}

	if(confirm(($("#MODE").val() == "SEND") ? "발송하시겠습니까?" : "저장하시겠습니까?")) {
		var f = new FormData($("#frmAdd")[0]);

		$.ajax({
			url: 'newsletter.addProc.php',
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

function oncl_Add(MODE) {
	$("#MODE").val(MODE);
	$("#frmAdd").submit();
}
//]]>
</script>

<div class="taR mb10">
	<a href="newsletter.list.php?<?php echo($phpTail)?>page=<?php echo($page)?>" class="nx-btn-b3">뒤로</a>
</div>

<form id="frmAdd" name="frmAdd" enctype="multipart/form-data" onsubmit="return onsu_Add();">
	<input type="submit" onclick="$('#MODE').val('');" style="width:1px;height:1px;position:absolute;top:-10000px;left:-10000px" />
	<input type="hidden" id="MODE" name="MODE" value="" />

	<table border="0" cellpadding="0" cellspacing="0" class="nx-t-read1">
		<colgroup>
			<col width="130"><col width="">
		</colgroup>
		<tbody>
			<tr>
				<th>구독자</th>
				<td><?php echo(number_format($total_count))?>명</td>
			</tr>
			<tr>
				<th>제목</th>
				<td><input type="text" id="NS_TITLE" name="NS_TITLE" maxlength="200" class="nx-ips1 wl" /></td>
			</tr>
			<tr>
				<th>내용등록</th>
				<td><textarea id="NS_CONTENT" name="NS_CONTENT" placeholder="이 부분에 이메일 html을 넣습니다." class="nx-ips1" style="min-height: 500px;"></textarea></td>
			</tr>
		</tbody>
	</table>

	<div class="taR mt10">
		<a href="javascript:oncl_Add('');" class="nx-btn-b2">저장</a>
		<a href="javascript:oncl_Add('SEND');" class="nx-btn-b2">보내기</a>
		<a href="newsletter.list.php?<?php echo($phpTail)?>page=<?php echo($page)?>" class="nx-btn-b3">뒤로</a>
	</div>
</form>

<?php
	//마지막 인클루드
	include_once G5_ADMIN_PATH."/admin.tail.php";
?>
