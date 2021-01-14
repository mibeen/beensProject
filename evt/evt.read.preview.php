<?php
	include_once("./_common.php");
	include_once(G5_LIB_PATH.'/apms.thema.lib.php');
	include_once(G5_LIB_PATH.'/thumbnail.lib.php'); //썸네일 라이브러리 로딩
	include_once(G5_PLUGIN_PATH.'/nx/common.php');


	$g5['title'] = $fm['fm_subject'];


	include_once('../bbs/_head.php');


	# set : variables
	$mode = $_GET['mode'];

	$EM_IDX = $_POST['EM_IDX'];
	$EC_IDX = $_POST['EC_IDX'];
	$EM_TITLE = $_POST['EM_TITLE'];
	$EM_TYPE = $_POST['EM_TYPE'];
	$EM_CG_NAME = $_POST['EM_CG_NAME'];
	$EM_CG_TEL1 = $_POST['EM_CG_TEL1'];
	$EM_CG_TEL2 = $_POST['EM_CG_TEL2'];
	$EM_CG_TEL3 = $_POST['EM_CG_TEL3'];
	$EM_CG_EMAIL = $_POST['EM_CG_EMAIL'];
	$EM_JOIN_TYPE = $_POST['EM_JOIN_TYPE'];
	$EM_NOTI_EMAIL = $_POST['EM_NOTI_EMAIL'];
	$EM_NOTI_SMS = $_POST['EM_NOTI_SMS'];
	$EM_CERT_TITLE = $_POST['EM_CERT_TITLE'];
	$EM_OPEN_YN = $_POST['EM_OPEN_YN'];
	
	$EM_S_DATE1 = $_POST['EM_S_DATE1'];
	$EM_S_DATE2 = $_POST['EM_S_DATE2'];
	$EM_S_DATE3 = $_POST['EM_S_DATE3'];
	$EM_S_TIME1 = $_POST['EM_S_TIME1'];
	$EM_S_TIME2 = $_POST['EM_S_TIME2'];

	$EM_E_DATE1 = $_POST['EM_E_DATE1'];
	$EM_E_DATE2 = $_POST['EM_E_DATE2'];
	$EM_E_DATE3 = $_POST['EM_E_DATE3'];
	$EM_E_TIME1 = $_POST['EM_E_TIME1'];
	$EM_E_TIME2 = $_POST['EM_E_TIME2'];
	$EM_CERT_TIME = $_POST['EM_CERT_TIME'];
	$EM_CERT_MINUTE = $_POST['EM_CERT_MINUTE'];

	$EM_JOIN_S_DATE1 = $_POST['EM_JOIN_S_DATE1'];
	$EM_JOIN_S_DATE2 = $_POST['EM_JOIN_S_DATE2'];
	$EM_JOIN_S_DATE3 = $_POST['EM_JOIN_S_DATE3'];
	$EM_JOIN_S_TIME1 = $_POST['EM_JOIN_S_TIME1'];
	$EM_JOIN_S_TIME2 = $_POST['EM_JOIN_S_TIME2'];

	$EM_JOIN_E_DATE1 = $_POST['EM_JOIN_E_DATE1'];
	$EM_JOIN_E_DATE2 = $_POST['EM_JOIN_E_DATE2'];
	$EM_JOIN_E_DATE3 = $_POST['EM_JOIN_E_DATE3'];
	$EM_JOIN_E_TIME1 = $_POST['EM_JOIN_E_TIME1'];
	$EM_JOIN_E_TIME2 = $_POST['EM_JOIN_E_TIME2'];

	$EM_JOIN_MAX = $_POST['EM_JOIN_MAX'];
	$EM_ADDR = $_POST['EM_ADDR'];
	$EM_CONT = $_POST['EM_CONT'];
	$FI_RNDCODE = $_POST['FI_RNDCODE'];

	
	# re-define
	$EM_IDX = CHK_NUMBER($EM_IDX);
	$EC_IDX = CHK_NUMBER($EC_IDX);
	$EM_TYPE = ((int)$EM_TYPE >= 1 && (int)$EM_TYPE <= 2) ? (int)$EM_TYPE : '';
	
	$EM_CG_TEL = "{$EM_CG_TEL1}-{$EM_CG_TEL2}-{$EM_CG_TEL3}";
	if ($EM_CG_TEL == '--') $EM_CG_TEL = '';
	
	$EM_JOIN_TYPE = ((int)$EM_JOIN_TYPE >= 1 && (int)$EM_JOIN_TYPE <= 2) ? (int)$EM_JOIN_TYPE : '';
	$EM_NOTI_EMAIL = F_YN($EM_NOTI_EMAIL,'N');
	$EM_NOTI_SMS = F_YN($EM_NOTI_SMS,'Y');
	$EM_CERT_TITLE = trim($EM_CERT_TITLE);
	$EM_OPEN_YN = F_YN($EM_OPEN_YN,'Y');
	
	$EM_S_DATE = "{$EM_S_DATE1}-{$EM_S_DATE2}-{$EM_S_DATE3}";
	if ($EM_S_DATE == '--') $EM_S_DATE = '';
	
	$EM_S_TIME1 = sprintf('%02d', (int)$EM_S_TIME1);
	$EM_S_TIME2 = sprintf('%02d', (int)$EM_S_TIME2);
	$EM_S_TIME = "{$EM_S_TIME1}:{$EM_S_TIME2}";
	if ($EM_S_TIME == ':') $EM_S_TIME = '';

	$EM_E_DATE = "{$EM_E_DATE1}-{$EM_E_DATE2}-{$EM_E_DATE3}";
	if ($EM_E_DATE == '--') $EM_E_DATE = '';
	
	$EM_E_TIME1 = sprintf('%02d', (int)$EM_E_TIME1);
	$EM_E_TIME2 = sprintf('%02d', (int)$EM_E_TIME2);
	$EM_E_TIME = "{$EM_E_TIME1}:{$EM_E_TIME2}";
	if ($EM_E_TIME == ':') $EM_E_TIME = '';

	$EM_CERT_TIME = ((int)$EM_CERT_TIME >= 1) ? (int)$EM_CERT_TIME : '';
	$EM_CERT_MINUTE = ((int)$EM_CERT_MINUTE >= 1) ? (int)$EM_CERT_MINUTE : '';

	$EM_JOIN_S_DATE = "{$EM_JOIN_S_DATE1}-{$EM_JOIN_S_DATE2}-{$EM_JOIN_S_DATE3}";
	if ($EM_JOIN_S_DATE == '--') $EM_JOIN_S_DATE = '';

	$EM_JOIN_S_TIME1 = sprintf('%02d', (int)$EM_JOIN_S_TIME1);
	$EM_JOIN_S_TIME2 = sprintf('%02d', (int)$EM_JOIN_S_TIME2);
	$EM_JOIN_S_TIME = "{$EM_JOIN_S_TIME1}:{$EM_JOIN_S_TIME2}";
	if ($EM_JOIN_S_TIME == ':') $EM_JOIN_S_TIME = '';

	$EM_JOIN_E_DATE = "{$EM_JOIN_E_DATE1}-{$EM_JOIN_E_DATE2}-{$EM_JOIN_E_DATE3}";
	if ($EM_JOIN_E_DATE == '--') $EM_JOIN_E_DATE = '';

	$EM_JOIN_E_TIME1 = sprintf('%02d', (int)$EM_JOIN_E_TIME1);
	$EM_JOIN_E_TIME2 = sprintf('%02d', (int)$EM_JOIN_E_TIME2);
	$EM_JOIN_E_TIME = "{$EM_JOIN_E_TIME1}:{$EM_JOIN_E_TIME2}";
	if ($EM_JOIN_E_TIME == ':') $EM_JOIN_E_TIME = '';

	if ($EM_JOIN_S_DATE != '') $EM_JOIN_S_DATE .= ' ' . $EM_JOIN_S_TIME;
	if ($EM_JOIN_E_DATE != '') $EM_JOIN_E_DATE .= ' ' . $EM_JOIN_E_TIME;

	$EM_JOIN_MAX = CHK_NUMBER($EM_JOIN_MAX);


	# 수정페이지인 경우 기존 파일정보 가져옴
	if ($EM_IDX != '') {
		# get : files
		$db_fls = get_file('NX_EVENT_MASTER', $EM_IDX);
	}


	$nx_page_tit = ($mode == 'project') ? '공모사업 모집정보' : '모집 정보';
