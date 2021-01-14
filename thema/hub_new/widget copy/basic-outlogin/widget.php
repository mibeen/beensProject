<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가 

// 버튼컬러
$btn_login = (isset($wset['login']) && $wset['login']) ? $wset['login'] : 'navy';
$btn_logout = (isset($wset['logout']) && $wset['logout']) ? $wset['logout'] : 'navy';

//필요한 전역변수 선언
global $member, $is_member, $at_href, $urlencode;

//add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$widget_url.'/widget.css">', 0);

?>
<div class="basic-outlogin">
	<?php if($is_member) { //Login ?>
		<div class="nx_main_memb after">
			<div class="top">
				<?php 
					// 회원프로필 사진
					$response_pic_json_obj = GetMyProfile_Photo();
					$MY_PIC = $response_pic_json_obj[result][0];
					$_SESSION['MY_PIC'] = $MY_PIC['my_img'];
					$_SESSION['MY_EPIC'] = $MY_PIC['my_eimg'];
	
					$my_pic = $MY_PIC['my_img']; 

					// 서버저장
					if(strpos($my_pic, ".jpg") !== false)
					{
						if(!is_dir(G5_PATH."/data/apms/photo/". substr($member['mb_id'], 0, 2) ."/")) {
							@mkdir(G5_PATH."/data/apms/photo/". substr($member['mb_id'], 0, 2) ."/", 0755);
						}
						$path = G5_PATH."/data/apms/photo/". substr($member['mb_id'], 0, 2) ."/". $member['mb_id'].".jpg";
						copy($my_pic, $path);														// URL로 복사
						//copy("/nas_contents/gseek/" . explode("/upload/", $my_pic)[1], $path);		// 내부에서 복사
					} else {  
						$msg = "jpg 파일이 아닙니다.";
					}  					
				?>
				<?php if($my_pic){ ?>
				  <a href="<?php echo $my_pic; ?>"  class="nx_pimg win_memo" target="_blank">
				  <img src="<?php echo $my_pic; ?>" onerror="this.onerror=null;this.src='<?php echo $MY_PIC['my_eimg']; ?>';">
				  </a><input type="hidden" value="<?php echo $path; ?>"/>
				<?php }else{ ?>
					<p class="nx_pimg win_memo">
					<img src="<?php echo $widget_url.'/img/photo.png';?>" alt="<?php if($msg){echo $msg; } ?>">
					</p>
				<?php } ?>
				<?php /* </a> */ ?>
				<div class="nx_txt_wrap">
					<p class="nx_p1"><?php echo $member['mb_nick'];?>님 환영합니다.</p>
					<ul class="nx_link">
						<?php  /*<li><a href="<?php echo $at_href['edit'];?>">회원정보수정</a></li>*/ ?>
						<li><a href="javascript:win_open2('http://xwww.gseek.4csoft.com/memb/myss.edit.info.pwcheck', '600');">회원정보수정</a></li>
						<?php  /*<li><a href="http://xwww.gseek.4csoft.com/memb/edit.info.pwcheck" target="_blank">회원정보수정</a></li>*/ ?>
						<?php  /*<li><a href="javascript:;" onclick="sidebar_open('sidebar-response');">알림</a></li>*/ ?>
					</ul>
				</div>
			</div>
		</div>

	<?php } else { //Logout ?>
				<form id="basic_outlogin" name="basic_outlogin" method="post" action="<?php echo $at_href['login_check'];?>" autocomplete="off" role="form" class="form" onsubmit="return basic_outlogin_form(this);">
		<input type="hidden" name="url" value="<?php echo $urlencode; ?>">
			<div class="nx_main_memb before">
				<div class="top">
					<h2 class="nx_tit">로그인</h2>
					<div class="main_log">
						<button type="button" onclick="win_open();" class="btn login" tabindex="23">로그인</button> 
						<div class="btn_wrap_right">
							<a href="#" onclick="win_open2('http://xwww.gseek.4csoft.com/memb/myss.join.info', '800');" class="btn join">회원가입</a>
							<a href="#" onclick="win_open2('http://xwww.gseek.4csoft.com/memb/myss.find.pw.input', '600');" class="btn find">비밀번호 찾기</a>
						</div>
					</div>
				</div>
			</div>

		</form>
		
	<?php } //End ?>
</div>