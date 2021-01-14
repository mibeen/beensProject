<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if(!$is_admin) {
	alert('관리자만 가능합니다.');
}

if(!$ca_name) {
	alert('분류관리에서 문서분류를 등록해 주셔야 합니다.');
}

$is_chk = false;

$row = sql_fetch(" select count(*) as cnt from $write_table where ca_name = '$ca_name' ");

if($w == 'u') {
	if($row['cnt'] > 1) 
		$is_chk = true;
} else {
	if($row['cnt'] > 0) 
		$is_chk = true;
}

if($is_chk) {
	alert($ca_name.' 문서는 이미 등록된 문서입니다.');
}

$write_skin_url = $board_skin_url.'/write/'.$page_form;
$write_skin_path = $board_skin_path.'/write/'.$page_form;

$wr_10 = get_text($page_form.'|'.$page_file.'|'.$page_skin.'|'.$page_head.'|'.$page_color.'|'.$page_wide);

@include_once($write_skin_path.'/write_update.head.skin.php');

?>