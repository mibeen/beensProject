<?php
$sub_menu = "990200";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$sql_common = " from {$g5['place_rental_req_table']} a ";
$sql_search = " where (1) and PR_DDATE is null";

$sql_order = " order by a.PR_SDATE desc ";

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

for ($i = 0; $row=sql_fetch_array($result); $i++) {
    // GET PS_NAME
    $sql = "select PM_IDX, PS_NAME, PS_GUBUN from {$g5['place_rental_sub_table']} where (1) and PS_IDX = {$row['PS_IDX']}";
    $result_PS = sql_fetch($sql);

    // GET USER NAME
    $sql = "select mb_nick from {$g5['member_table']} where (1) and mb_no = {$row['mb_no']}";
    $result_member = sql_fetch($sql);

    $sdate = date('Y-m-d', strtotime($row['PR_SDATE']));
    $year = date('Y', strtotime($row['PR_SDATE']));
    $month = date('m', strtotime($row['PR_SDATE']));

    if($sdate < 0) {
        $day_list[$i]['PR_SDATE'] = '-';
    }
    else {
        $day_list[$i]['PR_SDATE'] = $sdate;
    }
    
    $day_list[$i]['PR_IDX'] = $row['PR_IDX'];
    $day_list[$i]['PS_IDX'] = $row['PS_IDX'];
    $day_list[$i]['PS_NAME'] = $result_PS['PS_NAME'];
    $day_list[$i]['mb_nick'] = $result_member['mb_nick'];

    if($result_PS['PS_GUBUN'] == 'A')
        $day_list[$i]['PS_GUBUN'] = '강의실';
    else if($result_PS['PS_GUBUN'] == 'B')
        $day_list[$i]['PS_GUBUN'] = '숙소';

    if($row['PR_STATUS'] == 'A')
        $day_list[$i]['PR_STATUS'] = '신청';
    else if($row['PR_STATUS'] == 'B')
        $day_list[$i]['PR_STATUS'] = '승인';
    else if($row['PR_STATUS'] == 'C')
        $day_list[$i]['PR_STATUS'] = '보류';
    else if($row['PR_STATUS'] == 'D')
        $day_list[$i]['PR_STATUS'] = '삭제';

    $day_list[$i]['link_href'] = './place_rental_req_list.php?PM_IDX='.$result_PS['PM_IDX'].'&PS_IDX='.$row['PS_IDX'].'&PR_IDX='.$row['PR_IDX'].'&year='.$year.'&month='.$month.'&popup=1';
}

$g5['title'] = '강의실 / 숙소 관리';
include_once(G5_ADMIN_PATH.'/admin.head.php');

$colspan = 15;
?>

<ul class="nx-tab1">
    <li><a href="./place_rental_list.php">장소 목록</a></li>
    <li><a href="./place_rental_req_all_list.php" class="aon">예약 현황</a></li>
</ul>

<table border="0" cellpadding="0" cellspacing="0" class="nx-t-list1">
<caption><?php echo $g5['title']; ?> 목록</caption>
<colgroup>
    <col width="80"><col width="15%"><col width=""><col width="10%"><col width="10%"><col width="10%"><col width="10%">
</colgroup>
<thead>
<tr>
    <th>NO</th>
    <th>입실날짜</th>
    <th>강의실명</th>
    <th>신청자</th>
    <th>종류</th>
    <th>상태</th>
    <th>관리</th>
</tr>
</thead>
<tbody>
<?php
for ($i=0; $i < count($day_list); $i++) { 
?>
<tr>
    <td>
        <?php echo($total_count - $from_record - $i) ?> 
    </td>
    <td>
        <?php echo $day_list[$i]['PR_SDATE']; ?>
    </td>
    <td class="taL">
        <?php echo $day_list[$i]['PS_NAME']; ?>
    </td>
    <td>
        <?php echo $day_list[$i]['mb_nick']; ?>
    </td>
    <td>
        <?php echo $day_list[$i]['PS_GUBUN']; ?>
    </td>
    <td>
        <?php echo $day_list[$i]['PR_STATUS']; ?>
    </td>
    <td>
        <a href="<?php echo $day_list[$i]['link_href']?>" class="color1">상세 보기</a>
    </td>
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
