<?php
	include_once('./_common.php');
	include_once('./evt.err.php'); 	# $sub_menu = ($EP_IDX > 0) ? '980100' : '990100'

	auth_check($auth[$sub_menu], "r");


	# chk : rfv.
	if ($EM_IDX <= 0) {
		header("location:evt.list.php?".$epTail);
		exit();
	}


	// if (empty($ej_wdate1) || ! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $ej_wdate1) ) $ej_wdate1 = G5_TIME_YMD;
	// if (empty($ej_wdate2) || ! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $ej_wdate2) ) $ej_wdate2 = G5_TIME_YMD;
	
	$ord = $_GET['ord'];
	$ord = ((int)$ord >= 1 && (int)$ord <= 2) ? (int)$ord : (int)'2';
	
	$sts = $_GET['sts'];
	$sts = ((int)$sts >= 1 && (int)$sts <= 2) ? (int)$sts : '';

	$stx = $_GET['stx'];


	# wh
	$wh = "Where EJ.EJ_DDATE is null And EJ.EM_IDX = '" . mres($EM_IDX) . "'";
	
	if ($ej_wdate1 != '') {
		$wh .= " And EJ.EJ_WDATE >= '" . mres($ej_wdate1) . "'";
	}
	if ($ej_wdate2 != '') {
		$wh .= " And EJ.EJ_WDATE <= '" . mres($ej_wdate2) . "'";
	}

	if ($stx != '') {
		$wh .= " And EJ.EJ_NAME like '%" . mres($stx) . "%'";
	}

	if ($sts != '') {
		$wh .= " And EJ.EJ_STATUS = '" . mres($sts) . "'";
	}


	# ord
	switch ($ord) {
		case '1':	# 신청 오래된순
			$_ord = ' Order By EJ.EJ_IDX Asc';
			break;

		case '2':	# 신청 최신순
			$_ord = ' Order By EJ.EJ_IDX Desc';
			break;

		default:	# default
			$_ord = ' Order By EJ.EJ_IDX Asc';
			break;
	}


	$sql = "Select Count(EJ.EJ_IDX) As cnt"
		."	From NX_EVENT_JOIN As EJ"
		."		Inner Join NX_EVENT_MASTER As EM On EM.EM_IDX = EJ.EM_IDX"
		."			And EM.EM_DDATE is null"
		."	{$wh}"
		;
	$row = sql_fetch($sql);
	$total_count = $row['cnt'];

	$rows = $config['cf_page_rows'];
	$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
	if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
	$from_record = ($page - 1) * $rows; // 시작 열을 구함


	$sql = "Select EJ.EJ_IDX, EJ.EJ_NAME, EJ.EJ_MOBILE, EJ.EJ_EMAIL, EJ.EJ_ORG, EJ.EJ_STATUS"
		."		, DATE_FORMAT(EJ.EJ_WDATE, '%Y-%m-%d %H:%i') As EJ_WDATE"
		."	From NX_EVENT_JOIN As EJ"
		."		Inner Join NX_EVENT_MASTER As EM On EM.EM_IDX = EJ.EM_IDX"
		."			And EM.EM_DDATE is null"
		."	{$wh}"
		."	{$_ord}"
		."	Limit {$from_record}, {$rows}"
		;
	$row = sql_query($sql);


	# 관리자 개인정보 접근이력 기록
	nx_privacy_log('list', 'NX_EVENT_MASTER', $EM_IDX);


	$g5[title] = ($sub_menu == '980100') ? "공모사업 행사 신청자" : "신청자";
	include_once('../admin.head.php');
	include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');


	# 신청자 최종 확인일 update
	if ($page == 1) {
		$sql = "Update NX_EVENT_MASTER Set EM_EJ_R_DATE = now() Where EM_IDX = '" . mres($EM_IDX) . "' Limit 1";
		sql_query($sql);
	}
?>

<div class="taR mb10">
	<a href="./evt.list.php?<?php echo($epTail)?>" class="nx-btn-b3">뒤로</a>
</div>
<div class="sch_box">
	<form id="frmSch" name="frmSch" method="get" action="<?php echo($_SERVER['PHP_SELF'])?>">
	<input type="submit" style="width:1px;height:1px;position:absolute;top:-10000px;left:-10000px" />
	<input type="hidden" id="EM_IDX" name="EM_IDX" value="<?php echo($EM_IDX)?>" />
	<input type="hidden" id="EP_IDX" name="EP_IDX" value="<?php echo($EP_IDX)?>" />
	
	<div class="sch_opt1">
		<div class="sch_opt2">
			<span class="sch_term" data-tit="신청일">
				<input type="text" id="ej_wdate1" name="ej_wdate1" class="nx-ips1" value="<?php echo($ej_wdate1)?>" style="max-width:140px;">

				<span class="tilde">~</span>

				<input type="text" id="ej_wdate2" name="ej_wdate2" class="nx-ips1" value="<?php echo($ej_wdate2)?>" style="max-width:140px;">
			</span>

			<div class="res_wdtmh" style="height:10px;"></div>

			<span class="sch_state" data-tit="상태">
				<a href="?<?php echo($epTail)?>EM_IDX=<?php echo($EM_IDX)?>&sts=" class="btn_sch_term <?php if ($sts == '') echo('aon');?>">전체</a>
				<a href="?<?php echo($epTail)?>EM_IDX=<?php echo($EM_IDX)?>&sts=2" class="btn_sch_term <?php if ($sts == '2') echo('aon');?>">승인만</a>
				<a href="?<?php echo($epTail)?>EM_IDX=<?php echo($EM_IDX)?>&sts=1" class="btn_sch_term <?php if ($sts == '1') echo('aon');?>">미승인만</a>
			</span>

			<span class="nx-slt" data-tit="정렬">
				<select id="ord" name="ord" style="min-width: 140px">
					<option value="1" <?php if ($ord == '1') echo('selected');?>>신청 오래된순</option>
					<option value="2" <?php if ($ord == '2') echo('selected');?>>신청 최신순</option>
				</select>
				<span class="ico_select"></span>
			</span>

			<span class="sch_ipt wm2" data-tit="신청자명">
				<input type="text" id="stx" name="stx" class="nx-ips1" value="<?php echo(F_hsc($stx))?>" />
			</span>
		</div>
	</div>

	<a href="javascript:void(0);" onclick="$('#frmSch').submit();" class="btn_sch"><span class="ico_search2"></span></a>

	</form>
