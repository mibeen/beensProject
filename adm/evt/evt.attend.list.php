<?php
	include_once('./_common.php');
	include_once('./evt.err.php'); 	# $sub_menu = ($EP_IDX > 0) ? '980100' : '990100'

	auth_check($auth[$sub_menu], "r");


	# chk : rfv.
	if ($EM_IDX <= 0) {
		header("location:evt.list.php?".$epTail);
		exit();
	}


	$sts = $_GET['sts'];
	$sts = ((int)$sts >= 1 && (int)$sts <= 2) ? (int)$sts : '';

	$stx = $_GET['stx'];


	# wh : 승인된 신청자 중에서 보임
	$wh = "Where EJ.EJ_DDATE is null And EJ.EM_IDX = '" . mres($EM_IDX) . "' And EJ.EJ_STATUS = '2'";
	
	# 신청자명/접수번호
	if ($stx != '') {
		$wh .= " And (EJ.EJ_NAME like '%" . mres($stx) . "%' Or EJ.EJ_JOIN_CODE = '" . mres($stx) . "')";
	}

	# sts (status)
	switch ($sts) {
		case '1':	# 미참석
			$wh .= " And (EJ.EJ_JOIN_CHK1 = 'N' And EJ.EJ_JOIN_CHK2 = 'N')";
			break;

		case '2':	# 참석
			$wh .= " And (EJ.EJ_JOIN_CHK1 = 'Y' And EJ.EJ_JOIN_CHK2 = 'Y')";
			break;
		
		default:
			break;
	}


	$sql = "Select EJ.EJ_IDX, EJ.EJ_NAME, EJ.EJ_MOBILE, EJ.EJ_EMAIL, EJ.EJ_ORG"
		."		, EJ.EJ_STATUS, EJ.EJ_JOIN_CODE, EJ.EJ_JOIN_CHK1, EJ.EJ_JOIN_CHK2"
		."		, DATE_FORMAT(EJ.EJ_WDATE, '%Y-%m-%d %H:%i') As EJ_WDATE"
		."		, DATE_FORMAT(EM.EM_S_DATE, '%Y%m%d') As EM_S_DATE"
		."		, (Select DATE_FORMAT(EP.EP_WDATE, '%Y-%m-%d %H:%i') From NX_EVENT_PRINT As EP Where EP.EJ_IDX = EJ.EJ_IDX And EP.mb_id = EJ.mb_id Order By EP_IDX Desc Limit 0, 1) As EP_WDATE"
		."	From NX_EVENT_JOIN As EJ"
		."		Inner Join NX_EVENT_MASTER As EM On EM.EM_IDX = EJ.EM_IDX"
		."			And EM.EM_DDATE is null"
		."	{$wh}"
		."	Order By EJ.EJ_IDX Desc"
		;
	$row = sql_query($sql);


	# get : 행사명
	$db1 = sql_fetch("Select EM_TITLE From NX_EVENT_MASTER Where EM_IDX = '" . mres($EM_IDX) . "' Limit 1");
	if (!is_null($db1['EM_TITLE'])) {
		$EM_TITLE = $db1['EM_TITLE'];
	}


	# 관리자 개인정보 접근이력 기록
	nx_privacy_log('list', 'NX_EVENT_MASTER', $EM_IDX);


	$g5[title] = ($sub_menu == '980100') ? "공모사업 행사 참석관리" : "참석관리";
	include_once('../admin.head.php');
?>

<h3 class="nx-tit1 lh30 mb" style="margin-top:0px">
	<a href="evt.list.php?<?php echo($epTail)?>" class="nx-btn-b3 fR ml15">뒤로</a>
	<?php echo(F_hsc($EM_TITLE))?>
</h3>

<div class="sch_box">
	<form id="frmSch" name="frmSch" method="get" action="<?php echo($_SERVER['PHP_SELF'])?>">
	<input type="submit" style="width:1px;height:1px;position:absolute;top:-10000px;left:-10000px" />
	<input type="hidden" name="EP_IDX" value="<?php echo($EP_IDX)?>">
	<input type="hidden" id="EM_IDX" name="EM_IDX" value="<?php echo($EM_IDX)?>" />
	
	<div class="sch_opt1">
		<div class="sch_opt2">
			<span class="sch_state" data-tit="참석여부">
				<a href="?<?php echo($epTail)?>EM_IDX=<?php echo($EM_IDX)?>&sts=" class="btn_sch_term <?php if ($sts == '') echo('aon');?>">전체</a>
				<a href="?<?php echo($epTail)?>EM_IDX=<?php echo($EM_IDX)?>&sts=2" class="btn_sch_term <?php if ($sts == '2') echo('aon');?>">참석만</a>
				<a href="?<?php echo($epTail)?>EM_IDX=<?php echo($EM_IDX)?>&sts=1" class="btn_sch_term <?php if ($sts == '1') echo('aon');?>">미참석만</a>
			</span>


			<span class="sch_ipt wm2" data-tit="신청자명">
				<input type="text" id="stx" name="stx" class="nx-ips1" value="<?php echo(F_hsc($stx))?>" placeholder="신청자명/접수번호" />
			</span>
		</div>
	</div>

	<a href="javascript:void(0);" onclick="$('#frmSch').submit();" class="btn_sch"><span class="ico_search2"></span></a>

	</form>
