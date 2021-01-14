<?php
if (!defined('_GNUBOARD_')) exit; //개별 페이지 접근 불가

// 자료추출
if (!function_exists('miso_widget_list')) {
	function miso_widget_list($wset, $widget_path, $widget_url) {
		global $g5, $is_demo, $bonames; 

		$list = array();

		if($is_demo && $wset['demo']) {
			@include($widget_path.'/data/'.$wset['demo'].'.php');
		} else if($wset['data'] && is_file($widget_path.'/data/'.$wset['data'].'.php')) {
			// @include($widget_path.'/data/'.$wset['data'].'.php');
			$str_uniq = $wset['uniq'];
			$arr_uniq = explode(',', $str_uniq);
			$i2 = 0;
			for ($i=0; $i<count($arr_uniq); $i++) {
				$uniq = trim($arr_uniq[$i]);
				if ($uniq == '') continue;

				$list[$i2]['subject'] = $wset[$uniq . '_txt1'];
				$list[$i2]['fsize1'] = $wset[$uniq . '_fsize1'];
				$list[$i2]['fcolor1'] = $wset[$uniq . '_fcolor1'];
				$list[$i2]['content'] = $wset[$uniq . '_txt2'];
				$list[$i2]['fsize2'] = $wset[$uniq . '_fsize2'];
				$list[$i2]['fcolor2'] = $wset[$uniq . '_fcolor2'];
				$list[$i2]['href'] = $wset[$uniq . '_url'];
				$list[$i2]['barcolor'] = $wset[$uniq . '_barcolor']; // 가운데 바 컬러
				$list[$i2]['img']['src'] = $wset[$uniq . '_img']; //썸네일
				$list[$i2]['img']['org'] = $wset[$uniq . '_img']; //원본

				$i2++;
			}
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

if($is_demo && !$wset['stx']) {
	$wset['stx'] = '아미나빌더';
}

if(!$stxa && $wset['stx']) {
	$stxs = explode(',', $wset['stx']); //콤마(,)로 분리
	if(count($stxs)) {
		shuffle($stxs); //배열섞기
	}
	$stxa = $stxs[0]; //첫번째 배열값 지정
}

// 불투명, 래스터
$raster = '';
if($wset['opa']) {
	$raster .= '<div class="img-layer img-opa-bg img-opa'.$wset['opa'].'"></div>';
}
if($wset['raster']) {
	$raster .= '<div class="img-layer img-raster'.$wset['raster'].'"></div>';
}

// 스킨 불러오기
if(!$wset['skin']) $wset['skin'] = 'basic';

$skin = $wset['skin'].'.php';

// 슬라이드 사용안함
if(!$is_use_slider) {
	$skin = 'blank.php';
}

if(is_file($widget_path.'/skin/'.$skin)) {
	include ($widget_path.'/skin/'.$skin);
} else {
	echo '<ul class="bxslider">';
	echo '<li class="item text-center">'.$skin.' 위젯스킨 없음</li>';
	echo '</ul>';
}

?>