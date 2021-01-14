<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$result = sql_fetch("SELECT * FROM g5_board_extend WHERE bo_table = '{$bo_table}' AND wr_id = '{$wr_id}' LIMIT 0, 1");
#$sholar = sql_fetch($result);

$attach_list = '';
if ($view['link']) {
	// 링크
	for ($i=1; $i<=count($view['link']); $i++) {
		if ($view['link'][$i]) {
			#$attach_list .= '<a class="list-group-item break-word" href="'.$view['link_href'][$i].'" target="_blank">';
			#$attach_list .= '<span class="label label-warning pull-right view-cnt">'.number_format($view['link_hit'][$i]).'</span>';
			#$attach_list .= '<i class="fa fa-link"></i> '.cut_str($view['link'][$i], 70).'</a>'.PHP_EOL;
		}
	}
}

// 가변 파일
$j = 0;
for ($i=0; $i<count($view['file']); $i++) {
	if (isset($view['file'][$i]['source']) && $view['file'][$i]['source'] && !$view['file'][$i]['view']) {
		if ($board['bo_download_point'] < 0 && $j == 0) {
			#$attach_list .= '<a class="list-group-item"><i class="fa fa-bell red"></i> 다운로드시 <b>'.number_format(abs($board['bo_download_point'])).'</b>'.AS_MP.' 차감 (최초 1회 / 재다운로드시 차감없음)</a>'.PHP_EOL;
		}
		$file_tooltip = '';
		if($view['file'][$i]['content']) {
			$file_tooltip = ' data-original-title="'.strip_tags($view['file'][$i]['content']).'" data-toggle="tooltip"';
		}
		$attach_list .= '<a href="'.$view['file'][$i]['href'].'" class="btn btn-color btn-sm">';
		#$attach_list .= '<a class="thema-bg-color break-word view_file_download at-tip" href="'.$view['file'][$i]['href'].'"'.$file_tooltip.'>';
		#$attach_list .= '<span class="label label-primary pull-right view-cnt">'.number_format($view['file'][$i]['download']).'</span>';
		#$attach_list .= '<i class="fa fa-download"></i>원문 다운로드 : '.$view['file'][$i]['source'].' ('.$view['file'][$i]['size'].') &nbsp;';
		$attach_list .= '<i class="fa fa-download"></i> <span class="hidden-xs">원문 다운로드</span></a>';
		#$attach_list .= '<span class="en font-11 text-muted"><i class="fa fa-clock-o"></i> '.apms_datetime(strtotime($view['file'][$i]['datetime']), "Y.m.d").'</span></a>'.PHP_EOL;

		/* PDF 문서 바로 보기*/

		/*
		if(strpos($view['file'][$i]['source'], ".pdf") !== false) {
			$attach_list2 .= '<a href="'.G5_URL.'/web_pdf/viewer.html?file=../'. str_replace(G5_URL, "", $view['file'][$i]['path']).'/'.$view['file'][$i]['file'].'" target="_blank" class="thema-bg-color break-word view_file_download at-tip" >';
			$attach_list2 .= '<i class="fa fa-book"></i> 원문 바로 보기</a>';
		}
		*/


		$j++;
	}
}

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css" media="screen">', 0);

?>
<?php if($boset['video']) { ?>
	<style>
	.view-wrap .apms-autowrap { max-width:<?php echo (G5_IS_MOBILE) ? '100%' : $boset['video'];?> !important;}
	</style>
<?php } ?>
<style>
	.tagbox{display: inline-block;}
</style>
<script src="<?php echo G5_JS_URL; ?>/viewimageresize.js"></script>


