<?php
$sub_menu = "960100";
include_once('./_common.php');

# 시설 관리자 권한 부여
if ($member['mb_level'] == 3) {
    $auth['960100'] = 'w';
}
auth_check($auth[$sub_menu], 'w', true);


if ($_FILES["FILE"]["name"] == "") {
    alert('파일을 읽을 수 없습니다.', './member_udongmng.php?mode=' . $memb_mode);
}

$tmp_file = $_FILES['FILE']['tmp_name'];


require G5_PLUGIN_PATH . '/PhpSpreadsheet/vendor/autoload.php';

$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
$reader->setReadDataOnly(true);
$spreadsheet = $reader->load($tmp_file);
$sheet = $spreadsheet->getActiveSheet();

$maxRow = $sheet->getHighestRow();          // 마지막 라인

$target = "A"."2".":"."E"."$maxRow";        // A, B열의 내용
$lines = $sheet->rangeToArray($target, NULL, TRUE, FALSE);


$results = [];

$s = 0;
foreach ($lines as $key => $line) {
    $s++;

    # set : variables
    $_no = $line[0];
    $_lp_idx = $line[1];
    $_la_name = $line[2];
    $_lp_name = $line[3];
    $_mb_id = $line[4];


    if ($_lp_idx == '' || $_mb_id == '') {
        $results[] = [$_no, $_la_name, $_lp_name, $_mb_id, '실패', '필수정보 누락'];
        continue;
    }


    # 아이디 있는지 확인
    $mb = get_member($_mb_id);
    if (!$mb['mb_id']) {
        $results[] = [$_no, $_la_name, $_lp_name, $_mb_id, '실패', '존재하지 않는 시설관리자'];
        continue;
    }

    # 학습공간 있는지 확인
    $sql = "select count(*) as cnt from local_place where lp_ddate is null and lp_idx = '" . mres($_lp_idx) . "'";
    $row = sql_fetch($sql);
    if ($row['cnt'] == 0) {
        $results[] = [$_no, $_la_name, $_lp_name, $_mb_id, '실패', '존재하지 않는 학습공간'];
        continue;
    }


    # 수정
    $sql = "update local_place
                set mb_id = '" . mres($_mb_id) . "'
                where lp_idx = '" . mres($_lp_idx) . "'
                limit 1"
        ;
    sql_query($sql);

    $results[] = [$_no, $_la_name, $_lp_name, $_mb_id, '성공'];
}


$g5['title'] = '학습공간 시설관리자연결 엑셀일괄등록';
include_once(G5_ADMIN_PATH.'/admin.head.php');
?>

<div class="tbl_head01 tbl_wrap">
    <table>
        <caption>학습공간 시설관리자연결 일괄등록 결과내역</caption>
        <thead>
            <th scope="col">번호</th>
            <th scope="col">지역명</th>
            <th scope="col">장소명</th>
            <th scope="col">시설관리자 ID</th>
            <th scope="col">성공여부</th>
            <th scope="col">실패사유</th>
        </thead>
        <tbody>
            <?php
            # 결과내역
            $results_len = Count($results);
            for($i = 0; $i < $results_len; $i++) {
                $_itm = $results[$i];
                ?>
            <tr<?php if($_itm[4] == '실패'){echo(' class="color4"');}?>>
                <td class="td_num"><?php echo $_itm[0] ?></td>
                <td class="td_output"><?php echo $_itm[1] ?></td>
                <td class="td_output"><?php echo $_itm[2] ?></td>
                <td class="td_output"><?php echo $_itm[3] ?></td>
                <td class="td_num"><?php echo $_itm[4] ?></td>
                <td class="td_id"><?php echo $_itm[5] ?></td>
            </tr>
                <?php
            }
            ?>
        </tbody> 
    </table>
</div>

<div class="taR mt20">
    <a href="./place.list.php" class="nx-btn-b2">완료</a>
</div>

<?php
include_once(G5_ADMIN_PATH.'/admin.tail.php');
?>