<?php
# set : variables
$ma_target = $_GET['ma_target'];


# re-define : variables
$ma_target = (in_array($ma_target, array('A', 'B'))) ? $ma_target : 'A';


$sub_menu = ($ma_target == 'A') ? "200310" : "990500";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$sql_common = " from nx_sms_log as ml"
        . " Inner Join g5_member as m On m.mb_no = ml.mb_no"
        . " where ml.ma_id = '" . mres($ma_id) . "'";

// 테이블의 전체 레코드수만 얻음
$sql = " select COUNT(*) as cnt {$sql_common} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함


$sql = " select ml.*"
    . ", m.mb_name, m.mb_id"
    . " {$sql_common} order by ml_datetime desc "
    ."  Limit {$from_record}, {$rows} ";
$result = sql_query($sql);


$sql = "Select ma_subject From nx_sms Where ma_id = '" . mres($ma_id) . "'";
$db_ma = sql_fetch($sql);
$ma_subject = $db_ma['ma_subject'];


$g5['title'] = ($ma_target == 'A') ? '회원SMS발송' : 'SMS발송' ;
include_once('./admin.head.php');

$colspan = 7;
?>

<h3 class="nx-tit1 lh30 mb" style="margin-top:0px"><a href="./sms_list<?php if($ma_target == 'B'){echo('2');}?>.php" class="nx-btn-b3 fR ml15">뒤로</a>제목: <?php echo($ma_subject)?></h3>

<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col">번호</th>
        <th scope="col">발송관리회원</th>
        <th scope="col">발신번호</th>
        <th scope="col">발송개수</th>
        <th scope="col">종류</th>
        <th scope="col">발송시각</th>
        <th scope="col">IP</th>
    </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        $num = number_format($total_count - ($page - 1) * $config['cf_page_rows'] - $i);
    ?>

    <tr>
        <td class="td_num"><?php echo $num ?></td>
        <td class="td_id"><?php echo ($row['mb_name'] . " [" . $row['mb_id'] . "]") ?></td>
        <td class="td_etc"><?php echo $row['ml_from_tel'] ?></td>
        <td class="td_postal"><?php echo $row['ml_count'] ?></td>
        <td class="td_postal"><?php echo $row['ml_type'] ?></td>
        <td class="td_datetime"><?php echo $row['ml_datetime'] ?></td>
        <td class="td_datetime"><?php echo long2ip(sprintf("%d", $row['ml_ip'])) ?></td>
    </tr>

    <?php
    }
    if (!$i)
        echo "<tr><td colspan=\"".$colspan."\" class=\"empty_table\">자료가 없습니다.</td></tr>";
    ?>
    </tbody>
    </table>
</div>

<?php
    $qstr .= "&amp;page=";

    $pagelist = get_paging($config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?".$qstr);
    echo $pagelist;
?>

<?php
include_once ('./admin.tail.php');
?>