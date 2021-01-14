<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

?>
		<?php if($col_name) { ?>
			<?php if($col_name == "two") { ?>
					</div>
					<div class="at-col at-side nx_page_side<?php echo ($at_set['side']) ? ' pull-left' : ' pull-right';?>">
						<?php include_once($is_side_file); // Side ?>
					</div>
				</div>
			<?php } else { ?>
				</div><!-- .at-content -->
			<?php } ?>
			</div><!-- .at-container -->
		<?php } ?>
	</div><!-- .at-body -->

	<?php if(!$is_main_footer) { ?>
		<footer class="nx_footer_wrap">
			<div class="nx_footer">
				<div class="nx_logo">
				<?php
				$footer_dir = "footer";
				$footer_file = G5_DATA_PATH.'/member/'.$footer_dir.'/footer_logo_img.png';
				if (file_exists($footer_file)) {
					$footer_url = G5_DATA_URL.'/member/'.$footer_dir.'/footer_logo_img.png';
					echo '<img src="'.$footer_url.'" alt="">';
				}
				?>
				</div>
				<div class="nx_f_content">
					<ul class="nx_f_nav">
						<li><a href="<?php echo G5_BBS_URL;?>/page.php?hid=privacy">개인정보처리방침</a></li>
						<li><a href="<?php echo G5_BBS_URL;?>/page.php?hid=provision">이용정책 / 회원약관</a></li> 
						<li><a href="<?php echo G5_BBS_URL;?>/page.php?hid=copyright">저작권 보호정책</a></li>
						<li><a href="<?php echo G5_BBS_URL;?>/page.php?hid=webaccess">웹접근성정책</a></li>
					</ul>
					<?php
					if($config['cf_1'])
					{ 
						echo '<address class="nx_address">';
						echo $config['cf_1'];
						echo '</address>';
					}
					?>
				</div>
				<?php
				/*
				<span id="f_site" class="nx_family_site">
					<a href="javascript:void(0)" title="패밀리사이트">Family Site <span class="ico_select"></span></a>
					<ul class="nx_slt_lst">
						<li><a href="http://www.naver.com" taRget="_blank" title="새창열림">패밀리사이트1</a></li>
						<li><a href="http://www.naver.com" taRget="_blank" title="새창열림">패밀리사이트2 패밀리사이트2</a></li>
						<li><a href="http://www.naver.com" taRget="_blank" title="새창열림">패밀리사이트3</a></li>
					</ul>
				</span>
				*/
				?>
			</div>
		</footer>

		<?php
		/*
		<footer class="at-footer">
			<nav class="at-links">
				<div class="at-container">
					<ul class="pull-left">
						<li><a href="<?php echo G5_BBS_URL;?>/page.php?hid=privacy">개인정보처리방침</a></li>
						<li><a href="<?php echo G5_BBS_URL;?>/page.php?hid=provision">이용정책 / 회원약관</a></li> 
						<li><a href="<?php echo G5_BBS_URL;?>/page.php?hid=noemail">저작권 보호정책</a></li>
						<li><a href="<?php echo G5_BBS_URL;?>/page.php?hid=disclaimer">웹접근성정책</a></li>
					</ul>
					<ul class="pull-right">
						<li><a href="<?php echo G5_BBS_URL;?>/page.php?hid=guide">이용안내</a></li>
						<li><a href="<?php echo $at_href['secret'];?>">문의하기</a></li>
						<li><a href="<?php echo $as_href['pc_mobile'];?>"><?php echo (G5_IS_MOBILE) ? 'PC' : '모바일';?>버전</a></li>
					</ul>
					<div class="clearfix"></div>
				</div>
			</nav>
			<div class="at-infos">
				<div class="at-container">
					<?php if(IS_YC) { // YC5 ?>
						<div class="media">
							<div class="pull-right hidden-xs">
								<!-- 하단 우측 아이콘 -->
							</div>
							<div class="pull-left hidden-xs">
								<!-- 하단 좌측 로고 -->
								<i class="fa fa-leaf"></i>
							</div>
							<div class="media-body">
						
								<ul class="at-about hidden-xs">
									<li><b><?php echo $default['de_admin_company_name']; ?></b></li>
									<li>대표 : <?php echo $default['de_admin_company_owner']; ?></li>
									<li><?php echo $default['de_admin_company_addr']; ?></li>
									<li>전화 : <span><?php echo $default['de_admin_company_tel']; ?></span></li>
									<li>사업자등록번호 : <span><?php echo $default['de_admin_company_saupja_no']; ?></span></li>
									<li><a href="http://www.ftc.go.kr/info/bizinfo/communicationList.jsp" target="_blank">사업자정보확인</a></li>
									<li>통신판매업신고 : <span><?php echo $default['de_admin_tongsin_no']; ?></span></li>
									<li>개인정보관리책임자 : <?php echo $default['de_admin_info_name']; ?></li>
									<li>이메일 : <span><?php echo $default['de_admin_info_email']; ?></span></li>
								</ul>
								
								<div class="clearfix"></div>

								<div class="copyright">
									<strong><?php echo $config['cf_title'];?> <i class="fa fa-copyright"></i></strong>
									<span>All rights reserved.</span>
								</div>

								<div class="clearfix"></div>
							</div>
						</div>
					<?php } else { // G5 ?>
						<div class="at-copyright">
							<strong><?php echo $config['cf_title'];?> <i class="fa fa-copyright"></i></strong>
							All rights reserved.
						</div>
					<?php } ?>
				</div>
			</div>
		</footer>
		*/
		?>
	<?php } ?>
