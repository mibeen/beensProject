<?php
if (!defined('_GNUBOARD_')) exit; //개별 페이지 접근 불가

// 추출하기
$wset['image'] = 1; //이미지글만 추출
$wset['rows'] = $wmset['rows'] = 1;
$wset['thumb_w'] = 310;
$wset['thumb_h'] = 218;
if( isset($wset['where']) && is_numeric($wset['where']) ){
	$wset['where'] = " wr_id=".$wset['where']." ";
}else{
	$wset['where'] = "";
}

$list = apms_board_rows($wset);
$list_cnt = count($list);

// 아이콘
$icon = (isset($wset['icon']) && $wset['icon']) ? '<span class="lightgray">'.apms_fa($wset['icon']).'</span>' : '';
$is_ticon = (isset($wset['ticon']) && $wset['ticon']) ? true : false;

// 랭킹
$rank = apms_rank_offset($wset['rows'], $wset['page']); 

// 날짜
$is_date = (isset($wset['date']) && $wset['date']) ? true : false;
$is_dtype = (isset($wset['dtype']) && $wset['dtype']) ? $wset['dtype'] : 'm.d';
$is_dtxt = (isset($wset['dtxt']) && $wset['dtxt']) ? true : false;

// 새글
$is_new = (isset($wset['new']) && $wset['new']) ? $wset['new'] : 'red'; 

// 댓글
$is_comment = (isset($wset['comment']) && $wset['comment']) ? true : false;

//제목 
$is_title = (isset($wset['title']) && $wset['title'] == "1") ? true : false;

// 강조글
$bold = array();
$strong = explode(",", $wset['strong']);
$is_strong = count($strong);
for($i=0; $i < $is_strong; $i++) {

	list($n, $bc) = explode("|", $strong[$i]);

	if(!$n) continue;

	$n = $n - 1;
	$bold[$n]['num'] = true;
	$bold[$n]['color'] = ($bc) ? ' class="'.$bc.'"' : '';
}



$is_left = (isset($wset['view']) && $wset['view'] == 'left') ? 'view-left' : '';
$color = isset($wset['color']) ? $wset['color'] : ' red ';


$category = isset($wset['bo_list']) ? $wset['bo_list'] : 'sitein';
$c_result = sql_fetch("SELECT bo_subject FROM g5_board WHERE bo_table = '{$category}' ORDER BY gr_id DESC LIMIT 0, 1");
$category = $c_result['bo_subject'];

?>

<?php
// 리스트
#for ($i=0; $i < $list_cnt; $i++) { 
for ($i=0; $i < 1; $i++) { 

	//아이콘 체크
	$wr_icon = $icon;
	$is_lock = false;
	if ($list[$i]['secret'] || $list[$i]['is_lock']) {
		$is_lock = true;
		$wr_icon = '<span class="wr-icon wr-secret"></span>';
	} else if ($wset['rank']) {
		$wr_icon = '<span class="rank-icon en bg-'.$wset['rank'].'">'.$rank.'</span>';	
		$rank++;
	} else if($list[$i]['new']) {
		$wr_icon = '<span class="wr-icon wr-new"></span>';
	} else if($is_ticon) {
		if ($list[$i]['icon_video']) {
			$wr_icon = '<span class="wr-icon wr-video"></span>';
		} else if ($list[$i]['icon_image']) {
			$wr_icon = '<span class="wr-icon wr-image"></span>';
		} else if ($list[$i]['wr_file']) {
			$wr_icon = '<span class="wr-icon wr-file"></span>';
		}
	}

	// 링크이동
	$target = '';
	if($is_link_target && $list[$i]['wr_link1']) {
		$target = $is_link_target;
		$list[$i]['href'] = $list[$i]['link_href'][1];
	}

	//강조글
	if($is_strong) {
		if($bold[$i]['num']) {
			$list[$i]['subject'] = '<b'.$bold[$i]['color'].'>'.$list[$i]['subject'].'</b>';
		}
	}

?>

	 <li class="<?php echo $is_left . ' ' . $color ?>"><a href="<?php echo $list[$i]['href'];?>" <?php echo $target;?>>
      <div class="img-box">
      	<img src="<?php echo $list[$i]['img']['src'];?>" alt="<?php echo $list[$i]['img']['alt'];?>">
      </div>
      <div class="txt-box">
        <p class="post-category"><?php echo $category ?></p>
        <p class="post-title"><?php echo $list[$i]['subject'];?></p>
      </div>
    </a>

    <?php if($setup_href) { ?>
	<div class="btn-wset text-center p10">
		<a href="<?php echo $setup_href;?>" class="win_memo">
			<span class="text-muted"><i class="fa fa-cog"></i> 위젯설정</span>
		</a>
	</div>
	<?php } ?>


	</li>



<?php } ?>


<?php if(!$list_cnt) { ?>
	<div class="post-none">글이 없습니다.</div>
<?php } ?>