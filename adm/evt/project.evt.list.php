<?php
	include_once('./_common.php');
	$sub_menu = '980300';

	auth_check($auth[$sub_menu], "r");


	# set : variables
	$cate = $_GET['cate'];
	$ord = $_GET['ord'];
	$ord = ((int)$ord >= 1 && (int)$ord <= 4) ? (int)$ord : (int)'1';
	$stx = $_GET['stx'];


	# re-define
	$cate = (CHK_NUMBER($cate) > 0) ? (int)$cate : '';


	# wh
	$wh = "Where EM.EM_DDATE is null";

	$wh .= " And EM.EP_IDX is not null";
	
	if ($em_s_date != '') {
		$wh .= " And EM.EM_S_DATE >= '" . mres($em_s_date) . "'";
	}
	if ($em_e_date != '') {
		$wh .= " And EM.EM_E_DATE <= '" . mres($em_e_date) . "'";
	}

	if ($cate != '') {
		$wh .= " And EM.EC_IDX = '" . mres($cate) . "'";
	}

	if ($stx != '') {
		$wh .= " And EM.EM_TITLE like '%" . mres($stx) . "%'";
	}


	# ord
	switch ($ord) {
		case '1':	# 등록 최신순
			$_ord = ' Order By EM.EM_IDX Desc';
			break;

		case '2':	# 등록 오래된순
			$_ord = ' Order By EM.EM_IDX Asc';
			break;

		case '3':	# 행사 최신순
			$_ord = ' Order By EM.EM_S_DATE Desc, EM.EM_IDX Desc';
			break;

		case '4':	# 행사 오래된순
			$_ord = ' Order By EM.EM_S_DATE Asc, EM.EM_IDX Desc';
			break;
		
		default:	# default
			$_ord = ' Order By EM.EM_IDX Desc';
			break;
	}


	$sql = "Select Count(*) As cnt"
		."	From NX_EVENT_MASTER As EM"
		."		Left Join NX_EVENT_CATE As EC On EC.EC_IDX = EM.EC_IDX"
		."	{$wh}"
		;
	$row = sql_fetch($sql);
	$total_count = $row['cnt'];

	$rows = $config['cf_page_rows'];
	$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
	if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
	$from_record = ($page - 1) * $rows; // 시작 열을 구함


	$sql = "Select EM.EM_IDX, EM.EM_TITLE, EM.EM_S_DATE, EM.EM_E_DATE, EM.EM_S_TIME, EM.EM_E_TIME"
		."		, EC.EC_NAME"
		."		, EP.EP_IDX, EP.EP_TITLE"
		."		, M.mb_name"
		."		, (Select Count(EJ_IDX) From NX_EVENT_JOIN Where EM_IDX = EM.EM_IDX And EJ_DDATE is null) As CNT1"
		."		, (Select Count(EJ_IDX) From NX_EVENT_JOIN Where EM_IDX = EM.EM_IDX And EJ_DDATE is null And EJ_STATUS = '2') As CNT2"
		."		, (Select Count(EJ_IDX) From NX_EVENT_JOIN Where EM_IDX = EM.EM_IDX And EJ_DDATE is null And EJ_STATUS = '2' And (EJ_JOIN_CHK1 = 'Y' And EJ_JOIN_CHK2 = 'Y')) As CNT3"
		."		, (Select Count(EJ_IDX) From NX_EVENT_JOIN Where EM_IDX = EM.EM_IDX And EJ_DDATE is null And EJ_WDATE > EM.EM_EJ_R_DATE) As CNT4"
		."		, (Select Count(ENT_IDX) From NX_EVENT_NAMETAG_TEMPLATE Where ENT_DDATE is null And EM_IDX = EM.EM_IDX) As CNT5"
		."	From NX_EVENT_MASTER As EM"
		."		Left Join NX_EVENT_CATE As EC On EC.EC_IDX = EM.EC_IDX"
		."		Left Join NX_EVENT_PROJECT As EP On EP.EP_IDX = EM.EP_IDX"
		."			Inner Join g5_member As M On M.mb_id = EP.mb_id"
		."	{$wh}"
		."	{$_ord}"
		."	Limit {$from_record}, {$rows} "
		;
	$row = sql_query($sql);


	$g5[title] = "전체 현황";
	include_once('../admin.head.php');
	include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');
?>

