<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once(THEMA_PATH.'/assets/func.php');

// 위젯 대표아이디 설정
$wid = 'CMB';

// 게시판 제목 폰트 설정
$font = 'font-20';

// 게시판 제목 하단라인컬러 설정 - red, blue, green, orangered, black, orange, yellow, navy, violet, deepblue, crimson..
$line = 'navy';

// 사이드 위치 설정 - left, right
$side = ($at_set['side']) ? 'left' : 'right';


// 나눔고딕
add_stylesheet('<link rel="stylesheet" href="//fonts.googleapis.com/earlyaccess/nanumgothic.css">', -100);

// Ionicons : https://ionicons.com/v2/
add_stylesheet('<link rel="stylesheet" href="'.THEMA_URL.'/assets/css/css/ionicons.min.css" type="text/css">',-1);

// Main CSS
#add_stylesheet('<link rel="stylesheet" href="'.THEMA_URL.'/main/css/company-20.css" type="text/css">',0);
add_stylesheet('<link rel="stylesheet" href="'.THEMA_URL.'/main/css/community-20.css" type="text/css">',100);

?>

<style>
	#thema_wrapper{background: none;}

	.at-body{background: none;}
	.at-body.nx_main_bg{background: none; }

	.widget-area .div-title-underbar{margin-bottom: 10px;}
</style>

<!-- 타이틀 -->
<div class="at-mask<?php echo ($fullscreen) ? ' full-height' : '';?>">
	<?php echo apms_widget('miso-post-title', $wid.'-title', 'data=title type=video cover=1 dot=red vauto=1 vloop=1 vlist=dk9uNWPP7EA'); ?>
	<!--하단 마스크 -->
	<?php echo miso_mask('tail', $is_hmask, '#f5f5f5', $is_hmask_over);?>
</div>

<div class="section-whatson">
	<?php echo apms_widget('nx-widget-whatson', $wid.'-wt2', '', 'auto=0'); ?>
</div>

