<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가 
include_once("./_common.php");
include_once(G5_LIB_PATH.'/apms.thema.lib.php');
include_once(G5_LIB_PATH.'/thumbnail.lib.php'); //썸네일 라이브러리 로딩
include_once('../bbs/_head.php');
?>


<ul id="locationTab" class="tab1">
	<li style="list-style:none;" class="on"><a href="javascript:void(0)" onclick="changeTab('0'); return false;">수원(본원)</a></li>            
	<li style="list-style:none;"><a href="javascript:void(0)" onclick="changeTab('1'); return false;">수원(정책본부)</a></li>     
	<li style="list-style:none;"><a href="javascript:void(0)" onclick="changeTab('2'); return false;">파주캠퍼스</a></li>         
	<li style="list-style:none;"><a href="javascript:void(0)" onclick="changeTab('3'); return false;">양평캠퍼스</a></li>            
	
</ul>
<!-- *********************************************locationDiv0 본원******************************************************************************** -->
<div id="locationDiv0">
<div class="nx-page location">
	<p class="nx-tit1">지도</p>

	<div id="map0" style="height: 300px;"></div>

	<p class="nx-tit1">기본정보</p>
	<table class="nx-ts1">
		<caption class="hidden">기본정보</caption>
		<colgroup>
			<col /><col width="" />
		</colgroup>
		<tbody>
			<tr>
				<th>주소</th>
				<td>수원시 장안구 경수대로 1150(파장동 179) 13층 경기도평생교육진흥원</td>
			</tr>
			<tr>
				<th>전화</th>
				<td>031)547-6500</td>
			</tr>
			<tr>
				<th>팩스</th>
				<td>031)547-6565</td>
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
				<th>사당역(지하철 2호선, 4호선)</th>
				<td>7770번(20분 소요), 777번(50분 소요)-사당역(지하철2호선, 4호선 4번출구)</td>
			</tr>
			<tr>
				<th>양재역(지하철 3호선)</th>
				<td>3000번(30분 소요)</td>
			</tr>
			<tr>
				<th>수원역</th>
				<td>900, 777, 7770번(30분 소요)</td>
			</tr>
			<tr>
				<th>수원시외버스터미널</th>
				<td>30, 900, 777, 7770번(30분 소요)</td>
			</tr>
			<tr>
				<th>범계역(지하철 4호선)</th>
				<td>900번(30분 수요)</td>
			</tr>
			<tr>
				<th>명학역(지하철 1호선)</th>
				<td>64, 65번(수원 진입후 이목동에서 하차. 도보로 10분 소요)</td>
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
				<th>경부고속도로·신갈-안산고속도로 연계이용할 경우</th>
				<td>신갈IC에서 안산·인천방향 → 북수원IC에서 수원방향 → 지지대 고개 → 인재원</td>
			</tr>
			<tr>
				<th>과천-의왕간 고속도로를 이용할 경우</th>
				<td>판교 → 서울외곽도로 → 청계요금소 → 학의 분기점에서 수원방향 → TG에서 북수원→ 경수산업도로 수원방향 → 지지대 고개 → 인재원</td>
			</tr>
			<tr>
				<th>과천-의왕간 고속도로를 이용할 경우</th>
				<td>사당사거리에서 과천방향 → 남태령 → 관문사거리(우회전하면 과천)를 직진통과 →과천·의왕간 고속도로 진입(서울대공원 고가도로 우측밑으로) → 수원방향(좌측차로)→ TG(매표소) → 북수원(노면표지확인) → 경수산업도로 수원방향 → 지지대고개 → 인재원</td>
			</tr>
			<tr>
				<th>시흥대로 이용할 경우</th>
				<td>시흥대로 → 안양 → 의왕 → 인재원</td>
			</tr>
		</tbody>
	</table>
