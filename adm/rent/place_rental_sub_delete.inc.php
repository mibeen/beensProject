<?php
// board_delete.php , boardgroup_delete.php 에서 include 하는 파일

if (!defined('_GNUBOARD_')) exit;
if (!defined('_PLACE_SUB_DELETE_')) exit; // 개별 페이지 접근 불가

// $tmp_bo_table 에는 $bo_table 값을 넘겨주어야 함
if (!$tmp_PS_IDX) { return; }

// 게시판 1개는 삭제 불가 (게시판 복사를 위해서)
//$row = sql_fetch(" select count(*) as cnt from $g5['board_table'] ");
//if ($row['cnt'] <= 1) { return; }

// 게시판 삭제
sql_query(" delete from {$g5['place_rental_sub_table']} where PS_IDX = '{$tmp_PS_IDX}' ");
?>