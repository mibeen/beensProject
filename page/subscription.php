<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

include_once(G5_CAPTCHA_PATH.'/captcha.lib.php');

$captcha_html = captcha_html();
$captcha_js   = chk_captcha_js();
?>

<div class="nx-page subscription">
	<form id="nform" name="nform" method="post" onsubmit="return false;">
		<p class="sub-title red">경기도평생교육진흥원 웹진</p>
		<h3 class="main-title">더 more 구독신청</h3>

		<div class="img-box"><img src="/thema/<?php echo THEMA ?>/assets/images/newsletter.jpg" alt="더모어 뉴스레터"></div>
		<div class="desc-wrap">
			<div class="table-cell">
				<p>유익한 평생교육 정보를 받아보세요!</p>
				<p>더 more 웹진은 경기도평생교육진흥원에서 매달 발행하는 평생교육 매거진입니다.</p>
				<p>구독신청을 하시면 신청해주신 이메일로 더 more 웹진을 발송해드립니다.</p>
			</div>
		</div>

		<p class="form-title">개인정보 수집 및 이용 등에 대한 동의</p>
		<div class="form-wrap">
			<textarea name="" id="" class="agree-textarea">1. 개인정보의수집 및 이용목적
   경기도 평생교육진흥원이 발행하는 뉴스레터 발송을 위해 이메일 정보를 수집하고자 하며, 수집된 개인 정보는 메일발송 목적으로만 이용됩니다.
2. 수집개인정보항목
   개인정보의 항목은 “이름, 이메일주소” 입니다.
3. 개인정보의 보유 및 이용기간
   수집된 개인 정보는 관계법령에 따라 적정하게 처리하여, 귀하의 권익이 침해받지 않도록
   노력할 것입니다. 동의한 내용은 언제든지 철회하실 수 있으며, 동의 철회를 원하시는 경우
   031)547-6524,lsy@gill.or.kr로 연락주시기 바랍니다.
4. 동의를 거부할 권리 및 동의 거부에 따른 불이익
   개인정보수집 및 이용에 동의를 거부할 권리가 있으나, 거부할 시 경기도평생교육진흥원 뉴스레터 수신 대상에서 제외됩니다.</textarea>
		</div>
		<div class="form-btn text-right">
			<label for="agree1">
				<input type="checkbox" id="agree1" name="agree1" value="1" required> <span>개인정보 수집 및 웹진 수신에 동의합니다.</span>
			</label>
		</div>

		<p class="form-title">구독 정보 입력</p>
		<div class="form-wrap">
			<table class="nx-tb01">
				<caption class="hidden">구독 정보 입력(캡차)</caption>
				<tr>
					<th>이름</th>
					<td><input type="text" id="NM_NAME" name="NM_NAME" maxlength="20" required /></td>
				</tr>
				<tr>
					<th>이메일주소</th>
					<td><input type="email" id="NM_EMAIL" name="NM_EMAIL" maxlength="200" required /></td>
				</tr>
				<tr>
					<th>자동입력방지</th>
					<td><?php echo $captcha_html ?></td>
				</tr>
			</table>
		</div>

		<div class="btn-box text-center">
			<button id="btn_submit" onclick="nletter.onsu()" class="btn btn-nw-submit btn-lg">구독신청</button>
		</div>
	</form>
</div>

<script>
//<![CDATA[
var nletter = {
	onsu: function() {
		if(!$("#agree1").is(":checked")) {
			alert("약관에 동의해 주세요.");
			$("#agree1").focus(); return false;
		}

		if($("#NM_NAME").val().replace(/(^\s*)|(\s*$)/g, "") == "") {
			alert("이름 정보를 입력해 주세요.");
			$("#NM_NAME").focus(); return false;
		}

		if($("#NM_EMAIL").val().replace(/(^\s*)|(\s*$)/g, "") == "") {
			alert("이메일주소 정보를 입력해 주세요.");
			$("#NM_EMAIL").focus(); return false;
		}

		<?php echo $captcha_js; // 캡챠 사용시 자바스크립트에서 입력된 캡챠를 검사함 ?>

		$('#btn_submit').prop('disabled', true);

		$.ajax({
			url: '/newsletter/subscriber.addProc.php',
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