<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if($boset['lightbox']) apms_script('lightbox');

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$list_skin_url.'/list.css" media="screen">', 0);

// 이미지 비율
$thumb_w = $board['bo_'.MOBILE_.'gallery_width'];
$thumb_h = $board['bo_'.MOBILE_.'gallery_height'];
$img_h = apms_img_height($thumb_w, $thumb_h); // 이미지 높이

$img_align = ($boset['align']) ? 'img-right pull-right' : 'img-left pull-left';
$ellipsis = (G5_IS_MOBILE) ? '' : ' class="ellipsis"';
$cont_len = (G5_IS_MOBILE) ? $boset['m_cont'] : $boset['cont'];
if($cont_len == "") $cont_len = 100;
$no_img = ($boset['noimg']) ? '' : $board_skin_url.'/img/no-img.jpg';

?>
<style>	
	.is-pc .ko .panel .panel-body {font-size:13pt;}
</style>
<div class="list-webzine">
	<?php
	// 리스트
	for ($i=0; $i < $list_cnt; $i++) { 

		if($list[$i]['is_notice']) continue;		

		//아이콘 체크
		$is_lock = false;
		$wr_lock = $wr_icon = $wr_label = '';
		if ($list[$i]['icon_secret'] || $list[$i]['is_lock']) {
			$wr_lock = '<span class="wr-icon wr-secret"></span>';
			$list[$i]['wr_content'] = ($list[$i]['is_lock']) ? '잠긴글입니다' : '비밀글입니다.';
			$is_lock = true;
		}

		// 공지, 현재글 스타일 체크
		if ($wr_id == $list[$i]['wr_id']) { // 현재글
			$wr_label = '<div class="label-cap bg-blue">Now</div>';
			$wr_icon = '<span class="tack-icon bg-blue">현재</span>';
		} else if($is_lock) {
			$wr_label = '<div class="label-cap bg-red">Lock</div>';
		} else if ($list[$i]['icon_hot']) {
			$wr_label = '<div class="label-cap bg-orange">Hot</div>';
			$wr_icon = '<span class="tack-icon bg-orange">인기</span>';
		} else if ($list[$i]['icon_new']) {
			$wr_label = '<div class="label-cap bg-green">New</div>';
			$wr_icon = '<span class="tack-icon bg-green">새글</span>';
		}

		// 링크
		if($is_link_target && $list[$i]['wr_link1']) {
			$list[$i]['href'] = $list[$i]['link_href'][1];
		}

		$list[$i]['no_img'] = $no_img; // No-Image
		if($boset['lightbox']) { //라이트박스
			$img = ($is_lock) ? apms_thumbnail($list[$i]['no_img'], 0, 0, false, true) : apms_wr_thumbnail($bo_table, $list[$i], 0, 0, false, true);
			$thumb = apms_thumbnail($img['src'], $thumb_w, $thumb_h, false, true); // 썸네일
			$caption = "<a href='".$list[$i]['href']."'>".str_replace('"', '\'', $wr_icon).apms_get_html($list[$i]['subject'], 1);
			$caption .= " &nbsp;<i class='fa fa-comment'></i> ";
			if($list[$i]['wr_comment']) $caption .= "<span class='en orangered'>".$list[$i]['wr_comment']."</span>&nbsp; ";
			$caption .= "<span class='font-normal font-12'>댓글달기</span></a>";
		} else {
			$thumb = ($is_lock) ? apms_thumbnail($list[$i]['no_img'], $thumb_w, $thumb_h, false, true) : apms_wr_thumbnail($bo_table, $list[$i], $thumb_w, $thumb_h, false, true);
		}
	?>
  <div class="panel nx_faq">
    <div class="panel-heading nx_faq_top" role="tab" id="heading<?php echo $i; ?>">
      <h4 class="panel-title nx_panel_title">
	    <?php if ($is_checkbox) { ?>
			<input type="checkbox" name="chk_wr_id[]" value="<?php echo $list[$i]['wr_id']; ?>" id="chk_wr_id_<?php echo $i; ?>" class="nx_faq_adm_check">
		<?php } ?>
		
			<span class="nx_faq_q">
				<img src="<?php echo G5_SKIN_URL; ?>/board/Basic-Board/list/accordion/img/Q.png" alt="">
			</span>

			<?php 
			/*
			if ($boset['img']) { 
				$img = apms_wr_thumbnail($bo_table, $list[$i], 50, 50, false, true); // 썸네일
				$img['src'] = (!$img['src'] && $boset['photo']) ? apms_photo_url($list[$i]['mb_id']) : $img['src']; // 회원사진		
			?>
			<a href="<?php echo $list[$i]['href'];?>">
				<?php if($img['src']) { ?>
					<img src="<?php echo $img['src'];?>" alt="<?php echo $img['alt'];?>">
				<?php } else { ?>
					<?php echo $icon;?>
				<?php } ?>
			</a>
			<?php 
			} 
			*/
			?>

			<table border="0" cellpadding="0" cellspacing="0" class="nx_tit_wrap">
				<tr>
					<td>
			        <?php if ($is_category) { ?>
						<p class="nx_faq_cate"><?php echo $list[$i]['ca_name'] ?></p>
						<?php } ?>
						<p class="nx_faq_tit">
							<a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $i; ?>" aria-expanded="<?php echo $aria_expanded; ?>" aria-controls="collapse<?php echo $i; ?>"><?/*href="<?php echo $list[$i]['href'];?>"*/?>
							<?php echo $list[$i]['icon_reply']; ?>
							<?php echo $wr_icon;?>
							<?php echo $list[$i]['subject']; ?>
				        </a>

				        <?php if ($list[$i]['comment_cnt']) { ?>
							<span class="sound_only">댓글</span><span class="count orangered">+<?php echo $list[$i]['comment_cnt']; ?></span><span class="sound_only">개</span>
							<?php } ?>
						</p>
					</td>
				</tr>
			</table>
		
      </h4>
    </div>
    <div id="collapse<?php echo $i; ?>" class="panel-collapse collapse <?php echo $collapse_in; ?>" role="tabpanel" aria-labelledby="heading<?php echo $i; ?>">
      <div class="panel-body nx_faq_ct" data-url="<?php echo G5_SKIN_URL; ?>/board/Basic-Board/list/accordion/img/A.png">
      	<span class="nx_faq_a"><img src="<?php echo G5_SKIN_URL; ?>/board/Basic-Board/list/accordion/img/A.png" alt=""></span>
      	<?php echo $list[$i]['content']; ?>
      </div>
    </div>
  </div>
	<?php } ?>

</div>

<?php if (!$list_cnt) { ?>
	<div class="text-center text-muted list-none">게시물이 없습니다.</div>
<?php } ?>

<script>
$(function(){
	$(document).on('click', 'a[data-toggle=collapse]', function(e){
		// Accordion을 클릭했을 떄,
		if($(this).hasClass('collapsed')){
			// Opened
			$(this).closest('.nx_faq').removeClass('active');
		}else{
			// Closed
			$(this).closest('.nx_faq').addClass('active');
		}
	})
})
</script>
