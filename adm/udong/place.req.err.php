<?php
	# 시설관리자 권한 부여
	if ($lp_idx != "" && $member['mb_level'] == 4) {
		$sql = "Select Count(*) As cnt"
			. " From local_place As lp"
			. " Where lp.lp_idx = '" . mres($lp_idx) . "'"
			. " 	And lp.mb_id = '" . mres($member['mb_id']) . "'"
			;
		$db_epm = sql_fetch($sql);

		if ($db_epm['cnt'] > 0) {
			$auth['970100'] = 'r,w,d';
		}
	}


	$qstr = "";
	if (isset($sc_lr_sdate)) $qstr .= '$sc_lr_sdate=' . $sc_lr_sdate;
	if (isset($sc_lr_edate)) $qstr .= '$sc_lr_edate=' . $sc_lr_edate;
	if (isset($splace)) $qstr .= '&splace=' . $splace;
	if (isset($suy)) $qstr .= '&suy=' . $suy;
	if (isset($sfl)) $qstr .= '&sfl=' . $sfl;
	if (isset($stx)) $qstr .= '&stx=' . $stx;
	if (isset($page)) $qstr .= '&page=' . $page;
	if (isset($popup)) $qstr .= '&popup=' . $popup;

	if ($qstr != '') {
		if (substr($qstr, 0, 1) == '&') $qstr = substr($qstr, 1);
		$qstr .= '&';
	}
?>