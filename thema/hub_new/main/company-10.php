<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// 위젯 대표아이디 설정
$wid = 'mc-10';

// 불투명설정 : 0, 10, 20, 30, 40, 50, 60, 70, 75, 80, 85, 90, 95 설정가능
$opacity = 30;

// 래스트설정 : 1번부터 6번까지 주사선 설정가능
$raster = 1;

// 스타일설정 : 유튜브동영상 1, 패럴렉스 2, 일반배경 3
$mode = 1;

// 유튜브 동영상, 배경화면 설정
$vlist = array();

// 유튜브아이디, 볼륨, 시작시간, 끝시간, 배경화면주소
$vlist[] = array('QWCFTgRDVc4', 30, 0, 0, 'http://amina.co.kr/demo/image/biz03.jpg');
$vlist[] = array('wEZo0i31g5c', 30, 0, 0, 'http://amina.co.kr/demo/image/biz08.jpg');
$vlist[] = array('dk9uNWPP7EA', 30, 0, 0, 'http://amina.co.kr/demo/image/biz09.jpg');

// 랜덤출력 : 사용하지 않을 경우 주석처리
shuffle($vlist);

// 데모용
if($is_demo) {
	if(isset($dtset['mode'])) $mode = $dtset['mode'];
}

// 출력처리
$video_list = '';
if($mode == "1" && IS_IE > 8) {
	// Video
	apms_script('bgvideo');

	$vlist_cnt = count($vlist);
	for($i=0; $i < $vlist_cnt; $i++) {
		$is_vol = (int)$vlist[$i][1];
		$is_mute = ($is_vol > 0) ? ", vol:".$is_vol : ", mute:true";		
		$video_opt = ", containment:'#containerVideo', showControls:true".$is_mute.", showYTLogo:false, quality:'highres'";
		$video_list .= "{videoURL:'".$vlist[$i][0]."', autoPlay:true, startAt:".(int)$vlist[$i][2].", stopAt:".(int)$vlist[$i][3].$video_opt."}";
		$video_list .= (($i + 1) < $vlist_cnt) ? ','.PHP_EOL : PHP_EOL;
	}

	//배경이미지
	$bg_img = 'https://img.youtube.com/vi/'.$vlist[0][0].'/sddefault.jpg';

} else {

	//배경이미지
	$bg_img = $vlist[0][4];
}

// 나눔고딕
add_stylesheet('<link rel="stylesheet" href="//fonts.googleapis.com/earlyaccess/nanumgothic.css">', -100);

// Ionicons : https://ionicons.com/v2/
add_stylesheet('<link rel="stylesheet" href="'.THEMA_URL.'/assets/css/css/ionicons.min.css" type="text/css">',-1);

// Main CSS
add_stylesheet('<link rel="stylesheet" href="'.THEMA_URL.'/main/css/company-10.css" type="text/css">',0);

?>

<div id="containerVideo" class="at-mask min-height bg-video img-<?php echo ($mode == "2") ? 'parallax' : 'cover';?>" style="background-image:url('<?php echo $bg_img;?>');">
	<div style="display:none;">
		<div id="bgndVideo" class="player">Video</div>
	</div>
	<div class="at-cover op-opa img-opa<?php echo $opacity;?>"></div>
	<div class="at-raster img-raster<?php echo $raster;?>"></div>
	<div class="at-boxed at-container">
		<section class="section full-zone">
			<div class="op-tbl">
				<div class="op-cell op-cell-title">
					<!-- 헤드라인 영역 -->
					<?php echo apms_widget('miso-post-bxslider', $wid.'-headline', 'skin=title margin=0 rdm=1 heights=610px,610px,56.25%,75%,110% raster=1 nav=2 dot=red in=1 loop=1 demo=title'); ?>
				</div>
				<div class="op-cell op-cell-body">
					<div class="op-cell-icon">
						<!-- 아이콘 영역 -->
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
									<?php echo apms_widget('miso-post', $wid.'-notice', 'rows=5 icon={아이콘:caret-right}'); ?>
								</div>
								<div class="tab-pane" id="tab_12">
									<?php echo apms_widget('miso-post', $wid.'-news', 'rows=5 icon={아이콘:caret-right}'); ?>
								</div>
								<div class="tab-pane" id="tab_13">
									<?php echo apms_widget('miso-post', $wid.'-recruit', 'rows=5 icon={아이콘:caret-right}'); ?>
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
						<?php echo apms_widget('miso-post-slider', $wid.'-partner', 'heights=40% rdm=1 auto=1 nav=1 loop=1 items=2,2,4,3,2'); ?>
					</div>

				</div>
			</div>

			<div class="clearfix"></div>
		</section>
	</div>

	<!-- 하단 마스크 -->
	<?php echo miso_mask('tail', $is_hmask, '#333', $is_hmask_over);?>

</div><!-- .at-container -->

<?php if($video_list) { ?>
	<script>
	$(document).ready(function(){
		var vlist = [
			<?php echo $video_list;?>
		];
		$("#bgndVideo").YTPlaylist(vlist, false);
		$('#bgndVideo').on("YTPData", function(e){
			$('#containerVideo').css("background-image", "url(https://img.youtube.com/vi/" + e.prop.id + "/sddefault.jpg)");
		});
	});
	</script>
<?php } ?>