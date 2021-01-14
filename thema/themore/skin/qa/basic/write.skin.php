<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$skin_url.'/style.css" media="screen">', 0);

// 헤더 출력
if($header_skin)
	include_once('./header.php');

?>

<?php if($is_dhtml_editor) { ?>
	<style>
		#qa_content { border:0; display:none; }
	</style>
<?php } ?>

<div id="bo_w" class="write-wrap box-colorset<?php echo (G5_IS_MOBILE) ? ' box-colorset-mobile' : '';?>">
	<?php if(!$header_skin) { //헤더 없으면 출력 ?>
		<div class="well">
			<h2><?php echo ($w == "u") ? '글수정' : '글작성'; ?></h2>
		</div>
	<?php } ?>	
	<!-- 게시물 작성/수정 시작 { -->
    <form name="fwrite" id="fwrite" action="<?php echo $action_url ?>" onsubmit="return fwrite_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off" role="form" class="form-horizontal">
    <input type="hidden" name="w" value="<?php echo $w ?>">
    <input type="hidden" name="qa_id" value="<?php echo $qa_id ?>">
    <input type="hidden" name="sca" value="<?php echo $sca ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">

	<?php if ($category_option) { ?>
		<div class="form-group">
			<label class="col-sm-2 control-label hidden-xs" for="qa_category">분류<strong class="sound_only">필수</strong></label>
			<div class="col-sm-3">
				<select name="qa_category" id="qa_category" required class="form-control input-sm">
                    <option value="">선택하세요</option>
	                <?php echo $category_option ?>
		        </select>
			</div>
		</div>
	<?php } ?>

	<?php if ($is_email) { ?>
		<div class="form-group has-feedback">
			<label class="col-sm-2 control-label" for="qa_email">E-mail</label>
			<div class="col-sm-5">
                <input type="text" name="qa_email" value="<?php echo get_text($write['qa_email']); ?>" id="qa_email" <?php echo $req_email; ?> class="form-control input-sm email" size="50" maxlength="100">
				<span class="fa fa-envelope-o form-control-feedback"></span>
			</div>
			<div class="col-sm-5">
				<label style="font-weight:normal;">
					<input type="checkbox" name="qa_email_recv" value="1" <?php if($write['qa_email_recv']) echo 'checked="checked"'; ?>> 답변받기
				</label>
			</div>
		</div>
	<?php } ?>

	<?php if ($is_hp) { ?>
		<div class="form-group has-feedback">
			<label class="col-sm-2 control-label" for="qa_hp">휴대폰</label>
			<div class="col-sm-5">
                <input type="text" name="qa_hp" value="<?php echo get_text($write['qa_hp']); ?>" id="qa_hp" <?php echo $req_hp; ?> class="form-control input-sm" size="30">
				<span class="fa fa-phone form-control-feedback"></span>
			</div>
			<?php if($qaconfig['qa_use_sms']) { ?>
				<div class="col-sm-5">
					<label style="font-weight:normal;">
		                <input type="checkbox" name="qa_sms_recv" value="1" <?php if($write['qa_sms_recv']) echo 'checked="checked"'; ?>> 답변등록 SMS알림 수신
					</label>
				</div>
			<?php } ?>
		</div>
	<?php } ?>

	<div class="form-group">
		<label class="col-sm-2 control-label" for="qa_subject">제목<strong class="sound_only">필수</strong></label>
		<div class="col-sm-10">
			<?php if ($is_dhtml_editor) { ?>
				<input type="text" name="qa_subject" value="<?php echo get_text($write['qa_subject']); ?>" id="qa_subject" required class="form-control input-sm" size="50" maxlength="255">
				<input type="hidden" name="qa_html" value="1">
			<?php } else { ?>
				<div class="input-group">
					<input type="text" name="qa_subject" value="<?php echo $write['qa_subject']; ?>" id="qa_subject" required class="form-control input-sm" size="50" maxlength="255">
					<span class="input-group-addon font-12">
						<label style="padding:0; margin:0;font-weight:normal;">
							<input type="checkbox" id="qa_html" name="qa_html" onclick="html_auto_br(this);" value="'.$html_value.'" <?php echo $html_checked;?> style="padding:0; margin:0;">