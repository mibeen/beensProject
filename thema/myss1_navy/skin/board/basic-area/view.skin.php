<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$result = sql_fetch("SELECT * FROM g5_board_extend WHERE bo_table = '{$bo_table}' AND wr_id = '{$wr_id}' LIMIT 0, 1");
#$sholar = sql_fetch($result);

$sh_2 = $result['wr_2'];

$attach_list = '';
if ($view['link']) {
	// 링크
	for ($i=1; $i<=count($view['link']); $i++) {
		if ($view['link'][$i]) {
			$attach_list .= '<a class="list-group-item break-word" href="'.$view['link_href'][$i].'" target="_blank">';
			$attach_list .= '<span class="label label-warning pull-right view-cnt">'.number_format($view['link_hit'][$i]).'</span>';
			$attach_list .= '<i class="fa fa-link"></i> '.cut_str($view['link'][$i], 70).'</a>'.PHP_EOL;
		}
	}
}

// 가변 파일
$j = 0;
for ($i=0; $i<count($view['file']); $i++) {
	if (isset($view['file'][$i]['source']) && $view['file'][$i]['source'] && !$view['file'][$i]['view']) {
		if ($board['bo_download_point'] < 0 && $j == 0) {
			$attach_list .= '<a class="list-group-item"><i class="fa fa-bell red"></i> 다운로드시 <b>'.number_format(abs($board['bo_download_point'])).'</b>'.AS_MP.' 차감 (최초 1회 / 재다운로드시 차감없음)</a>'.PHP_EOL;
		}
		$file_tooltip = '';
		if($view['file'][$i]['content']) {
			$file_tooltip = ' data-original-title="'.strip_tags($view['file'][$i]['content']).'" data-toggle="tooltip"';
		}
		$attach_list .= '<a class="list-group-item break-word view_file_download at-tip" href="'.$view['file'][$i]['href'].'"'.$file_tooltip.'>';
		//$attach_list .= '<span class="label label-primary pull-right view-cnt">'.number_format($view['file'][$i]['download']).'</span>';
		$attach_list .= '<i class="fa fa-download"></i>자기소개서 : '.$view['file'][$i]['source'].' ('.$view['file'][$i]['size'].') &nbsp;';
		$attach_list .= '<span class="en font-11 text-muted"><i class="fa fa-clock-o"></i> '.apms_datetime(strtotime($view['file'][$i]['datetime']), "Y.m.d").'</span></a>'.PHP_EOL;

		/* PDF 문서 바로 보기*/
		/*
			if(strpos($view['file'][$i]['source'], ".pdf") !== false) { 
				$attach_list .= '<a href="'.G5_URL.'/web_pdf/viewer.html?file=../'. str_replace(G5_URL, "", $view['file'][$i]['path']).'/'.$view['file'][$i]['file'].'" target="_blank" class="list-group-item break-word view_file_download at-tip" style="font-weight:600;">';
				$attach_list .= '<i class="fa fa-book"></i> PDF 문서 바로 보기</a>';
			} 
		*/


		$j++;
	}
}

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css" media="screen">', 0);

