<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
?>

<!-- 게시물 작성/수정 시작 { -->
<form name="fwrite" id="fwrite" action="<?php echo $action_url ?>" onsubmit="return fwrite_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off" role="form" class="form-horizontal">
<input type="hidden" name="uid" value="<?php echo get_uniqid(); ?>">
<input type="hidden" name="w" value="<?php echo $w ?>">
<input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
<input type="hidden" name="wr_id" value="<?php echo $wr_id ?>">
<input type="hidden" name="sca" value="<?php echo $sca ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="spt" value="<?php echo $spt ?>">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="page_form" value="<?php echo $page_form ?>">

<?php
	$option = '';
	$option_hidden = '';
	if ($is_notice || $is_html || $is_secret || $is_mail) {
		if ($is_html) {
			if ($is_dhtml_editor) {
				$option_hidden .= '<input type="hidden" value="html1" name="html">';
			} else {
				$option .= "\n".'<label class="sp-label"><input type="checkbox" id="html" name="html" onclick="html_auto_br(this);" value="'.$html_value.'" '.$html_checked.'> HTML</label>';
				$option_ctn++;
			}
		}
		if ($is_admin) {
			$main_checked = ($write['as_type']) ? ' checked' : '';
			$option .= "\n".'<label class="sp-label"><input type="checkbox" id="as_type" name="as_type" value="1" '.$main_checked.'> 메인글</label>';
			$option_ctn++;
		}
	}

	echo $option_hidden;
?>

	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">Document Info</h4>
		</div>
		<div class="panel-body" style="padding-bottom:0px;">
			<div class="form-group">
				<label class="col-sm-2 control-label" for="ca_name">분류선택<strong class="sound_only">필수</strong></label>
				<div class="col-sm-3">
					<select name="ca_name" id="ca_name" required class="form-control input-sm">
						<option value="">선택하세요</option>
						<option value="요약"<?php echo get_selected($write['ca_name'], '요약');?>>요약문서</option>
						<?php echo $category_option ?>
					</select>
				</div>
				<div class="col-sm-7">
					<p class="form-control-static text-muted">
						요약문서 미등록시 첫번째 분류가 요약문서로 자동 출력
					</p>
				</div>
			</div>

			<?php if ($is_member) { // 임시 저장된 글 기능 ?>
				<script src="<?php echo G5_JS_URL; ?>/autosave.js"></script>
				<?php if($editor_content_js) echo $editor_content_js; ?>
				<div class="modal fade" id="autosaveModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
								<h4 class="modal-title" id="myModalLabel">임시 저장된 글 목록</h4>
							</div>
							<div class="modal-body">
								<div id="autosave_wrapper">
									<div id="autosave_pop">
										<ul></ul>
									</div>
								</div>	
							</div>
						</div>
					</div>
				</div>
			<?php } ?>

			<div class="form-group">
				<label class="col-sm-2 control-label" for="wr_subject">문서제목<strong class="sound_only">필수</strong></label>
				<div class="col-sm-6">
					<div class="input-group input-group-sm">
						<input type="text" name="wr_subject" value="<?php echo $subject ?>" id="wr_subject" required class="form-control input-sm" size="50" maxlength="255">
						<span class="input-group-btn">
							<button type="button" id="btn_autosave" data-toggle="modal" data-target="#autosaveModal" class="btn btn-black btn-sm">저장 (<span id="autosave_count"><?php echo $autosave_count; ?></span>)</button>
						</span>
					</div>
				</div>

				<div class="h10 visible-xs"></div>

				<div class="col-sm-4">
					<div class="input-group input-group-sm">
						<span class="input-group-addon">
							<span id="ticon"><?php echo ($write['as_icon']) ? apms_fa($write['as_icon']) : '아이콘';?></span>
						</span>
						<input type="text" name="as_icon" value="<?php echo $write['as_icon']; ?>" id="picon" class="form-control input-sm" size="50" maxlength="255">
						<span class="input-group-addon cursor" onclick="win_scrap('<?php echo G5_BBS_URL;?>/ficon.php?fid=picon&sid=ticon');" title="FA아이콘">
							선택
						</span>
					</div>
				</div>
			</div>

			<div class="form-group" style="margin-bottom:0px;">
				<label class="col-sm-2 control-label">문서위치</label>
				<div class="col-sm-6">
					<div class="input-group input-group-sm">
						<span class="input-group-addon">/page/</span>
						<input type="text" name="page_file" value="<?php echo $page_file; ?>" id="page_file" class="form-control input-sm" size="50" maxlength="255" placeholder="company.php">
					</div>
				</div>
				<div class="col-sm-4">
					<label class="sp-label">
						<input type="checkbox" name="page_wide" value="1"<?php echo get_checked($page_wide, '1');?>> 와이드
					</label>
					<?php echo $option ?>
				</div>
				<div class="col-sm-10 col-sm-offset-2">
					<p class="help-block">
						문서위치 → 문서파일 → 직접 입력순으로 출력 / 문서위치 또는 파일 등록시 글내용은 검색, SEO 용도의 요약글 입력
					</p>
				</div>
			</div>

			<div class="form-group">
				<div class="col-sm-12">
					<?php if($write_min || $write_max) { ?>
						<!-- 최소/최대 글자 수 사용 시 -->
						<div class="well well-sm" style="margin-bottom:15px;">
							현재 <strong><span id="char_count"></span></strong> 글자이며, 최소 <strong><?php echo $write_min; ?></strong> 글자 이상, 최대 <strong><?php echo $write_max; ?></strong> 글자 이하까지 쓰실 수 있습니다.
						</div>
					<?php } ?>
					<?php echo $editor_html; // 에디터 사용시는 에디터로, 아니면 textarea 로 노출 ?>
				</div>
			</div>

			<div class="form-group" style="margin-bottom:0px;">
				<label class="col-sm-2 control-label">문서꾸밈</label>
				<div class="col-sm-3" style="margin-bottom:10px;">
					<select name="page_skin" id="page_skin" class="form-control input-sm">
						<option value="">스킨 미사용</option>
						<?php
							for ($k=0; $k<count($skinlist); $k++) {
								echo "<option value=\"".$skinlist[$k]."\"".get_selected($page_skin, $skinlist[$k]).">".$skinlist[$k]."</option>\n";
							} 
						?>
					</select>
				</div>
				<div class="col-sm-3" style="margin-bottom:10px;">
					<select name="page_head" id="page_head" class="form-control input-sm">
						<option value="">헤더 미사용</option>
						<?php
							for ($k=0; $k<count($headlist); $k++) {
								echo "<option value=\"".$headlist[$k]."\"".get_selected($page_head, $headlist[$k]).">".$headlist[$k]."</option>\n";
							} 
						?>
					</select>
				</div>
				<div class="col-sm-3" style="margin-bottom:10px;">
					<select name="page_color" id="page_color" class="form-control input-sm">
						<?php echo apms_color_options($page_color);?>
					</select>
				</div>
			</div>

			<?php for ($i=0; $is_file && $i < $file_count; $i++) { if($i > 2) break; ?>
				<div class="form-group">
					<label class="col-sm-2 control-label"><?php echo ($i) ? '문서파일' : '대표사진';?></label>
					<div class="col-sm-10">
						<label class="control-label sp-label">
							<input type="file" name="bf_file[]" title="파일첨부 <?php echo $i+1 ?> : 용량 <?php echo $upload_max_filesize ?> 이하만 업로드 가능">
						</label>
						<?php if($w == 'u' && $file[$i]['file']) { ?>
							<div class="checkbox">
								<label>
									<input type="checkbox" id="bf_file_del<?php echo $i ?>" name="bf_file_del[<?php echo $i;  ?>]" value="1"> <?php echo $file[$i]['source'].'('.$file[$i]['size'].')';  ?> 파일 삭제
								</label>
							</div>
						<?php } ?>
					</div>
				</div>	
			<?php } ?>

			<div class="form-group">
				<label class="col-sm-2 control-label" for="as_tag">문서태그</label>
				<div class="col-sm-10">
					<input type="text" name="as_tag" id="as_tag" value="<?php echo $write['as_tag']; ?>" placeholder="각 태그는 콤마(,)로 구분합니다." class="form-control input-sm" size="50">
				</div>
			</div>

		</div>
	</div>

	<?php if ($is_guest) { //자동등록방지  ?>
		<div class="well well-sm text-center">
			<?php echo $captcha_html; ?>
		</div>
	<?php } ?>

	<div class="write-btn pull-center">
		<button type="submit" id="btn_submit" accesskey="s" class="btn btn-color btn-sm"><i class="fa fa-check"></i> <b>작성완료</b></button>
		<a href="./board.php?bo_table=<?php echo $bo_table ?>" class="btn btn-black btn-sm" role="button">취소</a>
	</div>

	<div class="clearfix"></div>

</form>

<script>
<?php if($write_min || $write_max) { ?>
// 글자수 제한
var char_min = parseInt(<?php echo $write_min; ?>); // 최소
var char_max = parseInt(<?php echo $write_max; ?>); // 최대
check_byte("wr_content", "char_count");

$(function() {
	$("#wr_content").on("keyup", function() {
		check_byte("wr_content", "char_count");
	});
});
<?php } ?>

function apms_change_skin(id, type, skin) {
	$.get("./board.skin.php?bo_table=<?php echo $bo_table;?>&type="+type+"&skin="+skin, function (data) {
		$("#"+id).html(data);
	});
}

function html_auto_br(obj) {
	if (obj.checked) {
		result = confirm("자동 줄바꿈을 하시겠습니까?\n\n자동 줄바꿈은 게시물 내용중 줄바뀐 곳을<br>태그로 변환하는 기능입니다.");
		if (result)
			obj.value = "html2";
		else
			obj.value = "html1";
	}
	else
		obj.value = "";
}

function fwrite_submit(f) {
	<?php echo $editor_js; // 에디터 사용시 자바스크립트에서 내용을 폼필드로 넣어주며 내용이 입력되었는지 검사함   ?>

	var subject = "";
	var content = "";
	$.ajax({
		url: g5_bbs_url+"/ajax.filter.php",
		type: "POST",
		data: {
			"subject": f.wr_subject.value,
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

	if (subject) {
		alert("제목에 금지단어('"+subject+"')가 포함되어있습니다");
		f.wr_subject.focus();
		return false;
	}

	if (content) {
		alert("내용에 금지단어('"+content+"')가 포함되어있습니다");
		if (typeof(ed_wr_content) != "undefined")
			ed_wr_content.returnFalse();
		else
			f.wr_content.focus();
		return false;
	}

	if (document.getElementById("char_count")) {
		if (char_min > 0 || char_max > 0) {
			var cnt = parseInt(check_byte("wr_content", "char_count"));
			if (char_min > 0 && char_min > cnt) {
				alert("내용은 "+char_min+"글자 이상 쓰셔야 합니다.");
				return false;
			}
			else if (char_max > 0 && char_max < cnt) {
				alert("내용은 "+char_max+"글자 이하로 쓰셔야 합니다.");
				return false;
			}
		}
	}

	<?php echo $captcha_js; // 캡챠 사용시 자바스크립트에서 입력된 캡챠를 검사함  ?>

	document.getElementById("btn_submit").disabled = "disabled";

	return true;
}

$(function(){
	$("#wr_content").addClass("form-control input-sm write-content");
});
</script>
