<?php
	$qstr = "";
	if (isset($splace)) $qstr .= '&splace=' . $splace;
	if (isset($suy)) $qstr .= '&suy=' . $suy;
	if (isset($sfl)) $qstr .= '&sfl=' . $sfl;
	if (isset($stx)) $qstr .= '&stx=' . $stx;
	if (isset($page)) $qstr .= '&page=' . $page;

	if ($qstr != '') {
		if (substr($qstr, 0, 1) == '&') $qstr = substr($qstr, 1);
		$qstr .= '&';
	}
?>