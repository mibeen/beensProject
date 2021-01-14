<?php
	$qstr = "";
	if (isset($sc_lr_sdate)) $qstr .= '$sc_lr_sdate=' . $sc_lr_sdate;
	if (isset($sc_lr_edate)) $qstr .= '$sc_lr_edate=' . $sc_lr_edate;
	if (isset($splace)) $qstr .= '&splace=' . $splace;
	if (isset($suy)) $qstr .= '&suy=' . $suy;
	if (isset($stx)) $qstr .= '&stx=' . $stx;
	if (isset($page)) $qstr .= '&page=' . $page;

	if ($qstr != '') {
		if (substr($qstr, 0, 1) == '&') $qstr = substr($qstr, 1);
		$qstr .= '&';
	}
?>