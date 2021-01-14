<?php
# Where 문
$cm_wh = "Where C.C_DDATE is null And C.C_TBNAME = '" . mres($C_TBNAME) . "' And C.C_TBPK = '" . mres($C_TBPK) . "'";


$sql = "Select Count(*) As cnt"
	."	From NX_COMMENT As C"
	."		Left Join g5_member As m On m.mb_id = C.mb_id"
	."	{$cm_wh}"
	;
$db2 = sql_fetch($sql);
$total_count = $db2['cnt'];


# get data
$sql = "Select C.*"
	."		, m.mb_name"
	."	From NX_COMMENT As C"
	."		Left Join g5_member As m On m.mb_id = C.mb_id"
	."	{$cm_wh}"
	."	Order By C.GROUP_IDX Desc, C.C_REPLY Asc";
$db2 = sql_query($sql);


$is_reply = true;


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
<link rel="stylesheet" href="<?php echo(THEMA_URL)?>/colorset/Basic/evt_comment.css" type="text/css">

<div class="view-comment font-18 en">
	<i class="fa fa-commenting"></i> <span class="orangered"><?php echo number_format($total_count);?></span> 댓글
</div>

<script>
// 글자수 제한
var char_min = parseInt(0); // 최소
var char_max = parseInt(0); // 최대
</script>

<?php
if(sql_num_rows($db2) > 0) {
	?>
	<section id="bo_vc" class="comment-media">
		<?php
		$s = 0;
		while ($rs2 = sql_fetch_array($db2)) {
			$C_IDX = $rs2['C_IDX'];
			$cmt_depth = ""; // 댓글단계
			$cmt_depth = strlen($rs2['C_REPLY']) * $depth_gap;
			$comment = $rs2['C_CONTENT'];
			$cmt_sv = $cmt_amt - $i + 1; // 댓글 헤더 z-index 재설정 ie8 이하 사이드뷰 겹침 문제 해결
			/* 주석 - 행사, 대관에서 비밀글 없음
			if(!$is_admin && $member['mb_id'] != $rs2['mb_id'] && $rs2['C_SECRET_YN'] == "Y") {
				$comment = '비밀글';
			}
			*/
		 ?>
			<div class="media" id="c_<?php echo $C_IDX ?>"<?php echo ($cmt_depth) ? ' style="margin-left:'.$cmt_depth.'px;"' : ''; ?>>
				<?php 
					if($is_cmt_photo) { // 회원사진
						$cmt_photo_url = apms_photo_url($rs2['mb_id']);
						$cmt_photo = ($cmt_photo_url) ? '<img src="'.$cmt_photo_url.'" alt="" class="media-object">' : '<div class="media-object"><i class="fa fa-user"></i></div>';
						echo '<div class="photo pull-left">'.$cmt_photo.'</div>'.PHP_EOL;
					 }
				?>
				<div class="media-body">
					<div class="media-heading">
						<b><?php echo $rs2['mb_name'] ?></b>
						<span class="font-11 text-muted">
							<span class="media-info">
								<i class="fa fa-clock-o"></i>
								<?php echo apms_date(strtotime($rs2['C_WDATE']), 'orangered', 'before');?>
							</span>
						</span>
						&nbsp;
						<?php if($is_reply || $is_admin || $member['mb_id'] == $rs2['mb_id']) {

							$query_string = clean_query_string($_SERVER['QUERY_STRING']);

							if($w == 'cu') {
								$sql = " select wr_id, wr_content from $write_table where wr_id = '$c_id' and wr_is_comment = '1' ";
								$cmt = sql_fetch($sql);
								$c_wr_content = $cmt['wr_content'];
							}

						 ?>
							<div class="print-hide pull-right font-11 ">
								<?php if ($is_reply) { ?>
									<a href="javascript:void(0);" onclick="comment_box('', '<?php echo $C_IDX ?>'); return false;">
										<span class="text-muted">답변</span>
									</a>
								<?php } ?>
								<?php if ($is_admin || $member['mb_id'] == $rs2['mb_id']) { ?>
									<a href="javascript:void(0);" onclick="comment_box('<?php echo $C_IDX ?>', ''); return false;">
										<span class="text-muted media-btn">수정</span>
									</a>
								<?php } ?>
								<?php if ($is_admin || $member['mb_id'] == $rs2['mb_id'])  { ?>
									<a href="../comment/delProc.php?url=<?php echo(urlencode($_SERVER['REQUEST_URI']))?>&C_IDX=<?php echo $C_IDX ?>" onclick="return comment_delete();">
										<span class="text-muted media-btn">삭제</span>
									</a>
								<?php } ?>
							</div>
						<?php } ?>
					</div>
					<div class="media-content">
						<?php /* if ($rs2['C_SECRET_YN'] == "Y") { ?>
							<img src="/thema/hub/skin/board/Basic-Board/img/icon_secret.gif" alt="">
						<?php } */ ?>
						<?php echo $comment ?>
						<?php if(!G5_IS_MOBILE) { // PC ?>
							<span id="edit_<?php echo $C_IDX ?>"></span><!-- 수정 -->
							<span id="reply_<?php echo $C_IDX ?>"></span><!-- 답변 -->
							<input type="hidden" value="<?php echo(($rs2['C_SECRET_YN'] == "Y") ? "secret" : "")?>" id="secret_comment_<?php echo $C_IDX ?>">
							<textarea id="save_comment_<?php echo $C_IDX ?>" style="display:none"><?php echo get_text($rs2['C_CONTENT'], 0) ?></textarea>
						<?php } ?>
					</div>
			  </div>
			</div>
			<?php if(G5_IS_MOBILE) { // Mobile ?>
				<span id="edit_<?php echo $C_IDX ?>"></span><!-- 수정 -->
				<span id="reply_<?php echo $C_IDX ?>"></span><!-- 답변 -->
				<input type="hidden" value="<?php echo(($rs2['C_SECRET_YN'] == "Y") ? "secret" : "")?>" id="secret_comment_<?php echo $C_IDX ?>">
				<textarea id="save_comment_<?php echo $C_IDX ?>" style="display:none"><?php echo get_text($rs2['C_CONTENT'], 0) ?></textarea>
			<?php } ?>
			<?php
			$s++;
		}
		?>
	</section>
	<?php
}
?>

