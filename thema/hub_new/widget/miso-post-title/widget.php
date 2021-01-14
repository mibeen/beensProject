<?php
if (!defined('_GNUBOARD_')) exit; //개별 페이지 접근 불가

global $atset, $at_href, $is_thema_size, $stx, $dtset;

if($is_demo && $dtset['type']) {
	$wset['img'] = $dtset['img'];
	$wset['type'] = $dtset['type'];
	if(G5_IS_MOBILE && $wset['type'] == 'video') {
		$wset['type'] = '';
	}
}

// 검색어
$stxa = $stx;

// bxslider
$is_use_slider = (!isset($wset['noslide']) || !$wset['noslide']) ? true : false;

if($is_use_slider) {
	apms_script('bxslider');
}

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$widget_url.'/widget.css">', 0);

// 모달창
$wset['modal_js'] = ($wset['modal'] == "1") ? apms_script('modal') : '';

// 초기값
if($wset['heights']) {
	list($wset['height'], $wset['hl'], $wset['hm'], $wset['hs'], $wset['hx']) = explode(",", $wset['heights']);
}

if(!$wset['height']) $wset['height'] = 640;

if($wset['opa'] == "") $wset['opa'] = 40;
if($wset['raster'] == "") $wset['raster'] = 1;

// 스킨 불러오기
if(!$wset['skin']) $wset['skin'] = 'basic';

// 타입설정
if($wset['type'] == 'video' && IS_IE > 8) {
	$is_slider = 'video';
} else if($wset['type'] == 'parallax') {
	$is_slider = 'parallax';
} else if($wset['type'] == 'slider') {
	$is_slider = 'slider';
} else {
	$is_slider = 'img';
}

if($wmset['type'] == 'slider') {
	$is_slider_m = 'slider';
} else {
	$is_slider_m = '';
}

// 아이디
$widget_id = apms_id(); 
$wset['wid'] = $wid;

// 동영상
$video_img = '';
$video_list = '';
if($is_slider == 'video') {

	$is_vlist = array();
	$vtmp = explode("\n", $wset['vlist']);
	$vcnt = count($vtmp);
	$z = 0;
	for($i=0; $i < $vcnt; $i++) {

		if(!$vtmp[$i]) continue;

		$vitem = trim($vtmp[$i]);

		if(!$vitem) continue;

		$is_vlist[$z] = $vitem;
		$z++;
	}

	if($z > 1 && $wset['vrdm']) {
		shuffle($is_vlist);
	}

	// 동영상수
	$is_vlist_cnt = $z;

	if(!$is_vlist_cnt) {
		$is_slider = 'img';
	} else {
		apms_script('bgvideo');

		// quality : default, small, medium, large, hd720, hd1080, highres
		if($wset['vol'] == "") $wset['vol'] = 30;
		$is_vol = (int)$wset['vol'];
		$is_mute = ($is_vol > 0) ? ", mute:false, vol:".$is_vol : ", mute:true";
		$is_video_js = ", containment:'#".$widget_id."', showControls:true".$is_mute.", showYTLogo:false, quality:'highres'";
		if($wset['vloop']) {
			$is_video_js .= ", loop:true";
		} else {
			$is_video_js .= ($is_vlist_cnt > 1) ? ", loop:false" : ", loop:true";
		}

		$vopt = array();
		for($i=0; $i < $is_vlist_cnt; $i++) { 
			list($vid, $vstart, $vstop) = explode(',', $is_vlist[$i]);

			$vopt[$i]['id'] = $vid;
			$vopt[$i]['start'] = (int)$vstart;
			$vopt[$i]['stop'] = (int)$vstop;

			$autoStart = (!$wset['vauto'] && !$i) ? 'false' : 'true';
			$startAt = ($vopt[$i]['start'] > 0) ? ', startAt:'.$vopt[$i]['start'] : '';
			$stopAt = ($vopt[$i]['stop'] > 0) ? ', stopAt:'.$vopt[$i]['stop'] : '';
			$video_list .= "{videoURL:'".$vid."', autoPlay:".$autoStart.$startAt.$stopAt.$is_video_js."}";

			if($wset['vloop']) break;

			$video_list .= (($i + 1) < $is_vlist_cnt) ? ','.PHP_EOL : PHP_EOL;
		}

		//Youtube Thumbnail 해상도 판별
		$arr = ['maxresdefault', 'sddefault', 'hqdefault', 'mqdefault', 'default'];

		$bg_img = '';
		$bg_q = '';
		for ($i = 0;$i < count($arr);$i++) {
			$bg_img = "https://img.youtube.com/vi/".$vopt[0]['id']."/" . $arr[$i] . ".jpg";
			$bg_q = $arr[$i];

			$ch = curl_init($bg_img);  //초기화, 핸들값 리턴
			curl_setopt($ch,  CURLOPT_RETURNTRANSFER, TRUE);  //curl_exec() 반환값을 문자열로 반환
			$response = curl_exec($ch);  //curl 실행
			$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);  //상태정보 리턴

			if ($httpCode == 404) continue;
			else break;
		}

		curl_close($ch);  //curl세션 종료

		//위 해상도 판별소스로 인한 주석처리
		//$bg_img = 'https://img.youtube.com/vi/'.$vopt[0]['id'].'/maxresdefault.jpg';
	}
} 

if($is_slider != 'video') {
	$bg_img = ($wset['img']) ? $wset['img'] : $atset['himg'];
}

