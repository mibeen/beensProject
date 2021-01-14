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

?>
<style>
 /*body{background: url(/thema/hub/assets/images/_main.jpg)  50% 0% / 1600px no-repeat;}*/
 .at-body{background: none;}
 #thema_wrapper{background: none;}
 .at-body.nx_main_bg{background: none; }
</style>
<link rel="stylesheet" href="/js/jquery.bxslider/jquery.bxslider.css">
<script src="/js/jquery.bxslider/jquery.bxslider.js"></script>
<script src="/js/nx.midslider/nx.midslider.js"></script>
<script>
$(function(){

  var midSlider = new mid_slider();

})
</script>

<div class="at-container widget-index clearfix" style="max-width: 100%;">

	<section id="row1" class="row">

		
		<?php echo apms_widget('nx-widget-title', $wid.'-wt1', '', 'auto=0'); //타이틀 ?>
		<?php /* 
			   * 위젯의 기본 형태 ?>
		<div class="widget-slider">
			<ul>
				<li><a href="#"><img src="/thema/hub/assets/images/main_banner.png" alt=""></a></li>
				<li><a href="#"><img src="/thema/hub/assets/images/main_banner.png" alt=""></a></li>
			</ul>
		</div>
		<?php */ ?>
		


		<div class="nx-widget-wrap">
			<div class="nx-widget">
				<div class="widget-title">공지사항 <a href="/bbs/board.php?bo_table=notice" class="more"></a></div>
				<div class="nx-notice-box">
					<?php echo apms_widget('nx-post-notice', 'widget-17', '', 'auto=0'); //타이틀 ?>
				</div>

				<?php /*
				       * 공지사항 위젯..
				       * 근데 사용자단에서 위젯을 등록할 수 있으면 안될텐데..
				?>

				
					<ul>
						<li>
							<p class="title"><a href="#">[채용] 2017년 제11차 계약직 직원 채용 공고</a></p>
							<p class="date"><a href="#">2017-09-08</a></p>
						</li>
						<li>
							<p class="title"><a href="#">[채용] 2017년 제11차 계약직 직원 채용 공고</a></p>
							<p class="date"><a href="#">2017-09-08</a></p>
						</li>
						<li>
							<p class="title"><a href="#">[채용] 2017년 제11차 계약직 직원 채용 공고</a></p>
							<p class="date"><a href="#">2017-09-08</a></p>
						</li>
						<li>
							<p class="title"><a href="#">[채용] 2017년 제11차 계약직 직원 채용 공고</a></p>
							<p class="date"><a href="#">2017-09-08</a></p>
						</li>
						<li>
							<p class="title"><a href="#">[채용] 2017년 제11차 계약직 직원 채용 공고</a></p>
							<p class="date"><a href="#">2017-09-08</a></p>
						</li>
					</ul>
				
				<?php */ ?>
			</div>
		</div>

		<div class="clearfix"></div>


	</section>

	<section id="row2" class="row">
		<h3 class="section-title">What's on</h3>

		<div class="slider-visible">
			<div class="mid-slider">
				<ul>
					<li><a href="#"><img src="/thema/hub/assets/images/mid_banner2.png" alt=""></a></li>
					<li><a href="#"><img src="/thema/hub/assets/images/mid_banner2.png" alt=""></a></li>
					<li><a href="#"><img src="/thema/hub/assets/images/mid_banner2.png" alt=""></a></li>
				</ul>
			</div>
		</div>
		<div class="mid-slider-caption">
			<p class="title">체인지업캠퍼스 사이버 랭귀지스쿨</p>
			<p class="sub">사이버랭귀지스쿨의 다양한 코스를 확인하세요.</p>
			<button class="btn_left"><img src="/thema/hub/assets/images/slide_arrow.png" alt="이전"></button>
			<button class="btn_right"><img src="/thema/hub/assets/images/slide_arrow.png" alt="다음"></button>
		</div>
	</section>

	<section id="row3" class="row">
		
	  <div class="nx-tab">
	  	<div class="nx-tab-wrap">

		  	<button class="tab-left"><img src="/thema/hub/assets/images/tab_arrow.png" alt="이전"></button>
		  	<button class="tab-right rotate-180"><img src="/thema/hub/assets/images/tab_arrow.png" alt="다음"></button>
		  	<div class="nx-tab-category">
			  	<ul>
			  		<li class="active"><a href="#all">전체보기</a></li>
			  		<li><a href="#tab1">카테고리1</a></li>
			  		<li><a href="#tab2">카테고리2</a></li>
			  		<li><a href="#tab3">카테고리3</a></li>
			  		<li><a href="#tab4">카테고리4</a></li>
			  		<li><a href="#tab5">카테고리5</a></li>
			  		<li><a href="#tab6">카테고리6</a></li>
			  		<li><a href="#tab2">카테고리2</a></li>
			  		<li><a href="#tab3">카테고리3</a></li>
			  		<li><a href="#tab4">카테고리4</a></li>
			  		<li><a href="#tab5">카테고리5</a></li>
			  		<li><a href="#tab6">카테고리6</a></li>
			  	</ul>
			</div>

	  	</div>
	  </div>

	  <script>
	  (function(){
	  	var ul = $('.nx-tab-category');
	  	var li = ul.find('li');
	  	var leng = li.length;

	  	if(leng > 2){
	  		$('.tab-left').addClass('active');
	  	}

	  	var idx = 0;
	  	var ul_width = 0;
	  	li.each(function(){
	  		ul_width += parseInt($(this).outerWidth()) + 70;
	  	})
	  	ul.find('ul').width(ul_width);

	  	if(parseInt(ul.width()) < ul_width){
	  		$('.tab-left').addClass('active');
	  	}

	  	$(document).on('click', '.tab-left.active', function(e){
	  		mov_left();
	  	})

	  	$(document).on('click', '.tab-right.active', function(e){
	  		mov_right();
	  	})

	  	$(window).resize(function(){
	  		chk_arrow();
	  	})

	  	$(document).on('click', '.nx-tab-category a[href^="#"]', function(e){
	  		var href = $(this).attr('href');
	  		$(this).closest('li').addClass('active').siblings('li').removeClass('active');
	  		$('.nx-tab-group').find('ul' + href ).addClass('active')
	  			.siblings('ul').removeClass('active');
	  	})

	  	$(document).on('click', '.nx-tab-group .more', function(e){
	  		e.preventDefault();
	  		var id = $('.nx-tab-group ul.active').attr('id');
	  		var leng = $('.nx-tab-group ul.active').find('li').length;
	  		more(id, leng);
	  	})

	  	function mov_left(){

	  		var ma_space = parseInt(li.eq(idx).outerWidth()) + 66;
	  		//console.log(ma_space);
	  		ul.find('ul').stop(false, true).animate({
	  			'margin-left' : '-=' + ma_space
	  		})
	  		idx += 1;

	  		chk_arrow();
	  	}

	  	function mov_right(){
	  		var ma_space = parseInt(li.eq(idx).outerWidth()) + 66;
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
	  		
	  		console.log(visible_width);

	  		if( parseInt(ul.find('ul').css('margin-left')) <= (-1 * visible_width) ){
	  			$('.tab-left').removeClass('active');
	  		}else{
	  			$('.tab-left').addClass('active');
	  		}


	  	}

	  	function more($id, $leng){

	  		var senddata = {'id' : $id, 'leng' : $leng};
	  		// id는 해당하는 table이나 group, leng은 limit에 사용된다. 한번에 8개씩 불러온다. (limit $leng, 8);
	  		$.ajax({
	  			url : '/thema/hub/main/getli.php',
	  			method : 'POST',
	  			data : senddata,
	  			success : function(data){
	  				//console.log(data)
	  				appendHtml(data);
	  				//li를 집어 넣는다.
	  			},
	  			error : function(msg){
	  				console.log(msg);
	  			}
	  		})
	  	}

	  	function appendHtml(data){
	  		//data는 html 형태의 li들이 들어있다.
	  		$('.nx-tab-group ul.active').append(data);

	  		//만약 more를 누르고 유저가 탭을 바꿨다면 어떡하지?? 값을 하나 더 넘기면 되는데, 어떻게 가져올까?
	  	}

	  })();
	  </script>

	  <div class="nx-tab-group">
	  	<ul id="tab1" class="active">
	  		<li><a href="">
	  				<div class="img-box"><img src="/thema/hub/assets/images/ban2_1.png" alt=""></div>
	  				<div class="status"><img src="/thema/hub/assets/images/status_a.png" alt=""></div>
	  				<div class="desc">
	  					<p class="title">2017 서울지식재산센터 지식재산 집중 교육</p>
	  					<p class="limit">인원 : <span>20명</span></p>
	  					<p class="date">날짜 : <span>2017.11.10 ~ 2018.01.02</span></p>
	  				</div>
	  			</a></li>
	  		<li><a href="">
	  				<div class="img-box"><img src="/thema/hub/assets/images/ban2_2.png" alt=""></div>
	  				<div class="status"><img src="/thema/hub/assets/images/status_b.png" alt=""></div>
	  				<div class="desc">
	  					<p class="title">2017 서울지식재산센터 지식재산 집중 교육</p>
	  					<p class="limit">인원 : <span>20명</span></p>
	  					<p class="date">날짜 : <span>2017.11.10 ~ 2018.01.02</span></p>
	  				</div>
	  			</a></li>
	  		<li><a href="">
	  				<div class="img-box"><img src="/thema/hub/assets/images/ban2_3.png" alt=""></div>
	  				<div class="status"><img src="/thema/hub/assets/images/status_a.png" alt=""></div>
	  				<div class="desc">
	  					<p class="title">2017 서울지식재산센터 지식재산 집중 교육</p>
	  					<p class="limit">인원 : <span>20명</span></p>
	  					<p class="date">날짜 : <span>2017.11.10 ~ 2018.01.02</span></p>
	  				</div>
	  			</a></li>
	  		<li><a href="">
	  				<div class="img-box"><img src="/thema/hub/assets/images/ban2_4.png" alt=""></div>
	  				<div class="status"><img src="/thema/hub/assets/images/status_b.png" alt=""></div>
	  				<div class="desc">
	  					<p class="title">2017 서울지식재산센터 지식재산 집중 교육</p>
	  					<p class="limit">인원 : <span>20명</span></p>
	  					<p class="date">날짜 : <span>2017.11.10 ~ 2018.01.02</span></p>
	  				</div>
	  			</a></li>
	  		<li><a href="">
	  				<div class="img-box"><img src="/thema/hub/assets/images/ban2_5.png" alt=""></div>
	  				<div class="status"><img src="/thema/hub/assets/images/status_b.png" alt=""></div>
	  				<div class="desc">
	  					<p class="title">2017 서울지식재산센터 지식재산 집중 교육</p>
	  					<p class="limit">인원 : <span>20명</span></p>
	  					<p class="date">날짜 : <span>2017.11.10 ~ 2018.01.02</span></p>
	  				</div>
	  			</a></li>
	  		<li><a href="">
	  				<div class="img-box"><img src="/thema/hub/assets/images/ban2_6.png" alt=""></div>
	  				<div class="status"><img src="/thema/hub/assets/images/status_a.png" alt=""></div>
	  				<div class="desc">
	  					<p class="title">2017 서울지식재산센터 지식재산 집중 교육</p>
	  					<p class="limit">인원 : <span>20명</span></p>
	  					<p class="date">날짜 : <span>2017.11.10 ~ 2018.01.02</span></p>
	  				</div>
	  			</a></li>
	  		<li><a href="">
	  				<div class="img-box"><img src="/thema/hub/assets/images/ban2_7.png" alt=""></div>
	  				<div class="status"><img src="/thema/hub/assets/images/status_a.png" alt=""></div>
	  				<div class="desc">
	  					<p class="title">2017 서울지식재산센터 지식재산 집중 교육</p>
	  					<p class="limit">인원 : <span>20명</span></p>
	  					<p class="date">날짜 : <span>2017.11.10 ~ 2018.01.02</span></p>
	  				</div>
	  			</a></li>
	  		<li><a href="">
	  				<div class="img-box"><img src="/thema/hub/assets/images/ban2_8.png" alt=""></div>
	  				<div class="status"><img src="/thema/hub/assets/images/status_b.png" alt=""></div>
	  				<div class="desc">
	  					<p class="title">2017 서울지식재산센터 지식재산 집중 교육</p>
	  					<p class="limit">인원 : <span>20명</span></p>
	  					<p class="date">날짜 : <span>2017.11.10 ~ 2018.01.02</span></p>
	  				</div>
	  			</a></li>
	  	</ul>

	  	<a href="javascript:;" class="more">더보기</a>
	  </div>

	</section>

	<section id="row4" class="row">
		<h3 class="section-title">사업 공고<span class="section-sub-title">경기도평생교육진흥원에서 진행하는 공고를 모았습니다.</span></h3>

		<div class="widget-area">
			<div class="row">

				<?php nx_widget_box($font); ?>

			</div>
		</div>

	</section>

	<section id="row5" class="row">
		<div class="learn-box">
			<div class="box">
				<div class="box-title"><span>평생학습</span> 온라인강좌</div>
				<ul>
					<li><a href="#">
						<div class="img-box">
							<img src="http://www.placehold.it/178x100" alt="">
						</div>
						<div class="txt-box">
							<p class="nx-post-title">SNS를 활용한 데이터수집 조목조목 알아보기</p>
							<p class="nx-post-name">홍길동</p>
						</div>
					</a></li>
					<li><a href="#">
						<div class="img-box">
							<img src="http://www.placehold.it/178x100" alt="">
						</div>
						<div class="txt-box">
							<p class="nx-post-title">SNS를 활용한 데이터수집 조목조목 알아보기</p>
							<p class="nx-post-name">홍길동</p>
						</div>
					</a></li>
					<li><a href="#">
						<div class="img-box">
							<img src="http://www.placehold.it/178x100" alt="">
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
							<img src="http://www.placehold.it/178x100" alt="">
						</div>
						<div class="txt-box">
							<p class="nx-post-title">SNS를 활용한 데이터수집 조목조목 알아보기</p>
							<p class="nx-post-name">홍길동</p>
						</div>
					</a></li>
					<li><a href="#">
						<div class="img-box">
							<img src="http://www.placehold.it/178x100" alt="">
						</div>
						<div class="txt-box">
							<p class="nx-post-title">SNS를 활용한 데이터수집 조목조목 알아보기</p>
							<p class="nx-post-name">홍길동</p>
						</div>
					</a></li>
					<li><a href="#">
						<div class="img-box">
							<img src="http://www.placehold.it/178x100" alt="">
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

	<!-- 배너 시작 -->
	<div class="nx_widget_box nx-rolling-banner" style="margin-bottom:0;">
		<div class="widget-box">
			<?php echo apms_widget('nx-rolling-banner', $wid.'-wm11', 'center=1'); ?>
		</div>
	</div>
	<!-- 배너 끝 -->

	<?php //include(THEMA_PATH.'/quickmenu.php') ?>
</div>
