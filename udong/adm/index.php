<?php
    include_once ('../../common.php');

    // 접근 권한 검사
    if (!$member['mb_id'])
    {
        goto_url(G5_URL.'/udong/adm/login.php');
    }
    else {
        goto_url(G5_URL.'/udong/adm/place.list.php');   
    }
?>
