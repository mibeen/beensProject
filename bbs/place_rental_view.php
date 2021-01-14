<?php
include_once('./_common.php');
include_once(G5_EDITOR_LIB);

// if($is_guest)
//     alert('회원이시라면 로그인 후 이용해 보십시오.', './login.php?url='.urlencode(G5_BBS_URL.'/place_rental_view.php?pm_id='.$pm_id));

$qaconfig = get_qa_config();

$g5['title'] = '대관 현황';
include_once('./place_rental_head.php');

$skin_file = $qa_skin_path.'/view.skin.php';

if(is_file($skin_file)) {
    if (isset($_REQUEST['sar']) && $_REQUEST['sar']) {
        $sar = preg_replace('/[^0-9]/', '', $sar);
        if ($sar)
            $qstr .= '&amp;sar=' . urlencode($sar);
    } else {
        $sar = '';
    }

    if (isset($_REQUEST['sal']) && $_REQUEST['sal'])  { // search order (검색 배열)
        $sal = preg_match("/^(1|2)$/i", $sal) ? $sal : '';
        if ($sal)
            $qstr .= '&amp;sal=' . urlencode($sal);
    } else {
        $sal = 1;
    }

    if (isset($_REQUEST['sco']) && $_REQUEST['sco'])  { // search order (검색 배열)
        $sco = preg_match("/^(1|2|3)$/i", $sco) ? $sco : '';
        if ($sco)
            $qstr .= '&amp;sco=' . urlencode($sco);
    } else {
        $sco = 1;
    }
    
    include_once('./place_rental_view_get_data.php');


    include_once($skin_file);
} else {
    echo '<div>'.str_replace(G5_PATH.'/', '', $skin_file).'이 존재하지 않습니다.</div>';
}

# 메뉴 표시에 사용할 변수 
$_gr_id = 'gseek';  // 게시판 그룹 ID - 현재 메뉴표시에 사용
$pid = ($pid) ? $pid : 'rentlist';   // Page ID

include "../page/inc.page.menu.php";

include_once('./qatail.php');
?>