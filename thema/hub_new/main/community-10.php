<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// 위젯 대표아이디 설정
$wid = 'mcmu-10';

// 사이드 위치 설정 - left, right
$side = 'right';

// 풀스크린 타이틀 설정 : 1이면 사용, 0이면 미사용(일반)
$fullscreen = 0;

// 데모용
if($is_demo) {
	if(isset($dtset['side'])) $side = $dtset['side'];
}

?>

<style>
	.main-wrap { padding:30px 0px 10px; overflow:hidden; }
	.main-line { height:25px; }
	@media all and (max-width:991px) {
		.responsive .main-wrap { padding:10px 0px; }
		.responsive .pull-right.col-md-9,
		.responsive .pull-left.col-md-3 { float:none !important; }
	}
	@media all and (max-width:767px) {
		.responsive .main-line { height:20px; }
	}

	/* 슬라이더 더보기 위치 조정 */
	.main-wrap .nav-top .owl-nav [class*='owl-'] {
		top: -37px;
	}
</style>

<!-- 타이틀 -->
<div class="at-mask<?php echo ($fullscreen) ? ' full-height' : '';?>">
	<?php echo apms_widget('miso-post-title', $wid.'-title', 'noslide=1 type=video vauto=1 vloop=1 vlist=dk9uNWPP7EA heights=200,200,200,200,200'); ?>
	<!--하단 마스크 -->
	<?php echo miso_mask('tail', $is_hmask, '#fff', $is_hmask_over);?>
</div>

<div class="clearfix"></div>

<?php @include_once(THEMA_PATH.'/wing.php'); // Wing ?>

