<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$result = sql_fetch("SELECT * FROM g5_board_extend WHERE bo_table = '{$bo_table}' AND wr_id = '{$wr_id}' LIMIT 0, 1");
#$sholar = sql_fetch($result);

$attach_list = '';
if ($view['link']) {
	// 링크
	for ($i=1; $i<=count($view['link']); $i++) {
		if ($view['link'][$i]) {
			$attach_list .= '<a class="list-group-item break-word" href="'.$view['link_href'][$i].'" target="_blank">';
			$attach_list .= '<span class="label label-warning pull-right view-cnt">'.number_format($view['link_hit'][$i]).'</span>';
			$attach_list .= '<i class="fa fa-link"></i> '.cut_str($view['link'][$i], 70).'</a>'.PHP_EOL;

		}
	}
}

// 가변 파일
$j = 0;
$file_list = "";
for ($i=0; $i<count($view['file']); $i++) {
	if (isset($view['file'][$i]['source']) && $view['file'][$i]['source'] && !$view['file'][$i]['view']) {
		if ($board['bo_download_point'] < 0 && $j == 0) {
			$file_list .= '<a class="list-group-item"><i class="fa fa-bell red"></i> 다운로드시 <b>'.number_format(abs($board['bo_download_point'])).'</b>'.AS_MP.' 차감 (최초 1회 / 재다운로드시 차감없음)</a>'.PHP_EOL;
		}
		$file_tooltip = '';
		if($view['file'][$i]['content']) {
			$file_tooltip = ' data-original-title="'.strip_tags($view['file'][$i]['content']).'" data-toggle="tooltip"';
		}
		$file_list .= '<a class="list-group-item break-word view_file_download at-tip" href="'.$view['file'][$i]['href'].'"'.$file_tooltip.'>';
		//$file_list .= '<span class="label label-primary pull-right view-cnt">'.number_format($view['file'][$i]['download']).'</span>';
		$file_list .= '<i class="fa fa-download"></i>'.$view['file'][$i]['source'].' ('.$view['file'][$i]['size'].') &nbsp;';
		$file_list .= '<span class="en font-11 text-muted"><i class="fa fa-clock-o"></i> '.apms_datetime(strtotime($view['file'][$i]['datetime']), "Y.m.d").'</span></a>'.PHP_EOL;

		/* PDF 문서 바로 보기*/
		/*
			if(strpos($view['file'][$i]['source'], ".pdf") !== false) {
				$attach_list .= '<a href="'.G5_URL.'/web_pdf/viewer.html?file=../'. str_replace(G5_URL, "", $view['file'][$i]['path']).'/'.$view['file'][$i]['file'].'" target="_blank" class="list-group-item break-word view_file_download at-tip" style="font-weight:600;">';
				$attach_list .= '<i class="fa fa-book"></i> PDF 문서 바로 보기</a>';
			}
		*/


		$j++;
	}
}

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css" media="screen">', 0);

#이미지 가져오기
#$v_img_count = count($view['file']);
#$v_img_view =  get_view_thumbnail($view['file'][0]['view']);

$img = apms_wr_thumbnail($bo_table, $view, 600, 337, false, true);

?>
<?php if($boset['video']) { ?>
	<style>
	.view-wrap .apms-autowrap { max-width:<?php echo (G5_IS_MOBILE) ? '100%' : $boset['video'];?> !important;}
	</style>
<?php } ?>
<style>
	.cutText{overflow: hidden;-ms-text-overflow: ellipsis;text-overflow: ellipsis;white-space: nowrap; width: 100% !important; position: relative; word-break: break-all;}
	#wr_12, #wr_13{padding-right: 0%;}
	#wr_12.cutText, #wr_13.cutText{padding-right: 5%;}
	.list-group-item .more{display: none; cursor: pointer; display: inline-block; position: static; color: #FFF; margin-left: 10px; padding: 0 10px; border-radius: 4px; background: #8ec700;}
	.list-group-item.cutText .more{position: absolute; right: 0; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; margin-left: 0;}
</style>
<script src="<?php echo G5_JS_URL; ?>/viewimageresize.js"></script>


<div class="view-wrap<?php echo (G5_IS_MOBILE) ? ' view-mobile font-14' : '';?>">

	<div class="print-hide view-btn text-right">
		<div class="btn-group">
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
		<div class="img-box"><img src="<?php echo $img['src'] ?>" alt="<?php echo $img['alt'] ?>"></div>
		<div class="txt-box">
			<table class="lect-tb">
				<tr>
					<th colspan="2"><h1><?php echo cut_str(get_text($view['wr_subject']), 70); ?></h1></th>
				</tr>
				<tr>
					<td colspan="2"><?php echo $tag_list?></td>
				</tr>
				<tr>
					<th>강의 경력</th>
					<td><?php echo F_hsc($result['wr_1'])?></td>
				</tr>
				<tr>
					<th>강의 지역</th>
					<td><?php echo str_replace(",", ", ", $result['wr_3']) . " " . $result['wr_2']?></td>
				</tr>
			</table>
		</div>
	</div>



	<div class="view-content">
		<?php #echo get_view_thumbnail($view['content']); ?>
		<?php
			if($is_link_video){
				echo "<h3 class=\"area-title\">샘플 강의</h3>";
				echo $autoplay.apms_link_video($view['link'], '', $seometa['img']['src']);
			}

			echo "<h3 class=\"area-title\">강사 소개</h3>";
			echo get_view_thumbnail(strip_tags($view['content'],'<p><span><img><br><a>'));

			if($view['file']['count'] > 0){

				echo "<h3 class=\"area-title\">참조 파일</h3>";
				echo $file_list;

			}
		?>
		<!-- 
			2017.11.27 Hoyeongkim
			p와 span, img, br 태그만 허용합니다. 
		-->

	</div>


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
		<p class="view-tag font-12"><i class="fa fa-tags"></i> <?php echo $tag_list;?></p>
	<?php } ?>

	<div class="print-hide view-icon">
		<div class="pull-right">
			<div class="form-group">
				<span class="pull-right"><img src="/img/sns/print.png" alt="프린트" class="cursor at-tip" onclick="apms_print();" data-original-title="프린트" data-toggle="tooltip"></span>
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

	<!--<h3 class="view-comment">댓글</h3>
	<div class="view-comment font-18 en">
		<i class="fa fa-commenting"></i>  댓글
	</div>
	<?php include_once('./view_comment.php'); ?>

	<div class="clearfix"></div>
</div>
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
