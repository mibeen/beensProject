<?php
include_once("../bbs/_common.php");


// 테마설정
$at = apms_gr_thema();
if(!defined('THEMA_PATH')) {
	include_once(G5_LIB_PATH.'/apms.thema.lib.php');
}

include_once(G5_PATH.'/head.sub.php');
if(!USE_G5_THEME) @include_once(THEMA_PATH.'/head.sub.php');


$OP_GUBUN = $_GET['OP_GUBUN'];
$OP_IDX = $_GET['OP_IDX'];

$OP_NAME = "";
$sql = "";
if ($OP_GUBUN == "D") {
	$db1 = sql_query("Select OP.OP_NAME From ORG_PART As OP Where OP.OP_DDATE is null And OP.OP_IDX = '" . mres($OP_IDX) . "'");
	$rs1 = sql_fetch_array($db1);
	$cnt = sql_num_rows($db1);
	$OP_NAME = $rs1["OP_NAME"];

	if ($rs1 > 0) {
		$sql = "Select OS.*"
			."		From ORG_STAFF As OS"
			."	Where OS.OS_DDATE is null"
			."		And OS.OP_PARENT_IDX = '" . mres($OP_IDX) . "' And OS.OP_GUBUN = 'D'"
			."	Order By OS.OS_SEQ Asc"
			;
	}

} else if ($OP_GUBUN == "E") {
	$db1 = sql_query("Select OP.OP_NAME From ORG_PART As OP Where OP.OP_DDATE is null And OP.OP_IDX = '" . mres($OP_IDX) . "'");
	$rs1 = sql_fetch_array($db1);
	$cnt = sql_num_rows($db1);
	$OP_NAME = $rs1["OP_NAME"];

	if ($rs1 > 0) {
		$sql = "Select OS.*"
			."		From ORG_STAFF As OS"
			."	Where OS.OS_DDATE is null"
			."		And OS.OP_IDX = '" . mres($OP_IDX) . "'"
			."	Order By OS.OS_SEQ Asc"
			;
	}

} else {
	$db1 = sql_query("Select OP.OP_NAME From ORG_PART As OP Where OP.OP_DDATE is null And OP.OP_GUBUN = '" . mres($OP_GUBUN) . "'");
	$rs1 = sql_fetch_array($db1);
	$cnt = sql_num_rows($db1);
	$OP_NAME = $rs1["OP_NAME"];

	if ($rs1 > 0) {
		$sql = "Select OS.*"
			."		From ORG_STAFF As OS"
			."	Where OS.OS_DDATE is null"
			."		And OS.OP_GUBUN = '" . mres($OP_GUBUN) . "'"
			."	Order By OS.OS_SEQ Asc"
			;
	}
}

if ($cnt < 1) {
	alert_script("잘못된 접근입니다.", "parent.$('#viewModal').modal('hide');");
	exit;
}
// $db1 = sql_query($sql);
?>
<link rel="stylesheet" href="<?php echo(THEMA_URL)?>/colorset/Basic/colorset.css" type="text/css">

<p class="org_tit1"><?php if ($OP_GUBUN == "E") { ?>부서<?php } else { ?>본부<?php } ?>명 : <?php echo(F_hsc($OP_NAME))?></p>
<table border="0" cellspacing="0" cellpadding="0" class="org_ts1">
	<colgroup>
		<col width="70" /><col width="80" /><col width="500" /><col width="150" /><col width="200" />
	</colgroup>
	<thead>
		<tr>
			<th>직위</th>
			<th>이름</th>
			<th>담당업무</th>
			<th>연락처</th>
			<th>이메일</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$db1 = sql_query($sql);
		if (sql_num_rows($db1) <= 0) {
			echo '<tr><td colspan="5">직원이 없습니다.</td></tr>';
		}
		else {
			while ($rs1 = sql_fetch_array($db1)) {
				?>
		<tr>
			<td data-title="직위"><?php echo(F_hsc($rs1['OS_POSITION']))?></td>
			<td data-title="이름"><?php echo(F_hsc($rs1['OS_NAME']))?></td>
			<td data-title="담당업무" class="nx_taL"><?php echo(F_hsc($rs1['OS_WORK']))?></td>
			<td data-title="연락처"><?php echo(F_hsc($rs1['OS_TEL']))?></td>
			<td data-title="이메일"><?php echo(F_hsc($rs1['OS_EMAIL']))?></td>
		</tr>
				<?php
			}
		}
		?>
	</tbody>
</table>