<div class="at-boxed at-container">
	<div class="main-wrap">
		<div class="row">
			<!-- 메인 영역 -->
			<div class="col-md-9<?php echo ($side == "left") ? ' pull-right' : '';?>">
				
				<?php echo apms_widget('miso-post-bxslider', $wid.'-top-banner', 'margin=0 nav=2 heights=18% auto=1 thumb_w=0 rdm=1 loop=1'); ?>

				<div class="main-line"></div>

				<!-- 위젯시작 -->
				<h4 class="h4"><a href="#이동주소"><b>새소식</b></a></h4>
				<hr class="hr"/>

				<?php echo apms_widget('miso-post-slider', $wid.'-headline', 'skin=sp-img sero=5 rows=20 heights=40% margin=20 items=3,2,2,1,1 nav=1 loop=1'); ?>

				<div class="main-line"></div>

				<!-- 위젯시작 -->
				<h4 class="h4"><a href="#이동주소"><b>갤러리</b></a></h4>
				<hr class="hr"/>

				<?php echo apms_widget('miso-post-slider', $wid.'-gallery', 'skin=hover sero=2 rows=20 nav=1 loop=1'); ?>

				<div class="main-line"></div>

				<!-- 위젯시작 -->
				<h4 class="h4"><a href="#이동주소"><b>매거진</b></a></h4>
				<hr class="hr"/>

				<?php echo apms_widget('miso-post-slider', $wid.'-webzine', 'skin=webzine line=3 sero=2 margin=20 rows=8 items=2,2,2,1,1 nav=1 loop=1'); ?>

				<div class="main-line"></div>

				<!-- 위젯시작 -->
				<h4 class="h4"><a href="#이동주소"><b>스페셜</b></a></h4>
				<hr class="hr"/>

				<?php echo apms_widget('miso-post-slider', $wid.'-special', 'skin=sp-img2 sero=5 rows=20 rank=red items=3,3,3,2,1 margin=20 nav=1 loop=1'); ?>

				<div class="main-line"></div>

				<!-- 2단 영역 -->
				<div class="row row-20">
					<div class="col-md-8 col-20">

						<!-- 위젯시작 -->
						<h4 class="h4"><a href="#이동주소"><b>리스트</b></a></h4>
						<hr class="hr"/>

						<?php echo apms_widget('miso-post-slider', $wid.'-list4', 'skin=list sero=8 rows=24 items=2,2,3,2,1 margin=20 nav=1 loop=1 bullet=1'); ?>

						<div class="main-line"></div>
					</div>
					<div class="col-md-4 col-20">

						<!-- 위젯시작 -->
						<h4 class="h4"><a href="#이동주소"><b>리스트</b></a></h4>
						<hr class="hr"/>

						<?php echo apms_widget('miso-post-slider', $wid.'-list5', 'skin=list sero=8 rows=24 items=1,1,3,2,1 margin=20 nav=1 loop=1 bullet=1'); ?>

						<div class="main-line"></div>

					</div>
				</div>

				<!-- 3단 영역 -->
				<div class="row row-20">
					<div class="col-md-4 col-20">

						<!-- 위젯시작 -->
						<h4 class="h4"><a href="#이동주소"><b>리스트</b></a></h4>
						<hr class="hr"/>

						<?php echo apms_widget('miso-post-slider', $wid.'-list1', 'skin=list sero=8 rows=24 items=1,1,3,2,1 margin=20 nav=1 loop=1 bullet=1'); ?>

						<div class="main-line"></div>
					</div>
					<div class="col-md-4 col-20">

						<!-- 위젯시작 -->
						<h4 class="h4"><a href="#이동주소"><b>리스트</b></a></h4>
						<hr class="hr"/>

						<?php echo apms_widget('miso-post-slider', $wid.'-list2', 'skin=list sero=8 rows=24 items=1,1,3,2,1 margin=20 nav=1 loop=1 bullet=1'); ?>

						<div class="main-line"></div>

					</div>
					<div class="col-md-4 col-20">

						<!-- 위젯시작 -->
						<h4 class="h4"><a href="#이동주소"><b>리스트</b></a></h4>
						<hr class="hr"/>

						<?php echo apms_widget('miso-post-slider', $wid.'-list3', 'skin=list sero=8 rows=24 items=1,1,3,2,1 margin=20 nav=1 loop=1 bullet=1'); ?>

						<div class="main-line"></div>

					</div>
				</div>

				<!-- 위젯시작 -->
				<h4 class="h4"><a href="#이동주소"><b>배너</b></a></h4>
				<hr class="hr"/>

				<?php echo apms_widget('miso-post-slider', $wid.'-bottom-banner', 'margin=20 nav=1 heights=35% items=3,3,3,2,1 rdm=1 loop=1'); ?>

				<div class="main-line"></div>

			</div>
			<!-- 사이드 영역 -->
			<div class="col-md-3<?php echo ($side == "left") ? ' pull-left' : '';?>">

				<div class="hidden-sm hidden-xs">
					<?php echo apms_widget('miso-outlogin', '', 'login=navy logout=navy exp=exp ani=1'); //외부로그인 ?>
					<div class="main-line"></div>
				</div>
				
				<!-- 위젯시작 -->
				<h4 class="h4"><a href="#이동주소"><b>알림장</b></a></h4>
				<hr class="hr"/>

				<?php echo apms_widget('miso-post-slider', $wid.'-notice', 'skin=list sero=7 rows=21 items=1,1,3,2,1 margin=20 nav=1 loop=1'); ?>

				<div class="main-line"></div>

				<!-- 위젯시작 -->
				<h4 class="h4"><b>최근글</b></h4>
				<hr class="hr"/>

				<?php echo apms_widget('miso-post-slider', $wid.'-latest', 'skin=sp-img2 sero=5 rows=15 items=1,1,3,2,1 margin=20 nav=1 loop=1'); ?>

				<div class="main-line"></div>

				<!-- 위젯시작 -->
				<h4 class="h4"><b>키워드</b></h4>
				<hr class="hr"/>

				<?php echo apms_widget('miso-popular-slider', $wid.'-popular', 'skin=line noslide=1 rows=15'); ?>
				
				<?php //echo apms_widget('miso-popular-slider', $wid.'-popular', 'rank=green sero=5 items=1,1,3,2,1 rows=15 nav=1 loop=1'); ?>

				<div class="main-line"></div>

				<!-- 위젯시작 -->
				<h4 class="h4"><b>댓글</b></h4>
				<hr class="hr"/>

				<?php echo apms_widget('miso-post-slider', $wid.'-comment', 'skin=list-name comment=1 sero=10 rows=30 items=1,1,3,2,1 margin=20 nav=1 loop=1'); ?>

				<div class="main-line"></div>

				<!-- 위젯시작 -->
				<h4 class="h4"><b>멤버</b></h4>
				<hr class="hr"/>

				<?php echo apms_widget('miso-member-slider', $wid.'-member', 'rank=green sero=5 items=1,1,3,2,1 rows=20 margin=20 cnt=1 nav=1 loop=1'); ?>

				<div class="main-line"></div>

				<!-- 위젯시작 -->
				<h4 class="h4"><b>태그</b></h4>
				<hr class="hr"/>

				<?php //echo apms_widget('miso-tag-slider', $wid.'-tag', 'skin=line noslide=1 rows=15'); ?>
				
				<?php echo apms_widget('miso-tag-slider', $wid.'-tag', 'rank=blue sero=5 items=1,1,3,2,1 rows=15 nav=1 loop=1'); ?>

				<div class="main-line"></div>

				<!-- 위젯시작 -->
				<?php 
					//설문이 있을때만 출력
					$poll_content = apms_widget('miso-poll', $wid.'-poll', 'rows=4'); 
					if($poll_content) {	
				?>
					<h4 class="h4"><b>설문</b></h4>
					<hr class="hr"/>

					<?php echo $poll_content; ?>
	
					<div class="main-line"></div>
				<?php } ?>

				<h4 class="h4"><b>현황</b></h4>
				<hr class="hr"/>
				<ul style="padding:0; margin:0; list-style:none;">
					<li><a href="<?php echo $at_href['connect'];?>">
						현재 접속자 <span class="pull-right"><?php echo number_format($stats['now_total']); ?><?php echo ($stats['now_mb'] > 0) ? '('.number_format($stats['now_mb']).')' : ''; ?> 명</span></a>
					</li>
					<li>오늘 방문자 <span class="pull-right"><?php echo number_format($stats['visit_today']); ?> 명</span></li>
					<li>어제 방문자 <span class="pull-right"><?php echo number_format($stats['visit_yesterday']); ?> 명</span></li>
					<li>최대 방문자 <span class="pull-right"><?php echo number_format($stats['visit_max']); ?> 명</span></li>
					<li>전체 방문자 <span class="pull-right"><?php echo number_format($stats['visit_total']); ?> 명</span></li>
					<li>전체 회원수	<span class="pull-right at-tip" data-original-title="<nobr>오늘 <?php echo $stats['join_today'];?> 명 / 어제 <?php echo $stats['join_yesterday'];?> 명</nobr>" data-toggle="tooltip" data-placement="top" data-html="true"><?php echo number_format($stats['join_total']); ?> 명</span>
					</li>
					<li>전체 게시물	<span class="pull-right at-tip" data-original-title="<nobr>글 <?php echo number_format($menu[0]['count_write']);?> 개/ 댓글 <?php echo number_format($menu[0]['count_comment']);?> 개</nobr>" data-toggle="tooltip" data-placement="top" data-html="true"><?php echo number_format($menu[0]['count_write'] + $menu[0]['count_comment']); ?> 개</span>
					</li>
				</ul>

				<div class="main-line"></div>

				<!-- SNS아이콘 시작 -->
				<div class="text-center">
					<?php echo $sns_share_icon; // SNS 공유아이콘 ?>
				</div>

				<div class="main-line"></div>

			</div>
		</div>
		<div class="clearfix"></div>
	</div>
</div>