<?php
	$sub_menu = "990410";
	include_once('./_common.php');

	auth_check($auth[$sub_menu], "r");


	# wh
	$wh = "Where NM.NM_DDATE is null";

	if($SC_START_DATE != "") {
		$wh .= " And NM.NM_WDATE >= '" . mres($SC_START_DATE) . "'";
	}

	if($SC_END_DATE != "") {
		$wh .= " And NM.NM_WDATE < '" . mres(date("Y-m-d", mktime(0, 0, 0, substr($SC_END_DATE, 5, 2), substr($SC_END_DATE, 8, 2) + 1, substr($SC_END_DATE, 0, 4)))) . "'";
	}

	if($SC_WORD != "") {
		$wh .= " And (NM.NM_NAME like '%" . mres($SC_WORD) . "%' Or NM.NM_EMAIL like '%" . mres($SC_WORD) . "%')";
	}


	$sql = "Select Count(*) As cnt"
		."	From NX_NEWSLETTER_MEMBER As NM"
		."	{$wh}"
		;
	$row = sql_fetch($sql);
	$total_count = $row['cnt'];

	$rows = $config['cf_page_rows'];
	$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
	if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
	$from_record = ($page - 1) * $rows; // 시작 열을 구함


	$sql = "Select NM.*"
		."	From NX_NEWSLETTER_MEMBER As NM"
		."	{$wh}"
		."	Order By NM.NM_IDX Desc"
		."	Limit {$from_record}, {$rows} "
		;
	$row = sql_query($sql);


	# 관리자 개인정보 접근이력 기록
	nx_privacy_log('list', 'NX_NEWSLETTER_MEMBER');


	$g5[title] = "뉴스레터 구독자";
	include_once('../admin.head.php');
	include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');
?>
<script>
//<![CDATA[
function oncl_Del() {
	var NM_IDX = "";

	$("input:checkbox[name*='NM_IDX']").each(function(e) {
		if($(this).is(":checked")) NM_IDX += ((NM_IDX == "") ? "" : ",") + $(this).val();
	});

	if(NM_IDX == "") {
		alert("삭제할 대상이 없습니다.");
		return;
	}

	if(confirm("삭제하시겠습니까?")) {
		var f = new FormData();
		f.append('NM_IDX', NM_IDX);

		$.ajax({
			url: 'subscriber.delProc.php',
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
			alert("삭제되었습니다.");
			window.location.reload();
		})
		.fail(function(a, b, c) {
			alert(a.statusText+' ('+a.status+')\n\n'+a.responseText);
			//alert(a.responseText);
		});
	}
}
//]]>
</script>
<div class="sch_box">
	<form id="frmSch" name="frmSch" method="get" action="<?php echo($_SERVER['PHP_SELF'])?>">
	<input type="submit" style="width:1px;height:1px;position:absolute;top:-10000px;left:-10000px" />

	<div class="sch_opt1">
		<div class="sch_opt2">
			<span class="sch_term" data-tit="구독신청일">
				<input type="text" id="SC_START_DATE" name="SC_START_DATE" value="<?php echo(F_hsc($SC_START_DATE))?>" maxlength="10" class="nx-ips1" style="max-width:140px;" />
				<a href="javascript:void(0);" onclick="$('#SC_START_DATE').focus();" class="btn_cal"></a>

				<span class="tilde">~</span>

				<input type="text" id="SC_END_DATE" name="SC_END_DATE" value="<?php echo(F_hsc($SC_END_DATE))?>" maxlength="10" class="nx-ips1" style="max-width:140px;" />
				<a href="javascript:void(0);" onclick="$('#SC_END_DATE').focus();" class="btn_cal"></a>
			</span>

			<div class="res_wdtmh" style="height:10px;"></div>

			<span class="sch_ipt wm2" data-tit="이름/이메일">
				<input type="text" id="SC_WORD" name="SC_WORD" value="<?php echo(F_hsc($SC_WORD))?>" class="nx-ips1" />
			</span>
		</div>
	</div>

	<a href="javascript:void(0);" onclick="$('#frmSch').submit();" class="btn_sch"><span class="ico_search2"></span></a>
	<a href="javascript:void(0)" onclick="excel();" class="nx-btn-b1">엑셀다운로드</a>
	</form>
</div>

<div class="taR mb10">
	<a href="javascript:oncl_Del();" class="nx-btn-b4">삭제</a>
	<a href="subscriber.add.php?<?php echo($phpTail)?>page=<?php echo($page)?>" class="nx-btn-b2">등록</a>
</div>

<table border="0" cellpadding="0" cellspacing="0" class="nx-t-list1">
	<colgroup>
		<col width="40px"><col width="60px"><col width="15%"><col width=""><col width="15%">
	</colgroup>
	<thead>
		<tr>
			<th>&nbsp;</th>
			<th>No.</th>
			<th>이름</th>
			<th>이메일</th>
			<th>구독신청일</th>
		</tr>
	</thead>
	<tbody>
		<?php
		if (sql_num_rows($row) <= 0) {
			echo '<tr><td colspan="5">뉴스레터 구독자가 없습니다.</td></tr>';
		}
		else {
			$s = 0;
			while ($rs1 = sql_fetch_array($row)) {
				?>
		<tr>
			<td><input type="checkbox" id="NM_IDX_<?php echo($s)?>" name="NM_IDX_<?php echo($s)?>" value="<?php echo(F_hsc($rs1['NM_IDX']))?>" /></td>
			<td><?php echo($total_count - $from_record - $s)?></td>
			<td><?php echo(F_hsc($rs1['NM_NAME']))?></td>
			<td class="taL"><?php echo(F_hsc($rs1['NM_EMAIL']))?></td>
			<td><?php echo(F_hsc(substr($rs1['NM_WDATE'], 0, 16)))?></td>
		</tr>
				<?php
				$s++;
			}
		}
		?>
	</tbody>
</table>

<div class="taR mt10">
	<a href="javascript:oncl_Del();" class="nx-btn-b4">삭제</a>
	<a href="subscriber.add.php?<?php echo($phpTail)?>page=<?php echo($page)?>" class="nx-btn-b2">등록</a>
</div>

<?php
	$qstr .= "&amp;page=";

	$pagelist = get_paging($config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr");
	echo $pagelist;
?>

<script>
$(function(){
    $("#SC_START_DATE, #SC_END_DATE").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });
});

function excel(){
	window.location.href = 'subscriber.list.excel.php?SC_START_DATE='+$('#SC_START_DATE').val()+'&SC_END_DATE='+$('#SC_END_DATE').val()+'&SC_WORD='+$('#SC_WORD').val();
}
</script>

<?php
	//마지막 인클루드
	include_once G5_ADMIN_PATH."/admin.tail.php";
?>
