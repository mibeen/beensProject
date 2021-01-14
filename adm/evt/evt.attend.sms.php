<?php
	include_once('./_common.php');
	include_once('./evt.err.php'); 	# $sub_menu = ($EP_IDX > 0) ? '980100' : '990100'


	auth_check($auth[$sub_menu], "w");


	# set : variables
	$EM_IDX = $_POST['EM_IDX'];
	$t_idxs = $_POST['t_idxs'];


	# re-define
	$EM_IDX = CHK_NUMBER($EM_IDX);


	# chk : rfv.
	if ($t_idxs == '') {
		F_script("대상 정보가 전달되지 않았습니다.", "self.close();");
	}


	# wh : 승인된 신청자 중에서 보임
	$wh = "Where EJ.EJ_DDATE is null And EJ.EM_IDX = '" . mres($EM_IDX) . "' And EJ.EJ_STATUS = '2'";


	# wh : sms 발송 대상 참석자 목록
	$_tstr = array();
	$_ts = explode('|', $t_idxs);
	for ($i = 0; $i < Count($_ts); $i++) {
		$_t = $_ts[$i];
		if (CHK_NUMBER($_t) <= 0) continue;

		$_tstr[] = $_t;
	}

	$t_wh = '';
	if (Count($_tstr) > 0) {
		$t_wh = "'" . implode("','", $_tstr) . "'";
		$wh .= " And EJ.EJ_IDX in ({$t_wh})";
	}


	$sql = "Select EJ.EJ_IDX, EJ.EJ_NAME, EJ.EJ_MOBILE"
		."	From NX_EVENT_JOIN As EJ"
		."		Inner Join NX_EVENT_MASTER As EM On EM.EM_IDX = EJ.EM_IDX"
		."			And EM.EM_DDATE is null"
		."	{$wh}"
		."	Order By EJ.EJ_IDX Desc"
		;
	$db1 = sql_query($sql);

	if (sql_num_rows($db1) <= 0) {
		unset($db1);
		F_script("대상 정보가 존재하지 않습니다.", "self.close();");
	}


	include_once("../inc/pop.top.php");
?>

<form id="frmAct" name="frmAct" onsubmit="return false">
<input type="hidden" id="EM_IDX" name="EM_IDX" value="<?php echo($EM_IDX)?>" />
<input type="hidden" id="t_idxs" name="t_idxs" value="<?php echo($t_idxs)?>" />

<div style="padding: 15px;">
	<h1 class="nx-tit1">SMS 발송</h1>
	<table border="0" cellpadding="0" cellspacing="0" class="nx-t-read2">
		<colgroup>
			<col width="130"><col width="">
		</colgroup>
		<tbody>
			<?php
			$rs1 = sql_fetch_array($db1);
			$_str = $rs1['EJ_NAME'];
			unset($rs1);

			if (sql_num_rows($db1) > 1) {
				$_str .= ' 외 '.(sql_num_rows($db1) - 1).'명';
			}
			?>
			<tr>
				<th>대상</th>
				<td><?php echo($_str)?></td>
			</tr>
			<tr>
				<th>제목</th>
				<td><input type="text" id="ESH_TITLE" name="ESH_TITLE" maxlength="100" class="nx-ips1 wl"></td>
			</tr>
			<tr>
				<th>내용</th>
				<td>
					<textarea id="ESH_CONT" name="ESH_CONT" class="nx-ips1" style="max-width:150px;min-height: 180px;"></textarea>
				</td>
			</tr>
		</tbody>
	</table>
	<div class="taC mt20">
		<a href="javascript:void(0)" onclick="sms.send();" class="nx-btn2">보내기</a>
		<a href="javascript:void(0)" onclick="sms.close();" class="nx-btn3">닫기</a>
	</div>
</div>

</form>

<script>
//<![CDATA[
var sms = {
	send: function() {
		var _t = $('#ESH_TITLE');
		if (_t.val() == '') {
			alert("제목 정보를 입력해 주세요.");
			_t.focus(); return;
		}

		var _t = $('#ESH_CONT');
		if (_t.val() == '') {
			alert("내용 정보를 입력해 주세요.");
			_t.focus(); return;
		}

		if (confirm("입력된 정보로 진행하시겠습니까?")) {
			$.ajax({
				url: 'evt.attend.smsProc.php?<?php echo($epTail)?>',
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
				self.close();
			})
			.fail(function(a, b, c) {
				alert(a.statusText+' ('+a.status+')\n\n'+a.responseText);
			});		
		}
	}
	, close: function() {
		self.close();
	}
}
//]]>
</script>
<?php
	include_once("../inc/pop.btm.php");
?>
