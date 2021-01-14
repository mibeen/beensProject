<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// 위젯 대표아이디 설정
$wid = 'CMB';

// 게시판 제목 폰트 설정
$font = 'font-20';

// 게시판 제목 하단라인컬러 설정 - red, blue, green, orangered, black, orange, yellow, navy, violet, deepblue, crimson..
$line = 'navy';

// 사이드 위치 설정 - left, right
$side = ($at_set['side']) ? 'left' : 'right';


# 사업관리 카테고리 GET
$sql      = "SELECT EC_IDX, EC_NAME FROM NX_EVENT_CATE ORDER By EC_SEQ Asc, EC_IDX DESC";
$row      = sql_query($sql);
$cnt      = sql_num_rows($row);

$category = "<li class=\"active\"><a href=\"#taball\">전체보기</a></li>";
$arr_cate = array('all'); //카테고리 li를 담는 박스.
$arr_cnt  = array(
				'all' => array(
					'cnt' => 0
				)
			); //li의 개수를 담는 박스
$box      = array(); //li들을 담는 박스



#카테고리 넣기
while($rs1 = sql_fetch_array($row)){
	$category .= "<li><a href=\"#tab".$rs1['EC_IDX']."\">".$rs1['EC_NAME']."</a></li>";
	$arr_cnt[$rs1['EC_IDX']]['cnt'] = 0;
	$arr_cate[] = $rs1['EC_IDX'];
}



#이미지 디렉토리와, 썸네일 대상 디렉토리
$s_path   = G5_PATH . "/data/file/NX_EVENT_MASTER"; 
$t_path   = G5_PATH . "/data/file/NX_EVENT_MASTER"; 



# 전체 루프
$sql = "SELECT EM.EM_IDX, EM.EC_IDX, EM.EP_IDX, EM.EM_TITLE, EM.EM_S_DATE, EM.EM_E_DATE, EM.EM_S_TIME, EM.EM_E_TIME, EM.EM_JOIN_MAX"
		."		, EM.EM_JOIN_S_DATE, EM.EM_JOIN_E_DATE"
		."		, FL.bf_file, bf_source"
		."	From NX_EVENT_MASTER As EM"
		."		Left Join {$g5['board_file_table']} As FL On FL.bo_table = 'NX_EVENT_MASTER' And FL.wr_id = EM.EM_IDX And FL.bf_no = '0'"
		."	Where EM.EM_DDATE is null And EM.EM_OPEN_YN = 'Y'" //" AND EM.EC_IDX in ({$rs1['EC_IDX']})"
		."	Order By EM.EM_IDX Desc";
		#."	Limit 0, 8 ";
$row2 = sql_query($sql);
while( $rs2 = sql_fetch_array($row2) ){


	# Create Thumbnail
    $thumb = thumbnail($rs2['bf_file'], $s_path, $t_path, 247, 247, false); 


	# Set status
	if ($rs2['EM_JOIN_S_DATE'] <= date('Y-m-d H:i') && $rs2['EM_JOIN_E_DATE'] >= date('Y-m-d H:i')) {
		$status = "<span class=\"receipt\">접수중</span>";
	}
	else if ($rs2['EM_JOIN_E_DATE'] < date('Y-m-d H:i')){
		$status = "<span class=\"end\">마감</span>";
	}
	else {
		$status = "<span class=\"ready\">준비중</span>";
	}


	# Create Element
	$rs_box = "
			<li>
				" . (($rs2['EP_IDX'] != "") ? "<span class=\"nx-label label-project\">공모</span>" : "") . "
				<a href=\"/evt/evt.read.php?&EM_IDX=".$rs2['EM_IDX']."\">
					<div class=\"img-box\">
						" . ((!is_null($thumb)) ? '<img src="/data/file/NX_EVENT_MASTER/'.$thumb.'" alt="'.clean_xss_tags($rs1['bf_source']).'" class="img" />' : '<img src="'.G5_URL.'/img/no_img.jpg'.'" alt="'.clean_xss_tags($rs1['bf_source']).'" class="img" />') . "
					</div>
					<div class=\"status\">".$status."</div>
					<div class=\"desc\">
						<p class=\"title\">".$rs2['EM_TITLE']."</p>
						<p class=\"limit\">인원 : <span>".(($rs2['EM_JOIN_MAX'] > 0) ? number_format($rs2['EM_JOIN_MAX']).'명' : '제한없음')."</span></p>
						<p class=\"date\">날짜 : <span>".substr($rs2['EM_JOIN_S_DATE'], 0, 10)." ~ ".substr($rs2['EM_JOIN_E_DATE'], 0, 10)."</span></p>
					</div>
				</a>
			</li>";



	# Insert Element to box['all'] array
	if($arr_cnt['all']['cnt'] < 8){

		$box['all'] .= $rs_box;
		$arr_cnt['all']['cnt'] += 1;

	}
	$arr_cnt['all']['total'] += 1; //total



	# Insert Element to box[$var] array
	if($arr_cnt[$rs2['EC_IDX']]['cnt'] < 8){

		$box[$rs2['EC_IDX']] .= $rs_box;
		$arr_cnt[$rs2['EC_IDX']]['cnt'] += 1;

	}
	$arr_cnt[$rs2['EC_IDX']]['total'] += 1; //total



} // row2 loop end



