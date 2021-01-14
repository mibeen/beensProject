<?php
$sub_menu = '990500';
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$sql_common = " from nx_sms Where ma_target = 'B' ";

// 테이블의 전체 레코드수만 얻음
$sql = " select COUNT(*) as cnt {$sql_common} ";

//페이징 추가
$row = sql_fetch($sql);
$total_count = $row['cnt'];
$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
//페이징추가

$sql = " select * {$sql_common} order by ma_id desc Limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$g5['title'] = 'SMS발송';
include_once('./admin.head.php');

$colspan = 8;
//echo "</h1>".$sql."</h1>";
//echo "</br></h1>total_count: ".$total_count."</h1>";
//echo "</br></h1>total_page: ".$total_page."</h1>";
//echo "</br></h1>from_record: ".$from_record."</h1>";
?>

<div class="local_desc01 local_desc">
    <p>
        <b>테스트</b>는 등록된 최고관리자의 휴대폰번호로 테스트 SMS를 발송합니다.<br>
        현재 등록된 SMS는 총 <?php echo $total_count ?>건입니다.<br>
        <strong>주의) 수신자가 동의하지 않은 대량 SMS 발송에는 적합하지 않습니다. 수십건 단위로 발송해 주십시오.</strong>
    </p>
</div>

<div class="btn_add01 btn_add">
    <a href="./sms_form2.php" id="mail_add">SMS내용추가</a>
</div>

<form name="fmaillist" id="fmaillist" action="./sms_delete.php?ma_target=B" method="post">
<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col"><input type="checkbox" name="chkall" value="1" id="chkall" title="현재 페이지 목록 전체선택" onclick="check_all(this.form)"></th>
        <th scope="col">번호</th>
        <th scope="col">제목</th>
        <th scope="col">작성일시</th>
        <th scope="col">발송이력</th>
        <th scope="col">테스트</th>
        <th scope="col">보내기</th>
        <th scope="col">미리보기</th>
    </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        $s_vie = '<a href="./sms_preview.php?ma_id='.$row['ma_id'].'" target="_blank">미리보기</a>';

        $num = number_format($total_count - ($page - 1) * $config['cf_page_rows'] - $i);

        $bg = 'bg'.($i%2);
    ?>

    <tr class="<?php echo $bg; ?>">
        <td class="td_chk">
            <label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo $row['ma_subject']; ?> SMS</label>
            <input type="checkbox" id="chk_<?php echo $i ?>" name="chk[]" value="<?php echo $row['ma_id'] ?>">
        </td>
        <td class="td_num"><?php echo $num ?></td>
        <td><a href="./sms_form2.php?w=u&amp;ma_id=<?php echo $row['ma_id'] ?>"><?php echo $row['ma_subject'] ?></a></td>
        <td class="td_datetime"><?php echo $row['ma_time'] ?></td>
        <td class="td_mngsmall"><a href="./sms_log.php?ma_target=B&ma_id=<?php echo $row['ma_id'] ?>">발송이력</a></td>
        <td class="td_test"><a href="./sms_test.php?ma_id=<?php echo $row['ma_id'] ?>">테스트</a></td>
        <td class="td_odrnum2"><a href="./sms_select_form2.php?ma_id=<?php echo $row['ma_id'] ?>">SMS 보내기</a></td>
        <td class="td_mngsmall"><?php echo $s_vie ?></td>
    </tr>

    <?php
    }
    if (!$i)
        echo "<tr><td colspan=\"".$colspan."\" class=\"empty_table\">자료가 없습니다.</td></tr>";
    ?>
    </tbody>
    </table>
</div>

<div class="btn_list01 btn_list">
    <input type="submit" value="선택삭제">
</div>
</form>

<?php
	$qstr .= "&amp;page=";

	$pagelist = get_paging($config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr");
	echo $pagelist;
?>

<script>
$(function() {
    $('#fmaillist').submit(function() {
        if(confirm("한번 삭제한 자료는 복구할 방법이 없습니다.\n\n정말 삭제하시겠습니까?")) {
            if (!is_checked("chk[]")) {
                alert("선택삭제 하실 항목을 하나 이상 선택하세요.");
                return false;
            }

            return true;
        } else {
            return false;
        }
    });
});
</script>

<?php
include_once ('./admin.tail.php');
?>