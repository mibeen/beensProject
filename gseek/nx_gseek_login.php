<?php
session_start();

include_once(G5_PATH."/gseek/api.php");

if($_SESSION['I_CODE']) {

$member['mb_id'] = GSK_I_ID;
$member['mb_password'] = GSK_I_CODE;
$member['mb_email'] = GSK_I_ID;
$member['mb_nick'] = GSK_I_NAME;
$member['mb_name'] = GSK_I_NAME;
$member['mb_level'] = 2;
$member['mb_point'] = 1000;

/*
unset($member);
$member = array(
			'mb_id'       => GSK_I_ID,
			'mb_password' => GSK_I_CODE,
			'mb_email'    => GSK_I_ID,
			'mb_nick'     => GSK_I_NAME,
			'mb_name'     => GSK_I_NAME,
			'mb_level'    => 2,
			'mb_point'    => 1000
		);
*/

set_session('ss_oauth_member_no', 'fcb_'.GSK_I_ID);
set_session('ss_oauth_member_fcb_Test_info', $member);


/*
$mb = get_member(GSK_I_CODE);
// 가입되지 않았으면 저장
if(!$member['mb_id'])
{
	$user_password = sql_password($_SESSION['I_CODE']);
	//$sql = "insert into g5_member set mb_id='".GSK_I_ID."', mb_password='".$user_password."', mb_nick='".GSK_I_NAME."', mb_name='".GSK_I_NAME."', mb_level=2, mb_point=1000, mb_datetime = now(), mb_today_login = now(), mb_login_ip = '{$_SERVER['REMOTE_ADDR']}', mb_ip = '{$_SERVER['REMOTE_ADDR']}'";
	$sql = "insert into g5_member set mb_id='".GSK_I_CODE."', mb_password='".$user_password."', mb_nick='".GSK_I_NAME."', mb_name='".GSK_I_NAME."', mb_email='".GSK_I_EMAIL."', mb_level=2, mb_point=1000, mb_datetime = now(), mb_today_login = now(), mb_login_ip = '{$_SERVER['REMOTE_ADDR']}', mb_ip = '{$_SERVER['REMOTE_ADDR']}'";
	sql_query($sql);

	//$mb = get_member(GSK_I_ID);
	$mb = get_member(GSK_I_CODE);
}
else
{
	//$sql = "update g5_member set mb_today_login = now(), mb_login_ip = '{$_SERVER['REMOTE_ADDR']}' where  mb_id='".GSK_I_ID."'";
	$sql = "update g5_member set mb_today_login = now(), mb_login_ip = '{$_SERVER['REMOTE_ADDR']}' where  mb_id='".GSK_I_CODE."'";
	sql_query($sql);
}
*/
/*
// 회원아이디 세션 생성
set_session('ss_mb_id', $mb_id);

// FLASH XSS 공격에 대응하기 위하여 회원의 고유키를 생성해 놓는다. 관리자에서 검사함 - 110106
set_session('ss_mb_key', md5($mb['mb_datetime'] . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']));

set_session('ck_mb_id', '');
set_session('ck_auto', '');
*/

}

/*
if($oauth_mb_no = get_session('ss_oauth_member_no')) {
    $member = get_session('ss_oauth_member_'.$oauth_mb_no.'_info');
    $is_member = true;
    $is_guest  = false;
}else{
*/
if(!$_SESSION['ss_mb_id'] || !$member['mb_id']){

	//session_destroy();
?>
<script>
//<![CDATA[
function login_win()
{
	// mobile
	if ($(window).width() < 992) {
		fn_member(1);
	}
	// else
	else {
		win_open();
	}
}
function win_open(iframe)
{
//	var auth_win = window.open('<?php echo GSK_Auth_URL;?>?response_type=code&client_id=<?php echo GSK_client_id;?>&client_secret=<?php echo GSK_client_secret;?>&state=<?php echo GSK_state;?>&redirect_uri=<?php echo GSK_redirect_uri;?>', "_blank", 'width=500,height=600');

	iframe = typeof iframe == 'undefined' ? 'iframe1' : iframe;

	//var url = "<?php echo GSK_Auth_URL;?>?response_type=code&client_id=<?php echo GSK_client_id;?>&state=<?php echo GSK_state;?>&redirect_uri=<?php echo GSK_redirect_uri;?>";
	var url = "<?php echo GSK_Auth_URL;?>?response_type=code&client_id=<?php echo GSK_client_id;?>&client_secret=<?php echo GSK_client_secret;?>&state=<?php echo GSK_state;?>&redirect_uri=<?php echo GSK_redirect_uri;?>";

	$('#' + iframe).attr("src", url);
	$('#modal-body').attr("style", "width:100%; height:530px;");
	$('#' + iframe).attr("style", "width:100%; height:500px;");
	$("#myModal").modal('show');
	<?php # ios11 modal 내부 input 이슈 처리  ?>
	$('#myModal').modal('show').on('shown.bs.modal', function (e) {
		$('body').addClass('body-fixed');
	});
	$('#myModal').modal().on('hidden.bs.modal', function (e) {
		$('body').removeClass('body-fixed');
	});
}

function win_open1()
{
	var url = "<?php echo GSK_Auth_URL;?>?response_type=code&client_id=<?php echo GSK_client_id;?>&state=<?php echo GSK_state;?>&redirect_uri=<?php echo GSK_redirect_uri;?>";

	var auth_win = window.open(url, "_blank", 'width=500,height=600');
}
//]]>
</script>
<div id="myModal" class="modal fade" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="z-index:10001;">
  <div class="modal-dialog">
    <div class="modal-content">
		<div class="modal-body">
		<iframe id="iframe1" frameborder="0" scrolling="no"></iframe>
		</div>
		<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
	  </div>
    </div>
  </div>
</div>

<?php /*
<a onclick="win_open();" >로그인</a>
<a onclick="win_open2('<?php echo(GSK_www_URL)?>/memb/myss.join.info', '800');">회원가입</a>
*/ ?>
<?php }?>