</div><!-- .wrapper -->

<div class="at-go">
	<div id="go-btn" class="go-btn">
		<span class="go-top cursor"><i class="fa fa-chevron-up"></i></span>
		<span class="go-bottom cursor"><i class="fa fa-chevron-down"></i></span>
	</div>
</div>

<script>
function win_open2(url, heights)
{
	//window.open("http://xwww.gseek.4csoft.com/memb/myss.join.info");

	$('#iframe2').attr("src", url);
	$('#iframe2').attr("style", "width:100%; height:"+heights+"px;");
	$('#myModal-lg').attr("style", "width:100%;");
	$("#myModal-lg").modal('show');
}
</script>
<div id="myModal-lg" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
		<div class="modal-body">
			<iframe id="iframe2" frameborder="0"  scrolling="yes"></iframe>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
		</div>
	</div>
  </div>
</div>

<!--[if lt IE 9]>
<script type="text/javascript" src="<?php echo THEMA_URL;?>/assets/js/respond.js"></script>
<![endif]-->

<!-- JavaScript -->
<script>
var sub_show = "<?php echo $at_set['subv'];?>";
var sub_hide = "<?php echo $at_set['subh'];?>";
var menu_startAt = "<?php echo ($m_sat) ? $m_sat : 0;?>";
var menu_sub = "<?php echo $m_sub;?>";
var menu_subAt = "<?php echo ($m_subsat) ? $m_subsat : 0;?>";


</script>
<script src="<?php echo THEMA_URL;?>/assets/bs3/js/bootstrap.min.js"></script>
<script src="<?php echo THEMA_URL;?>/assets/js/sly.min.js"></script>
<script src="<?php echo THEMA_URL;?>/assets/js/custom.js"></script>
<script src="<?php echo G5_URL;?>/gseek/jquery.cookie.js"></script>
<?php if($is_sticky_nav) { ?>
<script src="<?php echo THEMA_URL;?>/assets/js/sticky.js"></script>
<?php } ?>

<?php echo apms_widget('basic-sidebar'); //사이드바 및 모바일 메뉴(UI) ?>

<?php if($is_designer || $is_demo) include_once(THEMA_PATH.'/assets/switcher.php'); //Style Switcher ?>
