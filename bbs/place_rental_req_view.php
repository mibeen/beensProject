<?php
include_once('./_common.php');
include_once(G5_EDITOR_LIB);

if($is_guest) {
	alert_script("로그인 후 이용 가능 합니다.", "parent.$('#viewModal').modal('hide');");
}

$qaconfig = get_qa_config();

$g5['title'] = '대관 현황';
include_once('./place_rental_head.sub.php');

$skin_file = $qa_skin_path.'/req_view.skin.php';

if(is_file($skin_file)) {
	if ((int)$ps_id <= 0 || (int)$pr_id <= 0) {
		alert_script("잘못된 접근 입니다.", "parent.$('#viewModal').modal('hide');");
	}

	$req_view = sql_fetch(
		"Select PR.*"
		."		, PS.PS_GUBUN, PS.PS_NAME"
		."		, PA.PA_NAME"
		."		, M.mb_nick"
		."	From PLACE_RENTAL_REQ As PR"
		."		Inner Join PLACE_RENTAL_SUB As PS On PS.PS_IDX = PR.PS_IDX"
		."			And PS.PS_DDATE is null"
		."		Inner Join PLACE_RENTAL_MASTER As PM On PM.PM_IDX = PS.PM_IDX"
		."			And PM.PM_DDATE is null"
		."		Inner Join PLACE_RENTAL_AREA As PA On PA.PA_IDX = PM.PA_IDX"
		."			And PA.PA_DDATE is null"
		."		Inner Join {$g5['member_table']} As M On M.mb_no = PR.mb_no"
		."	Where PR.PR_DDATE is null"
		."		And PR.PS_IDX = '{$ps_id}'"
		."		And PR.PR_IDX = '{$pr_id}'"
		."		And PR.mb_no = '{$member['mb_no']}'"
		."	Order By PR.PR_IDX Desc"
		."	Limit 1"
	);
	if (is_null($req_view['PR_IDX'])) {
		unset($req_view);
		alert_script("잘못된 접근 입니다.", "parent.$('#viewModal').modal('hide');");
	}

	
	# re-define
	if ($req_view['PS_GUBUN'] == 'A') {
		$req_view['PS_GUBUN_STR'] = '강의실';
	}
	else {
		$req_view['PS_GUBUN_STR'] = '숙소';
	}


	if($qstr == "") { $qstr = "?"; }
	
	$update_href = '';
	$delete_href = '';
	$list_href = G5_BBS_URL.'/place_rental_view.php'.preg_replace('/^&amp;/', '?', $qstr).'&amp;pm_id='.$pm_id;
	$detail_href = G5_BBS_URL.'/place_rental_sub_view.php'.preg_replace('/^&amp;/', '?', $qstr).'&amp;pm_id='.$pm_id.'&amp;ps_id='.$ps_id;

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