?>
<?php if($boset['video']) { ?>
	<style>
	.view-wrap .apms-autowrap { max-width:<?php echo (G5_IS_MOBILE) ? '100%' : $boset['video'];?> !important;}
	</style>
<?php } ?>
<style>
	.cutText{overflow: hidden;-ms-text-overflow: ellipsis;text-overflow: ellipsis;white-space: nowrap; width: 100% !important; position: relative; word-break: break-all;}
	#wr_12, #wr_13{padding-right: 0%;}
	#wr_12.cutText, #wr_13.cutText{padding-right: 5%;}
	.list-group-item .more{display: none; cursor: pointer; display: inline-block; position: static; color: #FFF; margin-left: 10px; padding: 0 10px; border-radius: 4px; background: #8ec700;}
	.list-group-item.cutText .more{position: absolute; right: 0; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; margin-left: 0;}
</style>
<script src="<?php echo G5_JS_URL; ?>/viewimageresize.js"></script>


<div class="view-wrap<?php echo (G5_IS_MOBILE) ? ' view-mobile font-14' : '';?>">
	<h1><?php if($view['photo']) { ?><img src="<?php echo $view['photo'];?>" class="photo" alt=""><?php } ?><?php echo cut_str(get_text($view['wr_subject']), 70); ?></h1>
	<div class="panel panel-default view-head<?php echo ($attach_list) ? '' : ' no-attach';?>">
		<div class="panel-heading">
			<div class="font-12 text-muted">
				<i class="fa fa-user"></i>
				<?php echo $view['name']; //등록자 ?><?php echo ($is_ip_view) ? '<span class="print-hide hidden-xs">&nbsp;('.$ip.')</span>' : ''; ?>
				<?php if($view['ca_name']) { ?>
					<span class="hidden-xs">
						<span class="sp"></span>
						<i class="fa fa-tag"></i>
						<?php echo $view['ca_name']; //분류 ?>
					</span>
				<?php } ?>

				<span class="sp"></span>
				<i class="fa fa-comment"></i>
				<?php echo ($view['wr_comment']) ? '<b class="red">'.number_format($view['wr_comment']).'</b>' : 0; //댓글수 ?>

				<span class="sp"></span>
				<i class="fa fa-eye"></i>
				<?php echo number_format($view['wr_hit']); //조회수 ?>

				<span class="pull-right">
					<i class="fa fa-clock-o"></i>
					<?php echo apms_date($view['date'], 'orangered'); //시간 ?>
				</span>
			</div>
		</div>
	   <?php
			if($attach_list) {
				echo '<div class="list-group font-12">'.$attach_list.'</div>'.PHP_EOL;
			}
		?>

		<!-- 학술 정보 -->
		<div class="list-group font-12">
			<span class="list-group-item col-md-12"><i class="fa fa-bookmark"></i> 연락처 : <?=$result['wr_1']?></span>
			<!-- <span class="list-group-item col-md-12"><i class="fa fa-info-circle"></i> 강의 주제 : <?=$result['wr_2']?></i></span> -->
			<span class="list-group-item col-md-12"><i class="fa fa-graduation-cap"></i> 학습공간 지역 : <?=$result['wr_3']?></i></span>
			<span class="list-group-item col-md-12"><i class="fa fa-graduation-cap"></i> 학습공간 주소 : <?=$sh_2 ?></i></span>
			
			<span class="col-md-12"><div id="map" style="height: 400px; margin-top: 20px"></div></span>
			<div class="clearfix"></div>
		</div>
		<!-- 학술 정보 END -->
	</div>

	<?php if ($is_torrent) echo apms_addon('torrent-basic'); // 토렌트 파일정보 ?>

	<?php
		// 이미지 상단 출력
		$v_img_count = count($view['file']);
		if($v_img_count && $is_img_head) {
			echo '<div class="view-img">'.PHP_EOL;
			for ($i=0; $i<=count($view['file']); $i++) {
				if ($view['file'][$i]['view']) {
					echo get_view_thumbnail($view['file'][$i]['view']);
				}
			}
			echo '</div>'.PHP_EOL;
		}
	 ?>

	<div class="view-content">
		<hr>
		<?php echo get_view_thumbnail($view['content']); ?>
		<hr>
		<?php echo get_view_thumbnail($view['wr_1']); ?>
	</div>

	<?php
		// 이미지 하단 출력
		if($v_img_count && $is_img_tail) {
			echo '<div class="view-img">'.PHP_EOL;
			for ($i=0; $i<=count($view['file']); $i++) {
				if ($view['file'][$i]['view']) {
					echo get_view_thumbnail($view['file'][$i]['view']);
				}
			}
			echo '</div>'.PHP_EOL;
		}
	?>

	<?php if ($good_href || $nogood_href) { ?>
		<div class="print-hide view-good-box">
			<?php if ($good_href) { ?>
				<span class="view-good">
					<a href="#" onclick="apms_good('<?php echo $bo_table;?>', '<?php echo $wr_id;?>', 'good', 'wr_good'); return false;">
						<b id="wr_good"><?php echo number_format($view['wr_good']) ?></b>
						<br>
						<i class="fa fa-thumbs-up"></i>
					</a>
				</span>
			<?php } ?>
			<?php if ($nogood_href) { ?>
				<span class="view-nogood">
					<a href="#" onclick="apms_good('<?php echo $bo_table;?>', '<?php echo $wr_id;?>', 'nogood', 'wr_nogood'); return false;">
						<b id="wr_nogood"><?php echo number_format($view['wr_nogood']) ?></b>
						<br>
						<i class="fa fa-thumbs-down"></i>
					</a>
				</span>
			<?php } ?>
		</div>
		<p></p>
	<?php } ?>

	<?php if ($is_tag) { // 태그 ?>
		<p class="view-tag font-12"><i class="fa fa-tags"></i> <?php echo $tag_list;?></p>
	<?php } ?>

	<div class="print-hide view-icon">
		<div class="pull-right">
			<div class="form-group">
				<button onclick="apms_print();" class="btn btn-black btn-xs"><i class="fa fa-print"></i> <span class="hidden-xs">프린트</span></button>
				<?php if ($scrap_href) { ?>
					<!--<a href="<?php echo $scrap_href;  ?>" target="_blank" class="btn btn-black btn-xs" onclick="win_scrap(this.href); return false;"><i class="fa fa-tags"></i> <span class="hidden-xs">스크랩</span></a>-->
				<?php } ?>
				<?php if ($is_shingo) { ?>
					<button onclick="apms_shingo('<?php echo $bo_table;?>', '<?php echo $wr_id;?>');" class="btn btn-black btn-xs"><i class="fa fa-bell"></i> <span class="hidden-xs">신고</span></button>
				<?php } ?>
				<?php if ($is_admin) { ?>
					<?php if ($view['is_lock']) { // 글이 잠긴상태이면 ?>
						<button onclick="apms_shingo('<?php echo $bo_table;?>', '<?php echo $wr_id;?>', 'unlock');" class="btn btn-black btn-xs"><i class="fa fa-unlock"></i> <span class="hidden-xs">해제</span></button>
					<?php } else { ?>
						<!--<button onclick="apms_shingo('<?php echo $bo_table;?>', '<?php echo $wr_id;?>', 'lock');" class="btn btn-black btn-xs"><i class="fa fa-lock"></i> <span class="hidden-xs">잠금</span></button>-->
					<?php } ?>
				<?php } ?>
			</div>
		</div>
		<div class="pull-left">
			<div class="form-group">
				<?php include_once(G5_SNS_PATH."/view.sns.skin.php"); // SNS ?>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>

	<?php if($is_signature) echo apms_addon('sign-basic'); // 회원서명 ?>

	<h3 class="view-comment">댓글</h3>
	<?php include_once('./view_comment.php'); ?>

	<div class="clearfix"></div>

	<div class="print-hide view-btn text-right">
		<div class="btn-group">
			<?php if ($prev_href) { ?>
				<a href="<?php echo $prev_href ?>" class="btn btn-black btn-sm" title="이전글">
					<i class="fa fa-chevron-circle-left"></i><span class="hidden-xs"> 이전</span>
				</a>
			<?php } ?>
			<?php if ($next_href) { ?>
				<a href="<?php echo $next_href ?>" class="btn btn-black btn-sm" title="다음글">
					<i class="fa fa-chevron-circle-right"></i><span class="hidden-xs"> 다음</span>
				</a>
			<?php } ?>
			<?php if ($copy_href) { ?>
				<a href="<?php echo $copy_href ?>" class="btn btn-black btn-sm" onclick="board_move(this.href); return false;" title="복사">
					<i class="fa fa-clipboard"></i><span class="hidden-xs"> 복사</span>
				</a>
			<?php } ?>
			<?php if ($move_href) { ?>
				<a href="<?php echo $move_href ?>" class="btn btn-black btn-sm" onclick="board_move(this.href); return false;" title="이동">
					<i class="fa fa-share"></i><span class="hidden-xs"> 이동</span>
				</a>
			<?php } ?>
			<?php if ($delete_href) { ?>
				<a href="<?php echo $delete_href ?>" class="btn btn-black btn-sm" title="삭제" onclick="del(this.href); return false;">
					<i class="fa fa-times"></i><span class="hidden-xs"> 삭제</span>
				</a>
			<?php } ?>
			<?php if ($update_href) { ?>
				<a href="<?php echo $update_href ?>" class="btn btn-black btn-sm" title="수정">
					<i class="fa fa-plus"></i><span class="hidden-xs"> 수정</span>
				</a>
			<?php } ?>
			<?php if ($search_href) { ?>
				<a href="<?php echo $search_href ?>" class="btn btn-black btn-sm">
					<i class="fa fa-search"></i><span class="hidden-xs"> 검색</span>
				</a>
			<?php } ?>
			<a href="<?php echo $list_href ?>" class="btn btn-black btn-sm">
				<i class="fa fa-bars"></i><span class="hidden-xs"> 목록</span>
			</a>
			<?php if ($reply_href) { ?>
				<a href="<?php echo $reply_href ?>" class="btn btn-black btn-sm">
					<i class="fa fa-comments"></i><span class="hidden-xs"> 답변</span>
				</a>
			<?php } ?>
			<?php if ($write_href) { ?>
				<a href="<?php echo $write_href ?>" class="btn btn-color btn-sm">
					<i class="fa fa-pencil"></i><span class="hidden-xs"> 글쓰기</span>
				</a>
			<?php } ?>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<script>
function board_move(href){
	window.open(href, "boardmove", "left=50, top=50, width=500, height=550, scrollbars=1");
}
$(function() {
	$("a.view_image").click(function() {
		window.open(this.href, "large_image", "location=yes,links=no,toolbar=no,top=10,left=10,width=10,height=10,resizable=yes,scrollbars=no,status=no");
		return false;
	});
	<?php if ($board['bo_download_point'] < 0) { ?>
	$("a.view_file_download").click(function() {
		if(!g5_is_member) {
			alert("다운로드 권한이 없습니다.\n회원이시라면 로그인 후 이용해 보십시오.");
			return false;
		}

		var msg = "파일을 다운로드 하시면 포인트가 차감(<?php echo number_format($board['bo_download_point']) ?>점)됩니다.\n\n포인트는 게시물당 한번만 차감되며 다음에 다시 다운로드 하셔도 중복하여 차감하지 않습니다.\n\n그래도 다운로드 하시겠습니까?";

		if(confirm(msg)) {
			var href = $(this).attr("href")+"&js=on";
			$(this).attr("href", href);

			return true;
		} else {
			return false;
		}
	});
	<?php } ?>

	$(document).on('click', '.list-group-item:not(.cutText) .more', function(e){
		$(this).closest('.list-group-item').addClass('cutText').find('.more').text('more');
	})

	$(document).on('click', '.cutText .more', function(e){
		$(this).closest('.list-group-item').removeClass('cutText').find('.more').text('close');
	})


});
</script>
<script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=470cdf781265387abbaacb13ebcd6a2a&libraries=services"></script>
<script>
    var map_button = document.getElementById('map_search');
    var coods = '';
	var mapContainer = document.getElementById('map'), // 지도를 표시할 div 
	    mapOption = {
	        center: new daum.maps.LatLng(33.450701, 126.570667), // 지도의 중심좌표
	        level: 3 // 지도의 확대 레벨
	    };  

	// 지도를 생성합니다    
	var map = new daum.maps.Map(mapContainer, mapOption); 

	// 주소-좌표 변환 객체를 생성합니다
	var geocoder = new daum.maps.services.Geocoder();

	// 주소로 좌표를 검색합니다
	geocoder.addressSearch('<?php echo $sh_2 ?>', function(result, status) {

    // 정상적으로 검색이 완료됐으면 
     if (status === daum.maps.services.Status.OK) {

        coords = new daum.maps.LatLng(result[0].y, result[0].x);

        // 결과값으로 받은 위치를 마커로 표시합니다
        var marker = new daum.maps.Marker({
            map: map,
            position: coords
        });

        // 인포윈도우로 장소에 대한 설명을 표시합니다
        var infowindow = new daum.maps.InfoWindow({
            content: '<div style="width:150px;text-align:center;padding:6px 0;"><?php echo $view['wr_subject']?></div>'
        });
        infowindow.open(map, marker);

        // 지도의 중심을 결과값으로 받은 위치로 이동시킵니다
	        map.setCenter(coords);
	    }
	});  
	$(document).ready(function() {
		on_tab1();
	});

	$(window).resize(function() {
		on_tab1();
	});


	function on_tab1() {
		var $el = $('.map_wrap');
		$el.height();
		$el.width();

		resizeMap1('map', $el.width(), $el.height());
	}

	function resizeMap1(el, ww, hh)
	{
		if ($('#'+el).length <= 0) return;

		$('#'+el).css("width", ww);
		$('#'+el).css("height", hh);

		//map.relayout();
		//map.setCenter(new daum.maps.LatLng(35.9645879, 126.9586316));
		map.setCenter(coords);
	}

	//}  
	</script>
