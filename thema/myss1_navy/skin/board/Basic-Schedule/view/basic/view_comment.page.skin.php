<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// 값정리
$boset['cmt_photo'] = (isset($boset['cmt_photo'])) ? $boset['cmt_photo'] : '';
$boset['cmt_re'] = (isset($boset['cmt_re'])) ? $boset['cmt_re'] : '';

// 댓글추천
$is_cmt_good = ($board['bo_use_good'] && isset($boset['cgood']) && $boset['cgood']) ? true : false;
$is_cmt_nogood = ($board['bo_use_nogood'] && isset($boset['cnogood']) && $boset['cnogood']) ? true : false;

// 회원사진, 대댓글 이름
if(G5_IS_MOBILE) {
	$depth_gap = 20;
	$is_cmt_photo = (!$boset['cmt_photo'] || $boset['cmt_photo'] == "2") ? true : false;
	$is_replyname = ($boset['cmt_re'] == "1" || $boset['cmt_re'] == "3") ? true : false;
} else {
	$is_cmt_photo = (!$boset['cmt_photo'] || $boset['cmt_photo'] == "1") ? true : false;
	$is_replyname = ($boset['cmt_re'] == "1" || $boset['cmt_re'] == "2") ? true : false;
	$depth_gap = ($is_cmt_photo) ? 64 : 30;
}

$cmt_amt = count($list);
?>

<div id="viewcomment">
	<div class="view-comment font-18 en">
		<i class="fa fa-commenting"></i> <span class="orangered"><?php echo number_format($write['wr_comment']);?></span> 댓글
	</div>
	<?php
	// 댓글이 있으면
	if($cmt_amt) {
	?>
		<section id="bo_vc" class="comment-media">
			<?php
			for ($i=0; $i<$cmt_amt; $i++) {
				$comment_id = $list[$i]['wr_id'];
				$cmt_depth = ""; // 댓글단계
				$cmt_depth = strlen($list[$i]['wr_comment_reply']) * $depth_gap;
				$comment = $list[$i]['content'];
				$cmt_sv = $cmt_amt - $i + 1; // 댓글 헤더 z-index 재설정 ie8 이하 사이드뷰 겹침 문제 해결
				if(APMS_PIM && $list[$i]['is_secret']) {
					$comment = '<a href="./password.php?w=sc&amp;bo_table='.$bo_table.'&amp;wr_id='.$list[$i]['wr_id'].$qstr.'" target="_parent" class="s_cmt">댓글내용 확인</a>';
				}
			 ?>
				<div class="media" id="c_<?php echo $comment_id ?>"<?php echo ($cmt_depth) ? ' style="margin-left:'.$cmt_depth.'px;"' : ''; ?>>
					<?php 
						if($is_cmt_photo) { // 회원사진
							$cmt_photo_url = apms_photo_url($list[$i]['mb_id']);
							$cmt_photo = ($cmt_photo_url) ? '<img src="'.$cmt_photo_url.'" alt="" class="media-object">' : '<div class="media-object"><i class="fa fa-user"></i></div>';
							echo '<div class="photo pull-left">'.$cmt_photo.'</div>'.PHP_EOL;
						 }
					?>
					<div class="media-body">
						<div class="media-heading">
							<b><?php echo $list[$i]['name'] ?></b>
							<span class="font-11 text-muted">
								<span class="media-info">
									<i class="fa fa-clock-o"></i>
									<?php echo apms_date($list[$i]['date'], 'orangered', 'before');?>
								</span>
								<?php if ($is_ip_view) { ?>	<span class="print-hide hidden-xs media-info"><i class="fa fa-thumb-tack"></i> <?php echo $list[$i]['ip']; ?></span> <?php } ?>
							</span>
							&nbsp;
							<?php if ($list[$i]['wr_facebook_user']) { ?>
								<a href="https://www.facebook.com/profile.php?id=<?php echo $list[$i]['wr_facebook_user']; ?>" target="_blank"><i class="fa fa-facebook-square fa-lg lightgray"></i><span class="sound_only">페이스북에도 등록됨</span></a>
							<?php } ?>
							<?php if ($list[$i]['wr_twitter_user']) { ?>
								<a href="https://www.twitter.com/<?php echo $list[$i]['wr_twitter_user']; ?>" target="_blank"><i class="fa fa-twitter-square fa-lg lightgray"></i><span class="sound_only">트위터에도 등록됨</span></a>
							<?php } ?>
							<?php if($list[$i]['is_reply'] || $list[$i]['is_edit'] || $list[$i]['is_del'] || $is_shingo || $is_admin) {

								$query_string = clean_query_string($_SERVER['QUERY_STRING']);

								if($w == 'cu') {
									$sql = " select wr_id, wr_content from $write_table where wr_id = '$c_id' and wr_is_comment = '1' ";
									$cmt = sql_fetch($sql);
									$c_wr_content = $cmt['wr_content'];
								}

								$c_reply_href = './board.php?'.$query_string.'&amp;c_id='.$comment_id.'&amp;w=c#bo_vc_w';
								$c_edit_href = './board.php?'.$query_string.'&amp;c_id='.$comment_id.'&amp;w=cu#b