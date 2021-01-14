<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// 위젯 대표아이디 설정
$wid = 'mcmu-20';

// 풀스크린 타이틀 설정 : 1이면 사용, 0이면 미사용(일반)
$fullscreen = 0;

// Ionicons : https://ionicons.com/v2/
add_stylesheet('<link rel="stylesheet" href="'.THEMA_URL.'/assets/css/css/ionicons.min.css" type="text/css">',-1);

// Main CSS
add_stylesheet('<link rel="stylesheet" href="'.THEMA_URL.'/main/css/community-20.css" type="text/css">',100);

?>

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
		<div class="row row-20">
			<div class="col-md-4 col-sm-6 col-20">

				<div class="sec-ion">
					<ul>
					<li>
						<a href="#이동경로">
							<i class="ion-ios-checkmark-outline"></i>
							<span>바로가기</span>
						</a>
					</li>
					<li>
						<a href="#이동경로">
							<i class="ion-ios-information-outline"></i>
							<span>바로가기</span>
						</a>
					</li>
					<li>
						<a href="#이동경로">
							<i class="ion-ios-download-outline"></i>
							<span>바로가기</span>
						</a>
					</li>
					<li>
						<a href="#이동경로">
							<i class="ion-ios-color-wand-outline"></i>
							<span>바로가기</span>
						</a>
					</li>
					</ul>
					<div class="clearfix"></div>
				</div>

				<div class="sec-title">
					<a href="#이동경로">
						<i class="fa fa-gift fa-lg"></i> 게시판
					</a>
				</div>

				<div class="sec-content">
					<?php echo apms_widget('miso-post', $wid.'-apms', 'skin=list-notice rows=4 icon={아이콘:gift}'); ?>
				</div>

			</div>
			<div class="col-md-4 col-sm-6 col-20">

				<div class="sec-line"></div>

				<div class="sec-ion">
					<ul>
					<li>
						<a href="#이동경로">
							<i class="ion-ios-timer-outline"></i>
							<span>바로가기</span>
						</a>
					</li>
					<li>
						<a href="<?php echo $at_href['secret'];?>">
							<i class="ion-ios-locked-outline"></i>
							<span>바로가기</span>
						</a>
					</li>
					<li>
						<a href="<?php echo $at_href['inquiry'];?>">
							<i class="ion-ios-bookmarks-outline"></i>
							<span>바로가기</span>
						</a>
					</li>
					<li>
						<a href="#이동경로">
							<i class="ion-ios-speedometer-outline"></i>
							<span>바로가기</span>
						</a>
					</li>
					</ul>
					<div class="clearfix"></div>
				</div>

				<div class="sec-title">
					<i class="fa fa-smile-o fa-lg"></i> 게시판
				</div>

				<div class="sec-content">
					<?php echo apms_widget('miso-post', $wid.'-notice', 'skin=list-notice rows=4 icon={아이콘:bell}'); ?>
				</div>
			</div>
			<div class="col-md-4 col-sm-12 col-20">

				<!-- 배너 -->
				<?php echo apms_widget('miso-post-slider', $wid.'-banner', 'margin=20 nav=2 heights=33% items=1,1,3,2,1 rdm=1 loop=1'); ?>
				
				<div style="height:12px;"></div>

				<div class="sec-content">
					<ul class="sec-status">
						<li><i class="fa fa-bug lightgray"></i> 전체 회원수 <span class="pull-right at-tip" data-original-title="<nobr>오늘 <?php echo $stats['join_today'];?> 명 / 어제 <?php echo $stats['join_yesterday'];?> 명</nobr>" data-toggle="tooltip" data-placement="top" data-html="true"><?php echo number_format($stats['join_total']); ?>명</span>
						</li>
						<li><a href="<?php echo $at_href['connect'];?>">
							<i class="fa fa-bug orangered"></i> 현재 접속자 <span class="pull-right"><?php echo number_format($stats['now_total']); ?><?php echo ($stats['now_mb'] > 0) ? '(<b class="orangered">'.number_format($stats['now_mb']).'</b>)' : ''; ?>명</span></a>
						</li>
						<li><i class="fa fa-bug lightgray"></i> 오늘 방문자 <span class="pull-right"><?php echo number_format($stats['visit_today']); ?>명</span></li>
						<li><i class="fa fa-bug lightgray"></i> 어제 방문자 <span class="pull-right"><?php echo number_format($stats['visit_yesterday']); ?>명</span></li>
						<li><i class="fa fa-bug lightgray"></i> 최대 방문자 <span class="pull-right"><?php echo number_format($stats['visit_max']); ?>명</span></li>
						<li><i class="fa fa-bug lightgray"></i> 전체 방문자 <span class="pull-right"><?php echo number_format($stats['visit_total']); ?>명</span></li>
						<li><i class="fa fa-bug lightgray"></i> 전체 게시물 <span class="pull-right"><?php echo number_format($menu[0]['count_write']); ?>개</span></li>
						<li><i class="fa fa-bug lightgray"></i> 전체 댓글수 <span class="pull-right"><?php echo number_format($menu[0]['count_comment']); ?>개</span></li>
					</ul>
					<div class="clearfix"></div>	
				</div>
			</div>
		</div>

		<div class="row row-20">
			<div class="col-md-4 col-sm-12 col-20">
				<div class="sec-title">
					<a href="#이동경로">
						<i class="fa fa-download fa-lg"></i> 게시판
					</a>
				</div>
				<div class="sec-content">
					<?php echo apms_widget('miso-post-slider', $wid.'-news', 'skin=list-notice sero=7 rows=21 items=1,1,2,2,1 margin=30 nav=1 icon={아이콘:bell}'); ?>
				</div>
			</div>
			<div class="col-md-4 col-sm-6 col-20">
				<div class="sec-title">
					<a href="#이동경로">
						<i class="fa fa-download fa-lg"></i> 게시판
					</a>
				</div>
				<div class="sec-content">
					<?php echo apms_widget('miso-post-slider', $wid.'-dl1', 'skin=list-blank sero=7 rows=21 items=1,1,1,2,1 margin=30 nav=1 icon={아이콘:download} bullet=1'); ?>
				</div>

			</div>
			<div class="col-md-4 col-sm-6 col-20">
				<div class="sec-title">
					<a href="#이동경로">
						<i class="fa fa-download fa-lg"></i> 게시판
					</a>
				</div>
				<div class="sec-content">
					<?php echo apms_widget('miso-post-slider', $wid.'-dl2', 'skin=list-blank sero=7 rows=21 items=1,1,1,2,1 margin=30 nav=1 icon={아이콘:download} bullet=1'); ?>
				</div>
			</div>
		</div>

		<div class="row row-20">
			<div class="col-md-4 col-sm-6 col-20">
				<div class="sec-title">
					<a href="#이동경로">
						<i class="fa fa-download fa-lg"></i> 게시판
					</a>
				</div>
				<div class="sec-content">
					<?php echo apms_widget('miso-post-slider', $wid.'-dl3', 'skin=list-blank sero=7 rows=21 items=1,1,1,2,1 margin=30 nav=1 icon={아이콘:download} bullet=1'); ?>
				</div>
			</div>
			<div class="col-md-4 col-sm-6 col-20">
				<div class="sec-title">
					<a href="#이동경로">
						<i class="fa fa-book fa-lg"></i> 게시판
					</a>
				</div>
				<div class="sec-content">
					<?php echo apms_widget('miso-post-slider', $wid.'-guide', 'skin=list-blank sero=7 rows=21 items=1,1,1,2,1 margin=30 nav=1 icon={아이콘:book} bullet=1'); ?>
				</div>
			</div>
			<div class="col-md-4 col-sm-12 col-20">
				<div class="sec-title">
					<a href="#이동경로">
						<i class="fa fa-comment fa-lg"></i> 게시판
					</a>
				</div>
				<div class="sec-content">
					<?php echo apms_widget('miso-post-slider', $wid.'-community', 'skin=list-name sero=7 rows=21 items=1,1,2,2,1 margin=30 nav=1 icon={아이콘:comment} bullet=1'); ?>
				</div>
			</div>
		</div>

		<div class="row row-20">
			<div class="col-md-8 col-sm-12 col-20">
				<div class="sec-title">
					<a href="#이동경로">
						<i class="fa fa-commenting fa-lg"></i> 게시판
					</a>
				</div>
				<div class="sec-content">
					<?php echo apms_widget('miso-post-slider', $wid.'-qna', 'skin=list-name sero=7 rows=28 items=2,2,2,2,1 margin=30 nav=1 icon={아이콘:commenting} bullet=1'); ?>
				</div>
			</div>
			<div class="col-md-4 col-sm-12 col-20">
				<div class="sec-title">
					<a href="#이동경로">
						<i class="fa fa-commenting-o fa-lg"></i> 게시판
					</a>
				</div>
				<div class="sec-content">
					<?php echo apms_widget('miso-post-slider', $wid.'-comment', 'skin=list-name sero=7 rows=21 items=1,1,2,2,1 margin=30 nav=1 icon={아이콘:commenting-o} bullet=1'); ?>
				</div>
			</div>
		</div>
	</div>
</div>
