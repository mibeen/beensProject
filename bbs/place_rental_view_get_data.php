<?php
$sql = " select * from {$g5['place_rental_master_table']} where PM_IDX = '$pm_id' ";
$view = sql_fetch($sql);

if(!$view['PM_IDX'])
    alert('게시글이 존재하지 않습니다.\\n삭제되었거나 자신의 글이 아닌 경우입니다.');

$area_result = array();
$sql = " select * from {$g5['place_rental_area_table']} where PA_DDATE is null";
$area_result_temp = sql_query($sql);
for ($i=0; $row=sql_fetch_array($area_result_temp); $i++) {
    $area_result[] = $row;
}
$j_area = count($area_result);

$subject_len = G5_IS_MOBILE ? $qaconfig['qa_mobile_subject_len'] : $qaconfig['qa_subject_len'];

$view['PM_NAME'] = get_text($view['PM_NAME']);
$view['PM_INFO'] = conv_content($view['PM_INFO'], $view['PM_INFO']);
$view['PM_CHARGE'] = get_text($view['PM_CHARGE']);
$view['PM_TEL'] = get_text($view['PM_TEL']);
$view['PM_ADDRESS'] = get_text($view['PM_ADDRESS']);
$view['PM_EMAIL'] = get_text(get_email_address($view['PM_EMAIL']));

if (trim($stx))
    $view['PM_NAME'] = search_font($stx, $view['PM_NAME']);

if (trim($stx))
    $view['PM_INFO'] = search_font($stx, $view['PM_INFO']);

// area name
for ($ii = 0; $ii < $j_area; $ii++) { 
    $row_area = $area_result[$ii];
    if($row_area['PA_IDX'] == $view['PA_IDX']) {
        $view['PA_NAME'] = $row_area['PA_NAME'];
        break;
    }
}

// list image
$bo_table = 'place_rental';
$sql = " select * from {$g5['board_file_table']} where bo_table = '{$bo_table}' and wr_id = '{$pm_id}'";
$place_file_result = sql_query($sql);
$place_file = sql_fetch_array($place_file_result);
if(isset($place_file) && $place_file['bf_file'] != '') {
    $himg = G5_DATA_PATH."/file/".$bo_table."/".$place_file['bf_file'];
}

$view['bf_file'] = $place_file['bf_file'];
$view['bf_source'] = $place_file['bf_source'];


// all image
$place_image_list = array();
for ($i=0; $place_file=sql_fetch_array($place_file_result); $i++) {
    if(isset($place_file) && $place_file['bf_file'] != '') {
        $himg = G5_DATA_PATH."/file/".$bo_table."/".$place_file['bf_file'];
    }

    $place_image_list[$i]['bf_file'] = $place_file['bf_file'];
    $place_image_list[$i]['bf_source'] = $place_file['bf_source'];
}

// sub list
$sql = " select * from {$g5['place_rental_sub_table']} where PS_DDATE is null and PS_GUBUN = 'A' and PM_IDX = " . $pm_id;
$row_sub = sql_query($sql);
$row_sub_1 = array();
for ($i=0; $row=sql_fetch_array($row_sub); $i++) {
    $row_sub_1[$i]['PS_IDX'] = $row['PS_IDX'];
    $row_sub_1[$i]['PS_NAME'] = $row['PS_NAME'];

    $row_sub_1[$i]['view_href'] = G5_BBS_URL.'/place_rental_sub_view.php?pm_id='.$pm_id."&ps_id=".$row['PS_IDX'].$qstr;
    
    # 로그인 하지 않았을 경우
    if ($is_guest) {
        $row_sub_1[$i]['req_href'] = "javascript:alert('로그인 후 이용 가능 합니다.');login_win();";
    }
    else {
        $row_sub_1[$i]['req_href'] = G5_BBS_URL.'/place_rental_req_list.php?pm_id='.$pm_id."&ps_id=".$row['PS_IDX'].$qstr;
    }
    

    // list image
    $bo_table = 'place_rental_sub';
    $sql = " select * from {$g5['board_file_table']} where bo_table = '{$bo_table}' and bf_no = 0 and wr_id = '{$row['PS_IDX']}'";
    $place_file_result = sql_query($sql);
    $place_file = sql_fetch_array($place_file_result);
    $himg = '';
    if(isset($place_file) && $place_file['bf_file'] != '') {
        $himg = G5_DATA_PATH."/file/".$bo_table."/".$place_file['bf_file'];
    }

    $row_sub_1[$i]['bf_file'] = $place_file['bf_file'];
    $row_sub_1[$i]['bf_source'] = $place_file['bf_source'];
}

$sql = " select * from {$g5['place_rental_sub_table']} where PS_DDATE is null and PS_GUBUN = 'B' and PM_IDX = " . $pm_id;
$row_sub = sql_query($sql);
$row_sub_2 = array();
for ($i=0; $row=sql_fetch_array($row_sub); $i++) {
    $row_sub_2[$i]['PS_IDX'] = $row['PS_IDX'];
    $row_sub_2[$i]['PS_NAME'] = $row['PS_NAME'];

    $row_sub_2[$i]['view_href'] = G5_BBS_URL.'/place_rental_sub_view.php?pm_id='.$pm_id."&ps_id=".$row['PS_IDX'].$qstr;
    
    # 로그인 하지 않았을 경우
    if ($is_guest) {
        $row_sub_2[$i]['req_href'] = "javascript:alert('로그인 후 이용 가능 합니다.');login_win();";
    }
    else {
        $row_sub_2[$i]['req_href'] = G5_BBS_URL.'/place_rental_req_list.php?pm_id='.$pm_id."&ps_id=".$row['PS_IDX'].$qstr;
    }
    

    // list image
    $bo_table = 'place_rental_sub';
    $sql = " select * from {$g5['board_file_table']} where bo_table = '{$bo_table}' and bf_no = 0 and wr_id = '{$row['PS_IDX']}'";
    $place_file_result = sql_query($sql);
    $place_file = sql_fetch_array($place_file_result);
    $himg = '';
    if(isset($place_file) && $place_file['bf_file'] != '') {
        $himg = G5_DATA_PATH."/file/".$bo_table."/".$place_file['bf_file'];
    }

    $row_sub_2[$i]['bf_file'] = $place_file['bf_file'];
    $row_sub_2[$i]['bf_source'] = $place_file['bf_source'];
}


$update_href = '';
$delete_href = '';
$write_href = G5_BBS_URL.'/qawrite.php';
$rewrite_href = G5_BBS_URL.'/qawrite.php?w=r&amp;pm_id='.$view['pm_id'];
$list_href = G5_BBS_URL.'/place_rental_list.php'.preg_replace('/^&amp;/', '?', $qstr);

if(($view['qa_type'] && $is_admin) || (!$view['qa_type'] && $view['qa_status'] == 0)) {
    $update_href = G5_BBS_URL.'/qawrite.php?w=u&amp;pm_id='.$view['pm_id'].$qstr;
    $delete_href = G5_BBS_URL.'/qadelete.php?pm_id='.$view['pm_id'].$qstr;
}

$stx = get_text(stripslashes($stx));

$is_dhtml_editor = false;
// 모바일에서는 DHTML 에디터 사용불가
if ($config['cf_editor'] && $qaconfig['qa_use_editor'] && !G5_IS_MOBILE) {
    $is_dhtml_editor = true;
}

$ss_name = 'ss_qa_view_'.$pm_id;
if(!get_session($ss_name))
    set_session($ss_name, TRUE);

?>