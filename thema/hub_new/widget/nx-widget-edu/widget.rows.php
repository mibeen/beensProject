<?php
if (!defined('_GNUBOARD_')) exit; //개별 페이지 접근 불가

global $g5;


# 사업관리 카테고리 GET
$sql      = "SELECT EC_IDX, EC_NAME FROM NX_EVENT_CATE ORDER By EC_SEQ Asc, EC_IDX DESC";
$row      = sql_query($sql);
$cnt      = sql_num_rows($row);

$category = "<li class=\"active\"><a href=\"#taball\">전체보기</a></li>";
$arr_cate = array('all'); //카테고리 li를 담는 박스.
$arr_cnt  = array(
				'all' => array(
					'cnt' => 0
				)
			); //li의 개수를 담는 박스
$box      = array(); //li들을 담는 박스
$limit    = ($wset['rows'] * 2);

if ($limit == '' || $limit < 1) $limit = 8;



#카테고리 넣기
while($rs1 = sql_fetch_array($row)){
	$category .= "<li><a href=\"#tab".$rs1['EC_IDX']."\">".$rs1['EC_NAME']."</a></li>";
	$arr_cnt[$rs1['EC_IDX']]['cnt'] = 0;
	$arr_cate[] = $rs1['EC_IDX'];
}



#이미지 디렉토리와, 썸네일 대상 디렉토리
$s_path   = G5_PATH . "/data/file/NX_EVENT_MASTER"; 
$t_path   = G5_PATH . "/data/file/NX_EVENT_MASTER"; 
$board_file_table = $g5['board_file_table'];

// 추출하기
$wset['image'] = 1; //이미지글만 추출
// $list          = apms_board_rows($wset);
$sql = "SELECT EM.EM_IDX, EM.EC_IDX, EM.EP_IDX, EM.EM_TITLE, EM.EM_S_DATE, EM.EM_E_DATE, EM.EM_S_TIME, EM.EM_E_TIME, EM.EM_JOIN_MAX
				, EM.EM_JOIN_S_DATE, EM.EM_JOIN_E_DATE
				, FL.bf_file, bf_source
			From NX_EVENT_MASTER As EM
				Left Join {$board_file_table} As FL On FL.bo_table = 'NX_EVENT_MASTER' And FL.wr_id = EM.EM_IDX And FL.bf_no = '0'
			Where EM.EM_DDATE is null And EM.EM_OPEN_YN = 'Y'
			Order By EM.EM_IDX Desc ";
$row2 = sql_query($sql);
while( $rs2 = sql_fetch_array($row2) ){


	# Create Thumbnail
    $thumb = thumbnail($rs2['bf_file'], $s_path, $t_path, 247, 247, false); 


	# Set status
	if ($rs2['EM_JOIN_S_DATE'] <= date('Y-m-d H:i') && $rs2['EM_JOIN_E_DATE'] >= date('Y-m-d H:i')) {
		$status = "<span class=\"receipt\">접수중</span>";
	}
	else if ($rs2['EM_JOIN_E_DATE'] < date('Y-m-d H:i')){
		$status = "<span class=\"end\">마감</span>";
	}
	else {
		$status = "<span class=\"ready\">준비중</span>";
	}


	# Create Element
	$rs_box = "
			<li>
				" . (($rs2['EP_IDX'] != "") ? "<span class=\"nx-label label-project\">공모</span>" : "") . "
				<a href=\"/evt/evt.read.php?&EM_IDX=".$rs2['EM_IDX']."\">
					<div class=\"img-box\">
						" . ((!is_null($thumb)) ? '<img src="/data/file/NX_EVENT_MASTER/'.$thumb.'" alt="'.clean_xss_tags($rs1['bf_source']).'" class="img" />' : '<img src="'.G5_URL.'/img/no_img.jpg'.'" alt="'.clean_xss_tags($rs1['bf_source']).'" class="img" />') . "
					</div>
					<div class=\"status\">".$status."</div>
					<div class=\"desc\">
						<p class=\"title\">".$rs2['EM_TITLE']."</p>
						<p class=\"limit\">인원 : <span>".(($rs2['EM_JOIN_MAX'] > 0) ? number_format($rs2['EM_JOIN_MAX']).'명' : '제한없음')."</span></p>
						<p class=\"date\">날짜 : <span>".substr($rs2['EM_JOIN_S_DATE'], 0, 10)." ~ ".substr($rs2['EM_JOIN_E_DATE'], 0, 10)."</span></p>
					</div>
				</a>
			</li>";



	# Insert Element to box['all'] array
	if($arr_cnt['all']['cnt'] < $limit){

		$box['all'] .= $rs_box;
		$arr_cnt['all']['cnt'] += 1;

	}
	$arr_cnt['all']['total'] += 1; //total



	# Insert Element to box[$var] array
	if($arr_cnt[$rs2['EC_IDX']]['cnt'] < $limit){

		$box[$rs2['EC_IDX']] .= $rs_box;
		$arr_cnt[$rs2['EC_IDX']]['cnt'] += 1;

	}
	$arr_cnt[$rs2['EC_IDX']]['total'] += 1; //total



} // row2 loop end
# Cache out
unset($s_path, $t_path, $thumb);


