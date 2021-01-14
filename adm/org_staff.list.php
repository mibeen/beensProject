<?php
	$sub_menu = "990310";
	include_once('_common.php');

	include_once('org_staff.err.php');

	if ($ret = auth_check($auth[$sub_menu], "r", true, true)) {
		echo_json(array(
			'success'=>(boolean)false, 
			'msg'=>$ret
		));
	}

	$g5[title] = "조직도-직원";
	include_once('admin.head.php');


	$SC_OP_IDX = ($SC_OP_IDX == "") ? "0" : $SC_OP_IDX;

	# wh
	if ($SC_OP_PARENT_IDX == "" && $SC_WORD == "") $wh = "Where 1 = 2";
	else $wh = "Where OS.OS_DDATE is null";

	if ($SC_OP_PARENT_IDX != '') {
		if (CHK_NUMBER($SC_OP_PARENT_IDX) == 0) $wh .= " And OS.OP_GUBUN= '" . mres($SC_OP_PARENT_IDX) . "'";
		else $wh .= " And OS.OP_PARENT_IDX = '" . mres($SC_OP_PARENT_IDX) . "'";
	}
	if ($SC_OP_IDX != '' && CHK_NUMBER($SC_OP_PARENT_IDX) != 0) {
		$wh .= " And OS.OP_IDX = '" . mres($SC_OP_IDX) . "'";
	}
	if ($SC_WORD != '') {
		$wh .= " And OS.OS_NAME = '" . mres($SC_WORD) . "'";
	}
	

	$sql = "Select Count(OS.OS_IDX) As cnt"
		."	From ORG_STAFF As OS"
		."		Left Join ORG_PART As OP On OP.OP_IDX = OS.OP_IDX"
		."			And OP.OP_DDATE is null"
		."		Left Join ORG_PART As OP2 On if((OS.OP_GUBUN = 'D' Or OS.OP_GUBUN = 'E'), OP2.OP_IDX = OS.OP_PARENT_IDX, OP2.OP_GUBUN = OS.OP_GUBUN)"
		."			And OP2.OP_DDATE is null"
		."			And OP2.OP_IDX != 12"
		."	{$wh}"
		;
	$db1 = sql_fetch($sql);
	$total_count = $db1['cnt'];

	$rows = $config['cf_page_rows'];
	$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
	if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
	$from_record = ($page - 1) * $rows; // 시작 열을 구함


	$sql = "Select OS.*"
		."		, OP.OP_NAME"
		."		, OP2.OP_NAME As OP_PARENT_NAME"
		."	From ORG_STAFF As OS"
		."		Left Join ORG_PART As OP On OP.OP_IDX = OS.OP_IDX"
		."			And OP.OP_DDATE is null"
		."		Left Join ORG_PART As OP2 On if((OS.OP_GUBUN = 'D' Or OS.OP_GUBUN = 'E'), OP2.OP_IDX = OS.OP_PARENT_IDX, OP2.OP_GUBUN = OS.OP_GUBUN)"
		."			And OP2.OP_DDATE is null"
		."			And OP2.OP_IDX != 12"
		."	{$wh}"
		."	Order By OS.OS_SEQ Asc"
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
				<select id="SC_OP_PARENT_IDX" name="SC_OP_PARENT_IDX" style="min-width: 140px"  onchange="get_cate();">
					<option value="">선택해주세요</option>
					<option value="S"<?php if ($SC_OP_PARENT_IDX == "S") echo(' selected');?>>이사장</option>
					<option value="A"<?php if ($SC_OP_PARENT_IDX == "A") echo(' selected');?>>이사회</option>
					<option value="B"<?php if ($SC_OP_PARENT_IDX == "B") echo(' selected');?>>원장</option>
					<option value="C"<?php if ($SC_OP_PARENT_IDX == "C") echo(' selected');?>>GSEEK 캠퍼스 단장</option>
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
					<option value="35"<?php if ($SC_OP_PARENT_IDX == "35") echo(' selected');?>>무소속</option>
				</select>
				<span class="ico_select"></span>
			</span>

			<span id="span_SC_OP_IDX" class="nx-slt" data-tit="부서"<?php if(CHK_NUMBER($SC_OP_PARENT_IDX) == 0) echo(' style="display:none"')?>>
				<select id="SC_OP_IDX" name="SC_OP_IDX" style="min-width: 140px" onchange="document.getElementById('frmSch').submit();">
					<?php
					if (CHK_NUMBER($SC_OP_PARENT_IDX) != 0 && $SC_OP_PARENT_IDX != 35) echo('<option value="">본부장</option>');
					?>
					<?php
					if ($SC_OP_PARENT_IDX != "") {
						$sql = "Select OP.OP_IDX, OP.OP_NAME"
							."		From ORG_PART As OP"
							."	Where OP.OP_DDATE is null"
							."		And OP.OP_PARENT_IDX = '" . mres($SC_OP_PARENT_IDX) . "'"
							."	Order By OP.OP_SEQ Asc"
							;
						$db2 = sql_query($sql);
						while ($rs2 = sql_fetch_array($db2)) {
							?>
						<option value="<?php echo($rs2['OP_IDX'])?>" <?php if ($rs2['OP_IDX'] == $SC_OP_IDX) echo('selected');?>><?php echo(F_hsc($rs2['OP_NAME']))?></option>
							<?php
						}
					}
					?>
				</select>
				<span class="ico_select"></span>
			</span>

			<span class="sch_ipt ws" data-tit="이름">
				<input type="text" id="SC_WORD" name="SC_WORD" class="nx-ips1" value="<?php echo(F_hsc($SC_WORD))?>" />
			</span>
		</div>
	</div>

	<a href="javascript:void(0);" onclick="$('#frmSch').submit();" class="btn_sch"><span class="ico_search2"></span></a>

	</form>
