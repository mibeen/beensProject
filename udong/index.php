<?php
include_once('./_common.php');
include_once('../_head.php');


# set : variables
$sar = $_REQUEST['sar'];
$stx = $_REQUEST['stx'];


# re-define
$sar = preg_replace('/[^0-9]/', '', $sar);
$stx = trim($stx);


$qstr = '';


# wh
$wh = "Where lp.lp_ddate is null And lp.lp_use_yn = 'Y'";

if ($sar) {
    $wh .= " And lp.la_idx = '" . mres($sar) . "'";
    $qstr .= '&amp;sar=' . urlencode($sar);
}

if($stx) {
    if (preg_match("/[a-zA-Z]/", $stx))
        $wh .= " and ( INSTR(LOWER(lp.lp_name), LOWER('" . mres($stx) . "')) > 0 or INSTR(LOWER(lp.lp_address), LOWER('" . mres($stx) . "')) > 0 or INSTR(LOWER(lp.lp_intro), LOWER('" . mres($stx) . "')) > 0 or INSTR(LOWER(lp.lp_info), LOWER('" . mres($stx) . "')) > 0 )";
    else
        $wh .= " and ( INSTR(lp.lp_name, '" . mres($stx) . "') > 0 or INSTR(lp.lp_address, '" . mres($stx) . "') > 0 or INSTR(lp.lp_intro, '" . mres($stx) . "') > 0 or INSTR(lp.lp_info, '" . mres($stx) . "') > 0 ) ";

    $qstr .= '&amp;stx=' . urlencode($stx);
}


$sql = "Select count(*) As cnt
            From local_place As lp
            {$wh}";
$row = sql_fetch($sql);
$total_count = $row['cnt'];


$page_rows = 12;

$total_page  = ceil($total_count / $page_rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $page_rows; // 시작 열을 구함
$total_page  = ceil($total_count / $page_rows);  // 전체 페이지 계산
?>
<style>
    .list-category .active a {font-weight: 700;}
</style>

<div class="data_ct">
    <?php
        include "./inc.page.title.php";
    ?>

    <?php
    if ($total_count == 1) {
        # read
        include "./inc.place.read.php";
    }
    else {
        # list
        include "./inc.place.list.php";
    }
    ?>

</div>

<?php
# 메뉴 표시에 사용할 변수 
$_gr_id = 'gill';  // 게시판 그룹 ID - 현재 메뉴표시에 사용
$pid = ($pid) ? $pid : 'udong';   // Page ID

include "../page/inc.page.menu.php";
?>

<?php
include_once('../_tail.php');
?>