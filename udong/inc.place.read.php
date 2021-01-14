<?php
    include_once(G5_PLUGIN_PATH.'/nx/common.php');


    # 상세 페이지
    if ($lp_idx != '') {
        $sql = "Select lp.*
                        , la.la_name
                    From local_place As lp
                        Inner Join local_place_area As la On la.la_idx = lp.la_idx
                    Where lp.lp_ddate is null
                        And lp.lp_idx = '" . mres($lp_idx) . "'
                    limit 1";
        $LP = sql_fetch($sql);
    }
    # 리스트에 장소가 하나 뿐이면 상세페이지 보여짐
    else {
        $sql = "Select lp.*
                    From local_place As lp
                    {$wh}
                    Order By lp.lp_wdate Desc
                    limit 1";
        $LP = sql_fetch($sql);

        $lp_idx = $LP['lp_idx'];
    }


    if (!$lp_idx) {
        alert('게시글이 존재하지 않습니다.\\n삭제되었거나 자신의 글이 아닌 경우입니다.');
    }



    $LP['lp_name'] = get_text($LP['lp_name']);
    $LP['lp_tel'] = get_text($LP['lp_tel']);
    $LP['lp_email'] = get_text(get_email_address($LP['lp_email']));
    $LP['lp_address'] = get_text($LP['lp_address']);
    $LP['lp_intro'] = conv_content($LP['lp_intro'], $LP['lp_intro']);
    $LP['lp_info'] = conv_content($LP['lp_info'], $LP['lp_info']);
    $LP['lp_name_stx'] = $LP['lp_name'];
    $LP['lp_address_stx'] = $LP['lp_address'];


    # 검색어 색상 표시
    if ($stx != '') {
        $LP['lp_intro'] = search_font($stx, $LP['lp_intro']);
        $LP['lp_info'] = search_font($stx, $LP['lp_info']);
        $LP['lp_name_stx'] = search_font($stx, $LP['lp_name_stx']);
        $LP['lp_address_stx'] = search_font($stx, $LP['lp_address_stx']);
    }


    # list image
    $sql = "Select * From {$g5['board_file_table']} Where bo_table = '{$_file_table}' And wr_id = '" . mres($lp_idx) . "'";
    $db_FL = sql_query($sql);
    $_FL = sql_fetch_array($db_FL);

    if ($_FL['bf_file'] != '') {
        $LP['bf_file'] = $_FL['bf_file'];
        $LP['bf_source'] = $_FL['bf_source'];
    }


    # all image
    $FL_galls = array();
    for ($i = 0; $_FL = sql_fetch_array($db_FL); $i++) {

        $FL_galls[$i]['bf_file'] = $_FL['bf_file'];
        $FL_galls[$i]['bf_source'] = $_FL['bf_source'];
    }

    unset($db_FL, $_FL);
?>

<div class="coron_info_wrap">
    <div class="title_img_wrap">
        <div class="title_img">
            <div class="inner">
                <?php
                # 썸네일 생성
                if ($LP['bf_file'] != '') {
                    $thumb = thumbnail($LP['bf_file'], G5_PATH."/data/file/{$_file_table}", G5_PATH."/data/file/{$_file_table}", 704, 396, true);

                    if (!is_null($thumb)) {
                        echo '<img src="/data/file/'.$_file_table.'/'.$thumb.'" alt="'.htmlspecialchars($LP['bf_source']).'" />';
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
    <div class="coron_info">
        <p class="region"><?php echo $LP['la_name']; ?></p>
        <p class="tit"><?php echo $LP['lp_name_stx']; ?></p>
        <div class="coron_opt">
            <dl>
                <dt>문의전화</dt>
                <dd><?php echo $LP['lp_tel']; ?></dd>
            </dl>
            <dl>
                <dt>주소</dt>
                <dd><?php echo $LP['lp_address_stx']; ?></dd>
            </dl>
        </div>
        <div class="taR mt20">
            <?php
                if ($is_guest) {
                    $LP['req_link'] = "javascript:alert('로그인 후 이용 가능 합니다.');login_win()";
                }
                else {
                    $LP['req_link'] = "./req.list.php?lp_idx=".$lp_idx.$qstr;
                }
            ?>
            <a href="<?php echo($LP['req_link'])?>" class="coron_req">예약하기</a>
        </div>
    </div>
</div>   

<p class="coron_read_tit">장소소개</p>
<p>
    <?php echo $LP['lp_intro']; ?>
</p>
<hr>

<p class="coron_read_tit">장소 이용 정보</p>
<p>
    <?php echo $LP['lp_info']; ?>
</p>


<?php
# 사진
$_thumbs = array();
for ($i = 0; $i < Count($FL_galls); $i++)
{
    # 썸네일 생성
    $_file = thumbnail($FL_galls[$i]['bf_file'], G5_PATH."/data/file/{$_file_table}", G5_PATH."/data/file/{$_file_table}", 924, 520, true);

    if (!is_null($_file))
    {
        # 배열에 저장
        $_thumbs[] = array(
            'file' => '/data/file/'.$_file_table.'/'.$_file,
            'fname' => $FL_galls[$i]['bf_source']
        );
    }
}

# 사진이 하나라도 있을 경우 보임
if (Count($_thumbs) > 0) {
    ?>
<p class="coron_read_tit mt30">사진</p>
<ul class="coron_gall">
    <?php
    for ($i = 0; $i < Count($_thumbs); $i++) {
        $_thumb = $_thumbs[$i];
        ?>
    <li style="width:100%;margin:auto">
        <div class="img_wrap1">
            <div class="img_wrap2"><?php
                echo '<img src="'.$_thumb['file'].'" alt="'.htmlspecialchars($_thumb['fname']).'" class="img" />';
            ?></div>
        </div>
    </li>
        <?php
    }
    ?>
</ul>
    <?php
}
unset($_thumbs, $_thumb);
?>
    
<?php
#----- 위치
if ($LP['lp_address'] != '')
{
    ?>
<div class="place_map_wrap">
    <hr>
    <h4>위치</h4>
    <div style="line-height:40px"><?php echo($LP['lp_address_stx'])?></div>
    <div id="map" class="map" style="width: 100%; height: 400px;"></div>
    <input type="hidden" id="map_address" name="map_address" value="<?php echo($LP['lp_address'])?>" />
    <input type="hidden" id="map_title" name="map_title" value="<?php echo($LP['lp_name'])?>" />
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

        resizeMap1('map', $el.width(), $el.height());
    }

    function resizeMap1(el, ww, hh)
    {
        if ($('#'+el).length <= 0) return;

        $('#'+el).css("width", ww);
        $('#'+el).css("height", hh);

        //map.relayout();
        //map.setCenter(new daum.maps.LatLng(35.9645879, 126.9586316));
        map.setCenter(coords);
    }
    //]]>
    </script>
</div>
    <?php
}
#####
?>