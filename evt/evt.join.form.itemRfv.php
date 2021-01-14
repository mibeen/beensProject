<?php
	if (!defined('_GNUBOARD_')) exit;
	if ((int)$_POST['EM_IDX'] <= 0) exit;
	if (is_null($member['mb_id'])) exit;


	#---------- FORM BUILDER 사용 처리 ----------#
	$sql = "Select FI.*"
		. "		From NX_EVENT_FORM_ITEM As FI"
		. "		Where FI.FI_DDATE is null And FI.FI_USE_YN = 'Y' And FI.EM_IDX = '" . mres($EM_IDX) . "'"
		. "		Order By FI.FI_SEQ Asc"
		;
	$db1 = sql_query($sql);


	# case : FORM BUILDER 사용
	$FB = array();
	if (sql_num_rows($db1) > 0)
	{
		$s = 0;
		while ($rs1 = sql_fetch_array($db1))
		{
			$FI_IDX = $rs1['FI_IDX'];
			$FI_KIND = $rs1['FI_KIND'];
			$FI_NAME = $rs1['FI_NAME'];
			$FI_REQ_YN = $rs1['FI_REQ_YN'];
			$FI_TYPE = $rs1['FI_TYPE'];
			$FI_EXT_TYPE = $rs1['FI_EXT_TYPE'];
			$FI_MAX_SIZE = $rs1['FI_MAX_SIZE'];

			# 객체의 ID
			$_ID_ = "IPT_{$FI_IDX}";

			if($FI_KIND == "A" && $FI_TYPE == "TEL") {
				$_VAL_ = $_POST[$_ID_ . "1"] . "-" . $_POST[$_ID_ . "2"] . "-" . $_POST[$_ID_ . "3"];
				if($_VAL_ == "--") $_VAL_ = "";
			}
			else if($FI_KIND == "E" && $FI_TYPE == "DATE") {
				$_VAL_ = $_POST[$_ID_ . "_YY"] . "-" . $_POST[$_ID_ . "_MM"] . "-" . $_POST[$_ID_ . "_DD"];
			}
			else if($FI_KIND == "E" && $FI_TYPE == "DATETIME") {
				$_VAL_ = $_POST[$_ID_ . "_YY"] . "-" . $_POST[$_ID_ . "_MM"] . "-" . $_POST[$_ID_ . "_DD"] . " " . $_POST[$_ID_ . "_HH"] . ":" . $_POST[$_ID_ . "_II"];
			}
			else {
				$_VAL_ = $_POST[$_ID_];
			}

			if($FI_KIND == "F") {
				$_EXT1_ = $_POST[$_ID_ . "_EXT1"];
				$_EXT2_ = $_POST[$_ID_ . "_EXT2"];
			} else {
				$_EXT1_ = "";
				$_EXT2_ = "";
			}

			#---------- 한줄텍스트/여러줄텍스트/주소 ----------#
			if ($FI_KIND == "A" || $FI_KIND == "B" || $FI_KIND == "F") {
				$FB[] = array(
					"FI_IDX" => $FI_IDX
					, "FI_KIND" => $FI_KIND
					, "FI_TYPE" => $FI_TYPE
					, "FI_EXT_TYPE" => ""
					, "FI_MAX_SIZE" => (int)'0'
					, "FO_IDX" => (int)0
					, "FV_VAL" => $_VAL_
					, "FV_EXT1" => $_EXT1_
					, "FV_EXT2" => $_EXT2_
				);


				# chk : 필수 항목
				if ($FI_REQ_YN == 'Y') {
					if ($_VAL_ == "" || ($FI_KIND == "F" && ($_EXT1_ == "" || $_EXT2_ == ""))) {
						$ret = array(
							"success" => (boolean)false, 
							"msg"=>F_hsc($FI_NAME)." 정보가 입력되지 않았습니다."
						);
						echo_json($ret);
					}
				}
			}

			#---------- 단일/다중 선택 처리 ----------#
			else if ($FI_KIND == 'C' || $FI_KIND == 'D')
			{
				# get : 단일/다중 선택 항목
				$sql = "Select *"
					. "		From NX_EVENT_FORM_OPT"
					. "		Where FO_DDATE is null And FI_IDX = '" . mres($FI_IDX) . "'"
					. "		Order By FO_IDX Asc"
					;
				$sdb1 = sql_query($sql);

				$CHKBO = (boolean)false;
				while ($srs1 = sql_fetch_array($sdb1))
				{
					$FO_IDX = $srs1['FO_IDX'];

					# 객체의 ID
					$__ID__ = ($FI_KIND == 'C') ? "IPT_{$FI_IDX}" : "IPT_FO_{$FO_IDX}";
					$__VAL__ = $_POST[$__ID__];

					# case : 단일 선택일 경우 일치할 경우 입력으로 인정
					if ($FI_KIND == 'C' && $__VAL__ == $FO_IDX) {
						$FB[] = array(
							"FI_IDX" => $FI_IDX
							, "FI_KIND" => $FI_KIND
							, "FI_TYPE" => $FI_TYPE
							, "FI_EXT_TYPE" => ""
							, "FI_MAX_SIZE" => (int)'0'
							, "FO_IDX" => (int)$__VAL__
							, "FV_VAL" => ""
							, "FV_EXT1" => ""
							, "FV_EXT2" => ""
						);

						$CHKBO = (boolean)true;
					}
					# case : 다중 선택일 경우 값이 있을 경우 입력으로 인정
					else if ($FI_KIND == 'D' && $__VAL__ != "") {
						$FB[] = array(
							"FI_IDX" => $FI_IDX
							, "FI_KIND" => $FI_KIND
							, "FI_TYPE" => $FI_TYPE
							, "FI_EXT_TYPE" => ""
							, "FI_MAX_SIZE" => (int)'0'
							, "FO_IDX" => (int)$FO_IDX
							, "FV_VAL" => ""
							, "FV_EXT1" => ""
							, "FV_EXT2" => ""
						);

						$CHKBO = (boolean)true;
					}
				}
				unset($srs1, $sdb1);


				# chk : 필수 항목
				if ($FI_REQ_YN == 'Y' && !$CHKBO) {
					if ($__VAL__ == "") {
						$ret = array(
							"success" => (boolean)false, 
							"msg"=>F_hsc($FI_NAME)." 정보가 입력되지 않았습니다."
						);
						echo_json($ret);
					}
				}
			}

			#---------- 날짜 ----------#
			else if ($FI_KIND == 'E') {
				$FB[] = array(
					"FI_IDX" => $FI_IDX
					, "FI_KIND" => $FI_KIND
					, "FI_TYPE" => $FI_TYPE
					, "FI_EXT_TYPE" => ""
					, "FI_MAX_SIZE" => (int)'0'
					, "FO_IDX" => (int)0
					, "FV_VAL" => $_VAL_
					, "FV_EXT1" => ""
					, "FV_EXT2" => ""
				);


				# re-define
				$_VAL_ = (F_chkDate(substr($_VAL_, 0, 10))) ? $_VAL_ : "";


				# chk : 필수 항목
				if ($FI_REQ_YN == 'Y')
				{
					# chk : 날짜형식
					if ($_VAL_ == "") {
						$ret = array(
							"success" => (boolean)false, 
							"msg"=>F_hsc($FI_NAME)." 정보가 입력되지 않았습니다."
						);
						echo_json($ret);
					}
				}
			}

			#---------- 파일 ----------#
			else if ($FI_KIND == 'G') {
				$FB[] = array(
					"FI_IDX" => $FI_IDX
					, "FI_KIND" => $FI_KIND
					, "FI_TYPE" => $FI_TYPE
					, "FI_EXT_TYPE" => $FI_EXT_TYPE
					, "FI_MAX_SIZE" => (int)$FI_MAX_SIZE
					, "FO_IDX" => (int)0
					, "FV_VAL" => ""
					, "FV_EXT1" => ""
					, "FV_EXT2" => ""
				);

				# chk : 필수 항목
				if ($FI_REQ_YN == 'Y')
				{
					$CHKBO = (boolean)false;
					for ($k = 0; $k < Count($_FILES["IPT_{$FI_IDX}"]['name']); $k++)
					{
						if ($_FILES["IPT_{$FI_IDX}"]['name'][$k] == '') continue;
						$CHKBO = (boolean)true;
					}

					if (!$CHKBO) {
						$ret = array(
							"success" => (boolean)false, 
							"msg"=>F_hsc($FI_NAME)." 입력 영역에 파일을 첨부해주세요."
						);
						echo_json($ret);
					}
				}
			}

			$s++;
		}
		unset($rs1);
	}
	unset($db1);
	#---------- FORM BUILDER 사용 처리 ----------#
?>