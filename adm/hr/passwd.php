<?php
	include_once('_common.php');


	# 시설 관리자 권한 부여
	if ($member['mb_level'] == 6) {
		$auth['985100'] = 'r';
	}


	$g5['title'] = "비밀번호변경";
	include_once(G5_ADMIN_PATH . '/admin.head.php');
?>

<form id="frmPw" name="frmPw" method="post" onsubmit="return false;">
<input type="submit" style="width:1px;height:1px;position:absolute;top:-10000px;left:-10000px" />

<table border="0" cellpadding="0" cellspacing="0" class="nx-t-read1">
	<colgroup>
		<col width="130"><col width="">
	</colgroup>
	<tbody>
		<tr>
			<th>현재 비밀번호</th>
			<td><input type="password" id="mb_password" name="mb_password" maxlength="20" class="nx-ips1 wm" /></td>
		</tr>
		<tr>
			<th>새 비밀번호</th>
			<td><input type="password" id="new_password" name="new_password" minlength="9" maxlength="20" class="nx-ips1 wm" /> <span class="nx-tip">9자 이상. 영대문자, 영소문자, 숫자, 특수문자 중 3종류 이상 조합.</span></td>
		</tr>
		<tr>
			<th>새 비밀번호 확인</th>
			<td><input type="password" id="new_password_re" name="new_password_re" minlength="9" maxlength="20" class="nx-ips1 wm" /></td>
		</tr>
	</tbody>
</table>
</form>

<div class="taR mt10">
	<a href="javascript:onclOnsu();" class="nx-btn-b2">변경</a>
	<a href="./" class="nx-btn-b3">다음에 변경</a>
</div>

<script>
var onclOnsu = function() {
	if ($('#mb_password').val() == '') {
		alert("현재 비밀번호를 입력해 주세요.");
		$('#mb_password').focus(); return;
	}

	if ($('#new_password').val() == '') {
		alert("새 비밀번호를 입력해 주세요.");
		$('#new_password').focus(); return;
	}

	if ($('#new_password_re').val() == '') {
		alert("새 비밀번호 확인을 입력해 주세요.");
		$('#new_password_re').focus(); return;
	}

	if ($('#mb_password').val() == $('#new_password').val()) {
		alert("현재 사용중인 비밀번호로 변경할 수 없습니다.");
		$("#new_password").focus();
		return;
	}

	if ($("#new_password").val() != $("#new_password_re").val()) {
		alert("새 비밀번호와 새 비밀번호 확인이 다릅니다.");
		$("#new_password_re").focus();
		return;
	}

	var pattern1 = /[0-9]/;
	var pattern2 = /[a-z]/;
	var pattern3 = /[A-Z]/;
	var pattern4 = /[^a-zA-Z0-9]/gi;

	var sum = 0;
	sum += pattern1.test($("#new_password").val()) ? 1 : 0;
	sum += pattern2.test($("#new_password").val()) ? 1 : 0;
	sum += pattern3.test($("#new_password").val()) ? 1 : 0;
	sum += pattern4.test($("#new_password").val()) ? 1 : 0;

	if (sum < 3 || $("#new_password").val().length < 9) {
		alert("새 비밀번호는 영대문자, 영소문자, 숫자, 특수문자 중 3종류 이상 조합하여 9자 이상으로 입력해 주세요.");
		$("#new_password").focus();
		return;
	}

	if (confirm("입력하신 정보로 진행하시겠습니까?")) {
		var f = new FormData($('#frmPw')[0]);

		$.ajax({
			url: 'passwd.editProc.php',
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
			// alert("처리도중 에러가 발생했습니다.");
		});
	}
	return;
}
</script>

<?php
	//마지막 인클루드
	include_once G5_ADMIN_PATH."/admin.tail.php";
?>
