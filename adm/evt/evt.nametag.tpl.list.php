<?php
	include_once('./_common.php');
	include_once('./evt.err.php'); 	# $sub_menu = ($EP_IDX > 0) ? '980100' : '990100'

	auth_check($auth[$sub_menu], "r");


	$stx = $_GET['stx'];


	# wh
	$wh = "Where ENT.ENT_DDATE is null And ENT.EM_IDX is null";
	
	if ($stx != '') {
		$wh .= " And ENT.ENT_TITLE like '%" . mres($stx) . "%'";
	}


	$sql = "Select Count(ENT.ENT_IDX) As cnt"
		."	From NX_EVENT_NAMETAG_TEMPLATE As ENT"
		."	{$wh}"
		;
	$row = sql_fetch($sql);
	$total_count = $row['cnt'];

	$rows = $config['cf_page_rows'];
	$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
	if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
	$from_record = ($page - 1) * $rows; // 시작 열을 구함


	$sql = "Select ENT.ENT_IDX, ENT.ENT_TITLE, ENT.ENT_WIDTH, ENT.ENT_HEIGHT"
		."		, DATE_FORMAT(ENT.ENT_WDATE, '%Y-%m-%d %H:%i') As ENT_WDATE"
		."	From NX_EVENT_NAMETAG_TEMPLATE As ENT"
		."	{$wh}"
		."	Order By ENT_IDX Desc"
		."	Limit {$from_record}, {$rows}"
		;
	$row = sql_query($sql);


	$g5[title] = ($sub_menu == '980100') ? "공모사업 행사 명찰관리" : "사업관리";
	include_once('../admin.head.php');
?>

<div class="taR mb10">
	<a href="evt.list.php?<?php echo($epTail)?>" class="nx-btn-b3">뒤로</a>
</div>

<div class="sch_box">
	<form id="frmSch" name="frmSch" method="get" action="<?php echo($_SERVER['PHP_SELF'])?>">
	<input type="submit" style="width:1px;height:1px;position:absolute;top:-10000px;left:-10000px" />
	<input type="hidden" name="EP_IDX" value="<?php echo($EP_IDX)?>">
	
	<div class="sch_opt1">
		<div class="sch_opt2">
			<span class="sch_ipt wm2" data-tit="템플릿명">
				<input type="text" id="stx" name="stx" class="nx-ips1" value="<?php echo(F_hsc($stx))?>" />
			</span>
		</div>
	</div>

	<a href="javascript:void(0);" onclick="$('#frmSch').submit();" class="btn_sch"><span class="ico_search2"></span></a>

	</form>
</div>

<div class="taR mb10">
	<a href="evt.nametag.tpl.add.php?<?php echo($epTail)?>" class="nx-btn-b2">템플릿 등록</a>
</div>

<table border="0" cellpadding="0" cellspacing="0" class="nx-t-list1">
	<colgroup>
		<col width=""><col width="15%"><col width="15%">
	</colgroup>
	<thead>
		<tr>
			<th>템플릿명</th>
			<th>가로사이즈(mm)</th>
			<th>세로사이즈(mm)</th>
		</tr>
	</thead>
	<tbody>
		<?php
		if (sql_num_rows($row) <= 0) {
			echo '<tr><td colspan="3">등록된 템플릿이 없습니다.</td></tr>';
		}
		else {
			$s = 0;
			while ($rs1 = sql_fetch_array($row)) {
				?>
		<tr>
			<td class="taL">
				<a href="evt.nametag.tpl.edit.php?<?php echo($epTail)?>ENT_IDX=<?php echo($rs1['ENT_IDX'])?>" class="color-tit"><?php echo(F_hsc($rs1['ENT_TITLE']))?></a>
			</td>
			<td><?php echo(number_format($rs1['ENT_WIDTH']))?></td>
			<td><?php echo(number_format($rs1['ENT_HEIGHT']))?></td>
		</tr>
				<?php
				$s++;
			}
		}
		?>
	</tbody>
</table>

<div class="taR mt10">
	<a href="evt.nametag.tpl.add.php?<?php echo($epTail)?>" class="nx-btn-b2">템플릿 등록</a>
</div>

<?php
	$qstr .= "&amp;page=";

	$pagelist = get_paging($config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr");
	echo $pagelist;
?>

<?php
	//마지막 인클루드
	include_once G5_ADMIN_PATH."/admin.tail.php";
?>
