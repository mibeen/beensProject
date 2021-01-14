<?php
    
    $LA_IDX_ALL = $_REQUEST['la_idx_all'];    // 지역 선택 전체
    $LA_IDX = htmlspecialchars($_REQUEST['la_idxs']);    // 지역 선택
    $COURSE_NM = htmlspecialchars($_REQUEST['course_nm']);  // 강좌명
    $SORT_TYPE = $_REQUEST['sort'];   // 정렬 방법
    $N_PAGE = $_REQUEST['N_PAGE']; // 조회 개수
    $PAGE = $_REQUEST['PAGE'];    // 현재 페이지
    $TOTAL_CNT = 0;
    
    # re-define
    if(!$LA_IDX) $LA_IDX_ALL = 'all';
    if(!$COURSE_PTTN) $COURSE_PTTN_ALL = 'all';
    if(!$RECEIVE_DT) $RECEIVE_DT = 'all';
    if(!$N_PAGE) $N_PAGE = 6;
    if(!$PAGE) $PAGE = 1;
    
    $cond =  "WHERE  1=1 AND lp.lp_ddate is null And lp.lp_use_yn = 'Y'"
                    .(($LA_IDX_ALL == 'all') ? "" : " AND la.la_idx IN (" . $LA_IDX . ") ") // 지역 선택
                    .(($COURSE_NM == '') ? "" : " AND lp.lp_name LIKE '%" . $COURSE_NM . "%' ")    ;

    $sql = " Select lp.*
                    , la.la_name
                From local_place As lp
                    Inner Join local_place_area As la On la.la_idx = lp.la_idx
                ".$cond."
                Order By lp_wdate Desc
                LIMIT " . $N_PAGE * ($PAGE - 1) . " , " . $N_PAGE ;
    //echo "<p>".$sql."</p>";
    $LP = sql_query($sql);
    $LP_LEN = Count($LP);

    $list = array();
    $subject_len = G5_IS_MOBILE ? 60 : 100;
    for($i=0; $row=sql_fetch_array($LP); $i++) {
        $list[$i] = $row;


        $list[$i]['lp_name'] = conv_subject($row['lp_name'], $subject_len, '…');


        # 검색어 색상 표시
        if ($stx != '') {
            $list[$i]['lp_name'] = search_font($stx, $list[$i]['lp_name']);
            $list[$i]['lp_address'] = search_font($stx, $list[$i]['lp_address']);
        }

        $list[$i]['view_href'] = './place.read.php?lp_idx='.$row['lp_idx'].$qstr;


        // list image
        $sql = " select * from {$g5['board_file_table']} where bo_table = '{$_file_table}' and wr_id = '{$row['lp_idx']}'";
        $place_file_result = sql_query($sql);
        $place_file = sql_fetch_array($place_file_result);
        $himg = '';
        if(isset($place_file) && $place_file['bf_file'] != '') {
            $himg = G5_DATA_PATH."/file/".$_file_table."/".$place_file['bf_file'];
        }

        $list[$i]['bf_file'] = $place_file['bf_file'];
        $list[$i]['bf_source'] = $place_file['bf_source'];
    }
    
    # 총 강좌수
    $n_sql =   "Select COUNT(*) AS TOTAL_CNT
                From local_place As lp
                    Inner Join local_place_area As la On la.la_idx = lp.la_idx ".$cond;
    //echo "<p>".$n_sql."</p>";
    $n_data = sql_query($n_sql);
    if (sql_num_rows($n_data) > 0) {
        $rs1 = sql_fetch_array($n_data);
        $TOTAL_CNT = $rs1['TOTAL_CNT'];
    } else {
        $TOTAL_CNT = 0;
    }
?>
<style>
    /*강좌목록*/
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