<div class="view-wrap<?php echo (G5_IS_MOBILE) ? ' view-mobile font-14' : '';?>">

	<div class="print-hide view-btn text-right">
		
		<div class="btn-group">
			<?php if($attach_list) {
				echo $attach_list.PHP_EOL;
			} ?>			
			<?php if ($prev_href) { ?>
				<a href="<?php echo $prev_href ?>" class="btn btn-black btn-sm" title="이전글">
					<i class="fa fa-chevron-circle-left"></i><span class="hidden-xs"> 이전</span>
				</a>
			<?php } ?>
			<?php if ($next_href) { ?>
				<a href="<?php echo $next_href ?>" class="btn btn-black btn-sm" title="다음글">
					<i class="fa fa-chevron-circle-right"></i><span class="hidden-xs"> 다음</span>
				</a>
			<?php } ?>
			<?php if ($copy_href) { ?>
				<a href="<?php echo $copy_href ?>" class="btn btn-black btn-sm" onclick="board_move(this.href); return false;" title="복사">
					<i class="fa fa-clipboard"></i><span class="hidden-xs"> 복사</span>
				</a>
			<?php } ?>
			<?php if ($move_href) { ?>
				<a href="<?php echo $move_href ?>" class="btn btn-black btn-sm" onclick="board_move(this.href); return false;" title="이동">
					<i class="fa fa-share"></i><span class="hidden-xs"> 이동</span>
				</a>
			<?php } ?>
			<?php if ($delete_href) { ?>
				<a href="<?php echo $delete_href ?>" class="btn btn-black btn-sm" title="삭제" onclick="del(this.href); return false;">
					<i class="fa fa-times"></i><span class="hidden-xs"> 삭제</span>
				</a>
			<?php } ?>
			<?php if ($update_href) { ?>
				<a href="<?php echo $update_href ?>" class="btn btn-black btn-sm" title="수정">
					<i class="fa fa-plus"></i><span class="hidden-xs"> 수정</span>
				</a>
			<?php } ?>
			<?php if ($search_href) { ?>
				<a href="<?php echo $search_href ?>" class="btn btn-black btn-sm">
					<i class="fa fa-search"></i><span class="hidden-xs"> 검색</span>
				</a>
			<?php } ?>
			<a href="<?php echo $list_href ?>" class="btn btn-black btn-sm">
				<i class="fa fa-bars"></i><span class="hidden-xs"> 목록</span>
			</a>
			<?php if ($reply_href) { ?>
				<a href="<?php echo $reply_href ?>" class="btn btn-black btn-sm">
					<i class="fa fa-comments"></i><span class="hidden-xs"> 답변</span>
				</a>
			<?php } ?>
			<?php if ($write_href) { ?>
				<a href="<?php echo $write_href ?>" class="btn btn-color btn-sm">
					<i class="fa fa-pencil"></i><span class="hidden-xs"> 글쓰기</span>
				</a>
			<?php } ?>
		</div>
		<div class="clearfix"></div>
	</div>

	<div class="area-wrap">
		<h1 class><?php echo cut_str(get_text($view['wr_subject']), 70); ?></h1>
