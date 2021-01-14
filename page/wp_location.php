<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가 
?>
<div class="nx-page location">
	<p class="nx-tit1">지도</p>

	<div id="map" style="height: 300px;"></div>

	<p class="nx-tit1">기본정보</p>
	<table class="nx-ts1">
		<caption class="hidden">기본정보</caption>
		<colgroup>
			<col /><col width="" />
		</colgroup>
		<tbody>
			<tr>
				<th>주소</th>
				<td>경기도 양평군 용문면 연수로 209</td>
			</tr>
			<tr>
				<th>전화</th>
				<td>031-770-1500</td>
			</tr>
			<tr>
				<th>팩스</th>
				<td>031-770-1560</td>
			</tr>
		</tbody>
	</table>

	<p class="nx-tit1">버스로 오실 경우</p>
	<table class="nx-ts1">
		<caption class="hidden">버스로 오실 경우</caption>
		<colgroup>
			<col /><col width="" />
		</colgroup>
		<tbody>
			<tr>
				<th>용문역</th>
				<td>용문역 ≫ 용문 우체국으로 도보 이동(10분) ≫ 7-1 혹은 7-10 버스 탑승 ≫ 4개 정류장 후 ‘청소년야영장’에서 하차</td>
			</tr>
		</tbody>
	</table>
	
	<p class="nx-tit1">지하철, 열차로 오실 경우</p>
	<table class="nx-ts1">
		<caption class="hidden">지하철, 열차로 오실 경우</caption>
		<colgroup>
			<col /><col width="" />
		</colgroup>
		<tbody>
			<tr>
				<th>경의중앙선 / 무궁화, ITX 새마을호</th>
				<td>경의중앙선 / 무궁화, ITX 새마을호 ≫ 용문역 하차 후 용문면 내 택시 이용</td>
			</tr>
		</tbody>
	</table>

	<p class="nx-tit1">승용차로 오실 경우</p>
	<table class="nx-ts1">
		<caption class="hidden">승용차로 오실 경우</caption>
		<colgroup>
			<col /><col width="" />
		</colgroup>
		<tbody>
			<tr>
				<th>6번국도 팔당대교 IC</th>
				<td>6번국도 팔당대교 IC에서 양평, 홍천방향으로 약 24km, 오빈교차로에서 홍천(비발디파크)방면으로 약 10km, 용문터널 지나 용문교차로에서 용문면 방면으로 진입, 중진사거리에서 연수리 방면으로 좌회전 후 약 2km ⇒ 경기미래교육캠퍼스 양평캠프</td>
			</tr>
		</tbody>
	</table>
</div>
<script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=b603af8794432617e1d364bb0c3576ff&libraries=services"></script>
<script>
	
    var map_button = document.getElementById('map_search');
    var coods = '';
	var mapContainer = document.getElementById('map'), // 지도를 표시할 div 
	    mapOption = {
	        center: new daum.maps.LatLng(37.4987484, 127.5762743), // 지도의 중심좌표
	        level: 3 // 지도의 확대 레벨
	    };  

	// 지도를 생성합니다    
	var map = new daum.maps.Map(mapContainer, mapOption); 

	// 주소-좌표 변환 객체를 생성합니다
	var geocoder = new daum.maps.services.Geocoder();

	// 주소로 좌표를 검색합니다
	geocoder.addressSearch('경기도 양평군 용문면 연수로 209  ', function(result, status) {

    // 정상적으로 검색이 완료됐으면 
     if (status === daum.maps.services.Status.OK) {

        coords = new daum.maps.LatLng(result[0].y, result[0].x);

        // 결과값으로 받은 위치를 마커로 표시합니다
        var marker = new daum.maps.Marker({
            map: map,
            position: coords
        });

        // 인포윈도우로 장소에 대한 설명을 표시합니다
        var infowindow = new daum.maps.InfoWindow({
            content: '<div style="width:150px;text-align:center;padding:6px 0;">경기미래교육캠퍼스 양평본부</div>'
        });
        infowindow.open(map, marker);

        // 지도의 중심을 결과값으로 받은 위치로 이동시킵니다
	        map.setCenter(coords);
	    }
	});  
	$(document).ready(function() {
		on_tab1();
	});

	$(window).resize(function() {
		on_tab1();
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
		map.setCenter(coords);
	}

</script>