</div>

<div class="ofH mb10">
	<a href="org_staff.add.php?<?php echo($phpTail)?>" class="nx-btn-b2 fR">직원 추가</a>
</div>

<table border="0" cellpadding="0" cellspacing="0" class="nx-t-list1">
	<colgroup>
		<col width="20%"><col width="20%"><col width="20%"><col width="20%"><col width="20%">
	</colgroup>
	<thead>
		<tr>
			<th>순서</th>
			<th>본부명</th>
			<th>부서명</th>
			<th>직위</th>
			<th>이름</th>
		</tr>
	</thead>
	<tbody>
		<?php
		if (sql_num_rows($db1) <= 0) {
			if ($SC_OP_PARENT_IDX == "" && $SC_WORD == "") echo '<tr><td colspan="5">검색조건을 입력해주세요.</td></tr>';
			else echo '<tr><td colspan="5">직원이 없습니다.</td></tr>';
		}
		else {
			while ($rs1 = sql_fetch_array($db1)) {
				?>
		<tr onclick="window.location.href='org_staff.edit.php?<?php echo($phpTail)?>OS_IDX=<?php echo($rs1['OS_IDX'])?>'" style="cursor:pointer">
			<td><?php echo(F_hsc($rs1['OS_SEQ']))?></td>
			<td><?php echo(F_hsc($rs1['OP_PARENT_NAME']))?></td>
			<td><?php echo(F_hsc($rs1['OP_NAME']))?></td>
			<td><?php echo(F_hsc($rs1['OS_POSITION']))?></td>
			<td><?php echo(F_hsc($rs1['OS_NAME']))?></td>
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
function get_cate() {
	if (isNaN($('#SC_OP_PARENT_IDX').val())) {
		$('#span_SC_OP_IDX').hide();
	}
	else {
		$('#span_SC_OP_IDX').show();

		$.ajax({
			url: 'org_part.cate.list.php', type: 'POST', dataType: 'json',
			data: {OP_IDX: $('#SC_OP_PARENT_IDX').val()}
		})
		.done(function(json) {
			if (!json.success) {
				if (json.msg) alert(json.msg);
			}
			else if (json.success) {
				if ($("#SC_OP_PARENT_IDX").val() == 35) $("#SC_OP_IDX").html('');
				else $("#SC_OP_IDX").html('').append('<option value="">본부장</option>');

				if (json.itms)
				{
					for (var i = 0; i < json.itms.length; i++)
					{
						itm = json.itms[i];

						$("#SC_OP_IDX").append('<option value="' + itm.OP_IDX + '">' + itm.OP_NAME + '</option>');
					}
				}
			}
			else {
				alert("처리 도중 오류가 발생하였습니다.");
			}
		})
		.fail(function(a, b, c) {
			alert(a.statusText+' ('+a.status+')\n\n'+a.responseText);
			// alert("Request failed: " + a.responseText);
		});
	}

	setTimeout(function() {
		document.getElementById('frmSch').submit();
	}, 100);
}
//]]>
</script>

<?php
	//마지막 인클루드
	include_once G5_ADMIN_PATH."/admin.tail.php";
?>