<!-- SNS공유툴 !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! -->
		<div class="print-hide view-icon"  style="float:right;">
			<span class="pull-right">
				<!-- Go to www.addthis.com/dashboard to customize your tools SNS공유툴 --><div class="addthis_inline_share_toolbox" style="float:left; "></div>
				<img src="<?php echo G5_IMG_URL;?>/sns/print.png" alt="프린트" class="cursor at-tip" onclick="apms_print();" data-original-title="프린트" data-toggle="tooltip" style="width:30px;">
			</span>
			<div class="clearfix"></div>
		</div>
		<div class="clearfix"></div>
		<!-- !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! -->
		<p><span class="title"><span class="dot">· </span>구분</span> <?php echo $view['ca_name'] ?></p>
		<p><span class="title"><span class="dot">· </span>발행번호</span> <?php echo $result['wr_1'] ?></p>
		<p><span class="title"><span class="dot">· </span>ISBN</span> <?php echo $result['wr_2'] ?></p>
		<p><span class="title"><span class="dot">· </span>발행처</span> <?php echo $result['wr_3'] ?></p>
		<p><span class="title"><span class="dot">· </span>연구진</span> <?php echo $result['wr_4'] ?></p>
		<p><span class="title"><span class="dot">· </span>발행연월</span> <?php echo $result['wr_5'] ?></p>
		<p><span class="title"><span class="dot">· </span>주제어</span> <?php echo $tag_list ?></p>
	</div>
	

	<?php
		// 이미지 상단 출력
		$v_img_count = count($view['file']);
		if($v_img_count && $is_img_head) {
			echo '<div class="view-img">'.PHP_EOL;
			for ($i=0; $i<=count($view['file']); $i++) {
				if ($view['file'][$i]['view']) {
					#echo get_view_thumbnail($view['file'][$i]['view']);
				}
			}
			echo '</div>'.PHP_EOL;
		}
	 ?>

	<div class="view-content">
		<h3 class="area-title"><a href="javascript:void(0);" onclick="$('#cont-section1').slideToggle('fast');$(this).toggleClass('on');">초록(Abstract) <span class="arrw"><i class="fa fa-caret-down down"></i><i class="fa fa-caret-up up"></i></span></a></h3>
		<div id="cont-section1" style="display: none;">
			<?php echo nl2br($result['wr_12']) ?>
		</div>

		<h3 class="area-title"><a href="javascript:void(0);" onclick="$('#cont-section2').slideToggle('fast');$(this).toggleClass('on');">목차 <span class="arrw"><i class="fa fa-caret-down down"></i><i class="fa fa-caret-up up"></i></span></a></h3>
		<div id="cont-section2" style="display: none;">
			<?php echo nl2br($result['wr_13']) ?>
		</div>

		<h3 class="area-title"><a href="javascript:void(0);" onclick="$('#cont-section3').slideToggle('fast');$(this).toggleClass('on');">내용 <span class="arrw"><i class="fa fa-caret-down down"></i><i class="fa fa-caret-up up"></i></span></a></h3>
		<div id="cont-section3" style="display: none;">
			<?php echo get_view_thumbnail($view['content']); ?>
		</div>

		<?php
		if ($is_tag) {
			$tags = explode(',', $view['as_tag']);
			$in_tag = "";
			for ($i = 0; $i < count($tags); $i++) {
				if ($i == 0)
					$in_tag .= "'" . trim($tags[$i]) . "'";
				else
					$in_tag .= ", '" . trim($tags[$i]) . "'";
			}

			# get : 동일한 태그의 정책연구 보고서 (주제어) 
			/*$sql = "SELECT group_concat(a.tag) as tags"
				. "		, b.wr_id, b.ca_name, b.wr_subject"
				. "	FROM {$g5['apms_tag_log']} As a"
				. "		Inner Join {$g5['write_prefix']}{$bo_table} As b"
				. "	Where a.bo_table = '{$bo_table}'"
				. "		And a.tag In(".$in_tag.")"
				. "	group by a.wr_id"
				. "	Order by b.wr_datetime Desc"
				. "	Limit 0, 10"
				;*/

			$sql = "
				SELECT 
					a.bo_table, a.wr_id, b.wr_subject, b.ca_name, b.as_tag, group_concat(a.tag) as tags
				FROM {$g5['apms_tag_log']} a

				INNER JOIN {$g5['write_prefix']}{$bo_table} b

				WHERE a.wr_id = b.wr_id AND a.tag in ( {$in_tag} ) AND b.wr_id != {$wr_id}

				GROUP BY a.wr_id 
				ORDER BY b.wr_datetime DESC LIMIT 0,10";

			$result = sql_query($sql);

			if (sql_num_rows($result) > 0) {
				?>
				<h3 class="area-title"><a href="javascript:void(0);" onclick="$('#cont-section4').slideToggle('fast');$(this).toggleClass('on');">관련 주제의 다른 자료 <span class="arrw"><i class="fa fa-caret-down down"></i><i class="fa fa-caret-up up"></i></span></a></h3>
				<div id="cont-section4" style="display: none;">
					<ul class="relative_lst">
						<?php
						while ($row = sql_fetch_array($result)) {
							?>
							<li>
								<a href="/bbs/board.php?bo_table=<?php echo $row['bo_table']?>&amp;wr_id=<?php echo $row['wr_id']?>">
									<span class="tags"><?php echo($row['as_tag'])?></span>
									<span class="tit"><?php echo($row['wr_subject'])?></span>
								</a>
							</li>
							<?php
						}
						?>
					</ul>
				</div>
				<?php
			}
		}
		?>
	</div>

	<?php
		// 이미지 하단 출력
		if($v_img_count && $is_img_tail) {
			echo '<div class="view-img">'.PHP_EOL;
			for ($i=0; $i<=count($view['file']); $i++) {
				if ($view['file'][$i]['view']) {
					#echo get_view_thumbnail($view['file'][$i]['view']);
				}
			}
			echo '</div>'.PHP_EOL;
		}
	?>

	<?php if ($good_href || $nogood_href) { ?>
		<div class="print-hide view-good-box">
			<?php if ($good_href) { ?>
				<span class="view-good">
					<a href="#" onclick="apms_good('<?php echo $bo_table;?>', '<?php echo $wr_id;?>', 'good', 'wr_good'); return false;">
						<b id="wr_good"><?php echo number_format($view['wr_good']) ?></b>
						<br>
						<i class="fa fa-thumbs-up"></i>
					</a>
				</span>
			<?php } ?>
			<?php if ($nogood_href) { ?>
				<span class="view-nogood">
					<a href="#" onclick="apms_good('<?php echo $bo_table;?>', '<?php echo $wr_id;?>', 'nogood', 'wr_nogood'); return false;">
						<b id="wr_nogood"><?php echo number_format($view['wr_nogood']) ?></b>
						<br>
						<i class="fa fa-thumbs-down"></i>
					</a>
				</span>
			<?php } ?>
		</div>
		<p></p>
	<?php } ?>

	<?php if ($is_tag) { // 태그 ?>
		<!--<p class="view-tag font-12"><i class="fa fa-tags"></i> <?php echo $tag_list;?></p>-->
	<?php } ?>

	<div class="print-hide view-icon">
		<div class="pull-right">
			<!-- Go to www.addthis.com/dashboard to customize your tools SNS공유툴 --><div class="addthis_inline_share_toolbox" style="float:left;"></div>
			<div class="form-group">
				<!-- <span class="pull-right"><img src="/img/sns/print.png" alt="프린트" class="cursor at-tip" onclick="apms_print();" data-original-title="프린트" data-toggle="tooltip" style="width:30px;"></span> -->
				<?php if ($scrap_href) { ?>
					<!--<a href="<?php echo $scrap_href;  ?>" target="_blank" class="btn btn-black btn-xs" onclick="win_scrap(this.href); return false;"><i class="fa fa-tags"></i> <span class="hidden-xs">스크랩</span></a>-->
				<?php } ?>
				<?php if ($is_shingo) { ?>
					<button onclick="apms_shingo('<?php echo $bo_table;?>', '<?php echo $wr_id;?>');" class="btn btn-black btn-xs"><i class="fa fa-bell"></i> <span class="hidden-xs">신고</span></button>
				<?php } ?>
				<?php if ($is_admin) { ?>
					<?php if ($view['is_lock']) { // 글이 잠긴상태이면 ?>
						<button onclick="apms_shingo('<?php echo $bo_table;?>', '<?php echo $wr_id;?>', 'unlock');" class="btn btn-black btn-xs"><i class="fa fa-unlock"></i> <span class="hidden-xs">해제</span></button>
					<?php } else { ?>
						<!--<button onclick="apms_shingo('<?php echo $bo_table;?>', '<?php echo $wr_id;?>', 'lock');" class="btn btn-black btn-xs"><i class="fa fa-lock"></i> <span class="hidden-xs">잠금</span></button>-->
					<?php } ?>
				<?php } ?>
			</div>
		</div>
		<div class="pull-left">
			<div class="form-group">
				<?php include_once(G5_SNS_PATH."/view.sns.skin.php"); // SNS ?>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>

	<?php if($is_signature) echo apms_addon('sign-basic'); // 회원서명 ?>

	<!--<h3 class="view-comment">댓글</h3>-->
	<div class="view-comment font-18 en">
		<i class="fa fa-commenting"></i>  댓글
	</div>
	<?php include_once('./view_comment.php'); ?>

	<div class="clearfix"></div>

