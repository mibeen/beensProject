<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// 댓글추천
$is_cmt_good = ($board['bo_use_good'] && $boset['cgood']) ? true : false;
$is_cmt_nogood = ($board['bo_use_nogood'] && $boset['cnogood']) ? true : false;

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
?>

<div id="viewcomment" class="view-comment-box">
	<section id="bo_vc" class="comment-media">
		<?php
		$cmt_amt = count($list);
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
						<?php if($list[$i]['best']) { ?>
							<span class="rank-icon bg-orangered en">BEST <?php echo $list[$i]['best'];?></span>
						<?php } ?>
						<b><?php echo $list[$i]['name'] ?></b>
						<span class="font-11 text-muted">
							<span class="media-info">
								<i class="fa fa-clock-o"></i>
								<?php echo apms_date($list[$i]['date'], 'orangered');?>
							</span>
							<?php if ($is_ip_view) { ?>	<span class="print-hide hidden-xs media-info"><i class="fa fa-thumb-tack"></i> <?php echo $list[$i]['ip']; ?></span> <?php } ?>
							<?php if ($list[$i]['wr_facebook_user']) { ?>
								<a href="https://www.facebook.com/profile.php?id=<?php echo $list[$i]['wr_facebook_user']; ?>" target="_blank"><i class="fa fa-facebook"></i><span class="sound_only">페이스북에도 등록됨</span></a>
							<?php } ?>
							<?php if ($list[$i]['wr_twitter_user']) { ?>
								<a href="https://www.twitter.com/<?php echo $list[$i]['wr_twitter_user']; ?>" target="_blank"><i class="fa fa-facebook"></i><span class="sound_only">트위터에도 등록됨</span></a>
							<?php } ?>
						</span>
						<?php if($list[$i]['is_reply'] || $list[$i]['is_edit'] || $list[$i]['is_del'] || $is_shingo || $is_admin) {

							$query_string = clean_query_string($_SERVER['QUERY_STRING']);

							if($w == 'cu') {
								$sql = " select wr_id, wr_content, mb_id from $write_table where wr_id = '$c_id' and wr_is_comment = '1' ";
								$cmt = sql_fetch($sql);
								if (!($is_admin || ($member['mb_id'] == $cmt['mb_id'] && $cmt['mb_id'])))
									$cmt['wr_content'] = '';
								$c_wr_content = $cmt['wr_content'];
							}

							$c_reply_href = './board.php?'.$query_string.'&amp;c_id='.$comment_id.'&amp;w=c#bo_vc_w';
							$c_edit_href = './board.php?'.$query_string.'&amp;c_id='.$comment_id.'&amp;w=cu#bo_vc_w';

						 ?>
							<div class="print-hide pull-right font-11 ">
								<?php if ($list[$i]['is_reply']) { ?>
									<a href="<?php echo $c_reply_href;  ?>" onclick="comment_box('<?php echo $comment_id ?>', 'c'); return false;">
										<span class="text-muted">답변</span>
									</a>
								<?php } ?>
								<?php if ($list[$i]['is_edit']) { ?>
									<a href="<?php echo $c_edit_href;  ?>" onclick="comment_box('<?php echo $comment_id ?>', 'cu'); return false;">
										<span class="text-muted media-btn">수정</span>
									</a>
								<?php } ?>
								<?php if ($list[$i]['is_del'])  { ?>
									<a href="<?php echo $list[$i]['del_link']; ?>" onclick="<?php echo($list[$i]['del_return']) ? "apms_delete('viewcomment', '".$list[$i]['del_href']."','".$list[$i]['del_return']."'); return false;" : "return comment_delete();";?>">
										<span class="text-muted media-btn">삭제</span>
									</a>
								<?php } ?>
								<?php if ($is_shingo)  { ?>
									<a href="#" onclick="apms_shingo('<?php echo $bo_table;?>', '<?php echo $comment_id ?>'); return false;">
										<span class="text-muted media-btn">신고</span>
									</a>
								<?php } ?>
								<?php if ($is_admin) { ?>
									<?php if ($list[$i]['is_lock']) { // 글이 잠긴상태이면 ?>
										<a href="#" onclick="apms_shingo('<?php echo $bo_table;?>', '<?php echo $comment_id;?>', 'unlock'); return false;">
											<span class="text-muted media-btn"><i class="fa fa-unlock fa-lg"></i><span class="sound_only">해제</span></span>
										</a>
									<?php } else { ?>
										<a href="#" onclick="apms_shingo('<?php echo $bo_table;?>', '<?php echo $comment_id;?>', 'lock'); return false;">
											<span class="text-muted media-btn"><i class="fa fa-lock fa-lg"></i><span class="sound_only">잠금</span></span>
										</a>
									<?php } ?>
								<?php } ?>
							</div>
						<?php } ?>
					</div>
					<div class="media-content">
						<?php if (strstr($list[$i]['wr_option'], "secret")) { ?>
							<img src="<?php echo $board_skin_url;?>/img/icon_secret.gif" alt="">
						<?php } ?>
						<?php echo ($is_replyname && $list[$i]['reply_name']) ? '<b>[<span class="en">@</span>'.$list[$i]['reply_name'].']</b>'.PHP_EOL : ''; ?>
						<?php echo $comment ?>
						<?php if($is_cmt_good || $is_cmt_nogood) { ?>
							<div class="cmt-good-btn">
								<?php if($is_cmt_good) { ?>
									<a href="#" class="cmt-good" onclick="apms_good('<?php echo $bo_table;?>', '<?php echo $wr_id;?>', 'good', 'c_good<?php echo $comment_id;?>', '<?php echo $comment_id;?>'); return false;">
										<span id="c_good<?php echo $comment_id;?>"><?php echo number_format($list[$i]['wr_good']) ?></span>
									</a><?php } ?><?php if($is_cmt_nogood) { ?><a href="#" class="cmt-nogood" onclick="apms_good('<?php echo $bo_table;?>', '<?php echo $wr_id;?>', 'nogood', 'c_nogood<?php echo $comment_id;?>', '<?php echo $comment_id;?>'); return false;">
										<span id="c_nogood<?php echo $comment_id;?>"><?php echo number_format($list[$i]['wr_nogood']) ?></span>
									</a>
								<?php } ?>
							</div>
						<?php } ?>
						<?php if(!G5_IS_MOBILE) { // PC ?>
							<span id="edit_<?php echo $comment_id ?>"></span><!-- 수정 -->
							<span id="reply_<?php echo $comment_id ?>"></span><!-- 답변 -->
							<input type="hidden" value="<?php echo $comment_url.'&amp;page='.$page; ?>" id="comment_url_<?php echo $comment_id ?>">
							<input type="hidden" value="<?php echo $page; ?>" id="comment_page_<?php echo $comment_id ?>">
							<input type="hidden" value="<?php echo strstr($list[$i]['wr_option'],"secret") ?>" id="secret_comment_<?php echo $comment_id ?>">
							<textarea id="save_comment_<?php echo $comment_id ?>" style="display:none"><?php echo get_text($list[$i]['content1'], 0) ?></textarea>
						<?php } ?>
					</div>
			  </div>
			</div>
			<?php if(G5_IS_MOBILE) { // Mobile ?>
				<span id="edit_<?php echo $comment_id ?>"></span><!-- 수정 -->
				<span id="reply_<?php echo $comment_id ?>"></span><!-- 답변 -->
				<input type="hidden" value="<?php echo $comment_url.'&amp;page='.$page; ?>" id="comment_url_<?php echo $comment_id ?>">
				<input type="hidden" value="<?php echo $page; ?>" id="comment_page_<?p