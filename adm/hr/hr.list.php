<?php
	$sub_menu = "985100";
	include_once('./_common.php');
	include_once('./hr.err.php');


	# 공모업체 관리자 권한 부여
	if ($member['mb_level'] == 6) {
		$auth[$sub_menu] = 'r';
	}

	auth_check($auth[$sub_menu], "r");

	
	# set : variables
	$ord = $_GET['ord'];
	$ord = ((int)$ord >= 1 && (int)$ord <= 4) ? (int)$ord : (int)'1';
	$stx = $_GET['stx'];


	# wh
	$wh = "Where EP.EP_DDATE is null";
	
	if (!$is_admin) {
		$wh .= " And EP.EP_IDX In(Select EPM.EP_IDX From NX_EVENT_HR_MEMBER As EPM Where EPM.mb_id = '" . mres($member['mb_id']) . "')";
	}
	if ($sc_ep_s_date != '') {
		$wh .= " And EP.EP_S_DATE >= '" . mres($sc_ep_s_date) . "'";
	}
	if ($sc_ep_e_date != '') {
		$wh .= " And EP.EP_E_DATE <= '" . mres($sc_ep_e_date) . "'";
	}

	if ($stx != '') {
		$wh .= " And EP.EP_TITLE like '%" . mres($stx) . "%'";
	}


	# ord
	switch ($ord) {
		case '1':	# 등록 최신순
			$_ord = ' Order By EP.EP_IDX Desc';
			break;

		case '2':	# 등록 오래된순
			$_ord = ' Order By EP.EP_IDX Asc';
			break;

		case '3':	# 사업 최신순
			$_ord = ' Order By EP.EP_S_DATE Desc, EP.EP_IDX Desc';
			break;

		case '4':	# 사업 오래된순
			$_ord = ' Order By EP.EP_S_DATE Asc, EP.EP_IDX Desc';
			break;
		
		default:	# default
			$_ord = ' Order By EP.EP_IDX Desc';
			break;
	}


	$sql = "Select Count(*) As cnt"
		."	From NX_EVENT_HR As EP"
		."	{$wh}"
		;
	$row = sql_fetch($sql);
	$total_count = $row['cnt'];

	$rows = $config['cf_page_rows'];
	$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
	if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
	$from_record = ($page - 1) * $rows; // 시작 열을 구함


	$sql = "Select EP.EP_IDX, EP.EP_TITLE, EP.EP_S_DATE, EP.EP_E_DATE"
		."	From NX_EVENT_HR As EP"
		."	{$wh}"
		."	{$_ord}"
		."	Limit {$from_record}, {$rows} "
		;
	$row = sql_query($sql);


	$g5[title] = "기관/교육강사 목록(hr)";
	include_once('../admin.head.php');
	include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');
?>

<div class="sch_box">
	<form id="frmSch" name="frmSch" method="get" action="<?php echo($_SERVER['PHP_SELF'])?>">
	<input type="submit" style="width:1px;height:1px;position:absolute;top:-10000px;left:-10000px" />
	
	<div class="sch_opt1">
		<div class="sch_opt2">
			<span class="sch_term" data-tit="사업기간">
				<input type="text" id="sc_ep_s_date" name="sc_ep_s_date" class="nx-ips1" value="<?php echo($sc_ep_s_date)?>" style="max-width:140px;">

				<span class="tilde">~</span>

				<input type="text" id="sc_ep_e_date" name="sc_ep_e_date" class="nx-ips1" value="<?php echo($sc_ep_e_date)?>" style="max-width:140px;">
			</span>

			<div class="res_wdtmh" style="height:10px;"></div>

			<span class="nx-slt" data-tit="정렬">
				<select id="ord" name="ord" style="min-width: 140px">
					<option value="1" <?php if ($ord == '1') echo('selected');?>>등록 최신순</option>
					<option value="2" <?php if ($ord == '2') echo('selected');?>>등록 오래된순</option>
					<option value="3" <?php if ($ord == '3') echo('selected');?>>사업시작일 최신순</option>
					<option value="4" <?php if ($ord == '4') echo('selected');?>>사업시작일 오래된순</option>
				</select>
				<span class="ico_select"></span>
			</span>

			<span class="sch_ipt wm2" data-tit="공모사업명">
				<input type="text" id="stx" name="stx" class="nx-ips1" value="<?php echo(F_hsc($stx))?>" />
			</span>
		</div>
	</div>

	<a href="javascript:void(0);" onclick="$('#frmSch').submit();" class="btn_sch"><span class="ico_search2"></span></a>

	</form>
</div>

<?php
if ($is_admin || $member['mb_level'] == 6) {
	?>
<div class="taR mb10">
	<a href="hr.add.php" class="nx-btn-b2">기관 등록</a>
</div>
	<?php
}
?>

<table border="0" cellpadding="0" cellspacing="0" class="nx-t-list1">
	<colgroup>
		<col width="80">
		<col width="">
		<col width="220">
		<?php if ($is_admin) echo '<col width="100">'; ?>
	</colgroup>
	<thead>
		<tr>
			<th>번호</th>
			<th>공모사업명</th>
			<th>기간</th>
			<?php if ($is_admin) echo '<th>수정</th>'; ?>
		</tr>
	</thead>
	<tbody>
		<?php
		if (sql_num_rows($row) <= 0) {
			echo '<tr><td colspan="'.(($is_admin) ? '4': '3').'">등록된 교육강사가 없습니다.</td></tr>';
		}
		else {
			$s = 0;
			while ($rs1 = sql_fetch_array($row)) {
				?>
		<tr>
			<td><?php echo($total_count - $from_record - $s)?></td>
			<td class="taL">
				<div class="elss3">
					<a href="evt.list.php?EP_IDX=<?php echo($rs1['EP_IDX'])?>" class="color-tit"><?php echo(F_hsc($rs1['EP_TITLE']))?></a>
				</div>
			</td>
			<td>
				<?php echo(F_hsc($rs1['EP_S_DATE']).' ~ '.F_hsc($rs1['EP_E_DATE']))?><br>
			</td>
			<?php
			if ($is_admin) {
				?>
			<td>
				<a href="hr.edit.php?<?php echo($phpTail)?>EP_IDX=<?php echo($rs1['EP_IDX'])?>" class="nx-btn2">수정</a>
			</td>
				<?php
			}
			?>
		</tr>
				<?php
				$s++;
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
    $("#sc_ep_s_date, #sc_ep_e_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "2016:c+5" });
});
//]]>
</script>

<?php
	//마지막 인클루드
	include_once G5_ADMIN_PATH."/admin.tail.php";
?>
