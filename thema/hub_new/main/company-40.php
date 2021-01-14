<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// 위젯 대표아이디 설정
$wid = 'mc-40';

// 풀스크린 타이틀 설정 : 1이면 사용, 0이면 미사용(일반)
$fullscreen = 1;

// 데모용
if($is_demo) {
	if(isset($dtset['full'])) $fullscreen = $dtset['full'];
}

// 나눔고딕
add_stylesheet('<link rel="stylesheet" href="//fonts.googleapis.com/earlyaccess/nanumgothic.css">', -100);

// Ionicons : https://ionicons.com/v2/
add_stylesheet('<link rel="stylesheet" href="'.THEMA_URL.'/assets/css/css/ionicons.min.css" type="text/css">',-1);

// Main CSS
add_stylesheet('<link rel="stylesheet" href="'.THEMA_URL.'/main/css/company-40.css" type="text/css">',0);

?>

<!-- 타이틀 -->
<div class="at-mask<?php echo ($fullscreen) ? ' full-height' : '';?>">
	<?php echo apms_widget('miso-post-title', $wid.'-title', 'data=title type=video cover=1 vauto=1 vloop=1 dot=red vlist=dk9uNWPP7EA'); ?>
	<!--하단 마스크 -->
	<?php echo miso_mask('tail', $is_hmask, '#f5f5f5', $is_hmask_over);?>
</div>

<div class="at-boxed at-container">
	<section class="section">
		<div class="op-tbl">
			<div class="op-cell op-cell-title">
				<!-- 헤드라인 영역 -->
				<?php echo apms_widget('miso-post-bxslider', $wid.'-headline', 'skin=title margin=0 heights=530px,530px,56.25%,75%,110% raster=1 nav=2 dot=blue in=1 rdm=1 loop=1 data=title'); ?>

				<!-- 아이콘 영역 -->
				<div class="op-cell-icon">
					<ul>
						<li class="hover-1">
							<a href="#이동URL입력">
								<i class="ion-ios-keypad-outline"></i>
								<h4>제품정보</h4>
							</a>
						</li>
						<li class="hover-2">
							<a href="#이동URL입력">
								<i class="ion-ios-color-filter-outline"></i>
								<h4>사업소개</h4>
							</a>
						</li>
						<li class="hover-3">
							<a href="#이동URL입력">
								<i class="ion-ios-people-outline"></i>
								<h4>채용정보</h4>
							</a>
						</li>
						<li class="hover-4">
							<a href="#이동URL입력">
								<i class="ion-ios-paper-outline"></i>
								<h4>견적문의</h4>
							</a>
						</li>
						<li class="hover-5">
							<a href="#이동URL입력">
								<i class="ion-ios-chatboxes-outline"></i>
								<h4>고객상담</h4>
							</a>
						</li>
						<li class="hover-6">
							<a href="#이동URL입력">
								<i class="ion-ios-location-outline"></i>
								<h4>오시는길</h4>
							</a>
						</li>
					</ul>
					
					<div class="clearfix"></div>
				</div>

			</div>
			<div class="op-cell op-cell-body">
				<!-- 공지사항 등 -->
				<div class="op-widget op-tab" style="border-bottom:1px solid #ddd;">
					<div id="tab_10" class="tabs swipe-tab">
						<ul class="nav nav-tabs" data-toggle="tab-hover">
							<li class="active"><a href="#tab_11" data-toggle="tab"<?php echo tab_href('#이동URL');?>>공지사항</a></li>
							<li><a href="#tab_12" data-toggle="tab"<?php echo tab_href('#이동URL');?>>보도자료</a></li>
							<li><a href="#tab_13" data-toggle="tab"<?php echo tab_href('#이동URL');?>>채용공고</a></li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane active" id="tab_11">
								<?php echo apms_widget('miso-post', $wid.'-notice', 'icon={아이콘:caret-right}'); ?>
							</div>
							<div class="tab-pane" id="tab_12">
								<?php echo apms_widget('miso-post', $wid.'-news', 'icon={아이콘:caret-right}'); ?>
							</div>
							<div class="tab-pane" id="tab_13">
								<?php echo apms_widget('miso-post', $wid.'-recruit', 'icon={아이콘:caret-right}'); ?>
							</div>
						</div>
					</div>
				</div>

				<?php echo apms_widget('miso-post-slider', $wid.'-biz', 'skin=panel nav=2 loop=1 margin=0 items=1,1,2,2,1 opa=70 raster=3 data=panel'); ?>

				<!-- 고객센터 -->
				<div class="op-widget">
					<h3 class="op-subject">
						고객센터
					</h3>
					<div class="en red font-24">
						<i class="fa fa-volume-control-phone"></i> 
						<b>000.0000.0000</b>
					</div>

					<div class="help-block" style="line-height:1.8; margin-top:10px;">
						월-금 : 9:30 ~ 17:30, 토/일/공휴일 휴무
						<br>
						런치타임 : 12:30 ~ 13:30
					</div>
				</div>

			</div>
		</div>

		<!-- 협력업체 -->
		<div class="op-widget">
			<a href="#이동URL입력">				
				<h3 class="op-subject">
					협력업체
				</h3>
			</a>
			<?php echo apms_widget('miso-post-slider', $wid.'-partner', 'heights=40% nav=1 loop=1 items=5,5,4,3,2'); ?>
		</div>

		<div class="clearfix"></div>
	</section>
</div>
