<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
include_once(G5_PLUGIN_PATH.'/nx/common.php');
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$qa_skin_url.'/style.css">', 0);

$is_modal_js = apms_script('modal');
?>

<script src="<?php echo G5_JS_URL; ?>/viewimageresize.js"></script>

<div class="data_ct">
    <p class="nx_page_tit">대관 정보</p>
    <div class="taR mt30 mb10">
        <!--<a href="<?php echo $list_href?>" class="nx_btn5" style="width:100px;">목록으로</a>-->
        <a role="button" href="<?php echo $list_href?>" class="btn btn-black btn-sm">
            <i class="fa fa-bars"></i><span class="hidden-xs"> 목록</span>
        </a>
    </div>
    <div class="coron_info_wrap">
        <div class="title_img_wrap">
            <div class="title_img">
                <div class="inner"><?php
                    $_bo_table = 'place_rental';

                    # 썸네일 생성
                    $thumb = thumbnail($view['bf_file'], G5_PATH."/data/file/{$_bo_table}", G5_PATH."/data/file/{$_bo_table}", 704, 396, true);

                    if (!is_null($thumb)) {
                        echo '<img src="/data/file/'.$_bo_table.'/'.$thumb.'" alt="'.htmlspecialchars($view['bf_source']).'" />';
                    }
                    else {
                        echo '<div class="empty_img"></div>';
                    }

                    unset($thumb, $_bo_table);
                ?></div>
            </div>
        </div>
        <div class="coron_info">
            <p class="region"><?php echo $view['PA_NAME']; ?></p>
            <p class="tit"><?php echo $view['PM_NAME']; ?></p>
            <div class="coron_opt">
                <dl>
                    <dt>담당자</dt>
                    <dd><?php echo $view['PM_CHARGE']; ?></dd>
                </dl>
                <dl>
                    <dt>대표전화</dt>
                    <dd><?php echo $view['PM_TEL']; ?></dd>
                </dl>
                <dl>
                    <dt>대표메일</dt>
                    <dd><?php echo $view['PM_EMAIL']; ?></dd>
                </dl>
                <dl>
                    <dt>주소</dt>
                    <dd><?php echo $view['PM_ADDRESS']; ?></dd>
                </dl>
            </div>
        </div>
    </div>   
    
    <p class="coron_read_tit">장소소개</p>
    <p>
        <?php echo $view['PM_INFO']; ?>
    </p>

    <!-- 강의실 리스트 -->
    <div class="coron_detail_lst mt30">
        <p class="p1">강의실</p>
        <ul class="lst">
            <?php
            $_bo_table = 'place_rental_sub';

            for ($i=0; $i<count($row_sub_1); $i++)
            {
                ?>
            <li>
                <?php
                # 썸네일 생성
                $thumb = thumbnail($row_sub_1[$i]['bf_file'], G5_PATH."/data/file/{$_bo_table}", G5_PATH."/data/file/{$_bo_table}", 112, 63, true);

                if (!is_null($thumb)) {
                    echo '<img src="/data/file/'.$_bo_table.'/'.$thumb.'" alt="'.htmlspecialchars($row_sub_1[$i]['bf_source']).'" class="img" />';
                }
                else {
                    echo '<div class="empty_img"></div>';
                }
                ?>
                <p class="tit"><?php echo $row_sub_1[$i]['PS_NAME']; ?></p>
                <div class="btn_wrap">
                    <a href="<?php echo $row_sub_1[$i]['view_href']; ?>" class="detail" data-modal-title="상세보기" <?php echo($is_modal_js)?>>상세보기</a>
                    <?php /*<a href="<?php echo $row_sub_1[$i]['view_href']; ?>"  class="detail">상세보기</a>*/ ?>
                    <a href="<?php echo $row_sub_1[$i]['req_href']; ?>" class="reserve nx-auth">예약하기</a>
                </div>
            </li>
                <?php
            }

            if ($i == 0) {
                echo '<li class="nodata">게시물이 없습니다.</li>';
            }
            unset($thumb, $_bo_table);
            ?>
        </ul>

        <p class="p1 mt30">숙소</p>
        <ul class="lst">
            <?php
            $_bo_table = 'place_rental_sub';

            for ($i=0; $i<count($row_sub_2); $i++)
            {
                ?>
            <li>
                <?php
                # 썸네일 생성
                $thumb = thumbnail($row_sub_2[$i]['bf_file'], G5_PATH."/data/file/{$_bo_table}", G5_PATH."/data/file/{$_bo_table}", 112, 63, true);

                if (!is_null($thumb)) {
                    echo '<img src="/data/file/'.$_bo_table.'/'.$thumb.'" alt="'.htmlspecialchars($row_sub_2[$i]['bf_source']).'" class="img" />';
                }
                else {
                    echo '<div class="empty_img"></div>';
                }
                ?>
                <p class="tit"><?php echo $row_sub_2[$i]['PS_NAME']; ?></p>
                <div class="btn_wrap">
                    <a href="<?php echo $row_sub_2[$i]['view_href']; ?>" class="detail" data-modal-title="상세보기" <?php echo($is_modal_js)?>>상세보기</a>
                    <a href="<?php echo $row_sub_2[$i]['req_href']; ?>" class="reserve nx-auth">예약하기</a>
                </div>
            </li>
                <?php
            }

            if ($i == 0) {
                echo '<li class="nodata">게시물이 없습니다.</li>';
            }
            unset($thumb, $_bo_table);
            ?>
        </ul>
    </div>

    <!-- 사진 -->
    <?php
    $_bo_table = 'place_rental';
    $_tns = array();
    for ($i = 0; $i < Count($place_image_list); $i++)
    {
    	# 썸네일 생성
    	$_file = thumbnail($place_image_list[$i]['bf_file'], G5_PATH."/data/file/{$_bo_table}", G5_PATH."/data/file/{$_bo_table}", 924, 520, true);

    	if (!is_null($_file))
    	{
    		# 배열에 저장
    		$_tns[] = array(
    			'file' => '/data/file/'.$_bo_table.'/'.$_file,
    			'fname' => $place_image_list[$i]['bf_source']
    		);
    	}
    }

    # 사진이 하나라도 있을 경우 보임
    if (Count($_tns) > 0) {
    	?>
	<p class="coron_read_tit mt30">사진</p>
	<ul class="coron_gall">
	    <?php
	    for ($i = 0; $i < Count($_tns); $i++) {
            $_tn = $_tns[$i];
            ?>
	    <li style="width:100%;margin:auto">
	        <div class="img_wrap1">
	            <div class="img_wrap2"><?php
	                echo '<img src="'.$_tn['file'].'" alt="'.htmlspecialchars($_tn['fname']).'" class="img" />';
	            ?></div>
	        </div>
	    </li>
            <?php
	    }
	    ?>
	</ul>
    	<?php
    }
    unset($_tns, $_bo_table);
    ?>
        
    <?php
    #----- 위치
    if ($view['PM_ADDRESS'] != '')
    {
        ?>
    <div class="place_map_wrap">
        <hr>
        <h4>위치</h4>
        <div style="line-height:40px"><?php echo($view['PM_ADDRESS'])?></div>
        <div id="map" class="map" style="width: 100%; height: 400px;"></div>
        <input type="hidden" id="map_address" name="map_address" value="<?php echo($view['PM_ADDRESS'])?>" />
        <input type="hidden" id="map_title" name="map_title" value="<?php echo($view['PM_NAME'])?>" />
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

        // 주소-좌표 변환 객체를 생성합니다
        var geocoder = new daum.maps.services.Geocoder();

        // 일반 지도와 스카이뷰로 지도 타입을 전환할 수 있는 지도타입 컨트롤을 생성합니다
        var mapTypeControl = new daum.maps.MapTypeControl();

        // 지도에 컨트롤을 추가해야 지도위에 표시됩니다
        // daum.maps.ControlPosition은 컨트롤이 표시될 위치를 정의하는데 TOPRIGHT는 오른쪽 위를 의미합니다
        map.addControl(mapTypeControl, daum.maps.ControlPosition.TOPRIGHT);

        // 지도 확대 축소를 제어할 수 있는  줌 컨트롤을 생성합니다
        var zoomControl = new daum.maps.ZoomControl();
        map.addControl(zoomControl, daum.maps.ControlPosition.RIGHT);

        /* 정규식 - 건물명만 남기고 제거  */
        var detailed_addr = '';
        var pattern = /\d+-\d+\s|\d+\s/g;
        var regex = pattern.exec($('#map_address').val());
        if (regex != null) {
            detailed_addr = regex.input.slice(regex.index + regex[0].length);
        }

        // 주소로 좌표를 검색합니다
        geocoder.addressSearch($('#map_address').val(), function(result, status) {

            // 정상적으로 검색이 완료됐으면 
            if (status === daum.maps.services.Status.OK) {

                coords = new daum.maps.LatLng(result[0].y, result[0].x);

                <?php # 상세주소가 있다면 주변검색 ?>
                if (detailed_addr != '') {
                    map.setCenter(coords);

                    var ps = new daum.maps.services.Places(map); 

                    ps.keywordSearch(detailed_addr, placesSearchCB); 
                }
                else {
                    // 결과값으로 받은 위치를 마커로 표시합니다
                    var marker = new daum.maps.Marker({
                        map: map,
                        position: coords
                    });

                    // 인포윈도우로 장소에 대한 설명을 표시합니다
                    var infowindow = new daum.maps.InfoWindow({
                     content: '<div style="width:150px;text-align:center;padding:6px 0;">'+$('#map_title').val()+'</div>'
                    });
                    infowindow.open(map, marker);

                    // 지도의 중심을 결과값으로 받은 위치로 이동시킵니다
                    map.setCenter(coords);
                }
            }
        });

        function placesSearchCB (data, status, pagination) {
            if (status === daum.maps.services.Status.OK) {

                // 검색된 장소 위치를 기준으로 지도 범위를 재설정하기위해
                // LatLngBounds 객체에 좌표를 추가합니다
                var bounds = new daum.maps.LatLngBounds();

                for (var i=0; i<data.length; i++) {
                    displayMarker(data[i]);    
                    bounds.extend(new daum.maps.LatLng(data[i].y, data[i].x));
                }

                // 검색된 장소 위치를 기준으로 지도 범위를 재설정합니다
                map.setBounds(bounds);
            } 
        }

        // 지도에 마커를 표시하는 함수입니다
        function displayMarker(place) {
            
            // 마커를 생성하고 지도에 표시합니다
            var marker = new daum.maps.Marker({
                map: map,
                position: new daum.maps.LatLng(place.y, place.x) 
            });

            // 마커 인포윈도우
            var infowindow = new daum.maps.InfoWindow({
                 content: '<div style="width:150px;text-align:center;padding:6px 0;">'+$('#map_title').val()+'</div>'
                });
                infowindow.open(map, marker);
        }


        function on_tab1() {
            var $el = $('.map_wrap');
            $el.height();
            $el.width();

            resizeMap('map', $el.width(), $el.height());
        }

        function resizeMap(el, ww, hh)
        {
            if ($('#'+el).length <= 0) return;

            $('#'+el).css("width", ww);
            $('#'+el).css("height", hh);

            //map.relayout();
            //map.setCenter(new daum.maps.LatLng(35.9645879, 126.9586316));
            map.setCenter(coords);
        }

        $(document).ready(function() {
            // 모든 DOM이 로드된 이후에 스크립트 실행
            $(window).load(function(){
                on_tab1();
                $(window).resize(function() {
                    on_tab1();
                });  
            })
        });
        //]]>
        </script>
    </div>
        <?php
    }
    #####
    ?>

    <br>
    <br>
    
</div>

<div class="taR mt10">
    <!--<a href="<?php echo $list_href?>" class="nx_btn5" style="width:100px;">목록으로</a>-->

    <a role="button" href="<?php echo $list_href?>" class="btn btn-black btn-sm">
        <i class="fa fa-bars"></i><span class="hidden-xs"> 목록</span>
    </a>
</div>
