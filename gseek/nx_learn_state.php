<?php
include_once("./api.php");

///테스트를 위해 삭제
// $_SESSION['I_CODE'] = "596d706fd6640";
// define('GSK_I_CODE', $_SESSION['I_CODE']);



if($member['mb_id'] == "" || GSK_I_CODE == "GSK_I_CODE" || GSK_I_CODE == "" || GSK_I_CODE == null)
{
	//session_destroy();
?>
<script>
	alert("로그인을 해주세요.");
	document.location.href = "<?php echo GSK_Auth_URL;?>?response_type=code&client_id=<?php echo GSK_client_id;?>&state=<?php echo GSK_state;?>&redirect_uri=<?php echo GSK_redirect_uri;?>";
	
</script>	
<?php
}else{
	//hjpark hjpark1234 GSK_I_CODE=596d706fd6640
	if(!$_REQUEST['ls'])
	{
		$result_ary = GetSugangsincheong_Event();

		//if($result_ary[status] == "success")
		//if($result_ary['result'][0]['success'])
		//{
	?>
	<script>
		opener.window.document.location.href = opener.window.document.location.href;
		window.close();
	</script>
	<?php
		//}
	}
	else
	{
		$url = $_REQUEST[GO_URL].$_SERVER["REQUEST_URI"];
	?>
	<script>
		<?php //echo $url."<br/><br/>"; ?>
		window.onload = changeScreenSize;

		 	
    function changeScreenSize() {        
        window.resizeTo(screen.width,screen.height);

		window.document.location.href = "<?php echo $url; ?>";
    }
	</script>
	<?php
	}
}
?>