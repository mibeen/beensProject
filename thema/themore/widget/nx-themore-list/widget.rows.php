<?php
if (!defined('_GNUBOARD_')) exit; //개별 페이지 접근 불가

// 추출하기
$list = apms_board_rows($wset);
$list_cnt = count($list);

// 아이콘
$icon = (isset($wset['icon']) && $wset['icon']) ? '<span class="lightgray">'.apms_fa($wset['icon']).'</span>' : '';
$is_ticon = (isset($wset['ticon']) && $wset['ticon']) ? true : false;

// 랭킹
$rank = apms_rank_offset($wset['rows'], $wset['page']); 

// 날짜
$is_date = (isset($wset['date']) && $wset['date']) ? true : false;
$is_dtype = (isset($wset['dtype']) && $wset['dtype']) ? $wset['dtype'] : 'm.d';
$is_dtxt = (isset($wset['dtxt']) && $wset['dtxt']) ? true : false;

// 새글
$is_new = (isset($wset['new']) && $wset['new']) ? $wset['new'] : 'red'; 

// 댓글
$is_comment = (isset($wset['comment']) && $wset['comment']) ? true : false;

//제목 
$is_title = (isset($wset['title']) && $wset['title'] == "1") ? true : false;

// 강조글
$bold = array();
$strong = explode(",", $wset['strong']);
$is_strong = count($strong);
for($i=0; $i < $is_strong; $i++) {

	list($n, $bc) = explode("|", $strong[$i]);

	if(!$n) continue;

	$n = $n - 1;
	$bold[$n]['num'] = true;
	$bold[$n]['color'] = ($bc) ? ' class="'.$bc.'"' : '';
}

?>

<div class="attend-news-wrap">
	<div class="col-md-6 col-lg-6 col-sm-6 col-xs-6 blue"><a href="#">교육기부</a></div>
	<div class="col-md-6 col-lg-6 col-sm-6 col-xs-6 green"><a href="#">인증제</a></div>
	<div class="col-md-6 col-lg-6 col-sm-6 col-xs-6 orange"><a href="#">우리동네<br>학습공간</a></div>
	<div class="col-md-6 col-lg-6 col-sm-6 col-xs-6 red"><a href="#">365-24<br>두루누리</a></div>
	<div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 purple"><a href="#">지식 GSEEK</a></div>
	<div class="clearfix"></div>
</div>
