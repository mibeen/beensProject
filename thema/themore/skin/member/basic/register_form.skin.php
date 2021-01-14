<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$skin_url.'/style.css" media="screen">
', 0); add_stylesheet('
<link rel="stylesheet" href="'.THEMA_URL.'/assets/css/ico.css" media="screen">
', 0); /* add_javascript('
<script src="'.THEMA_URL.'/assets/js/core.js"></script>
', 0); add_javascript('
<script src="'.THEMA_URL.'/assets/js/comm.js"></script>
', 0); */ if($header_skin) include_once('./header.php'); ?>
<script src="<?php echo g5_js_url ?>/jquery.register_form.js"></script>
<?php if($config['cf_cert_use'] && ($config['cf_cert_ipin'] || $config['cf_cert_hp'])) { ?>
<script src="<?php echo g5_js_url ?>/certify.js?v=<?php echo APMS_SVER; ?>"></script>
<?php } ?>
<form class="form-horizontal register-form" role="form" id="fregisterform" name="fregisterform" action="<?php echo $action_url; ?>" onsubmit="return fregisterform_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off">
    <input type="hidden" name="w" value="<?php echo $w ?>"> 
	<input type="hidden" name="url" value="<?php echo $urlencode ?>"> 
	<input type="hidden" name="pim" value="<?php echo $pim;?>"> 
	<input type="hidden" name="agree" value="<?php echo $agree ?>"> 
	<input type="hidden" name="agree2" value="<?php echo $agree2 ?>"> 
	<input type="hidden" name="cert_type" value="<?php echo $member['mb_certify']; ?>"> 
	<input type="hidden" name="cert_no" value="">
    <?php if (isset($member['mb_sex'])) {  ?>
    <input type="hidden" name="mb_sex" value="<?php echo $member['mb_sex'] ?>"><?php }  ?>
    <?php if (isset($member['mb_nick_date']) && $member['mb_nick_date'] >
    date("Y-m-d", G5_SERVER_TIME - ($config['cf_nick_modify'] * 86400))) { // 닉네임수정일이 지나지 않았다면 ?> <input type="hidden" name="mb_nick_default" value="<?php echo get_text($member['mb_nick']) ?>"> <input type="hidden" name="mb_nick" value="<?php echo get_text($member['mb_nick']) ?>"> <?php }  ?>
    <div class="nx_page_wrapper">
        <div class="data_ct">
            <ul class="join_progress memb">
                <li>약관동의</li>
                <li class="arrw"><span class="ico_arr1_r"></span></li>
                <li class="on">정보입력</li>
                <li class="arrw"><span class="ico_arr1_r"></span></li>
                <li>가입완료</li>
            </ul>
            <div class="mb_form r_mh join">
                <dl>
                    <dt>아이디</dt>
                    <dd class="has_btn">
                        <input type="text" name="mb_id" value="<?php echo $member['mb_id']; ?>" id="reg_mb_id" <?php echo $required; ?> 
                        <?php echo $readonly ?>
                        class="mb_nx_ips1" minlength="3" maxlength="20"> 
						<?php /* <span class="fa fa-check form-control-feedback"></span>*/ ?>
                        <p class="note">
                            영문자, 숫자, _ 만 입력 가능. 최소 3자이상 입력하세요.
                        </p>
                    </dd>
                </dl>
                <dl>
                    <dt>비밀번호</dt>
                    <dd>
                        <div>
                            <input type="password" name="mb_password" id="reg_mb_password" <?php echo $required ?> class="mb_nx_ips1" minlength="3" maxlength="20">
							<?php /* <span class="fa fa-lock form-control-feedback"></span>*/ ?>
                            <p class="note">
                                대문자와 소문자를 포함한 영어알파벳과 숫자, 특수문자를 포함해주세요.
                            </p>
                        </div>
                    </dd>
                </dl>
                <dl>
                    <dt>비밀번호 확인</dt>
                    <dd>
                        <input type="password" name="mb_password_re" id="reg_mb_password_re" <?php echo $required ?> class="mb_nx_ips1" minlength="3" maxlength="20">
						<?php /*<span class="fa fa-check form-control-feedback mb_nx_ips1"></span>*/ ?>
                        <p class="note">
                            비밀번호를 다시 한번 입력해 주세요.
                        </p>
                    </dd>
                </dl>
                <dl>
                    <dt>이메일</dt>
                    <dd>
                        <input type="hidden" name="old_email" value="<?php echo $member['mb_email'] ?>"> <input type="text" name="mb_email" value="<?php echo isset($member['mb_email'])?$member['mb_email']:''; ?>" id="reg_mb_email" required class="email mb_nx_ips1" size="70" maxlength="100"> 
						<?php /*<span class="fa fa-envelope form-control-feedback"></span>*/ ?>

                    </dd>
                </dl>
                <dl>
                    <dt>이름</dt>
                    <dd>
                        <input type="text" id="reg_mb_name" name="mb_name" value="<?php echo get_text($member['mb_name']) ?>" <?php echo $required ?>
                        <?php echo $readonly; ?>
                        class="mb_nx_ips1" size="10"> 
						<?php /*<span class="fa fa-check form-control-feedback"></span>*/ ?>
                    </dd>
                </dl>
                <?php if ($req_nick) {  ?>
                <dl>
                    <dt>닉네임</dt>
                    <dd>
                      	<input type="hidden" name="mb_nick_default" value="<?php echo isset($member['mb_nick']) ? get_text($member['mb_nick']) : ''; ?>"> 
									<input type="text" name="mb_nick" value="<?php echo isset($member['mb_nick']) ? get_text($member['mb_nick']) : ''; ?>" id="reg_mb_nick" required class="nospace mb_nx_ips1" size="10" maxlength="20"> 
						<?php /*<span class="fa fa-user form-control-feedback"></span>*/ ?>
                        <p class="note">
                            공백없이 한글,영문,숫자만 입력 가능 (한글2자, 영문4자 이상) 닉네임을 바꾸시면 앞으로 <?php echo (int)$config['cf_nick_modify'] ?>
                            일 이내에는 변경 할 수 없습니다.
                        </p>
                    </dd>
                </dl>
                <?php } ?>
                <dl>
                	<dt>메일링서비스</dt>
                	<dd>
                		<div class="lh30">
                			<input type="checkbox" id="reg_mb_mailling" name="mb_mailling" class="chk"<?php echo ($w=='' || $member['mb_mailling'])?' checked':''; ?>><label for="reg_mb_mailling" style="margin:0;">정보 메일을 받겠습니다.</label>
                		</div>
                	</dd>
                </dl>
						<?php if ($config['cf_use_hp']) {  ?>
                <dl>
                	<dt>SMS 수신여부</dt>
                	<dd>
                		<input type="checkbox" id="reg_mb_sms" name="mb_sms" class="chk"<?php echo ($w=='' || $member['mb_sms'])?' checked':''; ?>><label for="reg_mb_sms">휴대폰 문자메세지를 받겠습니다.</label>
                	</dd>
                </dl>
						<?php }  ?>
						<?php if (isset($member['mb_open_date']) && $member['mb_open_date'] <= date("Y-m-d", G5_SERVER_TIME - ($config['cf_open_modify'] * 86400)) || empty($member['mb_open_date'])) { // 정보공개 수정일이 지났다면 수정가능  ?>
						<dl>
                	<dt>정보공개</dt>
                	<dd>
                		<input type="hidden" name="mb_open_default" value="<?php echo $member['mb_open'] ?>">
                		<input type="checkbox" id="reg_mb_open" name="mb_open" class="chk"<?php echo ($w=='' || $member['mb_open'])?' checked':''; ?>><label for="reg_mb_open" style="margin: 0;">다른분들이 나의 정보를 볼 수 있도록 합니다.</label>
                		<span class="help-block">
			                정보공개를 바꾸시면 앞으로 <?php echo (int)$config['cf_open_modify'] ?>
			                일 이내에는 변경이 안됩니다.
			          		</span>
                	</dd>
                </dl>
				   	<?php } else {  ?>
				   	<dl>
                	<dt>정보공개</dt>
                	<dd>
                		<span class="help-block">
			                정보공개는 수정후 <?php echo (int)$config['cf_open_modify'] ?>
			                일 이내, <?php echo date("Y년 m월 j일", isset($member['mb_open_date']) ? strtotime("{$member['mb_open_date']} 00:00:00")+$config['cf_open_modify']*86400:G5_SERVER_TIME+$config['cf_open_modify']*86400); ?>
			                까지는 변경이 안됩니다.<br>
			                이렇게 하는 이유는 잦은 정보공개 수정으로 인하여 쪽지를 보낸 후 받지 않는 경우를 막기 위해서 입니다.
			          		</span>
			          		<input type="hidden" name="mb_open" value="<?php echo $member['mb_open'] ?>">
                	</dd>
                </dl>
						<?php } ?>
				   	<?php if ($w == "" && $config['cf_use_recommend']) {  ?>
				   	<dl>
                	<dt>추천인아이디</dt>
                	<dd>
								<input type="text" id="reg_mb_recommend" name="mb_recommend"  class="nospace mb_nx_ips1"> 
                	</dd>
                </dl>
						<?php }  ?>
						<dl>
                	<dt>자동등록방지</dt>
                	<dd>
								<?php echo captcha_html(); ?>
                	</dd>
                </dl>
            </div>
            <div class="nx_taC mt15" style="border-top: 1px solid #e5e5e5;padding-top:25px;">
				<button type="submit" id="btn_submit" class="btn nx_btn_memb1" style="border-radius:4px !important;" accesskey="s"><?php echo $w==''?'회원가입':'정보수정'; ?></button>
                <a href="<?php echo $at_href['home'];?>" class="nx_btn_memb3 ml10">취소</a>
            </div>
        </div>
    </div>
</form>

<script>
$(function() {
	$("#reg_zip_find").css("display", "inline-block");

	<?php if($config['cf_cert_use'] && $config['cf_cert_ipin']) { ?>
	// 아이핀인증
	$("#win_ipin_cert").click(function() {
		if(!cert_confirm())
			return false;

		var url = "<?php echo G5_OKNAME_URL; ?>/ipin1.php";
		certify_win_open('kcb-ipin', url);
		return;
	});

	<?php } ?>
	<?php if($config['cf_cert_use'] && $config['cf_cert_hp']) { ?>
	// 휴대폰인증
	$("#win_hp_cert").click(function() {
		if(!cert_confirm())
			return false;

		<?php
		switch($config['cf_cert_hp']) {
			case 'kcb':
				$cert_url = G5_OKNAME_URL.'/hpcert1.php';
				$cert_type = 'kcb-hp';
				break;
			case 'kcp':
				$cert_url = G5_KCPCERT_URL.'/kcpcert_form.php';
				$cert_type = 'kcp-hp';
				break;
			case 'lg':
				$cert_url = G5_LGXPAY_URL.'/AuthOnlyReq.php';
				$cert_type = 'lg-hp';
				break;
			default:
				echo 'alert("기본환경설정에서 휴대폰 본인확인 설정을 해주십시오");';
				echo 'return false;';
				break;
		}
		?>

		certify_win_open("<?php echo $cert_type; ?>", "<?php echo $cert_url; ?>");
		return;
	});
	<?php } ?>
});

// submit 최종 폼체크
function fregisterform_submit(f)
{
	// 회원아이디 검사
	if (f.w.value == "") {
		var msg = reg_mb_id_check();
		if (msg) {
			alert(msg);
			f.mb_id.select();
			return false;
		}
	}

	if (f.w.value == "") {
		if (f.mb_password.value.length < 3) {
			alert("비밀번호를 3글자 이상 입력하십시오.");
			f.mb_password.focus();
			return false;
		}
	}

	if (f.mb_password.value != f.mb_password_re.value) {
		alert("비밀번호가 같지 않습니다.");
		f.mb_password_re.focus();
		return false;
	}

	if (f.mb_password.value.length > 0) {
		if (f.mb_password_re.value.length < 3) {
			alert("비밀번호를 3글자 이상 입력하십시오.");
			f.mb_password_re.focus();
			return false;
		}
	}

	// 이름 검사
	if (f.w.value=="") {
		if (f.mb_name.value.length < 1) {
			alert("이름을 입력하십시오.");
			f.mb_name.focus();
			return false;
		}

		/*
		var pattern = /([^가-힣\x20])/i;
		if (pattern.test(f.mb_name.value)) {
			alert("이름은 한글로 입력하십시오.");
			f.mb_name.select();
			return false;
		}
		*/
	}

	<?php if($w == '' && $config['cf_cert_use'] && $config['cf_cert_req']) { ?>
	// 본인확인 체크
	if(f.cert_no.value=="") {
		alert("회원가입을 위해서는 본인확인을 해주셔야 합니다.");
		return false;
	}
	<?php } ?>

	// 닉네임 검사
	if ((f.w.value == "") || (f.w.value == "u" && f.mb_nick.defaultValue != f.mb_nick.value)) {
		var msg = reg_mb_nick_check();
		if (msg) {
			alert(msg);
			f.reg_mb_nick.select();
			return false;
		}
	}

	// E-mail 검사
	if ((f.w.value == "") || (f.w.value == "u" && f.mb_email.defaultValue != f.mb_email.value)) {
		var msg = reg_mb_email_check();
		if (msg) {
			alert(msg);
			f.reg_mb_email.select();
			return false;
		}
	}

	<?php if (($config['cf_use_hp'] || $config['cf_cert_hp']) && $config['cf_req_hp']) {  ?>
	// 휴대폰번호 체크
	var msg = reg_mb_hp_check();
	if (msg) {
		alert(msg);
		f.reg_mb_hp.select();
		return false;
	}
	<?php } ?>

	if (typeof f.mb_icon != "undefined") {
		if (f.mb_icon.value) {
			if (!f.mb_icon.value.toLowerCase().match(/.(gif)$/i)) {
				alert("회원아이콘이 gif 파일이 아닙니다.");
				f.mb_icon.focus();
				return false;
			}
		}
	}

	if (typeof(f.mb_recommend) != "undefined" && f.mb_recommend.value) {
		if (f.mb_id.value == f.mb_recommend.value) {
			alert("본인을 추천할 수 없습니다.");
			f.mb_recommend.focus();
			return false;
		}

		var msg = reg_mb_recommend_check();
		if (msg) {
			alert(msg);
			f.mb_recommend.select();
			return false;
		}
	}

	<?php echo chk_captcha_js();  ?>

	document.getElementById("btn_submit").disabled = "disabled";

	return true;
}
</script>