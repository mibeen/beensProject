<?php
	$sub_menu = "990400";
	include_once('./_common.php');

	auth_check($auth[$sub_menu], "r");


	# set : variables
	$NS_IDX = $_GET['NS_IDX'];


	# re-define
	$NS_IDX = (is_numeric($NS_IDX)) ? (int)$NS_IDX : "";


	$sql = "Select NS.*"
		."		, (Select Count(*) From NX_NEWSLETTER_TARGET Where NS_IDX = NS.NS_IDX) As NT_CNT"
		."		, (Select Count(*) From NX_NEWSLETTER_TARGET Where NS_IDX = NS.NS_IDX And NT_SDATE > '') As NT_SUCCESS_CNT"
		."	From NX_NEWSLETTER_SEND As NS"
		."	Where NS.NS_IDX = '" . mres($NS_IDX) . "'"
		;
	$row = sql_query($sql);
	if (sql_num_rows($row) <= 0) {
		F_script("요청하신 정보가 존재하지 않습니다.", "history.back();");
	}
	$rs1 = sql_fetch_array($row);


	$g5[title] = "뉴스레터 발송";
	include_once('../admin.head.php');
?>
<script>
//<![CDATA[
function oncl_Send() {
	if(confirm("재발송하시겠습니까?")) {
		var f = new FormData();
		f.append('NS_IDX', '<?php echo($NS_IDX)?>');

		$.ajax({
			url: 'newsletter.sendProc.php',
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
			alert("재발송되었습니다.");
			window.location.reload();
		})
		.fail(function(a, b, c) {
			// alert(a.statusText+' ('+a.status+')\n\n'+a.responseText);
			//alert(a.responseText);
		});
	}
}

function oncl_Del() {
	if(confirm("삭제하시겠습니까?")) {
		var f = new FormData();
		f.append('NS_IDX', '<?php echo($NS_IDX)?>');

		$.ajax({
			url: 'newsletter.delProc.php',
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
			window.location.href = "newsletter.list.php?<?php echo($phpTail)?>page=<?php echo($page)?>";
		})
		.fail(function(a, b, c) {
			alert(a.statusText+' ('+a.status+')\n\n'+a.responseText);
			//alert(a.responseText);
		});
	}
}

function openPreview () {
	var win = window.open("", "Title", "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=700,height=600,top=50,left=50");
	win.document.body.innerHTML = document.getElementById("NS_CONTENT").value;
	return true;
}
//]]>
</script>

<div class="taR mb10">
	<a href="javascript: openPreview();" class="nx-btn-b2">미리보기</a>
	<a href="newsletter.list.php?<?php echo($phpTail)?>page=<?php echo($page)?>" class="nx-btn-b3">뒤로</a>
</div>

<table border="0" cellpadding="0" cellspacing="0" class="nx-t-read1">
	<colgroup>
		<col width="130"><col width="">
	</colgroup>
	<tbody>
		<tr>
			<th>구독자</th>
			<td><?php echo(number_format($rs1['NT_CNT']))?>명</td>
		</tr>
		<tr>
			<th>제목</th>
			<td><?php echo(F_hsc($rs1['NS_TITLE']))?></td>
		</tr>
		<tr>
			<th>내용등록</th>
			<td>
				<textarea style="display: none;" name="NS_CONTENT" id="NS_CONTENT" cols="30" rows="10"><?php echo(F_hsc($rs1['NS_CONTENT']))?></textarea>
				<pre style="min-height:150px;max-height:600px;white-space: pre-wrap;white-space: -moz-pre-wrap;white-space: -pre-wrap;white-space: -o-pre-wrap;"><?php echo(F_hsc($rs1['NS_CONTENT']))?></pre>
			</td>
		</tr>
	</tbody>
</table>

<div class="taR mt10">
	<a href="newsletter.list.php?<?php echo($phpTail)?>page=<?php echo($page)?>" class="nx-btn-b3">뒤로</a>
</div>

<div class="ofH mt40 mb10">
	<div class="fL"><div class="nx-tit1">전체 <?php echo(number_format($rs1['NT_CNT']))?> / 성공 <?php echo(number_format($rs1['NT_SUCCESS_CNT']))?> / 미발송 <?php echo(number_format($rs1['NT_CNT'] - $rs1['NT_SUCCESS_CNT']))?></div></div>
	<div class="fR">
		<?php
		if($rs1['NT_CNT'] > 0 && $rs1['NT_CNT'] > $rs1['NT_SUCCESS_CNT']) {
			?>
			<a href="javascript:oncl_Send();" class="nx-btn-b2">미발송대상 재발송</a>
			<?php
		}

		if($rs1['NT_SUCCESS_CNT'] == 0) {
			?>
			<a href="javascript:oncl_Del();" class="nx-btn-b4">삭제</a>
			<?php
		}
		?>
	</div>
</div>

<table border="0" cellpadding="0" cellspacing="0" class="nx-t-list1">
	<colgroup>
		<col width="100"><col width=""><col width="20%">
	</colgroup>
	<thead>
		<tr>
			<th>No.</th>
			<th>이메일</th>
			<th>발송성공여부</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$sql = "Select NT.*"
			."	From NX_NEWSLETTER_TARGET As NT"
			."	Where NT.NS_IDX = '" . mres($NS_IDX) . "'"
			."	Order By NT.NM_IDX Desc"
			;
		$row = sql_query($sql);

		$total_count = sql_num_rows($row);

		if ($total_count <= 0) {
			echo '<tr><td colspan="3">발송 대상이 없습니다.</td></tr>';
		}
		else {
			$s = 0;
			while ($rs1 = sql_fetch_array($row)) {
		?>
		<tr>
			<td><?php echo($total_count - $s)?></td>
			<td class="taL"><?php echo(F_hsc($rs1['NT_EMAIL']))?></td>
			<td><?php if(is_null($rs1['NT_SDATE'])) { ?>미발송<?php } else { ?>성공<?php } ?></td>
		</tr>
				<?php
				$s++;
			}
		}
		?>
	</tbody>
</table>

<?php
	//마지막 인클루드
	include_once G5_ADMIN_PATH."/admin.tail.php";
?>
