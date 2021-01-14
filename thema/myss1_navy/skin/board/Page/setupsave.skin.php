<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// 분류정리
$ca_list = '';
$ca_arr = explode("\n", $bo_category_list);
$ca_cnt = count($ca_arr);
for($n=0; $n < $ca_cnt; $n++) {

	$cate = trim($ca_arr[$n]);

	if(!$cate) continue;

	if($n > 0) $ca_list .= '|';

	$ca_list .= $cate;
}

// 보드설정값
$bo_set = " bo_category_list = '".addslashes(get_text($ca_list))."', bo_use_category = '1' ";

sql_query(" update {$g5['board_table']} set $bo_set where bo_table = '{$bo_table}' ", false);

?>