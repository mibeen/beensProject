<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once(THEMA_PATH.'/assets/thema.php');
?>

<div id="thema_wrapper" class="wrapper <?php echo $is_thema_layout;?> <?php echo $is_thema_font;?>">

	<!-- PC Header -->
	<header class="nx_pc_header_wrap">
		<div class="pc-header">
			<div class="at-container">
				<!-- PC Logo -->
				<div class="header-logo">
					<a href="<?php echo $at_href['home'];?>">
						<?php
						$footer_dir = "mlogo";
						$footer_file = G5_DATA_PATH.'/member/'.$footer_dir.'/mb_mlogo.png';
						if (file_exists($footer_file)) {
							$footer_url = G5_DATA_URL.'/member/'.$footer_dir.'/mb_mlogo.png';
							#echo '<img src="'.$footer_url.'" alt="">';
							echo '<img src="'.THEMA_URL.'/assets/images/common/logo.jpg" alt="">';
						}
						else
						{
							echo $page_title;
						}
						?>
					</a>
				</div>
				<!-- PC Search -->
				<div class="header-search" style="display: none;">
					<form name="tsearch" method="get" onsubmit="return tsearch_submit(this);" role="form" class="form">
					<input type="hidden" name="url"	value="<?php echo (IS_YC) ? $at_href['isearch'] : $at_href['search'];?>">
						<div>
							<input type="text" name="stx" value="<?php echo $stx;?>">

								<button type="submit" class="btn btn-sm">검색</button>

						</div>
					</form>
				</div>
				<div class="nx_header_memb" style="display: none;">
					<nav class="top-nav">

					<?php include(G5_PATH. "/gseek/nx_gseek_login.php"); ?>
					<?php include(G5_PATH. "/gseek/nx_gseek_menu.php"); ?>

					<?php
					if($is_member) {
						?>
						<?php if($member['admin']) { ?>
						<a href="<?php echo G5_ADMIN_URL;?>">관리</a>
						<?php } ?>
						<?php /*<a href="<?php echo $at_href['logout'];?>">로그아웃</a>*/ ?>
						<a onclick="winlogout('<?php echo $at_href['logout'];?>');">로그아웃</a>
						<script>
							function winlogout(url)
							{
								document.location.href = url;
							}
						</script>
						<?php
					} else {
						?>
						<?php /*
						<a href="<?php echo $at_href['login'];?>" onclick="sidebar_open('sidebar-user'); return false;">로그인</a>
						<a href="<?php echo $at_href['reg'];?>">회원가입</a>
						*/ ?>
						<?php
					}
					?>
			      <a href="/bbs/board.php?bo_table=calendar"><img src="<?php echo THEMA_URL ?>/assets/images/common/calendar.png" alt="일정 바로가기"></a>
			    </nav>
				</div><!--
				<a href="javascript:;" onclick="sidebar_open('sidebar-search');" class="nx_m_top_sch"><i class="fa fa-search fa-lg"></i></a>-->
				<a href="javascript:;" onclick="sidebar_open('sidebar-menu');" class="nx_m_menu"></a>

				<div class="clearfix"></div>
			</div>
		</div>
	</header>

	<!-- Mobile Header -->
	<header class="nx_mobile_header_wrap">
		<div class="m-header">
			<div class="at-container">
				<div class="header-wrap">
					<div class="header-logo en">
						<!-- Mobile Logo -->
						<a href="<?php echo $at_href['home'];?>">
							<img src="<?php echo(THEMA_URL)?>/assets/img/logo.png" alt="">
						</a>
					</div>
					<!-- <div class="header-icon">
						<a href="javascript:;" onclick="sidebar_open('sidebar-user');">
							<i class="fa fa-user"></i>
						</a>
					</div> -->
					<!-- <div class="header-icon">
						<a href="javascript:;" onclick="sidebar_open('sidebar-search');">
							<i class="fa fa-search"></i>
						</a>
					</div> -->
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
	</header>

	<div class="nx_header_spacer"></div>

	<!-- Menu -->
	<nav class="at-menu">
		<!-- PC Menu -->
		<div class="pc-menu">
			<!-- Menu Button & Right Icon Menu -->
			<div class="at-container">

			</div>
			<?php include_once(THEMA_PATH.'/menu.php');	// 메뉴 불러오기 ?>
			<div class="clearfix"></div>
			<div class="nav-back"></div>
		</div><!-- .pc-menu -->

		<!-- PC All Menu -->
		<div class="pc-menu-all" style="display: none;">
			<div id="menu-all" class="collapse">
				<div class="at-container table-responsive">
					<table class="table">
					<tr>
					<?php
						$az = 0;

						for ($i=1; $i < $menu_cnt; $i++) {

							if(!$menu[$i]['gr_id']) continue;

							if($menu[$i]['gr_id'] == "gseek")
								echo $_Menu_tag;

							// 줄나눔
							if($az && $az%$is_allm == 0) {
								echo '</tr><tr>'.PHP_EOL;
							}
					?>
						<td class="<?php echo $menu[$i]['on'];?>">
							<a class="menu-a" href="<?php echo $menu[$i]['href'];?>"<?php echo $menu[$i]['target'];?>>
								<?php echo $menu[$i]['name'];?>
								<?php if($menu[$i]['new'] == "new") { ?>
									<i class="fa fa-bolt new"></i>
								<?php } ?>
							</a>
							<?php if($menu[$i]['is_sub']) { //Is Sub Menu ?>
								<div class="sub-1div">
									<ul class="sub-1dul">
									<?php for($j=0; $j < count($menu[$i]['sub']); $j++) { ?>

										<?php if($menu[$i]['sub'][$j]['line']) { //구분라인 ?>
											<li class="sub-1line"><a><?php echo $menu[$i]['sub'][$j]['line'];?></a></li>
										<?php } ?>

										<li class="sub-1dli <?php echo $menu[$i]['sub'][$j]['on'];?>">
											<a href="<?php echo $menu[$i]['sub'][$j]['href'];?>" class="sub-1da<?php echo ($menu[$i]['sub'][$j]['is_sub']) ? ' sub-icon' : '';?>"<?php echo $menu[$i]['sub'][$j]['target'];?>>
												<?php echo $menu[$i]['sub'][$j]['name'];?>
												<?php if($menu[$i]['sub'][$j]['new'] == "new") { ?>
													<i class="fa fa-bolt sub-1new"></i>
												<?php } ?>
											</a>
										</li>
									<?php } //for ?>
									</ul>
								</div>
							<?php } else if($menu[$i]['gr_id'] == "gseek")
							echo $Menu_Html_Tag; ?>
						</td>
					<?php $az++; } //for ?>
					</tr>
					</table>
					<div class="menu-all-btn">
						<div class="btn-group">
							<a class="btn btn-lightgray" href="<?php echo $at_href['main'];?>"><i class="fa fa-home"></i></a>
							<a href="javascript:;" class="btn btn-lightgray" data-toggle="collapse" data-target="#menu-all"><i class="fa fa-times"></i></a>
						</div>
					</div>
				</div>
			</div>
		</div><!-- .pc-menu-all -->

		<!-- Mobile Menu -->
		<?php
		/*
		<div class="m-menu">
			<?php include_once(THEMA_PATH.'/menu-m.php');	// 메뉴 불러오기 ?>
		</div><!-- .m-menu -->
		*/
		?>
	</nav><!-- .at-menu -->

	<div class="clearfix"></div>

	<?php
	/*
	if($page_title) { // 페이지 타이틀 ?>
		<div class="at-title">
			<div class="at-container">
				<div class="page-title en">
					<strong<?php echo ($bo_table) ? " class=\"cursor\" onclick=\"go_page('".G5_BBS_URL."/board.php?bo_table=".$bo_table."');\"" : "";?>>
						<?php echo $page_title;?>
					</strong>
				</div>
				<?php if($page_desc) { // 페이지 설명글 ?>
					<div class="page-desc hidden-xs">
						<?php echo $page_desc;?>
					</div>
				<?php } ?>
				<div class="clearfix"></div>
			</div>
		</div>
	<?php }
	*/
	?>

	<?php
		$is_search = (strpos($_SERVER['REQUEST_URI'], "bbs/search.php") !== false) ? true : false;
	?>
	<div class="at-body<?php if($is_main) {echo(' nx_main_bg');}?>">
		<?php if($col_name) { ?>
			<div class="at-container">
			<?php if($col_name == "two" && !$is_search) { ?>
				<div class="row at-row">
					<div class="at-col at-main nx_page_content<?php echo ($at_set['side']) ? ' pull-right' : ' pull-left';?>">
			<?php } else { ?>
				<div class="row at-row">
					<div class="at-col at-main nx_page_content pull-right">
						<div class="at-content">
			<?php } ?>
		<?php } ?>

		<?php
		if($page_title) {// 페이지 타이틀  FDF3F3

			if(isset($board_color)){
				// 마우스 호버했을 때 컬러
				$hover_color = color_luminance($board_color, '1');
				// 더 하얗게 만드는 컬러
				$white_color = color_change($hover_color, '-2','21','21');
			}

			#테마 컬러 설정.
			$stylesheet = "
				<style>
					.nx-sub-wrap{border-top: 1px solid {$board_color}}
					.list-container .list-item{ border-top: 4px solid {$board_color};}
					.list-container .list-item:hover{background: {$hover_color};}
					.list-wrap .list-container h2 .nx-list-date {color: {$board_color};}
					.btn.btn-color{background: {$board_color}; border: 1px solid {$board_color};}
					.view-top a{color: {$board_color}; border: 1px solid {$board_color};}
					.view-area{background-color: {$white_color};}
					.view-area .nx-date{color: {$board_color};}
				</style>
			";
			add_stylesheet($stylesheet, 1);

			?>
				<div class="nx-sub-wrap">
					<div class="nx-sub">
						<p class="nx_page_tit"><?php echo $page_title;?></p>
						<p class="nx_page_desc"><?php echo $page_desc;?></p>
					</div>
				</div>
			<?php
		}
		?>
