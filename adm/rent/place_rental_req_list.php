<?php
$sub_menu = "990200";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$place_rental_popup_yn = 1;

if (isset($_REQUEST['PM_IDX']) && $_REQUEST['PM_IDX']) {
    $PM_IDX = preg_replace('/[^0-9]/', '', $PM_IDX);
} else {
    alert("잘못된 접근입니다.");
}

if (isset($_REQUEST['PS_IDX']) && $_REQUEST['PS_IDX']) {
    $PS_IDX = preg_replace('/[^0-9]/', '', $PS_IDX);
} else {
    alert("잘못된 접근입니다.");
}

if (isset($_REQUEST['PR_IDX']) && $_REQUEST['PR_IDX']) {
    $PR_IDX = preg_replace('/[^0-9]/', '', $PR_IDX);
} else {
    $PR_IDX = 0;
}

if (isset($_REQUEST['year']) && $_REQUEST['year']) {
    $year = preg_replace('/[^0-9]/', '', $year);
} else {
    $year = date('Y');
}

if (isset($_REQUEST['month']) && $_REQUEST['month']) {
    $month = preg_replace('/[^0-9]/', '', $month);
} else {
    $month = date('m');
}

if (isset($_REQUEST['popup']) && $_REQUEST['popup']) {
    $popup = preg_replace('/[^0-9]/', '', $popup);
} else {
    $popup = 0;
}

$today = date('Y-m-d', strtotime($year . '-' . $month . '-1'));
$last_day = date("t", strtotime($today));

$prev_year = $year;
$next_year = $year;
$next_month = $month + 1;
if($next_month >= 13) {
	$next_month = 1;
	$next_year = $year + 1;
}
$prev_month = $month - 1;
if($prev_month <= 0) {
	$prev_month = 12;
	$prev_year = $year - 1;
}

$sql = " select PS_GUBUN, PS_NAME from {$g5['place_rental_sub_table']} where PS_IDX = '{$PS_IDX}' ";
$ps_view = sql_fetch($sql);

$sql_common = " from {$g5['place_rental_req_table']} a ";
$sql_search = " where PR_DDATE is null And PS_IDX = {$PS_IDX}";

if($sfl != '') {
    $sql_search .= " and a.PS_IDX = {$sfl}";
}

