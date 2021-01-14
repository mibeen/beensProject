<?php
	include_once('./_common.php');
	include_once('./evt.err.php'); 	# $sub_menu = ($EP_IDX > 0) ? '980100' : '990100'

	auth_check($auth[$sub_menu], "r");


	# chk : rfv.
	if ($EM_IDX <= 0) {
		header("location:evt.list.php?".$epTail);
		exit();
	}


	# 관리자 개인정보 접근이력 기록
	nx_privacy_log('excel', 'NX_EVENT_MASTER', $EM_IDX);


	$sts = $_GET['sts'];
	$sts = ((int)$sts >= 1 && (int)$sts <= 2) ? (int)$sts : '';

	$stx = $_GET['stx'];


	# wh : 승인된 신청자 중에서 보임
	$wh = "Where EJ.EJ_DDATE is null And EJ.EM_IDX = '" . mres($EM_IDX) . "' And EJ.EJ_STATUS = '2'";
	
	# 신청자명/접수번호
	if ($stx != '') {
		$wh .= " And (EJ.EJ_NAME like '%" . mres($stx) . "%' Or EJ.EJ_JOIN_CODE = '" . mres($stx) . "')";
	}

	# sts (status)
	switch ($sts) {
		case '1':	# 미참석
			$wh .= " And (EJ.EJ_JOIN_CHK1 = 'N' And EJ.EJ_JOIN_CHK2 = 'N')";
			break;

		case '2':	# 참석
			$wh .= " And (EJ.EJ_JOIN_CHK1 = 'Y' And EJ.EJ_JOIN_CHK2 = 'Y')";
			break;
		
		default:
			break;
	}


	#----- get : form 입력 값
	$sql = "Select FV.FI_IDX, FV.FO_IDX, FV.FV_VAL, FV.FV_EXT1, FV.FV_EXT2, FV.FV_FILEYN, FV.FV_RNDCODE, FV.mb_id, FV.EM_IDX, FV.EJ_IDX"
		."		, FI.FI_SEQ, FI.FI_KIND, FI.FI_NAME, FI.FI_TYPE"
		."		, FO.FO_VAL"
		."		, Case When FI.FI_KIND in('A','B','E','F','G') Then FV.FV_VAL When FI.FI_KIND in('C','D') Then FO.FO_VAL Else '' End As EL_VAL"
		."	From NX_EVENT_FORM_VAL As FV"
		."		Inner Join NX_EVENT_FORM_ITEM As FI On FI.FI_IDX = FV.FI_IDX"
		."			And FI.FI_DDATE is null And FI.FI_USE_YN = 'Y'"
		."		Left Join NX_EVENT_FORM_OPT As FO On FO.FO_IDX = FV.FO_IDX"
		."			And FO.FO_DDATE is null And FO.FI_IDX = FI.FI_IDX"
		."	Where FI.EM_IDX = '" . mres($EM_IDX) . "'"
		."	Order By FI.FI_SEQ Asc"
		;
	$db1 = sql_query($sql);

	$FV_EXT1 = array();
	$FV_EXT2 = array();
	$EL_VAL = array();
	
	while ($rs1 = sql_fetch_array($db1))
	{
		$FV_EXT1["{$rs1['mb_id']}_{$rs1['EM_IDX']}_{$rs1['EJ_IDX']}_{$rs1['FI_IDX']}_{$rs1['FO_IDX']}"] = $rs1['FV_EXT1'];
		$FV_EXT2["{$rs1['mb_id']}_{$rs1['EM_IDX']}_{$rs1['EJ_IDX']}_{$rs1['FI_IDX']}_{$rs1['FO_IDX']}"] = $rs1['FV_EXT2'];
		$EL_VAL["{$rs1['mb_id']}_{$rs1['EM_IDX']}_{$rs1['EJ_IDX']}_{$rs1['FI_IDX']}_{$rs1['FO_IDX']}"] = $rs1['EL_VAL'];
	}
	unset($db1, $rs1);
	#####


	#----- get : form item 목록
	$sql = "Select FI.FI_IDX, FI.FI_KIND, FI.FI_NAME"
		. "		From NX_EVENT_FORM_ITEM As FI"
		. "		Where FI.FI_DDATE is null And FI.FI_USE_YN = 'Y' And FI.EM_IDX = '" . mres($EM_IDX) . "'"
		. "		Order By FI.FI_SEQ Asc"
		;
	$db1 = sql_query($sql);

	$fis = array();
	while ($rs1 = sql_fetch_array($db1)) {
		$fis[] = array(
			'FI_IDX'=>$rs1['FI_IDX']
			, 'FI_KIND'=>$rs1['FI_KIND']
			, 'FI_NAME'=>$rs1['FI_NAME']
		);
	}
	#####


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


	#----- get : 참석자 목록
	$sql = "Select EJ.*"
		."		, DATE_FORMAT(EJ.EJ_WDATE, '%Y-%m-%d %H:%i') As EJ_WDATE"
		."		, DATE_FORMAT(EM.EM_S_DATE, '%Y%m%d') As EM_S_DATE"
		."	From NX_EVENT_JOIN As EJ"
		."		Inner Join NX_EVENT_MASTER As EM On EM.EM_IDX = EJ.EM_IDX"
		."			And EM.EM_DDATE is null"
		."	{$wh}"
		."	Order By EJ.EJ_IDX Asc"
		;
	$row = sql_query($sql);

	if (sql_num_rows($row) <= 0) {
		F_script("승인된 행사 신청자가 없습니다.", "history.back();");
	}
	else
	{
		header("Content-Type: text/csv; charset=utf-8");
		header("Content-Disposition: attachment; fileName=".iconv('utf-8', 'euc-kr', '참석자목록')."_" . date("ymd") . ".csv");
		header("Content-Description: PHP" . phpversion() . " Generated Data");
		echo("\xEF\xBB\xBF");

		
		# header
		echo F_CSVSTR("NO.");
		echo "," . F_CSVSTR("신청자명");
		echo "," . F_CSVSTR("접수번호");
		echo "," . F_CSVSTR("휴대폰");
		echo "," . F_CSVSTR("이메일");
		echo "," . F_CSVSTR("소속");
		echo "," . F_CSVSTR("신청시간");
		echo "," . F_CSVSTR("참석여부");

		for ($i = 0; $i < Count($fis); $i++)
		{
			$fi = $fis[$i];
			
			echo "," . F_CSVSTR($fi['FI_NAME']);
		}

		echo("\r\n");


		$s = 0;
		while ($rs1 = sql_fetch_array($row))
		{
			echo F_CSVSTR($s + 1);
			echo "," . F_CSVSTR(F_hsc($rs1['EJ_NAME']));
			echo "," . F_CSVSTR($rs1['EJ_JOIN_CODE']);
			echo "," . F_CSVSTR(F_hsc($rs1['EJ_MOBILE']));
			echo "," . F_CSVSTR(F_hsc($rs1['EJ_EMAIL']));
			echo "," . F_CSVSTR(F_hsc($rs1['EJ_ORG']));
			echo "," . F_CSVSTR($rs1['EJ_WDATE']);
			
			if ($rs1['EJ_JOIN_CHK1'] == 'Y' && $rs1['EJ_JOIN_CHK2'] == 'Y') {
				echo "," . F_CSVSTR("참석");
			}
			else {
				echo "," . F_CSVSTR("미참석");
			}


			for ($i = 0; $i < Count($fis); $i++)
			{
				$fi = $fis[$i];
				
				# 값이 화면에 표시 되었는지 여부
				$_bo = false;

				# 한줄텍스트/여러줄텍스트/날짜
				if (in_array($fi['FI_KIND'], array('A','B','E'))) {
					echo "," . F_CSVSTR($EL_VAL["{$rs1['mb_id']}_{$rs1['EM_IDX']}_{$rs1['EJ_IDX']}_{$fi['FI_IDX']}_0"]);
					$_bo = true;
				}
				# get : 단일/다중 선택 항목
				else if (in_array($fi['FI_KIND'], array('C','D')))
				{
					$sql = "Select FO_IDX"
						. "		From NX_EVENT_FORM_OPT"
						. "		Where FO_DDATE is null And FI_IDX = '" . mres($fi['FI_IDX']) . "'"
						. "		Order By FO_SEQ Asc, FO_IDX Desc"
						;
					$sdb1 = sql_query($sql);

					$_v = '';
					while ($srs1 = sql_fetch_array($sdb1))
					{
						$_t = $EL_VAL["{$rs1['mb_id']}_{$rs1['EM_IDX']}_{$rs1['EJ_IDX']}_{$fi['FI_IDX']}_{$srs1['FO_IDX']}"];
						if (!is_null($_t)) {
							if ($_v != '') $_v .= ", ";
							$_v .= $_t;
						}
					}

					if ($_v != '') {
						echo "," . F_CSVSTR($_v);
						$_bo = true;
					}

					unset($srs1, $sdb1, $_v);
				}
				# 주소
				else if ($fi['FI_KIND'] == 'F')
				{
					$_v = $FV_EXT1["{$rs1['mb_id']}_{$rs1['EM_IDX']}_{$rs1['EJ_IDX']}_{$fi['FI_IDX']}_0"]
						.' '.$FV_EXT2["{$rs1['mb_id']}_{$rs1['EM_IDX']}_{$rs1['EJ_IDX']}_{$fi['FI_IDX']}_0"]
						.' '.$EL_VAL["{$rs1['mb_id']}_{$rs1['EM_IDX']}_{$rs1['EJ_IDX']}_{$fi['FI_IDX']}_0"]
						;
					echo "," . F_CSVSTR($_v);
						
					$_bo = true;

					unset($_v);
				}

				# 화면에 표시되는 값이 없을 경우 '공백' 을 찍어줌
				if (!$_bo) {
					echo ",\" \"";
				}
			}

			echo("\r\n");

			$s++;
		}
	}
	exit;
?>
