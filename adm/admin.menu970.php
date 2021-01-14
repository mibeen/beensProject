<?php
if($member['mb_level'] == 3){
}else{
$menu["menu970"] = array (
    array('970000', '우리동네 학습공간', G5_URL.(($is_admin) ? '/adm/udong' : '/udong-mng').'/place.list.php', 'place.list', '/adm/imgs/comm/menu980'),
    array("970100", "학습공간 관리", ''.G5_URL.(($is_admin) ? '/adm/udong' : '/udong-mng').'/place.list.php', 'place.list'),
    array("970200", "시설관리자 등록", ''.G5_ADMIN_URL.'/member_list.php?mode=udong', 'member'),
    array("970300", "전체 현황", ''.G5_URL.(($is_admin) ? '/adm/udong' : '/udong-mng').'/req.timeline.php', 'req.timeline'),
);

}
?>
