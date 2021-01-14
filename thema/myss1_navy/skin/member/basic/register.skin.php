<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$skin_url.'/style.css" media="screen">', 0); 
add_stylesheet('<link rel="stylesheet" href="'.THEMA_URL.'/assets/css/style.css" media="screen">', 0); 
add_stylesheet('<link rel="stylesheet" href="'.THEMA_URL.'/assets/css/ico.css" media="screen">', 0); 
/*
add_javascript('<script src="'.THEMA_URL.'/assets/js/core.js"></script>', 0);
add_javascript('<script src="'.THEMA_URL.'/assets/js/comm.js"></script>', 0);
*/ 
if($header_skin) include_once('./header.php'); 

?>

<form name="fregister" id="fregister" action="<?php echo $action_url ?>" onsubmit="return fregister_submit(this);" method="POST" autocomplete="off" class="form" role="form">
	<input type="hidden" name="pim" value="<?php echo $pim;?>">
	<div class="nx_page_wrapper">
		<div class="data_ct">
			<ul class="join_progress memb">
				<li class="on">약관동의</li>
				<li class="arrw"><span class="ico_arr1_r"></span></li>
				<li>정보입력</li>
				<li class="arrw"><span class="ico_arr1_r"></span></li>
				<li>가입완료</li>
			</ul>

			<div class="alert alert-info" role="alert">
				<strong><i class="fa fa-exclamation-circle fa-lg"></i> 회원가입약관 및 개인정보처리방침안내의 내용에 동의하셔야 회원가입 하실 수 있습니다.</strong>
			</div>

			<h4 class="blind">약관동의</h4>
			<h5 class="nx_tit3">회원가입약관</h5>
			<div class="box2 ml15 mr15" style="max-height:250px;">
				<?php if($provision) { ?>
				<?php echo $provision; ?>
				<?php } else { ?>
				<textarea class="form-control input-sm" rows="10" readonly><?php echo get_text($config['cf_stipulation']) ?>
				</textarea>
				<?php } ?>
			</div>
			<div class="mt10 nx_taR">
				<input type="checkbox" name="agree" value="1" id="agree" class="fill memb" >
				<label for="agree" class="nx_taC" style="width: 150px;"><span class="check"><span class="ico_check"></span></span><span class="txt">동의합니다.</span></label>
			</div>
			<h5 class="nx_tit3">개인정보처리방침안내( <label style="font-size:9pt;">
			<?php if($privacy) { ?>
			<a data-toggle="collapse" href="#privacy" aria-expanded="false" aria-controls="privacy" class="pull-right">전문보기</a>
			<?php } ?>
			</label>) </h5>
			<?php if($privacy) { ?>
			<div class="box2 ml15 mr15 panel-body collapse" id="privacy" style="border-bottom:1px solid #ddd; max-height:250px;">
				<div class="register-term">
					<?php echo $privacy; ?>
				</div>
			</div>
			<?php } ?>
			<div class="box2 ml15 mr15" style="max-height:250px;">
				<table class="table" style="border-top:0px;">
				<colgroup>
				<col width="40%">
				<col width="30%">
				</colgroup>
				<tbody>
				<tr>
					<th style="border-top:0px;">
						목적
					</th>
					<th style="border-top:0px;">
						항목
					</th>
					<th style="border-top:0px;">
						보유기간
					</th>
				</tr>
				<tr>
					<td>
						이용자 식별 및 본인여부 확인
					</td>
					<td>
						아이디, 이름, 비밀번호
					</td>
					<td>
						회원 탈퇴 시까지
					</td>
				</tr>
				<tr>
					<td>
						고객서비스 이용에 관한 통지, CS대응을 위한 이용자 식별
					</td>
					<td>
						연락처 (이메일, 휴대전화번호)
					</td>
					<td>
						회원 탈퇴 시까지
					</td>
				</tr>
				</tbody>
				</table>
			</div>
			<div class="mt10 nx_taR">
				<input type="checkbox" name="agree2" value="1" id="agree2" class="fill memb" >
				<label for="agree2" class="nx_taC" style="width: 150px;"><span class="check"><span class="ico_check"></span></span><span class="txt">동의합니다.</span></label>
			</div>
			<div class="nx_taC mt15" style="border-top: 1px solid #e5e5e5;padding-top:25px;">
				회원가입을 하기 위해 약관에 동의해주세요.
			</div>
			<div class="nx_taC mt15">
				<button type="submit" class="btn nx_btn_memb1" style="border-radius:4px !important;">회원가입</button>
				<a href="" class="nx_btn_memb3 ml10">취소</a>
			</div>
		</div>
	</div>
</form>
<script>
    function fregister_submit(f) {
        if (!f.agree.checked) {
            alert("회원가입약관의 내용에 동의하셔야 회원가입 하실 수 있습니다.");
            f.agree.focus();
            return false;
        }
        if (!f.agree2.checked) {
            alert("개인정보처리방침안내의 내용에 동의하셔야 회원가입 하실 수 있습니다.");
            f.agree2.focus();
            return false;
        }
        return true;
    }
</script>