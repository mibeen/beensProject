<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/common.php");
?>
<?php 

if(!G5_IS_MOBILE) { //PC일 때만 출력 ?>
	<div class="hidden-sm hidden-xs">
		<!-- 로그인 시작 -->
		<div class="widget-box">
			<?php echo apms_widget('basic-outlogin'); //외부로그인 ?>
		</div>
		<!-- 로그인 끝 -->
	</div>
<?php } 

?>