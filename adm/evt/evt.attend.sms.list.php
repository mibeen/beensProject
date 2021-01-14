<?php
	include_once('./_common.php');
	include_once('./evt.err.php'); 	# $sub_menu = ($EP_IDX > 0) ? '980100' : '990100'


	auth_check($auth[$sub_menu], "r");


	# chk : rfv.
	if ($EM_IDX <= 0) {
		header("location:evt.list.php?".$epTail);
		exit();
	}


	$sql = "Select Count(ESH.ESH_IDX) As cnt"
		."	From NX_EVT_SMS_HIST As ESH"
		."		Inner Join NX_EVENT_MASTER As EM On EM.EM_IDX = ESH.EM_IDX"
		."			And EM.EM_DDATE is null"
		."	Where ESH.EM_IDX = '" . mres($EM_IDX) . "'"
		;
	$row = sql_fetch($sql);
	$total_count = $row['cnt'];

	$rows = $config['cf_page_rows'];
	$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
	if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
	$from_record = ($page - 1) * $rows; // 시작 열을 구함


	$sql = "Select ESH.*"
		."		, DATE_FORMAT(ESH.ESH_WDATE, '%Y-%m-%d %H:%i') As ESH_WDATE"
		."	From NX_EVT_SMS_HIST As ESH"
		."		Inner Join NX_EVENT_MASTER As EM On EM.EM_IDX = ESH.EM_IDX"
		."			And EM.EM_DDATE is null"
		."	Where ESH.EM_IDX = '" . mres($EM_IDX) . "'"
		."	Order By ESH.ESH_IDX Desc"
		."	Limit {$from_record}, {$rows}"
		;
	$row = sql_query($sql);


	# get : 행사명
	$db1 = sql_fetch("Select EM_TITLE From NX_EVENT_MASTER Where EM_IDX = '" . mres($EM_IDX) . "' Limit 1");
	if (!is_null($db1['EM_TITLE'])) {
		$EM_TITLE = $db1['EM_TITLE'];
	}


	$g5[title] = "SMS발송내역";
	include_once('../admin.head.php');
?>
<h3 class="nx-tit1 lh30 mb" style="margin-top:0px"><a href="evt.attend.list.php?<?php echo($epTail)?>EM_IDX=<?php echo($EM_IDX)?>" class="nx-btn-b3 fR ml15">뒤로</a><?php echo(F_hsc($EM_TITLE))?></h3>

<?php
if (sql_num_rows($row) <= 0) {
	echo '<div class="taC" style="height:100px;line-height:100px">등록된 정보가 없습니다.</div>';
}
else {
	while ($rs1 = sql_fetch_array($row)) {
		?>
	<table border="0" cellpadding="0" cellspacing="0" class="nx-t-read2 mt10">
		<colgroup>
			<col width="130"><col width=""><col width="130"><col width="">
		</colgroup>
		<tbody>
			<tr>
				<th>대상</th>
				<td>
					<?php echo number_format(substr_count($rs1['ESH_TARGET'], '|') + 1)?>명 <a href="javascript:void(0)" onclick="targ.detail('<?php echo($rs1['ESH_IDX'])?>')">[대상목록]</a>
					<?php
					/*
					echo '<div id="tlist_'.$rs1['ESH_IDX'].'" style="display:none">';
						$_ts = explode('|', $rs1['ESH_TARGET']);
						for ($i = 0; $i < Count($_ts); $i++) {
							$_t = explode('^', $_ts[$i]);
							echo $_t[1].' '.$_t[2].'<br/>';
						}
					echo '</div>';
					*/
					?>
				</td>
				<th>발송시각</th>
				<td><?php echo($rs1['ESH_WDATE'])?></td>
			</tr>
			<tr id="<?php echo('tlist_'.$rs1['ESH_IDX'])?>" style="display:none; background: #e8eef7;">
				<th>대상목록</th>
				<td colspan="3">
					<?php
					$_ts = explode('|', $rs1['ESH_TARGET']);
					for ($i = 0; $i < Count($_ts); $i++) {
						$_t = explode('^', $_ts[$i]);
						echo $_t[1].' '.$_t[2].'<br/>';
					}
					?>
				</td>
			</tr>
			<tr>
				<th>타이틀</th>
				<td colspan="3"><?php echo(F_hsc($rs1['ESH_TITLE']))?></td>
			</tr>
			<tr>
				<th>내용</th>
				<td colspan="3">
					<textarea id="_cont" name="_cont" rows="5" cols="1" class="nx-ips3" readonly><?php echo F_hsc($rs1['ESH_CONT'])?></textarea>
				</td>
			</tr>
		</tbody>
	</table>
		<?php
	}
}
?>

<div class="taR mt10">
	<a href="evt.attend.list.php?<?php echo($epTail)?>EM_IDX=<?php echo($EM_IDX)?>" class="nx-btn-b3">뒤로</a>
</div>

<?php
	$qstr .= "&amp;page=";

	$pagelist = get_paging($config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr");
	echo $pagelist;
?>

<script>
//<![CDATA[
var targ = {
	detail: function(c) {
		if (c == '' || isNaN(c)) return;

		var _t = $('#tlist_'+c);
		if (_t.is(':visible')) {
			_t.hide();
		}
		else {
			_t.show();
		}
	}
}
//]]>
</script>
<?php
	//마지막 인클루드
	include_once G5_ADMIN_PATH."/admin.tail.php";
?>
