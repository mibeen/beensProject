<?php
$sub_menu = "970300";
include_once('./_common.php');
include_once('./req.err.php');

auth_check($auth[$sub_menu], 'r');


#----- wh
$wh = " Where lr.lr_ddate is null ";

if ($sc_lr_sdate != '') {
    $wh .= " And lr.lr_sdate >= '" . mres($sc_lr_sdate) . "'";
}
if ($sc_lr_edate != '') {
    $wh .= " And lr.lr_edate < DATE_ADD('" . mres($sc_lr_edate) . "', INTERVAL 1 DAY)";
}

if ($stx) {
    $wh .= " And lp.lp_name like '%" . mres($stx) . "%'";
}

if($suy != '') {
    $wh .= " And lr.lr_status = '" . mres($suy) . "'";
}

if($splace != '') {
    $wh .= " And la.la_idx = '" . mres($splace) . "'";
}
#####


$sql = "Select count(*) as cnt 
            From local_place_req As lr
                Inner Join local_place As lp On lp.lp_idx = lr.lp_idx 
                    And lp.lp_ddate is null
                Inner Join local_place_area As la On la.la_idx = lp.la_idx 
                    And la.la_ddate is null
                Inner Join g5_member As M On M.mb_id = lr.mb_id
            {$wh}"
    ;
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 50;
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함


$sql = "Select lr.* 
                , lp.lp_idx, lp.lp_name
                , la.la_idx, la.la_name
                , M.mb_nick
            From local_place_req As lr
                Inner Join local_place As lp On lp.lp_idx = lr.lp_idx 
                    And lp.lp_ddate is null
                Inner Join local_place_area As la On la.la_idx = lp.la_idx 
                    And la.la_ddate is null
                Inner Join g5_member As M On M.mb_id = lr.mb_id
            {$wh}
            Order By lr.lr_sdate Desc
            limit {$from_record}, {$rows}"
    ;
$result = sql_query($sql);


$sql = "Select * from local_place_area where la_ddate is null";
$area_result_temp = sql_query($sql);
$area_result = array();
for ($i=0; $row=sql_fetch_array($area_result_temp); $i++) {
    $area_result[] = $row;
}
$j_area = count($area_result);


# 관리자 개인정보 접근이력 기록
nx_privacy_log('list', 'local_place_req');


$str_lr_status = ['A'=>'신청', 'B'=>'승인', 'C'=>'미승인', 'D'=>'승인취소'];


$g5['title'] = '전체 현황';
include_once(G5_ADMIN_PATH.'/admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

$colspan = 10;
?>

<form name="fsearch" id="fsearch" method="get">
<input type="submit" style="width:1px;height:1px;position:absolute;top:-10000px;left:-10000px" />

<div class="sch_box">
    <div class="sch_opt1">
        <div class="sch_opt2">
            <span class="sch_term" data-tit="기간">
                <input type="text" id="sc_lr_sdate" name="sc_lr_sdate" class="nx-ips1" value="<?php echo($sc_lr_sdate)?>" style="max-width:140px;">

                <span class="tilde">~</span>

                <input type="text" id="sc_lr_edate" name="sc_lr_edate" class="nx-ips1" value="<?php echo($sc_lr_edate)?>" style="max-width:140px;">
            </span>

            <div class="res_wdtmh" style="height:10px;"></div>

            <span class="nx-slt" data-tit="지역">
                <select name="splace" id="splace" style="min-width: 140px;">
                    <option value="">전체</option>
                <?php
                for ($i=0; $i < $j_area; $i++) { 
                    $row = $area_result[$i];
                    ?>
                    <option value="<?php echo $row['la_idx']?>" <?php if($row['la_idx'] == $splace) { echo('selected'); }?>><?php echo $row['la_name']?></option>
                <?php } ?>
                </select>
                <span class="ico_select"></span>
            </span>

            <span class="nx-slt" data-tit="상태">
                <select name="suy" id="suy" style="min-width: 140px;">
                    <option value="">전체</option>
                    <option value="A" <?php if($suy == 'A') { echo('selected'); }?>><?php echo($str_lr_status['A'])?></option>
                    <option value="B" <?php if($suy == 'B') { echo('selected'); }?>><?php echo($str_lr_status['B'])?></option>
                    <option value="C" <?php if($suy == 'C') { echo('selected'); }?>><?php echo($str_lr_status['C'])?></option>
                    <option value="D" <?php if($suy == 'D') { echo('selected'); }?>><?php echo($str_lr_status['D'])?></option>
                </select>
                <span class="ico_select"></span>
            </span>

            <span class="sch_ipt wm2" data-tit="기관명">
                <input type="text" name="stx" id="stx" value="<?php echo $stx ?>" class="nx-ips1">
            </span>
        </div>
    </div>

    <a href="javascript:void(0);" onclick="$('#fsearch').submit();" class="btn_sch"><span class="ico_search2"></span></a>
</div>
</form>

<div class="taR mb10">
    <a href="./req.timeline.excel.php?<?php echo($qstr)?>" class="nx-btn-b2">엑셀 다운로드</a>
</div>

<table border="0" cellpadding="0" cellspacing="0" class="nx-t-list1">
<caption>우리동네 학습공간 전체 예약 목록</caption>
<colgroup>
    <col width="80"><col width=""><col width=""><col width=""><col width=""><col width=""><col width=""><col width=""><col width=""><col width="">
</colgroup>
<thead>
<tr>
    <th>NO</th>
    <th>지역명</th>
    <th>시설명</th>
    <th>신청자</th>
    <th>휴대폰</th>
    <th>이메일</th>
    <th>이용일시</th>
    <th>인원</th>
    <th>진행상태</th>
    <th>관리</th>
</tr>
</thead>
<tbody>
<?php
for ($i = 0; $row=sql_fetch_array($result); $i++) { 
    $_year = date('Y', strtotime($row['lr_sdate']));
    $_month = date('m', strtotime($row['lr_sdate']));
    $link_href = './place.req.list.php?lp_idx='.$row['lp_idx'].'&lr_idx='.$row['lr_idx'].'&year='.$_year.'&month='.$_month.'&'.$qstr.'popup=1';
    ?>
<tr>
    <td><?php echo($total_count - $from_record - $i) ?></td>
    <td><?php echo F_hsc($row['la_name'])?></td>
    <td><?php echo F_hsc($row['lp_name'])?></td>
    <td><?php echo F_hsc($row['mb_nick'])?></td>
    <td><?php echo F_hsc($row['lr_tel'])?></td>
    <td><?php echo F_hsc($row['lr_email'])?></td>
    <td><?php echo(date('Y-m-d H:i', strtotime($row['lr_sdate'])) . '~' . date('H:i', strtotime($row['lr_edate']))) ?></td>
    <td><?php echo F_hsc($row['lr_p_cnt']); ?></td>
    <td><?php echo $str_lr_status[$row['lr_status']]; ?></td>
    <td><a href="<?php echo $link_href?>" class="color1">상세 보기</a></td>
</tr>
    <?php
}

if ($i == 0)
    echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>';
?>
</tbody>
</table>

<?php
    $qstr .= "&amp;page=";

    $pagelist = get_paging($config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr");
    echo $pagelist;
?>

<script>
//<![CDATA[
$(function(){
    $("#sc_lr_sdate, #sc_lr_edate").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "2016:c+5" });
});
//]]>
</script>

<?php
include_once(G5_ADMIN_PATH.'/admin.tail.php');
?>
