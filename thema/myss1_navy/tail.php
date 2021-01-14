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

	<footer class="nx-footer-wrap">
		<ul class="nx-f-nav">
			<li><a href="">사이트맵</a></li>
			<li><a href="">뷰어다운로드</a></li>
			<li><a href="">이용약관</a></li>
			<li><a href="">웹접근성 정책</a></li>
			<li><a href="">저작권 정책</a></li>
			<li><a href="">이메일 수집거부</a></li>
			<li><a href="">개인정보처리방침</a></li>
		</ul>
		<div class="nx-footer">
			<div class="nx-f-logo">
			<?php
			$footer_dir = "footer";
			$footer_file = G5_DATA_PATH.'/member/'.$footer_dir.'/footer_logo_img.png';
			if (file_exists($footer_file)) {
				$footer_url = G5_DATA_URL.'/member/'.$footer_dir.'/footer_logo_img.png';
				echo '<img src="'.$footer_url.'" alt="">';
			}
			?>
			</div>
			<div class="nx-f-mark">
				<img src="<?php echo(THEMA_URL)?>/assets/imgs/comm/f_logo2.png" alt="" width="86">
				<img src="<?php echo(THEMA_URL)?>/assets/imgs/comm/f_logo3.png" alt="" width="111">
			</div>
			<div class="nx-f-content">
				<?php
				if($config['cf_1'])
				{ 
					echo '<address class="nx-address">';
					echo $config['cf_1'];
					echo '</address>';
				}
				?>
			</div>
		</div>
	</footer>

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
