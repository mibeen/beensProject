<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$write_skin_url.'/write.css" media="screen">', 0);

//날짜선택기
apms_script('datepicker');

if(!$header_skin) { 
?>
<div class="well">
	<h2><?php echo $g5['title'] ?></h2>
</div>
<?php } ?>

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
<?php
	$option = '';
	$option_hidden = '';
	if ($is_notice || $is_html || $is_secret || $is_mail) {
		if ($is_notice) {
			$option .= "\n".'<label class="control-label sp-label"><input type="checkbox" id="notice" name="notice" value="1" '.$notice_checked.'> 공지</label>';
		}

		if ($is_html) {
			if ($is_dhtml_editor) {
				$option_hidden .= '<input type="hidden" value="html1" name="html">';
			} else {
				$option .= "\n".'<label class="control-label sp-label"><input type="checkbox" id="html" name="html" onclick="html_auto_br(this);" value="'.$html_value.'" '.$html_checked.'> HTML</label>';
			}
		}

		if ($is_secret) {
			if ($is_admin || $is_secret==1) {
				$option .= "\n".'<label class="control-label sp-label"><input type="checkbox" id="secret" name="secret" value="secret" '.$secret_checked.'> 비밀글</label>';
			} else {
				$option_hidden .= '<input type="hidden" name="secret" value="secret">';
			}
		}

		if ($is_notice) {
			$main_checked = ($write['as_type']) ? ' checked' : '';
			$option .= "\n".'<label class="control-label sp-label"><input type="checkbox" id="as_type" name="as_type" value="1" '.$main_checked.'> 메인글</label>';
		}

		if ($is_mail) {
			$option .= "\n".'<label class="control-label sp-label"><input type="checkbox" id="mail" name="mail" value="mail" '.$recv_email_checked.'> 답변메일받기</label>';
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

<?php
/*
<div class="form-group">
	<label class="col-sm-2 control-label hidden-xs">포토</label>
	<div class="col-sm-10">
		<input type="hidden" name="as_icon" value="<?php echo $write['as_icon'];?>" id="picon">
		<?php
			$fa_photo = (isset($boset['ficon']) && $boset['ficon']) ? apms_fa($boset['ficon']) : '<i class="fa fa-user"></i>';		
			$myicon = ($w == 'u') ? apms_photo_url($write['mb_id']) : apms_photo_url($member['mb_id']);
			$myicon = ($myicon) ? '<img src="'.$myicon.'">' : $fa_photo;
			if($write['as_icon']) {
				$as_icon = apms_fa(apms_emo($write['as_icon']));
				$as_icon = ($as_icon) ? $as_icon : $myicon;
			} else {
				$as_icon = $myicon;
			}
		?>
		<style>
			.write-wrap .talker-photo i { 
				<?php echo (isset($boset['ibg']) && $boset['ibg']) ? 'background:'.apms_color($boset['icolor']).'; color:#fff' : 'color:'.apms_color($boset['icolor']);?>; 
			}
		</style>
		<span id="ticon" class="talker-photo"><?php echo $as_icon;?></span>
		&nbsp;
		<div class="btn-group" data-toggle="buttons">
			<label class="btn btn-default" onclick="apms_emoticon('picon', 'ticon');" title="이모티콘">
				<input type="radio" name="select_icon" id="select_icon1">
				<i class="fa fa-smile-o fa-lg"></i><span class="sound_only">이모티콘</span>
			</label>
			<label class="btn btn-default" onclick="win_scrap('<?php echo G5_BBS_URL;?>/ficon.php?fid=picon&sid=ticon');" title="FA아이콘">
				<input type="radio" name="select_icon" id="select_icon2">
				<i class="fa fa-info-circle fa-lg"></i><span class="sound_only">FA아이콘</span>
			</label>
			<label class="btn btn-default" onclick="apms_myicon();" title="내사진">
				<input type="radio" name="select_icon" id="select_icon3">
				<i class="fa fa-user fa-lg"></i><span class="sound_only">내사진</span>
			</label>
		</div>
	</div>
</div>
*/
?>

<?php if ($is_category || $option) { ?>
	<div class="form-group">
		<label class="col-sm-2 control-label hidden-xs"><?php echo ($is_category) ? '분류' : '옵션';?></label>
		<?php if ($is_category) { ?>
			<div class="col-sm-3">
				<select name="ca_name" id="ca_name" required class="form-control input-sm">
					<option value="">선택하세요</option>
					<?php echo $category_option ?>
				</select>
			</div>
		<?php } ?>
		<?php if ($option) { ?>
			<div class="col-sm-7">
				<div class="h10 visible-xs"></div>
				<?php echo $option ?>
			</div>
		<?php } ?>
	</div>
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
	<label class="col-sm-2 control-label" for="wr_subject">교육신청 제목<strong class="sound_only">필수</strong></label>
	<div class="col-sm-10">
		<div class="input-group">
			<input type="text" name="wr_subject" value="<?php echo $subject ?>" id="wr_subject" required class="form-control input-sm" size="50" maxlength="255">
			<span class="input-group-btn" role="group">
				<?php /* <a href="<?php echo G5_BBS_URL;?>/helper.php" target="_blank" class="btn btn-<?php echo $btn1;?> btn-sm hidden-xs win_scrap">안내</a> */ ?>
				<?php /* <a href="<?php echo G5_BBS_URL;?>/helper.php?act=map" target="_blank" class="btn btn-<?php echo $btn1;?> btn-sm hidden-xs win_scrap">지도</a> */ ?>
				<?php if ($is_member) { // 임시 저장된 글 기능 ?>
					<button type="button" id="btn_autosave" data-toggle="modal" data-target="#autosaveModal" class="btn btn-<?php echo $btn1;?> btn-sm">저장 (<span id="autosave_count"><?php echo $autosave_count; ?></span>)</button>
				<?php } ?>
			</span>
		</div>
	</div>
</div>

<?php if($is_admin){ ?>
<div class="form-group">
	<label class="col-sm-2 control-label" for="wr_datetime">날짜</label>
	<div class="col-sm-10">
		<div class="input-group">
			<input type="text" name="wr_datetime" value="<?php echo $write['wr_datetime'] ?>" id="wr_writedate" class="form-control input-sm" size="50" maxlength="255" placeholder="<?php echo date('Y-m-d H:i:s');?>">
		</div>
	</div>
</div>
<?php } ?>

<div class="form-group">
	<label class="col-sm-2 control-label" for="wr_1">신청자 성명(기관명)</label>
	<div class="col-sm-10">
		<div class="input-group">
			<input type="text" name="wr_1" value="<?php echo $write['wr_1'] ?>" id="wr_1" required class="form-control input-sm" size="50" maxlength="255">
		</div>
	</div>
</div>

<div class="form-group">
	<label class="col-sm-2 control-label" for="wr_2">연락처</label>
	<div class="col-sm-10">
		<div class="input-group">
			<input type="text" name="wr_2" value="<?php echo $write['wr_2'] ?>" id="wr_2" required class="form-control input-sm" size="50" maxlength="255">
		</div>
	</div>
</div>

<div class="form-group">
	<label class="col-sm-2 control-label" for="wr_6">대상(인원)</label>
	<div class="col-sm-10">
		<div class="input-group">
			<input type="text" name="wr_6" value="<?php echo $write['wr_6'] ?>" id="wr_6" required class="form-control input-sm" size="50" maxlength="255">
		</div>
	</div>
</div>

<div class="form-group">
	<label class="col-sm-2 control-label" for="wr_8">지역</label>
	<div class="col-sm-10">
		<div class="input-group dsIB">
			<select name="wr_8" id="wr_8" required class="form-control input-sm">
				<option value="">선택</option>
				<option value="가평군"<?php if($write['wr_8'] == '가평군') {echo(' selected');} ?>>가평군</option>
				<option value="고양시"<?php if($write['wr_8'] == '고양시') {echo(' selected');} ?>>고양시</option>
				<option value="과천시"<?php if($write['wr_8'] == '과천시') {echo(' selected');} ?>>과천시</option>
				<option value="광주시"<?php if($write['wr_8'] == '광주시') {echo(' selected');} ?>>광주시</option>
				<option value="구리시"<?php if($write['wr_8'] == '구리시') {echo(' selected');} ?>>구리시</option>
				<option value="군포시"<?php if($write['wr_8'] == '군포시') {echo(' selected');} ?>>군포시</option>
				<option value="김포시"<?php if($write['wr_8'] == '김포시') {echo(' selected');} ?>>김포시</option>
				<option value="남양주시"<?php if($write['wr_8'] == '남양주시') {echo(' selected');} ?>>남양주시</option>
				<option value="동두천시"<?php if($write['wr_8'] == '동두천시') {echo(' selected');} ?>>동두천시</option>
				<option value="부천시"<?php if($write['wr_8'] == '부천시') {echo(' selected');} ?>>부천시</option>
				<option value="성남시"<?php if($write['wr_8'] == '성남시') {echo(' selected');} ?>>성남시</option>
				<option value="수원시"<?php if($write['wr_8'] == '수원시') {echo(' selected');} ?>>수원시</option>
				<option value="시흥시"<?php if($write['wr_8'] == '시흥시') {echo(' selected');} ?>>시흥시</option>
				<option value="안산시"<?php if($write['wr_8'] == '안산시') {echo(' selected');} ?>>안산시</option>
				<option value="안성시"<?php if($write['wr_8'] == '안성시') {echo(' selected');} ?>>안성시</option>
				<option value="안양시"<?php if($write['wr_8'] == '안양시') {echo(' selected');} ?>>안양시</option>
				<option value="양주시"<?php if($write['wr_8'] == '양주시') {echo(' selected');} ?>>양주시</option>
				<option value="양평군"<?php if($write['wr_8'] == '양평군') {echo(' selected');} ?>>양평군</option>
				<option value="양평군"<?php if($write['wr_8'] == '양평군') {echo(' selected');} ?>>여주시</option>
				<option value="연천군"<?php if($write['wr_8'] == '연천군') {echo(' selected');} ?>>연천군</option>
				<option value="오산시"<?php if($write['wr_8'] == '오산시') {echo(' selected');} ?>>오산시</option>
				<option value="용인시"<?php if($write['wr_8'] == '용인시') {echo(' selected');} ?>>용인시</option>
				<option value="의왕시"<?php if($write['wr_8'] == '의왕시') {echo(' selected');} ?>>의왕시</option>
				<option value="의정부시"<?php if($write['wr_8'] == '의정부시') {echo(' selected');} ?>>의정부시</option>
				<option value="이천시"<?php if($write['wr_8'] == '이천시') {echo(' selected');} ?>>이천시</option>
				<option value="이천시"<?php if($write['wr_8'] == '이천시') {echo(' selected');} ?>>파주시</option>
				<option value="평택시"<?php if($write['wr_8'] == '평택시') {echo(' selected');} ?>>평택시</option>
				<option value="포천시"<?php if($write['wr_8'] == '포천시') {echo(' selected');} ?>>포천시</option>
				<option value="하남시"<?php if($write['wr_8'] == '하남시') {echo(' selected');} ?>>하남시</option>
				<option value="화성시"<?php if($write['wr_8'] == '화성시') {echo(' selected');} ?>>화성시</option>
				<option value="기타"<?php if($write['wr_8'] == '기타') {echo(' selected');} ?>>기타</option>
			</select>
		</div>
		<div id="wr_9_wrap" class="input-group"<?php echo
			(($write['wr_8'] != '기타') ? ' style="display:none;"' : ' style="display:inline-block;"');?>>
			<input type="text" name="wr_9" value="<?php echo $write['wr_9'] ?>" id="wr_9" class="form-control input-sm" size="20" maxlength="50">
		</div>
	</div>
</div>

<div class="form-group">
	<label class="col-sm-2 control-label" for="wr_7">장소제공여부</label>
	<div class="col-sm-10">
		<div class="input-group">
			<label class="control-label sp-label">
				<input type="radio" name="wr_7" value="A"<?php if($write['wr_7'] == 'A') echo ' checked';?>> 제공
			</label>
			<label class="control-label sp-label">
				<input type="radio" name="wr_7" value="B"<?php if($write['wr_7'] == 'B') echo ' checked';?>> 미제공
			</label>
		</div>
	</div>
</div>

<div class="form-group">
	<label class="col-sm-2 control-label" for="wr_3">모집기간</label>
	<div class="col-sm-10">
		<div class="input-group">
			<label class="control-label sp-label">
				<input type="radio" name="wr_3" value="A"<?php if($write['wr_3'] == 'A') echo ' checked';?>> 상시모집
			</label>
			<label class="control-label sp-label">
				<input type="radio" name="wr_3" value="B"<?php if($write['wr_3'] == 'B') echo ' checked';?>> 직접입력
			</label>
		</div>
		<div id="wr_4_wrap" class="row mt10"<?php if($write['wr_3'] != 'B') echo ' style="display:none;"';?>>
            <div class="col-sm-4" style="padding:0;">
            	<div class='input-group date' id='datetimepicker1'>
            	    <input name="wr_4" value="<?php echo $write['wr_4'] ?>" id="wr_4" type='text' class="form-control" maxlength="255" />
            	    <span class="input-group-addon">
            	        <span class="glyphicon glyphicon-calendar"></span>
            	    </span>
            	</div>
		        <script type="text/javascript">
		            $(function () {
		                $('#datetimepicker1').datetimepicker({
							dayViewHeaderFormat: "YYYY년 MMMM",
							format: 'YYYY-MM-DD',
							locale: 'ko'
		                });
		            });
		        </script>
            </div>
            <div class="col-sm-1 taC" style="padding:0; line-height: 34px;">~</div>
	        <div class="col-sm-4" style="padding:0;">
	        	<div class='input-group date' id='datetimepicker2'>
	                <input name="wr_5" value="<?php echo $write['wr_5'] ?>" id="wr_5" type='text' class="form-control" maxlength="255" />
	                <span class="input-group-addon">
	                    <span class="glyphicon glyphicon-calendar"></span>
	                </span>
	            </div>
	        	<script type="text/javascript">
	        	    $(function () {
	        	        $('#datetimepicker2').datetimepicker({
							dayViewHeaderFormat: "YYYY년 MMMM",
							format: 'YYYY-MM-DD',
							locale: 'ko'
	        	        });
	        	    });
	        	</script>
	        </div>
		</div>
	</div>
</div>

<div class="form-group">
	<label class="col-sm-12" for="wr_content">교육 내용</label>
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
	<label class="col-sm-12" for="term1_Y">개인정보 수집·이용 동의서</label>
	<div class="col-sm-12">
		<div style="padding: 10px; border: 1px solid #ccc;">
			개인정보 수집·이용 내역<br>
			1. 수집목적 : 교육기부 매칭<br>
			2. 수집항목 : 성명, 연락처<br>
			3. 보유기간 : 경기도평생교육진흥원 홈페이지 교육기부 받기 게시글 삭제 시까지 <br>
			<br>
			위의 개인정보 수집·이용에 대한 동의를 거부할 권리가 있습니다.<br>
			그러나 동의를 거부할 경우 교육기부 받기 서비스를 이용하실 수 없습니다.
		</div>
	</div>
	<div class="col-sm-12 taR mt10">
		<div class="pull-right">
			<span class="dsIB vaB mr10">개인정보 수집·이용 동의서에 동의하시면 동의함을 선택해 주십시오.</span>
			<span class="dsIB vaB">
				<label class="control-label sp-label">
					<input type="radio" name="term1" id="term1_Y" value="Y"<?php if($subject != ''){echo(' checked');}?>> 동의함
				</label>
				<label class="control-label sp-label">
					<input type="radio" name="term1" id="term1_N" value="N"> 동의안함
				</label>
			</span>
		</div>
	</div>
</div>

<div class="form-group">
	<label class="col-sm-12" for="term2_Y">개인정보 제3자 제공 동의서</label>
	<div class="col-sm-12">
		<div style="padding: 10px; border: 1px solid #ccc;">
			개인정보 제3자 제공 내역<br>
			1. 제공받는 자 : 불특정 다수(경기도평생교육진흥원 홈페이지 교육기부 받기 게시)<br>
			2. 이용 목적 : 교육기부 매칭<br>
			3. 제공 항목 : 성명, 연락처<br>
			4. 제공 받는 자의 보유기간 : 개인정보 제공자의 내용 변경 및 파기요구 시까지<br>
			<br>
			위의 개인정보 제공에 대한 동의를 거부할 권리가 있습니다.<br>
			그러나 동의를 거부할 경우 교육기부 받기 서비스를 이용하실 수 없습니다.
		</div>
	</div>
	<div class="col-sm-12 taR mt10">
		<div class="pull-right">
			<span class="dsIB vaB mr10">개인정보 제3자 제공 동의서에 동의하시면 동의함을 선택해 주십시오.</span>
			<span class="dsIB vaB">
				<label class="control-label sp-label">
					<input type="radio" name="term2" id="term2_Y" value="Y"<?php if($subject != ''){echo(' checked');}?>> 동의함
				</label>
				<label class="control-label sp-label">
					<input type="radio" name="term2" id="term2_N" value="N"> 동의안함
				</label>
			</span>
		</div>
	</div>
</div>

<?php /* if($is_use_tag) { //태그 ?>
	<div class="form-group">
		<label class="col-sm-2 control-label" for="as_tag">태그</label>
		<div class="col-sm-10">
			<input type="text" name="as_tag" id="as_tag" value="<?php echo $write['as_tag']; ?>" class="form-control input-sm" size="50">
		</div>
	</div>
<?php } */ ?>
<?php /* for ($i=1; $is_link && $i<=G5_LINK_COUNT; $i++) { ?>
	<div class="form-group">
		<label class="col-sm-2 control-label" for="wr_link<?php echo $i ?>">링크 #<?php echo $i ?></label>
		<div class="col-sm-10">
			<input type="text" name="wr_link<?php echo $i ?>" value="<?php echo $write['wr_link'.$i]; ?>" id="wr_link<?php echo $i ?>" class="form-control input-sm" size="50">
			<?php if($i == "1") { ?>
				<div class="text-muted font-12" style="margin-top:4px;">
					유튜브, 비메오 등 동영상 공유주소 등록시 해당 동영상은 본문 자동실행
				</div>
			<?php } ?>
		</div>
	</div>
<?php } */ ?>

<?php if ($is_file) { ?>
	<div class="form-group">
		<label class="col-sm-2 control-label hidden-xs">첨부파일</label>
		<div class="col-sm-10">
			<p class="form-control-static text-muted" style="padding:0px; padding-top:4px;">
				<span class="cursor" onclick="add_file();"><i class="fa fa-plus-circle fa-lg"></i> 파일추가</span>
				&nbsp;
				<span class="cursor" onclick="del_file();"><i class="fa fa-times-circle fa-lg"></i> 파일삭제</span>
			</p>
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
				본문삽입시 {이미지:0}, {이미지:1} 형태로 글내용 입력시 지정 첨부사진이 출력됨
			</div>
		</div>
	</div>
<?php } ?>

<?php if ($is_guest) { //자동등록방지  ?>
	<div class="well well-sm text-center">
		<?php echo $captcha_html; ?>
	</div>
<?php } ?>

<div class="write-btn pull-center">
	<button type="submit" id="btn_submit" accesskey="s" class="btn btn-<?php echo $btn2;?> btn-sm"><i class="fa fa-check"></i> <b>작성완료</b></button>
	<a href="./board.php?bo_table=<?php echo $bo_table ?>" class="btn btn-<?php echo $btn1;?> btn-sm" role="button">취소</a>
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

$(function(){
	$('input[name="wr_3"]').on('change', function() {
		if ($(this).val() == 'B') $('#wr_4_wrap').show();
		else $('#wr_4_wrap').hide();
	});

	$('#wr_8').on('change', function() {
		if ($(this).val() == '기타') $('#wr_9_wrap').css('display', 'inline-block');
		else $('#wr_9_wrap').hide();
	});
})

function apms_myicon() {
	document.getElementById("picon").value = '';
	document.getElementById("ticon").innerHTML = '<?php echo str_replace("'","\"", $myicon);?>';
	return true;
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

	if ($('#wr_8').val() == '') {
		alert("지역을 선택해주세요.");
		$('#wr_8').focus();
		return false;
	}

	if ($('#wr_8').val() == '기타' && $('#wr_9').val() == '') {
		alert("지역을 직접 입력해주세요.");
		$('#wr_9').focus();
		return false;
	}

	if ($('input[name="wr_7"]:checked').length == 0) {
		alert("장소제공 여부를 선택해주세요.");
		$('input[name="wr_7"]').eq(0).focus();
		return false;
	}

	if ($('input[name="wr_3"]:checked').length == 0) {
		alert("모집기간을 선택해주세요.");
		$('input[name="wr_3"]').eq(0).focus();
		return false;
	}

	if ($('input[name="wr_3"]:checked').val() == 'B' && $('#wr_4').val() == '') {
		alert("모집 시작일을 입력해 주세요.");
		$('#wr_4').focus();
		return false;
	}

	if ($('input[name="wr_3"]:checked').val() == 'B' && $('#wr_5').val() == '') {
		alert("모집 마감일을 입력해 주세요.");
		$('#wr_5').focus();
		return false;
	}
	
	if ($('input[name="term1"]:checked').length == 0 || $('input[name="term1"]:checked').val() != 'Y') {
		alert("개인정보 수집·이용 동의서에 동의해야합니다.");
		$('input[name="term1"]').eq(0).focus();
		return false;
	}

	if ($('input[name="term2"]:checked').length == 0 || $('input[name="term2"]:checked').val() != 'Y') {
		alert("개인정보 제3자 제공 동의서에 동의해야합니다.");
		$('input[name="term2"]').eq(0).focus();
		return false;
	}

	<?php echo $captcha_js; // 캡챠 사용시 자바스크립트에서 입력된 캡챠를 검사함  ?>

	document.getElementById("btn_submit").disabled = "disabled";

	return true;
}

$(function(){
	$("#wr_content").addClass("form-control input-sm write-content");
});
</script>
