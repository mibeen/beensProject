<?php
	# set : variables
	$sc_ep_s_date			= $_REQUEST["sc_ep_s_date"];
	$sc_ep_e_date			= $_REQUEST["sc_ep_e_date"];
	$ord				= $_REQUEST["ord"];
	$stx				= $_REQUEST["stx"];
	$page				= $_REQUEST["page"];


	# re-define : variables
	$ord				= (is_numeric($ord)) ? (int)$ord : "";
	$page				= (is_numeric($page)) ? (int)$page : 1;


	$phpTail = "sc_ep_s_date={$sc_ep_s_date}"
		. "&sc_ep_e_date=" . urlencode($sc_ep_e_date)
		. "&ord=" . urlencode($ord)
		. "&stx=" . urlencode($stx)
		. "&page=" . urlencode($page)
		. "&";
?>
