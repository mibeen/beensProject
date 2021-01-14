<?php
if (!defined('_GNUBOARD_')) {
	if(is_file('../../../../../../common.php')) {
		include_once('../../../../../../common.php');
	} else {
		include_once('../../../../common.php');
	}
	include_once(G5_LIB_PATH.'/apms.more.lib.php');

	// Ajax
	$is_ajax = true;

	// 모달창
	$wset['modal_js'] = ($wset['modal'] == "1") ? apms_script('modal') : '';

	// 라인
	$wset['line'] = (!$wset['line']) ? 1 : (int)$wset['line'];
	$wset['lineh'] = (!$wset['lineh']) ? 20 : (int)$wset['lineh'];

	// 위젯아이디
	$wset['wid'] = $wid;
}

// 자료추출
if (!function_exists('miso_widget_list')) {
	function miso_widget_list($wset, $widget_path, $widget_url) {
		global $g5, $is_demo, $bonames; 

		$list = array();

		if($is_demo && $wset['demo']) {
			@include($widget_path.'/data/'.$wset['demo'].'.php');
		} else if($wset['data'] && is_file($widget_path.'/data/'.$wset['data'].'.php')) {
			@include($widget_path.'/data/'.$wset['data'].'.php');
		} else {
			// 글머리
			if($wset['bullet'] && empty($bonames)) {
				$result = sql_query(" select bo_table, bo_subject from {$g5['board_table']} ");
				for ($i=0; $row=sql_fetch_array($result); $i++) {

					if(!$row['bo_table']) continue;

					$pn = $row['bo_table'];

					$bonames[$pn] = $row['bo_subject'];
				}
			}

			// 추출
			$list = apms_board_rows($wset);
		}

		$list_cnt = count($list);

		if($wset['rdm'] && $list_cnt) {
			shuffle($list);
		}

		$rank = apms_rank_offset($wset['rows'], $wset['page']); 

		// 라벨
		if($wset['cap']) {
			$label_lock = '<div class="label-cap bg-orange">Lock</div>';
			$label_rank = '<div class="label-cap bg-'.$wset['rank'].'">Top';
			$label_new = '<div class="label-cap bg-'.$wset['new'].'">New</div>';
		} else {
			$label_lock = '<div class="in-right rank-icon en bg-orange">Lock</div>';
			$label_rank = '<div class="in-right rank-icon en bg-'.$wset['rank'].'">';
			$label_new = '<div class="in-right rank-icon en bg-'.$wset['new'].'">new</div>';
		}

		for ($i=0; $i < $list_cnt; $i++) {

			// 배열받기
			$rows[$i] = $list[$i];

			$bullet = '';
			if($wset['bullet']) {
				$bid = $list[$i]['bo_table'];
				if($wset['bullet'] == "2") {
					$bullet = $bonames[$bid];
				} else {
					$bullet = ($list[$i]['ca_name']) ? $list[$i]['ca_name'] : $bonames[$bid];
				}
				if($bullet && $wset['bulcut'])	{
					$bullet = cut_str($bullet, $wset['bulcut'], '');
				}
				if($bullet) {
					$bullet .= ' | ';
				}
			}

			// 글라벨, 글아이콘
			$label = '';
			$icon = $wset['icon'];
			if ($list[$i]['secret'] || $list[$i]['is_lock']) {
				$label = $label_lock;
				$icon = '<span class="img-lock">Lock</span>';
			} else if ($wset['rank']) {
				$label = $label_rank.$rank.'</div>';
				$icon = '<span class="rank-icon en bg-'.$wset['rank'].'">'.$rank.'</span>';	
			} else if ($list[$i]['new']) {
				$label = $label_new;
				$icon = ($icon) ? '<span class="'.$wset['new'].'">'.$icon.'</span>' : '<i class="fa fa-circle '.$wset['new'].'"></i>';
			} else if($icon) {
				$icon = '<span class="lightgray">'.$icon.'</span>';
			}

			// 글 링크
			$rows[$i]['target'] = $wset['modal_js'];
			if($wset['modal'] == "2" && $list[$i]['wr_link1']) { // 링크#1 현재창
				$rows[$i]['href'] = $list[$i]['link_href'][1];
				$rows[$i]['target'] = '';
			} else if($wset['modal'] == "3" && $list[$i]['wr_link1']) { // 링크#1 새창
				$rows[$i]['href'] = $list[$i]['link_href'][1];
				$rows[$i]['target'] = ' target="_blank"';
			}

			// 이미지 링크
			if($wset['lightbox'] && $list[$i]['img']['org']) {
				$rows[$i]['img_href'] = $list[$i]['img']['org'];
				$rows[$i]['img_target'] = ' data-lightbox="lb-'.$wset['wid'].'" data-title=""';
			} else {
				$rows[$i]['img_href'] = $rows[$i]['href'];
				$rows[$i]['img_target'] = $rows[$i]['target'];
			}
				
			$rows[$i]['bullet'] = $bullet;
			$rows[$i]['label'] = $label;
			$rows[$i]['icon'] = $icon;
			$rows[$i]['rank'] = $rank;
			$rank++;
		}

		unset($list);
		unset($wset);

		return $rows;
	}
}

// 글자수
$wset['bulcut'] = ($wset['bulcut'] == "") ? 2 : (int)$wset['bulcut'];

// 아이콘
$wset['icon'] = ($wset['icon']) ? apms_fa($wset['icon']) : '';

// 불투명, 래스터
$raster = '';
if($wset['opa']) {
	$raster .= '<div class="img-layer img-opa-bg img-opa'.$wset['opa'].'"></div>';
}
if($wset['raster']) {
	$raster .= '<div class="img-layer img-raster'.$wset['raster'].'"></div>';
}

// 메이슨리
$is_masonry_img = false;
if($wset['masonry'] == "2" && $wset['round'] != "1" && !$wset['comment']) {
	$is_masonry_img = true;
	$wset['thumb_h'] = '';
}

// 세로수
$is_sero = (int)$wset['sero'];
if(!$is_sero) $is_sero = 1;

// 라인
$line = ($wset['line'] == "1") ? 'ellipsis' : 'ellipsis-line';

// 스킨 불러오기
if(!$wset['skin']) $wset['skin'] = 'basic';

$skin = $wset['skin'].'.php';

if(is_file($widget_path.'/skin/'.$skin)) {
	include ($widget_path.'/skin/'.$skin);
} else {
	echo '<ul class="post post-list">';
	echo '<li class="item text-center">'.$skin.' 위젯스킨 없음</li>';
	echo '</ul>';
}

?>