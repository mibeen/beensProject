<?php
include_once("./api.php");

// 설정값 불러오기
$is_faq_sub = false;
@include_once($faq_skin_path.'/config.skin.php');

$g5['title'] = $fm['fm_subject'];


if($is_faq_sub) {
	include_once(G5_PATH.'/head.sub.php');
	if(!USE_G5_THEME) @include_once(THEMA_PATH.'/head.sub.php');
} else {
	include_once('../bbs/_head.php');
}



if(GSK_I_ID != $member['mb_id'])
{
	alert("잘 못 된 접금입니다.\n처음화면으로 이동하겠습니다.");
	Header("Location:".G5_URL);
}	
else
{	
?>

<p class="nx_page_tit">개인정보 변경</p>

<div class="page-wrap">
<div class="data_ct">

					<p class="nx_memb_slog" style="margin-bottom:35px;">정보보호를 위해 비밀번호를 입력해주세요.</p>
					<div class="chk_pw">
						<label for="" class="blind">비밀번호</label>
						<input type="password" id="" name="" class="nx_ips3" placeholder="비밀번호">
						<a href="" class="nx_btn_memb">확인</a>
					</div>
				</div></div>


<?php } ?>
<?php 
//include(THEMA_PATH."/side/nx-side.php");

for ($i=0; $i < count($menu); $i++) {
	$menu[$i]['on'] = "";
	$menu[$i]['is_sub'] = false;
}
$menu[6]['on'] = "on";
$menu[6]['is_sub'] = true;

if($is_faq_sub) {
	if(!USE_G5_THEME) @include_once(THEMA_PATH.'/tail.sub.php');
	include_once(G5_PATH.'/tail.sub.php');
} else {
	include_once('../bbs/_tail.php');
}
?>