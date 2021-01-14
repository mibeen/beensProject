<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once(G5_PLUGIN_PATH.'/nx/common.php');

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css" media="screen">', 0);

$result = sql_fetch("SELECT * FROM g5_board_extend WHERE bo_table = '{$bo_table}' AND wr_id = '{$wr_id}' LIMIT 0, 1");

for($m=1;$m<16;$m++){
    $vvar = "sh_".$m;
    $$vvar = $result['wr_'.$m];
}


/* (사용안함) 데이터 업데이트
$sql2 = "UPDATE g5_write_area 
    	Inner Join g5_board_extend
    		On g5_board_extend.wr_id = g5_write_area.wr_id
		SET
        g5_write_area.wr_2 = g5_board_extend.wr_1,
        g5_write_area.wr_3 = g5_board_extend.wr_2
      WHERE g5_board_extend.bo_table = 'area'
";

$result2 = sql_query($sql2, true);
*/


?>

<?php if($is_dhtml_editor) { ?>
	<style>
		#wr_content { border:0; display:none; }
		.input-group{width: 100%;}
	</style>
<?php } ?>
<style>
	input[type=checkbox]{margin: 0;}
	input[type=checkbox] + label{font-weight: normal; margin-right: 5px;}
</style>

<div id="bo_w" class="write-wrap<?php echo (G5_IS_MOBILE) ? ' font-14' : '';?>">
    <div class="well">
		<h2><?php echo $g5['title'] ?></h2>
	</div>

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
    <input type="hidden" name="g5_extend" value="1">
    <?php
		$option_cnt = 0;
		$option = '';
		$option_hidden = '';
		if ($is_notice || $is_html || $is_secret || $is_mail) {
			$option = '';
			if ($is_notice) {
				$option .= "\n".'<label class="control-label sp-label"><input type="checkbox" id="notice" name="notice" value="1" '.$notice_checked.'> 공지</label>';
				$option_ctn++;
			}

			if ($is_html) {
				if ($is_dhtml_editor) {
					$option_hidden .= '<input type="hidden" value="html1" name="html">';
				} else {
					$option .= "\n".'<label class="control-label sp-label"><input type="checkbox" id="html" name="html" onclick="html_auto_br(this);" value="'.$html_value.'" '.$html_checked.'> HTML</label>';
					$option_ctn++;
				}
			}

			if ($is_secret) {
				if ($is_admin || $is_secret==1) {
					$option .= "\n".'<label class="control-label sp-label"><input type="checkbox" id="secret" name="secret" value="secret" '.$secret_checked.'> 비밀글</label>';
					$option_ctn++;
				} else {
					$option_hidden .= '<input type="hidden" name="secret" value="secret">';
				}
			}

			if ($is_admin) {
				$main_checked = ($write['as_type']) ? ' checked' : '';
				$option .= "\n".'<label class="control-label sp-label"><input type="checkbox" id="as_type" name="as_type" value="1" '.$main_checked.'> 메인글</label>';
				$option_ctn++;
			}

			if ($is_mail) {
				$option .= "\n".'<label class="control-label sp-label"><input type="checkbox" id="mail" name="mail" value="mail" '.$recv_email_checked.'> 답변메일받기</label>';
				$option_ctn++;
			}
		}

		echo $option_hidden;
    ?>

	<?php if ($is_name) { ?>
		<div class="form-group has-feedback">
			<label class="col-sm-2 control-label" for="wr_name">이름<strong class="sound_only">필수</strong></label>
			<div class="col-sm-3">
				<input type="text" name="wr_name" value="<?php echo $name ?>" id="wr_name" required class="form-control input-sm" size="10" maxlength="20">
				<span class="fa fa-check form-control-feedback"></span>
			</div>
		</div>
	<?php } ?>

	<?php if ($is_password) { ?>
		<div class="form-group has-feedback">
			<label class="col-sm-2 control-label" for="wr_password">비밀번호<strong class="sound_only">필수</strong></label>
			<div class="col-sm-3">
				<input type="password" name="wr_password" id="wr_password" <?php echo $password_required ?> class="form-control input-sm" maxlength="20">
				<span class="fa fa-check form-control-feedback"></span>
			</div>
		</div>
	<?php } ?>

	<?php if ($is_email) { ?>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="wr_email">E-mail</label>
			<div class="col-sm-6">
				<input type="text" name="wr_email" id="wr_email" value="<?php echo $email ?>" class="form-control input-sm email" size="50" maxlength="100">
			</div>
		</div>
	<?php } ?>

	<?php if ($is_homepage) { ?>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="wr_homepage">홈페이지</label>
			<div class="col-sm-6">
				<input type="text" name="wr_homepage" id="wr_homepage" value="<?php echo $homepage ?>" class="form-control input-sm" size="50">
			</div>
		</div>
	<?php } ?>

	<?php if ($is_category) { ?>
		<div class="form-group">
			<label class="col-sm-2 control-label hidden-xs" for="ca_name">분류<strong class="sound_only">필수</strong></label>
			<div class="col-sm-3">
				<select name="ca_name" id="ca_name" required class="form-control input-sm">
                    <option value="">선택하세요</option>
	                <?php echo $category_option ?>
		        </select>
			</div>
		</div>
	<?php } ?>
	<?php if ($option) { ?>
		<!--<div class="form-group">
			<label class="col-sm-2 control-label hidden-xs">옵션</label>
			<div class="col-sm-10">
				<?php echo $option ?>
			</div>
		</div>-->
	<?php } ?>
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
		<label class="col-sm-2 control-label" for="wr_subject">장소명<strong class="sound_only">필수</strong></label>
		<div class="col-sm-10">
			<div class="input-group">
				<input type="text" name="wr_subject" value="<?php echo $subject ?>" id="wr_subject" required class="form-control input-sm" size="50" maxlength="255">
				<span class="input-group-btn">
					<?php /* <a href="<?php echo G5_BBS_URL;?>/helper.php" target="_blank" class="btn btn-black btn-sm hidden-xs win_scrap">안내</a> */ ?>
					<?php /* <a href="<?php echo G5_BBS_URL;?>/helper.php?act=map" target="_blank" class="btn btn-black btn-sm hidden-xs win_scrap">지도</a> */ ?>
					<?php if ($is_member) { // 임시 저장된 글 기능 ?>
						<button type="button" id="btn_autosave" data-toggle="modal" data-target="#autosaveModal" class="btn btn-black btn-sm">저장 (<span id="autosave_count"><?php echo $autosave_count; ?></span>)</button>
					<?php } ?>
				</span>
			</div>
		</div>
	</div>

	<?php if($is_admin){ ?>
	<div class="form-group">
		<label class="col-sm-2 control-label" for="wr_subject">날짜</label>
		<div class="col-sm-10">
			<div class="input-group">
				<input type="text" name="wr_datetime" value="<?php echo $write['wr_datetime'] ?>" id="wr_writedate" class="form-control input-sm" size="50" maxlength="255" placeholder="<?php echo date('Y-m-d H:i:s');?>">
			</div>
		</div>
	</div>
	<?php } ?>

	<div class="form-group">
		<label class="col-sm-2 control-label" for="sh_1">문의 전화<strong class="sound_only">필수</strong></label>
		<div class="col-sm-10">
			<div class="input-group">
				<input type="text" name="sh_1" value="<?php echo $sh_1 ?>" id="sh_1" required class="form-control input-sm" size="50" maxlength="255">
				<input type="hidden" name="wr_2" value="<?php echo $wr_2 ?>" id="wr_2" maxlength="255">
			</div>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label" for="sh_2">학습관 위치<strong class="sound_only">필수</strong></label>
		<div class="col-sm-8">
			<div class="input-group">
				<input type="text" name="sh_2" value="<?php echo $sh_2 ?>" id="sh_2" required class="form-control input-sm" size="50" maxlength="255">
				<input type="hidden" name="wr_3" value="<?php echo $wr_3 ?>" id="wr_3" maxlength="255">
			</div>
		</div>
		<button type="button" class="btn btn-default btn-xs" id="map_search">찾기</button>
		<div id="map" class="col-md-12"></div>
	</div>


	<div class="form-group" style="display: none;">
		<label class="col-sm-2 control-label" for="sh_3">지역<strong class="sound_only">필수</strong></label>
		<div class="col-sm-10">
			<div class="input-group">
				<!--<input type="text" name="sh_3" value="<?php echo $sh_3 ?>" id="sh_3" required class="form-control input-sm" size="50" maxlength="255">-->
				<?php
					$place = $sh_3;
					$place_split = explode(',',$place);


					#echo sizeof($place_split);
					function confirm_ck($str, $arr){
						if (in_array($str, $arr)) {
						    echo "checked";
						}

					}

				?>

				<!--<input type="checkbox" name="sh_3" value="제주" id="pl_11" <?php confirm_ck('제주',$place_split) ?>><label for="pl_11">제주</label>-->

        <select name="sh_3" id="pl_11">
          <option value="가평군" name="sh_3" <?php echo $place == '가평군' ? 'selected':''; ?>>가평군</option>
          <option value="고양시" name="sh_3" <?php echo $place == '고양시' ? 'selected':''; ?>>고양시</option>
          <option value="과천시" name="sh_3" <?php echo $place == '과천시' ? 'selected':''; ?>>과천시</option>
          <option value="광명시" name="sh_3" <?php echo $place == '광명시' ? 'selected':''; ?>>광명시</option>
          <option value="광주시" name="sh_3" <?php echo $place == '광주시' ? 'selected':''; ?>>광주시</option>
          <option value="구리시" name="sh_3" <?php echo $place == '구리시' ? 'selected':''; ?>>구리시</option>
          <option value="군포시" name="sh_3" <?php echo $place == '군포시' ? 'selected':''; ?>>군포시</option>
          <option value="김포시" name="sh_3" <?php echo $place == '김포시' ? 'selected':''; ?>>김포시</option>
          <option value="남양주시" name="sh_3" <?php echo $place == '남양주시' ? 'selected':''; ?>>남양주시</option>
          <option value="동두천시" name="sh_3" <?php echo $place == '동두천시' ? 'selected':''; ?>>동두천시</option>
          <option value="부천시" name="sh_3" <?php echo $place == '부천시' ? 'selected':''; ?>>부천시</option>
          <option value="성남시" name="sh_3" <?php echo $place == '성남시' ? 'selected':''; ?>>성남시</option>
          <option value="수원시" name="sh_3" <?php echo $place == '수원시' ? 'selected':''; ?>>수원시</option>
          <option value="시흥시" name="sh_3" <?php echo $place == '시흥시' ? 'selected':''; ?>>시흥시</option>
          <option value="안산시" name="sh_3" <?php echo $place == '안산시' ? 'selected':''; ?>>안산시</option>
          <option value="안성시" name="sh_3" <?php echo $place == '안성시' ? 'selected':''; ?>>안성시</option>
          <option value="안양시" name="sh_3" <?php echo $place == '안양시' ? 'selected':''; ?>>안양시</option>
          <option value="양주시" name="sh_3" <?php echo $place == '양주시' ? 'selected':''; ?>>양주시</option>
          <option value="양평군" name="sh_3" <?php echo $place == '양평군' ? 'selected':''; ?>>양평군</option>
          <option value="여주시" name="sh_3" <?php echo $place == '여주시' ? 'selected':''; ?>>여주시</option>
          <option value="연천군" name="sh_3" <?php echo $place == '연천군' ? 'selected':''; ?>>연천군</option>
          <option value="오산시" name="sh_3" <?php echo $place == '오산시' ? 'selected':''; ?>>오산시</option>
          <option value="용인시" name="sh_3" <?php echo $place == '용인시' ? 'selected':''; ?>>용인시</option>
          <option value="의왕시" name="sh_3" <?php echo $place == '의왕시' ? 'selected':''; ?>>의왕시</option>
          <option value="의정부시" name="sh_3" <?php echo $place == '의정부시' ? 'selected':''; ?>>의정부시</option>
          <option value="이천시" name="sh_3" <?php echo $place == '이천시' ? 'selected':''; ?>>이천시</option>
          <option value="파주시" name="sh_3" <?php echo $place == '파주시' ? 'selected':''; ?>>파주시</option>
          <option value="평택시" name="sh_3" <?php echo $place == '평택시' ? 'selected':''; ?>>평택시</option>
          <option value="포천시" name="sh_3" <?php echo $place == '포천시' ? 'selected':''; ?>>포천시</option>
          <option value="하남시" name="sh_3" <?php echo $place == '하남시' ? 'selected':''; ?>>하남시</option>
          <option value="화성시" name="sh_3" <?php echo $place == '화성시' ? 'selected':''; ?>>화성시</option>
        </select>
			</div>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label">장소 소개</label>
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

	<div class="form-group">
		<label class="col-sm-2 control-label">장소 이용정보</label>
		<div class="col-sm-12">
			<?php if($write_min || $write_max) { ?>
				<!-- 최소/최대 글자 수 사용 시 -->
				<div class="well well-sm" style="margin-bottom:15px;">
					현재 <strong><span id="char_count"></span></strong> 글자이며, 최소 <strong><?php echo $write_min; ?></strong> 글자 이상, 최대 <strong><?php echo $write_max; ?></strong> 글자 이하까지 쓰실 수 있습니다.
				</div>
			<?php } ?>
			<?php echo editor_html('wr_1', $write['wr_1'], $is_dhtml_editor) ?>

		</div>
	</div>
	<?php if ($is_file) { ?>
		<style>
		#variableFiles { width:100%; margin:0; border:0; }
		#variableFiles td { padding:0px 0px 7px; border:0; }
		#variableFiles input[type=file] { box-shadow : none; border: 1px solid #ccc !important; outline:none; }
		#variableFiles .form-group { margin-left:0; margin-right:0; margin-bottom:7px; }
		#variableFiles .checkbox-inline { padding-top:0px; font-weight:normal; }
		</style>
		<div class="form-group">
			<label class="col-sm-2 control-label">장소 이미지</label>
			<div class="col-sm-10">
				<button class="btn btn-sm btn-color" type="button" onclick="add_file();"><i class="fa fa-plus-circle fa-lg"></i> 추가하기</button>
				<button class="btn btn-sm btn-black" type="button" onclick="del_file();"><i class="fa fa-times-circle fa-lg"></i> 삭제하기</button>
			</div>
		</div>
		<div class="form-group" style="margin-bottom:0;">
			<div class="col-sm-10 col-sm-offset-2">
				<table id="variableFiles"></table>
			</div>
		</div>
		<script>
		var flen = 0;
		function add_file(delete_code) {
			var upload_count = <?php echo (int)$board['bo_upload_count']; ?>;
			if (upload_count && flen >= upload_count) {
				alert("이 게시판은 "+upload_count+"개 까지만 파일 업로드가 가능합니다.");
				return;
			}

			var objTbl;
			var objNum;
			var objRow;
			var objCell;
			var objContent;
			if (document.getElementById)
				objTbl = document.getElementById("variableFiles");
			else
				objTbl = document.all["variableFiles"];

			objNum = objTbl.rows.length;
			objRow = objTbl.insertRow(objNum);
			objCell = objRow.insertCell(0);

			objContent = "<div class='row'>";
			objContent += "<div class='col-sm-7'><div class='form-group'><div class='input-group input-group-sm'><span class='input-group-addon'>파일 "+objNum+"</span><input type='file' class='form-control input-sm' name='bf_file[]' title='파일 용량 <?php echo $upload_max_filesize; ?> 이하만 업로드 가능'></div></div></div>";
			if (delete_code) {
				objContent += delete_code;
		    } else {
				<?php if ($is_file_content) { ?>
				objContent += "<div class='col-sm-5'><div class='form-group'><input type='text'name='bf_content[]' class='form-control input-sm' placeholder='이미지에 대한 내용을 입력하세요.'></div></div>";
				<?php } ?>
				;
			}
			objContent += "</div>";

			objCell.innerHTML = objContent;

			flen++;
		}

		<?php echo $file_script; //수정시에 필요한 스크립트?>

		function del_file() {
			// file_length 이하로는 필드가 삭제되지 않아야 합니다.
			var file_length = <?php echo (int)$file_length; ?>;
			var objTbl = document.getElementById("variableFiles");
			if (objTbl.rows.length - 1 > file_length) {
				objTbl.deleteRow(objTbl.rows.length - 1);
				flen--;
			}
		}
		</script>
		
		<?php /* ?>
		<div class="form-group">
			<label class="col-sm-2 control-label">첨부사진</label>
			<div class="col-sm-10">
				<label class="control-label sp-label">
					<input type="radio" name="as_img" value="0"<?php if(!$write['as_img']) echo ' checked';?>> 상단출력
				</label>
				<label class="control-label sp-label">
					<input type="radio" name="as_img" value="1"<?php if($write['as_img'] == "1") echo ' checked';?>> 하단출력
				</label>
				<label class="control-label sp-label">
					<input type="radio" name="as_img" value="2"<?php if($write['as_img'] == "2") echo ' checked';?>> 본문삽입
				</label>
				<div class="text-muted font-12" style="margin-top:4px;">
					본문삽입시 {이미지:0}, {이미지:1} 과 같이 첨부번호를 입력하면 내용에 첨부사진 출력 가능
				</div>
			</div>
		</div>
	<?php */ ?>

	<?php } ?>

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
        <?php echo get_editor_js("wr_1"); ?>
        <?php echo chk_editor_js("wr_1"); ?>

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

	$(document).ready(function() {
		$('#sh_1').on('keyup', function() {
			$('#wr_2').val($(this).val());
		});

		$('#sh_2').on('keyup', function() {
			$('#wr_3').val($(this).val());
		});
	});
    </script>
    <script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=<?php echo(NX__DAUM_MAP_TOKEN)?>&libraries=services"></script>
    <script>
    var map_button = document.getElementById('map_search');
	var mapContainer = document.getElementById('map'), // 지도를 표시할 div
	    mapOption = {
	        center: new daum.maps.LatLng(33.450701, 126.570667), // 지도의 중심좌표
	        draggable: false,
	        level: 3 // 지도의 확대 레벨
	    };

	mapContainer.style.height = '400px';
	mapContainer.style.marginTop = '20px';

	// 지도를 생성합니다
	var map = new daum.maps.Map(mapContainer, mapOption);

	// 일반 지도와 스카이뷰로 지도 타입을 전환할 수 있는 지도타입 컨트롤을 생성합니다
	var mapTypeControl = new daum.maps.MapTypeControl();

	// 지도에 컨트롤을 추가해야 지도위에 표시됩니다
	// daum.maps.ControlPosition은 컨트롤이 표시될 위치를 정의하는데 TOPRIGHT는 오른쪽 위를 의미합니다
	map.addControl(mapTypeControl, daum.maps.ControlPosition.TOPRIGHT);

	// 지도 확대 축소를 제어할 수 있는  줌 컨트롤을 생성합니다
	var zoomControl = new daum.maps.ZoomControl();
	map.addControl(zoomControl, daum.maps.ControlPosition.RIGHT);

	// 주소-좌표 변환 객체를 생성합니다
	var geocoder = new daum.maps.services.Geocoder();

	map_button.onclick = function(e){

    	var map_value  = document.getElementById('sh_2').value;
		//console.log(map_value);
		var map_title = document.getElementById('wr_subject').value;
		if(map_title.length < 1){
			map_title = '학습장소';
		}

	// 주소로 좌표를 검색합니다
		geocoder.addressSearch(map_value, function(result, status) {

		    // 정상적으로 검색이 완료됐으면
		     if (status === daum.maps.services.Status.OK) {

		        var coords = new daum.maps.LatLng(result[0].y, result[0].x);

		        // 결과값으로 받은 위치를 마커로 표시합니다
		        var marker = new daum.maps.Marker({
		            map: map,
		            position: coords
		        });

		        // 인포윈도우로 장소에 대한 설명을 표시합니다
		        var infowindow = new daum.maps.InfoWindow({
		            content: '<div style="width:150px;text-align:center;padding:6px 0;">' + map_title + '</div>'
		        });
		        infowindow.open(map, marker);

		        // 지도의 중심을 결과값으로 받은 위치로 이동시킵니다
		        map.setCenter(coords);
		    }else{
		    	alert('해당 주소를 찾지 못했습니다.');
		    	console.log(result);
		    	return false;
		    }
		});

	}
	</script>
</div>
<!-- } 게시물 작성/수정 끝 -->
