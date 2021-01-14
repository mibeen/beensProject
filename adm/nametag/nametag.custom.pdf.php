<?php
	$sub_menu = "990600";
	include_once('./_common.php');
	include_once('./nametag.custom.err.php');

	auth_check($auth[$sub_menu], "w");


	# set : variables
	$ENC_IDX = $_POST['ENC_IDX'];


	# re-define
	$ENC_IDX = CHK_NUMBER($ENC_IDX);


	# chk : rfv.
	if ($ENC_IDX <= 0) {
		exit();
	}


	function hex2rgba($color) {
		//Sanitize $color if "#" is provided
		$color = str_replace('#', '', $color);

		//Check if color has 6 or 3 characters and get values
		if (strlen($color) == 6) {
			$hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
		} elseif ( strlen( $color ) == 3 ) {
			$hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
		} else {
			return $default;
		}

		//Convert hexadec to rgb
		$rgb = array_map('hexdec', $hex);

		//Return rgb(a) color string
		return $rgb;
	}


	require_once(G5_PLUGIN_PATH.'/fpdf17/korean.php');


	$pdf = new PDF_Korean('L', 'pt');

	$pdf->AddUHCFont();
	$pdf->AddUHCFont('명조');
	$pdf->AddUHCFont('맑은 고딕', 'Malgun Gothic');
	$pdf->SetDrawColor(213, 213, 213);


	$sql = "Select ENT.*"
		."	From NX_EVENT_NAMETAG_CUSTOM As ENC"
		."		Inner Join NX_EVENT_NAMETAG_TEMPLATE As ENT On ENT.ENC_IDX = ENC.ENC_IDX"
		."			And ENT.ENT_DDATE is null"
		."	Where ENC.ENC_DDATE is null"
		."		And ENT.ENT_DDATE is null"
		."		And ENC.ENC_IDX = '" . mres($ENC_IDX) . "'"
		."	Order By ENT.ENT_IDX Desc"
		."	Limit 1"
		;
	$row = sql_fetch($sql);
	if (is_null($row['ENC_IDX'])) {
		F_script("요청하신 정보가 존재하지 않습니다.", "window.location.href='nametag.custom.list.php';");
	}

	$db_ent = $row;


	$ENT_WIDTH = round($db_ent['ENT_WIDTH'] * (72 / 25.4));
	$ENT_HEIGHT = round($db_ent['ENT_HEIGHT'] * (72 / 25.4));


	# get files
	$_dir = "NX_EVENT_NAMETAG_TEMPLATE";
	$_files = get_file('NX_EVENT_NAMETAG_TEMPLATE', ($ENT_IDX > 0) ? $ENT_IDX : $row['ENT_IDX']);

	if (is_null($_files[1]) || $_files[1]['file'] == '') {
		alert("엑셀파일을 불러오는데 실패했습니다.");
		echo('<script>window.close();</script>');
	}
	else {
		if (file_exists(G5_DATA_PATH.'/file/'.$_dir.'/'.$_files[1]['file'])) {
			$file_excel = G5_DATA_PATH.'/file/'.$_dir.'/'.$_files[1]['file'];
		}
		else {
			alert("엑셀파일을 불러오는데 실패했습니다.");
			echo('<script>window.close();</script>');
		}
	}

	
	include "./inc.nametag.excel.php";


	$s = 0;
	foreach ($lines as $key => $line) {
		if($ENT_HEIGHT > 244) {
			if($s % 2 == 0) {
				$left = 28;
				$top = 28;
				$pdf->AddPage();
			} else if($s % 2 == 1) {
				$left = 424;
				$top = 28;
				$pdf->SetY($top);
			}
		} else {
			if($s % 4 == 0) {
				$left = 28;
				$top = 28;
				$pdf->AddPage();
			} else if($s % 4 == 1) {
				$left = 424;
				$top = 28;
				$pdf->SetY($top);
			} else if($s % 4 == 2) {
				$left = 28;
				$top = $ENT_HEIGHT + 38;
				$pdf->SetY($top);
			} else if($s % 4 == 3) {
				$left = 424;
				$top = $ENT_HEIGHT + 38;
				$pdf->SetY($top);
			}
		}


		if($s % 2 == 1) $pdf->SetX($left);
		$pdf->Cell($ENT_WIDTH, $ENT_HEIGHT, '', 0, 0, 'C', false);


		$_img = G5_PATH.'/adm/evt/imgs/cross.jpg';
		$pdf->Image($_img, $left - 7, $top - 7, 10);
		$pdf->Image($_img, $left + $ENT_WIDTH - 3, $top - 7, 10);
		$pdf->Image($_img, $left - 7, $top + $ENT_HEIGHT - 3, 10);
		$pdf->Image($_img, $left + $ENT_WIDTH - 3, $top + $ENT_HEIGHT - 3, 10);


		$_img = null;
		$_file = $_files[0];
		if (!is_null($_file) && $_file['file'] != '') {
			if (file_exists(G5_DATA_PATH.'/file/'.$_dir.'/'.$_file['file'])) {
				$_img = G5_DATA_PATH.'/file/'.$_dir.'/'.$_file['file'];
			}
		}


		if ($_img != "") $pdf->Image($_img, $left, $top, $ENT_WIDTH);


		$pdf->Ln(6);


		if($line[0] != "") {
			$pdf->Ln($db_ent['ENT_F1_Y']);
			$pdf->SetFont('맑은 고딕', (($db_ent['ENT_F1_BOLD'] == 'Y') ? 'B' : '') . (($db_ent['ENT_F1_UNDERLINE'] == 'Y') ? 'U' : ''), $db_ent['ENT_F1_SIZE']);
			$rgb = hex2rgba(($db_ent['ENT_F1_COLOR'] != '') ? $db_ent['ENT_F1_COLOR'] : 'ad251f');
			$pdf->SetTextColor($rgb[0], $rgb[1], $rgb[2]);
			$align = "C";
			if($db_ent['ENT_F1_ALIGN'] == "LEFT") $align = "L"; else if($db_ent['ENT_F1_ALIGN'] == "RIGHT") $align = "R";
			if($s % 2 == 1) $pdf->SetX($left + $db_ent['ENT_F1_LEFT']); else if($db_ent['ENT_F1_LEFT'] > 0) $pdf->SetX(28 + $db_ent['ENT_F1_LEFT']);
			$pdf->Cell($ENT_WIDTH - $db_ent['ENT_F1_LEFT'] - $db_ent['ENT_F1_RIGHT'], $db_ent['ENT_F1_SIZE'], iconv('utf-8', 'euc-kr', F_hsc($line[0])), 0, 0, $align, false);
			$pdf->Ln($db_ent['ENT_F1_Y'] * -1);
		}

		if($line[1] != "") {
			$pdf->Ln($db_ent['ENT_F2_Y']);
			$pdf->SetFont('맑은 고딕', (($db_ent['ENT_F2_BOLD'] == 'Y') ? 'B' : '') . (($db_ent['ENT_F2_UNDERLINE'] == 'Y') ? 'U' : ''), $db_ent['ENT_F2_SIZE']);
			$rgb = hex2rgba(($db_ent['ENT_F2_COLOR'] != '') ? $db_ent['ENT_F2_COLOR'] : 'ad251f');
			$pdf->SetTextColor($rgb[0], $rgb[1], $rgb[2]);
			$align = "C";
			if($db_ent['ENT_F2_ALIGN'] == "LEFT") $align = "L"; else if($db_ent['ENT_F2_ALIGN'] == "RIGHT") $align = "R";
			if($s % 2 == 1) $pdf->SetX($left + $db_ent['ENT_F2_LEFT']); else if($db_ent['ENT_F2_LEFT'] > 0) $pdf->SetX(28 + $db_ent['ENT_F2_LEFT']);
			$pdf->Cell($ENT_WIDTH - $db_ent['ENT_F2_LEFT'] - $db_ent['ENT_F2_RIGHT'], $db_ent['ENT_F2_SIZE'], iconv('utf-8', 'euc-kr', F_hsc($line[1])), 0, 0, $align, false);
			$pdf->Ln($db_ent['ENT_F2_Y'] * -1);
		}

		if($line[2] != "") {
			$pdf->Ln($db_ent['ENT_F3_Y']);
			$pdf->SetFont('맑은 고딕', (($db_ent['ENT_F3_BOLD'] == 'Y') ? 'B' : '') . (($db_ent['ENT_F3_UNDERLINE'] == 'Y') ? 'U' : ''), $db_ent['ENT_F3_SIZE']);
			$rgb = hex2rgba(($db_ent['ENT_F3_COLOR'] != '') ? $db_ent['ENT_F3_COLOR'] : 'ad251f');
			$pdf->SetTextColor($rgb[0], $rgb[1], $rgb[2]);
			$align = "C";
			if($db_ent['ENT_F3_ALIGN'] == "LEFT") $align = "L"; else if($db_ent['ENT_F3_ALIGN'] == "RIGHT") $align = "R";
			if($s % 2 == 1) $pdf->SetX($left + $db_ent['ENT_F3_LEFT']); else if($db_ent['ENT_F3_LEFT'] > 0) $pdf->SetX(28 + $db_ent['ENT_F3_LEFT']);
			$pdf->Cell($ENT_WIDTH - $db_ent['ENT_F3_LEFT'] - $db_ent['ENT_F3_RIGHT'], $db_ent['ENT_F3_SIZE'], iconv('utf-8', 'euc-kr', F_hsc($line[2])), 0, 0, $align, false);
			$pdf->Ln($db_ent['ENT_F3_Y'] * -1);
		}

		$pdf->Ln($db_ent['ENT_F4_Y']);
		$pdf->SetFont('맑은 고딕', (($db_ent['ENT_F4_BOLD'] == 'Y') ? 'B' : '') . (($db_ent['ENT_F4_UNDERLINE'] == 'Y') ? 'U' : ''), $db_ent['ENT_F4_SIZE']);
		$rgb = hex2rgba(($db_ent['ENT_F4_COLOR'] != '') ? $db_ent['ENT_F4_COLOR'] : '222222');
		$pdf->SetTextColor($rgb[0], $rgb[1], $rgb[2]);
		$align = "C";
		if($db_ent['ENT_F4_ALIGN'] == "LEFT") $align = "L"; else if($db_ent['ENT_F4_ALIGN'] == "RIGHT") $align = "R";
		if($s % 2 == 1) $pdf->SetX($left + $db_ent['ENT_F4_LEFT']); else if($db_ent['ENT_F4_LEFT'] > 0) $pdf->SetX(28 + $db_ent['ENT_F4_LEFT']);
		$pdf->Cell($ENT_WIDTH - $db_ent['ENT_F4_LEFT'] - $db_ent['ENT_F4_RIGHT'], $db_ent['ENT_F4_SIZE'], iconv('utf-8', 'euc-kr', F_hsc($line[3])), 0, 0, $align, false);


		unset($_arr);


		$s++;
	}
	unset($lines, $line);


	$pdf->Output();
?>