</div>

<div class="taR mb10">
	<a href="javascript:void(0)" onclick="auth.do({m:'2'});" class="nx-btn-b2">선택 승인</a>
	<a href="javascript:void(0)" onclick="auth.do({m:'1'});" class="nx-btn-b2">선택 미승인</a>
	<a href="javascript:void(0)" onclick="auth.do({m:'all'});" class="nx-btn-b2">신청자 일괄승인</a>
	<?php /*<a href="" class="nx-btn-b2">SMS 발송</a>*/ ?>
</div>

<table border="0" cellpadding="0" cellspacing="0" class="nx-t-list1">
	<colgroup>
		<col width="50"><col width="8%"><col width="10%"><col width="15%"><col width=""><col width=""><col width="15%"><col width="8%">
	</colgroup>
	<thead>
		<tr>
			<th>
				<input type="checkbox" id="chkAll" name="chkAll" class="chk1" onclick="chk.all()"><label for="chkAll"><span class="chkbox"><span class="ico_check"></span></span></label>
			</th>
			<th>NO.</th>
			<th>신청자명</th>
			<th>휴대폰</th>
			<th>이메일</th>
			<th>소속</th>
			<th>신청시간</th>
			<th>상태</th>
		</tr>
	</thead>
	<tbody>
		<?php
		if (sql_num_rows($row) <= 0) {
			echo '<tr><td colspan="8">신청자가 없습니다.</td></tr>';
		}
		else {
			$s = 0;
			while ($rs1 = sql_fetch_array($row)) {
				?>
		<tr>
			<td>
				<input type="checkbox" id="chkItem_<?php echo($rs1['EJ_IDX'])?>" name="chkItem" class="chk1" value="<?php echo($rs1['EJ_IDX'])?>" onclick="chk.item()" /><label for="chkItem_<?php echo($rs1['EJ_IDX'])?>"><span class="chkbox"><span class="ico_check"></span></span></label>
			</td>
			<td><?php echo($total_count - $from_record - $s)?></td>
			<td><a href="javascript:onclRead('<?php echo($rs1['EJ_IDX'])?>');" class="color-tit"><?php echo(F_hsc($rs1['EJ_NAME']))?></a></td>
			<td><?php echo(F_hsc($rs1['EJ_MOBILE']))?></td>
			<td><?php echo(F_hsc($rs1['EJ_EMAIL']))?></td>
			<td><?php echo(F_hsc($rs1['EJ_ORG']))?></td>
			<td><?php echo($rs1['EJ_WDATE'])?></td>
			<td><?php echo(($rs1['EJ_STATUS'] == '2') ? '승인' : '<span style="color:#ff0000">미승인</span>')?></td>
		</tr>
				<?php
				$s++;
			}
		}
		?>
	</tbody>
</table>

<div class="taR mt10">
	<a href="javascript:void(0)" onclick="auth.do({m:'2'});" class="nx-btn-b2">선택 승인</a>
	<a href="javascript:void(0)" onclick="auth.do({m:'1'});" class="nx-btn-b2">선택 미승인</a>
	<a href="javascript:void(0)" onclick="auth.do({m:'all'});" class="nx-btn-b2">신청자 일괄승인</a>
	<?php /*<a href="" class="nx-btn-b2">SMS 발송</a>*/ ?>
</div>

<?php
	$qstr .= "&amp;EM_IDX=".$EM_IDX."&amp;page=";

	$pagelist = get_paging($config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr");
	echo $pagelist;
?>

<script>
//<![CDATA[
$(function(){
    $("#ej_wdate1, #ej_wdate2").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "2016:c", maxDate: "+0d" });
});

var chk = {
	all: function() {
		$(':checkbox[name="chkItem"]').prop('checked', (($('#chkAll').prop('checked')) ? true : false));
	}
	, item: function() {
		var cnt = $(':checkbox[name="chkItem"]:not(:checked)').length;
		$('#chkAll').prop('checked', ((cnt == 0) ? true : false));
	}
}

var auth = {
	do: function(o) {
		var Z = this;
		var def = {m:''};
		var o = $.extend({}, def, o);

		if (o.m == 'all') {
			var f = new FormData();
			f.append('m', o.m);
			f.append('EM_IDX', $('#EM_IDX').val());

			Z.act(f);
		}
		else if (o.m == '1' || o.m == '2')
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

			Z.act(f);
		}
	}
	, act: function(form) {
		if (form === undefined) return;

		if (confirm("선택하신 항목으로 진행하시겠습니까?"))
		{
			$.ajax({
				url: 'evt.joinProc.php?<?php echo($epTail)?>',
				type: 'POST',
				dataType: 'json',
				data: form,
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
}

var onclRead = function(c) {
	if (c == '' || isNaN(c)) return;

	window.open('evt.join.read.php?EM_IDX='+$('#EM_IDX').val()+'&EJ_IDX='+c, 'evt_join_read', 'width=500,height=600,scrollbars=yes');
}
//]]>
</script>

<?php
	//마지막 인클루드
	include_once G5_ADMIN_PATH."/admin.tail.php";
?>