</div>
</div>
<!-- *********************************************locationDiv1 분원******************************************************************************** -->
<div id="locationDiv1" >
<div class="nx-page location">
	<p class="nx-tit1">지도</p>

	<div id="map1" style="height: 300px;"></div>

	<p class="nx-tit1">기본정보</p>
	<table class="nx-ts1">
		<caption class="hidden">기본정보</caption>
		<colgroup>
			<col /><col width="" />
		</colgroup>
		<tbody>
			<tr>
				<th>주소</th>
				<td>경기도 수원시 장안구 경수대로 1045 에스씨씨빌딩 5층 경기도평생교육진흥원 정책본부</td>
			</tr>
			<tr>
				<th>전화</th>
				<td>031)547-6500</td>
			</tr>
			<tr>
				<th>팩스</th>
				<td>031)547-6565, 2699</td>
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
				<th>사당역(지하철 2호선, 4호선)</th>
				<td>7770번(20분 소요), 777번(50분 소요)-사당역(지하철2호선, 4호선 4번출구)</td>
			</tr>
			<tr>
				<th>양재역(지하철 3호선)</th>
				<td>3000번(30분 소요)</td>
			</tr>
			<tr>
				<th>수원역</th>
				<td>900, 777, 7770번(30분 소요)</td>
			</tr>
			<tr>
				<th>수원시외버스터미널</th>
				<td>30, 900, 777, 7770번(30분 소요)</td>
			</tr>
			<tr>
				<th>범계역(지하철 4호선)</th>
				<td>900번(30분 수요)</td>
			</tr>
			<tr>
				<th>명학역(지하철 1호선)</th>
				<td>64, 65번(수원 진입후 이목동에서 하차. 도보로 10분 소요)</td>
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
				<th>경부고속도로·신갈-안산고속도로 연계이용할 경우</th>
				<td>신갈IC에서 안산·인천방향 → 북수원IC에서 수원방향 → 지지대 고개 → 인재원 → 한국가스안전공사</td>
			</tr>
			<tr>
				<th>과천-의왕간 고속도로를 이용할 경우</th>
				<td>판교 → 서울외곽도로 → 청계요금소 → 학의 분기점에서 수원방향 → TG에서 북수원→ 경수산업도로 수원방향 → 지지대 고개 → 인재원 → 한국가스안전공사</td>
			</tr>
			<tr>
				<th>과천-의왕간 고속도로를 이용할 경우</th>
				<td>사당사거리에서 과천방향 → 남태령 → 관문사거리(우회전하면 과천)를 직진통과 →과천·의왕간 고속도로 진입(서울대공원 고가도로 우측밑으로) → 수원방향(좌측차로)→ TG(매표소) → 북수원(노면표지확인) → 경수산업도로 수원방향 → 지지대고개 → 인재원 → 한국가스안전공사</td>
			</tr>
			<tr>
				<th>시흥대로 이용할 경우</th>
				<td>시흥대로 → 안양 → 의왕 → 인재원 → 한국가스안전공사</td>
			</tr>
		</tbody>
	</table>

	</table>
</div>
</div>

<!-- *********************************************locationDiv2 파주******************************************************************************** -->
<div id="locationDiv2" >
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
</div>

<!-- *********************************************locationDiv3 양평******************************************************************************** -->
<div id="locationDiv3" >
<div class="nx-page location">
	<p class="nx-tit1">지도</p>

	<div id="map3" style="height: 300px;"></div>

	<p class="nx-tit1">기본정보</p>
	<table class="nx-ts1">
		<caption class="hidden">기본정보</caption>
		<colgroup>
			<col /><col width="" />
		</colgroup>
		<tbody>
			<tr>
				<th>주소</th>
				<td>경기도 양평군 용문면 연수로 209 경기미래교육 양평캠퍼스</td>
			</tr>
			<tr>
				<th>전화</th>
				<td>031)770-1500</td>
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
</div>



