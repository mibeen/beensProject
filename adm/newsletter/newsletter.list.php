<?php
	$sub_menu = "990400";
	include_once('./_common.php');

	auth_check($auth[$sub_menu], "r");


	# wh
	$wh = "Where 1 = 1";

	if($SC_WORD != "") {
		$wh .= " And NS.NS_TITLE like '%" . mres($SC_WORD) . "%'";
	}


	$sql = "Select Count(*) As cnt"
		."	From NX_NEWSLETTER_SEND As NS"
		."	{$wh}"
		;
	$row = sql_fetch($sql);
	$total_count = $row['cnt'];

	$rows = $config['cf_page_rows'];
	$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
	if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
	$from_record = ($page - 1) * $rows; // 시작 열을 구함


	$sql = "Select NS.*"
		."		, (Select Count(*) From NX_NEWSLETTER_TARGET Where NS_IDX = NS.NS_IDX) As NT_CNT"
		."	From NX_NEWSLETTER_SEND As NS"
		."	{$wh}"
		."	Order By NS.NS_IDX Desc"
		."	Limit {$from_record}, {$rows} "
		;
	$row = sql_query($sql);


	$g5[title] = "뉴스레터 발송";
	include_once('../admin.head.php');
?>

<div class="sch_box">
	<form id="frmSch" name="frmSch" method="get" action="<?php echo($_SERVER['PHP_SELF'])?>">
	<input type="submit" style="width:1px;height:1px;position:absolute;top:-10000px;left:-10000px" />

	<div class="sch_opt1">
		<div class="sch_opt2">
			<span class="sch_ipt wm2" data-tit="제목">
				<input type="text" id="SC_WORD" name="SC_WORD" value="<?php echo(F_hsc($SC_WORD))?>" class="nx-ips1" />
			</span>
		</div>
	</div>

	<a href="javascript:void(0);" onclick="$('#frmSch').submit();" class="btn_sch"><span class="ico_search2"></span></a>

	</form>
</div>

<div class="taR mb10">
	<a href="newsletter.add.php?<?php echo($phpTail)?>page=<?php echo($page)?>" class="nx-btn-b2">발송</a>
</div>

<table border="0" cellpadding="0" cellspacing="0" class="nx-t-list1">
	<colgroup>
		<col width="80"><col width=""><col width="160"><col width="100"><col width="100"><col width="100">
	</colgroup>
	<thead>
		<tr>
			<th>No.</th>
			<th>제목</th>
			<th>발송시각</th>
			<th>인원</th>
			<th>상태</th>
			<th>실패이력</th>
		</tr>
	</thead>
	<tbody>
		<?php
		if (sql_num_rows($row) <= 0) {
			echo '<tr><td colspan="6">뉴스레터 발송 이력이 없습니다.</td></tr>';
		}
		else {
			$s = 0;
			while ($rs1 = sql_fetch_array($row)) {
				?>
		<tr>
			<td><?php echo($total_count - $from_record - $s)?></td>
			<td class="taL">
				<div class="elss3">
					<a href="newsletter.read.php?<?php echo($phpTail)?>page=<?php echo($page)?>&NS_IDX=<?php echo($rs1['NS_IDX'])?>" class="color-tit"><?php echo(F_hsc($rs1['NS_TITLE']))?></a>
				</div>
			</td>
			<td><?php echo(F_hsc(substr($rs1['NS_SDATE'], 0, 16)))?></td>
			<td><?php echo(number_format($rs1['NT_CNT']))?></td>
			<td><?php if($rs1['NS_STATUS'] == "A") { ?>저장<?php } else if($rs1['NS_STATUS'] == "B") { ?>발송<?php } ?></td>
			<td>
				<?php
				# 로그기록 시작일 이후이면
				if (strtotime($rs1['NS_SDATE']) > strtotime('2018-07-24')) {
					?>
				<a href="newsletter.log.php?<?php echo($phpTail)?>date=<?php echo(date('Ymd', strtotime($rs1['NS_SDATE'])))?>" class="nx-btn3">실패이력</a>
					<?php
				}
				?>
			</td>	
		</tr>
				<?php
				$s++;
			}
		}
		?>
	</tbody>
</table>

<div class="taR mt10">
	<a href="newsletter.add.php?<?php echo($phpTail)?>page=<?php echo($page)?>" class="nx-btn-b2">발송</a>
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