# UL그룹을 만들어서 박스를 뿌린다.
$ul_gr = "";
foreach($arr_cate as $var)
{

	$active = $var == 'all' ? 'active' : '';
	$style  = "width: 100%; text-align: center; height: auto; padding: 30px 0; background: none;";

	if(strlen($box[$var]) < 1){
		$box[$var] = "<li style=\"".$style."\">행사가 없습니다.</li>";
	}
	$ul_gr .= "<ul id=\"tab".$var."\" class=\"".$active."\" max=\"".$arr_cnt[$var]['total']."\">
				".$box[$var]."
			  </ul>";
	
	if($arr_cnt[$var]['total'] > $limit && $arr_cnt[$var]['cnt'] <= $limit ){
		//해당하는 그룹에 자식이 1명이라도 있으면.
		$ul_gr .= "<a href=\"javascript:;\" class=\"more\" data=\"".$var."\"><span>더보기</span> <i class=\"fa fa-spinner fa-pulse fa-3x fa-fw\"></i><span class=\"sr-only\">Loading...</span></a>";
	}
}

$rank          = apms_rank_offset($wset['rows'], $wset['page']);

// 날짜
$is_date       = (isset($wset['date']) && $wset['date']) ? true : false;
$is_dtype      = (isset($wset['dtype']) && $wset['dtype']) ? $wset['dtype'] : 'm.d';
$is_dtxt       = (isset($wset['dtxt']) && $wset['dtxt']) ? true : false;

// 새글
$is_new        = (isset($wset['new']) && $wset['new']) ? $wset['new'] : 'red'; 

// 분류
$is_cate       = (isset($wset['cate']) && $wset['cate']) ? true : false;

// 동영상아이콘
$is_vicon      = (isset($wset['vicon']) && $wset['vicon']) ? '' : '<i class="fa fa-play-circle-o post-vicon"></i>'; 

// 스타일
$is_center     = (isset($wset['center']) && $wset['center']) ? ' text-center' : ''; 
$is_bold       = (isset($wset['bold']) && $wset['bold']) ? true : false; 

// 그림자
$shadow_in     = '';
$shadow_out = (isset($wset['shadow']) && $wset['shadow']) ? apms_shadow($wset['shadow']) : '';
if($shadow_out && isset($wset['inshadow']) && $wset['inshadow']) {
	$shadow_in = '<div class="in-shadow">'.$shadow_out.'</div>';
	$shadow_out = '';	
}

//속도
$speed = isset($wset['speed']) && (int) $wset['speed'] > 0 ? (int) $wset['speed'] : 000;
$is_autoplay = (isset($wset['auto']) && $wset['auto'] > 0) ? 1 : 0;
$autoplay_interval = (isset($wset['auto']) && ($wset['auto'] > 0 || $wset['auto'] == "0")) ? $wset['auto'] : 3000;


$rows = (int)$wset['rows'];
?>


