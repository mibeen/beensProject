<?php
$sub_menu = "970100";
include_once('./_common.php');
include_once('./place.req.err.php');

auth_check($auth[$sub_menu], 'r');

$place_rental_popup_yn = 1;


# set : variables
$year = $_REQUEST['year'];
$month = $_REQUEST['month'];
$lp_idx = $_REQUEST['lp_idx'];
$lr_idx = $_REQUEST['lr_idx'];
$popup = $_GET['popup'];


# re-define
$year = (CHK_NUMBER($year) > 0) ? (int)$year : date('Y');
$month = (CHK_NUMBER($month) > 0) ? (int)$month : date('n');
$lp_idx = (CHK_NUMBER($lp_idx) > 0) ? (int)$lp_idx : '';
$lr_idx = (CHK_NUMBER($lr_idx) > 0) ? (int)$lr_idx : '';


# chk : rfv.
if ($lp_idx <= 0) alert('잘못된 접근입니다.');


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


$sql = "Select lp_name from local_place where lp_idx = '" . mres($lp_idx) ."'";
$LP = sql_fetch($sql);

$sql_common = " from local_place_req lr ";
$sql_search = " where lr_ddate is null And lp_idx = '" . mres($lp_idx) . "'";

$sql = " select count(*) as cnt {$sql_common} {$sql_search}";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select * {$sql_common} {$sql_search} Order by lr.lp_idx Asc limit {$from_record}, {$rows} ";
$result = sql_query($sql);

for ($i = 1; $i <= $last_day; $i++) {
    $sql = "Select lr.lr_idx, lr.lr_sdate, lr.lr_edate, lr.lr_status
                    , m.mb_nick
                From local_place_req As lr
                    Inner Join g5_member As m On m.mb_id = lr.mb_id
                where lr_ddate is null 
                    And lp_idx = '" . mres($lp_idx) . "'
                    And year(lr_sdate) = '" . mres($year) . "'
                    And month(lr_sdate) = '" . mres($month) . "'
                    And day(lr_sdate) = {$i}
                Order by lr.lr_sdate"
        ;

    $result_req = sql_query($sql);
    $day_list[] = $result_req;
}


$_list_link = ($popup == 1) ? 'req.timeline.php?' . $qstr : 'place.list.php?' . $qstr;


$g5['title'] = '예약 현황';
include_once(G5_ADMIN_PATH.'/admin.head.php');
?>

<h3 class="nx-tit1 lh30 mb" style="margin-top:0px"><a href="<?php echo($_list_link)?>" class="nx-btn-b3 fR ml15">뒤로</a><?php echo($LP['lp_name'])?></h3>
<input type="hidden" name="last_day" id="last_day" value="<?php echo $last_day?>">
<table border="0" cellpadding="0" cellspacing="0" class="nx-t-list1 place_req_list">
<caption><?php echo $g5['title']; ?> 목록</caption>
<colgroup>
    <col width="60"><col width="">
</colgroup>
<thead>
<tr>
    <th scope="col" colspan="3"><span class="vaM"><?php echo $year?></span>&nbsp;&nbsp;<a href="./place.req.list.php?lp_idx=<?php echo($lp_idx)?>&amp;year=<?php echo $prev_year?>&amp;month=<?php echo $prev_month?>"> <i class="fa fa-angle-left fa-2x vaM"></i> </a>&nbsp;<span class="vaM"><?php echo $month?>월</span>&nbsp;<a href="./place.req.list.php?lp_idx=<?php echo($lp_idx)?>&amp;year=<?php echo $next_year?>&amp;month=<?php echo $next_month?>"> <i class="fa fa-angle-right fa-2x vaM"></i> </a></th>
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

                    $req_lr_idx = $row['lr_idx'];
                    $req_status = $row['lr_status'];
                    
                    $req_sdate = date('H:i', strtotime($row['lr_sdate']));
                    $req_edate = date('H:i', strtotime($row['lr_edate']));
                    $req_edate_show = $req_edate;

                    $req_title = ($ii+1) . '. ' . $row['mb_nick'] . ' (' . $req_sdate . '~' . $req_edate_show . ')';

                    $req_status_title = '';
                    if($req_status == 'A') { $req_status_title = '신청'; }
                    else if($req_status == 'B') { $req_status_title = '승인'; }
                    else if($req_status == 'C') { $req_status_title = '미승인'; }
                    else if($req_status == 'D') { $req_status_title = '승인취소'; }

                    // $link_href_tag = '<span href="javascript:void(0);"class="req_list_link req_list_type_'.$req_status.'">'.$req_title.'<span>'.$req_status_title.'</span></span>';
                    // if(($row['mb_id'] == $member['mb_id']) || $is_admin == 'super') {
                        $link_href_tag = '<a href="javascript:void(0);" onclick="req.view(\''.$req_lr_idx.'\')" class="req_list_link req_list_type_'.$req_status.'">'.$req_title.'<span>'.$req_status_title.'</span></a>';
                    // }
                ?>
                <li<?php if($lr_idx == $req_lr_idx) {echo(' class="req_selected"');}?>>
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
    <a href="<?php echo($_list_link)?>" class="nx-btn-b3">뒤로</a>
</div>

<div style="display:none">
    <form id="req_form" name="req_form" method="post" onsubmit="return false">
        <input type="hidden" name="lp_idx" value="" />
        <input type="hidden" name="lr_idx" value="" />
    </form>
</div>

<script>
//<![CDATA[
var req = {
    view: function(c) {
        if (c == '' || isNaN(c)) return;

        var f = document.getElementById('req_form');

        var url = './place.req.read.php';
        window.open("" ,"req_read", "width=540,height=606,scrollbars=no"); 

        f.action = url;
        f.target = 'req_read';
        f.lp_idx.value = '<?php echo($lp_idx)?>';
        f.lr_idx.value = c;

        f.submit();
    }
}
//]]>
</script>
<?php
include_once(G5_ADMIN_PATH.'/admin.tail.php');
?>