?>

<p class="nx_page_tit"><?php echo($nx_page_tit)?></p>

<div class="taR mt30">
	<?php /*<a href="evt.list.php" class="nx_btn5" style="width:100px;">목록으로</a>*/ ?>
	<a role="button" href="./evt.list.php?mode=<?php echo(urlencode($mode))?>&ord=<?php echo(urlencode($ord))?>&stx=<?php echo(urlencode($stx))?>&scate=<?php echo $scate?>" class="btn btn-black btn-sm">
		<i class="fa fa-bars"></i><span class="hidden-xs"> 목록</span>
	</a>
</div>

<div class="event_info_wrap">
	<p class="tit"><?php echo(F_hsc($EM_TITLE))?></p>
	<div class="title_img_wrap">
		<div class="title_img">
			<div class="inner">
				<?php
				$tmp_file  = $_FILES['em_file']['tmp_name'][1];
				if (is_uploaded_file($tmp_file)) {
					$mime = mime_content_type($tmp_file);
					$imageData = file_get_contents($tmp_file);
					echo sprintf('<img src="data:'.$mime.';base64,%s" alt="" width="396" height="396" />', base64_encode($imageData));
				}
				else if ($db_fls[1]) {
					$thumb = thumbnail($db_fls[1]['file'], G5_PATH.'/data/file/NX_EVENT_MASTER', G5_PATH.'/data/file/NX_EVENT_MASTER', 396, 396, true); //704, 396
					if (!is_null($thumb)) {
						echo '<img src="/data/file/NX_EVENT_MASTER/'.$thumb.'" alt="'.F_hsc($db_fls[1]['source']).'" />';
					}
					else {
						echo '<div style="width:396px;">';
						echo '	<img src="'.G5_URL.'/img/no_img.jpg'.'" alt="'.F_hsc($rs1['bf_source']).'" class="img" style="margin:0 auto;" />';
						echo '</div>';
					}
					unset($thumb);
				}
				else {
					echo '<div style="width:396px;">';
					echo '	<img src="'.G5_URL.'/img/no_img.jpg'.'" alt="" class="img" style="margin:0 auto;" />';
					echo '</div>';
				}
				?>
			</div>
		</div>
	</div>
	<div class="event_info">
		<div class="event_opt mb20">
			<?php
			$wname = array('일','월','화','수','목','금','토');
			?>
			<dl>
				<dt>행사일자</dt>
				<dd><?php
					echo $EM_S_DATE.' ('.$wname[date('w', strtotime($EM_S_DATE))].') '.substr($EM_S_TIME, 0, 5).' ~ <br/>';
					echo $EM_E_DATE.' ('.$wname[date('w', strtotime($EM_E_DATE))].') '.substr($EM_E_TIME, 0, 5);
				?></dd>
			</dl>
			<dl>
				<dt>신청기간</dt>
				<dd><?php
					echo substr($EM_JOIN_S_DATE, 0, 16).' ('.$wname[date('w', strtotime($EM_JOIN_S_DATE))].')';
					echo ' ~ ';
					echo substr($EM_JOIN_E_DATE, 0, 16).' ('.$wname[date('w', strtotime($EM_JOIN_E_DATE))].')';
				?></dd>
			</dl>
			<?php
			unset($wname);
			?>

			<dl>
				<dt>장소</dt>
				<dd><?php echo(F_hsc($EM_ADDR))?></dd>
			</dl>
			<dl>
				<dt>담당자</dt>
				<dd><?php echo(F_hsc($EM_CG_NAME))?></dd>
			</dl>
			<dl>
				<dt>Email</dt>
				<dd><?php
					if ($EM_CG_EMAIL != '') {
						echo '<a href="mailto:'.$EM_CG_EMAIL.'">';
						echo F_hsc($EM_CG_EMAIL);
						echo '</a>';
					}
					else {
						echo ' ';
					}
				?></dd>
			</dl>
			<dl>
				<dt>연락처</dt>
				<dd><?php
					if ($EM_CG_TEL != '') {
						echo '<a href="tel:'.$EM_CG_TEL.'">';
						echo F_hsc($EM_CG_TEL);
						echo '</a>';
					}
					else {
						echo ' ';
					}
				?></dd>
			</dl>

			<dl>
				<dt>모집인원</dt>
				<dd>
					<?php echo(($EM_JOIN_MAX > 0) ? number_format($EM_JOIN_MAX).'명' : '제한없음')?>
					<?php
					# 인원제한 있으면 그래프 표시
					if ($EM_JOIN_MAX > 0) {
						# 100% 초과시 100% 로 보임
						if ($CNT1 > $EM_JOIN_MAX) {
							$perc = (int)'100';
						}
						else {
							$perc = (int)(($CNT1/$EM_JOIN_MAX)*100);
						}
						?>

					<?php if ($EM_JOIN_E_DATE >= date('Y-m-d H:i')) { ?>
					<div class="nx-graph1 mt5">
						<div class="gage_wrap">
							<div class="gage">
								<div class="fill" style="width: <?php echo($perc)?>%;"></div>
							</div>
						</div>
						<div class="count"><?php echo($perc)?>%</div>
					</div>
					<?php } ?>
						<?php
					}
					?>
				</dd>
			</dl>
		</div>
		<div class="btn_area mt30 mb5 evt-toolbox">
			<?php
				# 접수기간 = 신청하기
				if ($EM_JOIN_S_DATE <= date('Y-m-d H:i') && $EM_JOIN_E_DATE >= date('Y-m-d H:i'))
				{
					# 로그인
					if ($member['mb_id'] != '') {
						echo '<a class="regist">신청하기</a>';
					}
					# 로그인 하지 않음
					else {
						echo '<a href="javascript:void(0)" class="regist">신청하기</a>';
					}
				}
				# 접수기간 종료 = 마감
				else if ($EM_JOIN_E_DATE < date('Y-m-d H:i')) {
					echo '<a href="javascript:void(0)" class="disabled">모집이 마감되었습니다.</a>';
				}
				# 기타 = 접수준비중
				else {
					echo '<a href="javascript:void(0);" class="disabled">모집 기간이 아닙니다.</a>';
				}
			?>
		</div>

		<!-- Go to www.addthis.com/dashboard to customize your tools --> <div class="mt20 addthis_inline_share_toolbox evt-toolbox"></div>
	</div>
