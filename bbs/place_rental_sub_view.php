<?php
include_once('./_common.php');
include_once(G5_EDITOR_LIB);

// if($is_guest)
//     alert('회원이시라면 로그인 후 이용해 보십시오.', './login.php?url='.urlencode(G5_BBS_URL.'/place_rental_view.php?ps_id='.$ps_id));

$qaconfig = get_qa_config();

$place_rental_sub_view_yn = 1;

$g5['title'] = '대관 현황';
include_once('./place_rental_head.php');

$skin_file = $qa_skin_path.'/sub_view.skin.php';

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

    $sql = " select * from {$g5['place_rental_sub_table']} where PS_IDX = '{$ps_id}' ";
    $view = sql_fetch($sql);

    if(!$view['PS_IDX'])
        alert('게시글이 존재하지 않습니다.\\n삭제되었거나 자신의 글이 아닌 경우입니다.');

    // place name
    $sql = " select PM_IDX, PM_NAME, PA_IDX from {$g5['place_rental_master_table']} where PM_IDX = '{$pm_id}' and PM_USE_YN = 'Y'";
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

    // all image
    $bo_table = 'place_rental_sub';
    $sql = " select * from {$g5['board_file_table']} where bo_table = '{$bo_table}' and wr_id = '{$ps_id}'";
    $place_file_result = sql_query($sql);
    $place_image_list = array();
    for ($i=0; $place_file=sql_fetch_array($place_file_result); $i++) {
        if(isset($place_file) && $place_file['bf_file'] != '') {
            $himg = G5_DATA_PATH."/file/".$bo_table."/".$place_file['bf_file'];
        }

        $place_image_list[$i]['bf_file'] = $place_file['bf_file'];
        $place_image_list[$i]['bf_source'] = $place_file['bf_source'];
    }

    if($qstr == "") { $qstr = "?"; }
    
    $update_href = '';
    $delete_href = '';
    $write_href = G5_BBS_URL.'/qawrite.php';
    $rewrite_href = G5_BBS_URL.'/qawrite.php?w=r&amp;ps_id='.$view['ps_id'];
    $list_href = G5_BBS_URL.'/place_rental_view.php'.preg_replace('/^&amp;/', '?', $qstr).'&amp;pm_id='.$pm_id;

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

include_once('./qatail.php');
?>