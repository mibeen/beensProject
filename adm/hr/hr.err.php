<?php
# set : variables
$EP_IDX = (isset($_POST['EP_IDX']) && $_POST['EP_IDX'] != '') ? $_POST['EP_IDX'] : $_GET['EP_IDX'];

//echo"<script>console.log('PHP_Console1: ".$EP_IDX."')</script>";

# re-define : variables
$EP_IDX				= (CHK_NUMBER($EP_IDX) > 0) ? (int)$EP_IDX : "";


//$sub_menu = ($EP_IDX != "") ? '980100' : '990100';
$sub_menu = '985100';

//echo"<script>console.log('PHP_Console2: ".$EP_IDX."')</script>";

if ($EP_IDX != "") {
    $epTail = "EP_IDX={$EP_IDX}"
    . "&";
}
else {
    $epTail = "";
}


# 공모업체 관리자 권한 부여
if ($EP_IDX != "" && $member['mb_level'] == 6) {
    $sql = "Select Count(*) As cnt"
        . " , (Select EP_E_DATE From NX_EVENT_HR Where EP_IDX = '" . mres($EP_IDX) . "') As EP_E_DATE"
            . " From NX_EVENT_HR_MEMBER As EPM"
                . " Where EPM.EP_IDX = '" . mres($EP_IDX) . "'"
                    . " 	And EPM.mb_id = '" . mres($member['mb_id']) . "'"
                        ;
                        $db_epm = sql_fetch($sql);
                        
                        if ($db_epm['cnt'] > 0) {
                            # 공모 사업 종료일이 지났으면 읽기 권한만
                            if (date('Y-m-d') > $db_epm['EP_E_DATE']) {
                                $auth['985100'] = 'r';
                            }
                            else {
                                $auth['985100'] = 'r,w,d';
                            }
                        }
}
?>
