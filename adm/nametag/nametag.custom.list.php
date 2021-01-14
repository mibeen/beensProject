<?php
	$sub_menu = "990600";
	include_once('./_common.php');
	include_once('./nametag.custom.err.php');


	# set : variables
	$stx = $_GET['stx'];


	# wh
	$wh = "Where ENC.ENC_DDATE is null And ENT.ENT_DDATE is null";
	
	if ($sc_enc_s_date != '') {
		$wh .= " And DATE(ENC.ENC_WDATE) >= '" . mres($sc_enc_s_date) . "'";
	}
	if ($sc_enc_e_date != '') {
		$wh .= " And DATE(ENC.ENC_WDATE) <= '" . mres($sc_enc_e_date) . "'";
	}

	if ($stx != '') {
		$wh .= " And ENC.ENC_TITLE like '%" . mres($stx) . "%'";
	}


	$sql = "Select Count(*) As cnt"
		."	From NX_EVENT_NAMETAG_CUSTOM As ENC"
		."		Inner Join NX_EVENT_NAMETAG_TEMPLATE As ENT On ENT.ENC_IDX = ENC.ENC_IDX"
		."	{$wh}"
		;
	$row = sql_fetch($sql);
	$total_count = $row['cnt'];

	$rows = $config['cf_page_rows'];
	$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
	if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
	$from_record = ($page - 1) * $rows; // 시작 열을 구함


	$sql = "Select ENC.ENC_IDX, ENC.ENC_TITLE, ENC.ENC_WDATE"
		."	, ENT.ENT_TITLE"
		."	From NX_EVENT_NAMETAG_CUSTOM As ENC"
		."		Inner Join NX_EVENT_NAMETAG_TEMPLATE As ENT On ENT.ENC_IDX = ENC.ENC_IDX"
		."	{$wh}"
		."	Order By ENC.ENC_WDATE Desc"
		."	Limit {$from_record}, {$rows} "
		;
	$row = sql_query($sql);


	$g5[title] = "명찰제작";
	include_once('../admin.head.php');
	include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');
?>

<div class="sch_box">
	<form id="frmSch" name="frmSch" method="get" action="<?php echo($_SERVER['PHP_SELF'])?>">
	<input type="submit" style="width:1px;height:1px;position:absolute;top:-10000px;left:-10000px" />
	
	<div class="sch_opt1">
		<div class="sch_opt2">
			<span class="sch_term" data-tit="등록일">
				<input type="text" id="sc_enc_s_date" name="sc_enc_s_date" class="nx-ips1" value="<?php echo($sc_enc_s_date)?>" style="max-width:140px;">

				<span class="tilde">~</span>

				<input type="text" id="sc_enc_e_date" name="sc_enc_e_date" class="nx-ips1" value="<?php echo($sc_enc_e_date)?>" style="max-width:140px;">
			</span>

			<span class="sch_ipt wm2" data-tit="행사명">
				<input type="text" id="stx" name="stx" class="nx-ips1" value="<?php echo(F_hsc($stx))?>" />
			</span>
		</div>
	</div>

	<a href="javascript:void(0);" onclick="$('#frmSch').submit();" class="btn_sch"><span class="ico_search2"></span></a>

	</form>
</div>

<div class="taR mb10">
	<a href="nametag.custom.edit.php" class="nx-btn-b2">행사 만들기</a>
</div>

<table border="0" cellpadding="0" cellspacing="0" class="nx-t-list1">
	<colgroup>
		<col width="80">
		<col width="">
		<col width="">
		<col width="120">
		<col width="150">
	</colgroup>
	<thead>
		<tr>
			<th>번호</th>
			<th>행사명</th>
			<th>템플릿</th>
			<th>등록일</th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<?php
		if (sql_num_rows($row) <= 0) {
			echo '<tr><td colspan="5">등록된 명찰이 없습니다.</td></tr>';
		}
		else {
			$s = 0;
			while ($rs1 = sql_fetch_array($row)) {
				?>
		<tr>
			<td><?php echo($total_count - $from_record - $s)?></td>
			<td class="taL">
				<div class="elss3">
					<a href="nametag.custom.edit.php?ENC_IDX=<?php echo($rs1['ENC_IDX'])?>" class="color-tit"><?php echo(F_hsc($rs1['ENC_TITLE']))?></a>
				</div>
			</td>
			<td>
				<?php echo(F_hsc($rs1['ENT_TITLE']))?><br>
			</td>
			<td>
				<?php echo(substr($rs1['ENC_WDATE'], 0, 10))?><br>
			</td>
			<td>
				<a href="javascript:nt.pdf(<?php echo($rs1['ENC_IDX'])?>);" class="nx-btn2">PDF다운로드</a>
			</td>
		</tr>
				<?php
				$s++;
			}
		}
		?>
	</tbody>
</table>

<form id="frmAct" name="frmAct" onsubmit="return false">
	<input type="hidden" id="ENC_IDX" name="ENC_IDX" value="" />
</form>

<?php
	$qstr .= "&amp;page=";

	$pagelist = get_paging($config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?".$qstr);
	echo $pagelist;
?>

<script>
//<![CDATA[
$(function(){
    $("#sc_enc_s_date, #sc_enc_e_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "2016:c+5" });
});

var nt = {
	pdf: function(ENC_IDX) {
		if (ENC_IDX == '' || typeof ENC_IDX == 'undefined') return;

		$('#ENC_IDX').val(ENC_IDX);

		var url = 'nametag.custom.pdf.php';
		window.open("" ,"nametag_pdf", "scrollbars=yes");

		var f = document.frmAct;
		f.action = url;
		f.method = 'post';
		f.target = 'nametag_pdf';
		
		f.submit();
	}
}
//]]>
</script>

<?php
	//마지막 인클루드
	include_once G5_ADMIN_PATH."/admin.tail.php";
?>
