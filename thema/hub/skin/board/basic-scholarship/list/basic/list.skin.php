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
$is_name = (isset($boset['lname']) && $boset['lname']) ? false : true;
$is_date = (isset($boset['ldate']) && $boset['ldate']) ? false : true;
$is_hit = (isset($boset['lhit']) && $boset['lhit']) ? false : true;
$is_vicon = (isset($boset['vicon']) && $boset['vicon']) ? false : true;

// 보임설정
$is_category = (isset($boset['lcate']) && $boset['lcate']) ? true : false;
$is_thumb = (isset($boset['lthumb']) && $boset['lthumb']) ? true : false;
$is_down = (isset($boset['ldown']) && $boset['ldown']) ? true : false;
$is_visit = (isset($boset['lvisit']) && $boset['lvisit']) ? true : false;
$is_good = (isset($boset['lgood']) && $boset['lgood']) ? true : false;
$is_nogood = (isset($boset['lnogood']) && $boset['lnogood']) ? true : false;

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

		<span class="wr-category hidden-xs">구분</span>
		<span class="wr-subject">제목</span>
		<span class="wr-hit">진행상태</span>
		<span class="wr-date hidden-xs">첨부파일</span>

	</div>
	<ul class="list-body">
	<?php
	for ($i=0; $i < $list_cnt; $i++) { 

		$sql_row = sql_fetch("SELECT * FROM g5_board_extend WHERE bo_table = '{$bo_table}' AND wr_id = {$list[$i]['wr_id']} ORDER BY wr_id DESC LIMIT 0,1");

		//아이콘 체크
		$wr_icon = '';
		$is_lock = false;
		if ($list[$i]['icon_secret'] || $list[$i]['is_lock']) {
			$wr_icon = '<span class="wr-icon wr-secret"></span>';
			$is_lock = true;
		} else if ($list[$i]['icon_hot']) {
			$wr_icon = '<span class="wr-icon wr-hot"></span>';
		} else if ($list[$i]['icon_new']) {
			$wr_icon = '<span class="wr-icon wr-new"></span>';
		} else if ($list[$i]['icon_video']) {
			$wr_icon = '<span class="wr-icon wr-video"></span>';
		} else if ($list[$i]['icon_image']) {
			$wr_icon = '<span class="wr-icon wr-image"></span>';
		} else if ($list[$i]['icon_file']) {
			$wr_icon = '<span class="wr-icon wr-file"></span>';
		}

		// 공지, 현재글 스타일 체크
		$li_css = '';
		if ($list[$i]['is_notice']) { // 공지사항
			$li_css = ' bg-light';
			$list[$i]['num'] = $num_notice;
			// $list[$i]['ca_name'] = '';
			$list[$i]['subject'] = '<b>'.$list[$i]['subject'].'</b>';
			$wr_icon = ($is_thumb) ? '' : '<b class="wr-hidden">[알림]</b>';
		} else {
			if ($wr_id == $list[$i]['wr_id']) {
				$li_css = ' bg-light';
				$list[$i]['num'] = '<span class="wr-text orangered">열람중</span>';
				$list[$i]['subject'] = '<b class="red">'.$list[$i]['subject'].'</b>';
			}
		}

		// 링크이동
		$list[$i]['target'] = '';
		$cursor_style = '';
		if($is_link_target && !$list[$i]['is_notice'] && $list[$i]['wr_link1']) {
			$list[$i]['target'] = $is_link_target;
			$list[$i]['href'] = $list[$i]['link_href'][1];
		}
		// 진행상태 off일때 9레벨 이상이 아니면 링크 없음.
		if ($sql_row['wr_14'] == 'off' && (!$is_member || ($is_member && $member['mb_level'] < 9))) {
			$list[$i]['href'] = 'javascript:void(0);';
			$cursor_style = ' style="cursor:default"';
		}

		# 첨부파일 다운로드
		$file_href = '';
		$afile = get_file($bo_table,$list[$i]['wr_id']);
		for($m=0; $m < $afile['count']; $m++){
			# pdf 인지 확인
			if(strpos($afile[$m]['source'], ".pdf") !== false) {
				$file_href = $afile[$m]['href'];
				break;
			}
		}
		unset($afile);
	?>
		<li class="list-item<?php echo $li_css;?>">
			<?php if ($is_checkbox) { ?>
				<div class="wr-chk">
					<input type="checkbox" name="chk_wr_id[]" value="<?php echo $list[$i]['wr_id'] ?>" id="chk_wr_id_<?php echo $i ?>">
				</div>
				
			<?php } ?>
			<div class="wr-category hidden-xs"><?php echo $list[$i]['ca_name']; ?></div>
			<div class="wr-subject">
				<a href="<?php echo $list[$i]['href']; ?>" class="item-subject"<?php echo $list[$i]['target'];?><?php echo $is_modal_js;?><?php echo($cursor_style)?>>
					<?php if ($list[$i]['wr_comment']) { ?>
						<span class="orangered visible-xs pull-right wr-comment">
							<i class="fa fa-comment lightgray"></i>
							<b><?php echo $list[$i]['wr_comment']; ?></b>
						</span>
					<?php } ?>
					<?php echo $list[$i]['icon_reply']; ?>
					<?php echo $wr_icon; ?>
					<?php echo $list[$i]['subject']; ?>
					<?php if ($list[$i]['wr_comment']) { ?>
						<span class="count orangered hidden-xs"><?php echo $list[$i]['wr_comment']; ?></span>
					<?php } ?>
				</a>
			</div>
			<div class="wr-hit"><?php echo(($sql_row['wr_14'] == 'off') ? '진행중' : '완료')?></div>
			<div class="wr-date hidden-xs">
				<?php
				if ($file_href != '') {
					?>
					<a href="<?php echo $file_href?>" class="wr-pdf"><i class="fa fa-file-pdf-o"></i> pdf</a>
					<?php
				}
				?>
			</div>
		</li>
	<?php } ?>
	</ul>
	<div class="clearfix"></div>
	<?php if (!$is_list) { ?>
		<div class="wr-none">게시물이 없습니다.</div>
	<?php } ?>
</div>