# Cache out
unset($s_path, $t_path, $thumb);



# UL그룹을 만들어서 박스를 뿌린다.
$ul_gr = "";
foreach($arr_cate as $var)
{

	$active = $var == 'all' ? 'active' : '';
	$style  = "width: 100%; text-align: center; height: auto; padding: 30px 0; background: none;";

	if(strlen($box[$var]) < 1){
		$box[$var] = "<li style=\"".$style."\">행사가 없습니다.</li>";
	}
	$ul_gr .= "<ul id=\"tab".$var."\" class=\"".$active."\" max=\"".$arr_cnt[$var]['total']."\">
				".$box[$var]."
			  </ul>";
	
	if($arr_cnt[$var]['total'] > 8 && $arr_cnt[$var]['cnt'] <= 8 ){
		//해당하는 그룹에 자식이 1명이라도 있으면.
		$ul_gr .= "<a href=\"javascript:;\" class=\"more\" data=\"".$var."\"><span>더보기</span> <i class=\"fa fa-spinner fa-pulse fa-3x fa-fw\"></i><span class=\"sr-only\">Loading...</span></a>";
	}
}

?>


<link rel="stylesheet" href="<?php echo(G5_PLUGIN_URL)?>/OwlCarousel2/assets/owl.carousel.min.css" />
<script src="<?php echo(G5_PLUGIN_URL)?>/OwlCarousel2/owl.carousel.min.js"></script>


<style>
 /*body{background: url(/thema/hub/assets/images/_main.jpg)  50% 0% / 1600px no-repeat;}*/
 .at-body{background: none;}
 #thema_wrapper{background: none;}
 .at-body.nx_main_bg{background: none; }

 .nx-tab-group ul:not(.active) + a.more{display: none;}
 .nx-tab-group li a .status span {
    display: inline-block;
    padding: 2px 5px;
    font-size: 14px;
    color: #fff;
    border-radius: 4px
}


.nx-tab-group li a .status .ready {
    background: #8dd25b
}

.nx-tab-group li a .status .receipt {
    background: #3f9de3
}

.nx-tab-group li a .status .end {
    background: #f95c5c
}
.nx-tab-group a.more i.fa{display: none;}
.nx-tab-group a.more.processing i.fa{display: inline-block;}
.nx-tab-group a.more.processing > span{display: none;}

.widget-area .div-title-underbar{margin-bottom: 10px;}

</style>
<link rel="stylesheet" href="/js/jquery.bxslider/jquery.bxslider.css">
<script src="/js/jquery.bxslider/jquery.bxslider.js"></script>

