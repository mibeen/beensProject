<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if($boset['lightbox']) apms_script('lightbox');

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$list_skin_url.'/list.css" media="screen">', 0);

// 이미지 비율
$thumb_w = $board['bo_'.MOBILE_.'gallery_width'];
$thumb_h = $board['bo_'.MOBILE_.'gallery_height'];
$img_h = apms_img_height($thumb_w, $thumb_h); // 이미지 높이

$cont_len = (G5_IS_MOBILE) ? $boset['m_cont'] : $boset['cont'];
if(!$cont_len) $cont_len = 100;

?>
<style>
	.list-item .talk-img { width:<?php echo ($boset['img_w'] > 0) ? $boset['img_w'] : 100;?>px; margin-left:15px; }
</style>
<div class="list-talk">
	<?php
	// 리스트
	for ($i=0; $i < $list_cnt; $i++) { 

		if($list[$i]['is_notice']) continue;		

		// 아이콘 체크
		$is_lock = false;
		$wr_lock = $wr_icon = $wr_label = '';
		if ($list[$i]['icon_secret'] || $list[$i]['is_lock']) {
			$wr_lock = '<span class="wr-icon wr-secret"></span>';
			$list[$i]['wr_content'] = ($list[$i]['is_lock']) ? '잠긴글입니다' : '비밀글입니다.';
			$is_lock = true;
		}

		// 공지, 현재글 스타일 체크
		if ($wr_id == $list[$i]['wr_id']) { // 현재글
			$wr_label = '<div class="label-band bg-blue">Now</div>';
			$wr_icon = '<span class="tack-icon bg-blue">현재</span>';
		} else if($is_lock) {
			$wr_label = '<div class="label-band bg-red">Lock</div>';
		} else if ($list[$i]['icon_hot']) {
			$wr_label = '<div class="label-band bg-orange">Hot</div>';
			$wr_icon = '<span class="tack-icon bg-orange">인기</span>';
		} else if ($list[$i]['icon_new']) {
			$wr_label = '<div class="label-band bg-green">New</div>';
			$wr_icon = '<span class="tack-icon bg-green">새글</span>';
		}

		// 링크
		if($is_link_target && $list[$i]['wr_link1']) {
			$list[$i]['href'] = $list[$i]['link_href'][1];
		}

		// 아이콘
		$wr_photo = ($list[$i]['as_icon']) ? apms_fa(apms_emo($list[$i]['as_icon'])) : '';
		if(!$wr_photo) {
			$wr_photo = apms_photo_url($list[$i]['mb_id']);
			$wr_photo = ($wr_photo) ? '<img src="'.$wr_photo.'">' : '<i class="fa fa-user"></i>';
		}

		$list[$i]['no_img'] = ''; // No-Image
		if($boset['lightbox']) { //라이트박스
			$img = ($is_lock) ? apms_thumbnail($list[$i]['no_img'], 0, 0, false, true) : apms_wr_thumbnail($bo_table, $list[$i], 0, 0, false, true);
			$thumb = apms_thumbnail($img['src'], $thumb_w, $thumb_h, false, true); // 썸네일
			$caption = "<a href='".$list[$i]['href']."'>".str_replace('"', '\'', $wr_icon).apms_get_html($list[$i]['subject'], 1);
			$caption .= " &nbsp;<i class='fa fa-comment'></i> ";
			if($list[$i]['wr_comment']) $caption .= "<span class='en orangered'>".$list[$i]['wr_comment']."</span>&nbsp; ";
			$caption .= "<span class='font-normal font-11'>댓글달기</span></a>";
		} else {
			$thumb = ($is_lock) ? apms_thumbnail($list[$i]['no_img'], $thumb_w, $thumb_h, false, true) : apms_wr_thumbnail($bo_table, $list[$i], $thumb_w, $thumb_h, false, true);
		}

	?>
		<div class="list-row">
			<div class="list-item">
				<div class="talk-box-wrap">
					<div class="pull-left">
						<span class="talker-photo"><?php echo $wr_photo;?></span>
						<?php if ($is_checkbox) { ?>
							<p class="list-chk">
								<input type="checkbox" name="chk_wr_id[]" value="<?php echo $list[$i]['wr_id'] ?>" id="chk_wr_id_<?php echo $i ?>">
							</p>
						<?php } ?>
					</div>
					<div class="talk-box talk-right">
						<div class="talk-bubble">
							<div class="list-content">
								<?php echo $wr_label;?>
								<?php if($thumb['src']) { ?>
									<div class="talk-img pull-right">
										<div class="imgframe">
											<div class="img-wrap" style="padding-bottom:<?php echo $img_h;?>%;">
												<div class="img-item">
													<?php if($boset['lightbox']) { //라이트박스 ?>
														<a href="<?php echo $img['src'];?>" data-lightbox="<?php echo $bo_table;?>-lightbox" data-title