<?php
	include_once("./_common.php");
	include_once(G5_LIB_PATH.'/apms.thema.lib.php');
	include_once(G5_LIB_PATH.'/thumbnail.lib.php'); //썸네일 라이브러리 로딩

	
	// 테마설정
	$at = apms_gr_thema();
	if(!defined('THEMA_PATH')) {
		include_once(G5_LIB_PATH.'/apms.thema.lib.php');
	}


	include_once(G5_PATH.'/head.sub.php');
	if(!USE_G5_THEME) @include_once(THEMA_PATH.'/head.sub.php');


	# set : variables
	# EJ_IDX 를 넘길 경우는 둘다 post 로 전달
	if ($_POST['EJ_IDX'] != '') {
		$EM_IDX = $_POST['EM_IDX'];
		$EJ_IDX = $_POST['EJ_IDX'];
	}
	else {
		$EM_IDX = $_GET['EM_IDX'];
		$EJ_IDX = $_GET['EJ_IDX'];
	}
	

	# re-define
	$EM_IDX = CHK_NUMBER($EM_IDX);
	$EJ_IDX = CHK_NUMBER($EJ_IDX);


	# chk : rfv.
	if ($EM_IDX <= 0) exit;


	/*	같은 만족도는 한번만 응할수 있도록 cookie 조회
		- cookie 로 생성하여 검사 함으로, cookie 삭제 후 다시 만족도 조사에 응하는 부분은 의도적으로 막지 않음
	*/
	if (strpos($_COOKIE['evt_join'].'|', "|{$EM_IDX}|") !== false) {
		F_script("이미 만족도 조사를 완료하였습니다.", "self.close();");
	}


	if ($EJ_IDX > 0)
	{
		# get : join data
		$sql = "Select EJ.EJ_NAME, EJ.EJ_JOIN_CODE"
			."	From NX_EVENT_JOIN As EJ"
			."		Inner Join NX_EVENT_MASTER As EM On EM.EM_IDX = EJ.EM_IDX"
			."			And EM.EM_DDATE is null"
			."	Where EJ.EM_IDX = '" . mres($EM_IDX) . "'"
			."		And EJ.EJ_IDX = '" . mres($EJ_IDX) . "'"
			."	Order By EJ.EJ_IDX Desc"
			."	Limit 1"
			;
		$row = sql_fetch($sql);
		
		if (is_null($row['EJ_NAME'])) {
			unset($row);
			F_script("잘못된 접근 입니다.", "self.close();");
		}
		$EJ_NAME = $row['EJ_NAME'];
		$EJ_JOIN_CODE = $row['EJ_JOIN_CODE'];
		unset($row);
	}


	# get : event master + sati
	$sql = "Select EM.EM_TITLE"
		."	From NX_EVENT_MASTER As EM"
		."		Inner Join NX_EVT_SATI_MA As ESM On ESM.EM_IDX = EM.EM_IDX"
		."	Where EM.EM_DDATE is null"
		."		And (ESM.ESM_S_DATE <= DATE_FORMAT(now(), '%Y-%m-%d') And ESM.ESM_E_DATE >= DATE_FORMAT(now(), '%Y-%m-%d'))"
		."		And EM.EM_IDX = '" . mres($EM_IDX) . "'"
		."	Limit 1"
		;
	$row = sql_fetch($sql);

	if (is_null($row['EM_TITLE'])) {
		unset($row);
		F_script("잘못된 접근 입니다.", "self.close();");
	}
	$EM_TITLE = $row['EM_TITLE'];
	unset($row);
?>
<form id="frmSati" name="frmSati" onsubmit="return false">
<input type="hidden" id="EM_IDX" name="EM_IDX" value="<?php echo($EM_IDX)?>" />
<input type="hidden" id="EJ_IDX" name="EJ_IDX" value="<?php echo($EJ_IDX)?>" />

<div class="nx-poll-outer" style="padding:20px">
	<h3 class="nx_page_tit"><?php echo $Select_Menu_Text; ?>만족도 조사</h3>
	<div class="nx-poll-tit">행사명: <?php echo(F_hsc($EM_TITLE))?></div>
	<div class="nx-poll-lst">
		<?php
		# get : 설문 항목
		$sql = "Select ESD.ESD_IDX, ESD.ESD_QUES"
			." 	From NX_EVT_SATI_DE As ESD"
			." 	Where ESD.ESD_DDATE is null"
			."		And ESD.EM_IDX = '" . mres($EM_IDX) . "'"
			." 	Order By ESD.ESD_IDX Asc"
			;
		$db1 = sql_query($sql);

		$nums = '';
		$s = 0;
		while ($rs1 = sql_fetch_array($db1))
		{
			$num = uniqid();
			$nums .= '|'.$num;
			?>
		<div class="nx-poll-item">
			<div class="tit"><?php echo($s + 1)?>. <?php echo(F_hsc($rs1['ESD_QUES']))?></div>
			<div class="radio1_wrap mb ml20">
				<?php
				echo '<input type="hidden" id="ESD_IDX_'.$num.'" name="ESD_IDX_'.$num.'" value="'.(int)$rs1['ESD_IDX'].'" />';

				$_arr = array(
					'매우 그렇지 않다'
					, '그렇지 않다'
					, '보통이다'
					, '그렇다'
					, '매우 그렇다'
				);

				$i = Count($_arr) - 1;
				while ($i > -1)
				{
					echo '<input type="radio" id="ESV_VAL_'.$i.'_'.$num.'" name="ESV_VAL_'.$num.'" class="radio1" value="'.($i + 1).'" />';
					echo '<label for="ESV_VAL_'.$i.'_'.$num.'"><span class="radio"><span></span></span><span class="txt">';
					echo $_arr[$i];
					echo '</span></label>';
					$i--;
				}
				?>
			</div>
		</div>
			<?php
			$s++;
		}
		unset($rs1, $db1);
		?>

		<input type="hidden" id="nums" name="nums" value="<?php echo($nums)?>" />
	</div>

	<div class="taC mt30">
		<a href="javascript:void(0)" onclick="sati.send();" class="event_apply_btn1">설문 제출</a>
	</div>
</div>

</form>

<script>
//<![CDATA[
var sati = {
	send: function() {
		if (confirm("입력하신 사항으로 진행하시겠습니까?"))
		{
			$.ajax({
				url: 'evt.satiProc.php',
				type: 'POST',
				dataType: 'json',
				data: $('#frmSati').serialize()
			})
			.done(function(json) {
				if (!json.success) {
					if (json.msg) alert(json.msg);
					return;
				}

				if (json.msg) alert(json.msg);
				if (opener) {
					opener.location.reload();
					self.close();
				}
				else {
					window.location.href = "/";
				}
			})
			.fail(function(a, b, c) {
				alert(a.statusText+' ('+a.status+')\n\n'+a.responseText);
			});
		}
	}
}
//]]>
</script>
<?php
	if(!USE_G5_THEME) @include_once(THEMA_PATH.'/tail.sub.php');
	include_once(G5_PATH.'/tail.sub.php');
?>
