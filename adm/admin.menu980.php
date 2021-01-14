<?php
if($member['mb_level'] == 3){
}else{
$menu["menu980"] = array (
    array('980000', '공모사업', G5_URL.(($is_admin) ? '/adm/evt' : '/project').'/project.list.php', 'project.list', '/adm/imgs/comm/menu980'),
    // 활성도 통계 http://www.happyjung.com/dataroom/440
    array("980100", "공모사업 목록", ''.G5_URL.(($is_admin) ? '/adm/evt' : '/project').'/project.list.php', 'project.list'),
    array("980200", "사업관리자 등록", ''.G5_ADMIN_URL.'/member_list.php?mode=project', 'member'),
    array("980300", "전체 현황", ''.G5_URL.(($is_admin) ? '/adm/evt' : '/project').'/project.evt.list.php', 'project.evt.list'),
);

}
?>
