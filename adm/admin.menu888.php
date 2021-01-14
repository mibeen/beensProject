<?php
if($member['mb_level'] == 3){
}else{
	if(!USE_PARTNER) return;
	@include_once(G5_ADMIN_PATH.'/apms_admin/apms.pmenu.php');
	
}
?>