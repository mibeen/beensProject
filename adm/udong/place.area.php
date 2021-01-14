<?php
$sub_menu = "970100";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');


$sql = "Select * From
        local_place_area As la 
        Order by la.la_idx Asc"
    ;
$LA = sql_query($sql);


$g5['title'] = '지역관리';
include_once(G5_ADMIN_PATH.'/admin.head.php');

$colspan = 2;
?>

<div class="nx-box1 mb30">
    <div class="nx-tit2">지역 추가</div>
    <form name="fboardlist" id="fboardlist" action="./place.areaProc.php" onsubmit="return true;" method="post">
    <input type="text" name="la_name" required class="nx-ips1 wm2" placeholder="새로운 지역을 입력해주세요.">
    <input type="submit" name="act_button" class="nx-btn2" value="지역추가" onclick="document.pressed=this.value">
    </form>
</div>

<form name="fboardlist" id="fboardlist" action="./place.areaProc.php" onsubmit="return fboardlist_submit(this);" method="post">
<input type="hidden" name="token" value="<?php echo $token ?>">

<div class="ofH mb10">
    <div class="fL">
        <input type="submit" name="act_button" value="선택수정" onclick="document.pressed=this.value" class="nx-btn-b2">
        <?php if ($is_admin == 'super') { ?>
        <input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value" class="nx-btn-b2">
        <?php } ?>
    </div>
    <div class="fR">
        <a href="place.list.php" class="nx-btn-b3">장소 목록</a>
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
for ($i=0; $row=sql_fetch_array($LA); $i++) {
?>
<tr>
    <td>
        <input type="hidden" name="la_idx[<?php echo $i ?>]" value="<?php echo $row['la_idx'] ?>">
        <input type="checkbox" id="chk_<?php echo $i ?>" name="chk[]" value="<?php echo $i ?>" class="chk1"><label for="chk_<?php echo $i ?>"><span class="chkbox"><span class="ico_check"></span></span></label>
    </td>
    <td>
        <label for="bo_subject_<?php echo $i; ?>" class="sound_only">이름<strong class="sound_only"> 필수</strong></label>
        <input type="text" name="la_name[<?php echo $i ?>]" value="<?php echo get_text($row['la_name']) ?>" id="la_name_<?php echo $i ?>" required class="nx-ips1 la_name">
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
