<?php
	$sub_menu = "990400";
	include_once('./_common.php');

	auth_check($auth[$sub_menu], "r");


	if ($date == '') {
		F_script("발송일자가 선택되지 않았습니다.", "window.location.href='newsletter.list.php?".$phpTail."'");
		exit();
	}


	if (!file_exists(G5_DATA_PATH."/log_directsend/log_mail".$date)) {
		F_script("로그파일이 존재하지 않습니다.", "window.location.href='newsletter.list.php?".$phpTail."'");
		exit();
	}


	/*	CSV 용 string 으로 변환
		- 전달된 값에 '"' 를 더함
		- 전달된 값의 '"' 를 '""' 로 치환
	*/
	function F_CSVSTR($v)
	{
		if ($v == '') return;

		$ret = "\"" . str_replace("\"", "\"\"", $v) . "\"";
		return $ret;
	}

	function startsWith($haystack, $needle)
	{
	     $length = strlen($needle);
	     return (substr($haystack, 0, $length) === $needle);
	}

	
	header("Content-Type: text/csv; charset=utf-8");
	header("Content-Disposition: attachment; fileName=".iconv('utf-8', 'euc-kr', '뉴스레터 발송실패 이력')."_" . date("ymd") . ".csv");
	header("Content-Description: PHP" . phpversion() . " Generated Data");
	echo("\xEF\xBB\xBF");

	
	# header
	echo F_CSVSTR("NO.");
	echo "," . F_CSVSTR("이메일");
	echo "," . F_CSVSTR("오류코드");
	echo "," . F_CSVSTR("오류메시지");

	echo("\r\n");


	$s = 0;
	$fp = fopen(G5_DATA_PATH."/log_directsend/log_mail".$date, "r");
	while( !feof($fp) ) {
		$line = fgets($fp);
		if (startsWith($line, '{"ID"')) {
			$json = json_decode($line);
			if ($json->Result == 'fail') {
				$s++;

				echo F_CSVSTR($s);
				echo "," . F_CSVSTR($json->Recipients[0]->Email);
				echo "," . F_CSVSTR($json->Recipients[0]->SmtpCode);
				echo "," . F_CSVSTR($json->Recipients[0]->SmtpMsg);


				echo("\r\n");
			}
		}
	}
	exit;
?>
