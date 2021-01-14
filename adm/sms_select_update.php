<?php
# set : variables
$ma_target = $_POST['ma_target'];
$from_tel = $_POST['from_tel'];


# re-define : variables
$ma_target = (in_array($ma_target, array('A', 'B'))) ? $ma_target : 'A';


$sub_menu = ($ma_target == 'A') ? "200310" : "990500";
include_once('./_common.php');
include_once(G5_PLUGIN_PATH.'/nx_sms/class.DR_SMS.php');

auth_check($auth[$sub_menu], 'w');


$g5['title'] = ($ma_target == 'A') ? '회원SMS발송' : 'SMS발송' ;

check_demo();

check_admin_token();

include_once('./admin.head.php');
include_once(G5_LIB_PATH.'/mailer.lib.php');

$countgap = 10; // 몇건씩 보낼지 설정
$maxscreen = 500; // 몇건씩 화면에 보여줄건지?
$sleepsec = 200;  // 천분의 몇초간 쉴지 설정

echo "<span style='font-size:9pt;'>";
echo "<p>SMS 발송중 ...<p><font color=crimson><b>[끝]</b></font> 이라는 단어가 나오기 전에는 중간에 중지하지 마세요.<p>";
echo "</span>";
?>

<span id="cont"></span>

<?php
include_once('./admin.tail.php');
?>

<?php
flush();
ob_flush();

$ma_id = trim($_POST['ma_id']);
$select_member_list = trim($_POST['ma_list']);

//print_r2($_POST); EXIT;
$member_list = explode("\n", conv_unescape_nl($select_member_list));

// SMS내용 가져오기
$sql = "select ma_subject, ma_content from nx_sms where ma_id = '$ma_id' ";
$ma = sql_fetch($sql);

$subject = $ma['ma_subject'];

$cnt = 0;
for ($i=0; $i<count($member_list); $i++)
{
    if ($ma_target == 'A') {
        list($to_hp, $mb_id, $name, $nick, $datetime) = explode("||", trim($member_list[$i]));
    }
    else {
        list($to_hp, $name) = explode("||", trim($member_list[$i]));
    }

    $sw = preg_match("/[^0-9]/", $to_hp);
    // 올바른 휴대폰번호 주소만
    if ($sw == true)
    {
        $cnt++;

        $content = $ma['ma_content'];
        if ($ma_target == 'A') {
            $content = preg_replace("/{이름}/", $name, $content);
            $content = preg_replace("/{닉네임}/", $nick, $content);
            $content = preg_replace("/{회원아이디}/", $mb_id, $content);
            $content = preg_replace("/{휴대폰번호}/", $to_hp, $content);
        }
        else {
            $content = preg_replace("/{이름}/", $name, $content);
            $content = preg_replace("/{휴대폰번호}/", $to_hp, $content);
        }

		DR_SMS::SEND(array('SCHEDULE_TYPE'=>'0', 'SMS_MSG'=>$content, 'CALLBACK'=>$from_tel, 'CALLEE_NO'=>$to_hp));

        echo "<script> document.all.cont.innerHTML += '$cnt. $to_hp ($mb_id : $name)<br>'; </script>\n";
        //echo "+";
        flush();
        ob_flush();
        ob_end_flush();
        usleep($sleepsec);
        if ($cnt % $countgap == 0)
        {
            echo "<script> document.all.cont.innerHTML += '<br>'; document.body.scrollTop += 1000; </script>\n";
        }

        // 화면을 지운다... 부하를 줄임
        if ($cnt % $maxscreen == 0)
            echo "<script> document.all.cont.innerHTML = ''; document.body.scrollTop += 1000; </script>\n";
    }
}


# 1개이상 발송된 경우 이력 저장
if ($cnt >= 1) {
    $ml_type = (strlen(iconv("utf-8", "euc-kr", $SMS_MSG)) <= 90) ? 'SMS' : 'MMS';

    $sql = "Insert Into nx_sms_log("
        ."ma_id"
        .", mb_no"
        .", ml_from_tel"
        .", ml_count"
        .", ml_type"
        .", ml_datetime"
        .", ml_ip"
        .") Values("
        ."'" . mres($ma_id) . "'"
        .", '" . mres($member['mb_no']) . "'"
        .", '" . mres($from_tel) . "'"
        .", '" . mres($cnt) . "'"
        .", '" . mres($ml_type) . "'"
        .", now()"
        .", inet_aton('" . mres($_SERVER['REMOTE_ADDR']) . "')"
        .")"
        ;
    sql_query($sql);
}
?>
<script> document.all.cont.innerHTML += "<br><br>총 <?php echo number_format($cnt) ?>건 발송<br><br><font color=crimson><b>[끝]</b></font>"; document.body.scrollTop += 1000; </script>