<div class="at-container widget-index clearfix" style="max-width: 100%;">

	<section id="row1" class="row">

		
		<?php echo apms_widget('nx-widget-title', $wid.'-wt1', '', 'auto=0'); //타이틀 ?>
		
		<div class="nx-widget-wrap">
			<div class="nx-widget">
				<div class="widget-title">공지사항 <a href="/bbs/board.php?bo_table=notice" class="more"></a></div>
				<div class="nx-notice-box">
					<?php echo apms_widget('nx-post-notice', 'widget-17', '', 'auto=0'); //타이틀 ?>
				</div>
			</div>
			<div class="nx-widget">
				<div class="widget-title">도정주요소식 <a href="/bbs/board.php?bo_table=dojungnews" class="more"></a></div>
				<div class="nx-notice-box">
					<?php echo apms_widget('nx-post-notice', 'widget-18', '', 'auto=0'); //타이틀 ?>
				</div>
			</div>
		</div>

		<div class="clearfix"></div>


	</section>

	<section id="row2" class="row">
		<h3 class="section-title">What's on</h3>

		<?php echo apms_widget('nx-widget-whatson', $wid.'-wt2', '', 'auto=0'); ?>

	</section>

	<section id="row3" class="row">
		
	  <div class="nx-tab">
	  	<div class="nx-tab-wrap">

		  	<button class="tab-left"><img src="/thema/hub/assets/images/tab_arrow.png" alt="이전"></button>
		  	<button class="tab-right rotate-180"><img src="/thema/hub/assets/images/tab_arrow.png" alt="다음"></button>
		  	<div class="nx-tab-category">
			  	<ul>
			  		<?php echo $category ?>
			  	</ul>
			</div>

			<style>
				.nx-tab-category .bx-wrapper{
					box-shadow: none;
					border: none;
					margin: 0 ;
					background: transparent;
				}
			</style>

	  	</div>
	  </div>

	  <script>
	  (function(){

	  	var ul = $('.nx-tab-category');
	  	var li = ul.find('li');
	  	var leng = li.length;

	  	$(document).on('click', '.nx-tab-category a[href^="#"]', function(e){
	  		e.preventDefault();
	  		var href = $(this).attr('href');
	  		$(this).closest('li').addClass('active').siblings('li').removeClass('active');
	  		$('#evt-tab-group').find('ul' + href ).addClass('active')
	  			.siblings('ul').removeClass('active');
	  	})

	  	$(document).on('click', '#evt-tab-group .more', function(e){
	  		e.preventDefault();
	  		var id = $('#evt-tab-group ul.active').attr('id');
	  		var leng = $('#evt-tab-group ul.active').find('li').length;
	  		more(id, leng);
	  	})

	  	/*

	  	if(leng > 2){
	  		$('.tab-left').addClass('active');
	  	}

	  	var idx = 0;
	  	var ul_width = 0;
	  	li.each(function(){
	  		ul_width += parseInt($(this).outerWidth()) + 70;
	  	})
	  	ul.find('ul').css('min-width' , ul_width);

	  	if(parseInt(ul.width()) < ul_width){
	  		$('.tab-left').addClass('active');
	  	}

	  	$(document).on('click', '.tab-left.active', function(e){
	  		mov_left();
	  	})

	  	$(document).on('click', '.tab-right.active', function(e){
	  		mov_right();
	  	})

	  	chk_arrow();

	  	$(window).resize(function(){
	  		chk_arrow();
	  	})

	  	function mov_left(){

	  		var ma_space = parseInt(li.eq(idx).outerWidth());
	  		//console.log(ma_space);
	  		ul.find('ul').stop(false, true).animate({
	  			'margin-left' : '-=' + ma_space
	  		})
	  		idx += 1;

	  		chk_arrow();
	  	}

	  	function mov_right(){
	  		var ma_space = parseInt(li.eq(idx).outerWidth());
	  		//console.log(ma_space);
	  		ul.find('ul').stop(false, true).animate({
	  			'margin-left' : '+=' + ma_space
	  		})
	  		idx -= 1;

	  		chk_arrow();
	  	}

	  	function chk_arrow(){

	  		// Right 
	  		if(idx > 0 && ul_width > parseInt(ul.width()) ){
	  			$('.tab-right').addClass('active');
	  		}else{
	  			$('.tab-right').removeClass('active');
	  		}

	  		//Left

	  		//visible width
	  		var visible_width = ul_width - parseInt(ul.width()) - parseInt(li.eq(0).outerWidth());
	  		
	  		//console.log(visible_width);

	  		if( parseInt(ul.find('ul').css('margin-left')) <= (-1 * visible_width) ){
	  			$('.tab-left').removeClass('active');
	  		}else{
	  			$('.tab-left').addClass('active');
	  		}


	  	}

	  	*/

	  	function more($id, $leng){

	  		$('#evt-tab-group ul.active + a.more').addClass('processing');

	  		var senddata = {'id' : $id, 'leng' : $leng};
	  		// id는 해당하는 table이나 group, leng은 limit에 사용된다. 한번에 8개씩 불러온다. (limit $leng, 8);
	  		$.ajax({
	  			url : '/thema/hub/main/getli.php',
	  			method : 'POST',
	  			data : senddata,
	  			success : function(data){

	  				if(data == 'nodata'){
	  					alert('값이 없습니다');
	  					$('#evt-tab-group ul.active + a.more').removeClass('processing');
	  					return false;
	  				}
	  				//console.log(data)
	  				appendHtml(data);
	  				$('#evt-tab-group ul.active + a.more').removeClass('processing');
	  				//li를 집어 넣는다.
	  			},
	  			error : function(msg){
	  				console.log(msg);
	  				$('#evt-tab-group ul.active + a.more').removeClass('processing');
	  			}
	  		})
	  	}

	  	function appendHtml(data){
	  		//data는 html 형태의 li들이 들어있다.
	  		$('#evt-tab-group ul.active').append(data);
	  		chk_max();

	  		//만약 more를 누르고 유저가 탭을 바꿨다면 어떡하지?? 값을 하나 더 넘기면 되는데, 어떻게 가져올까?
	  	}

	  	function chk_max(){
	  		var cur_ul = $('#evt-tab-group ul.active');
	  		var cur_li = cur_ul.find('li').length;
	  		var max    = cur_ul.attr('max');

	  		if(cur_li >= max){
	  			//cur_ul.next('.processing').remove();
	  			$('.processing').remove();
	  		}
	  	}

	  })();
	  
	  </script>

	  <div id="evt-tab-group" class="nx-tab-group">
		  <?php echo $ul_gr; ?>		  	
	  </div>

	</section>

	<?php # 슬라이드1 영역 ?>
	<section class="nx-main-slide1-section">
		<div class="nx-main-tit black">
			<h3 class="section-title">
				진흥원의 최근 현황
				<span class="section-sub-title">경기도평생교육진흥원에서 있었던 최근 현황을 사진으로 모았습니다.</span>
			</h3>
		</div>	
		<?php echo apms_widget('nx-slider', $wid.'-wg1', 'nav_size=big'); ?>
	</section>

	<section id="row4" class="row">
		<h3 class="section-title">소통하는 진흥원<span class="section-sub-title">경기도평생교육진흥원에서 추진하는 주요 사업 내용을 모았습니다.</span></h3>

		<div class="widget-area">
			<div class="row">

				<?php nx_widget_box($font); ?>

			</div>
		</div>

	</section>

	<?php
	/*
	<section id="row5" class="row" style="display: none;">
		<div class="learn-box">
			<div class="box">
				<div class="box-title"><span>평생학습</span> 온라인강좌</div>
				<ul>
					<li><a href="#">
						<div class="img-box">
							<img src="//www.placehold.it/178x100" alt="">
						</div>
						<div class="txt-box">
							<p class="nx-post-title">SNS를 활용한 데이터수집 조목조목 알아보기</p>
							<p class="nx-post-name">홍길동</p>
						</div>
					</a></li>
					<li><a href="#">
						<div class="img-box">
							<img src="//www.placehold.it/178x100" alt="">
						</div>
						<div class="txt-box">
							<p class="nx-post-title">SNS를 활용한 데이터수집 조목조목 알아보기</p>
							<p class="nx-post-name">홍길동</p>
						</div>
					</a></li>
					<li><a href="#">
						<div class="img-box">
							<img src="//www.placehold.it/178x100" alt="">
						</div>
						<div class="txt-box">
							<p class="nx-post-title">SNS를 활용한 데이터수집 조목조목 알아보기</p>
							<p class="nx-post-name">홍길동</p>
						</div>
					</a></li>
				</ul>
			</div>
			<div class="box">
				<div class="box-title"><span>평생학습</span> 오프라인강좌</div>
				<ul>
					<li><a href="#">
						<div class="img-box">
							<img src="//www.placehold.it/178x100" alt="">
						</div>
						<div class="txt-box">
							<p class="nx-post-title">SNS를 활용한 데이터수집 조목조목 알아보기</p>
							<p class="nx-post-name">홍길동</p>
						</div>
					</a></li>
					<li><a href="#">
						<div class="img-box">
							<img src="//www.placehold.it/178x100" alt="">
						</div>
						<div class="txt-box">
							<p class="nx-post-title">SNS를 활용한 데이터수집 조목조목 알아보기</p>
							<p class="nx-post-name">홍길동</p>
						</div>
					</a></li>
					<li><a href="#">
						<div class="img-box">
							<img src="//www.placehold.it/178x100" alt="">
						</div>
						<div class="txt-box">
							<p class="nx-post-title">SNS를 활용한 데이터수집 조목조목 알아보기</p>
							<p class="nx-post-name">홍길동</p>
						</div>
					</a></li>
				</ul>
			</div>
		</div>
	</section>
	*/
	?>

	<!-- 배너 시작 -->
	<div class="nx_widget_box nx-rolling-banner" style="margin-bottom:0;">
		<div class="widget-box">
			<?php echo apms_widget('nx-slider', $wid.'-wm11', 'center=1'); ?>
		</div>
	</div>
	<!-- 배너 끝 -->

	<?php //include(THEMA_PATH.'/quickmenu.php') ?>
</div>
