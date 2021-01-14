<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$list_skin_url.'/list.css" media="screen">', 0);

// 헤드스킨
if(isset($boset['hskin']) && $boset['hskin']) {
	add_stylesheet('<link rel="stylesheet" href="'.G5_CSS_URL.'/head/'.$boset['hskin'].'.css" media="screen">', 0);
	$head_class = 'list-head';
} else {
	$head_class = (isset($boset['hcolor']) && $boset['hcolor']) ? 'border-'.$boset['hcolor'] : 'border-black';
}

// 숨김설정
$is_num = (isset($boset['lnum']) && $boset['lnum']) ? false : true;

// 포토
$fa_photo = (isset($boset['ficon']) && $boset['ficon']) ? apms_fa($boset['ficon']) : '<i class="fa fa-user"></i>';

// 출력설정
$num_notice = ($is_thumb) ? '*' : '<span class="wr-icon wr-notice"></span>';

?>
<?php if($is_thumb) { ?>
	<style>
		.list-board .list-body .thumb-icon a { 
			<?php echo (isset($boset['ibg']) && $boset['ibg']) ? 'background:'.apms_color($boset['icolor']).'; color:#fff' : 'color:'.apms_color($boset['icolor']);?>; 
		}
	</style>
<?php } ?>
<div class="list-board">
	<div class="div-head <?php echo $head_class;?>">
		<?php if ($is_checkbox) { ?>
			<span class="wr-chk"><input type="checkbox" id="chkall" onclick="if (this.checked) all_checked(true); else all_checked(false);"></span>
		<?php } ?>
		<?php if($is_num) { ?>
			<span class="wr-num hidden-xs">번호</span>
		<?php } ?>
		<span class="wr-category hidden-xs">지역</span>
		<span class="wr-subject">교육신청 제목</span>
		<span class="wr-name hidden-xs">신청자 성명(기관명)</span>
		<span class="wr-member hidden-xs">대상(인원)</span>
		<span class="wr-date">모집기간</span>
	</div>
	<ul class="list-body">
	<?php
	for ($i=0; $i < $list_cnt; $i++) { 

		// 공지, 현재글 스타일 체크
		$li_css = '';
		if ($list[$i]['is_notice']) { // 공지사항
			$li_css = ' bg-light';
			$list[$i]['num'] = $num_notice;
			$list[$i]['ca_name'] = '';
			$list[$i]['subject'] = '<b>'.$list[$i]['subject'].'</b>';
			$wr_icon = '<b class="wr-hidden">[알림]</b>';
		} else {
			if($is_category && $list[$i]['ca_name']) {
				$list[$i]['subject'] = '['.$list[$i]['ca_name'].'] '.$list[$i]['subject'];
			}
			if ($wr_id == $list[$i]['wr_id']) {
				$li_css = ' bg-light';
				$list[$i]['num'] = '<span class="wr-text orangered">열람중</span>';
				$list[$i]['subject'] = '<b class="red">'.$list[$i]['subject'].'</b>';
			}
		}

		// 링크이동
		$list[$i]['target'] = '';
		if($is_link_target && !$list[$i]['is_notice'] && $list[$i]['wr_link1']) {
			$list[$i]['target'] = $is_link_target;
			$list[$i]['href'] = $list[$i]['link_href'][1];
		}

	?>
		<li class="list-item<?php echo $li_css;?>">
			<?php if ($is_checkbox) { ?>
				<div class="wr-chk">
					<input type="checkbox" name="chk_wr_id[]" value="<?php echo $list[$i]['wr_id'] ?>" id="chk_wr_id_<?php echo $i ?>">
				</div>
			<?php } ?>
			<?php if($is_num) { ?>
				<div class="wr-num hidden-xs"><?php echo $list[$i]['num']; ?></div>
			<?php } ?>
			<div class="wr-category hidden-xs"><div class="elss1"><?php echo(($list[$i]['wr_8'] == '기타') ? $list[$i]['wr_9'] : $list[$i]['wr_8'])?></div></div>
			<div class="wr-subject">
				<a href="<?php echo $list[$i]['href']; ?>" class="item-subject"<?php echo $list[$i]['target'];?><?php echo $is_modal_js;?>>
					<?php if ($list[$i]['wr_comment']) { ?>
						<span class="orangered visible-xs pull-right wr-comment">
							<i class="fa fa-comment lightgray"></i>
							<b><?php echo $list[$i]['wr_comment']; ?></b>
						</span>
					<?php } ?>
					<?php echo $list[$i]['icon_reply']; ?>
					<?php echo $list[$i]['subject']; ?>
					<?php if ($list[$i]['wr_comment']) { ?>
						<span class="count orangered hidden-xs"><?php echo $list[$i]['wr_comment']; ?></span>
					<?php } ?>
				</a>
			</div>
			<div class="wr-name hidden-xs"><?php echo F_hsc($list[$i]['wr_1']); ?></div>
			<div class="wr-member hidden-xs"><?php echo F_hsc($list[$i]['wr_6']); ?></div>
			<div class="wr-date">
				<?php echo (($list[$i]['wr_3'] == 'A') ? '상시모집' : date("y.m.d", strtotime($list[$i]['wr_4'])) . ' ~ ' . date("y.m.d", strtotime($list[$i]['wr_5']))); ?>
			</div>
		</li>
	<?php } ?>
	</ul>
	<div class="clearfix"></div>
	<?php if (!$is_list) { ?>
		<div class="wr-none">게시물이 없습니다.</div>
	<?php } ?>
</div>