// 클래스 타입
if($is_slider == 'video') {
	$wrap = ' is-video';
} else if($is_slider == 'parallax') {
	$wrap = ' is-parallax';
} else if($is_slider == 'slider') {
	$wrap = ' is-slider';
} else if($is_slider_m == 'slider') {
	$wrap = ' is-slider';
} else {
	$wrap = '';
}

?>
<style>
	<?php if($is_slider != 'slider' || $is_slider_m != 'slider') { ?>
	#<?php echo $widget_id;?> { background-image : url('<?php echo $bg_img;?>'); }
	<?php } ?>
	#<?php echo $widget_id;?> .full-zone { height:<?php echo $wset['height'];?>px; }
	<?php if($wset['dot']) { ?>
	#<?php echo $widget_id;?> .bx-wrapper .bx-pager.bx-default-pager a.active { background:<?php echo apms_color($wset['dot']);?>; }
	<?php } ?>
	<?php if($is_slider == 'slider' && (int)$is_thema_size) { ?>
	@media all and (min-width:<?php echo $is_thema_size;?>px) {
		.wided #<?php echo $widget_id;?> .bx-wrapper .bx-prev { left: calc((100% - <?php echo $is_thema_size;?>px - 10px)/2); }
		.wided #<?php echo $widget_id;?> .bx-wrapper .bx-next { right: calc((100% - <?php echo $is_thema_size;?>px - 10px)/2); }
	}
	<?php } ?>
	<?php if(_RESPONSIVE_) { //반응형일 때만 작동 ?>
		<?php if($wset['hl']) { ?>
		@media (max-width:1199px) { 
			.responsive #<?php echo $widget_id;?> .full-zone { height:<?php echo $wset['hl'];?>px; } 
		}
		<?php } ?>
		<?php if($wset['hm']) { ?>
		@media (max-width:991px) { 
			.responsive #<?php echo $widget_id;?> .full-zone { height:<?php echo $wset['hm'];?>px; } 
		}
		<?php } ?>
		<?php if($wset['hs']) { ?>
		@media (max-width:767px) { 
			.responsive #<?php echo $widget_id;?> .full-zone { height:<?php echo $wset['hs'];?>px; } 
		}
		<?php } ?>
		<?php if($wset['hx']) { ?>
		@media (max-width:480px) { 
			.responsive #<?php echo $widget_id;?> .full-zone { height:<?php echo $wset['hx'];?>px; } 
		}
		<?php } ?>
	<?php } ?>
	@media only screen and (max-width: 991px) {
		.back_none { background-image: none !important; }
		.back_none > .mbYTP_wrapper, .mb_YTPBar { display: none !important; }
	}
</style>
<div id="<?php echo $widget_id;?>" class="miso-post-title mask-hidden<?php echo $wrap;?> <?php if($is_slider_m) { echo 'back_none'; }?>">
	<?php if($is_slider == 'video') { ?>
		<div style="display:none;">
			<div id="yt_<?php echo $widget_id;?>" class="player">Video</div>
		</div>
	<?php } ?>
	<?php 
		if($wset['cache'] > 0) { // 캐시적용시
			echo apms_widget_cache($widget_path.'/widget.rows.php', $wname, $wid, $wset);
		} else {
			include($widget_path.'/widget.rows.php');
		}
	?>
	<?php if($setup_href) { ?>
		<div class="btn-wset">
			<a href="<?php echo $setup_href;?>" class="btn btn-xs btn-red btn-block win_memo">
				위젯설정
			</a>
		</div>
	<?php } ?>
</div>
<?php if($is_use_slider || $is_slider == 'video') { ?>
<script>
$(document).ready(function(){
	<?php if($is_slider == 'video') { ?>
	var vlist_<?php echo $widget_id;?> = [
		<?php echo $video_list;?>
	];
	// $("#yt_<?php echo $widget_id;?>").YTPlaylist(vlist_<?php echo $widget_id;?>, false);
	$("#yt_<?php echo $widget_id;?>").YTPlaylist(vlist_<?php echo $widget_id;?>, {
		playerVars: {rel: 0, showinfo: 0, ecver: 2}
	});
		<?php if(!$wset['vloop']) { // 배경처리 ?>
		$('#yt_<?php echo $widget_id;?>').on("YTPData", function(e){
			$('#<?php echo $widget_id;?>').css("background-image", "url(https://img.youtube.com/vi/" + e.prop.id + "/<?php echo($bg_q)?>.jpg)");
		});
		<?php } ?>
	<?php } ?>
	<?php if($is_use_slider) { ?>
	$('#<?php echo $widget_id;?> .bxslider').bxSlider({
		mode:'<?php echo ($wset['mode']) ? $wset['mode'] : 'horizontal';?>',
		pager:<?php echo ($wset['dot']) ? 'true' : 'false';?>,
		controls:<?php echo ($wset['nav']) ? 'false' : 'true';?>,
		auto:<?php echo ($wset['auto']) ? 'false' : 'true';?>,
		autoHover : <?php echo ($wset['hover']) ? 'false' : 'true';?>,
		prevText:'<i class="fa fa-angle-left"></i>',
		nextText:'<i class="fa fa-angle-right"></i>',
		adaptiveHeight: true,
		margin:0
	});
	<?php } ?>
});
</script>
<?php } ?>

<?php
// 비우기
unset($list);
unset($wset);
?>