<div class="sch_box">
	<form id="frmSch" name="frmSch" method="get" action="<?php echo($_SERVER['PHP_SELF'])?>">
	<input type="submit" style="width:1px;height:1px;position:absolute;top:-10000px;left:-10000px" />
	<input type="hidden" name="EP_IDX" value="<?php echo($EP_IDX)?>">
	
	<div class="sch_opt1">
		<div class="sch_opt2">
			<span class="sch_term" data-tit="행사일">
				<input type="text" id="em_s_date" name="em_s_date" class="nx-ips1" value="<?php echo($em_s_date)?>" style="max-width:140px;">

				<span class="tilde">~</span>

				<input type="text" id="em_e_date" name="em_e_date" class="nx-ips1" value="<?php echo($em_e_date)?>" style="max-width:140px;">
			</span>

			<div class="res_wdtmh" style="height:10px;"></div>

			<span class="nx-slt" data-tit="카테고리">
				<select id="cate" name="cate" style="min-width: 140px" onchange="sear.cate()">
					<option value="">전체</option>
					<?php
					$sql = "Select EC_IDX, EC_NAME From NX_EVENT_CATE Order By EC_SEQ Asc, EC_IDX Desc";
					$sdb1 = sql_query($sql);

					while ($srs1 = sql_fetch_array($sdb1)) {
						echo '<option value="'.$srs1['EC_IDX'].'" '.(($srs1['EC_IDX'] == $cate) ? 'selected' : '').'>'.F_hsc($srs1['EC_NAME']).'</option>';
					}
					?>
				</select>
				<span class="ico_select"></span>
			</span>

			<span class="nx-slt" data-tit="정렬">
				<select id="ord" name="ord" style="min-width: 140px">
					<option value="1" <?php if ($ord == '1') echo('selected');?>>등록 최신순</option>
					<option value="2" <?php if ($ord == '2') echo('selected');?>>등록 오래된순</option>
					<option value="3" <?php if ($ord == '3') echo('selected');?>>행사 최신순</option>
					<option value="4" <?php if ($ord == '4') echo('selected');?>>행사 오래된순</option>
				</select>
				<span class="ico_select"></span>
			</span>

			<span class="sch_ipt wm2" data-tit="행사명">
				<input type="text" id="stx" name="stx" class="nx-ips1" value="<?php echo(F_hsc($stx))?>" />
			</span>
		</div>
	</div>

	<a href="javascript:void(0);" onclick="$('#frmSch').submit();" class="btn_sch"><span class="ico_search2"></span></a>

	</form>
</div>

<table border="0" cellpadding="0" cellspacing="0" class="nx-t-list1">
	<colgroup>
		<col width="60"><col width="150"><col width=""><col width="100"><col width="200"><col width="50"><col width="50"><col width="50"><col width="180">
	</colgroup>
	<thead>
		<tr>
			<th>카테고리</th>
			<th>공모사업명</th>
			<th>행사명</th>
			<th>업체명</th>
			<th>행사일</th>
			<th>신청</th>
			<th>승인</th>
			<th>참석</th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<?php
		if ($total_count <= 0 || sql_num_rows($row) <= 0) {
			echo '<tr><td colspan="9">등록된 행사가 없습니다.</td></tr>';
		}
		else {
			while ($rs1 = sql_fetch_array($row)) {
				?>
		<tr>
			<td><?php echo F_hsc($rs1['EC_NAME'])?></td>
			<td><?php echo F_hsc($rs1['EP_TITLE'])?></td>
			<td class="taL">
				<div class="elss3">
					<a href="evt.edit.php?<?php echo($phpTail)?>EP_IDX=<?php echo($rs1['EP_IDX'])?>&EM_IDX=<?php echo($rs1['EM_IDX'])?>" class="color-tit"><?php echo(F_hsc($rs1['EM_TITLE']))?></a>
				</div>
			</td>
			<td><?php echo F_hsc($rs1['mb_name'])?></td>
			<td>
				<?php echo(F_hsc($rs1['EM_S_DATE']).' ~ '.F_hsc($rs1['EM_E_DATE']))?><br>
				(<?php echo(F_hsc(substr($rs1['EM_S_TIME'], 0, 5)).' ~ '.F_hsc(substr($rs1['EM_E_TIME'], 0, 5)))?>)
			</td>
			<td><?php echo(number_format($rs1['CNT1']))?></td>
			<td><?php echo(number_format($rs1['CNT2']))?></td>
			<td><?php echo(number_format($rs1['CNT3']))?></td>
			<td><?php
				echo '<a href="evt.join.list.php?EP_IDX='.$rs1['EP_IDX'].'&EM_IDX='.$rs1['EM_IDX'].'" class="nx-btn3 '.(($rs1['CNT4'] > 0) ? 'nx-btn-new' : '').'">신청자</a> ';
				echo '<a href="evt.attend.list.php?EP_IDX='.$rs1['EP_IDX'].'&EM_IDX='.$rs1['EM_IDX'].'" class="nx-btn3">참석관리</a> ';
				echo '<div style="height:3px;"></div>';
				echo '<a href="evt.sati.php?EP_IDX='.$rs1['EP_IDX'].'&EM_IDX='.$rs1['EM_IDX'].'" class="nx-btn3">만족도관리</a> ';
				echo '<a href="evt.nametag.php?EP_IDX='.$rs1['EP_IDX'].'&EM_IDX='.$rs1['EM_IDX'].'" class="nx-btn3 '.(($rs1['CNT5'] > 0) ? 'nx-btn-new' : '').'">명찰관리</a>';
			?></td>
		</tr>
				<?php
			}
		}
		?>
	</tbody>
</table>

<?php
	$qstr .= "&amp;page=";

	$pagelist = get_paging($config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?".$qstr);
	echo $pagelist;
?>

<script>
//<![CDATA[
$(function(){
    $("#em_s_date, #em_e_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "2016:c+5" });
});

var onclCate = function() {
	window.open('evt.cate.php', 'evt_cate', 'width=420,height=500,scrollbars=yes');
}
var onclSati = function() {
	window.open('evt.sati.tpl.php', 'evt_sati', 'width=420,height=500,scrollbars=yes');
}

var sear = {
	cate: function() {
		var v = $('#cate').val();
		$('#frmSch').submit();
	}
}
//]]>
</script>

<?php
	//마지막 인클루드
	include_once G5_ADMIN_PATH."/admin.tail.php";
?>
