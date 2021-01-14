<?php
include_once('./_common.php');
include_once(G5_EDITOR_LIB);

if($is_guest) {
	// alert('로그인 후 이용 가능 합니다.', './login.php?url='.urlencode(G5_BBS_URL.'/place_rental_req_list.php?pm_id='.$pm_id.'&ps_id='.$ps_id));
	alert('로그인 후 이용 가능 합니다.', G5_BBS_URL.'/place_rental_view.php?pm_id='.$pm_id);
}

$qaconfig = get_qa_config();

$g5['title'] = '대관 현황';
include_once('./place_rental_head.php');

$skin_file = $qa_skin_path.'/req_list.skin.php';

if(is_file($skin_file)) {
	if (isset($_REQUEST['sar']) && $_REQUEST['sar']) {
		$sar = preg_replace('/[^0-9]/', '', $sar);
		if ($sar)
			$qstr .= '&amp;sar=' . urlencode($sar);
	} else {
		$sar = '';
	}

	if (isset($_REQUEST['sal']) && $_REQUEST['sal'])  { // search order (검색 배열)
		$sal = preg_match("/^(1|2)$/i", $sal) ? $sal : '';
		if ($sal)
			$qstr .= '&amp;sal=' . urlencode($sal);
	} else {
		$sal = 1;
	}

	if (isset($_REQUEST['sco']) && $_REQUEST['sco'])  { // search order (검색 배열)
		$sco = preg_match("/^(1|2|3)$/i", $sco) ? $sco : '';
		if ($sco)
			$qstr .= '&amp;sco=' . urlencode($sco);
	} else {
		$sco = 1;
	}

	if (isset($_REQUEST['year']) && $_REQUEST['year']) {
		$year = preg_replace('/[^0-9]/', '', $year);
	} else {
		$year = date('Y');
	}

	if (isset($_REQUEST['month']) && $_REQUEST['month']) {
		$month = preg_replace('/[^0-9]/', '', $month);
	} else {
		$month = date('m');
	}

	// get last day
	$last_today = date('Y-m-d', strtotime($year . '-' . $month . '-1'));
	$last_day = date("t", strtotime($last_today));

	$prev_year = $year;
	$next_year = $year;
	$next_month = $month + 1;
	if($next_month >= 13) {
		$next_month = 1;
		$next_year = $year + 1;
	}
	$prev_month = $month - 1;
	if($prev_month <= 0) {
		$prev_month = 12;
		$prev_year = $year - 1;
	}

	$sql = " select * from {$g5['place_rental_sub_table']} where PS_IDX = '{$ps_id}' ";
	$view = sql_fetch($sql);

	if(!$view['PS_IDX'])
		alert('게시글이 존재하지 않습니다.\\n삭제되었거나 자신의 글이 아닌 경우입니다.');


	// place name
	$sql = " select PM_IDX, PM_NAME, PA_IDX from {$g5['place_rental_master_table']} where PM_IDX = '{$pm_id}'";
	$place_result_temp = sql_fetch($sql);

	if(!$place_result_temp['PM_IDX'])
		alert('게시글이 존재하지 않습니다.\\n삭제되었거나 자신의 글이 아닌 경우입니다.');


	$view['PM_NAME'] = $place_result_temp['PM_NAME'];
	$view['PA_IDX'] = $place_result_temp['PA_IDX'];

	$area_result = array();
	$sql = " select * from {$g5['place_rental_area_table']} where PA_DDATE is null";
	$area_result_temp = sql_query($sql);
	for ($i=0; $row=sql_fetch_array($area_result_temp); $i++) {
		$area_result[] = $row;
	}
	$j_area = count($area_result);


	$subject_len = G5_IS_MOBILE ? $qaconfig['qa_mobile_subject_len'] : $qaconfig['qa_subject_len'];

	$view['PS_NAME'] = get_text($view['PS_NAME']);
	$view['PS_INFO'] = conv_content($view['PS_INFO'], $view['PS_INFO']);
	if($view['PS_GUBUN'] == 'A') {
		$view['PA_GUBUN_TITLE'] = '강의실';
	}
	elseif($view['PS_GUBUN'] == 'B') {
		$view['PA_GUBUN_TITLE'] = '숙소';
	}

	// area name
	for ($ii = 0; $ii < $j_area; $ii++) { 
		$row_area = $area_result[$ii];
		if($row_area['PA_IDX'] == $view['PA_IDX']) {
			$view['PA_NAME'] = $row_area['PA_NAME'];
			break;
		}
	}

	// list image
	$bo_table = 'place_rental_sub';
	$sql = " select * from {$g5['board_file_table']} where bo_table = '{$bo_table}' and wr_id = '{$ps_id}'";
	$place_file = sql_fetch($sql);

	if(isset($place_file) && $place_file['bf_file'] != '') {
		$himg = G5_DATA_PATH."/file/".$bo_table."/".$place_file['bf_file'];
	}

	$view['bf_file'] = $place_file['bf_file'];
	$view['bf_source'] = $place_file['bf_source'];


	for ($i = 1; $i <= $last_day; $i++) {
		$sql = "select * from {$g5['place_rental_req_table']} where (1) and PS_IDX = '{$view['PS_IDX']}' and year(PR_SDATE) = '{$year}' and month(PR_SDATE) = '{$month}' and day(PR_SDATE) = '{$i}' and PR_STATUS <> 'D' and PR_DDATE is null order by PR_SDATE";

		$result_req = sql_query($sql);
		$day_list[] = $result_req;
	}

	$prev_month_year = ($prev_month == 12) ? $year - 1 : $year; // 지난달의 연도
	if ($view['PS_GUBUN'] == 'B') {
		$sql = 
			"Select day(PR_EDATE) As edate"
			." From PLACE_RENTAL_REQ"
			." Where PR_DDATE is null"
			."		And PR_STATUS in ('A', 'B')"
			."		And PS_IDX = '{$view['PS_IDX']}'"
			."		And year(PR_SDATE) = '{$prev_month_year}'"
			."		And month(PR_SDATE) = '{$prev_month}'"
			." Order by PR_SDATE Desc"
			." Limit 0, 1"
			;

		$result_req = sql_fetch($sql);
		$last_req_edate = $result_req['edate'];
	}

	// make next month req check array for gubun b
	/*
	$next_month = $month + 1;
	$next_year = $year;
	if($next_month > 12) { 
		$next_month = 1;
		$next_year++;
	}
	for ($i = 1; $i <= 9; $i++) {
		$sql = "select count(*) from {$g5['place_rental_req_table']} where (1) and PS_IDX = {$view['PS_IDX']} and year(PR_SDATE) = {$next_year} and month(PR_SDATE) = {$next_month} and day(PR_SDATE) = {$i} and (PR_STATUS = 'A' or PR_STATUS = 'B') and PR_DDATE is null order by PR_SDATE";

		$result_req = sql_fetch($sql);

		if($result_req['count(*)'] == 0) {
			$next_month_day_list[$i] = 1;
		}
		else {
			$next_month_day_list[$i] = 0;
		}
	}
	*/

	if($qstr == "") { $qstr = "?"; }
	
	$update_href = '';
	$delete_href = '';
	$write_href = G5_BBS_URL.'/qawrite.php';
	$rewrite_href = G5_BBS_URL.'/qawrite.php?w=r&amp;ps_id='.$view['ps_id'];
	$list_href = G5_BBS_URL.'/place_rental_view.php'.preg_replace('/^&amp;/', '?', $qstr).'&amp;pm_id='.$pm_id;
	$detail_href = G5_BBS_URL.'/place_rental_sub_view.php'.preg_replace('/^&amp;/', '?', $qstr).'&amp;pm_id='.$pm_id.'&amp;ps_id='.$ps_id;

	/*
	if($view['qa_type']) {
		if($is_admin)
			$update_href = G5_BBS_URL.'/qawrite.php?w=u&amp;ps_id='.$view['ps_id'].$qstr;
	} else {
		if($view['qa_status'] == 0)
			$update_href = G5_BBS_URL.'/qawrite.php?w=u&amp;ps_id='.$view['ps_id'].$qstr;
	}
	*/
	if(($view['qa_type'] && $is_admin) || (!$view['qa_type'] && $view['qa_status'] == 0)) {
		$update_href = G5_BBS_URL.'/qawrite.php?w=u&amp;ps_id='.$view['ps_id'].$qstr;
		$delete_href = G5_BBS_URL.'/qadelete.php?ps_id='.$view['ps_id'].$qstr;
	}

	// 질문글이고 등록된 답변이 있다면
	$answer = array();
	$answer_update_href = '';
	$answer_delete_href = '';
	if(!$view['qa_type'] && $view['qa_status']) {
		$sql = " select *
					from {$g5['qa_content_table']}
					where qa_type = '1'
					  and qa_parent = '{$view['ps_id']}' ";
		$answer = sql_fetch($sql);

		if($is_admin) {
			$answer_update_href = G5_BBS_URL.'/qawrite.php?w=u&amp;ps_id='.$answer['ps_id'].$qstr;
			$answer_delete_href = G5_BBS_URL.'/qadelete.php?ps_id='.$answer['ps_id'].$qstr;
		}
	}

	$stx = get_text(stripslashes($stx));

	$is_dhtml_editor = false;
	// 모바일에서는 DHTML 에디터 사용불가
	if ($config['cf_editor'] && $qaconfig['qa_use_editor'] && !G5_IS_MOBILE) {
		$is_dhtml_editor = true;
	}
	$editor_html = editor_html('qa_content', $content, $is_dhtml_editor);
	$editor_js = '';
	$editor_js .= get_editor_js('qa_content', $is_dhtml_editor);
	$editor_js .= chk_editor_js('qa_content', $is_dhtml_editor);

	$ss_name = 'ss_qa_view_'.$ps_id;
	if(!get_session($ss_name))
		set_session($ss_name, TRUE);


	include_once($skin_file);
} else {
	echo '<div>'.str_replace(G5_PATH.'/', '', $skin_file).'이 존재하지 않습니다.</div>';
}

# 메뉴 표시에 사용할 변수 
$_gr_id = 'gseek';  // 게시판 그룹 ID - 현재 메뉴표시에 사용
$pid = ($pid) ? $pid : 'rentlist';   // Page ID

include "../page/inc.page.menu.php";

include_once('./qatail.php');
?>