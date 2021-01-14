<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// 전체목록보이기 무조건 사용 - 목록에 내용표시
$is_show_list = true;

// 목록갯수는 1개로 고정
$board['bo_page_rows'] = 1;
$board['bo_mobile_page_rows'] = 1;

// 목록에서 글내용처리
$is_list_page = false;
$is_exist = true;
if(!isset($wr_id) || !$wr_id) {

	$is_list_page = true;

	if(isset($sca) && $sca) { //분류가 있으면...
		$write = sql_fetch(" select * from $write_table where ca_name = '{$sca}' and wr_is_comment = '0' ");
	} else { //분류가 없으면..
		// 요약글 체크
		$write = sql_fetch(" select * from $write_table where ca_name = '요약' and wr_is_comment = '0' ");
		if(!$write['wr_id']) {
			list($sca) = explode('|', $board['bo_category_list']);
			if($sca) {
				$write = sql_fetch(" select * from $write_table where ca_name = '{$sca}' and wr_is_comment = '0' ");
			} else {
				$write = sql_fetch(" select * from $write_table where wr_is_comment = '0' ");
			}
		}
	}
	
	$wr_id = $write['wr_id'];

	// SEO, Hit, Title
	if($wr_id) {
		$is_seometa = 'view';
		$ss_name = 'ss_view_'.$bo_table.'_'.$wr_id;
		if (!get_session($ss_name)) {
			sql_query(" update {$write_table} set wr_hit = wr_hit + 1 where wr_id = '{$wr_id}' ", false); //글 조회수
			sql_query(" update {$g5['board_new_table']} set as_hit = as_hit + 1 where bo_table = '{$bo_table}' and wr_id = '{$wr_id}' ", false); //새글 조회수
			set_session($ss_name, TRUE);
		}
		$g5['title'] = strip_tags(conv_subject($write['wr_subject'], 255))." > ".$board['bo_subject'];
	} else {
		$is_exist = false;
		$write['wr_ip'] = gethostbyname(); //서버IP
	}
}

// 개별 스킨설정
list($page_form, $page_file, $page_skin, $header_skin, $header_color, $page_wide) = explode("|", get_text($write['wr_10']));

// 와이드 레이아웃
if($page_wide) {
	$is_wide_layout = true;
}

// Nav 설정
$page_nav1 = $group['gr_subject'];
$page_nav2 = $board['bo_subject'];
if($write['ca_name'] == "요약") {
	$page_name = $board['bo_subject'];
	$page_nav3 = '';
} else {
	$page_name = $write['ca_name'];
	$page_nav3 = $write['ca_name'];
}

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css" media="screen">', 0);

?>