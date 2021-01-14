<?php
	include_once("./_common.php");


	// 테마설정
	$at = apms_gr_thema();
	if(!defined('THEMA_PATH')) {
		include_once(G5_LIB_PATH.'/apms.thema.lib.php');
	}

	include_once(G5_PATH.'/head.sub.php');
	if(!USE_G5_THEME) @include_once(THEMA_PATH.'/head.sub.php');


	# 로그인 하지 않고 접근 불가
	if (is_null($member['mb_id'])) {
		F_script("잘못된 접근 입니다.", 'parent.$(\'#viewModal\').modal(\'hide\');');
	}


	# set : variables
	$EM_IDX = $_GET['EM_IDX'];


	# re-define
	$EM_IDX = CHK_NUMBER($EM_IDX);
	if ($EM_IDX <= 0) {
		F_script("잘못된 접근 입니다.", 'parent.$(\'#viewModal\').modal(\'hide\');');
	}


	$sql = "Select EJ.*"
		."		, EM.*"
		."	From NX_EVENT_JOIN As EJ"
		."		Inner Join NX_EVENT_MASTER As EM On EM.EM_IDX = EJ.EM_IDX"
		."			And EM.EM_DDATE is null"
		."	Where EJ.EJ_DDATE is null"
		."		And EJ.EM_IDX = '" . mres($EM_IDX) . "'"
		."		And EJ.mb_id = '" . mres($member['mb_id']) . "'"
		."	Order By EJ.EJ_IDX Desc"
		."	Limit 1"
		;
	$rs1 = sql_fetch($sql);
	if (is_null($rs1['EM_IDX'])) {
		unset($rs1);
		F_script("존재하지 않는 정보 입니다.", 'parent.$(\'#viewModal\').modal(\'hide\');');
	}
	$DB_EJ = $rs1;
	unset($rs1);


	# get : theme 정보
	$sql ="select data_1 from g5_apms_data where type ='11'";
	$row = sql_fetch($sql);

	define('THEMA_URL', G5_URL.'/thema/'.$row['data_1']);
?>
<link rel="stylesheet" href="<?php echo(THEMA_URL)?>/colorset/Basic/colorset.css" type="text/css">

<div id="title-outer" class="event_apply_top" style="display:none">
	<div class="top_tit">참가신청</div>
	<a href="javascript:void(0);" onclick="ejoin.close();" class="cls"><span class="ico_x"></span></a>
</div>

