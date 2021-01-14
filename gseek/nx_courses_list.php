<?php

	include_once("../common.php");
	include_once(G5_LIB_PATH.'/apms.thema.lib.php');

	$g5['title'] = $fm['fm_subject'];

	# set : variables
	include_once('../bbs/_head.php');

	$SIGUNGU_CD_ALL = $_REQUEST['sigungu_cd_all'];    // 지역 선택 전체
	$SIGUNGU_CD = htmlspecialchars($_REQUEST['sigungu_cds']);    // 지역 선택
	$COURSE_PTTN_ALL = $_REQUEST['course_pttn_all'];  // 학습 목적 전체
	$COURSE_PTTN = htmlspecialchars($_REQUEST['course_pttns']);  // 학습 목적
	$RECEIVE_DT = $_REQUEST['receive_dt'];    // 접수 마감일
	$COURSE_NM = htmlspecialchars($_REQUEST['course_nm']);  // 강좌명
	$SORT_TYPE = $_REQUEST['sort'];   // 정렬 방법
	$N_PAGE = $_REQUEST['N_PAGE']; // 조회 개수
	$PAGE = $_REQUEST['PAGE'];    // 현재 페이지
	$TOTAL_CNT = 0;
	
	# re-define
	if(!$SIGUNGU_CD) $SIGUNGU_CD_ALL = 'all';
	if(!$COURSE_PTTN) $COURSE_PTTN_ALL = 'all';
	if(!$RECEIVE_DT) $RECEIVE_DT = 'all';
	if(!$N_PAGE) $N_PAGE = 10;
	if(!$PAGE) $PAGE = 1;
	
	# 검색 조건
	$cond =  "FROM   institutes AS I "
	        ."JOIN   courses AS C "
	        ."  ON   I.INST_ID = C.INST_ID "
	        ."WHERE  C.COLLECTED_DATE >= '2018.01.01' "
	        ."  AND  C.INST_ID != '' "
	        .(($SIGUNGU_CD_ALL == 'all') ? "" : " AND C.SIGUNGU_CD IN (" . $SIGUNGU_CD . ") ") // 지역 선택
	        .(($COURSE_PTTN_ALL == 'all') ? "" : " AND C.COURSE_PTTN_CD REGEXP '" . $COURSE_PTTN . "' ") // 학습 목적
	        .(($RECEIVE_DT == 'all') ? "" : " AND C.RECEIVE_END_DT <= DATE_ADD(NOW(), INTERVAL " . $RECEIVE_DT . " MONTH) ") // 접수 마감일
	        .(($COURSE_NM == '') ? "" : " AND C.COURSE_NM LIKE '%" . $COURSE_NM . "%' ")
	        .($SORT_TYPE == "DESC" ? " AND  C.RECEIVE_END_DT >= NOW() " : "") // 정렬 방법 : 접수가능 강좌
	        .($SORT_TYPE == "ASC" ? " AND  C.RECEIVE_END_DT < NOW() " : "") // 정렬 방법 : 접수종료 강좌
	;
	
	# 총 강좌수
	$n_sql =   "SELECT COUNT(C.INST_ID) AS TOTAL_CNT " . $cond;
	$n_data = sql_query($n_sql);
	if (sql_num_rows($n_data) > 0) {
	   $rs1 = sql_fetch_array($n_data);
	   $TOTAL_CNT = $rs1['TOTAL_CNT'];
	} else {
	   $TOTAL_CNT = 0;
	}
	                    
	# 리스트 조회
	$sql =   "SELECT "
	        ."  C.COURSE_ID, "
	        ."  C.INST_ID, "
	        ."  COURSE_PTTN_CD, "
	        ."  C.COURSE_URL, "
	        ."  IFNULL(SUBSTR(C.COURSE_NM, 1, (500/CAST(BIT_LENGTH(SUBSTR(C.COURSE_NM,1,1))/8 AS UNSIGNED))),'') AS COURSE_NM, "
	        ."  I.INST_NM, "
	        ."  C.RECEIVE_START_DT, "
	        ."  C.RECEIVE_END_DT, "
	        ."  I.HOMEPAGE_URL, "
	        ."  C.EDU_LOCATION_DESC, "
	        ."  C.COURSE_START_DT, "
	        ."  C.COURSE_END_DT, "
	        ."  C.INQUIRY_TEL_NO, "
	        ."  IFNULL(SUBSTR(C.ENROLL_AMT, 1, (200/CAST(BIT_LENGTH(SUBSTR(C.ENROLL_AMT,1,1))/8 AS UNSIGNED))),'') AS ENROLL_AMT, "
	        ."  REPLACE(C.EDU_QUOTA_CNT, '명', '') AS EDU_QUOTA_CNT "
	        .$cond // 검색 조건
	        ."ORDER BY "
	        .($SORT_TYPE == "COL_DT" ? " C.COLLECTED_DATE DESC, " : "") // 정렬 방법 : 최신순
	        ."  CASE "
	        ."    WHEN C.COURSE_START_DT = '' THEN 0 "
	        ."    WHEN C.COURSE_START_DT NOT LIKE '2%' THEN 1 "
	        ."    ELSE C.COURSE_START_DT END "
	        ."  DESC "
	        ."LIMIT " . $N_PAGE * ($PAGE - 1) . " , " . $N_PAGE
	;
	$item = sql_query($sql);
	
	$total_page = ceil($TOTAL_CNT / $N_PAGE);
