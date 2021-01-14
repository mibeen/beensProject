<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/common.php';

$id    = clean_xss_tags(trim($_POST['id']));
$id    = substr($id, 3, 3);
$leng  = (int) clean_xss_tags(trim($_POST['leng']));
$limit = (int) clean_xss_tags(trim($_POST['limit']));


if ($limit == '' || $limit < 1) $limit = 8;

switch ($id) {
	case 'all':
		$where = " ";
		break;
	
	default:
		$where = " AND EM.EC_IDX in (".$id.") ";
		break;
}

# 사업
$sql = "SELECT EM.EM_IDX, EM.EC_IDX, EM.EP_IDX, EM.EM_TITLE, EM.EM_S_DATE, EM.EM_E_DATE, EM.EM_S_TIME, EM.EM_E_TIME, EM.EM_JOIN_MAX"
		."		, EM.EM_JOIN_S_DATE, EM.EM_JOIN_E_DATE"
		."		, (Select Count(EJ_IDX) From NX_EVENT_JOIN Where EM_IDX = EM.EM_IDX And EJ_DDATE is null) As CNT1"
		."		, (Select Count(EJ_IDX) From NX_EVENT_JOIN Where EM_IDX = EM.EM_IDX And EJ_DDATE is null And EJ_STATUS = '2') As CNT2"
		."		, (Select Count(EJ_IDX) From NX_EVENT_JOIN Where EM_IDX = EM.EM_IDX And EJ_DDATE is null And EJ_STATUS = '2' And (EJ_JOIN_CHK1 = 'Y' And EJ_JOIN_CHK2 = 'Y')) As CNT3"
		."		, FL.bf_file, bf_source"
		."	From NX_EVENT_MASTER As EM"
		."		Left Join {$g5['board_file_table']} As FL On FL.bo_table = 'NX_EVENT_MASTER' And FL.wr_id = EM.EM_IDX And FL.bf_no = '0'"
		."	Where EM.EM_DDATE is null And EM.EM_OPEN_YN = 'Y' {$where}"
		."	Order By EM.EM_IDX Desc"
		."	Limit {$leng}, {$limit} ";
$row = sql_query($sql);

if(!sql_num_rows($row)){
	echo "nodata";
	die();
}

#이미지 디렉토리와, 썸네일 대상 디렉토리
$s_path = G5_PATH . "/data/file/NX_EVENT_MASTER"; 
$t_path = G5_PATH . "/data/file/NX_EVENT_MASTER"; 

while($rs1 = sql_fetch_array($row)){



	# 썸네일 생성
	$thumb = thumbnail($rs1['bf_file'], $s_path, $t_path, 247, 247, true);


	# 상태 설정
	if ($rs1['EM_JOIN_S_DATE'] <= date('Y-m-d H:i') && $rs1['EM_JOIN_E_DATE'] >= date('Y-m-d H:i')) {
		$status = "<span class=\"receipt\">접수중</span>";
	}
	else if ($rs1['EM_JOIN_E_DATE'] < date('Y-m-d H:i')){
		$status = "<span class=\"end\">마감</span>";
	}
	else {
		$status = "<span class=\"ready\">준비중</span>";
	}


	echo "<li>
			" . (($rs2['EP_IDX'] != "") ? "<span class=\"nx-label label-project\">공모</span>" : "") . "
			<a href=\"/evt/evt.read.php?&EM_IDX=".$rs1['EM_IDX']."\">
				<div class=\"img-box\">
					" . ((!is_null($thumb)) ? '<img src="/data/file/NX_EVENT_MASTER/'.$thumb.'" alt="'.clean_xss_tags($rs1['bf_source']).'" class="img" />' : '<img src="'.G5_URL.'/img/no_img.jpg'.'" alt="'.clean_xss_tags($rs1['bf_source']).'" class="img" />') . "
				</div>
				<div class=\"status\">".$status."</div>
				<div class=\"desc\">
					<p class=\"title\">".$rs1['EM_TITLE']."</p>
					<p class=\"limit\">인원 : <span>".(($rs1['EM_JOIN_MAX'] > 0) ? number_format($rs1['EM_JOIN_MAX']).'명' : '제한없음')."</span></p>
					<p class=\"date\">날짜 : <span>".substr($rs1['EM_JOIN_S_DATE'], 0, 10)." ~ ".substr($rs1['EM_JOIN_E_DATE'], 0, 10)."</span></p>
				</div>
		 	</a>
		</li>";
}

