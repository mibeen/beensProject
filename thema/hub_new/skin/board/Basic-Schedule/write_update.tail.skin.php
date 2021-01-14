<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

@include_once($write_skin_path.'/write_update.tail.skin.php');

$year = substr($wr_1,0,4);
$year = preg_replace('/[^0-9_]/i', '', $year);
$qstr .= ($year) ? '&amp;year=' . urlencode($year) : '';

$month = substr($wr_1,4,2);
$month = preg_replace('/[^0-9_]/i', '', $month);
$qstr .= ($month) ? '&amp;month=' . urlencode($month) : '';

// 목록으로 이동하기
if($w == '' && isset($is_direct) && $is_direct) {
	if ($file_upload_msg)
		alert($file_upload_msg, G5_HTTP_BBS_URL.'/board.php?bo_table='.$bo_table);
	else
		goto_url(G5_HTTP_BBS_URL.'/board.php?bo_table='.$bo_table);
}

?>