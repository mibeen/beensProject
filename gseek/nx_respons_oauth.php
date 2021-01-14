<?php
include_once("./api.php");

/*
 Array
 (
 [access_token] => 273a5715871bfbbd5f3b3e2dbf9b28bd42ead3e6
 [expires_in] => 3600
 [token_type] => Bearer
 [scope] =>
 [refresh_token] => 462ab2b28b6759ef4d0e5a0843e7655d1ccfbd87
 )
 */

$userid = $_REQUEST["userid"];



if($_REQUEST[access_token]) {
    
    session_start();
    
    $_SESSION['Access_Token'] = $_REQUEST["access_token"];
    $_SESSION['Access_Token_Re'] = $_REQUEST["refresh_token"];
    $_SESSION['Expires_in'] = $_REQUEST["expires_in"];
    $_SESSION['Token_type'] = $_REQUEST["token_type"];
    $_SESSION['Scope'] = $_REQUEST["scope"];
    $_SESSION['Login_Time'] = date("Y-m-d H:i:s");
    
    
    $response_json_obj = GetLogined_Info($response_json_obj['access_token']);
    
    $MESSAGE_ARY = $response_json_obj['message'];
    //	echo '<pre>';
    //	print_r($MESSAGE_ARY);
    //	echo '</pre>';
    $_SESSION['I_CODE'] = $MESSAGE_ARY['I_CODE'];
    $_SESSION['ID'] = $MESSAGE_ARY['I_CODE'];//$MESSAGE_ARY['ID'];
    $_SESSION['NAME'] = $MESSAGE_ARY['NAME'];
    $_SESSION['EMAIL'] = $MESSAGE_ARY['EMAIL'];
    
    // 회원프로필 사진
    /*
     $response_pic_json_obj = GetMyProfile_Photo();
     $MY_PIC = $response_pic_json_obj[result][0];
     $_SESSION['MY_PIC'] = $MY_PIC['my_img'];
     $_SESSION['MY_EPIC'] = $MY_PIC['my_eimg'];
     
     $mb = get_member($_SESSION['I_CODE']);
     */
    
    
    # 가입되지 않았으면 저장
    if(!$member['mb_id'])
    {
        $user_password = sql_password($_SESSION['I_CODE']);
        $sql = "insert into g5_member set mb_id='".$_SESSION['I_CODE']."', mb_password='".$user_password."', mb_nick='".$_SESSION['NAME']."', mb_name='".$_SESSION['NAME']."', mb_email='".$_SESSION['EMAIL']."', mb_level=2, mb_point=1000, mb_datetime = now(), mb_today_login = now(), mb_login_ip = '{$_SERVER['REMOTE_ADDR']}', mb_ip = '{$_SERVER['REMOTE_ADDR']}'";
        sql_query($sql);
        
        $mb = get_member($_SESSION['I_CODE']);
    }
    else
    {
        $sql = "update g5_member set mb_today_login = now(), mb_login_ip = '{$_SERVER['REMOTE_ADDR']}' where  mb_id='".$_SESSION['I_CODE']."'";
        sql_query($sql);
    }
    
    
    # 로그인 성공하면 API로 받은 프로필 이미지를 서버에 저장
    /*
     if(strpos(strtolower($MY_PIC['my_img']), ".jpg") !== false || strpos(strtolower($MY_PIC['my_img']), ".jpeg") !== false || strpos(strtolower($MY_PIC['my_img']), ".gif") !== false || strpos(strtolower($MY_PIC['my_img']), ".png") !== false)
     {
     if(!is_dir(G5_PATH."/data/apms/photo/". substr($MESSAGE_ARY['I_CODE'], 0, 2) ."/")) {
     @mkdir(G5_PATH."/data/apms/photo/". substr($MESSAGE_ARY['I_CODE'], 0, 2) ."/", 0755);
     }
     $path = G5_PATH."/data/apms/photo/". substr($MESSAGE_ARY['I_CODE'], 0, 2) ."/". $MESSAGE_ARY['I_CODE'].".jpg";
     copy($MY_PIC['my_img'], $path);														// URL로 복사
     //copy("/nas_contents/gseek/" . explode("/upload/", $MY_PIC['my_img'])[1], $path);		// 내부에서 복사
     }
     */
    /*
     // 모든 세션 변수 해제
     $_SESSION = array();
     
     // 세션을 없애려면, 세션 쿠키도 지웁니다.
     // 주의: 이 동작은 세션 데이터뿐이 아닌, 세션 자체를 파괴합니다!
     if (isset($_COOKIE[session_name()])) {
     setcookie(session_name(), '', time()-42000, '/');
     }
     
     session_destroy();
     
     echo "<hr/><hr/><hr/>".$_SESSION['I_CODE'] ;
     */
    ?>
	<script>
	if(opener)
	{	
		//var url =opener.document.location.href;
		var url = "<?php echo G5_URL.'/gseek/nx_login_check.php'; ?>";
		opener.document.location.href = url;
		window.close();
	}else{
		var url = "<?php echo G5_URL.'/gseek/nx_login_check.php'; ?>";
		//opener.document.location.href = url;
		parent.document.location.href = url+"?url="+parent.document.location.href;
	}
	</script>
	<?php
} 
else {
	?>
	<script>
		alert("로그인하지 못 했습니다.\n로그인 페이지로 이동합니다.");

		document.location.href = "https://www.gillapi.or.kr/auth/authorize.php?response_type=code&client_id=<?php echo GSK_client_id;?>&state=<?php echo GSK_state;?>&redirect_uri=<?php echo GSK_redirect_uri;?>";
	</script>
	<?php 
}
?>