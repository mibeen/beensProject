<?php
$memb_mode = 'udong_adm';
$sub_menu = "970200";
include_once('./_common.php');
auth_check($auth[$sub_menu], 'w, d', true);


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

$target = "B"."2".":"."G"."$maxRow";        // A, B열의 내용
$lines = $sheet->rangeToArray($target, NULL, TRUE, FALSE);


$results = [];

$s = 0;
foreach ($lines as $key => $line) {
    $s++;

    # set : variables
    $_id = $line[0];
    $_pw = $line[1];
    $_name = $line[2];
    $_nick = $line[3];
    $_email = $line[4];
    $_hp = $line[5];


    # set : re-define
    $_hp = (substr_count($_hp,'-') == 2) ? (string)$_hp : hyphen_hp_number($_hp);


    if ($_id == '' || $_pw == '' || $_name == '' || $_nick == '' || $_email == '' || $_hp == '') {
        $results[] = [$s, $_id, $_name, '실패', '필수정보 누락'];
        continue;
    }


    # 아이디 중복체크
    ## 아이디 중복되면 => 아이디, 닉네임 모두 Update
    ## 아이디 중복되지 않으면 => 닉네임 중복 시는 실패, 닉네임 중복되지 않으면 Insert
    $mb = get_member($_id);

    if ($mb['mb_id']) {
        # 아이디 중복 시

        # Update
        $sql = "update {$g5['member_table']}
                    set mb_password = '" . F_xenc($_pw) . "'
                        , mb_password_type = 'B'
                        , mb_email_certify = '" . mres(G5_TIME_YMDHIS) . "'
                        , mb_name = '" . mres($_name) . "'
                        , mb_nick = '" . mres($_nick) . "'
                        , mb_email = '" . mres($_email) . "'
                        , mb_hp = '" . mres($_hp) . "'
                        , mb_level = '4'
                        , mb_adult = '0'
                        , mb_mailling = '0'
                        , mb_sms = '0'
                        , mb_open = '0'
                    where mb_id = '" . mres($_id) . "'
                    limit 1"
            ;

    } else {
        # 아이디 중복되지 않을 시

        $sql = "select count(*) as cnt from {$g5['member_table']} where mb_nick = '" . mres($_nick) . "' ";
        $row = sql_fetch($sql);
        unset($sql);

        if ($row['cnt'] > 0) {
            # 닉네임이 중복되면 실패
            $results[] = [$s, $_id, $_name, '실패', '닉네임 중복'];
            continue;
        } else{
            # 닉네임 중복도 없으면 Insert

            $sql = "insert into {$g5['member_table']}
                        set mb_id = '" . mres($_id) . "'
                            , mb_password = '" . F_xenc($_pw) . "'
                            , mb_password_type = 'B'
                            , mb_datetime = '" . mres(G5_TIME_YMDHIS) . "'
                            , mb_ip = '" . $_SERVER['REMOTE_ADDR'] . "'
                            , mb_email_certify = '" . mres(G5_TIME_YMDHIS) . "'
                            , mb_name = '" . mres($_name) . "'
                            , mb_nick = '" . mres($_nick) . "'
                            , mb_email = '" . mres($_email) . "'
                            , mb_hp = '" . mres($_hp) . "'
                            , mb_level = '4'
                            , mb_adult = '0'
                            , mb_mailling = '0'
                            , mb_sms = '0'
                            , mb_open = '0'"
                ;
        }

    }


    # 등록
    sql_query($sql);

    $results[] = [$s, $_id, $_name, '성공'];
}


$g5['title'] = '시설관리자 엑셀일괄등록';
include_once('../admin.head.php');
?>

<div class="tbl_head01 tbl_wrap">
    <table>
        <caption>시설관리자 일괄등록 결과내역</caption>
        <thead>
            <th scope="col">번호</th>
            <th scope="col">아이디</th>
            <th scope="col">이름</th>
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
            <tr<?php if($_itm[3] == '실패'){echo(' class="color4"');}?>>
                <td class="td_num"><?php echo $_itm[0] ?></td>
                <td class="td_output"><?php echo $_itm[1] ?></td>
                <td class="td_output"><?php echo $_itm[2] ?></td>
                <td class="td_num"><?php echo $_itm[3] ?></td>
                <td class="td_id"><?php echo $_itm[4] ?></td>
            </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>

<div class="taR mt20">
    <a href="./member_list.php?mode=<?php echo($memb_mode)?>" class="nx-btn-b2">완료</a>
</div>

<?php
include_once('../admin.tail.php');
?>