<?php #echo $widget_path; ?>
<link rel="stylesheet" href="<?php echo(G5_PLUGIN_URL)?>/OwlCarousel2/assets/owl.carousel.min.css" />
<script src="<?php echo(G5_PLUGIN_URL)?>/OwlCarousel2/owl.carousel.min.js"></script>
<style>
	#<?php echo $widget_id;?> { padding-top: 30px; }
	/*.nx-tab:after{ content:'';clear: both;display: block; }*/
	.nx-tab{ width: 100%; background: #dbdee2; padding-top: 20px; border-top: 1px solid #DBDBDB; position: relative; }
	/*.nx-tab:after{ content:'';clear: both;display: block; width: 100%; height: 6px; background: url(/thema/hub/assets/images/tab_bg.jpg) 50% 100% repeat-x; position: absolute; bottom: -6px; left: 0; }*/
	.nx-tab li{ display: inline-block; padding: 0 33px; }
    .nx-tab li:hover a,
    .nx-tab li.active a{color: #3F9DE3;}
    .nx-tab li:hover a:after,
    .nx-tab li.active a:after{width: 100%; margin-left: -50%; left: 50%;}
	.nx-tab a{ padding: 13px 5px 7px; display: block; text-align: center; font-size: 16px; color: #666; position: relative; text-align: center;}
	.nx-tab a:after{content:'';clear: both;display: block;position: absolute; left: 50%; bottom: 0px; width: 0%; margin-left: 0px; height: 2px; background: #3F9DE3; transition: all .2s ease-in;}
	.nx-tab button{background: none; border: none; position: absolute; top: 50%;margin-top: -10px; opacity: 0;}
	.nx-tab button.active{opacity: 1;}
	.nx-tab .tab-left{left: 30px; }
	.nx-tab .tab-right{right: 30px; }

	.nx-tab-wrap{max-width: 1200px; margin: 0 auto; position: relative; text-align: center; overflow: hidden;}
	.nx-tab-category{width: 90%; margin: 0 auto; overflow: hidden;}

	.nx-tab-group{ background: #DBDEE1; padding: 35px 0 19px; }
	.nx-tab-group ul{ max-width: 1200px; margin: 0 auto; display: none;}
	.nx-tab-group ul:not(.active) + a.more{display: none;}
	.nx-tab-group ul.active{display: block;}
	.nx-tab-group ul:after{content:'';clear: both;display: block;}

	<?php /** width: 286px; */ ?>
	.nx-tab-group li{ position: relative; overflow: hidden; float: left; width: calc((100% - <?php echo(($rows - 1) * 20); ?>px) / <?php echo($rows); ?>); margin-left: 20px; margin-bottom: 20px; background: #FFF; }
	.nx-tab-group li:nth-child(<?php echo($rows); ?>n-<?php echo($rows-1); ?>){margin-left: 0;}
	.nx-tab-group li:hover .img-box img{transform: scale(1.1);}

	.nx-tab-group li .nx-label {position: absolute; top: -25px; left: 50%; z-index: 2; width: 50px; height: 50px; margin-left: -25px; padding-top: 25px; line-height: 20px; text-align: center; color: #fff; border-radius: 50%;}
	.nx-tab-group li .nx-label.label-project {background: #0090FF;}

	.nx-tab-group li a {display: block; padding: 19px;}
	.nx-tab-group li a .status span { display: inline-block; padding: 2px 5px; font-size: 14px; color: #fff; border-radius: 4px }
	.nx-tab-group li a .status .ready { background: #8dd25b }
	.nx-tab-group li a .status .receipt { background: #3f9de3 }
	.nx-tab-group li a .status .end { background: #f95c5c }

	.nx-tab-group a.more i.fa{display: none;}
	.nx-tab-group a.more.processing i.fa{display: inline-block;}
	.nx-tab-group a.more.processing > span{display: none;}

	<?php /* .nx-tab-group .img-box{width: 247px; height: 247px; overflow: hidden;} */ ?>
	.nx-tab-group .img-box{ overflow: hidden; text-align: center; position: relative; padding-bottom: 100%; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; }
	.nx-tab-group .img-box img{max-width: 100%; max-height: 100%; transition: all .3s ease-in-out; position: absolute; top: 0; left: 0; width: 100%;}

	.nx-tab-group p{font-size: 15px; line-height: 1.3; word-break: keep-all;}

	.nx-tab-group .title{color: #000; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;}
	.nx-tab-group .limit, .date{color: #888;}
	.nx-tab-group .limit span{color: #666;}
	.nx-tab-group .date span{ color: #3F9DE3;}
	.nx-tab-group .status{text-align: right; padding: 5px 0 3px;}
	.nx-tab-group .more{color: #3F9DE3; text-align: center; display: block; margin: 0 auto; font-size: 16px;}

	<?php $rows = ($rows > 0) ? $rows - 1 : $rows; ?>
	@media screen and (max-width: 1220px) {
		.nx-tab-group ul{max-width: 895px;}
		.nx-tab-group li{ width: calc((100% - <?php echo(($rows - 1) * 20); ?>px) / <?php echo($rows); ?>); }
		.nx-tab-group li:nth-child(<?php echo $rows + 1; ?>n-<?php echo $rows; ?>){margin-left: 20px;}
		.nx-tab-group li:nth-child(<?php echo $rows; ?>n-<?php echo $rows-1; ?>){margin-left: 0;}
	}


	@media screen and (max-width: 991px) {
		.nx-tab-group{padding-left: 10px; padding-right: 10px;}
		.nx-tab .tab-left{left: 5px;}
		.nx-tab .tab-right{right: 5px;}
		.nx-tab li{padding: 0 5px;}
		.nx-tab a{font-size: 15px;}
	}


	@media screen and (max-width: 936px) {
		.nx-tab-group ul{max-width: 590px;}
		.nx-tab-group ul.active {padding: 0 12px;}
		.nx-tab-group li {width: 47%; margin-left: 6%; height: auto; margin-bottom: 35px; }
		.nx-tab-group li:nth-child(<?php echo $rows + 1; ?>n-<?php echo $rows; ?>){margin-left: 6%;}
		.nx-tab-group li:nth-child(2n-1) {margin-left: 0px;}
		.nx-tab-group li:nth-child(2n):after {content:'';clear: both;display: block;}
		.nx-tab-group .img-box{width: 100%; height: auto;}
		.nx-tab-group p{overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-size: 14px;}
	}


	@media screen and (max-width: 500px) {
		.nx-tab-group ul.active{ padding: 0 12px; }
		.nx-tab-group li { margin-bottom: 25px; }
		.nx-tab-group p { font-size: 11px; }
	}
</style>


<section class="row" id="<?php echo $widget_id;?>">
	<div class="nx-main-tit black" style="padding-bottom: 15px;">
		<h3 class="section-title">
			경기도민과 함께 하는 진흥원
		</h3>
		<p class="section-sub-title">경기도평생교육진흥원에서 모집하는 행사와 교육 내용을 확인하시고 직접 신청하세요.</p>
	</div>
	<div class="nx-tab">
		<div class="nx-tab-wrap">

	  	<button class="tab-left"><img src="/thema/hub/assets/images/tab_arrow.png" alt="이전"></button>
	  	<button class="tab-right rotate-180"><img src="/thema/hub/assets/images/tab_arrow.png" alt="다음"></button>
	  	<div class="nx-tab-category">
		  	<ul>
		  		<?php echo $category ?>
		  	</ul>
		</div>

		<style>
			.nx-tab-category .bx-wrapper{
				box-shadow: none;
				border: none;
				margin: 0 ;
				background: transparent;
			}
		</style>

		</div>
	</div>

	<div id="evt-tab-group" class="nx-tab-group">
		<?php echo $ul_gr; ?>		  	
	</div>

</section>

<script>
  (function(){

  	var ul = $('.nx-tab-category');
  	var li = ul.find('li');
  	var leng = li.length;

  	$(document).on('click', '.nx-tab-category a[href^="#"]', function(e){
  		e.preventDefault();
  		var href = $(this).attr('href');
  		$(this).closest('li').addClass('active').siblings('li').removeClass('active');
  		$('#evt-tab-group').find('ul' + href ).addClass('active')
  			.siblings('ul').removeClass('active');
  	})

  	$(document).on('click', '#evt-tab-group .more', function(e){
  		e.preventDefault();
  		var id = $('#evt-tab-group ul.active').attr('id');
  		var leng = $('#evt-tab-group ul.active').find('li').length;
  		more(id, leng);
  	})

  	function more($id, $leng){

  		$('#evt-tab-group ul.active + a.more').addClass('processing');

  		var senddata = {'id' : $id, 'leng' : $leng, 'limit' : '<?php echo ($wset['rows'] * 2) ?>'};
  		// id는 해당하는 table이나 group, leng은 limit에 사용된다. 한번에 8개씩 불러온다. (limit $leng, 8);
  		$.ajax({
  			url : '/thema/hub_new/main/getli.php',
  			method : 'POST',
  			data : senddata,
  			success : function(data){

  				if(data == 'nodata'){
  					alert('값이 없습니다');
  					$('#evt-tab-group ul.active + a.more').removeClass('processing');
  					return false;
  				}
  				//console.log(data)
  				appendHtml(data);
  				$('#evt-tab-group ul.active + a.more').removeClass('processing');
  				//li를 집어 넣는다.
  			},
  			error : function(msg){
  				console.log(msg);
  				$('#evt-tab-group ul.active + a.more').removeClass('processing');
  			}
  		})
  	}

  	function appendHtml(data){
  		//data는 html 형태의 li들이 들어있다.
  		$('#evt-tab-group ul.active').append(data);
  		chk_max();

  		//만약 more를 누르고 유저가 탭을 바꿨다면 어떡하지?? 값을 하나 더 넘기면 되는데, 어떻게 가져올까?
  	}

  	function chk_max(){
  		var cur_ul = $('#evt-tab-group ul.active');
  		var cur_li = cur_ul.find('li').length;
  		var max    = cur_ul.attr('max');

  		if(cur_li >= max){
  			//cur_ul.next('.processing').remove();
  			$('.processing').remove();
  		}
  	}

  })();
  
</script>

<?php if($setup_href) { ?>
<div class="btn-wset text-center p10">
	<a href="<?php echo $setup_href;?>" class="win_memo">
		<span class="text-muted font-12"><i class="fa fa-cog"></i> 위젯설정</span>
	</a>
</div>
<?php } ?>