?>
<style>
    /*강좌목록*/
    dl, dd, ul, li{ list-style: none; padding: 0; }
    
    .pt03 { color: #bc346d; }
    .ico{ display: inline-block; padding: 1px 4px 4px 4px; line-height: 100%; color: #fff; font-size: 11px; font-weight: normal; background: #989898; vertical-align: middle; margin: 0px 2px 2px 2px; }
    .img-ico { display: inline-block; text-indent: -9999px; overflow: hidden; background: url(../img/icon.png);}
    .img-ico.offline { width: 10px; height: 9px; background-position: -134px 0px; }
    
    .courses .nodata { text-align: center; padding: 10px 0; border-top: 1px solid #E3E3E3; border-bottom: 1px solid #E3E3E3; }
    
    .courses dd:after,
    .courses dd > ul:after{ content:''; clear: both; display: block; }
    
    .courses dt a{ color: #333; font-size: 18px; font-weight: bold; transition: all .2s ease-in-out; }
    
    .courses li:hover dt a{ color: #1f60d6; }
    
    .courses dd{ margin-top: 5px; background: #fbfbfb; border-top: 1px solid #e3e3e3; border-bottom: 1px solid #e3e3e3; *zoom: 1; }
    .courses dd > ul { float: left; width: 33.33333333%; padding: 10px 0; line-height: 21px; }
    .courses dd > ul > li { position: relative; list-style: disc; margin-left: 30px; padding-left: 55px; overflow: hidden; table-layout: fixed; word-break: break-all; font-size: 12px; color: #666; white-space: nowrap; -ms-text-overflow: ellipsis; text-overflow: ellipsis; overflow: hidden;}
    .courses dd > ul > li span { position: absolute; top: 0; left: 0; }
    
    
    @media screen and (max-width: 768px) {
    	.courses dt{ position: relative; padding-right: 80px; word-break: keep-all; }
    	.courses .ico{ position: absolute; right: 0; top: 50%; margin-top: -8px; }
    
    	.courses dd > ul { width: 50%; padding-bottom: 0; }
    	.courses dd > ul:last-child { width: 100%; padding: 0 0 10px; }
    	.courses dd > ul:last-child > li { float: left; width: calc(50% - 30px); }
    }
    
    @media screen and (max-width: 576px) {
    	.courses dd > ul { width: 100%; padding: 0; }
    	.courses dd > ul:nth-child(1) { padding-top: 10px; }
    	.courses dd > ul li { width: 100%; margin-left: 0; padding-left: 90px; }
    	.courses dd > ul:last-child > li { float: none; width: 100%; }
    
    	.courses dd > ul > li span { position: absolute; top: 0; left: 20px; }
    }
    
    /*강좌정보검색*/
    .class-search{padding:5px 10px; background:#58addd; font-size:1.5em;color:#fff;font-weight:bold; min-height:44px; padding-top:10px;}
    .class-area{border-bottom: 1px solid #d8d6d6}
    .classArea-head{display:inline-block; width:15%; min-width:120px; background:#f5f5f5;color: #797979; font-weight: bold; font-size:1.1em; text-indent:23px}
    .classArea-body{display:inline-block; width:81%; margin-left:7px; line-height: 130%; vertical-align:middle}
    .classArea-body span{ display:inline-block; width:83px; text-align:left}
    .classArea-body input[type="checkbox"], .classArea-body input[type="radio"]{margin-top:-5px}
    .classArea-body input[type="radio"]{}
    .classArea-body input[type="text"]{width:70%; margin-top: -10px; border:1px solid #d8d6d6; text-indent:10px}
    .classArea-body label{font-weight:100}
    /* head-height */
    .class-wrab .class-area:nth-of-type(1) .classArea-head{line-height: 720%;}
    .class-wrab .class-area:nth-of-type(2) .classArea-head{line-height: 420%;}
    .class-wrab .class-area:nth-of-type(3) .classArea-head{line-height: 270%;}
    .class-wrab .class-area:nth-of-type(4) .classArea-head{line-height: 270%;}
    
    /*검색 버튼*/
    .class-btn{ display: inline-block; float:right; width:17%; min-width:162px; vertical-align: middle; margin-right:10px;}
    .class-btn button{padding:2% 14%; font-weight:bold}
    .class-btn .search-btn{background: #5eaad0;border:1px solid #5eaad0; color:#fff}
    .class-btn .close-btn{background:#fcfeff; border:1px solid #5eaad0; color:#5eaad0}
    
    /* 셀렉트 박스 */
    .class-select{display:inline-block; width:100%; margin-top:20px; margin-bottom: 25px; padding:8px 0; background:#fbfbfb; border-top:1px solid #e3e3e3; border-bottom:1px solid #e3e3e3;}
    .class-select .class-text{display:inline-block; margin-left:10px; font-size:1.15em; margin-top:2px;}
    .class-select .class-text>span{font-weight: bold}
    .class-select .class-text>span>span{display:inline-block; margin-left:5px; color:#3F9DE3; font-weight:bold;}
    .class-select .select-box{display:inline-block; float:right; margin-top:2px; margin-right:10px;}
    .class-select select{border: 1px solid #ccc;}
    
    @media screen and (max-width: 1047px){
    .class-wrab .class-area:nth-of-type(1) .classArea-head{line-height: 800%;}
    .class-wrab .class-area:nth-of-type(2) .classArea-head{line-height: 500%;}
    }
    
    /*반응형*/
    @media screen and (max-width: 768px){
    .classArea-head{ display:block; width:100%; margin:0; text-align:center; text-indent: 0;}	
    .classArea-body{display:block; width:100%; margin:12px 0; text-align:center}	
    .classArea-body input[type="text"]{width:90%;}
    .class-select .class-text{font-size:1em;}
    .class-btn{display:block; width:100%; margin-top:10px; padding-top:10px; text-align:center; border-top: 1px solid #e3f6fd;}
    .class-btn button{margin: 0 3%; padding: 1% 11%;}		
    .class-wrab .class-area:nth-of-type(1) .classArea-head, .class-wrab .class-area:nth-of-type(2) .classArea-head, .class-wrab .class-area:nth-of-type(3) .classArea-head, .class-wrab .class-area:nth-of-type(4) .classArea-head{line-height:200%;}
    }
</style>


<script src="<?php echo G5_URL.'/gseek/jquery.cookie.js';?>"></script>

<h3 class="nx_page_tit">우리동네 강좌정보</h3>

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

<div class="data_ct">
	<form id="frmSch" name="frmSch" enctype="multipart/form-data" method="post" onsubmit="return false;">
	<input type="submit" style="width:1px;height:1px;position:absolute;top:-10000px;left:-10000px" />
	<input type="hidden" id="PAGE" name="PAGE" value="<?php echo $PAGE;?>" />
	<input type="hidden" id="sigungu_cds" name="sigungu_cds" value="<?php echo $SIGUNGU_CD;?>" />
	<input type="hidden" id="course_pttns" name="course_pttns" value="<?php echo $COURSE_PTTN;?>" />
		<div id="search_area">
    		<!--강좌정보검색-->
            <div class="class-search">
            	<span>강좌정보검색</span>
            </div>
            
            <div class="class-wrab">
                <div class="class-area">
                    <div class="classArea-head">지역선택</div>
                    <div class="classArea-body">
                    	<span><label for="sigungu_cd_all"><input type="checkbox" name="sigungu_cd_all" value="all" id="sigungu_cd_all" class="allArea" <?php echo ($SIGUNGU_CD_ALL == 'all' ? 'checked' : '');?>>전체</label></span>
               			<span><label for="sigungu_cd1"><input type="checkbox" name="sigungu_cd" value="4105" id="sigungu_cd1" class="classArea" <?php echo (strpos($SIGUNGU_CD,'4105') !== false ? 'checked' : '');?>>부천시</label></span>
                        <span><label for="sigungu_cd2"><input type="checkbox" name="sigungu_cd" value="4111" id="sigungu_cd2" class="classArea" <?php echo (strpos($SIGUNGU_CD,'4111') !== false ? 'checked' : '');?>>수원시</label></span>
                        <span><label for="sigungu_cd3"><input type="checkbox" name="sigungu_cd" value="4113" id="sigungu_cd3" class="classArea" <?php echo (strpos($SIGUNGU_CD,'4113') !== false ? 'checked' : '');?>>성남시</label></span>
                        <span><label for="sigungu_cd4"><input type="checkbox" name="sigungu_cd" value="4115" id="sigungu_cd4" class="classArea" <?php echo (strpos($SIGUNGU_CD,'4115') !== false ? 'checked' : '');?>>의정부시</label></span>
                        <span><label for="sigungu_cd5"><input type="checkbox" name="sigungu_cd" value="4117" id="sigungu_cd5" class="classArea" <?php echo (strpos($SIGUNGU_CD,'4117') !== false ? 'checked' : '');?>>안양시</label></span>
                        <span><label for="sigungu_cd6"><input type="checkbox" name="sigungu_cd" value="4121" id="sigungu_cd6" class="classArea" <?php echo (strpos($SIGUNGU_CD,'4121') !== false ? 'checked' : '');?>>광명시</label></span>
                        <span><label for="sigungu_cd7"><input type="checkbox" name="sigungu_cd" value="4122" id="sigungu_cd7" class="classArea" <?php echo (strpos($SIGUNGU_CD,'4122') !== false ? 'checked' : '');?>>평택시</label></span>
                        <span><label for="sigungu_cd8"><input type="checkbox" name="sigungu_cd" value="4125" id="sigungu_cd8" class="classArea" <?php echo (strpos($SIGUNGU_CD,'4125') !== false ? 'checked' : '');?>>동두천시</label></span>
                        <span><label for="sigungu_cd9"><input type="checkbox" name="sigungu_cd" value="4127" id="sigungu_cd9" class="classArea" <?php echo (strpos($SIGUNGU_CD,'4127') !== false ? 'checked' : '');?>>안산시</label></span>
                        <span><label for="sigungu_cd10"><input type="checkbox" name="sigungu_cd" value="4128" id="sigungu_cd10" class="classArea" <?php echo (strpos($SIGUNGU_CD,'4128') !== false ? 'checked' : '');?>>고양시</label></span>
                        <span><label for="sigungu_cd11"><input type="checkbox" name="sigungu_cd" value="4129" id="sigungu_cd11" class="classArea" <?php echo (strpos($SIGUNGU_CD,'4129') !== false ? 'checked' : '');?>>과천시</label></span>
                        <span><label for="sigungu_cd12"><input type="checkbox" name="sigungu_cd" value="4136" id="sigungu_cd12" class="classArea" <?php echo (strpos($SIGUNGU_CD,'4136') !== false ? 'checked' : '');?>>남양주시</label></span>
                        <span><label for="sigungu_cd13"><input type="checkbox" name="sigungu_cd" value="4139" id="sigungu_cd13" class="classArea" <?php echo (strpos($SIGUNGU_CD,'4139') !== false ? 'checked' : '');?>>시흥시</label></span>
                        <span><label for="sigungu_cd14"><input type="checkbox" name="sigungu_cd" value="4141" id="sigungu_cd14" class="classArea" <?php echo (strpos($SIGUNGU_CD,'4141') !== false ? 'checked' : '');?>>군포시</label></span>
                        <span><label for="sigungu_cd15"><input type="checkbox" name="sigungu_cd" value="4143" id="sigungu_cd15" class="classArea" <?php echo (strpos($SIGUNGU_CD,'4143') !== false ? 'checked' : '');?>>의왕시</label></span>
                        <span><label for="sigungu_cd16"><input type="checkbox" name="sigungu_cd" value="4145" id="sigungu_cd16" class="classArea" <?php echo (strpos($SIGUNGU_CD,'4145') !== false ? 'checked' : '');?>>하남시</label></span>
                        <span><label for="sigungu_cd17"><input type="checkbox" name="sigungu_cd" value="4146" id="sigungu_cd17" class="classArea" <?php echo (strpos($SIGUNGU_CD,'4146') !== false ? 'checked' : '');?>>용인시</label></span>
                        <span><label for="sigungu_cd18"><input type="checkbox" name="sigungu_cd" value="4150" id="sigungu_cd18" class="classArea" <?php echo (strpos($SIGUNGU_CD,'4150') !== false ? 'checked' : '');?>>이천시</label></span>
                        <span><label for="sigungu_cd19"><input type="checkbox" name="sigungu_cd" value="4157" id="sigungu_cd19" class="classArea" <?php echo (strpos($SIGUNGU_CD,'4157') !== false ? 'checked' : '');?>>김포시</label></span>
                        <span><label for="sigungu_cd20"><input type="checkbox" name="sigungu_cd" value="4159" id="sigungu_cd20" class="classArea" <?php echo (strpos($SIGUNGU_CD,'4159') !== false ? 'checked' : '');?>>화성시</label></span>
                        <span><label for="sigungu_cd21"><input type="checkbox" name="sigungu_cd" value="4161" id="sigungu_cd21" class="classArea" <?php echo (strpos($SIGUNGU_CD,'4161') !== false ? 'checked' : '');?>>광주시</label></span>
                        <span><label for="sigungu_cd22"><input type="checkbox" name="sigungu_cd" value="4163" id="sigungu_cd22" class="classArea" <?php echo (strpos($SIGUNGU_CD,'4163') !== false ? 'checked' : '');?>>양주시</label></span>
                        <span><label for="sigungu_cd23"><input type="checkbox" name="sigungu_cd" value="4165" id="sigungu_cd23" class="classArea" <?php echo (strpos($SIGUNGU_CD,'4165') !== false ? 'checked' : '');?>>포천시</label></span>
                        <span><label for="sigungu_cd24"><input type="checkbox" name="sigungu_cd" value="4180" id="sigungu_cd24" class="classArea" <?php echo (strpos($SIGUNGU_CD,'4180') !== false ? 'checked' : '');?>>연천군</label></span>
                        <span><label for="sigungu_cd25"><input type="checkbox" name="sigungu_cd" value="4183" id="sigungu_cd25" class="classArea" <?php echo (strpos($SIGUNGU_CD,'4183') !== false ? 'checked' : '');?>>양평군</label></span>
                        <span><label for="sigungu_cd25"><input type="checkbox" name="sigungu_cd" value="4137" id="sigungu_cd25" class="classArea" <?php echo (strpos($SIGUNGU_CD,'4137') !== false ? 'checked' : '');?>>오산시</label></span>
                    </div>
                </div>
                
                <div class="class-area">
                    <div class="classArea-head">학습 목적</div>
                    <div class="classArea-body">
                    	<span><label for="course_pttn_all"><input type="checkbox" name="course_pttn_all" value="all" id="course_pttn_all" class="allLearning" <?php echo ($COURSE_PTTN_ALL == 'all' ? 'checked' : '');?>>전체</label></span>
            	        <span><label for="course_pttn1"><input type="checkbox" name="course_pttn" value="취업|창업" id="course_pttn1" class="classLearning" <?php echo (strpos($COURSE_PTTN,'취업|창업') !== false ? 'checked' : '');?>>취업/창업</label></span>
                        <span><label for="course_pttn2"><input type="checkbox" name="course_pttn" value="스포츠" id="course_pttn2" class="classLearning" <?php echo (strpos($COURSE_PTTN,'스포츠') !== false ? 'checked' : '');?>>스포츠</label></span>
                        <span><label for="course_pttn3"><input type="checkbox" name="course_pttn" value="외국어" id="course_pttn3" class="classLearning" <?php echo (strpos($COURSE_PTTN,'외국어') !== false ? 'checked' : '');?>>외국어</label></span>
                        <span><label for="course_pttn4"><input type="checkbox" name="course_pttn" value="자격증" id="course_pttn4" class="classLearning" <?php echo (strpos($COURSE_PTTN,'자격증') !== false ? 'checked' : '');?>>자격증</label></span>
                        <span><label for="course_pttn5"><input type="checkbox" name="course_pttn" value="음악|미술" id="course_pttn5" class="classLearning" <?php echo (strpos($COURSE_PTTN,'음악|미술') !== false ? 'checked' : '');?>>음악/미술</label></span>
                        <span><label for="course_pttn6"><input type="checkbox" name="course_pttn" value="컴퓨터" id="course_pttn6" class="classLearning" <?php echo (strpos($COURSE_PTTN,'컴퓨터') !== false ? 'checked' : '');?>>컴퓨터</label></span>
                        <span><label for="course_pttn7"><input type="checkbox" name="course_pttn" value="인문|교양" id="course_pttn7" class="classLearning" <?php echo (strpos($COURSE_PTTN,'인문|교양') !== false ? 'checked' : '');?>>인문/교양</label></span>
                        <span><label for="course_pttn8"><input type="checkbox" name="course_pttn" value="생활|경제" id="course_pttn8" class="classLearning" <?php echo (strpos($COURSE_PTTN,'생활|경제') !== false ? 'checked' : '');?>>생활/경제</label></span>
                        <span><label for="course_pttn9"><input type="checkbox" name="course_pttn" value="시민참여" id="course_pttn9" class="classLearning" <?php echo (strpos($COURSE_PTTN,'시민참여') !== false ? 'checked' : '');?>>시민참여</label></span>
                        <span><label for="course_pttn10"><input type="checkbox" name="course_pttn" value="건강|의료" id="course_pttn10" class="classLearning" <?php echo (strpos($COURSE_PTTN,'건강|의료') !== false ? 'checked' : '');?>>건강/의료</label></span>
                        <span><label for="course_pttn11"><input type="checkbox" name="course_pttn" value="한글|한문" id="course_pttn11" class="classLearning" <?php echo (strpos($COURSE_PTTN,'한글|한문') !== false ? 'checked' : '');?>>한글/한문</label></span>
                        <span><label for="course_pttn12"><input type="checkbox" name="course_pttn" value="학력보완" id="course_pttn12" class="classLearning" <?php echo (strpos($COURSE_PTTN,'학력보완') !== false ? 'checked' : '');?>>학력보완</label></span>
                    </div>
                </div>
               
                <div class="class-area">
                    <div class="classArea-head">접수 마감일</div>
                    <div class="classArea-body">
                        <span><label for="receive_dt_all">
                            <input type="radio" value="all"  name="receive_dt" id="receive_dt_all" class="allclosingdate" <?php echo ($RECEIVE_DT == 'all' ? 'checked' : '');?>>전체
                        </label>
                        </span>
                        <span>
                        <label for="receive_dt1">
                            <input type="radio" value="1" name="receive_dt" id="receive_dt1" <?php echo ($RECEIVE_DT == '1' ? 'checked' : '');?>>1달 이내
                        </label>
                        </span>
                        <span>
                        <label for="receive_dt2">
                            <input type="radio" value="2" name="receive_dt" id="receive_dt2" <?php echo ($RECEIVE_DT == '2' ? 'checked' : '');?>>2달 이내
                        </label>
                        </span>
                        <span>
                        <label for="receive_dt3">
                            <input type="radio" value="3" name="receive_dt" id="receive_dt3" <?php echo ($RECEIVE_DT == '3' ? 'checked' : '');?>>3달 이내
                        </label>
                        </span>
                    </div>
                </div>
            
               
               
                <div class="class-area">
                    <div class="classArea-head">강좌명</div>
                    <div class="classArea-body">
                       <input type="text" size="70" name="course_nm" id="course_nm" name="course_nm" value="<?php echo $COURSE_NM;?>" placeholder="">
                    </div>
                </div>
            </div>
            <!--강좌정보검색//-->
        </div>

        <!-- 강좌조회 -->
        <div class="class-select">
        	<div class="class-text">
        		<span>총 강좌수 : 총<span id="TOTAL_CNT"><?php echo number_format($TOTAL_CNT);?></span>건</span>
        	</div>
        	<div class="select-box">	
    			<select id="sort" name="sort">
        			<option value="COL_DT" <?php echo ($SORT_TYPE == 'COL_DT' ? 'selected' : '');?>>최신순</option>
					<option value="DESC" <?php echo ($SORT_TYPE == 'DESC' ? 'selected' : '');?>>접수가능 강좌</option>
					<option value="ASC" <?php echo ($SORT_TYPE == 'ASC' ? 'selected' : '');?>>접수종료 강좌</option>
        		</select>
        		<select id="N_PAGE" name="N_PAGE">
        			<option value="10" <?php echo ($N_PAGE == '10' ? 'selected' : '');?>>10개씩</option>
					<option value="20" <?php echo ($N_PAGE == '20' ? 'selected' : '');?>>20개씩</option>
					<option value="30" <?php echo ($N_PAGE == '30' ? 'selected' : '');?>>30개씩</option>
        		</select>
    		</div>
        	<div class="class-btn">
        		<button type="button" class="search-btn">검색</button>
        		<button type="button" class="close-btn">닫기</button>	
        	</div>
        </div>
		<?php 
			if ( $is_admin == "super" ) {
				?>
				<div style="text-align: right;">
					<button type="button" onclick=" document.location.href = '/gseek/nx_courses_list.excel.php'; " style="background: #5eaad0; border: 0; outline: 0; color: #FFF; padding: 5px 15px;">전체 엑셀 다운로드</button>
				</div>
				<?php
			}
		?>
        <!-- 강좌조회// -->
	</form>

	<!-- 늘배움센터 UI 붙이기 시작 -->
	<ul id="resultUL" class="courses">
		<?php
		
		$COUNT = sql_num_rows($item);
		
		if ($COUNT < 1) {
        ?>
			<li class="nodata">등록된 학습이 없습니다.</li>
		<?php
        } else {
            while ($itm = sql_fetch_array($item)) {
		?>
			<li class="area" name="0" zoo="itemli" style="display: list-item;">
				<dl>
					<dt>
						<a<?php if($itm['COURSE_URL'] == "") { ?> href="javascript:void(0);"<?php } else { ?> href="<?php echo($itm['COURSE_URL'])?>" target="_blank"<?php } ?> title="새창"><?php echo(htmlspecialchars($itm['COURSE_NM']))?></a>
						<span class="ico"><i class="img-ico offline"></i> 오프라인</span>
					</dt>
					<dd>
						<ul>
							<li class="type3"><span>교육기관 </span>: <font class="instituteNm"><?php echo(htmlspecialchars($itm['INST_NM']))?></font></li>
							<li><span>접수기간 </span>: <font class="receiveDt"><?php echo(htmlspecialchars($itm['RECEIVE_START_DT']))?> ~ <?php echo(htmlspecialchars($itm['RECEIVE_END_DT']))?></font></li>
							<li><span>홈페이지 </span>: <?php if($itm['HOMEPAGE_URL'] > "") { ?><font class="linkUrl"><a href="<?php echo($itm['HOMEPAGE_URL'])?>" target="_blank" title="새창"><?php echo(htmlspecialchars($itm['HOMEPAGE_URL']))?></a></font><?php } ?></li>
						</ul>
						<ul>
		                    <li class="type4"><span>장소    </span>: <font class="eduLocationDesc"><?php echo(htmlspecialchars($itm['EDU_LOCATION_DESC']))?></font></li>
		                    <li><span>교육기간 </span>:  <strong class="pt03"><font class="data_em courseDt"><?php echo(htmlspecialchars($itm['COURSE_START_DT']))?> ~ <?php echo(htmlspecialchars($itm['COURSE_END_DT']))?></font></strong></li>
		                    <li><span>연락처   </span>: <font class="inquiryTelNo"><?php echo(htmlspecialchars($itm['INQUIRY_TEL_NO']))?></font></li>
		                </ul>
		                <ul>
		                    <li><span>수강료  </span>: <font class="enrollAmt"><?php echo(htmlspecialchars($itm['ENROLL_AMT']))?></font><?php if(is_numeric(str_replace(",", "", $itm['ENROLL_AMT']))) { ?>원<?php } ?></li>
		                    <li><span>정원    </span>: <font class="eduQuotaCnt"><?php echo(htmlspecialchars($itm['EDU_QUOTA_CNT']))?></font><?php if(is_numeric(str_replace(",", "", $itm['EDU_QUOTA_CNT']))) { ?>명<?php } ?></li>
		                </ul>
					</dd>
				</dl>
			</li>
		<?php 
		    }
        }
		?>
	</ul>
	<!-- 늘배움센터 UI 붙이기 종료 -->

	<?php
	if ($total_page >= 1) {
		?>
		<div class="pagenate_wrap">
			
			<div class="list-page text-center">
				<ul class="pagination pagination-sm en">
					<?php
					$perPage = (int)5;		# 페이징 수

					$end = round(($PAGE + ($perPage / 2 - 1)) / $perPage) * $perPage;
					$start = $end - ($perPage-1);
					?>
					<li><a<?php if($start != 1) { ?> href="javascript:onclOnsu(<?php echo($start - $perPage)?>);"<?php } ?>><i class="fa fa-angle-double-left"></i></a></li>
					<li><a<?php if ($PAGE - 1 > 0) { ?> href="javascript:onclOnsu(<?php echo($PAGE - 1)?>);"<?php } ?>><i class="fa fa-angle-left"></i></a></li>
					<?php
					for($i = $start; $i <= $end; $i++) {
						?>
						<li<?php if($PAGE == $i) {echo(' class="active"');}?>>
						<a href="javascript:onclOnsu(<?php echo $i;?>);"><?php echo $i;?></a>
						</li>
						<?php
					}
					?>
					<li><a<?php if ($PAGE + 1 <= $total_page) { ?> href="javascript:onclOnsu(<?php echo($PAGE + 1)?>);"<?php } ?>><i class="fa fa-angle-right"></i></a></li>
					<li><a<?php if($end < $total_page) { ?> href="javascript:onclOnsu(<?php echo($end + 1)?>);"<?php } ?>><i class="fa fa-angle-double-right"></i></a></li>
				</ul>
			</div>
		</div>
		<?php
	}
	?>
</div>

<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-58bff1ea9221b257"></script>
<script>
//<![CDATA[
	
  $( document ).ready( function() {
    // 지역선택 전체 선택
  	$( '.allArea' ).click( function() {
  	  	if(this.checked) {
  	  		$( '.classArea' ).prop( 'checked', false );
  	  	}
    } );

    // 지역선택 세부 선택시 전체 선택 체크 여부
    $( '.classArea' ).click( function() {
      var cnt = 0;
      $( 'input[name=sigungu_cd]:checkbox' ).each( function() {
        if( $(this).is(':checked') ) {
            cnt++;
        }
      });
      
      if ( $( 'input[name=sigungu_cd]:checkbox' ).size() == cnt ) {
    	  $( '.allArea' ).prop("checked", true);
    	  $( '.classArea' ).prop( 'checked', false );
      } else {
    	  $( '.allArea' ).prop("checked", false);
      }
    });

    // 학습목적 전체 선택
    $( '.allLearning' ).click( function() {
        if(this.checked) {
        	$( '.classLearning' ).prop( 'checked', false );
        }
    } );

    // 학습목적 세부 선택시 전체 선택 체크 여부
    $( '.classLearning' ).click( function() {
      var cnt = 0;
      $( 'input[name=course_pttn]:checkbox' ).each( function() {
        if( $(this).is(':checked') ) {
            cnt++;
        }
      });
      
      if ( $( 'input[name=course_pttn]:checkbox' ).size() == cnt ) {
    	  $( '.allLearning' ).prop("checked", true);
    	  $( '.classLearning' ).prop( 'checked', false );
      } else {
    	  $( '.allLearning' ).prop("checked", false);
      }
    });

    // 강좌정보검색 열기/닫기
    $( '.close-btn' ).click( function() {
        if( $( '#search_area' ).css('display') == 'none' ) {
        	$("#search_area").show();
        	$('.close-btn' ).text('닫기');
        } else {
        	$("#search_area").hide();
        	$('.close-btn' ).text('열기');
        }
    });

    // 강좌검색
    $( '.search-btn' ).click( function() {
    	onclOnsu(1);
    });
    
  } );

var onclOnsu = function(num) {
	
	$('#PAGE').val(num);

    var tmp = '';
	$.each($('input[name=sigungu_cd]:checkbox:checked'), function() {
		tmp += $(this).val() + ','; 
	});
	if(tmp.length > 1) {
		tmp = tmp.substring(0, tmp.length - 1);
	}
	$('#sigungu_cds').val(tmp);
	
	tmp = '';
	$.each($('input[name=course_pttn]:checkbox:checked'), function() {
		tmp += $(this).val() + '|'; 
	});
	if(tmp.length > 1) {
		tmp = tmp.substring(0, tmp.length - 1);
	}
	$('#course_pttns').val(tmp);
	
	var f = $('#frmSch').serialize();
	window.location.replace("?"+$('#frmSch').serialize());
}

//]]>
</script>

<?php 
	# 메뉴 표시에 사용할 변수 
	$_gr_id = 'gill';  // 게시판 그룹 ID - 현재 메뉴표시에 사용
	$pid = ($pid) ? $pid : '';   // Page ID
	$pid = 'off_lecture';

	include "../page/inc.page.menu.php";

	include_once('../bbs/_tail.php');
?>





