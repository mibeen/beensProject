<?php
include_once("./_common.php");
include_once(G5_LIB_PATH.'/apms.thema.lib.php');
include_once(G5_LIB_PATH.'/thumbnail.lib.php'); //썸네일 라이브러리 로딩
include_once(G5_PLUGIN_PATH.'/nx/common.php');


$g5['title'] = $fm['fm_subject'];


include_once('../bbs/_head.php');


# set : variables
$mode = $_GET['mode'];
$ord = $_GET['ord'];
$scate = $_GET['scate'];
$stx = $_GET['stx'];
$EM_IDX = $_GET['EM_IDX'];


# re-define
$EM_IDX = CHK_NUMBER($EM_IDX);
if ($EM_IDX <= 0) {
    F_script("잘못된 접근 입니다.", "history.back();");
}


/*
 CNT1 : 신청인원
 CNT2 : 승인인원
 CNT3 : 참석인원
 CNT4 : 내가 신청한 행사
 */
$sql = "Select EM.*"
    ."		, (Select Count(EJ_IDX) From NX_EVENT_JOIN Where EM_IDX = EM.EM_IDX And EJ_DDATE is null) As CNT1"
        ."		, (Select Count(EJ_IDX) From NX_EVENT_JOIN Where EM_IDX = EM.EM_IDX And EJ_DDATE is null And EJ_STATUS = '2') As CNT2"
            ."		, (Select Count(EJ_IDX) From NX_EVENT_JOIN Where EM_IDX = EM.EM_IDX And EJ_DDATE is null And EJ_STATUS = '2' And (EJ_JOIN_CHK1 = 'Y' And EJ_JOIN_CHK2 = 'Y')) As CNT3"
                ."		, (Select Count(EJ_IDX) From NX_EVENT_JOIN Where EM_IDX = EM.EM_IDX And EJ_DDATE is null And (mb_id != '' And mb_id = '" . mres($member['mb_id']) . "')) As CNT4"
                    ."		, EJ.EJ_IDX, EJ.EJ_STATUS, EJ.EJ_JOIN_CHK1, EJ.EJ_JOIN_CHK2"
                        ."		, ESM.ESM_S_DATE, ESM.ESM_E_DATE"
                            ."	From NX_EVENT_MASTER As EM"
                                ."		Left Join ("
                                    ."			Select *"
                                        ."				From NX_EVENT_JOIN"
                                            ."				Where EJ_DDATE is null"
                                                ."					And EM_IDX = '" . mres($EM_IDX) . "'"
                                                    ."					And (mb_id != '' And mb_id = '" . mres($member['mb_id']) . "')"
                                                        ."				Order By EJ_IDX Desc"
                                                            ."				Limit 1"
                                                                ."		) As EJ On EJ.EM_IDX = EM.EM_IDX"
                                                                    ."		Left Join NX_EVT_SATI_MA As ESM On ESM.EM_IDX = EJ.EM_IDX"
                                                                        ."	Where EM.EM_DDATE is null"
                                                                            ."		And EM.EM_IDX = '" . mres($EM_IDX) . "'"
                                                                                ."	Limit 1"
                                                                                    ;
                                                                                    $rs1 = sql_fetch($sql);
                                                                                    if (is_null($rs1['EM_IDX'])) {
                                                                                        unset($rs1);
                                                                                        F_script("존재하지 않는 정보 입니다.", "window.location.href='evt.list.php?mode=".$mode."';");
                                                                                    }
                                                                                    
                                                                                    
                                                                                    # get : files
                                                                                    $db_fls = get_file('NX_EVENT_MASTER', $rs1['EM_IDX']);
                                                                                    
                                                                                    
                                                                                    $nx_page_tit = ($mode == 'project') ? '공모사업 모집정보' : '모집 정보';
                                                                                    
                                                                                    
                                                                                    $is_modal_js = apms_script('modal_pop');
                                                                                    ?>
<p class="nx_page_tit"><?php echo($nx_page_tit)?></p>

