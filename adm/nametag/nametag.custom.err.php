<?php
	# set : variables
	$sc_enc_sdate			= $_REQUEST["sc_enc_s_date"];
	$sc_enc_e_date			= $_REQUEST["sc_enc_e_date"];
	$stx				= $_REQUEST["stx"];
	$page				= $_REQUEST["page"];


	# re-define : variables
	$page				= (is_numeric($page)) ? (int)$page : 1;


	$phpTail = "sc_enc_s_date={$sc_enc_s_date}"
		. "&sc_enc_e_date=" . urlencode($sc_enc_e_date)
		. "&stx=" . urlencode($stx)
		. "&page=" . urlencode($page)
		. "&";
?>