</div>

<div class="ofH mb10">
	<div class="fL">
		<a href="javascript:void(0)" onclick="attend.sms();" class="nx-btn-b2">선택 SMS발송</a>
		<a href="javascript:void(0)" onclick="attend.smslist();" class="nx-btn-b2">SMS 발송내역</a>
		<a href="javascript:void(0)" onclick="attend.excel();" class="nx-btn-b1">엑셀다운로드</a>
	</div>
	<div class="fR">
		<a href="javascript:void(0)" onclick="attend.do({m:'2'});" class="nx-btn-b2">선택 참석</a>
		<a href="javascript:void(0)" onclick="attend.do({m:'1'});" class="nx-btn-b3">선택 참석취소</a>
		<a href="javascript:void(0)" onclick="attend.do({m:'allY'});" class="nx-btn-b2">일괄 참석처리</a>
		<a href="javascript:void(0)" onclick="attend.do({m:'allN'});" class="nx-btn-b3">일괄 참석취소</a>
	</div>
</div>

<table border="0" cellpadding="0" cellspacing="0" class="nx-t-list1">
	<colgroup>
		<col width="50"><col width="5%"><col width="7%"><col width="12%"><col width="14%"><col width=""><col width="12%"><col width="10%"><col width="17%"><col width="10%">
	</colgroup>
	<thead>
		<tr>
			<th>
				<input type="checkbox" id="chkAll" name="chkAll" class="chk1" onclick="chk.all()"><label for="chkAll"><span class="chkbox"><span class="ico_check"></span></span></label>
			</th>
			<th>NO.</th>
			<th>신청자명</th>
			<th>접수번호</th>
			<th>휴대폰</th>
			<th>이메일</th>
			<th>소속</th>
			<th>신청시간</th>
			<th>참석여부</th>
			<th>확인증인쇄일</th>
		</tr>
	</thead>
	<tbody>
		<?php
		if (sql_num_rows($row) <= 0) {
			echo '<tr><td colspan="10">승인된 행사 신청자가 없습니다.</td></tr>';
		}
		else {
			$s = 0;
			while ($rs1 = sql_fetch_array($row)) {
				?>
		<tr>
			<td><?php
				echo '<input type="checkbox" id="chkItem_'.$rs1['EJ_IDX'].'" name="chkItem" class="chk1" value="'.$rs1['EJ_IDX'].'" onclick="chk.item()" />';
				echo '<label for="chkItem_'.$rs1['EJ_IDX'].'"><span class="chkbox"><span class="ico_check"></span></span></label>';
			?></td>
			<td><?php echo(sql_num_rows($row) - $s)?></td>
			<td><a href="javascript:onclRead('<?php echo($rs1['EJ_IDX'])?>');" class="color-tit"><?php echo(F_hsc($rs1['EJ_NAME']))?></a></td>
			<td><?php echo($rs1['EJ_JOIN_CODE']);?></td>
			<td><?php echo(F_hsc($rs1['EJ_MOBILE']))?></td>
			<td><?php echo(F_hsc($rs1['EJ_EMAIL']))?></td>
			<td><?php echo(F_hsc($rs1['EJ_ORG']))?></td>
			<td><?php echo($rs1['EJ_WDATE'])?></td>
			<td><?php
				if ($rs1['EJ_JOIN_CHK1'] == 'Y' && $rs1['EJ_JOIN_CHK2'] == 'Y') {
					echo '<span class="dsIB color2" style="width: 50px;">참석</span> <a href="javascript:void(0)" onclick="attend.do({m:\'N\',c:\''.$rs1['EJ_IDX'].'\'})" class="nx-btn3 ml10" style="width:75px;">참석취소</a>';
				}
				else {
					echo '<span class="dsIB" style="width: 50px;">미참석</span> <a href="javascript:void(0)" onclick="attend.do({m:\'Y\',c:\''.$rs1['EJ_IDX'].'\'})" class="nx-btn2 ml10" style="width:75px;">참석</a>';
				}
			?></td>
			<td><?php echo($rs1['EP_WDATE'])?></td>
		</tr>
				<?php
				$s++;
			}
		}
		?>
	</tbody>
</table>