</div>

<div>
	<div style="display:none;"><textarea id="hd_content" rows="1" cols="1"><?php echo(F_hsc($EM_CONT))?></textarea></div>
	<script type="text/javascript">
	//<![CDATA[
	document.write('<iframe src="about:blank" id="if_cont" name="if_cont" width="100%" height="100%" border="0" frameborder="0" framespacing="0" scrolling="auto" vspace="0" style="border:none; margin:0; padding:0; width:100%;"></iframe>');

	var innerVal = ''
	+ '<style type="text/css">@import url(//fonts.googleapis.com/earlyaccess/notosanskr.css);*{padding:0; margin:0;} body{font-size:14px; line-height:18px; font-family: \'Noto Sans KR\', sans-serif, Tahoma,Verdana,Arial} img {border:none; max-width: 100%;} p{margin: .5em auto;}</style>'
	+ '<div id="placeHolderContent" style="width:100%; overflow-y:hidden;">'+$('#hd_content').val()+'</div>'
	+ '<scr'+'ipt type="text/javascript" language="javascript" src="/lib/jquery-1.12.4.min.js"></scr'+'ipt>'
	+ '<scr'+'ipt type="text/javascript">'
	+ 'window.onload = function() { setTimeout( function() { parent.document.getElementById("if_cont").height = (Number(($("#placeHolderContent").css("height")).replace("px", "")) + 20); }, 1) };'
	+ '</scr'+'ipt>';

	frames.window["if_cont"].document.open();
	frames.window["if_cont"].document.write(innerVal);
	frames.window["if_cont"].document.close();
	//]]>
	</script>
