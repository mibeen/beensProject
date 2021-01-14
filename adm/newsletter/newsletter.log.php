<?php
	$sub_menu = "990400";
	include_once('./_common.php');

	auth_check($auth[$sub_menu], "r");


	# chk : rfv.
	if ($date == '') {
		header("location:newsletter.list.php?".$phpTail);
		exit();
	}


	$g5[title] = "뉴스레터 발송실패 이력 - " . $date;
	include_once G5_ADMIN_PATH."/admin.head.php";
?>

<div class="ofH mb10">
	<div class="fL">
		<a href="newsletter.log.excel.php?<?php echo($phpTail)?>date=<?php echo($date)?>" class="nx-btn-b1">엑셀 다운로드</a>
	</div>
	<div class="fR">
		<a href="newsletter.list.php?<?php echo($phpTail)?>" class="nx-btn-b3">목록</a>
	</div>
</div>

<table border="0" cellpadding="0" cellspacing="0" class="nx-t-list1">
	<colgroup>
		<col width="80"><col width=""><col width=""><col width="">
	</colgroup>
	<thead>
		<tr>
			<th>No.</th>
			<th>이메일</th>
			<th>오류코드</th>
			<th>오류메시지</th>
		</tr>
	</thead>
	<tbody>
		<?php
		function startsWith($haystack, $needle)
		{
		     $length = strlen($needle);
		     return (substr($haystack, 0, $length) === $needle);
		}
		
		$s = 0;

		if (file_exists(G5_DATA_PATH."/log_directsend/log_mail".$date)) {
			$fp = fopen(G5_DATA_PATH."/log_directsend/log_mail".$date, "r");
			while( !feof($fp) ) {
				$line = fgets($fp);
				if (startsWith($line, '{"ID"')) {
					$json = json_decode($line);
					if ($json->Result == 'fail') {
						$s++;
						?>
			<tr>
				<td><?php echo($s)?></td>
				<td><?php echo($json->Recipients[0]->Email)?></td>
				<td><?php echo($json->Recipients[0]->SmtpCode)?></td>
				<td><?php echo($json->Recipients[0]->SmtpMsg)?></td>
			</tr>
						<?php
					}
				}
			}
			fclose($fp); 

			if ($s == 0) {
				echo '<tr><td colspan="4">뉴스레터 발송실패 이력이 없습니다.</td></tr>';
			}
		}
		else {
			echo '<tr><td colspan="4">로그파일이 존재하지 않습니다.</td></tr>';
		}
		?>
	</tbody>
</table>

<?php
	//마지막 인클루드
	include_once G5_ADMIN_PATH."/admin.tail.php";
?>