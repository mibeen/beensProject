<?php
if($member['mb_level'] == 3){
    $menu["menu960"] = array (
        array('960000', '우리동네 학습공간', G5_URL.(($is_admin) ? '/adm/udong_adm' : '/udong/adm').'/place.list.php', 'place.list', '/adm/imgs/comm/menu980'),
        array("960100", "학습공간 관리", ''.G5_URL.(($is_admin) ? '/adm/udong_adm' : '/udong/adm').'/place.list.php', 'place.list'),
        array("960200", "시설관리자 등록", ''.G5_URL.(($is_admin) ? '/adm/udong_adm' : '/udong/adm').'/member_list.php?mode=udong_adm', 'member'),
        array("960300", "전체 현황", ''.G5_URL.(($is_admin) ? '/adm/udong_adm' : '/udong/adm').'/req.timeline.php', 'req.timeline')
    );
    
}else{}

?>
