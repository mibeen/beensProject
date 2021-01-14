<?php
	$sub_menu = "990300";
	include_once('_common.php');

	include_once('org_part.err.php');

	if ($ret = auth_check($auth[$sub_menu], "r", true, true)) {
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>$ret
		));
	}

	$g5[title] = "조직도-부서";
	include_once('admin.head.php');

	# wh
	if ($SC_OP_PARENT_IDX == "" && $SC_WORD == "") $wh = "Where 1 = 2";
	else $wh = "Where OP.OP_DDATE is null And OP.OP_PARENT_IDX != ''";

	if ($SC_OP_PARENT_IDX != '') {
		$wh .= " And OP.OP_PARENT_IDX = '" . mres($SC_OP_PARENT_IDX) . "'";
	}
	if ($SC_WORD != '') {
		$wh .= " And OP.OP_NAME = '" . mres($SC_WORD) . "'";
	}
	

	$sql = "Select Count(OP.OP_IDX) As cnt"
		."	From ORG_PART As OP"
		."	{$wh}"
		;
	$db1 = sql_fetch($sql);
	$total_count = $db1['cnt'];

	$rows = $config['cf_page_rows'];
	$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
	if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
	$from_record = ($page - 1) * $rows; // 시작 열을 구함


	$sql = "Select OP.OP_IDX, OP.OP_SEQ, OP.OP_NAME"
		."		, (Select OP2.OP_NAME From ORG_PART As OP2 Where OP2.OP_IDX = OP.OP_PARENT_IDX) As OP_PARENT_NAME"
		."	From ORG_PART As OP"
		."	{$wh}"
		."	Order By OP.OP_SEQ Asc"
		."	Limit {$from_record}, {$rows} "
		;
	$db1 = sql_query($sql);
?>
<div class="sch_box">
	<form id="frmSch" name="frmSch" method="get" action="<?php echo($_SERVER['PHP_SELF'])?>">
	<input type="submit" style="width:1px;height:1px;position:absolute;top:-10000px;left:-10000px" />
	
	<div class="sch_opt1">
		<div class="sch_opt2">
			<span class="nx-slt" data-tit="본부">
				<select id="SC_OP_PARENT_IDX" name="SC_OP_PARENT_IDX" style="min-width: 140px" onchange="document.getElementById('frmSch').submit();">
					<option value="">선택해주세요</option>
					<?php
					$sql = "Select OP.OP_IDX, OP.OP_NAME"
						."		From ORG_PART As OP"
						."	Where OP.OP_DDATE is null And OP.OP_PARENT_IDX is null And OP.OP_PARENT2_IDX is not null"
						."	Order By OP.OP_SEQ Asc"
						;
					$db2 = sql_query($sql);
					while ($rs2 = sql_fetch_array($db2)) {
						?>
					<option value="<?php echo($rs2['OP_IDX'])?>" <?php if ($rs2['OP_IDX'] == $SC_OP_PARENT_IDX) echo('selected');?>><?php echo(F_hsc($rs2['OP_NAME']))?></option>
						<?php
					}
					?>
					<option value="35" <?php if ($SC_OP_PARENT_IDX == "35") echo('selected');?>>무소속</option>
				</select>
				<span class="ico_select"></span>
			</span>

			<span class="sch_ipt wm2" data-tit="부서명">
				<input type="text" id="SC_WORD" name="SC_WORD" class="nx-ips1" value="<?php echo(F_hsc($SC_WORD))?>" />
			</span>
		</div>
	</div>

	<a href="javascript:void(0);" onclick="$('#frmSch').submit();" class="btn_sch"><span class="ico_search2"></span></a>

	</form>
</div>

<div class="ofH mb10">
	<a href="javascript:void(0);" onclick="onclPart();" class="nx-btn-b2 fL">본부 관리</a>
	<a href="org_part.add.php?<?php echo($phpTail)?>" class="nx-btn-b2 fR">부서 추가</a>
</div>

<table border="0" cellpadding="0" cellspacing="0" class="nx-t-list1">
	<colgroup>
		<col width="20%"><col width="40%"><col width="40%">
	</colgroup>
	<thead>
		<tr>
			<th>순서</th>
			<th>본부명</th>
			<th>부서명</th>
		</tr>
	</thead>
	<tbody>
		<?php
		if (sql_num_rows($db1) <= 0) {
			if ($SC_OP_PARENT_IDX == "" && $SC_WORD == "") echo '<tr><td colspan="3">검색조건을 입력해주세요.</td></tr>';
			else echo '<tr><td colspan="3">부서가 없습니다.</td></tr>';
		}
		else {
			while ($rs1 = sql_fetch_array($db1)) {
				?>
		<tr onclick="window.location.href='org_part.edit.php?<?php echo($phpTail)?>OP_IDX=<?php echo($rs1['OP_IDX'])?>'" style="cursor:pointer">
			<td><?php echo(F_hsc($rs1['OP_SEQ']))?></td>
			<td><?php echo(F_hsc($rs1['OP_PARENT_NAME']))?></td>
			<td><?php echo(F_hsc($rs1['OP_NAME']))?></td>
		</tr>
				<?php
			}
		}
		?>
	</tbody>
</table>

<?php
	$qstr .= "&amp;page=";

	$pagelist = get_paging($config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr");
	echo $pagelist;
?>

<script>
//<![CDATA[
var onclPart = function() {
	window.open('org_part.cate.php', 'org_part_cate', 'width=400,height=500,scrolling=auto');
}
//]]>
</script>

<?php
	//마지막 인클루드
	include_once G5_ADMIN_PATH."/admin.tail.php";
?>
