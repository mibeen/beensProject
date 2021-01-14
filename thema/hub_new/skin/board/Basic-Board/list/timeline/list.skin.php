<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if($boset['lightbox']) apms_script('lightbox');

// 스크립트 로딩
apms_script('timeline');

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$list_skin_url.'/list.css" media="screen">', 0);

// 이미지 비율
$thumb_w = $board['bo_'.MOBILE_.'gallery_width'];
$thumb_h = $board['bo_'.MOBILE_.'gallery_height'];
$img_h = apms_img_height($thumb_w, $thumb_h); // 이미지 높이

$cont_len = (G5_IS_MOBILE) ? $boset['m_cont'] : $boset['cont'];
if($cont_len == "") $cont_len = 100;
$sep_color = ($boset['color']) ? $boset['color'] : 'black';

?>
<div class="list-timeline">
	<div class="list-row timeline animated<?php echo $boset['dadan'];?>">
	<?php
	// 리스트
	$save_sep = '';
	for ($i=0; $i < $list_cnt; $i++) { 

		if($list[$i]['is_notice']) continue;		

		//날짜
		$timeline_sep = '';
		if($boset['sep'] == 'day') {
			$date_sep = date("Ymd", $list[$i]['date']);
			$date_title = date("d M Y", $list[$i]['date']);
			$am_pm = (int)date("H", $list[$i]['date']);
			$am_pm = ($am_pm > 12) ? 'PM' : 'AM';
			$date_wr = date("Y.m.d h:i", $list[$i]['date']).' '.$am_pm;
		} else if($boset['sep'] == 'year') {
			$date_sep = date("Y", $list[$i]['date']);
			$date_title = date("Y", $list[$i]['date']);
			$date_wr = date("d M Y", $list[$i]['date']);
		} else {
			$date_sep = date("Ym", $list[$i]['date']);
			$date_title = date("M Y", $list[$i]['date']);
			$date_wr = date("d M Y", $list[$i]['date']);
		}

		if(substr($list[$i]['wr_datetime'], 0, 10) == G5_TIME_YMD) {
			$date_sep = $date_title = 'Today';
		}

		if($save_sep == $date_sep) {
			;
		} else {
			$timeline_sep = ' timeline-sep';
			$save_sep = $date_sep;
		}

		//아이콘 체크
		$is_lock = false;
		$wr_lock = $wr_icon = $wr_label = '';
		$fa_icon = '<i class="fa fa-comment bg-lightgray"></i>';
		if ($list[$i]['icon_secret'] || $list[$i]['is_lock']) {
			$wr_lock = '<span class="wr-icon wr-secret"></span>';
			$list[$i]['wr_content'] = ($list[$i]['is_lock']) ? '잠긴글입니다' : '비밀글입니다.';
			$is_lock = true;
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

		// 공지, 현재글 스타일 체크
		if ($wr_id == $list[$i]['wr_id']) { // 현재글
			$wr_label = '<div class="label-band bg-blue">Now</div>';
			$wr_icon = '<span class="tack-icon bg-blue">현재</span>';
			$fa_icon = '<i class="fa fa-eye bg-blue"></i>';
		} else if($is_lock) {
			$wr_label = '<div class="label-band bg-red">Lock</div>';
			$fa_icon = '<i class="fa fa-lock bg-red"></i>';
		} else if ($list[$i]['icon_hot']) {
			$wr_label = '<div class="label-band bg-orange">Hot</div>';
			$wr_icon = '<span class="tack-icon bg-orange">인기</span>';
			$fa_icon = '<i class="fa fa-thumbs-up bg-orange"></i>';
		} else if ($list[$i]['icon_new']) {
			$wr_label = '<div class="label-cap bg-green">New</div>';
			$wr_icon = '<span class="tack-icon bg-green">새글</span>';
			$fa_icon = '<i class="fa fa-heart bg-green"></i>';
		} else if ($img['src']) {
			$fa_icon = '<i class="fa fa-picture-o bg-green"></i>';
		} else if ($list[$i]['icon_link']) {
			$fa_icon = '<i class="fa fa-link bg-yellow"></i>';
		} else if ($list[$i]['icon_file']) {
			$fa_icon = '<i class="fa fa-download bg-violet"></i>';
		}

		// 링크
		if($is_link_target && $list[$i]['wr_link1']) {
			$list[$i]['href'] = $list[$i]['link_href'][1];
		}

		//회원사진
		$photo = ($list[$i]['mb_id'] && $boset['photo']) ? apms_photo_url($list[$i]['mb_id']) : ''; 
		$fa_icon = ($photo) ? '<img src="'.$photo.'">' : $fa_icon;

	?>
		<div class="timeline-row list-item<?php echo $timeline_sep;?>">
			<?php if($timeline_sep) { ?>
				<div class="timeline-label en bg-<?php echo $sep_color;?> no-border">
					<i><?php echo $date_title;?></i>
				</div>
			<?php } ?>
			<!-- 20200327 주요사업 페이지 리뉴얼로 인한 삭제 <div class="timeline-time en">
				<i class="fa fa-clock-o fa-lg lightgray"></i> <?php //echo $date_wr; ?>
			</div>
			 -->
			<div class="timeline-icon">
				<i class="fa fa-comment bg-lightgray"></i>
			</div>
			<div class="timeline-content">
				<div class="list-content">
					<?php if($thumb['src']) { ?>
						<div class="list-media">
							<?php echo $wr_label;?>
							<?php if($boset['lightbox']) { //라이트박스 ?>
								<a href="<?php echo $img['src'];?>" data-lightbox="<?php echo $bo_table;?>-lightbox" data-title="<?php echo $caption;?>">
							<?php } else { ?>
								<a href="<?php echo $list[$i]['href'];?>"<?php echo $is_modal_js.$is_link_target;?>>
							<?php } ?>
								<img src="<?php echo $list[$i]['as_thumb'];?>" alt="<?php echo $list[$i]['as_thumb'];?>" class="timeline-img">
							</a>
							<?php if($boset['shadow']) echo apms_shadow($boset['shadow']); //그림자 ?>
						</div>
					<?php } ?>
					
				</div>
			</div>
		</div>
	<?php } ?>
	</div>
</div>
<div class="clearfix h30"></div>
<?php if (!$list_cnt) { ?>
	<div class="text-center text-muted list-none">게시물이 없습니다.</div>
<?php } ?>