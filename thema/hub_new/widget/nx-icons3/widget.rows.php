<?php
if (!defined('_GNUBOARD_')) exit; //개별 페이지 접근 불가

// 추출하기
$wset['image'] = 1; //이미지글만 추출

$list = apms_board_rows($wset);
$list_cnt = count($list);

$rank = apms_rank_offset($wset['rows'], $wset['page']);

// 날짜
$is_date = (isset($wset['date']) && $wset['date']) ? true : false;
$is_dtype = (isset($wset['dtype']) && $wset['dtype']) ? $wset['dtype'] : 'm.d';
$is_dtxt = (isset($wset['dtxt']) && $wset['dtxt']) ? true : false;

// 새글
$is_new = (isset($wset['new']) && $wset['new']) ? $wset['new'] : 'red'; 

// 분류
$is_cate = (isset($wset['cate']) && $wset['cate']) ? true : false;

// 글내용 - 줄이 1줄보다 크고
/* (TEMP)
$is_cont = ($wset['line'] > 1) ? true : false; 
$is_details = ($is_cont) ? '' : ' no-margin'; 
*/

// 동영상아이콘
$is_vicon = (isset($wset['vicon']) && $wset['vicon']) ? '' : '<i class="fa fa-play-circle-o post-vicon"></i>'; 

// 스타일
$is_center = (isset($wset['center']) && $wset['center']) ? ' text-center' : ''; 
$is_bold = (isset($wset['bold']) && $wset['bold']) ? true : false; 

// 그림자
$shadow_in = '';
$shadow_out = (isset($wset['shadow']) && $wset['shadow']) ? apms_shadow($wset['shadow']) : '';
if($shadow_out && isset($wset['inshadow']) && $wset['inshadow']) {
	$shadow_in = '<div class="in-shadow">'.$shadow_out.'</div>';
	$shadow_out = '';	
}

// owl-hide : 모양유지용 프레임
?>

