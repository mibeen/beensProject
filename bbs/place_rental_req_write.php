<?php
include_once('./_common.php');
include_once(G5_EDITOR_LIB);

if($is_guest) {
	alert_script("로그인 후 이용 가능 합니다.", "parent.$('#viewModal').modal('hide');");
}

$qaconfig = get_qa_config();

$g5['title'] = '대관 현황';
include_once('./place_rental_head.sub.php');

$skin_file = $qa_skin_path.'/req_write.skin.php';

if(is_file($skin_file)) {
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

	if (isset($_REQUEST['day']) && $_REQUEST['day']) {
		$day = preg_replace('/[^0-9]/', '', $day);
	} else {
		$day = date('d');
	}


	#----- get : 수정일 경우 해당 글의 정보
	# 신청/보류 + 입실일이 오늘 전 일 경우만 수정 페이지 접근 가능
	if ((int)$pr_id > 0) {
		$req_view = sql_fetch(
			"Select *"
			."	From PLACE_RENTAL_REQ"
			."	Where PR_DDATE is null"
			."		And PR_STATUS in ('A','C')"
			."		And PS_IDX = '{$ps_id}'"
			."		And PR_IDX = '{$pr_id}'"
			."		And DATE_FORMAT(PR_SDATE, '%Y-%m-%d') >= DATE_FORMAT(now(), '%Y-%m-%d')"
			."	Order By PR_IDX Desc"
			."	Limit 1"
		);
		if (is_null($req_view['PR_IDX'])) {
			unset($req_view);
			alert_script("잘못된 접근 입니다.", "parent.$('#viewModal').modal('hide');");
		}

		# 기존 정보를 기반으로 할 경우 날짜 정보도 해당 기준으로 변경
		$_t = explode('-', date('Y-m-d', strtotime($req_view['PR_SDATE'])));
		$year = $_t[0];
		$month = $_t[1];
		$day = $_t[2];

		unset($_t);
	}
	else {
		$req_view = array();
	}
	#####


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


	#----- get : 대관 main record
	$view = sql_fetch("select * from {$g5['place_rental_sub_table']} where PS_IDX = '{$ps_id}'");

	if(!$view['PS_IDX']) {
		alert('게시글이 존재하지 않습니다.\\n삭제되었거나 자신의 글이 아닌 경우입니다.');
	}


	#----- get : 장소 정보	
	$place_result_temp = sql_fetch("select PM_IDX, PM_NAME, PA_IDX from {$g5['place_rental_master_table']} where PM_IDX = '{$pm_id}'");

	if(!$place_result_temp['PM_IDX']) {
		alert('게시글이 존재하지 않습니다.\\n삭제되었거나 자신의 글이 아닌 경우입니다.');
	}


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


	for ($i = 1; $i <= $last_day; $i++) {
		$sql = "select * from {$g5['place_rental_req_table']} where (1) and PS_IDX = '{$view['PS_IDX']}' and year(PR_SDATE) = '{$year}' and month(PR_SDATE) = '{$month}' and day(PR_SDATE) = '{$i}' and PR_STATUS <> 'D' and PR_DDATE is null order by PR_SDATE";

		$result_req = sql_query($sql);
		$day_list[] = $result_req;
	}

	// make next month req check array for gubun b
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


	#----- get : 지정된 날짜(시간)의 예약된 목록
	if (in_array($view['PS_GUBUN'], array('A','B')))
	{
		# 강의실
		if ($view['PS_GUBUN'] == 'A')
		{
			$sql = "Select PR.*"
				."		, hour(PR.PR_SDATE) As PR_SDATE_HH"
				."		, hour(PR.PR_EDATE) As PR_EDATE_HH"
				."	From PLACE_RENTAL_REQ As PR"
				."		Inner Join PLACE_RENTAL_SUB As PS On PS.PS_IDX = PR.PS_IDX"
				."			And PS.PS_DDATE is null"
				."			And PS.PS_IDX = '{$ps_id}'"
				."		Inner Join PLACE_RENTAL_MASTER As PM On PM.PM_IDX = PS.PM_IDX"
				."			And PM.PM_DDATE is null"
				."			And PM.PM_IDX = '{$pm_id}'"
				."	Where PR.PR_DDATE is null"
				."		And year(PR.PR_SDATE) = '{$year}'"
				."		And month(PR.PR_SDATE) = '{$month}'"
				."		And day(PR.PR_SDATE) = '{$day}'"
				."		And PR.PR_IDX != '{$pr_id}'"
				."	Order By hour(PR.PR_SDATE) Asc"
				;
		}
		# 숙소
		else {
			/*
			$sql = "Select PR.*"
				."		, day(PR.PR_SDATE) As PR_SDATE_DD"
				."		, day(PR.PR_EDATE) As PR_EDATE_DD"
				."	From PLACE_RENTAL_REQ As PR"
				."		Inner Join PLACE_RENTAL_SUB As PS On PS.PS_IDX = PR.PS_IDX"
				."			And PS.PS_DDATE is null"
				."			And PS.PS_IDX = '{$ps_id}'"
				."		Inner Join PLACE_RENTAL_MASTER As PM On PM.PM_IDX = PS.PM_IDX"
				."			And PM.PM_DDATE is null"
				."			And PM.PM_IDX = '{$pm_id}'"
				."	Where PR.PR_DDATE is null"
				."		And year(PR.PR_SDATE) = '{$year}'"
				."		And month(PR.PR_SDATE) = '{$month}'"
				."		And PR.PR_IDX != '{$pr_id}'"
				."	Order By day(PR.PR_SDATE) Asc"
				;
			*/
			$sql = "Select PR.*"
				."		, day(PR.PR_SDATE) As PR_SDATE_DD"
				."		, day(PR.PR_EDATE) As PR_EDATE_DD"
				."	From PLACE_RENTAL_REQ As PR"
				."		Inner Join PLACE_RENTAL_SUB As PS On PS.PS_IDX = PR.PS_IDX"
				."			And PS.PS_DDATE is null"
				."			And PS.PS_IDX = '{$ps_id}'"
				."		Inner Join PLACE_RENTAL_MASTER As PM On PM.PM_IDX = PS.PM_IDX"
				."			And PM.PM_DDATE is null"
				."			And PM.PM_IDX = '{$pm_id}'"
				."	Where PR.PR_DDATE is null"
				."		And PR.PR_SDATE >= '{$year}-{$month}-{$day}'"
				."		And PR.PR_SDATE < date_add('{$year}-{$month}-{$day}', INTERVAL 10 DAY)"
				."		And PR.PR_STATUS != 'C'"
				."		And PR.PR_IDX != '{$pr_id}'"
				."	Order By day(PR.PR_SDATE) Asc"
				;
		}

		$db1 = sql_query($sql);
		$req_list = array();
		while ($rs1 = sql_fetch_array($db1)) {
			$req_list[] = $rs1;
		}
	}
	else {
		$req_list = array();
	}
	#####


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

include_once('./place_rental_tail.sub.php');
?>