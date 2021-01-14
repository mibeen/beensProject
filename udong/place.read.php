<?php
include_once('./_common.php');
include_once('../_head.php');


# set : variables
$sar = $_REQUEST['sar'];
$stx = $_GET['stx'];


# re-define
$sar = preg_replace('/[^0-9]/', '', $sar);
$stx = trim($stx);


$qstr = '';

if ($sar) {
    $qstr .= '&amp;sar=' . urlencode($sar);
}
if ($stx) {
    $qstr .= '&amp;stx=' . urlencode($stx);
}
?>

<div class="data_ct">
    <?php
        include "./inc.page.title.php";
    ?>
    
    <div class="taR mb10">
        <a role="button" href="./?<?php echo($qstr)?>" class="btn btn-black btn-sm">
            <i class="fa fa-bars"></i><span class="hidden-xs"> 목록</span>
        </a>
    </div>

    <?php
        include "./inc.place.read.php";
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