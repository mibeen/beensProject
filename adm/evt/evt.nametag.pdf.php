<?php
	include_once('./_common.php');
	include_once('./evt.err.php'); 	# $sub_menu = ($EP_IDX > 0) ? '980100' : '990100'

	auth_check($auth[$sub_menu], "w");


	# set : variables
	$EM_IDX = $_POST['EM_IDX'];
	$EM_TITLE = $_POST['EM_TITLE'];


	# re-define
	$EM_IDX = CHK_NUMBER($EM_IDX);
	$EM_TITLE = trim($EM_TITLE);


	# chk : rfv.
	if ($EM_IDX <= 0 || $EM_TITLE == '') {
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
		."	From NX_EVENT_MASTER As EM"
		."		Inner Join NX_EVENT_NAMETAG_TEMPLATE As ENT On ENT.EM_IDX = EM.EM_IDX"
		."			And ENT.ENT_DDATE is null"
		."	Where EM.EM_DDATE is null"
		."		And EM.EM_IDX = '" . mres($EM_IDX) . "'"
		."	Order By ENT.ENT_IDX Desc"
		."	Limit 1"
		;
	$row = sql_fetch($sql);
	if (is_null($row['EM_IDX'])) {
		F_script("요청하신 정보가 존재하지 않습니다.", "window.location.href='evt.list.php?'".$epTail.";");
	}


	$db_ent = $row;


	$ENT_WIDTH = round($db_ent['ENT_WIDTH'] * (72 / 25.4));
	$ENT_HEIGHT = round($db_ent['ENT_HEIGHT'] * (72 / 25.4));


	# get data
	$sql = "Select EJ.EJ_IDX, EJ.EJ_JOIN_CODE, EJ.EJ_NAME, EJ.EJ_MOBILE, EJ.EJ_EMAIL, EJ.EJ_ORG"
		."		, EM.EM_TITLE"
		."	From NX_EVENT_JOIN As EJ"
		."		Inner Join NX_EVENT_MASTER As EM On EM.EM_IDX = EJ.EM_IDX"
		."			And EM.EM_DDATE is null"
		."	Where EJ.EJ_DDATE is null And EJ.EM_IDX = '" . mres($EM_IDX) . "' And EJ.EJ_STATUS = '2'"
		."	Order By EJ.EJ_IDX Desc"
		;
	$db2 = sql_query($sql);

	$s = 0;
	while($rs2 = sql_fetch_array($db2)) {
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


		$_dir = "NX_EVENT_NAMETAG_TEMPLATE";
		$_files = get_file('NX_EVENT_NAMETAG_TEMPLATE', ($ENT_IDX > 0) ? $ENT_IDX : $row['ENT_IDX']);
		$_img = null;
		for ($k = 0; $k < Count($_files); $k++) {
			$_file = $_files[$k];

			if (!$_file['file']) continue;

			if (file_exists(G5_DATA_PATH.'/file/'.$_dir.'/'.$_file['file'])) {
				$_img = G5_DATA_PATH.'/file/'.$_dir.'/'.$_file['file'];
			}
		}
		unset($_dir, $_files);


		if ($_img != "") $pdf->Image($_img, $left, $top, $ENT_WIDTH);


		$pdf->Ln(6);


		$_arr = ['NAME'=>$rs2['EJ_NAME'], 'MOBILE'=>$rs2['EJ_MOBILE'], 'EMAIL'=>$rs2['EJ_EMAIL'], 'ORG'=>$rs2['EJ_ORG'], 'EM_TITLE'=>$EM_TITLE];


		if($db_ent['ENT_F1_KIND'] != "") {
			$pdf->Ln($db_ent['ENT_F1_Y']);
			$pdf->SetFont('맑은 고딕', (($db_ent['ENT_F1_BOLD'] == 'Y') ? 'B' : '') . (($db_ent['ENT_F1_UNDERLINE'] == 'Y') ? 'U' : ''), $db_ent['ENT_F1_SIZE']);
			$rgb = hex2rgba(($db_ent['ENT_F1_COLOR'] != '') ? $db_ent['ENT_F1_COLOR'] : 'ad251f');
			$pdf->SetTextColor($rgb[0], $rgb[1], $rgb[2]);
			$align = "C";
			if($db_ent['ENT_F1_ALIGN'] == "LEFT") $align = "L"; else if($db_ent['ENT_F1_ALIGN'] == "RIGHT") $align = "R";
			if($s % 2 == 1) $pdf->SetX($left + $db_ent['ENT_F1_LEFT']); else if($db_ent['ENT_F1_LEFT'] > 0) $pdf->SetX(28 + $db_ent['ENT_F1_LEFT']);
			$pdf->Cell($ENT_WIDTH - $db_ent['ENT_F1_LEFT'] - $db_ent['ENT_F1_RIGHT'], $db_ent['ENT_F1_SIZE'], iconv('utf-8', 'euc-kr', F_hsc($_arr[$db_ent['ENT_F1_KIND']])), 0, 0, $align, false);
			$pdf->Ln($db_ent['ENT_F1_Y'] * -1);
		}

		if($db_ent['ENT_F2_KIND'] != "") {
			$pdf->Ln($db_ent['ENT_F2_Y']);
			$pdf->SetFont('맑은 고딕', (($db_ent['ENT_F2_BOLD'] == 'Y') ? 'B' : '') . (($db_ent['ENT_F2_UNDERLINE'] == 'Y') ? 'U' : ''), $db_ent['ENT_F2_SIZE']);
			$rgb = hex2rgba(($db_ent['ENT_F2_COLOR'] != '') ? $db_ent['ENT_F2_COLOR'] : 'ad251f');
			$pdf->SetTextColor($rgb[0], $rgb[1], $rgb[2]);
			$align = "C";
			if($db_ent['ENT_F2_ALIGN'] == "LEFT") $align = "L"; else if($db_ent['ENT_F2_ALIGN'] == "RIGHT") $align = "R";
			if($s % 2 == 1) $pdf->SetX($left + $db_ent['ENT_F2_LEFT']); else if($db_ent['ENT_F2_LEFT'] > 0) $pdf->SetX(28 + $db_ent['ENT_F2_LEFT']);
			$pdf->Cell($ENT_WIDTH - $db_ent['ENT_F2_LEFT'] - $db_ent['ENT_F2_RIGHT'], $db_ent['ENT_F2_SIZE'], iconv('utf-8', 'euc-kr', F_hsc($_arr[$db_ent['ENT_F2_KIND']])), 0, 0, $align, false);
			$pdf->Ln($db_ent['ENT_F2_Y'] * -1);
		}

		if($db_ent['ENT_F3_KIND'] != "") {
			$pdf->Ln($db_ent['ENT_F3_Y']);
			$pdf->SetFont('맑은 고딕', (($db_ent['ENT_F3_BOLD'] == 'Y') ? 'B' : '') . (($db_ent['ENT_F3_UNDERLINE'] == 'Y') ? 'U' : ''), $db_ent['ENT_F3_SIZE']);
			$rgb = hex2rgba(($db_ent['ENT_F3_COLOR'] != '') ? $db_ent['ENT_F3_COLOR'] : 'ad251f');
			$pdf->SetTextColor($rgb[0], $rgb[1], $rgb[2]);
			$align = "C";
			if($db_ent['ENT_F3_ALIGN'] == "LEFT") $align = "L"; else if($db_ent['ENT_F3_ALIGN'] == "RIGHT") $align = "R";
			if($s % 2 == 1) $pdf->SetX($left + $db_ent['ENT_F3_LEFT']); else if($db_ent['ENT_F3_LEFT'] > 0) $pdf->SetX(28 + $db_ent['ENT_F3_LEFT']);
			$pdf->Cell($ENT_WIDTH - $db_ent['ENT_F3_LEFT'] - $db_ent['ENT_F3_RIGHT'], $db_ent['ENT_F3_SIZE'], iconv('utf-8', 'euc-kr', F_hsc($_arr[$db_ent['ENT_F3_KIND']])), 0, 0, $align, false);
			$pdf->Ln($db_ent['ENT_F3_Y'] * -1);
		}

		$pdf->Ln($db_ent['ENT_F4_Y']);
		$pdf->SetFont('맑은 고딕', (($db_ent['ENT_F4_BOLD'] == 'Y') ? 'B' : '') . (($db_ent['ENT_F4_UNDERLINE'] == 'Y') ? 'U' : ''), $db_ent['ENT_F4_SIZE']);
		$rgb = hex2rgba(($db_ent['ENT_F4_COLOR'] != '') ? $db_ent['ENT_F4_COLOR'] : '222222');
		$pdf->SetTextColor($rgb[0], $rgb[1], $rgb[2]);
		$align = "C";
		if($db_ent['ENT_F4_ALIGN'] == "LEFT") $align = "L"; else if($db_ent['ENT_F4_ALIGN'] == "RIGHT") $align = "R";
		if($s % 2 == 1) $pdf->SetX($left + $db_ent['ENT_F4_LEFT']); else if($db_ent['ENT_F4_LEFT'] > 0) $pdf->SetX(28 + $db_ent['ENT_F4_LEFT']);
		$pdf->Cell($ENT_WIDTH - $db_ent['ENT_F4_LEFT'] - $db_ent['ENT_F4_RIGHT'], $db_ent['ENT_F4_SIZE'], iconv('utf-8', 'euc-kr', F_hsc($rs2['EJ_JOIN_CODE'])), 0, 0, $align, false);


		unset($_arr);


		$s++;
	}
	unset($db2, $rs2);


	$pdf->Output();
?>
