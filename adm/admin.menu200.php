<?php
if($member['mb_level'] == 3){
}else{
	$menu['menu200'] = array (
		array('200000', '회원관리', G5_ADMIN_URL.'/member_list.php', 'member', '/adm/imgs/comm/menu200'),
		array('200100', '회원관리', G5_ADMIN_URL.'/member_list.php', 'mb_list'),
		array('200300', '회원메일발송', G5_ADMIN_URL.'/mail_list.php', 'mb_mail'),
		array('200310', '회원SMS발송', G5_ADMIN_URL.'/sms_list.php', 'mb_sms'),
		array('200600', '관리회원 접속로그', G5_ADMIN_URL.'/manager_log.php', 'manager_log'),
		array('200610', '개인정보 접근이력', G5_ADMIN_URL.'/privacy_log.php', 'privacy_log'),
		array('200800', '접속자집계', G5_ADMIN_URL.'/visit_list.php', 'mb_visit', 1),
		array('200810', '접속자검색', G5_ADMIN_URL.'/visit_search.php', 'mb_search', 1),
		array('200820', '접속자로그삭제', G5_ADMIN_URL.'/visit_delete.php', 'mb_delete', 1),
		//array('200200', '포인트관리', G5_ADMIN_URL.'/point_list.php', 'mb_point'),
		#array('200900', '투표관리', G5_ADMIN_URL.'/poll_list.php', 'mb_poll')
	);
	
}
?>