<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// 위젯 대표아이디 설정
$wid = 'CMB';

// 게시판 제목 폰트 설정
$font = 'font-22';

// 게시판 제목 하단라인컬러 설정 - red, blue, green, orangered, black, orange, yellow, navy, violet, deepblue, crimson..
$line = 'navy';

// 사이드 위치 설정 - left, right
$side = ($at_set['side']) ? 'left' : 'right';

?>
<style>
	.widget-index .at-main,
	.widget-index .at-side { padding-bottom:0px; }
	.widget-index .div-title-underbar { margin-bottom:15px; }
	.widget-index .div-title-underbar span { padding-bottom:4px; }
	/* .widget-index .div-title-underbar span b { font-weight:500; } */
	.widget-index .widget-img img { display:block; max-width:100%; /* 배너 이미지 */ }
	.widget-box { margin-bottom:15px; }
</style>

<div class="at-container widget-index clearfix">

	<div class="h20"></div>

	<?php echo apms_widget('basic-title', $wid.'-wt1', 'height=260px', 'auto=0'); //타이틀 ?>

	<div class="row at-row">
		<!-- 메인 영역 -->
		<div class="col-md-8 at-col at-main">

			<!-- 시화전 시작-->
			<div class="nx_widget_box">
				<div class="div-title-underbar clearfix">
					<a href="<?php echo G5_BBS_URL;?>/board.php?bo_table=pnp" class="pull-right nx_btn2">더보기</a>
					<span class="div-title <?php echo $font;?>">
						<b>시화전</b>
					</span>
				</div>
				<div class="widget-box">
					<?php //echo apms_widget('basic-post-sero', $wid.'-wm60', 'icon={아이콘:caret-right} date=1 center=1 strong=1,2'); ?>
					<?php //echo apms_widget('basic-post-gallery', $wid.'-wm600', 'center=1'); ?>
					<?php echo apms_widget('basic-post-slider', $wid.'-wm1', 'center=1'); ?>				
				</div>
			</div>
			<!-- 시화전 끝-->

			<!-- 이미지 배너 시작 -->	
			<!-- <div class="widget-box widget-img">
				<a href="#배너이동주소">
					<img src="<?php echo THEMA_URL;?>/assets/img/banner.jpg">
				</a>
			</div> -->
			<!-- 이미지 배너 끝 -->	

			<?php
			/* (TEMP) 모임 숨김
			<div class="row">
				<div class="col-sm-6 nx_widget_box res_sm">

					<!-- 학습자 모임 시작 -->
					<div class="div-title-underbar clearfix">
						<a href="<?php echo G5_BBS_URL;?>/board.php?bo_table=learners" class="pull-right nx_btn2">더보기</a>
						<span class="div-title <?php echo $font;?>">
							<b>학습자 모임</b>
						</span>
					</div>
					<div class="widget-box">
						<?php echo apms_widget('basic-post-list', $wid.'-wm8'); ?>
					</div>
					<!-- 학습자 모임 끝 -->

				</div>

				<div class="col-sm-6 nx_widget_box res_sm">

					<!-- 강사 모임 시작 -->
					<div class="div-title-underbar clearfix">
						<a href="<?php echo G5_BBS_URL;?>/board.php?bo_table=tutors" class="pull-right nx_btn2">더보기</a>
						<span class="div-title <?php echo $font;?>">
							<b>강사 모임</b>
						</span>
					</div>
					<div class="widget-box">
						<?php echo apms_widget('basic-post-list', $wid.'-wm7'); ?>
					</div>
					<!-- 강사 모임 끝 -->

				</div>
			</div>

			<div class="row">
				<div class="col-sm-6 nx_widget_box res_sm">

					<!-- 학습자 모임 시작 -->
					<div class="div-title-underbar clearfix">
						<a href="<?php echo G5_BBS_URL;?>/board.php?bo_table=managers" class="pull-right nx_btn2">더보기</a>
						<span class="div-title <?php echo $font;?>">
							<b>시/군 관계자 모임</b>
						</span>
					</div>
					<div class="widget-box">
						<?php echo apms_widget('basic-post-list', $wid.'-wm9'); ?>
					</div>
					<!-- 학습자 모임 끝 -->

				</div>

				<div class="col-sm-6 nx_widget_box res_sm">

					<!-- 강사 모임 시작 -->
					<div class="div-title-underbar clearfix">
						<a href="<?php echo G5_BBS_URL;?>/board.php?bo_table=free" class="pull-right nx_btn2">더보기</a>
						<span class="div-title <?php echo $font;?>">
							<b>자유 모임</b>
						</span>
					</div>
					<div class="widget-box">
						<?php echo apms_widget('basic-post-list', $wid.'-wm10'); ?>
					</div>
					<!-- 강사 모임 끝 -->

				</div>
			</div>
			*/
			?>
			
			<div class="row">
				<div class="col-sm-6 nx_widget_box res_sm">

					<!-- 가이드 시작 -->
					<div class="div-title-underbar clearfix">
						<a href="<?php echo G5_BBS_URL;?>/board.php?bo_table=gallery" class="pull-right nx_btn2">더보기</a>
						<span class="div-title <?php echo $font;?>">
							<b>갤러리</b>
						</span>
					</div>
					<div class="widget-box">
						<?php echo apms_widget('basic-post-gallery', $wid.'-wm5', 'center=1'); ?>
					</div>
					<!-- 가이드 끝 -->

				</div>
				<div class="col-sm-6 nx_widget_box res_sm">

					<!-- 팁 시작 -->
					<div class="div-title-underbar clearfix">
						<a href="<?php echo G5_BBS_URL;?>/board.php?bo_table=materials" class="pull-right nx_btn2">더보기</a>
						<span class="div-title <?php echo $font;?>">
							<b>자료실</b>
						</span>
					</div>
					<div class="widget-box">
						<?php echo apms_widget('basic-post-mix', $wid.'-wm6', 'icon={아이콘:caret-right} bold=1 idate=1 date=1 strong=1'); ?>
					</div>
					<!-- 팁 끝 -->

				</div>
			</div>

			<div class="row">
				<div class="col-sm-6 nx_widget_box res_sm">

					<!-- 가이드 시작 -->
					<div class="div-title-underbar clearfix">
						<a href="<?php echo G5_BBS_URL;?>/board.php?bo_table=press" class="pull-right nx_btn2">더보기</a>
						<span class="div-title <?php echo $font;?>">
							<b>보도 자료</b>
						</span>
					</div>
					<div class="widget-box">
						<?php echo apms_widget('basic-post-list', $wid.'-wm12', 'title=1 rows=5 bo_list=press'); ?>
					</div>
					<!-- 가이드 끝 -->

				</div>
				<div class="col-sm-6 nx_widget_box res_sm">

					<!-- 팁 시작 -->
					<div class="div-title-underbar clearfix">
						<a href="<?php echo G5_BBS_URL;?>/board.php?bo_table=calendar" class="pull-right nx_btn2">더보기</a>
						<span class="div-title <?php echo $font;?>">
							<b>행사 일정</b>
						</span>
					</div>
					<div class="widget-box">
						<?php echo apms_widget('basic-post-list', $wid.'-wm13', 'title=1 rows=5 bo_list=calendar'); ?>
					</div>
					<!-- 팁 끝 -->

				</div>
			</div>

			<!-- 추천학습 시작 -->
			<div class="nx_widget_box">
				<div class="div-title-underbar clearfix">
					<a href="<?php echo G5_URL;?>/gseek/nx_learn_list.php?SC_CC_CODE=ch" class="pull-right nx_btn2">더보기</a>
					<span class="div-title <?php echo $font;?>">
						<b>추천학습</b>
					</span>
				</div>
				<div class="widget-box">
					<?php //echo apms_widget('basic-post-webzine', $wid.'-wm4', 'bold=1 date=1'); ?>
					<?php echo apms_widget('nx-post-gallery-chucheon', $wid.'-wm700', 'center=1'); ?>
				</div>
			</div>
			<!-- 추천학습 끝 -->	
			
		</div>
		<!-- 사이드 영역 -->
		<div class="col-md-4 at-col at-side">

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

			<!-- 공지 시작 -->
			<div class="nx_widget_box">
				<div class="div-title-underbar clearfix">
					<a href="<?php echo G5_BBS_URL;?>/board.php?bo_table=notice" class="pull-right nx_btn2">더보기</a>
					<span class="div-title <?php echo $font;?>">
						<b>공지</b>
					</span>
				</div>
				<div class="widget-box">
					<?php echo apms_widget('nx-post-notice', $wid.'-ws1', 'icon={아이콘:bell} date=1 strong=1,3'); ?>
				</div>
			</div>
			<!-- 공지 끝 -->

			<div class="widget-box">
				<?php echo apms_widget('nx-post-image-banner', $wid.'-wm100'); ?>
			</div>

			<!-- 설문 시작 -->
			<?php // 설문조사
				$is_poll_list = apms_widget('basic-poll', $wid.'-ws3', 'icon={아이콘:commenting}');
				if($is_poll_list) {
			?>
				<div class="nx_widget_box">
					<div class="div-title-underbar clearfix">
						<span class="div-title <?php echo $font;?>">
							<b>Poll</b>
						</span>
					</div>
					<div class="widget-box">
						<?php echo $is_poll_list; ?>
					</div>					
				</div>
			<?php } ?>
			<!-- 설문 끝 -->

			<?php
			/*
			<div class="nx_widget_box">
				<!-- 통계 시작 -->
				<div class="div-title-underbar clearfix">
					<span class="div-title <?php echo $font;?>">
						<b>State</b>
					</span>
				</div>
				<div class="widget-box">
					<ul style="padding:0; margin:0; list-style:none;">
						<li><i class="fa fa-bug red"></i>  <a href="<?php echo $at_href['connect'];?>">
							현재 접속자 <span class="pull-right"><?php echo number_format($stats['now_total']); ?><?php echo ($stats['now_mb'] > 0) ? '(<b>'.number_format($stats['now_mb']).'</b>)' : ''; ?> 명</span></a>
						</li>
						<li><i class="fa fa-bug"></i> 오늘 방문자 <span class="pull-right"><?php echo number_format($stats['visit_today']); ?> 명</span></li>
						<li><i class="fa fa-bug"></i> 어제 방문자 <span class="pull-right"><?php echo number_format($stats['visit_yesterday']); ?> 명</span></li>
						<li><i class="fa fa-bug"></i> 최대 방문자 <span class="pull-right"><?php echo number_format($stats['visit_max']); ?> 명</span></li>
						<li><i class="fa fa-bug"></i> 전체 방문자 <span class="pull-right"><?php echo number_format($stats['visit_total']); ?> 명</span></li>
						<li><i class="fa fa-bug"></i> 전체 게시물	<span class="pull-right"><?php echo number_format($menu[0]['count_write']); ?> 개</span></li>
						<li><i class="fa fa-bug"></i> 전체 댓글수	<span class="pull-right"><?php echo number_format($menu[0]['count_comment']); ?> 개</span></li>
						<li><i class="fa fa-bug"></i> 전체 회원수	<span class="pull-right at-tip" data-original-title="<nobr>오늘 <?php echo $stats['join_today'];?> 명 / 어제 <?php echo $stats['join_yesterday'];?> 명</nobr>" data-toggle="tooltip" data-placement="top" data-html="true"><?php echo number_format($stats['join_total']); ?> 명</span>
						</li>
					</ul>
				</div>
				<!-- 통계 끝 -->
			</div>
			*/
			?>

			<!-- SNS아이콘 시작 -->
			<!-- <div class="widget-box text-center">
				<?php echo $sns_share_icon; // SNS 공유아이콘 ?>
			</div> -->
			<!-- SNS아이콘 끝 -->

		</div>

	</div>

	<!-- 배너 시작 -->
	<div class="nx_widget_box" style="margin-bottom:0;">
		<div class="div-title-underbar clearfix">
			<span class="div-title <?php echo $font;?>">
				<b>관련기관</b>
			</span>
		</div>
		<div class="widget-box">
			<?php echo apms_widget('nx-rolling-banner', $wid.'-wm11', 'center=1'); ?>
		</div>
	</div>
	<!-- 배너 끝 -->	

	<?php //include(THEMA_PATH.'/quickmenu.php') ?>
</div>
