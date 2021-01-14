<?php
if (!defined('_GNUBOARD_')) exit; //개별 페이지 접근 불가

// 커스텀 페이저 사용
$wset['dot'] = 1;
$pager_custom = '#headline-thumb-right-pager';
$pager_thumb_w = 50;
$pager_thumb_h = 40;
$pager_left = "col-lg-9 col-md-8 col-sm-7";
$pager_right = "col-lg-3 col-md-4 col-sm-5";

// 높이설정 : 1개 높이는 ((썸네일 높이 + 여백 20 + 테두리 1) * 출력수) + 상단 테두리 1
$pager_h = (($pager_thumb_h + 20 + 1) * $wset['rows']) + 1; 
$wset['heights'] = $pager_h.'px,'.$pager_h.'px,'.$pager_h.'px,'.$pager_hp.'px,75%';
?>