<style>
	.sec-ion li { font-size: 16px; }
	.widgetlink {
	   /*border: 1px solid #ddd;*/
	   background-color: #f2f2f2;
	}
	.snswidget_link3_line{line-height:13px;}
	.snswidget_link4_line{line-height:12px;}
	.snswidget_link2_line{line-height:29px;}
	.clicktxtcolor{color:#004eff;}
	.sns-widget-div1{margin-bottom:35px;}
	.mgb-10{margin-top:16px;}
	
.grayscale { filter: grayscale(100%); }
</style>

<script>
	$('html').click(function(e) { 
		
		var container = $("#sns-widget-div1");
		if (!container.is(e.target) && container.has(e.target).length === 0){ 
			//alert("another position");
			$("[id^='snswidget_link']").css("display","none");
			$("[id^='snswidget_txt']").removeClass("clicktxtcolor");
			$("#snswidget_sec1").css("display","block");
			$("#snswidget_sec2").css("display","block");
			$("#sns-widget-div-top").addClass("sns-widget-div1");
		}
		});
	function openwigetTab(iNum){
		if(iNum ==1){
			if($("#snswidget_link1").is(":visible")){
				$("[id^='snswidget_link']").css("display","none");
				$("[id^='snswidget_txt']").removeClass("clicktxtcolor");
				
				$("#snswidget_sec2").css("display","block");

    			$("#sns-widget-div-top").addClass("sns-widget-div1");
			}else{
				$("[id^='snswidget_link']").css("display","none");
				$("[id^='snswidget_txt']").removeClass("clicktxtcolor");
				
    			$("#snswidget_sec2").css("display","none");
    			$("#snswidget_link1").css("display","block");
    			$("#snswidget_txt1").addClass("clicktxtcolor");

    			$("#sns-widget-div-top").removeClass("sns-widget-div1");
			}
		}
		if(iNum ==2){
			if($("#snswidget_link2").is(":visible")){
				$("[id^='snswidget_link']").css("display","none");
				$("[id^='snswidget_txt']").removeClass("clicktxtcolor");
				
				$("#snswidget_sec2").css("display","block");

    			$("#sns-widget-div-top").addClass("sns-widget-div1");
			}else{
				$("[id^='snswidget_link']").css("display","none");
				$("[id^='snswidget_txt']").removeClass("clicktxtcolor");
				
    			$("#snswidget_sec2").css("display","none");
    			$("#snswidget_link2").css("display","block");
    			$("#snswidget_txt2").addClass("clicktxtcolor");

    			$("#sns-widget-div-top").removeClass("sns-widget-div1");
			}
		}
		if(iNum ==3){
			if($("#snswidget_link3").is(":visible")){
				$("[id^='snswidget_link']").css("display","none");
				$("[id^='snswidget_txt']").removeClass("clicktxtcolor");
				
				$("#snswidget_sec1").css("display","block");

    			$("#sns-widget-div-top").addClass("sns-widget-div1");
			}else{
				$("[id^='snswidget_link']").css("display","none");
				$("[id^='snswidget_txt']").removeClass("clicktxtcolor");
				
    			$("#snswidget_sec1").css("display","none");
    			$("#snswidget_link3").css("display","block");
    			$("#snswidget_txt3").addClass("clicktxtcolor");

    			$("#sns-widget-div-top").removeClass("sns-widget-div1");
			}
		}
		if(iNum ==4){
			if($("#snswidget_link4").is(":visible")){
				$("[id^='snswidget_link']").css("display","none");
				$("[id^='snswidget_txt']").removeClass("clicktxtcolor");
				
				$("#snswidget_sec1").css("display","block");

    			$("#sns-widget-div-top").addClass("sns-widget-div1");
			}else{
				$("[id^='snswidget_link']").css("display","none");
				$("[id^='snswidget_txt']").removeClass("clicktxtcolor");
				
    			$("#snswidget_sec1").css("display","none");
    			$("#snswidget_link4").css("display","block");
    			$("#snswidget_txt4").addClass("clicktxtcolor");
    			
    			$("#sns-widget-div-top").removeClass("sns-widget-div1");
			}
		}
	}
</script>
<div id="sns-widget-div1">
<div class="col-md-12 col-sm-12 sns-widget-div1" id="sns-widget-div-top">
	<div class="row row-20">
		<div class="sec3-ion" id="snswidget_sec1">
			<ul>
				<li><!-- 페이스북 -->
					<a href="#none"  onclick="openwigetTab(1)">
						<img alt="facebook" src="<?php echo G5_URL; ?>/img/widget_facebook_icon.png" style="max-width:40%; margin-left:30%; margin-bottom:10px;" id="snswidget_img1">
						<span id="snswidget_txt1"><?php echo $wset['name_01']; ?></span>
					</a>						
				</li>
				<li><!-- 인스타그램 -->
					<a href="#none" onclick="openwigetTab(2)">
						<img alt="instagram" src="<?php echo G5_URL; ?>/img/widget_instagram_icon.png" style="max-width:40%; margin-left:30%; margin-bottom:10px;" id="snswidget_img2">
						<span id="snswidget_txt2"><?php echo $wset['name_02']; ?></span>
					</a>
				</li>
				
			</ul>
			<div class="clearfix"></div>
		</div>
		<div id="snswidget_link1" class="widgetlink snswidget_link3_line" style="display: none;"><!-- 페이스북 -->
			<ul style="margin-bottom: 0px; margin-top: 12px; padding-bottom: 0px;">
				<li style="margin-left: 20px;"><a href="http://www.facebook.com/gill1228" target="_blank">경기도평생교육진흥원</a></li>
				<li style="margin-left: 20px;"><a href="http://facebook.com/gcampus.paju/" target="_blank">경기미래교육파주캠퍼스</a></li>
				<li style="margin-left: 20px;"><a href="http://facebook.com/gillyp209/" target="_blank">경기미래교육양평캠퍼스</a></li>
			</ul>
		</div>
		<div id="snswidget_link2" class="widgetlink snswidget_link3_line"  style="display: none;"><!-- 인스타그램 -->
			<ul style="margin-bottom: 0px; margin-top: 12px; padding-bottom: 0px;">
				<li style="margin-left: 20px;"><a href="http://www.instagram.com/gill_forum" target="_blank">경기도평생교육진흥원</a></li>
				<li style="margin-left: 20px;"><a href="http://instagram.com/g_future_campus/" target="_blank">경기미래교육파주캠퍼스</a></li>
				<li style="margin-left: 20px;"><a href="http://www.instagram.com/gill_yp209" target="_blank">경기미래교육양평캠퍼스</a></li>
			</ul>
		</div>
		
	</div>
</div>

<div class="col-md-12 col-sm-12" style="margin-bottom: 6px;">
	<div class="row row-20">
		
		<div id="snswidget_link3" class="widgetlink snswidget_link4_line"  style="display: none;margin-bottom: 11px; margin-top: 12px;"><!-- 유튜브 -->
			<ul style="margin-bottom: 0px;">
				<li style="margin-left: 20px;"><a href="https://www.youtube.com/channel/UCPRBaiM3fQL99VnwpNoW3VQ?view_as=subscriber" target="_blank">경기도평생교육진흥원</a></li>
				<li style="margin-left: 20px;"><a href="https://www.youtube.com/channel/UCODrDuBopMXKAv81j4xNqRQ" target="_blank">경기미래교육파주캠퍼스</a></li>
				<li style="margin-left: 20px;"><a href="https://www.youtube.com/channel/UCqYalLl1hVprsco9uWMqdhA" target="_blank">경기미래교육양평캠퍼스</a></li>
			</ul>
		</div>
		<div id="snswidget_link4" class="widgetlink snswidget_link2_line"   style="display: none; margin-bottom: 10px; margin-top: 11px;"><!-- 블로그 -->
			<ul style="margin-bottom: 0px;">
				<li style="margin-left: 20px;"><a href="http://blog.naver.com/gill_forum" target="_blank">경기도평생교육진흥원</a></li>
				<li style="margin-left: 20px;"><a href="http://blog.naver.com/gillpaju" target="_blank">경기미래교육파주캠퍼스</a></li>
			</ul>
		</div>
		<div class="sec3-ion" id="snswidget_sec2" style="margin: 0px;" id="sns-widget-div-bottom">
			<ul>
				<li><!-- 유튜브 -->
					<a href="#none" onclick="openwigetTab(3)">
						<img alt="youtube" src="<?php echo G5_URL; ?>/img/widget_youtube_icon.png" style="max-width:40%; margin-left:30%; margin-bottom:10px;"  id="snswidget_img3">
						<span id="snswidget_txt3"><?php echo $wset['name_03']; ?></span>
					</a>
				</li>
				<li><!-- 블로그 -->
					<a href="#none" onclick="openwigetTab(4)">
						<img alt="blog" src="<?php echo G5_URL; ?>/img/widget_blog_icon.png" style="max-width:40%; margin-left:30%; margin-bottom:10px;" id="snswidget_img4">
						<span id="snswidget_txt4"><?php echo $wset['name_04']; ?></span>
					</a>
				</li>
			</ul>
			<div class="clearfix"></div>
		</div>
	</div>
</div>
</div>