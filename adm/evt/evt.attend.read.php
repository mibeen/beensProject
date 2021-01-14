<?php
	include_once('./_common.php');
	include_once('./evt.err.php'); 	# $sub_menu = ($EP_IDX > 0) ? '980100' : '990100'

	auth_check($auth[$sub_menu], "r");

	$g5[title] = "사업관리";
	include_once("../inc/pop.top.php");


	# set : variables
	$EJ_IDX = (isset($_POST['EJ_IDX']) && $_POST['EJ_IDX'] != '') ? $_POST['EJ_IDX'] : $_GET['EJ_IDX'];


	# re-define
	$EJ_IDX = CHK_NUMBER($EJ_IDX);

	# chk : rfv.
	if ($EM_IDX <= 0 || $EJ_IDX <= 0) {
		exit();
	}


	# wh
	$wh = "Where EJ.EJ_DDATE is null And EJ.EM_IDX = '" . mres($EM_IDX) . "' And EJ.EJ_IDX = '" . mres($EJ_IDX) . "'";


	$sql = "Select EJ.*"
		."		, DATE_FORMAT(EJ.EJ_WDATE, '%Y-%m-%d %H:%i') As EJ_WDATE"
		."	From NX_EVENT_JOIN As EJ"
		."		Inner Join NX_EVENT_MASTER As EM On EM.EM_IDX = EJ.EM_IDX"
		."			And EM.EM_DDATE is null"
		."	{$wh}"
		."	Order By EJ.EJ_IDX Desc"
		."	Limit 1"
		;
	$db1 = sql_query($sql);
	$rs1 = sql_fetch_array($db1);

	if (sql_num_rows($db1) <= 0) {
		unset($rs1, $db1);
		F_script("잘못된 접근 입니다.", "self.close();");
	}


	# 관리자 개인정보 접근이력 기록
	nx_privacy_log('read', 'NX_EVENT_JOIN', $EJ_IDX);
?>

<form id="frmAct" name="frmAct" onsubmit="return false">
<input type="submit" style="width:1px;height:1px;position:absolute;top:-10000px;left:-10000px" />
<input type="hidden" id="m" name="m" value="edit" />
<input type="hidden" id="EM_IDX" name="EM_IDX" value="<?php echo($EM_IDX)?>" />
<input type="hidden" id="EJ_IDX" name="EJ_IDX" value="<?php echo($EJ_IDX)?>" />

<div style="padding: 15px;">
	<h1 class="nx-tit1">신청 정보</h1>
	<table border="0" cellpadding="0" cellspacing="0" class="nx-t-read2">
		<colgroup>
			<col width="130"><col width="">
		</colgroup>
		<tbody>
			<tr>
				<th>참석자 이름</th>
				<td><?php echo(F_hsc($rs1['EJ_NAME']))?></td>
			</tr>
			<tr>
				<th>접수번호</th>
				<td><?php echo(F_hsc($rs1['EJ_JOIN_CODE']))?></td>
			</tr>
			<tr>
				<th>참석여부</th>
				<td>
					<div class="chk1_wrap">
						<input type="checkbox" id="EJ_JOIN_CHK" name="EJ_JOIN_CHK" class="chk1" value="Y" <?php if ($rs1['EJ_JOIN_CHK1'] == 'Y' && $rs1['EJ_JOIN_CHK2'] == 'Y') echo('checked');?> /><label for="EJ_JOIN_CHK"><span class="chkbox"><span class="ico_check"></span></span></label>
					</div>
				</td>
			</tr>
			<tr>
				<th>비고</th>
				<td>
					<textarea id="EJ_MEMO" name="EJ_MEMO" class="nx-ips1" style="min-height: 100px;"><?php echo(F_hsc($rs1['EJ_MEMO']))?></textarea>
				</td>
			</tr>
		</tbody>
	</table>

	<div class="taR mt20">
		<a href="javascript:void(0)" onclick="onsu()" class="nx-btn2">저장</a>
		<a href="javascript:void(0)" onclick="self.close()" class="nx-btn3">닫기</a>
	</div>
</div>

</form>

<script>
//<![CDATA[
var onsu = function() {
	if (confirm("입력하신 사항으로 진행하시겠습니까?"))
	{
		$.ajax({
			url: 'evt.attendProc.php?<?php echo($epTail)?>',
			type: 'POST',
			dataType: 'json',
			data: $('#frmAct').serialize()
		})
		.done(function(json) {
			if (!json.success) {
				if (json.msg) alert(json.msg);
				return;
			}

			if (json.msg) alert(json.msg);
			opener.location.reload();
			self.close();
		})
		.fail(function(a, b, c) {
			alert(a.statusText+' ('+a.status+')\n\n'+a.responseText);
			//alert(a.responseText);
		});
	}
}
//]]>
</script>
<?php
	include_once("../inc/pop.btm.php");
?>