</div>

<?php
# 변경한 첨부파일이 있을 경우
if ($_FILES['em_file']['name'][2]) {
	if (is_uploaded_file($_FILES['em_file']['tmp_name'][2])) {
		?>
<div class="mb20">
	<hr>
	<h3 style="margin: 20px 0 10px;color: #000;">첨부파일</h3>
	<div style="line-height:40px"><a><?php echo(F_hsc($_FILES['em_file']['name'][2]))?></a></div>
</div>
		<?php
	}
}
# DB에 첨부파일이 있을 경우
else if ($db_fls[2] && $db_fls[2]['file'] != '') {
	if (file_exists(G5_DATA_PATH.'/file/NX_EVENT_MASTER/'.$db_fls[2]['file'])) {
		set_session('ss_view_NX_EVENT_MASTER_'.$EM_IDX, TRUE);
		?>
<div class="mb20">
	<hr>
	<h3 style="margin: 20px 0 10px;color: #000;">첨부파일</h3>
	<div style="line-height:40px"><a href="<?php echo $db_fls[2]['href']?>"><?php echo(F_hsc($db_fls[2]['source']))?></a></div>
</div>
		<?php
	}
}
?>

<?php
if ($EM_ADDR != '')
{
	?>
<div class="mb20">
	<hr>
	<h3 style="margin: 20px 0 10px;color: #000;">장소(위치)</h3>
	<div style="line-height:40px"><?php echo(F_hsc($EM_ADDR))?></div>
	<div id="map" class="map" style="width: 100%; height: 400px;"></div>
	<input type="hidden" id="map_address" name="map_address" value="<?php echo(F_hsc($EM_ADDR))?>" />
	<script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=<?php echo(NX__DAUM_MAP_TOKEN)?>&libraries=services"></script>
	<script>
	//<![CDATA[
	var map_button = document.getElementById('map_search');
	var coords = '';
	var mapContainer = document.getElementById('map'), // 지도를 표시할 div 
	mapOption = {
		center: new daum.maps.LatLng(33.450701, 126.570667), // 지도의 중심좌표
        draggable: false,
		level: 3 // 지도의 확대 레벨
	};  

	// 지도를 생성합니다    
	var map = new daum.maps.Map(mapContainer, mapOption); 

	// 일반 지도와 스카이뷰로 지도 타입을 전환할 수 있는 지도타입 컨트롤을 생성합니다
	var mapTypeControl = new daum.maps.MapTypeControl();

	// 지도에 컨트롤을 추가해야 지도위에 표시됩니다
	// daum.maps.ControlPosition은 컨트롤이 표시될 위치를 정의하는데 TOPRIGHT는 오른쪽 위를 의미합니다
	map.addControl(mapTypeControl, daum.maps.ControlPosition.TOPRIGHT);

	// 지도 확대 축소를 제어할 수 있는  줌 컨트롤을 생성합니다
	var zoomControl = new daum.maps.ZoomControl();
	map.addControl(zoomControl, daum.maps.ControlPosition.RIGHT);

	// 주소-좌표 변환 객체를 생성합니다
	var geocoder = new daum.maps.services.Geocoder();

	// 주소로 좌표를 검색합니다
	geocoder.addressSearch($('#map_address').val(), function(result, status) {

		// 정상적으로 검색이 완료됐으면 
		if (status === daum.maps.services.Status.OK) {


			coords = new daum.maps.LatLng(result[0].y, result[0].x);

			// 결과값으로 받은 위치를 마커로 표시합니다
			var marker = new daum.maps.Marker({
				map: map,
				position: coords
			});

			// 인포윈도우로 장소에 대한 설명을 표시합니다
			// var infowindow = new daum.maps.InfoWindow({
			// 	content: '<div style="width:150px;text-align:center;padding:6px 0;">aaaa</div>'
			// });
			// infowindow.open(map, marker);

			// 지도의 중심을 결과값으로 받은 위치로 이동시킵니다
			map.setCenter(coords);
		}
	});  
	$(document).ready(function() {

		$(window).load(function(){

			on_tab1();
			
			$(window).resize(function() {
				on_tab1();
			});

		})

		
	});


	function on_tab1() {
		var $el = $('.map_wrap');
		$el.height();
		$el.width();

		resizeMap1('map', $el.width(), $el.height());
	}

	function resizeMap1(el, ww, hh)
	{
		if ($('#'+el).length <= 0) return;

		$('#'+el).css("width", ww);
		$('#'+el).css("height", hh);

		//map.relayout();
		//map.setCenter(new daum.maps.LatLng(35.9645879, 126.9586316));

		// 검색된 장소가 없으면 코드를 실행하지 않는다.
		// 검색된 장소가 없을 경우 어떻게 처리??
		// 1) 지도를 띄우질 않거나,
		// 2) 관리자단에서 위성값 받아서 처리

		if(coords.length > 1){
			map.setCenter(coords);	
		}
		
	}
	//]]>
	</script>
</div>
	<?php
}
?>

<div style="display:none">
	<form id="frmSati" name="frmSati" onsubmit="return false">
		<input type="hidden" id="sati_em_idx" name="EM_IDX" value="" />
		<input type="hidden" id="sati_ej_idx" name="EJ_IDX" value="" />
	</form>
</div>

<!-- Go to www.addthis.com/dashboard to customize your tools --> <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=<?php echo(NX__SNS_BTN_TOKEN)?>"></script>
<?php 
	# 메뉴 표시에 사용할 변수 
	if ($mode == 'project') {
		$_gr_id = 'project';  // 게시판 그룹 ID - 현재 메뉴표시에 사용
		$pid = ($pid) ? $pid : 'project';   // Page ID
	}
	else {
		$_gr_id = 'gseek';  // 게시판 그룹 ID - 현재 메뉴표시에 사용
		$pid = ($pid) ? $pid : 'evtlist';   // Page ID
	}

	include "../page/inc.page.menu.php";

	include_once('../bbs/_tail.php');
?>