<script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=b603af8794432617e1d364bb0c3576ff&libraries=services"></script>
<script>
//map0수원본원**************************************************************************************************************************************
var mapContainer = document.getElementById('map0'), // 지도를 표시할 div
mapOption = {
center: new kakao.maps.LatLng(37.314963, 126.989527), // 지도의 중심좌표
level: 3 // 지도의 확대 레벨
};
var map = new kakao.maps.Map(mapContainer, mapOption);
// 마커가 표시될 위치입니다
var markerPosition = new kakao.maps.LatLng(37.314963, 126.989527);
// 마커를 생성합니다
var marker = new kakao.maps.Marker({
position: markerPosition
});
// 마커가 지도 위에 표시되도록 설정합니다
marker.setMap(map);
var iwContent = '<div style="padding:3px;text-align:center;font-size:14px;">경기도평생교육진흥원<br>수원(본원)</div>', // 인포윈도우에 표출될 내용으로 HTML 문자열이나 document element가 가능합니다
//var iwContent = '<div style="padding:5px;text-align:center;">경기도평생교육진흥원 <br><a href="https://map.kakao.com/link/map/경기도평생교육진흥원,37.314963, 126.989527" style="color:blue" target="_blank">큰지도보기</a> <a href="https://map.kakao.com/link/to/Hello World!,37.314963, 126.989527" style="color:blue" target="_blank">길찾기</a></div>', // 인포윈도우에 표출될 내용으로 HTML 문자열이나 document element가 가능합니다
iwPosition = new kakao.maps.LatLng(37.314963, 126.989527); //인포윈도우 표시 위치입니다
// 인포윈도우를 생성합니다
var infowindow = new kakao.maps.InfoWindow({
position : iwPosition,
content : iwContent
});
// 마커 위에 인포윈도우를 표시합니다. 두번째 파라미터인 marker를 넣어주지 않으면 지도 위에 표시됩니다
infowindow.open(map, marker);

//map1수원분원**************************************************************************************************************************************
var mapContainer = document.getElementById('map1'), // 지도를 표시할 div
mapOption = {
center: new kakao.maps.LatLng(37.3075946,126.9974749), // 지도의 중심좌표
level: 3 // 지도의 확대 레벨
};
var map = new kakao.maps.Map(mapContainer, mapOption);
// 마커가 표시될 위치입니다
var markerPosition = new kakao.maps.LatLng(37.3075946,126.9974749);
// 마커를 생성합니다
var marker = new kakao.maps.Marker({
position: markerPosition
});
// 마커가 지도 위에 표시되도록 설정합니다
marker.setMap(map);
var iwContent = '<div style="padding:3px;text-align:center;font-size:14px;">경기도평생교육진흥원<br>수원(정책본부)</div>', // 인포윈도우에 표출될 내용으로 HTML 문자열이나 document element가 가능합니다
iwPosition = new kakao.maps.LatLng(37.3075946,126.9974749); //인포윈도우 표시 위치입니다
// 인포윈도우를 생성합니다
var infowindow = new kakao.maps.InfoWindow({
position : iwPosition,
content : iwContent
});
// 마커 위에 인포윈도우를 표시합니다. 두번째 파라미터인 marker를 넣어주지 않으면 지도 위에 표시됩니다
infowindow.open(map, marker);


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


//map3 양평**************************************************************************************************************************************
var mapContainer = document.getElementById('map3'), // 지도를 표시할 div
mapOption = {
center: new kakao.maps.LatLng(37.499651, 127.576371), // 지도의 중심좌표
level: 3 // 지도의 확대 레벨
};
var map = new kakao.maps.Map(mapContainer, mapOption);
//마커가 표시될 위치입니다
var markerPosition = new kakao.maps.LatLng(37.499651, 127.576371);
//마커를 생성합니다
var marker = new kakao.maps.Marker({
position: markerPosition
});
//마커가 지도 위에 표시되도록 설정합니다
marker.setMap(map);
var iwContent = '<div style="padding:5px;text-align:center;">양평캠퍼스</div>', // 인포윈도우에 표출될 내용으로 HTML 문자열이나 document element가 가능합니다
iwPosition = new kakao.maps.LatLng(37.499651, 127.576371); //인포윈도우 표시 위치입니다
//인포윈도우를 생성합니다
var infowindow = new kakao.maps.InfoWindow({
position : iwPosition,
content : iwContent
});
//마커 위에 인포윈도우를 표시합니다. 두번째 파라미터인 marker를 넣어주지 않으면 지도 위에 표시됩니다
infowindow.open(map, marker);


for(i=1 ; i<4 ; i++) { 
	document.getElementById("locationDiv"+i).style.display = "none"; 
}

function changeTab(num){
	//tab
	$('li').removeClass('on');
	$("#locationTab").children().eq(num).addClass("on");
	//div
	for(i=0 ; i<4 ; i++) { 
		document.getElementById("locationDiv"+i).style.display = "none"; 
	}
	document.getElementById("locationDiv"+num).style.display = "block";
}

</script>