<div class="taR mt10">
	<a href="javascript:void(0)" onclick="attend.do({m:'2'});" class="nx-btn-b2">선택 참석</a>
	<a href="javascript:void(0)" onclick="attend.do({m:'1'});" class="nx-btn-b3">선택 참석취소</a>
	<a href="javascript:void(0)" onclick="attend.do({m:'allY'});" class="nx-btn-b2">일괄 참석처리</a>
	<a href="javascript:void(0)" onclick="attend.do({m:'allN'});" class="nx-btn-b3">일괄 참석취소</a>
</div>

<div style="display:none">
	<form id="frmSms" name="frmSms" method="post" onsubmit="return false;">
		<input type="hidden" id="sms_EM_IDX" name="EM_IDX" value="<?php echo($EM_IDX)?>" />
		<input type="hidden" id="t_idxs" name="t_idxs" value="" />
	</form>
</div>

<script>
//<![CDATA[
var attend = {
	do: function(o) {
		var Z = this;
		var def = {m:'', c:''};
		var o = $.extend({}, def, o);

		if ((['Y','N','1','2','allY','allN']).indexOf(o.m) === -1 || ((o.m == 'Y' || o.m == 'N') && (o.c == '' || isNaN(o.c)))) return;

		if (o.m == '1' || o.m == '2')
		{
			var v = '';
			$(':checkbox[name="chkItem"]:checked').each(function(e) {
				v += '|'+$(this).val();
			});

			if (v == '') {
				alert("선택한 항목이 없습니다.");
				return;
			}

			var f = new FormData();
			f.append('m', o.m);
			f.append('EM_IDX', $('#EM_IDX').val());
			f.append('v', v);
		}
		else if (o.m == 'allY' || o.m == 'allN') {
			var f = new FormData();
			f.append('m', o.m);
			f.append('EM_IDX', $('#EM_IDX').val());
		}
		else {
			var f = new FormData();
			f.append('m', o.m);
			f.append('EM_IDX', $('#EM_IDX').val());
			f.append('EJ_IDX', o.c);
		}

		var str = "";
		switch (o.m) {
			case 'Y':
				str = "참석 처리하시겠습니까?";
				break;

			case 'N':
				str = "미참석 처리하시겠습니까?";
				break;

			case '1':
				str = "선택한 신청자를 참석 취소하시겠습니까?";
				break;

			case '2':
				str = "선택한 신청자를 참석 처리하시겠습니까?";
				break;

			case 'allY':
				str = "전체 신청자를 참석 처리하시겠습니까?";
				break;

			case 'allN':
				str = "전체 신청자를 참석 취소하시겠습니까?";
				break;
		}

		if (confirm(str)) {
			$.ajax({
				url: 'evt.attendProc.php?<?php echo($epTail)?>',
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
				if (json.redir) window.location.href = json.redir;
			})
			.fail(function(a, b, c) {
				alert(a.statusText+' ('+a.status+')\n\n'+a.responseText);
				//alert(a.responseText);
			});
		}
	}
	, excel: function() {
		window.location.href = 'evt.attend.excel.php?<?php echo($epTail)?>EM_IDX='+$('#EM_IDX').val()+'&sts=<?php echo($sts)?>&stx='+$('#stx').val();
	}
	, sms: function() {
		var v = '';
		$(':checkbox[name="chkItem"]:checked').each(function(e) {
			v += '|'+$(this).val();
		});

		if (v == '') {
			alert("선택한 항목이 없습니다.");
			return;
		}

		window.open("" ,"attend_sms", "width=390, height=406, scrollbars=yes"); 
		
		var f = document.frmSms;
		f.action = 'evt.attend.sms.php?EM_IDX=' + $('#EM_IDX').val();
		f.target = 'attend_sms';
		f.t_idxs.value = v;
		f.submit();
	}
	, smslist: function() {
		window.location.href = 'evt.attend.sms.list.php?<?php echo($epTail)?>EM_IDX='+$('#EM_IDX').val();
	}
}

var chk = {
	all: function() {
		$(':checkbox[name="chkItem"]:not(:disabled)').prop('checked', (($('#chkAll').prop('checked')) ? true : false));
	}
	, item: function() {
		var cnt = $(':checkbox[name="chkItem"]:not(:checked)').length;
		$('#chkAll').prop('checked', ((cnt == 0) ? true : false));
	}
}

var onclRead = function(c) {
	if (c == '' || isNaN(c)) return;

	window.open('evt.attend.read.php?EM_IDX='+$('#EM_IDX').val()+'&EJ_IDX='+c, 'evt_attend_read', 'width=500,height=362,scrollbars=yes');
}
//]]>
</script>

<?php
	//마지막 인클루드
	include_once G5_ADMIN_PATH."/admin.tail.php";
?>