<!-- 검색창 ST -->
<div class="data_ct">
	<form id="frmSch" name="frmSch" enctype="multipart/form-data" method="post" onsubmit="return false;">
	<input type="submit" style="width:1px;height:1px;position:absolute;top:-10000px;left:-10000px" />
	<input type="hidden" id="PAGE" name="PAGE" value="<?php echo $PAGE;?>" />
	<input type="hidden" id="la_idxs" name="la_idxs" value="<?php echo $LA_IDX;?>" />
	<input type="hidden" id="course_pttns" name="course_pttns" value="<?php echo $COURSE_PTTN;?>" />
		<div id="search_area">
    		<!--강좌정보검색-->
            <div class="class-search">
            	<span>학습공간 검색</span>
            </div>
            
            <div class="class-wrab">
                <div class="class-area">
                    <div class="classArea-head">지역선택</div>
                    <div class="classArea-body">
                    	<span><label for="la_idx_all"><input type="checkbox" name="la_idx_all" value="all" id="la_idx_all" class="allArea" >전체</label></span>
                        <span><label for="la_idx1">   <input type="checkbox" name="la_idx" value="1"  id="la_idx1"  class="classArea" >가평군</label></span>
                        <span><label for="la_idx2">   <input type="checkbox" name="la_idx" value="2"  id="la_idx2"  class="classArea" >고양시</label></span>
                        <span><label for="la_idx3">   <input type="checkbox" name="la_idx" value="3"  id="la_idx3"  class="classArea" >과천시</label></span>
                        <span><label for="la_idx4">   <input type="checkbox" name="la_idx" value="4"  id="la_idx4"  class="classArea" >광명시</label></span>
                        <span><label for="la_idx5">   <input type="checkbox" name="la_idx" value="5"  id="la_idx5"  class="classArea" >광주시</label></span>
                        <span><label for="la_idx6">   <input type="checkbox" name="la_idx" value="6"  id="la_idx6"  class="classArea" >구리시</label></span>
                        <span><label for="la_idx7">   <input type="checkbox" name="la_idx" value="7"  id="la_idx7"  class="classArea" >군포시</label></span>
                        <span><label for="la_idx8">   <input type="checkbox" name="la_idx" value="8"  id="la_idx8"  class="classArea" >김포시</label></span>
                        <span><label for="la_idx9">   <input type="checkbox" name="la_idx" value="9"  id="la_idx9"  class="classArea" >남양주시</label></span>
                        <span><label for="la_idx10">  <input type="checkbox" name="la_idx" value="10" id="la_idx10" class="classArea" >동두천시</label></span>
                        <span><label for="la_idx11">  <input type="checkbox" name="la_idx" value="11" id="la_idx11" class="classArea" >부천시</label></span>
                        <span><label for="la_idx12">  <input type="checkbox" name="la_idx" value="12" id="la_idx12" class="classArea" >성남시</label></span>
                        <span><label for="la_idx13">  <input type="checkbox" name="la_idx" value="13" id="la_idx13" class="classArea" >수원시</label></span>
                        <span><label for="la_idx14">  <input type="checkbox" name="la_idx" value="14" id="la_idx14" class="classArea" >시흥시</label></span>
                        <span><label for="la_idx15">  <input type="checkbox" name="la_idx" value="15" id="la_idx15" class="classArea" >안산시</label></span>
                        <span><label for="la_idx16">  <input type="checkbox" name="la_idx" value="16" id="la_idx16" class="classArea" >안성시</label></span>
                        <span><label for="la_idx17">  <input type="checkbox" name="la_idx" value="17" id="la_idx17" class="classArea" >안양시</label></span>
                        <span><label for="la_idx18">  <input type="checkbox" name="la_idx" value="18" id="la_idx18" class="classArea" >양주시</label></span>
                        <span><label for="la_idx19">  <input type="checkbox" name="la_idx" value="19" id="la_idx19" class="classArea" >양평군</label></span>
                        <span><label for="la_idx20">  <input type="checkbox" name="la_idx" value="20" id="la_idx20" class="classArea" >여주시</label></span>
                        <span><label for="la_idx21">  <input type="checkbox" name="la_idx" value="21" id="la_idx21" class="classArea" >연천군</label></span>
                        <span><label for="la_idx22">  <input type="checkbox" name="la_idx" value="22" id="la_idx22" class="classArea" >오산시</label></span>
                        <span><label for="la_idx23">  <input type="checkbox" name="la_idx" value="23" id="la_idx23" class="classArea" >용인시</label></span>
                        <span><label for="la_idx24">  <input type="checkbox" name="la_idx" value="24" id="la_idx24" class="classArea" >의왕시</label></span>
                        <span><label for="la_idx25">  <input type="checkbox" name="la_idx" value="25" id="la_idx25" class="classArea" >의정부시</label></span>
                        <span><label for="la_idx26">  <input type="checkbox" name="la_idx" value="26" id="la_idx26" class="classArea" >이천시</label></span>
                        <span><label for="la_idx27">  <input type="checkbox" name="la_idx" value="27" id="la_idx27" class="classArea" >파주시</label></span>
                        <span><label for="la_idx28">  <input type="checkbox" name="la_idx" value="28" id="la_idx28" class="classArea" >평택시</label></span>
                        <span><label for="la_idx29">  <input type="checkbox" name="la_idx" value="29" id="la_idx29" class="classArea" >포천시</label></span>
                        <span><label for="la_idx30">  <input type="checkbox" name="la_idx" value="30" id="la_idx30" class="classArea" >하남시</label></span>
                        <span><label for="la_idx31">  <input type="checkbox" name="la_idx" value="31" id="la_idx31" class="classArea" >화성시</label></span>

                    </div>
                </div>
               
                <div class="class-area">
                    <div class="classArea-head">학습공간 명</div>
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
        		<span>총 학습공간수 : 총<span id="TOTAL_CNT"><?php echo number_format($TOTAL_CNT);?></span>건</span>
        	</div>
        	<div class="select-box">	
        		<select id="N_PAGE" name="N_PAGE">
        			<option value="6" <?php echo ($N_PAGE == '6' ? 'selected' : '');?>>6개씩</option>
        			<option value="12" <?php echo ($N_PAGE == '12' ? 'selected' : '');?>>12개씩</option>
					<option value="18" <?php echo ($N_PAGE == '18' ? 'selected' : '');?>>18개씩</option>
					<option value="24" <?php echo ($N_PAGE == '24' ? 'selected' : '');?>>24개씩</option>
        		</select>
    		</div>
        	<div class="class-btn">
        		<button type="button" class="search-btn">검색</button>
        		<button type="button" class="close-btn">닫기</button>	
        	</div>
        </div>
        <!-- 강좌조회// -->
	</form>
