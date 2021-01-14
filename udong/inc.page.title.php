<p class="nx_page_tit">우리동네 학습공간</p>
 <!-- SNS공유툴 !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! -->
		<div class="print-hide view-icon view-padding"  style="float:right;">
			<span class="pull-right">
				<!-- Go to www.addthis.com/dashboard to customize your tools SNS공유툴 --><div class="addthis_inline_share_toolbox" style="float:left;"></div>
				<img src="<?php echo G5_IMG_URL;?>/sns/print.png" alt="프린트" class="cursor at-tip" onclick="apms_print();" data-original-title="프린트" data-toggle="tooltip" style="width:30px; border-radius:50%;">
			</span>
			<div class="clearfix"></div>
		</div>
		<div class="clearfix"></div>
		<!-- !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! -->
<!-- 
<p class="mb50 taC">
    <?php /* 지역의 유휴 공간을 일정 시간 기부 받아 학습 공간이 필요한 일반인들에게 무료로 제공합니다. (소정의 실비 발생 가능)<br>
    학습공간을 이용하시고자 하는 분은 해당 문의전화로 연락하여 사전에 예약진행 해주시기 바랍니다. */ ?>
    지역의 유휴공간을 일정시간 기부받아 학습공간이 필요한 일반인들에게 무료로 제공합니다.(소정의 실비 발생가능) <br>
    학습공간을 이용하실 분은 홈페이지 로그인(회원가입) 후, 원하시는 학습공간을 선택하여 '예약하기' 클릭하여 신청서를 작성해주시기 바랍니다.<br>
    예약 승인관련 문의는 해당시설로 직접하시기 바랍니다.<br>
    <a href="<?php echo G5_BBS_URL;?>/board.php?bo_table=materials&wr_id=34" target="_blank" 
	style=" display: inline-block; vertical-align: middle; background: #5eaad0; border:1px solid #5eaad0; color:#fff; font-weight:bold; width:9%; text-align:center;">이용수칙</a>
<a href="<?php echo G5_BBS_URL;?>/board.php?bo_table=materials&wr_id=35" target="_blank" 
	style=" display: inline-block; vertical-align: middle; background: #5eaad0; border:1px solid #5eaad0; color:#fff; font-weight:bold; width:9%; text-align:center;">매뉴얼</a>
</p>

 -->
<p class="mb10" style="text-align: right">
	문의 ) 경기도청 <b>031-8008-4562</b>
</p>
<?php
##### 엑셀 다운로드 버튼 시작
if ( $is_admin == "super" ) {
	?>
	<div style="text-align: right; padding-bottom:20px;">
		<button type="button" onclick=" document.location.href = '/udong/udong_list.excel.php'; " style="background: #5eaad0; border: 0; outline: 0; color: #FFF; padding: 5px 15px;">전체 엑셀 다운로드</button>
	</div>
	<?php
}
##### 엑셀 다운로드 버튼 끝
?>
<p class="mb10" style="padding-left: 20px;">

	<b>□ 우리동네 학습공간은?</b><br>
    우리동네의 다양한 학습장소의 <b>유휴시간을 활용</b>, 시설주의 <b>공간기부</b>를 통해 지역주민들이 학습활동으로<br>
    함께 배우고 나눔을 실천하는 <b>‘지역커뮤니티 학습공간’</b>입니다.<br>
    <br>
    <b>□ 우리동네 학습공간 이용수칙</b> 
    <!-- <a href="<?php echo G5_BBS_URL;?>/board.php?bo_table=materials&wr_id=34" target="_blank" >(<b style="color:blue;">평생학습-평생학습자료실 11번</b>)</a>  -->
    <a href="<?php echo G5_BBS_URL;?>/board.php?bo_table=materials&wr_id=34" target="_blank" 
    style=" display: inline-block; vertical-align: middle; background: #5eaad0; border:1px solid #5eaad0; color:#fff; font-weight:bold; width:9%; text-align:center;">이용수칙</a>
  	<br>
    1. <b style="color:red;">정치적·종교적·개인 영리적(상업 등)</b> 활동을 <b style="color:red;">지양</b>합니다.<br>
    2. <b style="color:red;">학습공간 시설</b>은 시설주분들의 <b>좋은 마음</b>으로 <b>기부</b>해주신 것임으로 <b style="color:red;">사용에 주의</b>해주시 바랍니다.<br>
    3. 학습공간이 필요한 일반인에게 무료로 제공하지만 소정의 비용이(관리비) 발생 될 수 있습니다. <br>
    <br>
    <b>□ 우리동네 학습공간 홈페이지 예약시스템 이용매뉴얼</b> 
    <!-- <a href="<?php echo G5_BBS_URL;?>/board.php?bo_table=materials&wr_id=35" target="_blank" >(<b style="color:blue;">평생학습- 평생학습자료실 12번</b>)</a> -->
    <a href="<?php echo G5_BBS_URL;?>/board.php?bo_table=materials&wr_id=35" target="_blank" 
    style=" display: inline-block; vertical-align: middle; background: #5eaad0; border:1px solid #5eaad0; color:#fff; font-weight:bold; width:9%; text-align:center;">매뉴얼</a>
    <br>
    학습공간을 이용하실 분은 홈페이지 로그인 후, 학습공간을 선택하여 ‘예약하기’ 에서 신청서를 작성해 <br>
    주시기 바랍니다. 예약 확인은 해당시설로 직접 문의 바랍니다.
</p>

<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-58bff1ea9221b257"></script>
