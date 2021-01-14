<?php
include_once('./_common.php');

if(!$is_admin) {
	alert('관리자만 가능합니다.');
}

$temp = array();

if($_POST['btn_submit'] == '일괄저장') {
	$temp = $_POST['chk_id'];
	$count = count($temp);
	for ($i=0; $i < $count; $i++) {

		$n = $temp[$i];

		$wr_10 = addslashes(get_text($page_form[$n].'|'.$page_file[$n].'|'.$page_skin[$n].'|'.$page_head[$n].'|'.$page_color[$n].'|'.$page_wide[$n]));

		sql_query(" update $write_table set wr_10 = '{$wr_10}' where wr_id = '{$n}' ", false);
	}
} else {
    alert('올바른 방법으로 이용해 주세요.');
}

goto_url(G5_BBS_URL.'/board.php?bo_table='.$bo_table.'&amp;page='.$page.$qstr);

?>