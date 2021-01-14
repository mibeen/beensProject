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

# 지난호보기에 자동으로 1개월 전 날짜가 표시되도록 한다.
$prev_month = date("Y.m", strtotime("-1 month"));

?>
<style>
 /*body{background: url(/thema/hub/assets/images/_main.jpg)  50% 0% / 1600px no-repeat;}*/
 .at-body{background: none;}
 #thema_wrapper{background: none;}
 .at-body.nx_main_bg{background: none; }
</style>
<link rel="stylesheet" href="/js/jquery.bxslider/jquery.bxslider.css">
<script src="/js/jquery.bxslider/jquery.bxslider.js"></script>


<div class="at-container widget-index clearfix" style="max-width: 100%;">

	<section class="row" id="row0">

	<?php echo apms_widget('nx-widget-title', $wid.'-wt1', '', 'auto=0'); //타이틀 ?>
		
	</section>


    <!-- section view before -->

    <section class="row" id="row1">
      <h3 class="nx-section-title">지난호보기</h3>
      <p class="nx-section-subtitle"><?php echo $prev_month ?></p>

      <ul class="nx-view-before">
        <?php echo apms_widget('nx-view-before', 'view-before1', '', 'auto=1'); //타이틀 ?>
        <?php echo apms_widget('nx-view-before', 'view-before2', '', 'auto=1'); //타이틀 ?>
        <?php echo apms_widget('nx-view-before', 'view-before3', '', 'auto=1'); //타이틀 ?>
        <?php echo apms_widget('nx-view-before', 'view-before4', '', 'auto=1'); //타이틀 ?>
        <?php /* 만약 DB가 초기화된 상태이면 g5_apms_data에 view-before- 로 시작하는 데이터 4개를 만들어 줘야 합니다. */ ?>
      </ul>
    </section>


    <section class="widget-area row">
      <div class="widget-group">

      	<?php nx_widget_box($font); ?>

      </div>
    </section>

<script>
$(function(){

  $(window).resize(function(){
    setWidget();
  })
  $(window).load(function(){
    setWidget();
  })

})

function setWidget(){

  var widget = $('.widget-fullimg');
  var box = widget.closest('.nx_widget_box2').parent('div');

  var near_height = parseInt(box.siblings('div').height());

  box.height(near_height);

}
</script>

	<!-- 배너 시작 -->
	<div class="nx_widget_box nx-rolling-banner" style="display: none;">
		<div class="widget-box">
			<?php echo apms_widget('nx-rolling-banner', $wid.'-wm11', 'center=1'); ?>
		</div>
	</div>
	<!-- 배너 끝 -->

	<?php //include(THEMA_PATH.'/quickmenu.php') ?>
</div>
