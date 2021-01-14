<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// 위젯 대표아이디 설정
$wid = 'mc-20';

// 풀스크린 타이틀 설정 : 1이면 사용, 0이면 미사용(일반)
$fullscreen = 0;

// 박스형 컨텐츠 설정 : 1이면 사용, 0이면 미사용(와이드)
$boxedcontent = 1;

// 데모용
if($is_demo) {
	if(isset($dtset['full'])) $fullscreen = $dtset['full'];
	if(isset($dtset['box'])) $boxedcontent = $dtset['box'];
}

// 나눔고딕
add_stylesheet('<link rel="stylesheet" href="//fonts.googleapis.com/earlyaccess/nanumgothic.css">', -100);

// Ionicons : https://ionicons.com/v2/
add_stylesheet('<link rel="stylesheet" href="'.THEMA_URL.'/assets/css/css/ionicons.min.css" type="text/css">',-1);

// Main CSS
add_stylesheet('<link rel="stylesheet" href="'.THEMA_URL.'/main/css/company-20.css" type="text/css">',0);

?>

<!-- 타이틀 -->
<div class="at-mask<?php echo ($fullscreen) ? ' full-height' : '';?>">
	<?php echo apms_widget('miso-post-title', $wid.'-title', 'data=title type=video cover=1 dot=red vauto=1 vloop=1 vlist=dk9uNWPP7EA'); ?>
	<!--하단 마스크 -->
	<?php echo miso_mask('tail', $is_hmask, '#f5f5f5', $is_hmask_over);?>
</div>

<div class="at-boxed at-container<?php echo ($boxedcontent) ? '' : ' wide-container';?>">
	<section class="section">
		<div class="op-tbl">
			<div class="op-cell op-cell-title">
				<!-- 헤드라인 영역 -->
				<?php echo apms_widget('miso-post-bxslider', $wid.'-headline', 'skin=title margin=0 rdm=1 heights=845px,845px,56.25%,75%,110% raster=1 nav=2 dot=blue in=1 loop=1 data=title'); ?>
			</div>
			<div class="op-cell op-cell-body">
				<div class="op-tbl2">
					<div class="op-cell2 op-cell-icon">
						<!-- 가운데 아이콘 영역 -->
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
					<div class="op-cell2 op-cell-content">

						<!-- 우측 컨텐츠 영역 -->
						<div class="op-box-tbl">
							<div class="op-box-cell box-deepblue">
								<a href="#이동URL입력">
									<h4>회사소개</h4>
									<i class="ion-ios-world-outline"></i>
									<h5>아미나빌더 1.7.16이상에서 사용가능</h5>
								</a>
							</div>
							<div class="op-box-cell box-gray">
								<a href="#이동URL입력">
									<h4>서비스</h4>
									<i class="ion-ios-color-wand-outline"></i>
									<h5>마스크 기능 및 페이지 개별설정 지원</h5>
								</a>
							</div>
						</div>

						<!-- 공지사항 등 -->
						<div class="op-widget op-tab">
							<div id="tab_10" class="tabs swipe-tab">
								<ul class="nav nav-tabs" data-toggle="tab-hover">
									<li class="active"><a href="#tab_11" data-toggle="tab"<?php echo tab_href('#이동URL');?>>공지사항</a></li>
									<li><a href="#tab_12" data-toggle="tab"<?php echo tab_href('#이동URL');?>>보도자료</a></li>
									<li><a href="#tab_13" data-toggle="tab"<?php echo tab_href('#이동URL');?>>채용공고</a></li>
								</ul>
								<div class="tab-content">
									<div class="tab-pane active" id="tab_11">
										<?php echo apms_widget('miso-post', $wid.'-notice', 'rows=6 icon={아이콘:caret-right}'); ?>
									</div>
									<div class="tab-pane" id="tab_12">
										<?php echo apms_widget('miso-post', $wid.'-news', 'rows=6 icon={아이콘:caret-right}'); ?>
									</div>
									<div class="tab-pane" id="tab_13">
										<?php echo apms_widget('miso-post', $wid.'-recruit', 'rows=6 icon={아이콘:caret-right}'); ?>
									</div>
								</div>
							</div>
						</div>

						<!-- 배너 -->
						<div class="op-banner">
							<?php echo apms_widget('miso-post-bxslider', $wid.'-banner', 'mode=vertical rdm=1 heights=56.25% nav=2 loop=1 demo=title'); ?>
						</div>

						<!-- 갤러리 -->
						<div class="op-widget">
							<a href="#이동URL입력">				
								<h3 class="op-subject">
									갤러리
								</h3>
							</a>
							<?php echo apms_widget('miso-post-slider', $wid.'-gallery', 'skin=gallery-subject rdm=1 nav=1 loop=1 items=2,2,3,3,2'); ?>
						</div>

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
