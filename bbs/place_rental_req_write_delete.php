<?php
    include_once('./_common.php');

    /*==========================
    $w == a : 답변
    $w == r : 추가질문
    $w == u : 수정
    ==========================*/

    if($is_guest) {
        alert_script("로그인 후 이용 가능 합니다.", "parent.$('#viewModal').modal('hide');");
    }

    $msg = array();

    if (isset($_POST['year']) && $_POST['year']) {
        $qstr_year = preg_replace('/[^0-9]/', '', $year);
    } else {
        $qstr_year = date('Y');
    }

    if (isset($_POST['month']) && $_POST['month']) {
        $qstr_month = preg_replace('/[^0-9]/', '', $month);
    } else {
        $qstr_month = date('m');
    }


    // pm_id check
    if (isset($_REQUEST['pm_id']) && $_REQUEST['pm_id']) {
    	$pm_id = preg_replace('/[^0-9]/', '', $pm_id);
    } else {
        $pm_id = '';
    }

    // ps_id check
    if (isset($_REQUEST['ps_id']) && $_REQUEST['ps_id']) {
    	$ps_id = preg_replace('/[^0-9]/', '', $ps_id);
    } else {
        $ps_id = '';
    }

    // ps_id check
    if (isset($_POST['pr_id']) && $_POST['pr_id']) {
        $pr_id = preg_replace('/[^0-9]/', '', $pr_id);
    } else {
        $pr_id = '';
    }

    if(!$pm_id || !$ps_id) {
        alert_script("잘못된 접근입니다.", "parent.$('#viewModal').modal('hide');");
    }

    $upload_max_filesize = ini_get('upload_max_filesize');

    if (empty($_POST)) {
        alert("파일 또는 글내용의 크기가 서버에서 설정한 값을 넘어 오류가 발생하였습니다.\\npost_max_size=".ini_get('post_max_size')." , upload_max_filesize=".$upload_max_filesize."\\n게시판관리자 또는 서버관리자에게 문의 바랍니다.");
    }

    
    # 대상글 존재여부 확인
    $row = sql_fetch(
        "Select Count(PR.PR_IDX) As cnt"
        ."  From PLACE_RENTAL_REQ As PR"
        ."      Inner Join PLACE_RENTAL_SUB As PS On PS.PS_IDX = PR.PS_IDX"
        ."          And PS.PS_DDATE is null"
        ."      Inner Join PLACE_RENTAL_MASTER As PM On PM.PM_IDX = PS.PM_IDX"
        ."          And PM.PM_DDATE is null"
        ."      Inner Join PLACE_RENTAL_AREA As PA On PA.PA_IDX = PM.PA_IDX"
        ."          And PA.PA_DDATE is null"
        ."      Inner Join {$g5['member_table']} As M On M.mb_no = PR.mb_no"
        ."  Where PR.PR_DDATE is null"
        ."      And PR.PS_IDX = '{$ps_id}'"
        ."      And PR.PR_IDX = '{$pr_id}'"
        ."      And PR.PR_STATUS in ('A','C')"
        ."      And DATE_FORMAT(PR.PR_SDATE, '%Y-%m-%d') >= DATE_FORMAT(now(), '%Y-%m-%d')"
        ."      And PR.mb_no = '{$member['mb_no']}'"
        ."  Order By PR.PR_IDX Desc"
        ."  Limit 1"
    );
    if ($row['cnt'] <= 0) {
        unset($row);
        alert_script("잘못된 접근 입니다.", "parent.$('#viewModal').modal('hide');");
    }
    unset($row);


    # 삭제 처리
    $sql = "Update PLACE_RENTAL_REQ Set"
        ." PR_DDATE = now()"
        .", PR_DIP = '{$_SERVER['REMOTE_ADDR']}'"
        ." Where PR_IDX = '{$pr_id}'"
        ." Limit 1"
        ;
    sql_query($sql);


    $result_url = G5_BBS_URL.'/place_rental_req_list.php?pm_id='.$pm_id.'&ps_id='.$ps_id.'&year='.$qstr_year.'&month='.$qstr_month.preg_replace('/^&amp;/', '?', $qstr);

    alert_script('취소 되었습니다.', "parent.location.href='{$result_url}';");
?>