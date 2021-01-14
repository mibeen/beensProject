<?php
if($member['mb_level'] == 3){
}else{
$menu["menu990"] = array (
    array('990000', '추가기능', G5_ADMIN_URL.'/evt/evt.list.php', 'evt.list', '/adm/imgs/comm/menu990'),
    // 활성도 통계 http://www.happyjung.com/dataroom/440
    array("990100", "사업관리", ''.G5_ADMIN_URL.'/evt/evt.list.php', 'evt.list'),
    array("990200", "대관관리", ''.G5_ADMIN_URL.'/rent/place_rental_list.php', 'bbs_place_rantal'),
    array("990300", "조직도-부서", ''.G5_ADMIN_URL.'/org_part.list.php', 'org_part_list'),
    array("990310", "조직도-직원", ''.G5_ADMIN_URL.'/org_staff.list.php', 'org_staff_list'),
    array("990400", "뉴스레터 발송", ''.G5_ADMIN_URL.'/newsletter/newsletter.list.php', 'newsletter_list'),
    array("990410", "뉴스레터 구독자", ''.G5_ADMIN_URL.'/newsletter/subscriber.list.php', 'subscriber_list'),
    array("990500", "SMS발송", ''.G5_ADMIN_URL.'/sms_list2.php', 'sms_list2'),
    //array("990550", "SMS발송_DR", ''.G5_ADMIN_URL.'/sms_list_dr.php', 'sms_list_dr'),
    array("990600", "명찰제작", ''.G5_ADMIN_URL.'/nametag/nametag.custom.list.php', 'nametag_custom'),
    array("990700", "게시판 통계", ''.G5_ADMIN_URL.'/static.php', 'nametag_custom')
);

}
?>
