<?php
	$SC_OP_PARENT_IDX = ($_POST["SC_OP_PARENT_IDX"] != '') ? $_POST["SC_OP_PARENT_IDX"] : $_GET["SC_OP_PARENT_IDX"];
	$SC_WORD = ($_POST["SC_WORD"] != '') ? $_POST["SC_WORD"] : $_GET["SC_WORD"];

	$phpTail = "SC_OP_PARENT_IDX=" . urlencode($SC_OP_PARENT_IDX)
		."&SC_WORD=" . urlencode($SC_WORD)
		."&"
		;
?>