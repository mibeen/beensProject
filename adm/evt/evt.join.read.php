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


	$sql = "Select EM.EM_REQUIRE_BIRTH_YN"
		."		, EJ.*"
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

<div style="padding: 15px;">
	<h1 class="nx-tit1">신청 정보</h1>
	
	<input type="hidden" id="EM_IDX" name="EM_IDX" value="<?php echo($EM_IDX)?>" />
	<input type="hidden" id="EJ_IDX" name="EJ_IDX" value="<?php echo($EJ_IDX)?>" />
	
	<table border="0" cellpadding="0" cellspacing="0" class="nx-t-read2">
		<colgroup>
			<col width="130"><col width="">
		</colgroup>
		<tbody>
			<tr>
				<th>신청자 이름</th>
				<td><?php echo(F_hsc($rs1['EJ_NAME']))?></td>
			</tr>
			<tr>
				<th>소속</th>
				<td><?php echo(F_hsc($rs1['EJ_ORG']))?></td>
			</tr>
			<tr>
				<th>휴대폰번호</th>
				<td><?php echo(F_hsc($rs1['EJ_MOBILE']))?></td>
			</tr>
			<tr>
				<th>이메일</th>
				<td><?php echo(F_hsc($rs1['EJ_EMAIL']))?></td>
			</tr>
			<?php
			if ($rs1['EM_REQUIRE_BIRTH_YN'] == 'Y') {
				?>
			<tr>
				<th>생년월일</th>
				<td><?php echo(F_hsc($rs1['EJ_BIRTH']))?></td>
			</tr>
				<?php
			}
			?>
			
			<?php
			$_mb_id_ = $rs1['mb_id'];
			include "evt.join.form.item.php";
			?>
			<!-- <tr>
				<th>폼빌더항목1</th>
				<td>
					<div class="radio1_wrap">
						<input type="radio" id="aa1" name="aa" class="radio1" checked disabled><label for="aa1"><span class="radbox"><span></span></span><span class="txt">라디오1</span></label>
						<input type="radio" id="aa2" name="aa" class="radio1" disabled><label for="aa2"><span class="radbox"><span></span></span><span class="txt">라디오2</span></label>
					</div>
				</td>
			</tr>
			<tr>
				<th>폼빌더항목2</th>
				<td>
					<div class="chk1_wrap">
						<input type="checkbox" id="bb1" name="bb" class="chk1" checked disabled><label for="bb1"><span class="chkbox"><span class="ico_check"></span></span><span class="txt">체크박스1</span></label>
						<input type="checkbox" id="bb2" name="bb" class="chk1" disabled><label for="bb2"><span class="chkbox"><span class="ico_check"></span></span><span class="txt">체크박스2</span></label>
					</div>
				</td>
			</tr>
			<tr>
				<th>폼빌더항목3</th>
				<td></td>
			</tr> -->

		</tbody>
	</table>
	<div class="taR mt20">
		<?php
		if ($rs1['EJ_STATUS'] == '1') {
			echo '<a href="javascript:void(0)" onclick="auth.do({m:\'2\'})" class="nx-btn2">승인</a>';
		}
		else if ($rs1['EJ_STATUS'] == '2') {
			echo '<a href="javascript:void(0)" onclick="auth.do({m:\'1\'})" class="nx-btn4">승인취소</a>';
		}
		?>
		<a href="javascript:void(0)" onclick="self.close()" class="nx-btn3">닫기</a>
	</div>
</div>

<script>
//<![CDATA[
var auth = {
	do: function(o) {
		var Z = this;
		var def = {m:''};
		var o = $.extend({}, def, o);

		if ((['1','2']).indexOf(o.m) === -1) return;
		
		var f = new FormData();
		f.append('m', o.m);
		f.append('EM_IDX', $('#EM_IDX').val());
		f.append('v', $('#EJ_IDX').val());

		var str = (o.m == '1') ? "승인취소 하시겠습니까?" : "승인 하시겠습니까?";
		if (confirm(str)) {
			$.ajax({
				url: 'evt.joinProc.php?<?php echo($epTail)?>',
				type: 'POST',
				dataType: 'json',
				data: f,
				processData: false, 
				contentType: false
			})
			.done(function(json) {
				if (!json.success) {
					if (json.msg) alert(json.msg);
					return;
				}

				if (json.msg) alert(json.msg);
				opener.location.reload();
				window.location.reload();
			})
			.fail(function(a, b, c) {
				alert(a.statusText+' ('+a.status+')\n\n'+a.responseText);
				//alert(a.responseText);
			});
		}
	}
}
//]]>
</script>
<?php
	include_once("../inc/pop.btm.php");
?>