<div id="cont-outer" class="event_apply">
	<?php
	/*
	<p class="page_tit">행사정보</p>

	$wname = array('일','월','화','수','목','금','토');
	
	echo '<p class="event_date">행사기간 : <span class="dsIB"><span class="dsIB">';
	echo $DB_EJ['EM_S_DATE'].' ('.$wname[date('w', strtotime($DB_EJ['EM_S_DATE']))].') '.substr($DB_EJ['EM_S_TIME'], 0, 5);
	echo ' ~</span> <span class="dsIB">';
	echo $DB_EJ['EM_E_DATE'].' ('.$wname[date('w', strtotime($DB_EJ['EM_E_DATE']))].') '.substr($DB_EJ['EM_E_TIME'], 0, 5);
	echo '</span></span></p>';
	
	unset($wname);
	*/
	?>

	<p class="tit"><span class="name"><?php echo(F_hsc($DB_EJ['EM_TITLE']))?></span></p>

	<p class="page_tit br_t mt30">참가자 정보 <span class="red small">(* : 필수 입력 항목)</span></p>
	<table cellspacing="0" cellpadding="0" border="0" class="event_ts1">
		<colgroup>
			<col width="100px" /><col width="" />
		</colgroup>
		<tr>
			<th>이름<span class="red">*</span></th>
			<td style="height:40px;line-height:40px"><?php
				echo(F_hsc($DB_EJ['EJ_NAME']));
			?></td>
		</tr>
		<tr>
			<th>연락처<span class="red">*</span></th>
			<td style="height:40px;line-height:40px"><?php
				$_t = $DB_EJ['EJ_MOBILE'];
				if ($_t != '') {
					echo '<a href="tel:'.$_t.'">';
					echo F_hsc($_t);
					echo '</a>';
				} unset($_t);
			?></td>
		</tr>
		<tr>
			<th>이메일<span class="red">*</span></th>
			<td style="height:40px;line-height:40px"><?php
				$_t = $DB_EJ['EJ_EMAIL'];
				if ($_t != '') {
					echo '<a href="mailto:'.$_t.'">';
					echo F_hsc($_t);
					echo '</a>';
				} unset($_t);
			?></td>
		</tr>
		<tr>
			<th>소속<span class="red">*</span></th>
			<td style="height:40px;line-height:40px"><?php
				echo(F_hsc($DB_EJ['EJ_ORG']));
			?></td>
		</tr>
		<?php
		if ($DB_EJ['EM_REQUIRE_BIRTH_YN'] == 'Y') {
			?>
		<tr>
			<th>생년월일<span class="red">*</span></th>
			<td style="height:40px;line-height:40px"><?php
				echo(F_hsc($DB_EJ['EJ_BIRTH']));
			?></td>
		</tr>
			<?php
		}
		?>
		
		<?php
			# form 입력 항목
			$EJ_IDX = $DB_EJ['EJ_IDX'];
			include_once "evt.join.form.item.read.php";
		?>

	</table>

	<div class="taR mt20">
		<a href="javascript:void(0)" onclick="ejoin.cancel();" class="event_apply_btn3">신청취소</a>
		<a href="javascript:void(0)" onclick="ejoin.rejoin();" class="event_apply_btn2">재신청</a>
	</div>

	<div class="taC mt20">
		<a href="javascript:void(0)" onclick="ejoin.close();" class="event_apply_btn2">닫기</a>
	</div>
</div>

<script>
//<![CDATA[
var ejoin = {
	rejoin: function() {
		if (confirm("재신청하면 현재 입력된 내용은 없어지고 다시 내용을 등록해야합니다.\r\n재신청하시겠습니까?")) {
			window.location.href = "evt.join.php?EM_IDX=<?php echo($EM_IDX)?>&REJOIN=Y";
		}
	},
	cancel: function() {
		if (confirm("신청을 취소하시겠습니까?")) {
			// var f = new FormData();		// ie 9이하에서 지원안하여 대체
			// f.append('EM_IDX', '<?php echo($EM_IDX)?>');

			$.ajax({
				url: 'evt.cancelProc.php',
				type: 'POST',
				dataType: 'json',
				data: {EM_IDX:'<?php echo($EM_IDX)?>'},
			})
			.done(function(json) {
				if (!json.success) {
					if (json.msg) alert(json.msg);
					return;
				}

				if (json.msg) alert(json.msg);
				if (opener) {
					if (json.redir) opener.location.href = json.redir;
					self.close();
				}
				else {
					if (json.redir) parent.location.href = json.redir;
					// parent.$('#viewModal').modal('hide');
				}
			})
			.fail(function(a, b, c) {
				// alert(a.statusText+' ('+a.status+')\n\n'+a.responseText);
			});
		}
	}
	, close: function() {
		if (opener) {
			self.close();
		}
		else {
			parent.$('#viewModal').modal('hide');
		}
	}
}

<?php /* 새창으로 열었을 경우 title, cont 영역 수정 */ ?>
$(function() {
	if (opener) {
		$('#title-outer').css({marginBottom:'20px'}).show();
		$('#cont-outer').css({padding:'0 20px 40px 20px'}).show();
	}
});
//]]>
</script>
<?php
	if(!USE_G5_THEME) @include_once(THEMA_PATH.'/tail.sub.php');
	include_once(G5_PATH.'/tail.sub.php');
?>