<div class="at-container widget-index clearfix" style="max-width: 100%;">
	<div class="nx-main-tit black">
		<h3 class="section-title">
			평생학습의 길(GILL)을 만드는 진흥원
		</h3>
		<p class="section-sub-title">경기도평생교육진흥원의 주요 사업과 각종 게시물을 확인하세요.</p>
	</div>

	<!-- 2 row -->
	<div class="widget-wrapper">	
		<div class="row row-20">

			<div class="col-lg-3 col-md-6 col-sm-6 col-20 nx-widget-top-wrap">
				<div class="nx-widget">
					<div class="widget-title">공지사항 <a href="/bbs/board.php?bo_table=notice" class="more"></a></div>
					<div class="nx-notice-box">
						<div class="nx-post-notice">
							<?php echo apms_widget('miso-post', $wid.'-notice', 'skin=basic sero=1 rows=5 items=1,1,1,2,1 margin=30 nav=1 icon={아이콘:check} bullet=1'); ?>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-md-6 col-sm-6 col-20 nx-widget-top-wrap">
				<div class="nx-widget">
					<div class="widget-title">채용안내 <a href="/bbs/board.php?bo_table=recruit" class="more"></a></div>
					<div class="nx-notice-box">
						<div class="nx-post-notice">
							<?php echo apms_widget('miso-post', $wid.'-recruit', 'skin=basic sero=1 rows=5 items=1,1,1,2,1 margin=30 nav=1 icon={아이콘:book} bullet=1'); ?>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-md-6 col-sm-6 col-20 nx-widget-top-wrap">
				<div class="nx-widget">
					<div class="widget-title">사업안내 <a href="/bbs/board.php?bo_table=biz" class="more"></a></div>
					<div class="nx-notice-box">
						<div class="nx-post-notice">
							<?php echo apms_widget('miso-post', $wid.'-biz', 'skin=basic sero=1 rows=5 items=1,1,2,2,1 margin=30 nav=1 icon={아이콘:comment} bullet=1'); ?>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-md-6 col-sm-6 col-20 nx-widget-top-wrap">
				<div class="nx-widget">
					<div class="widget-title">도정소식 <a href="/bbs/board.php?bo_table=do" class="more"></a></div>
					<div class="nx-notice-box">
						<div class="nx-post-notice">
							<?php echo apms_widget('miso-post', $wid.'-do', 'skin=basic sero=1 rows=5 items=1,1,2,2,1 margin=30 nav=1 icon={아이콘:comment} bullet=1'); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- end 2 row -->

		<!-- 3 row -->
		<div class="row row-20">

			<div class="nx-widget-divide">
				
				<div class="col-md-6 col-sm-6 col-20 nx-widget-top-wrap">
					<div class="nx-widget">
						<div class="widget-title">최근 행사 소식 <a href="/bbs/board.php?bo_table=happyphoto" class="more"></a></div>
						<div class="nx-notice-box">
							<div pre-class="sec-content">
								<?php echo apms_widget('miso-post-slider', $wid.'-recently', 'skin=caption sero=1 rows=10 items=5,5,5,2,2 margin=30 nav=0 icon={아이콘:commenting} bullet=1'); ?>
							</div>
						</div>
					</div>
				</div>
				
				<div class="col-md-6 col-sm-6 col-20 nx-widget-top-wrap" id="sns-widget-div">
					<div class="nx-widget">
						<div class="widget-title">SNS 바로가기 <!-- <a href="/bbs/board.php?bo_table=co_promotion" class="more"></a> --></div>
						<div class="nx-notice-box" style="padding:15px 0px;">
							<div pre-class="sec-content">
								<?php //echo apms_widget('miso-post-slider', $wid.'-banner1', 'skin=gallery sero=1 rows=10 items=5,5,5,2,2 margin=30 nav=0 icon={아이콘:commenting} bullet=1'); ?>
								<?php echo apms_widget('nx-icons3', $wid.'-icons3'); ?>
							</div>
						</div>
					</div>
				</div>

			</div>

			<div class="nx-widget-divide">
				<?php echo apms_widget('nx-widget-title', $wid.'-wt1', '', 'auto=0'); //타이틀 ?>
			</div>
			
		</div> 
		<!-- end 3 row -->
	</div>

	<?php 
	# 교육 위젯
	echo apms_widget('nx-widget-edu', $wid.'-wt3', '', 'auto=0');  
	?>

	<div class="widget-wrapper">
		<div class="row row-20">
			<div class="col-md-12 col-sm-12 col-20"  style="padding-bottom: 30px;">

				<!-- title row -->
				<div class="row row-20">
					<div class="col-md-12 col-sm-12 col-20">
						<div class="sec-ion">
							<p>&nbsp;</p>
							<h3 class="section-title">
								진흥원 소개 및 사업 내용
							</h3>
							<p class="section-sub-title">
								경기도평생교육진흥원이 어떤 곳인지, 어떤 사업을 하고 있는지 알아보세요.
							</p>
							<div class="clearfix"></div>
						</div>
					</div>
					
				</div> 
				<!-- end title row -->
			</div>

			<?php echo apms_widget('nx-icons', $wid.'-icons'); ?>
		</div> 
		<!-- end 5 row -->
	</div>
	
	<!--sns widget 200414 sdkim 
	<div class="space" style="padding-bottom: 50px; margin: 40px auto; border-bottom: 1px solid #DDD;"></div>
	<div class="widget-wrapper">
		<div class="row row-20">
			<div class="col-md-12 col-sm-12 col-20"  style="padding-bottom: 30px;">
				<div class="row row-20">
					<div class="col-md-12 col-sm-12 col-20">
						<div class="sec-ion">
							<p>&nbsp;</p>
							<h3 class="section-title">
								진흥원 SNS 바로 가기
							</h3>
							<p class="section-sub-title">
								경기도평생교육진흥원의 SNS를 통해 우리 더 친해져요.
							</p>
							<div class="clearfix"></div>
						</div>
					</div>
				</div> 
			</div>
			<?php echo apms_widget('nx-icons2', $wid.'-icons2'); ?>
		</div> 
	</div>
  -->


	<div class="space" style="padding-bottom: 50px; margin: 40px auto; border-bottom: 1px solid #DDD;"></div>

	
	<?php /** 슬라이드1 영역  */ ?>
	<section class="nx-main-slide1-section">
		<div class="nx-main-tit black">
			<h3 class="section-title">
				진흥원의 최근 현황
			</h3>
			<p class="section-sub-title">경기도평생교육진흥원에서 있었던 최근 현황을 사진으로 모았습니다.</p>
		</div>
		<?php echo apms_widget('nx-slider', $wid.'-wg1', 'nav_size=big'); ?>
	</section>

	<section id="row4" class="row">
		<h3 class="section-title">소통하는 진흥원</h3>
		<p class="section-sub-title">경기도평생교육진흥원에서 추진하는 주요 사업 내용을 모았습니다.</p>

		<div class="widget-area">
			<div class="row">

				<?php nx_widget_box($font); ?>

			</div>
		</div>

	</section>


	<!-- 배너 시작 -->
	<div class="nx_widget_box nx-rolling-banner" style="margin-bottom:0;">
		<div class="widget-box">
			<?php echo apms_widget('nx-slider', $wid.'-wm11', 'center=1'); ?>
		</div>
	</div>
	<!-- 배너 끝 -->

	<?php //include(THEMA_PATH.'/quickmenu.php') ?>
</div>
