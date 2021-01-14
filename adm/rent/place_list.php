<?php
$sub_menu = "990200";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$sql_common = " from {$g5['place_rental_area_table']} a ";
$sql_search = " where (1) ";

if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case "bo_table" :
            $sql_search .= " ($sfl like '$stx%') ";
            break;
        case "a.gr_id" :
            $sql_search .= " ($sfl = '$stx') ";
            break;
        default :
            $sql_search .= " ($sfl like '%$stx%') ";
            break;
    }
    $sql_search .= " ) ";
}

if (!$sst) {
    $sst  = "a.PA_IDX";
    $sod = "asc";
}
$sql_order = " order by $sst $sod ";

$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select * {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';

$g5['title'] = '지역관리';
include_once(G5_ADMIN_PATH.'/admin.head.php');

$colspan = 2;
?>

<div class="nx-box1 mb30">
    <div class="nx-tit2">지역 추가</div>
    <form name="fboardlist" id="fboardlist" action="./place_update.php" onsubmit="return true;" method="post">
    <input type="text" name="PA_NAME" required class="nx-ips1 wm2" placeholder="새로운 지역을 입력해주세요.">
    <input type="submit" name="act_button" class="nx-btn2" value="지역추가" onclick="document.pressed=this.value">
    </form>
</div>

<form name="fboardlist" id="fboardlist" action="./place_update.php" onsubmit="return fboardlist_submit(this);" method="post">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="token" value="<?php echo $token ?>">

<div class="ofH mb10">
    <div class="fL">
        <input type="submit" name="act_button" value="선택수정" onclick="document.pressed=this.value" class="nx-btn-b2">
        <?php if ($is_admin == 'super') { ?>
        <input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value" class="nx-btn-b2">
        <?php } ?>
    </div>
    <div class="fR">
        <a href="place_rental_list.php" class="nx-btn-b3">장소 목록</a>
    </div>
</div>

<table border="0" cellpadding="0" cellspacing="0" class="nx-t-list1">
<caption><?php echo $g5['title']; ?> 목록</caption>
<colgroup>
    <col width="50"><col width="">
</colgroup>
<thead>
<tr>
    <th>
        <input type="checkbox" id="chkall" name="chkall" class="chk1" value="1" onclick="check_all(this.form)"><label for="chkall"><span class="chkbox"><span class="ico_check"></span></span></label>
    </th>
    <th>이름</a></th>
</tr>
</thead>
<tbody>
<?php
for ($i=0; $row=sql_fetch_array($result); $i++) {
?>
<tr>
    <td>
        <input type="hidden" name="PA_IDX[<?php echo $i ?>]" value="<?php echo $row['PA_IDX'] ?>">
        <input type="checkbox" id="chk_<?php echo $i ?>" name="chk[]" value="<?php echo $i ?>" class="chk1"><label for="chk_<?php echo $i ?>"><span class="chkbox"><span class="ico_check"></span></span></label>
    </td>
    <td>
        <label for="bo_subject_<?php echo $i; ?>" class="sound_only">이름<strong class="sound_only"> 필수</strong></label>
        <input type="text" name="PA_NAME[<?php echo $i ?>]" value="<?php echo get_text($row['PA_NAME']) ?>" id="PA_NAME_<?php echo $i ?>" required class="nx-ips1 PA_NAME">
    </td>
</tr>
<?php
}
if ($i == 0)
    echo '<tr><td colspan="'.$colspan.'" class="empty_table">등록된 지역이 없습니다.</td></tr>';
?>
</tbody>
</table>

<div class="taL mt10">
    <input type="submit" name="act_button" value="선택수정" onclick="document.pressed=this.value" class="nx-btn-b2">
    <?php if ($is_admin == 'super') { ?>
    <input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value" class="nx-btn-b2">
    <?php } ?>
</div>
</form>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$qstr.'&amp;page='); ?>

<script>
function fboardlist_submit(f)
{
    if (!is_checked("chk[]")) {
        alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "선택삭제") {
        if(!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
            return false;
        }
    }

    return true;
}
</script>

<?php
include_once(G5_ADMIN_PATH.'/admin.tail.php');
?>
