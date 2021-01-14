<?php
	include_once("./_common.php");


	# set : variables
	$url = $_POST['url'];
	$C_IDX = $_POST['C_IDX'];
	$PARENT_IDX = $_POST['PARENT_IDX'];
	$C_TBNAME = $_POST['C_TBNAME'];
	$C_TBPK = $_POST['C_TBPK'];
	$wr_content = $_POST['wr_content'];
	$wr_secret = $_POST['wr_secret'];


	# re-define
	$C_IDX = (is_numeric($C_IDX)) ? (int)$C_IDX : "";
	$PARENT_IDX = (is_numeric($PARENT_IDX)) ? (int)$PARENT_IDX : "";
	$C_TBNAME = trim($C_TBNAME);
	$C_TBPK = (is_numeric($C_TBPK)) ? (int)$C_TBPK : "";
	$C_CONTENT = trim($wr_content);
	$C_SECRET_YN = ($wr_secret == "secret") ? "Y" : "N";


	if($C_IDX == "") {
		if($PARENT_IDX == "") {
			$GROUP_IDX = "";
			$C_REPLY = "";
		} else {
			$sql = "Select C.GROUP_IDX, C.C_REPLY"
				."	From NX_COMMENT As C"
				."	Where C.C_DDATE is null And C.C_IDX = '" . mres($PARENT_IDX) . "'"
				;
			$row = sql_query($sql);
			if (sql_num_rows($row) <= 0) {
				alert("잘못 들어오셨습니다.");
			}
			$rs1 = sql_fetch_array($row);
			$GROUP_IDX = $rs1['GROUP_IDX'];
			$C_REPLY = $rs1['C_REPLY'];


			if(strlen($C_REPLY) >= 5) {
	            alert("더 이상 답변하실 수 없습니다.\\n\\n답변은 5단계 까지만 가능합니다.");
			}


			$sql = "Select Min(C.C_REPLY) As C_REPLY"
				."	From NX_COMMENT As C"
				."	Where C.GROUP_IDX = '" . mres($GROUP_IDX) . "' And C.C_REPLY like '" . mres($C_REPLY) . "_'"
				;
			$row = sql_query($sql);
			if (sql_num_rows($row) <= 0) {
				alert("잘못 들어오셨습니다.");
			}
			$rs1 = sql_fetch_array($row);
			if(is_null($rs1['C_REPLY'])) {
				$C_REPLY .= "Z";
			} else {
				if(substr($rs1['C_REPLY'], -1) == "A") {
		            alert("더 이상 답변하실 수 없습니다.\\n\\n답변은 26개 까지만 가능합니다.");
				}
				$C_REPLY .= chr(ord(substr($rs1['C_REPLY'], -1)) - 1);
			}
		}


		# insert : NX_COMMENT
		$sql = "Insert Into NX_COMMENT("
			."GROUP_IDX"
			.", mb_id"
			.", C_TBNAME"
			.", C_TBPK"
			.", C_REPLY"
			.", C_CONTENT"
			.", C_SECRET_YN"
			.", C_PW"
			.", C_WDATE"
			.", C_WIP"
			.") values("
			."'" . mres($GROUP_IDX) . "'"
			.", '" . mres($member['mb_id']) . "'"
			.", '" . mres($C_TBNAME) . "'"
			.", '" . mres($C_TBPK) . "'"
			.", '" . mres($C_REPLY) . "'"
			.", '" . mres($C_CONTENT) . "'"
			.", '" . mres($C_SECRET_YN) . "'"
			.", '" . mres($member['mb_password']) . "'"
			.", now()"
			.", inet_aton('" . mres($_SERVER['REMOTE_ADDR']) . "')"
			.")"
			;
		sql_query($sql);

		$C_IDX = sql_insert_id();


		if($GROUP_IDX == "") {
			# update : NX_COMMENT
			$sql = "Update NX_COMMENT Set"
				." GROUP_IDX = '" . mres($C_IDX) . "'"
				." Where C_IDX = '" . mres($C_IDX) . "'"
				." Limit 1"
				;
			sql_query($sql);
		}
	} else {
		$sql = "Select C.mb_id"
			."	From NX_COMMENT As C"
			."	Where C.C_DDATE is null And C.C_IDX = '" . mres($C_IDX) . "'"
			;
		$row = sql_query($sql);
		if (sql_num_rows($row) <= 0) {
			alert("잘못 들어오셨습니다.");
		}
		$rs1 = sql_fetch_array($row);
		$mb_id = $rs1['mb_id'];


		if(!$is_admin && $member['mb_id'] != $rs1['mb_id']) {
			alert("본인이 쓴 글만 수정할 수 있습니다.");
		}


		# update : NX_COMMENT
		$sql = "Update NX_COMMENT Set"
			." C_CONTENT = '" . mres($C_CONTENT) . "'"
			.", C_SECRET_YN = '" . mres($C_SECRET_YN) . "'"
			.", C_MDATE = now()"
			.", C_MIP = inet_aton('" . mres($_SERVER['REMOTE_ADDR']) . "')"
			." Where C_IDX = '" . mres($C_IDX) . "'"
			." Limit 1"
			;
		sql_query($sql);
	}


	goto_url($url.'&amp;#c_'.$C_IDX);
?>
