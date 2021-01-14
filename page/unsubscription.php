<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

include_once(G5_CAPTCHA_PATH.'/captcha.lib.php');

$captcha_html = captcha_html();
$captcha_js   = chk_captcha_js();


# set : variables
$NM_CODE = $_GET['NM_CODE'];


# re-define
$NM_CODE = trim($NM_CODE);
?>

<div class="nx-page subscription">
	<form id="nform" name="nform" method="post" onsubmit="return false;">
		<input type="hidden" id="NM_CODE" name="NM_CODE" value="<?php echo(htmlspecialchars($NM_CODE))?>" />

		<p class="sub-title red">경기도평생교육진흥원 웹진</p>
		<h3 class="main-title">더 more 구독취소</h3>

		<div class="img-box"><img src="/thema/<?php echo THEMA ?>/assets/images/newsletter.jpg" alt="더모어 뉴스레터"></div>
		<div class="desc-wrap">
			<div class="table-cell">
				<p>그동안 더 more 웹진을 구독해주셔서 감사합니다.</p>
				<p>앞으로도 유익한 정보를 제공하도록 노력하겠습니다.</p>
				<p>더 more 웹진을 다시 구독하고 싶으시다면 언제든 방문하여 구독신청을 해주세요.</p>
			</div>
		</div>

		<p class="form-title">취소 정보 입력</p>
		<div class="form-wrap">
			<table class="nx-tb01">
				<caption class="hidden">취소 정보 입력(캡차)</caption>
				<tr>
					<th>이름</th>
					<td><input type="text" id="NM_NAME" name="NM_NAME" maxlength="20" required /></td>
				</tr>
				<?php /*
				<tr style="display:none;">
					<th>이메일주소</th>
					<td><input type="email" id="NM_EMAIL" name="NM_EMAIL" maxlength="200" required /></td>
				</tr>
				*/ ?>
				<tr>
					<th>자동입력방지</th>
					<td><?php echo $captcha_html ?></td>
				</tr>
			</table>
		</div>

		<div class="btn-box text-center">
			<button id="btn_submit" onclick="nletter.onsu()" class="btn btn-nw-submit btn-lg">구독취소</button>
		</div>
	</form>
</div>

<script>
//<![CDATA[
var nletter = {
	onsu: function() {
		if($("#NM_NAME").val().replace(/(^\s*)|(\s*$)/g, "") == "") {
			alert("이름 정보를 입력해 주세요.");
			$("#NM_NAME").focus(); return false;
		}

		<?php /*
		if($("#NM_EMAIL").val().replace(/(^\s*)|(\s*$)/g, "") == "") {
			alert("이메일주소 정보를 입력해 주세요.");
			$("#NM_EMAIL").focus(); return false;
		}
		*/ ?>

		<?php echo $captcha_js; // 캡챠 사용시 자바스크립트에서 입력된 캡챠를 검사함 ?>

		$('#btn_submit').prop('disabled', true);

		$.ajax({
			url: '/newsletter/subscriber.delProc.php',
			type: 'POST',
			dataType: 'json',
			data: $('#nform').serialize()
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
		})
		.always(function() {
			$('#btn_submit').prop('disabled', false);
		});
	}
}
//]]>
</script>
<?php
/*
submit 이후 넘어가는 페이지에서
include_once(G5_CAPTCHA_PATH.'/captcha.lib.php'); 로딩 후,

if(!chk_captcha())
	alert('자동등록방지 숫자가 틀렸습니다.');

하나 넣어주세요.
*/
?>