<div class="print-hide">
	<?php
	if($is_member) {
		?>
	<aside id="bo_vc_w">
		<form id="fviewcomment" name="fviewcomment" action="../comment/update.php" onsubmit="return fviewcomment_submit(this);" method="post" autocomplete="off" class="form comment-form" role="form">
		<input type="hidden" name="url" value="<?php echo(F_hsc($_SERVER['REQUEST_URI']))?>" />
		<input type="hidden" id="C_IDX" name="C_IDX" value="" />
		<input type="hidden" id="PARENT_IDX" name="PARENT_IDX" value="" />
		<input type="hidden" name="C_TBNAME" value="<?php echo(F_hsc($C_TBNAME))?>" />
		<input type="hidden" name="C_TBPK" value="<?php echo(F_hsc($C_TBPK))?>" />

		<div class="comment-box">
			<div class="pull-left help-block hidden-xs">
				<i class="fa fa-smile-o fa-lg"></i> 댓글은 자신을 나타내는 '얼굴'입니다. *^^*
			</div>
			<?php if ($comment_min || $comment_max) { ?>
				<div class="pull-right help-block" id="char_cnt">
					<i class="fa fa-commenting-o fa-lg"></i>
					현재 <b class="orangered"><span id="char_count">0</span></b>글자
					/
					<?php if($comment_min) { ?>
						<?php echo number_format($comment_min);?>글자 이상
					<?php } ?>
					<?php if($comment_max) { ?>
						<?php echo number_format($comment_max);?>글자 이하
					<?php } ?>
				</div>
			<?php } ?>
			<div class="clearfix"></div>

			<div class="form-group comment-content">
				<div class="comment-cell">
					<textarea tabindex="13" id="wr_content" name="wr_content" maxlength="10000" rows=5 class="form-control input-sm" title="내용"
					<?php if ($comment_min || $comment_max) { ?>onkeyup="check_byte('wr_content', 'char_count');"<?php } ?>><?php echo $c_wr_content;  ?></textarea>
					<?php if ($comment_min || $comment_max) { ?><script> check_byte('wr_content', 'char_count'); </script><?php } ?>
					<script>
					$("textarea#wr_content[maxlength]").live("keyup change", function() {
						var str = $(this).val()
						var mx = parseInt($(this).attr("maxlength"))
						if (str.length > mx) {
							$(this).val(str.substr(0, mx));
							return false;
						}
					});
					</script>
				</div>
				<div tabindex="14" class="comment-cell comment-submit" onclick="apms_comment_submit();" onKeyDown="apms_comment_onKeyDown();" id="btn_submit">
					등록
				</div>
			</div>

			<div class="comment-btn">
				<div class="form-group pull-right">
					<?php 
					/*
					<span class="cursor">
						<label class="checkbox-inline"><input type="checkbox" name="wr_secret" value="secret" id="wr_secret"> 비밀글</label>
					</span>
					*/ 
					?>
					<?php
					/*
					<span class="cursor" title="이모티콘" onclick="apms_emoticon();">
						<i class="fa fa-smile-o fa-lg"></i><span class="sound_only">이모티콘</span>
					</span>
					*/
					?>
					<span class="cursor" title="새댓글" onclick="comment_box('','');">
						<i class="fa fa-pencil fa-lg"></i><span class="sound_only">새댓글 작성</span>
					</span>
					<?php
					/*
					<span class="cursor" title="새로고침" onclick="apms_page('viewcomment','<?php echo $comment_url;?>');">
						<i class="fa fa-refresh fa-lg"></i><span class="sound_only">댓글 새로고침</span>
					</span>
					*/
					?>
					<span class="cursor" title="늘이기" onclick="apms_textarea('wr_content','down');">
						<i class="fa fa-plus-circle fa-lg"></i><span class="sound_only">입력창 늘이기</span>
					</span>
					<span class="cursor" title="줄이기" onclick="apms_textarea('wr_content','up');">
						<i class="fa fa-minus-circle fa-lg"></i><span class="sound_only">입력창 줄이기</span>
					</span>
				</div>	
				<div class="clearfix"></div>
			</div>
		</div>

		</form>
	</aside>
		<?php
	} else {
		?>
	<div class="h10"></div>
	<div class="well text-center">
		<?php /* <a href="<?php echo $comment_login_url;?>">로그인한 회원만 댓글 등록이 가능합니다.</a> */ ?>
		<a onclick="win_open();">로그인한 회원만 댓글 등록이 가능합니다.</a>
	</div>
		<?php
	}
	?>
