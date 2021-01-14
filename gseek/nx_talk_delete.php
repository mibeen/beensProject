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
	alert("Oauth 2.0 로그인을 해주세요.");
	parent.winPopup("<?php echo GSK_Auth_URL;?>?response_type=code&client_id=<?php echo GSK_client_id;?>&state=<?php echo GSK_state;?>&redirect_uri=<?php echo GSK_redirect_uri;?>";);
</script>	
<?php
}else{
	switch($_REQUEST['t'])
	{
		case "talk":
			$result_ary = SetTalkDelete();
			break;
		case "sugang":
			$result_ary = SetSugangDelete();
			break;
	}



//if($result_ary[status] == "success")
if($result_ary['result'][0]['success'])
	$msg = "삭제했습니다";
else
	$msg = "삭제 실패 했습니다.";
/*
		echo 'REQUEST_API_KEY => '. GSK_client_id."<br/>";
	    echo 'REQUEST_API_CODE => learn_read<br/>';
		echo 'SG_CODE => '. $SG_CODE."<br/>";
		echo 'REQUEST_DATA =>  study_counsel_add'."<br/>";
		echo 'SC_CONT => '. $SC_CONT."<br/>";
		echo 'ck_I_CODE => '. GSK_I_CODE	."<br/>";
*/
?>
<script>
alert('<?php echo $msg; ?>');
parent.document.location.href = parent.document.location.href;
</script>

<?php } ?>