die();



?>

<li><a href="">
	<div class="img-box"><img src="/thema/hub/assets/images/ban2_5.png" alt="" title=""></div>
	<div class="status"><img src="/thema/hub/assets/images/status_b.png" alt=""></div>
	<div class="desc">
		<p class="title">2017 서울지식재산센터 지식재산 집중 교육</p>
		<p class="limit">인원 : <span>20명</span></p>
		<p class="date">날짜 : <span>2017.11.10 ~ 2018.01.02</span></p>
	</div>
</a></li>

<li><a href="">
	<div class="img-box"><img src="/thema/hub/assets/images/ban2_5.png" alt="" title=""></div>
	<div class="status"><img src="/thema/hub/assets/images/status_b.png" alt=""></div>
	<div class="desc">
		<p class="title">2017 서울지식재산센터 지식재산 집중 교육</p>
		<p class="limit">인원 : <span>20명</span></p>
		<p class="date">날짜 : <span>2017.11.10 ~ 2018.01.02</span></p>
	</div>
</a></li><li><a href="">
	<div class="img-box"><img src="/thema/hub/assets/images/ban2_5.png" alt="" title=""></div>
	<div class="status"><img src="/thema/hub/assets/images/status_b.png" alt=""></div>
	<div class="desc">
		<p class="title">2017 서울지식재산센터 지식재산 집중 교육</p>
		<p class="limit">인원 : <span>20명</span></p>
		<p class="date">날짜 : <span>2017.11.10 ~ 2018.01.02</span></p>
	</div>
</a></li><li><a href="">
	<div class="img-box"><img src="/thema/hub/assets/images/ban2_5.png" alt="" title=""></div>
	<div class="status"><img src="/thema/hub/assets/images/status_b.png" alt=""></div>
	<div class="desc">
		<p class="title">2017 서울지식재산센터 지식재산 집중 교육</p>
		<p class="limit">인원 : <span>20명</span></p>
		<p class="date">날짜 : <span>2017.11.10 ~ 2018.01.02</span></p>
	</div>
</a></li><li><a href="">
	<div class="img-box"><img src="/thema/hub/assets/images/ban2_5.png" alt="" title=""></div>
	<div class="status"><img src="/thema/hub/assets/images/status_b.png" alt=""></div>
	<div class="desc">
		<p class="title">2017 서울지식재산센터 지식재산 집중 교육</p>
		<p class="limit">인원 : <span>20명</span></p>
		<p class="date">날짜 : <span>2017.11.10 ~ 2018.01.02</span></p>
	</div>
</a></li><li><a href="">
	<div class="img-box"><img src="/thema/hub/assets/images/ban2_5.png" alt="" title=""></div>
	<div class="status"><img src="/thema/hub/assets/images/status_b.png" alt=""></div>
	<div class="desc">
		<p class="title">2017 서울지식재산센터 지식재산 집중 교육</p>
		<p class="limit">인원 : <span>20명</span></p>
		<p class="date">날짜 : <span>2017.11.10 ~ 2018.01.02</span></p>
	</div>
</a></li><li><a href="">
	<div class="img-box"><img src="/thema/hub/assets/images/ban2_5.png" alt="" title=""></div>
	<div class="status"><img src="/thema/hub/assets/images/status_b.png" alt=""></div>
	<div class="desc">
		<p class="title">2017 서울지식재산센터 지식재산 집중 교육</p>
		<p class="limit">인원 : <span>20명</span></p>
		<p class="date">날짜 : <span>2017.11.10 ~ 2018.01.02</span></p>
	</div>
</a></li><li><a href="">
	<div class="img-box"><img src="/thema/hub/assets/images/ban2_5.png" alt="" title=""></div>
	<div class="status"><img src="/thema/hub/assets/images/status_b.png" alt=""></div>
	<div class="desc">
		<p class="title">2017 서울지식재산센터 지식재산 집중 교육</p>
		<p class="limit">인원 : <span>20명</span></p>
		<p class="date">날짜 : <span>2017.11.10 ~ 2018.01.02</span></p>
	</div>
</a></li>