</div>


<?php
if ($is_member) {
	?>
	<script>
	var save_before = '';
	var save_html = document.getElementById('bo_vc_w').innerHTML;

	function fviewcomment_submit(f)
	{
		var pattern = /(^\s*)|(\s*$)/g; // \s 공백 문자

		var subject = "";
		var content = "";
		$.ajax({
			url: "../comment/ajax.filter.php",
			type: "POST",
			data: {
				"subject": "",
				"content": f.wr_content.value
			},
			dataType: "json",
			async: false,
			cache: false,
			success: function(data, textStatus) {
				subject = data.subject;
				content = data.content;
			}
		});

		if (content) {
			alert("내용에 금지단어('"+content+"')가 포함되어있습니다");
			f.wr_content.focus();
			return false;
		}

		// 양쪽 공백 없애기
		var pattern = /(^\s*)|(\s*$)/g; // \s 공백 문자
		document.getElementById('wr_content').value = document.getElementById('wr_content').value.replace(pattern, "");
		if (char_min > 0 || char_max > 0)
		{
			check_byte('wr_content', 'char_count');
			var cnt = parseInt(document.getElementById('char_count').innerHTML);
			if (char_min > 0 && char_min > cnt)
			{
				alert("댓글은 "+char_min+"글자 이상 쓰셔야 합니다.");
				return false;
			} else if (char_max > 0 && char_max < cnt)
			{
				alert("댓글은 "+char_max+"글자 이하로 쓰셔야 합니다.");
				return false;
			}
		}
		else if (!document.getElementById('wr_content').value)
		{
			alert("댓글을 입력하여 주십시오.");
			f.wr_content.focus();
			return false;
		}

		if (typeof(f.wr_name) != 'undefined')
		{
			f.wr_name.value = f.wr_name.value.replace(pattern, "");
			if (f.wr_name.value == '')
			{
				alert('이름이 입력되지 않았습니다.');
				f.wr_name.focus();
				return false;
			}
		}

		if (typeof(f.wr_password) != 'undefined')
		{
			f.wr_password.value = f.wr_password.value.replace(pattern, "");
			if (f.wr_password.value == '')
			{
				alert('비밀번호가 입력되지 않았습니다.');
				f.wr_password.focus();
				return false;
			}
		}

		set_comment_token(f);

		document.getElementById("btn_submit").disabled = "disabled";

		return true;
	}

	function comment_box(C_IDX, PARENT_IDX)
	{
		var comment_id;
		var el_id;
		// 댓글 아이디가 넘어오면 답변, 수정
		if (C_IDX != '' || PARENT_IDX != '')
		{
			if (C_IDX != '')
			{
				comment_id = C_IDX;
				el_id = 'edit_' + C_IDX;
			}
			else if (PARENT_IDX != '')
			{
				comment_id = PARENT_IDX;
				el_id = 'reply_' + PARENT_IDX;
			}
		}
		else
			el_id = 'bo_vc_w';

		if (save_before != el_id)
		{
			if (save_before)
			{
				document.getElementById(save_before).style.display = 'none';
				document.getElementById(save_before).innerHTML = '';
			}

			document.getElementById(el_id).style.display = '';
			document.getElementById(el_id).innerHTML = save_html;
			// 댓글 수정
			if (C_IDX != '')
			{
				document.getElementById('wr_content').value = document.getElementById('save_comment_' + comment_id).value;
				if (typeof char_count != 'undefined')
					check_byte('wr_content', 'char_count');
				if (document.getElementById('secret_comment_'+comment_id).value)
					document.getElementById('wr_secret').checked = true;
				else
					document.getElementById('wr_secret').checked = false;
			}

			document.getElementById('C_IDX').value = C_IDX;
			document.getElementById('PARENT_IDX').value = PARENT_IDX;

			if(save_before)
				$("#captcha_reload").trigger("click");

			save_before = el_id;
		}
	}

	function comment_delete(){
		return confirm("이 댓글을 삭제하시겠습니까?");
	}

	comment_box('', ''); // 댓글 입력폼이 보이도록 처리하기위해서 추가 (root님)

	// 댓글등록
	function apms_comment_submit() {
		var f = document.getElementById("fviewcomment");
		if (fviewcomment_submit(f)) {
			$("#fviewcomment").submit();
		}
		return false;
	}

	function apms_comment_onKeyDown() {
		  if(event.keyCode == 13) {
			apms_comment_submit();
		 }
	}
	</script>
	<?php
}
?>