<div class="taR mt30">
	<?php /*<a href="evt.list.php" class="nx_btn5" style="width:100px;">목록으로</a>*/ ?>
	<a role="button" href="./evt.list.php?mode=<?php echo(urlencode($mode))?>&ord=<?php echo(urlencode($ord))?>&stx=<?php echo(urlencode($stx))?>&scate=<?php echo $scate?>" class="btn btn-black btn-sm">
		<i class="fa fa-bars"></i><span class="hidden-xs"> 목록</span>
	</a>
</div>

<div class="event_info_wrap">
	<p class="tit"><?php echo(F_hsc($rs1['EM_TITLE']))?></p>
	<div class="title_img_wrap">
		<div class="title_img">
			<div class="inner">
				<?php
				# 썸네일 표시
				if ($db_fls[1]) {
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
					echo '	<img src="'.G5_URL.'/img/no_img.jpg'.'" alt="'.F_hsc($rs1['bf_source']).'" class="img" style="margin:0 auto;" />';
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
					echo $rs1['EM_S_DATE'].' ('.$wname[date('w', strtotime($rs1['EM_S_DATE']))].') '.substr($rs1['EM_S_TIME'], 0, 5).' ~ <br/>';
					echo $rs1['EM_E_DATE'].' ('.$wname[date('w', strtotime($rs1['EM_E_DATE']))].') '.substr($rs1['EM_E_TIME'], 0, 5);
				?></dd>
			</dl>
			<dl>
				<dt>신청기간</dt>
				<dd><?php
					echo substr($rs1['EM_JOIN_S_DATE'], 0, 16).' ('.$wname[date('w', strtotime($rs1['EM_JOIN_S_DATE']))].')';
					echo ' ~ ';
					echo substr($rs1['EM_JOIN_E_DATE'], 0, 16).' ('.$wname[date('w', strtotime($rs1['EM_JOIN_E_DATE']))].')';
				?></dd>
			</dl>
			<?php
			unset($wname);
			?>

			<dl>
				<dt>장소</dt>
				<dd>
					<?php 
					$addr1 = F_hsc($rs1['EM_ADDR1']);
					$addr2 = F_hsc($rs1['EM_ADDR2']);
					?>
					<?php echo(F_hsc($rs1['EM_ADDR']))?>
					<?php if($addr1 != ''){?><br/><?php  echo($addr1);}?>
					<?php if($addr2 != ''){?><br/><?php  echo($addr2);}?>
				</dd>
			</dl>
			<dl>
				<dt>담당자</dt>
				<dd><?php echo(F_hsc($rs1['EM_CG_NAME']))?></dd>
			</dl>
			<dl>
				<dt>Email</dt>
				<dd><?php
					if ($rs1['EM_CG_EMAIL'] != '') {
						echo '<a href="mailto:'.$rs1['EM_CG_EMAIL'].'">';
						echo F_hsc($rs1['EM_CG_EMAIL']);
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
					if ($rs1['EM_CG_TEL'] != '') {
						echo '<a href="tel:'.$rs1['EM_CG_TEL'].'">';
						echo F_hsc($rs1['EM_CG_TEL']);
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
					<?php echo(($rs1['EM_JOIN_MAX'] > 0) ? number_format($rs1['EM_JOIN_MAX']).'명' : '제한없음')?>
					<?php if ($rs1['EM_JOIN_TYPE'] == '3'){ 
					}else{?>
    					<?php
    					# 인원제한 있으면 그래프 표시
    					if ($rs1['EM_JOIN_MAX'] > 0) {
    						# 100% 초과시 100% 로 보임
    						if ($rs1['CNT1'] > $rs1['EM_JOIN_MAX']) {
    							$perc = (int)'100';
    						}
    						else {
    							$perc = (int)(($rs1['CNT1']/$rs1['EM_JOIN_MAX'])*100);
    						}
    						?>
    
    					<?php if ($rs1['EM_JOIN_E_DATE'] >= date('Y-m-d H:i')) { ?>
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

					}?>
				</dd>
			</dl>
		</div>
		<?php if ($rs1['EM_JOIN_TYPE'] == '3'){ ?>
		<?php }else{?>
    		<div class="btn_area mt30 mb5 evt-toolbox">
    			<?php
    			# 접수 이력이 있을 경우
    			if ($rs1['CNT4'] > 0)
    			{
    				# 접수기간 종료 + 행사기간 종료 = '확인증 인쇄/만족도조사' 버튼 보임
    				$_bo = false;
    				$EM_E_DATETIME = new DateTime($rs1['EM_E_DATE'] . ' ' . $rs1['EM_E_TIME']);
    				if ($rs1['EM_JOIN_E_DATE'] < date('Y-m-d H:i') && $EM_E_DATETIME->format('Y-m-d H:i') < date('Y-m-d H:i'))
    				{
    					# 승인 + 참석
    					if ($rs1['EJ_STATUS'] == '2' && ($rs1['EJ_JOIN_CHK1'] == 'Y' && $rs1['EJ_JOIN_CHK2'] == 'Y'))
    					{
    						echo '<a class="certi" href="javascript:void(0)" onclick="evt.cert({em_idx:\''.$rs1['EM_IDX'].'\',ej_idx:\''.$rs1['EJ_IDX'].'\'})">확인증 인쇄</a>';
    						$_bo = true;
    
    
    						# 만족도 조사기간일 경우 '만족도조사' 버튼 보임
    						if ($rs1['ESM_S_DATE'] <= date('Y-m-d') && $rs1['ESM_E_DATE'] >= date('Y-m-d')) {
    							echo '<a class="poll" href="javascript:void(0)" onclick="evt.sati({em_idx:\''.$rs1['EM_IDX'].'\',ej_idx:\''.$rs1['EJ_IDX'].'\'})">만족도조사</a>';
    						}
    					}
    				}
    				
    				if (!$_bo) {
    					echo '<a class="info" href="evt.join.read.php?EM_IDX='.$rs1['EM_IDX'].'" data-modal-title="신청 내역" '.$is_modal_js.'>신청 내역 보기</a>';
    				}
    			}
    			# 접수 이력 없음
    			else {
    				# 접수기간 = 신청하기
    				if ($rs1['EM_JOIN_S_DATE'] <= date('Y-m-d H:i') && $rs1['EM_JOIN_E_DATE'] >= date('Y-m-d H:i'))
    				{
    					# 로그인
    					if ($member['mb_id'] != '') {
    						echo '<a href="evt.join.php?EM_IDX='.$EM_IDX.'" class="regist" data-modal-title="참가신청" '.$is_modal_js.'>신청하기</a>';
    					}
    					# 로그인 하지 않음
    					else {
    						echo '<a href="javascript:void(0)" onclick="alert(\'로그인 후 이용할 수 있습니다.\');login_win();" class="regist">신청하기</a>';
    					}
    				}
    				# 접수기간 종료 = 마감
    				else if ($rs1['EM_JOIN_E_DATE'] < date('Y-m-d H:i')) {
    					echo '<a href="javascript:void(0)" class="disabled">모집이 마감되었습니다.</a>';
    				}
    				# 기타 = 접수준비중
    				else {
    					echo '<a href="javascript:void(0);" class="disabled">모집 기간이 아닙니다.</a>';
    				}
    			}
    			?>
    			<?php /*<a href="" class="favorite">관심 <span class="ico_heart"></span></a>*/ ?>
    		</div>
		<?php 
		}?>
		<?php
		# 접수 이력이 있을 경우
		if ($rs1['CNT4'] > 0)
		{
			?>
		<div class="state"><?php
			echo '상태 : ';

			# 미승인
			if ($rs1['EJ_STATUS'] == '1') {
				# 신청인원 <= 승인인원
				if ($rs1['CNT1'] <= $rs1['CNT2']) {
					echo '신청 (대기자)';
				}
				# 미승인
				else {
					echo '신청 (미승인)';
				}
			}
			# 승인
			else if ($rs1['EJ_STATUS'] == '2') {
				# 승인 + 참석
				if ($rs1['EJ_JOIN_CHK1'] == 'Y' && $rs1['EJ_JOIN_CHK2'] == 'Y') {
					echo '참석 완료';
				}
				# 승인 + 미참석
				else {
					echo '신청 완료';
				}
			}
		?></div>
			<?php
		}
		?>
		<!-- Go to www.addthis.com/dashboard to customize your tools --> <div class="mt20 addthis_inline_share_toolbox evt-toolbox"></div>
	</div>
</div>

<div>
	<div style="display:none;"><textarea id="hd_content" rows="1" cols="1"><?php echo(F_hsc($rs1['EM_CONT']))?></textarea></div>
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
# 첨부파일이 있을 경우
if ($db_fls[2]) {
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
if ($rs1['EM_ADDR'] != '')
{
	?>

	
<div class="mb20" <?php if($rs1['EM_ADDR_USE_YN'] == 'Y'){?>style="display:none"<?php }?> >
	<hr>
	<h3 style="margin: 20px 0 10px;color: #000;">장소(위치)</h3>
	
	<div style="line-height:40px"><?php echo(F_hsc($rs1['EM_ADDR']))?></div>
	<div id="map" class="map" style="width: 100%; height: 400px;"></div>
	
	<div style="margin-top:20px; line-height:40px;<?php if($addr1 == '' || is_null($addr1)){?>display:none;<?php }?>  "><?php echo($addr1)?></div>
	<div id="map1" class="map" style="width: 100%; height: 400px; <?php if($addr1 == '' || is_null($addr1)){?>display:none;<?php }?>"></div>
	
	<div style="margin-top:20px; line-height:40px;<?php if($addr2 == '' || is_null($addr2)){?>display:none;<?php }?>  "><?php echo($addr2)?></div>
	<div id="map2" class="map" style="width: 100%; height: 400px; <?php if($addr2 == '' || is_null($addr2)){?>display:none;<?php }?>"></div>
	
	<input type="hidden" id="map_address" name="map_address" value="<?php echo(F_hsc($rs1['EM_ADDR']))?>" />
	<input type="hidden" id="map_address1" name="map_address1" value="<?php echo(F_hsc($rs1['EM_ADDR1']))?>" />
	<input type="hidden" id="map_address2" name="map_address2" value="<?php echo(F_hsc($rs1['EM_ADDR2']))?>" />
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
	var map = new daum.maps.Map(mapContainer, mapOption); 
	var mapTypeControl = new daum.maps.MapTypeControl();
	map.addControl(mapTypeControl, daum.maps.ControlPosition.TOPRIGHT);
	var zoomControl = new daum.maps.ZoomControl();
	map.addControl(zoomControl, daum.maps.ControlPosition.RIGHT);
	var geocoder = new daum.maps.services.Geocoder();
	geocoder.addressSearch($('#map_address').val(), function(result, status) {
		if (status === daum.maps.services.Status.OK) {
			coords = new daum.maps.LatLng(result[0].y, result[0].x);
			var marker = new daum.maps.Marker({
				map: map,
				position: coords
			});
			map.setCenter(coords);
		}
	});  
	if($('#map_address1').val() != ""){
		////////////////////////////////////////////////////////////
		////////////////////////////////////////////////////////////
		////////////////////////////////////////////////////////////
		var coords = '';
    	var mapContainer1 = document.getElementById('map1'), // 지도를 표시할 div 
    	mapOption1 = {
    		center: new daum.maps.LatLng(33.450701, 126.570667), // 지도의 중심좌표
            draggable: false,
    		level: 3 // 지도의 확대 레벨
    	};  
    	var map1 = new daum.maps.Map(mapContainer1, mapOption1); 
    	var mapTypeControl1 = new daum.maps.MapTypeControl();
    	map1.addControl(mapTypeControl, daum.maps.ControlPosition.TOPRIGHT);
    	var zoomControl1 = new daum.maps.ZoomControl();
    	map1.addControl(zoomControl1, daum.maps.ControlPosition.RIGHT);
    	var geocoder1 = new daum.maps.services.Geocoder();
    	geocoder1.addressSearch($('#map_address1').val(), function(result, status) {
    		if (status === daum.maps.services.Status.OK) {
    			coords = new daum.maps.LatLng(result[0].y, result[0].x);
    			var marker1 = new daum.maps.Marker({
    				map: map1,
    				position: coords
    			});
    			map1.setCenter(coords);
    		}
    	});  
		////////////////////////////////////////////////////////////
		////////////////////////////////////////////////////////////
		////////////////////////////////////////////////////////////
	}
	if($('#map_address2').val() != ""){
		////////////////////////////////////////////////////////////
		////////////////////////////////////////////////////////////
		////////////////////////////////////////////////////////////
		var coords = '';
    	var mapContainer2 = document.getElementById('map2'), // 지도를 표시할 div 
    	mapOption2 = {
    		center: new daum.maps.LatLng(33.450701, 126.570667), // 지도의 중심좌표
            draggable: false,
    		level: 3 // 지도의 확대 레벨
    	};  
    	var map2 = new daum.maps.Map(mapContainer2, mapOption2); 
    	var mapTypeControl2 = new daum.maps.MapTypeControl();
    	map2.addControl(mapTypeControl, daum.maps.ControlPosition.TOPRIGHT);
    	var zoomControl2 = new daum.maps.ZoomControl();
    	map2.addControl(zoomControl2, daum.maps.ControlPosition.RIGHT);
    	var geocoder2 = new daum.maps.services.Geocoder();
    	geocoder2.addressSearch($('#map_address2').val(), function(result, status) {
    		if (status === daum.maps.services.Status.OK) {
    			coords = new daum.maps.LatLng(result[0].y, result[0].x);
    			var marker2 = new daum.maps.Marker({
    				map: map2,
    				position: coords
    			});
    			map2.setCenter(coords);
    		}
    	});
		////////////////////////////////////////////////////////////
		////////////////////////////////////////////////////////////
		////////////////////////////////////////////////////////////
	}
	
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
		if($('#map_address1').val() != ""){
			resizeMap1('map1', $el.width(), $el.height());
		}
		if($('#map_address2').val() != ""){
			resizeMap1('map2', $el.width(), $el.height());
		}
	}

	function resizeMap1(el, ww, hh)
	{
		if ($('#'+el).length <= 0) return;

		$('#'+el).css("width", ww);
		$('#'+el).css("height", hh);

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

<?php
	$C_TBNAME = "NX_EVENT_MASTER";
	$C_TBPK = $EM_IDX;
	include "../comment/list.php";
?>

<div style="display:none">
	<form id="frmSati" name="frmSati" onsubmit="return false">
		<input type="hidden" id="sati_em_idx" name="EM_IDX" value="" />
		<input type="hidden" id="sati_ej_idx" name="EJ_IDX" value="" />
	</form>
</div>

<script>
//<![CDATA[
var evt = {
	cert: function(o) {
		if (confirm("수료증을 인쇄 하시겠습니까?")) {
			var def = {em_idx: '', ej_idx: ''};
			var o = $.extend({}, def, o);
			
			if ((isNaN(o.em_idx) || parseInt(o.em_idx) <= 0) || (isNaN(o.ej_idx) || parseInt(o.ej_idx) <= 0)) return;

			window.open("", "evt_cert", "width=640, height=900, scrollbars=yes");

			var f = document.frmSati;
			f.action = 'evt.cert.outer.php';
			f.method = 'post';
			f.target = 'evt_cert';
			f.EM_IDX.value = o.em_idx;
			f.EJ_IDX.value = o.ej_idx;
			f.submit();
		}
	}
	, sati: function(o) {
		if (confirm("만족도 조사를 진행하시겠습니까?")) {
			var def = {em_idx: '', ej_idx: ''};
			var o = $.extend({}, def, o);
			
			if ((isNaN(o.em_idx) || parseInt(o.em_idx) <= 0) || (isNaN(o.ej_idx) || parseInt(o.ej_idx) <= 0)) return;

			window.open("" ,"evt_sati", "width=600, height=600, scrollbars=yes"); 
			
			var f = document.frmSati;
			f.action = '../evt/evt.sati.php';
			f.method = 'post';
			f.target = 'evt_sati';
			f.EM_IDX.value = o.em_idx;
			f.EJ_IDX.value = o.ej_idx;
			f.submit();
		}
	}
}
//]]>
</script>
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
