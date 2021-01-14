<?php
	include_once("./_common.php");
	include_once(G5_LIB_PATH.'/apms.thema.lib.php');

	
	# 로그인 chk
	if (is_null($member['mb_id'])) {
		exit();
	}


	// 테마설정
	$at = apms_gr_thema();
	if(!defined('THEMA_PATH')) {
		include_once(G5_LIB_PATH.'/apms.thema.lib.php');
	}


	# set : variables
	$EM_IDX = $_POST['EM_IDX'];
	$EJ_IDX = $_POST['EJ_IDX'];
	

	# re-define
	$EM_IDX = CHK_NUMBER($EM_IDX);
	$EJ_IDX = CHK_NUMBER($EJ_IDX);


	# chk : rfv.
	if ($EM_IDX <= 0 || $EJ_IDX <= 0) exit;


	$sql = "Select EJ.EJ_NAME"
		."	From NX_EVENT_MASTER As EM"
		."		Inner Join NX_EVENT_JOIN As EJ On EJ.EM_IDX = EM.EM_IDX"
		."			And EJ.EJ_DDATE is null"
		."			And EJ.mb_id = '" . mres($member['mb_id']) . "'"
		."	Where EM.EM_DDATE is null"
		."		And EM.EM_IDX = '" . mres($EM_IDX) . "'"
		."		And EJ.EJ_IDX = '" . mres($EJ_IDX) . "'"
		."	Limit 1"
		;
	$rs1 = sql_fetch($sql);
	if (is_null($rs1['EJ_NAME'])) {
		unset($rs1);
		F_script("존재하지 않는 정보 입니다.", "self.close();");
	}
	$DB_EJ = $rs1;
	unset($rs1);


	# insert : NX_EVENT_PRINT
	$sql = "Insert Into NX_EVENT_PRINT("
		."EJ_IDX"
		.", mb_id"
		.", EP_WDATE"
		.", EP_WIP"
		.") values("
		."'" . mres($EJ_IDX) . "'"
		.", '" . mres($member['mb_id']) . "'"
		.", now()"
		.", inet_aton('" . mres($_SERVER['REMOTE_ADDR']) . "')"
		.")"
		;
	sql_query($sql);


	include_once(G5_PATH.'/head.sub.php');
	if(!USE_G5_THEME) @include_once(THEMA_PATH.'/head.sub.php');
?>
<div class="event_apply_top">
	<div class="top_tit">확인증 인쇄</div>
	<a href="javascript:void(0);" onclick="window.close();" class="cls"><span class="ico_x"></span></a>
</div>
<div class="nx-certi-tit mt30 mb20"><?php echo(F_hsc($DB_EJ['EJ_NAME']))?>님, 참여한 행사의 확인증을 인쇄하세요.</div>
<div class="taC mb20">
	<a href="javascript:void(0)" onclick="cert.print()" class="event_apply_btn1">인쇄하기</a>
</div>
<div>
	<iframe id="if_inner" name="if_inner" frameborder="0" height="850" style="width:100%"></iframe>
</div>

<div style="display:none">
	<form id="frmSati" name="frmSati" onsubmit="return false">
		<input type="hidden" id="sati_em_idx" name="EM_IDX" value="<?php echo($EM_IDX)?>" />
		<input type="hidden" id="sati_ej_idx" name="EJ_IDX" value="<?php echo($EJ_IDX)?>" />
	</form>
</div>

<script>
//<![CDATA[
var cert = {
	init: function() {
		var def = {em_idx: '', ej_idx: ''};
		var o = $.extend({}, def, o);
		
		if ((isNaN(o.em_idx) || parseInt(o.em_idx) <= 0) || (isNaN(o.ej_idx) || parseInt(o.ej_idx) <= 0)) return;

		var f = document.frmSati;
		f.action = 'evt.cert.inner.php';
		f.method = 'post';
		f.target = 'if_inner';
		f.submit();
	}
	, print: function() {
		var agent = navigator.userAgent.toLowerCase();

		// ie인 경우 inner로 페이지 이동
		if ( (navigator.appName == 'Netscape' && navigator.userAgent.search('Trident') != -1) || (agent.indexOf("msie") != -1) ) {
			window.location.href = "evt.cert.inner.php?EM_IDX=" + $('#sati_em_idx').val() + "&EJ_IDX=" + $('#sati_ej_idx').val() + "&onload=Y";
		}
		else {
			var obj = document.getElementById('if_inner');
			var objDoc = obj.contentWindow || obj.contentDocument;
			objDoc._print();
		}
	}
}
cert.init();
//]]>
</script>
<?php
	if(!USE_G5_THEME) @include_once(THEMA_PATH.'/tail.sub.php');
	include_once(G5_PATH.'/tail.sub.php');
?>