</div>
<!-- 검색창 END -->

<ul class="coron_lst">
    <?php
    for ($i=0; $i<count($list); $i++) {
    ?>
    <li>
        <a href="<?php echo $list[$i]['view_href']; ?>">
            <div class="img_wrap1">
                <div class="img_wrap2">
                    <?php
                    # 썸네일 생성
                    $thumb = thumbnail($list[$i]['bf_file'], G5_PATH."/data/file/$_file_table", G5_PATH."/data/file/$_file_table", 480, 270, false);

                    if (!is_null($thumb)) {
                        echo '<img src="/data/file/'.$_file_table.'/'.$thumb.'" alt="'.htmlspecialchars($list[$i]['bf_source']).'" />';
                    }
                    else {
                        echo '<img src="'.G5_URL.'/img/no_img_udonglist.jpg'.'" alt="'.htmlspecialchars($list[$i]['bf_source']).'" class="img" />';
                    }

                    unset($thumb);
                    ?>
                </div>
            </div>
            <p class="region"><?php echo $list[$i]['la_name']; ?></p>
            <p class="tit"><?php echo $list[$i]['lp_name']; ?></p>
            <p class="info">연락처 : <?php echo $list[$i]['lp_tel']?></p>
            <p class="info">주소 : <?php echo $list[$i]['lp_address']?></p>
        </a>
    </li>
    <?php } ?>
    <?php
    if($i == 0) { ?>
        <li class="nodata"><p>등록된 학습공간이 없습니다.</p></li>
    <?php } ?>
</ul>

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
      $( 'input[name=la_idx]:checkbox' ).each( function() {
        if( $(this).is(':checked') ) {
            cnt++;
        }
      });
      
      if ( $( 'input[name=la_idx]:checkbox' ).size() == cnt ) {
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
	$.each($('input[name=la_idx]:checkbox:checked'), function() {
		tmp += $(this).val() + ','; 
	});
	if(tmp.length > 1) {
		tmp = tmp.substring(0, tmp.length - 1);
	}
	$('#la_idxs').val(tmp);
	
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

var la_idxs = $('#la_idxs').val();
if(la_idxs.indexOf(",") != -1){
	//다중검색 일때
	var la_idx = la_idxs.split(",");
	for(var i in la_idx){
		$('#la_idx'+la_idx[i]).attr("checked", true);
	}
}else{
	//단일검색 일때
	$('#la_idx'+la_idxs).attr("checked", true);
}

</script>
<?php
	if ($total_page >= 1) {
		?>
		<div class="pagenate_wrap">
			
			<div class="list-page text-center">
				<ul class="pagination pagination-sm en">
					<?php
					//ceil(number_format($TOTAL_CNT) / 12)
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

