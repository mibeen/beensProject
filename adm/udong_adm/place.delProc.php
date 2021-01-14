<?php
    $sub_menu = "960100";
    include_once('./_common.php');
    include_once('./place.err.php');


    $lp_idx = $_POST['lp_idx'];
    
    # 시설 관리자 권한 부여
    if ($member['mb_level'] == 3) {
        $auth['960100'] = 'd';
    }
    auth_check($auth[$sub_menu], 'd');

    // check_admin_token();


    # chk : recode exist.
    $sql = "select Count(*) as cnt from local_place Where lp_ddate is null and lp_idx = '" . mres($lp_idx) . "'";
    $lp = sql_fetch($sql);

    if ($lp['cnt'] == 0) {
        echo_json(array(
            'success'=>(boolean)false, 
            'msg'=>'존재하지 않는 데이터입니다.'
        ));
    }


    # set : 삭제일
    $sql = "update local_place 
                set lp_ddate = now()
                    , lp_dip = '" . $_SERVER['REMOTE_ADDR'] . "'
                where lp_idx = '" . mres($lp_idx) . "'"
        ;
    sql_query($sql);


    echo_json(array(
        'success'=>(boolean)true, 
        'redir'=>'place.list.php', 
        'msg'=>"정상적으로 삭제되었습니다."
    ));
?>
