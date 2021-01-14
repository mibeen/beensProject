<?php
include_once('./_common.php');

// if($is_guest)
//     alert('회원이시라면 로그인 후 이용해 보십시오.', './login.php?url='.urlencode(G5_BBS_URL.'/place_rental_list.php'));

$qaconfig = get_qa_config();

$g5['title'] = '대관 현황';
include_once('./place_rental_head.php');

$skin_file = $qa_skin_path.'/list.skin.php';

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

    $area_result = array();
    $sql = " select * from {$g5['place_rental_area_table']} where PA_DDATE is null";
    $area_result_temp = sql_query($sql);
    for ($i=0; $row=sql_fetch_array($area_result_temp); $i++) {
        $area_result[] = $row;
    }
    $j_area = count($area_result);

    $sql_common = " from {$g5['place_rental_master_table']} ";
    $sql_search = " where PM_DDATE is null and PM_USE_YN = 'Y' ";

    if($sar) {
        $sql_search .= " and PA_IDX = '{$sar}' ";
    }

    $stx = trim($stx);
    if($stx) {
        if (preg_match("/[a-zA-Z]/", $stx))
            $sql_search .= " and ( INSTR(LOWER(PM_NAME), LOWER('$stx')) > 0 or INSTR(LOWER(PM_INFO), LOWER('$stx')) > 0 )";
        else
            $sql_search .= " and ( INSTR(PM_NAME, '$stx') > 0 or INSTR(PM_INFO, '$stx') > 0 ) ";
    }

    $sql_order = " order by PM_IDX ";

    $sql = " select count(*) as cnt
                $sql_common
                $sql_search ";
    $row = sql_fetch($sql);
    $total_count = $row['cnt'];

    $page_rows = 12;
    if($sco == 2) {
        $page_rows = 24;
    }
    else if($sco == 2) {
        $page_rows = 48;
    }

    if($total_count == 1) {

        $total_page  = ceil($total_count / $page_rows);  // 전체 페이지 계산
        if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
        $from_record = ($page - 1) * $page_rows; // 시작 열을 구함

        $sql = " select *
                    $sql_common
                    $sql_search
                    $sql_order
                    limit $from_record, $page_rows ";
        $result = sql_query($sql);
        $row = sql_fetch_array($result);
        $pm_id = $row['PM_IDX'];

        include_once('./place_rental_view_get_data.php');
    }
    else {
        $total_page  = ceil($total_count / $page_rows);  // 전체 페이지 계산
        if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
        $from_record = ($page - 1) * $page_rows; // 시작 열을 구함

        $sql = " select *
                    $sql_common
                    $sql_search
                    $sql_order
                    limit $from_record, $page_rows ";
        $result = sql_query($sql);

        $list = array();
        $num = $total_count - ($page - 1) * $page_rows;
        $subject_len = G5_IS_MOBILE ? $qaconfig['qa_mobile_subject_len'] : $qaconfig['qa_subject_len'];
        for($i=0; $row=sql_fetch_array($result); $i++) {
            $list[$i] = $row;

            // area name
            for ($ii = 0; $ii < $j_area; $ii++) { 
                $row_area = $area_result[$ii];
                if($row_area['PA_IDX'] == $row['PA_IDX']) {
                    $list[$i]['PA_NAME'] = $row_area['PA_NAME'];
                    break;
                }
            }

            $list[$i]['PM_NANE'] = conv_subject($row['PM_NANE'], $subject_len, '…');
            if ($stx) {
                $list[$i]['PM_NANE'] = search_font($stx, $list[$i]['PM_NANE']);
            }

            $list[$i]['view_href'] = G5_BBS_URL.'/place_rental_view.php?pm_id='.$row['PM_IDX'].$qstr;

            $list[$i]['name'] = apms_sideview($row['mb_id'], get_text($row['qa_name']), $row['email'], '', 'no');

            // list image
            $bo_table = 'place_rental';
            $sql = " select * from {$g5['board_file_table']} where bo_table = '{$bo_table}' and wr_id = '{$row['PM_IDX']}'";
            $place_file_result = sql_query($sql);
            $place_file = sql_fetch_array($place_file_result);
            $himg = '';
            if(isset($place_file) && $place_file['bf_file'] != '') {
                $himg = G5_DATA_PATH."/file/".$bo_table."/".$place_file['bf_file'];
            }

            $list[$i]['bf_file'] = $place_file['bf_file'];
            $list[$i]['bf_source'] = $place_file['bf_source'];

            // count sub
            $sql = " select count(*) as cnt from {$g5['place_rental_sub_table']} where PS_DDATE is null and PS_GUBUN = 'A' and PM_IDX = " . $row['PM_IDX'];
            $row_sub = sql_fetch($sql);
            $gubun_a_count = $row_sub['cnt'];

            $sql = " select count(*) as cnt from {$g5['place_rental_sub_table']} where PS_DDATE is null and PS_GUBUN = 'B' and PM_IDX = " . $row['PM_IDX'];
            $row_sub = sql_fetch($sql);
            $gubun_b_count = $row_sub['cnt'];

            $list[$i]['REQ_INFO'] = "강의실 " . $gubun_a_count . " | 숙소 " . $gubun_b_count;
        }

        $is_checkbox = false;
        $admin_href = '';
        if($is_admin) {
            $is_checkbox = true;
            $admin_href = G5_ADMIN_URL.'/place_rental_list.php';
        }

        $list_href = G5_BBS_URL.'/place_rental_list.php';
        $write_href = '';

        $list_pages = preg_replace('/(\.php)(&amp;|&)/i', '$1?', get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, './place_rental_list.php'.$qstr.'&amp;page='));

        $stx = get_text(stripslashes($stx));
    }

    
    include_once($skin_file);
} else {
    echo '<div>'.str_replace(G5_PATH.'/', '', $skin_file).'이 존재하지 않습니다.</div>';
}


# 메뉴 표시에 사용할 변수 
$_gr_id = 'gseek';  // 게시판 그룹 ID - 현재 메뉴표시에 사용
$pid = ($pid) ? $pid : 'rentlist';   // Page ID

include "../page/inc.page.menu.php";
?>

<?php
include_once('./qatail.php');
?>