<?php
include_once('../common.php');

include_once(G5_PATH.'/head.php');

$is_modal_js = apms_script('modal');
?>
<h3 class="nx_page_tit"><?php echo $Select_Menu_Text; ?>만족도 조사</h3>
<div class="nx-poll-tit">행사명: 평생교육진흥원 연말 워크샵</div>
<div class="nx-poll-lst">
	<div class="nx-poll-item">
		<div class="tit">1. 행사에 대해 만족합니까?</div>
		<div class="radio1_wrap mb ml20">
			<input type="radio" id="aa1" name="aa" class="radio1" disabled><label for="aa1"><span class="radio"><span></span></span><span class="txt">매우 그렇지않다</span></label>
			<input type="radio" id="aa2" name="aa" class="radio1" disabled><label for="aa2"><span class="radio"><span></span></span><span class="txt">그렇지않다</span></label>
			<input type="radio" id="aa3" name="aa" class="radio1" disabled checked><label for="aa3"><span class="radio"><span></span></span><span class="txt">보통이다</span></label>
			<input type="radio" id="aa4" name="aa" class="radio1" disabled><label for="aa4"><span class="radio"><span></span></span><span class="txt">그렇다</span></label>
			<input type="radio" id="aa5" name="aa" class="radio1" disabled><label for="aa5"><span class="radio"><span></span></span><span class="txt">매우그렇다</span></label>
		</div>
	</div>
	<div class="nx-poll-item">
		<div class="tit">2. 행사 직원에 대해 만족합니까?</div>
		<div class="radio1_wrap mb ml20">
			<input type="radio" id="bb1" name="bb" class="radio1" disabled><label for="bb1"><span class="radio"><span></span></span><span class="txt">매우 그렇지않다</span></label>
			<input type="radio" id="bb2" name="bb" class="radio1" disabled><label for="bb2"><span class="radio"><span></span></span><span class="txt">그렇지않다</span></label>
			<input type="radio" id="bb3" name="bb" class="radio1" disabled><label for="bb3"><span class="radio"><span></span></span><span class="txt">보통이다</span></label>
			<input type="radio" id="bb4" name="bb" class="radio1" disabled checked><label for="bb4"><span class="radio"><span></span></span><span class="txt">그렇다</span></label>
			<input type="radio" id="bb5" name="bb" class="radio1" disabled><label for="bb5"><span class="radio"><span></span></span><span class="txt">매우그렇다</span></label>
		</div>
	</div>
</div>

<div class="taC mt30">
	<a href="" class="event_apply_btn1">설문 제출</a>
</div>
<?php 
//include(THEMA_PATH."/side/nx-side.php");

for ($i=0; $i < count($menu); $i++) {
	$menu[$i]['on'] = "";
	$menu[$i]['is_sub'] = false;
}
$menu[7]['on'] = "on";
$menu[7]['is_sub'] = true;

include_once(G5_PATH.'/tail.php');
?>