</div>

<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-58bff1ea9221b257"></script>
<script>
function board_move(href){
	window.open(href, "boardmove", "left=50, top=50, width=500, height=550, scrollbars=1");
}
$(function() {
	$("a.view_image").click(function() {
		window.open(this.href, "large_image", "location=yes,links=no,toolbar=no,top=10,left=10,width=10,height=10,resizable=yes,scrollbars=no,status=no");
		return false;
	});
	<?php if ($board['bo_download_point'] < 0) { ?>
	$("a.view_file_download").click(function() {
		if(!g5_is_member) {
			alert("다운로드 권한이 없습니다.\n회원이시라면 로그인 후 이용해 보십시오.");
			return false;
		}

		var msg = "파일을 다운로드 하시면 포인트가 차감(<?php echo number_format($board['bo_download_point']) ?>점)됩니다.\n\n포인트는 게시물당 한번만 차감되며 다음에 다시 다운로드 하셔도 중복하여 차감하지 않습니다.\n\n그래도 다운로드 하시겠습니까?";

		if(confirm(msg)) {
			var href = $(this).attr("href")+"&js=on";
			$(this).attr("href", href);

			return true;
		} else {
			return false;
		}
	});
	<?php } ?>

	$(document).on('click', '.list-group-item:not(.cutText) .more', function(e){
		$(this).closest('.list-group-item').addClass('cutText').find('.more').text('more');
	})

	$(document).on('click', '.cutText .more', function(e){
		$(this).closest('.list-group-item').removeClass('cutText').find('.more').text('close');
	})


});
</script>
