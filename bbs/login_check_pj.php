<?php
include_once('./_common.php');
include_once G5_LIB_PATH . '/nx.lib.php';
include_once G5_PLUGIN_PATH . '/nx/class.NX_LOGIN_FAIL.php';

$g5['title'] = "로그인 검사";    


include "login_check.inc.php";


if ($url) {
    // url 체크
    check_url_host($url);

    $link = urldecode($url);
    // 2003-06-14 추가 (다른 변수들을 넘겨주기 위함)
    if (preg_match("/\?/", $link))
        $split= "&amp;";
    else
        $split= "?";

    // $_POST 배열변수에서 아래의 이름을 가지지 않은 것만 넘김
    foreach($_POST as $key=>$value) {
        if ($key != 'mb_id' && $key != 'mb_password' && $key != 'x' && $key != 'y' && $key != 'url') {
            $link .= "$split$key=$value";
            $split = "&amp;";
        }
    }
} else  {
    $link = G5_URL.'/project/project.list.php';
}


# 비밀번호 변경 90일 초과 or 초기 비밀번호인 경우 비밀번호 변경 페이지로 이동.
$sql = "Select mb_password_date From {$g5['member_table']} Where mb_id = '{$mb['mb_id']}'";
$row = sql_fetch($sql);
$pw_date = $row['mb_password_date'];

$limit_date = date('Y-m-d', strtotime("-90 days"));

if($pw_date == '0000-00-00' || $pw_date == ''){
    alert(
        '초기 비밀번호를 변경 후 사용해주세요.'
        , G5_URL.'/project/passwd.php'
    );
} else if($pw_date < $limit_date) {
    alert(
        '비밀번호를 변경한지 90일이 지났습니다. 비밀번호를 변경해주세요.'
        , G5_URL.'/project/passwd.php'
    );
}


goto_url($link);
?>
