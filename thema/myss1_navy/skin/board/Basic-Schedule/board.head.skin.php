<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// 날짜체크
$today = getdate();
$b_mon = $today['mon'];
$b_day = $today['mday'];
$b_year = $today['year'];
if($year < 1) { // 오늘의 달력 일때
  $month = $b_mon;
  $mday = $b_day;
  $year = $b_year;
}

$lastday=array(0,31,28,31,30,31,30,31,31,30,31,30,31);
if($year%4 == 0) $lastday[2] = 29;
$dayoftheweek = date("w", mktime(0,0,0,$month,1,$year));

$sca_qstr = ($qstr) ? '&amp;'.$qstr : '';

if($month == 1) { 
	$year_prev = $year - 1;
	$month_prev = 12;
	$year_next = $year;
	$month_next = $month + 1;
} else if($month == 12) { 
	$year_prev = $year; 
	$month_prev = $month - 1;
	$year_next = $year + 1;
	$month_next = 1;
} else {
	$year_prev = $year; 
	$month_prev = $month - 1;
	$year_next = $year;
	$month_next = $month + 1;
}

if (isset($year)) {
    $year = preg_replace('/[^0-9_]/i', '', $year);
	$qstr .= '&amp;year=' . urlencode($year);
}

if (isset($month)) {
    $month = preg_replace('/[^0-9_]/i', '', $month);
	$qstr .= '&amp;month=' . urlencode($month);
}

// 버튼컬러
$btn1 = (isset($boset['btn1']) && $boset['btn1']) ? $boset['btn1'] : 'black';
$btn2 = (isset($boset['btn2']) && $boset['btn2']) ? $boset['btn2'] : 'color';

// 보드상단출력
$is_bo_content_head = false;

?>