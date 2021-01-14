<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가 
?>
<div class="nx-page location">
	<p class="nx-tit1">지도</p>

	<div id="map2" style="height: 300px;"></div>

	<p class="nx-tit1">기본정보</p>
	<table class="nx-ts1">
		<caption class="hidden">기본정보</caption>
		<colgroup>
			<col /><col width="" />
		</colgroup>
		<tbody>
			<tr>
				<th>주소</th>
				<td>경기도 파주시 탄현면 얼음실로 40 경기미래교육 파주캠퍼스</td>
			</tr>
			<tr>
				<th>전화</th>
				<td>1588-0554, 031)956-2000</td>
			</tr>
			<!-- <tr>
				<th>팩스</th>
				<td>-</td>
			</tr> -->
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
				<th>2200(좌석버스)</th>
				<td>합정역(60분)-출판단지(20분)-경기미래교육캠퍼스</td>
			</tr>
			<tr>
				<th>900(시내버스)</th>
				<td>일산현대백화점(80분)-대화역(80분)-금촌역(20분)-경기미래교육캠퍼스</td>
			</tr>
			<tr>
				<th>033(마을버스)</th>
				<td>금촌역(20분)-문산제일고-경기미래교육캠퍼스-헤이리-탄현산업단지</td>
			</tr>
			<tr>
				<th>035(마을버스)</th>
				<td>금촌역(20분)-금촌로터리-성동사거리-경기미래교육캠퍼스-통일초교</td>
			</tr>
			<tr>
				<th>075(마을버스)</th>
				<td>맥금동-경기미래교육캠퍼스-파주프리미엄아울렛-교하동 우리은행</td>
			</tr>
		</tbody>
	</table>
	
	<p class="nx-tit1">전철, 지하철로 오실 경우</p>
	<table class="nx-ts1">
		<caption class="hidden">전철, 지하철로 오실 경우</caption>
		<colgroup>
			<col /><col width="" />
		</colgroup>
		<tbody>
			<tr>
				<th>금촌역(파주)</th>
				<td>900, 033, 035 - 금촌역 1번출구, 금촌역 정류장</td>
			</tr>
			<tr>
				<th>대화역(고양)</th>
				<td>900 - 대화역 5번출구, 대화역 정류장 승차</td>
			</tr>
			<tr>
				<th>합정역(서울)</th>
				<td>2200 - 합정역 1번출구 정류장 승차</td>
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
				<td>자유로를 타고 성동IC에서 나와 약 1.6km를 이정표를 따라 직진(성동사거리를 지남) 후 헤이리사거리에서 좌회전하여 약 500m지점에 위치</td>
			</tr>
			<tr>
				<td>서울외곽순환도로 이용시 자유로분기점에서 '문산,킨텍스'방면으로 자유로를 따라 약 27km를 직진하시면 성동IC가 나옵니다.</td>
			</tr>
			<tr>
				<td>곳곳에 이정표와 안내표시가 설치되어 있으니 참고하시기 바랍니다.</td>
			</tr>
			<tr>
				<td>자가용 이용시 네비게이션에 ‘경기미래교육캠퍼스’로 검색하시면 찾기 쉽습니다.</td>
			</tr>
		</tbody>
	</table>
</div>



<script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=b603af8794432617e1d364bb0c3576ff&libraries=services"></script>
<script>
	
//map2 파주**************************************************************************************************************************************
var mapContainer = document.getElementById('map2'), // 지도를 표시할 div
mapOption = {
center: new kakao.maps.LatLng(37.785139, 126.703719), // 지도의 중심좌표
level: 3 // 지도의 확대 레벨
};
var map = new kakao.maps.Map(mapContainer, mapOption);
// 마커가 표시될 위치입니다
var markerPosition = new kakao.maps.LatLng(37.785139, 126.703719);
// 마커를 생성합니다
var marker = new kakao.maps.Marker({
position: markerPosition
});
// 마커가 지도 위에 표시되도록 설정합니다
marker.setMap(map);
var iwContent = '<div style="padding:5px;text-align:center;">파주캠퍼스</div>', // 인포윈도우에 표출될 내용으로 HTML 문자열이나 document element가 가능합니다
//var iwContent = '<div style="padding:5px;text-align:center;">경기도평생교육진흥원 <br><a href="https://map.kakao.com/link/map/경기도평생교육진흥원,37.314963, 126.989527" style="color:blue" target="_blank">큰지도보기</a> <a href="https://map.kakao.com/link/to/Hello World!,37.314963, 126.989527" style="color:blue" target="_blank">길찾기</a></div>', // 인포윈도우에 표출될 내용으로 HTML 문자열이나 document element가 가능합니다
iwPosition = new kakao.maps.LatLng(37.785139, 126.703719); //인포윈도우 표시 위치입니다
// 인포윈도우를 생성합니다
var infowindow = new kakao.maps.InfoWindow({
position : iwPosition,
content : iwContent
});
// 마커 위에 인포윈도우를 표시합니다. 두번째 파라미터인 marker를 넣어주지 않으면 지도 위에 표시됩니다
infowindow.open(map, marker);

</script>