if (!$sst) {
    $sst  = "a.PS_IDX";
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

if($ps_view['PS_GUBUN'] == 'A') {
    $ps_view['PA_GUBUN_TITLE'] = '강의실';
}
elseif($ps_view['PS_GUBUN'] == 'B') {
    $ps_view['PA_GUBUN_TITLE'] = '숙소';
}

for ($i = 1; $i <= $last_day; $i++) {
    $sql = "select * from {$g5['place_rental_req_table']} where PR_DDATE is null And PS_IDX = {$PS_IDX} and year(PR_SDATE) = {$year} and month(PR_SDATE) = {$month} and day(PR_SDATE) = {$i}";

    $result_req = sql_query($sql);
    $day_list[] = $result_req;
}

$g5['title'] = '예약 현황';
include_once(G5_ADMIN_PATH.'/admin.head.php');

$colspan = 15;
?>

<h3 class="nx-tit1 lh30 mb" style="margin-top:0px"><a href="place_rental_sub_list.php?PM_IDX=<?php echo($PM_IDX)?>" class="nx-btn-b3 fR ml15">강의실/숙소 목록</a><?php echo($ps_view['PA_GUBUN_TITLE'] . ' : ' . $ps_view['PS_NAME'])?></h3>
<input type="hidden" name="last_day" id="last_day" value="<?php echo $last_day?>">
<table border="0" cellpadding="0" cellspacing="0" class="nx-t-list1 place_req_list">
<caption><?php echo $g5['title']; ?> 목록</caption>
<colgroup>
    <col width="60"><col width="">
</colgroup>
<thead>
<tr>
    <th scope="col" colspan="3"><span class="vaM"><?php echo $year?></span>&nbsp;&nbsp;<a href="./place_rental_req_list.php?PM_IDX=<?php echo($PM_IDX)?>&PS_IDX=<?php echo $PS_IDX?>&amp;year=<?php echo $prev_year?>&amp;month=<?php echo $prev_month?>"> <i class="fa fa-angle-left fa-2x vaM"></i> </a>&nbsp;<span class="vaM"><?php echo $month?>월</span>&nbsp;<a href="./place_rental_req_list.php?PM_IDX=<?php echo($PM_IDX)?>&PS_IDX=<?php echo $PS_IDX?>&amp;year=<?php echo $next_year?>&amp;month=<?php echo $next_month?>"> <i class="fa fa-angle-right fa-2x vaM"></i> </a></th>
</tr>
</thead>
<tbody>
<?php
    $j = count($day_list);
    for ($i = 0; $i < $j; $i++) {
        $idx = ($i + 1);
        $result_req_row = $day_list[$i];
    ?>
    <tr>

        <td><span><?php echo $idx; ?></span></td>
        <td class="middle_td">
            <?php
                for ($ii=0; $row=sql_fetch_array($result_req_row); $ii++) {
                    if($ii == 0) { echo("<ul>"); }

                    $req_PR_IDX = $row['PR_IDX'];
                    $req_status = $row['PR_STATUS'];
                    $req_p_cnt = $row['PR_P_CNT'];
                    $req_tel = $row['PR_TEL'];
                    $req_info = $row['PR_CONT'];
                    $req_mb_no = $row['mb_no'];
                    
                    if($ps_view['PS_GUBUN'] == 'A') {
                        $req_sdate = date('H:i', strtotime($row['PR_SDATE']));
                        $req_edate = date('H:i', strtotime($row['PR_EDATE']));
                        $req_edate_show = $req_edate;
                    }
                    else if($ps_view['PS_GUBUN'] == 'B') {
                        $req_sdate = date('Y-m-d', strtotime($row['PR_SDATE']));
                        $req_edate = date('Y-m-d', strtotime($row['PR_EDATE']));
                        $req_edate_show = $req_edate;
                        
                        $date1 = new DateTime($req_sdate);
                        $date2 = new DateTime($req_edate);
                        $req_edate = $date1->diff($date2)->days;
                    }

                    $t_member = sql_fetch(" select mb_nick, mb_no from {$g5['member_table']} where mb_no = TRIM('$req_mb_no') ");

                    $req_title = ($ii+1) . '. ' . $t_member['mb_nick'] . ' (' . $req_sdate . '~' . $req_edate_show . ')';

                    $req_status_title = '';
                    if($req_status == 'A') { $req_status_title = '신청'; }
                    else if($req_status == 'B') { $req_status_title = '승인'; }
                    else if($req_status == 'C') { $req_status_title = '보류'; }
                    else if($req_status == 'D') { $req_status_title = '삭제'; }

                    $is_mine = false;
                    $link_href_tag = '<span href="javascript:void(0);"class="req_list_link req_list_type_'.$req_status.'">'.$req_title.'<span>'.$req_status_title.'</span></span>';
                    if(($row['mb_no'] == $member['mb_no']) || $is_admin == 'super') {
                        $is_mine = true;
                        $link_href_tag = '<a href="javascript:void(0);" onclick="req.view(\''.$req_PR_IDX.'\')" class="req_list_link req_list_type_'.$req_status.'">'.$req_title.'<span>'.$req_status_title.'</span></a>';
                    }
                ?>
                <li<?php if($PR_IDX == $req_PR_IDX) {echo(' class="req_selected"');}?>>
                    <?php echo $link_href_tag?>
                    <span class="req_selected_text">선택한 예약</span>
                </li>
            <?php } ?>

            <?php if($ii == 0) { ?>
            <span><?php echo('신청현황이 없습니다.'); ?></span>
            <?php } else { ?>
            </ul>
            <?php } ?>
        </td>
    </tr>
<?php } ?>
</tbody>
</table>

<div class="taR mt10">
    <a href="place_rental_sub_list.php?PM_IDX=<?php echo($PM_IDX)?>" class="nx-btn-b3">강의실/숙소 목록</a>
</div>

<div style="display:none">
    <form id="req_form" name="req_form" method="post" onsubmit="return false">
        <input type="hidden" name="PM_IDX" value="" />
        <input type="hidden" name="PS_IDX" value="" />
        <input type="hidden" name="PR_IDX" value="" />
    </form>
</div>

<script>
//<![CDATA[
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

var req = {
    view: function(c) {
        if (c == '' || isNaN(c)) return;

        var f = document.getElementById('req_form');

        var url = './place_rental_req_view.php';
        window.open("" ,"req_view", "width=540,height=606,scrollbars=no"); 

        f.action = url;
        f.target = 'req_view';
        f.PM_IDX.value = '<?php echo($PM_IDX)?>';
        f.PS_IDX.value = '<?php echo($PS_IDX)?>';
        f.PR_IDX.value = c;

        f.submit();
    }
}
//]]>
</script>
<?php
include_once(G5_ADMIN_PATH.'/admin.tail.php');
?>
