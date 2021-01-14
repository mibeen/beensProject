<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가 
?>


<style>
	.table1{width: 100%;}

	.table1 tr.bold td{background: #DDD; text-align: center; font-weight: bold;}

	.table1 th, .table1 td{border-bottom: 1px solid #DDD; padding: 10px 5px; text-align: center;}
	.table1 td:not(.bold){text-indent: 10px; }

	.table1 button{background: #FFF; font-size: 14px; padding: 5px 10px; border-radius: 4px; border: 1px solid #DDD; outline: none; background: #DDD;}
</style>


<form method="post" id="frmFlag" name="frmFlag">
	<input type="hidden" name="flag" id="flag">
</form>

<table class="table1">
	<tbody><tr class="bold tr_bg">
		<td colspan="2">구분</td>
		<td>면적</td>
		<td>수량</td>
		<td>수용인원</td>
		<td>상세보기</td>
	</tr>
	<tr>
		<td class="bold" rowspan="3">교육시설</td>
		<td>대강의실</td>
		<td>-</td>
		<td>1</td>
		<td>70명</td>
		<td><button onclick="javascript:goFacilDtl('EDU','1')">상세보기</button></td>
	</tr>
	<tr>
		<td>중강의실</td>
		<td>-</td>
		<td>1</td>
		<td>40명</td>
		<td><button onclick="javascript:goFacilDtl('EDU','2')">상세보기</button></td>
	</tr>
	<tr class="tr_line">
		<td>소강의실</td>
		<td>-</td>
		<td>4</td>
		<td>25명</td>
		<td><button onclick="javascript:goFacilDtl('EDU','3')">상세보기</button></td>
	</tr>
	<tr>
		<td class="bold" rowspan="2">숙박시설</td>
		<td>침대(3인)</td>
		<td>-</td>
		<td>34</td>
		<td>102명</td>
		<td><button onclick="javascript:goFacilDtl('STY','1')">상세보기</button></td>
	</tr>
	<tr class="tr_line">
		<td>온돌(4인)</td>
		<td>-</td>
		<td>7</td>
		<td>28명</td>
		<td><button onclick="javascript:goFacilDtl('STY','2')">상세보기</button></td>
	</tr>
	<tr>
		<td class="bold" rowspan="5">기타시설</td>
		<td>대운동장(잔디)</td>
		<td>-</td>
		<td>1</td>
		<td>-</td>
		<td><button onclick="javascript:goFacilDtl('ETC','1')">상세보기</button></td>
	</tr>
	<tr>
		<td>소운동장</td>
		<td>-</td>
		<td>1</td>
		<td>-</td>
		<td><button onclick="javascript:goFacilDtl('ETC','2')">상세보기</button></td>
	</tr>
	<tr>
		<td>멀티플랙스(농구,풋살)</td>
		<td>-</td>
		<td>1</td>
		<td>-</td>
		<td><button onclick="javascript:goFacilDtl('ETC','3')">상세보기</button></td>
	</tr>
	<tr>
		<td>족구/테니스장(야간이용 가능)</td>
		<td>-</td>
		<td>1</td>
		<td>-</td>
		<td><button onclick="javascript:goFacilDtl('ETC','4')">상세보기</button></td>
	</tr>
	<tr>
		<td>주차장</td>
		<td>-</td>
		<td>가능</td>
		<td>-</td>
		<td><button onclick="javascript:goFacilDtl('ETC','5')">상세보기</button></td>
	</tr>
</tbody>
</table>





<div class="h30"></div>
