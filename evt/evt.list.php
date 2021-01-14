<?php
	include_once("./_common.php");
	include_once(G5_LIB_PATH.'/apms.thema.lib.php');
	include_once(G5_LIB_PATH.'/thumbnail.lib.php'); //썸네일 라이브러리 로딩


	$g5['title'] = $fm['fm_subject'];


	include_once('../bbs/_head.php');


	# set : variables
	$mode = $_GET['mode'];
	$ord = $_GET['ord'];
	$scate = $_GET['scate'];
	$stx = $_GET['stx'];
	
	# 웹취약점 조치 sdkim 20200714 
	$mode = strip_tags(preg_replace("/(<(script|style)\b[^>]*>).*?(<\/\2>)/is", "", $mode));
	$mode = trim(strip_tags($mode));
    $ord = strip_tags(preg_replace("/(<(script|style)\b[^>]*>).*?(<\/\2>)/is", "", $ord));
	$ord = trim(strip_tags($ord));
	$scate = strip_tags(preg_replace("/(<(script|style)\b[^>]*>).*?(<\/\2>)/is", "", $scate));
	$scate = trim(strip_tags($scate));
	$stx = strip_tags(preg_replace("/(<(script|style)\b[^>]*>).*?(<\/\2>)/is", "", $stx));
	$stx = trim(strip_tags($stx));
    
	# re-define
	$ord = (in_array($ord, array('1a','1d','2a','2d','3a','3d'))) ? (string)$ord : '1d';


	$qstr = '';


	# wh
	$wh = "Where EM.EM_DDATE is null And EM.EM_OPEN_YN = 'Y'";

	if ($scate != '') {
		$_t = explode('|', $scate);
		$_s = array();
		for($i = 0; $i < Count($_t); $i++) {
			if ((int)$_t[$i] <= 0) continue;

			$_s[] = $_t[$i];
		}

		if (Count($_s) > 0) {
			$wh .= " And EM.EC_IDX in('" . implode("','", $_s) . "')";
			$qstr .= '&amp;scate='.urlencode($scate);
		}
	}
	if ($stx != '') {
		$wh .= " And EM.EM_TITLE like '%" . mres($stx) . "%'";
		$qstr .= '&amp;stx='.urlencode($stx);
	}


	# ord
	switch ($ord) {
		case '1a':	# 등록 asc
			$_ord = ' Order By EM.EM_IDX Asc';
			break;
		case '1d':	# 등록 desc
			$_ord = ' Order By EM.EM_IDX Desc';
			break;

		case '2a':	# 행사 asc
			$_ord = ' Order By EM.EM_S_DATE Asc, EM.EM_IDX Desc';
			break;
		case '2d':	# 행사 desc
			$_ord = ' Order By EM.EM_S_DATE Desc, EM.EM_IDX Desc';
			break;

		case '3a':	# 이름 asc
			$_ord = ' Order By binary(EM.EM_TITLE) Asc, EM.EM_IDX Desc';
			break;
		case '3d':	# 이름 desc
			$_ord = ' Order By binary(EM.EM_TITLE) Desc, EM.EM_IDX Desc';
			break;
		
		default:	# default
			$_ord = ' Order By EM.EM_IDX Desc';
			break;
	}
	if (in_array($ord, array('1a','1d','2a','2d','3a','3d'))) {
		$qstr .= '&amp;ord='.urlencode($ord);
	}


	$sql = "Select Count(EM.EM_IDX) As cnt"
		."	From NX_EVENT_MASTER As EM"
		."	{$wh}"
		;
	$row = sql_fetch($sql);
	$total_count = $row['cnt'];
	
	# paging 수 조정
	$config['cf_page_rows'] = 24;
	if ($_COOKIE['evt_rows'] != '') {
		$config['cf_page_rows'] = (int)$_COOKIE['evt_rows'];
	}
	$rows = $config['cf_page_rows'];

	$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
	if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
	$from_record = ($page - 1) * $rows; // 시작 열을 구함


	$sql = "Select EM.EM_IDX, EM.EP_IDX, EM.EM_TITLE, EM.EM_S_DATE, EM.EM_E_DATE, EM.EM_S_TIME, EM.EM_E_TIME, EM.EM_JOIN_MAX"
		."		, EM.EM_JOIN_S_DATE, EM.EM_JOIN_E_DATE"
		."		, (Select Count(EJ_IDX) From NX_EVENT_JOIN Where EM_IDX = EM.EM_IDX And EJ_DDATE is null) As CNT1"
		."		, (Select Count(EJ_IDX) From NX_EVENT_JOIN Where EM_IDX = EM.EM_IDX And EJ_DDATE is null And EJ_STATUS = '2') As CNT2"
		."		, (Select Count(EJ_IDX) From NX_EVENT_JOIN Where EM_IDX = EM.EM_IDX And EJ_DDATE is null And EJ_STATUS = '2' And (EJ_JOIN_CHK1 = 'Y' And EJ_JOIN_CHK2 = 'Y')) As CNT3"
		."		, FL.bf_file, bf_source"
		."	From NX_EVENT_MASTER As EM"
		."		Left Join {$g5['board_file_table']} As FL On FL.bo_table = 'NX_EVENT_MASTER' And FL.wr_id = EM.EM_IDX And FL.bf_no = '0'"
		."	{$wh}"
		."	{$_ord}"
		."	Limit {$from_record}, {$rows} "
		;
	$row = sql_query($sql);


	$nx_page_tit = ($mode == 'project') ? '공모사업 모집정보' : '모집 정보';
?>

<p class="nx_page_tit"><?php echo($nx_page_tit)?></p>

<?php
# get : cate list
$sql = "Select EC_IDX, EC_NAME From NX_EVENT_CATE Order By EC_SEQ Asc, EC_IDX Desc";
$sdb1 = sql_query($sql);

if (sql_num_rows($sdb1) > 0) {
	/* checkbox type 검색
	echo '<ul class="cate_bx">';
	
		echo '<li>';
			echo '<span class="chk1_wrap">';
				echo '<input type="checkbox" id="EC_IDX_ALL" name="EC_IDX_ALL" class="chk1" />';
				echo '<label for="EC_IDX_ALL"><span class="chkbox"><span class="ico_check"></span></span>전체</label>';
			echo '</span>';
		echo '</li>';

		while ($srs1 = sql_fetch_array($sdb1)) {
			echo '<li>';
				echo '<span class="chk1_wrap">';
					echo '<input type="checkbox" id="EC_IDX_'.$srs1['EC_IDX'].'" name="EC_IDX" value="'.$srs1['EC_IDX'].'" class="chk1" onclick="scate.chk()" '.((strpos($scate, '|'.$srs1['EC_IDX']) !== false) ? 'checked' : '').' />';
					echo '<label for="EC_IDX_'.$srs1['EC_IDX'].'"><span class="chkbox"><span class="ico_check"></span></span>'.F_hsc($srs1['EC_NAME']).'</label>';
				echo '</span>';
			echo '</li>';
		}

	echo '</ul>';
	*/
	
	# tab type 검색
	echo '<ul class="tab1">';
	
		echo '<li class="'.(($scate == '') ? 'on' : '').'">';
			echo '<a href="javascript:void(0)" onclick="scate.link(\'\')">전체</a>';
		echo '</li>';

		while ($srs1 = sql_fetch_array($sdb1)) {
			echo '<li class="'.(($scate == $srs1['EC_IDX']) ? 'on' : '').'">';
				echo '<a href="javascript:void(0)" onclick="scate.link(\''.$srs1['EC_IDX'].'\')">'.F_hsc($srs1['EC_NAME']).'</a>';
			echo '</li>';
		}

	echo '</ul>';
}
?>

<div class="sch_bx r_mh">
	<form id="frmSch" name="frmSch" method="get" action="<?php echo($_SERVER['PHP_SELF'])?>">
	<input type="hidden" id="mode" name="mode" value="<?php echo($mode)?>" />
	<input type="submit" style="width:1px;height:1px;position:absolute;top:-10000px;left:-10000px" />
	<div class="sch_top issue">
		<div class="fL">
			<div class="ofH">
				<?php
				# 개설순 ord
				if (in_array($ord, array('1a','1d'))) {
					echo '<a href="?&mode='.$mode.'&ord=1'.(($ord == '1d') ? 'a' : 'd').'" class="btn_order '.(((in_array($ord, array('1a','1d'))) && $ord == '1a') ? 'asc' : 'desc').'">개설순<span class="blind">으로 리스트 정렬</span></a>';
				}
				else {
					echo '<a href="?&mode='.$mode.'&ord=1d" class="btn_order">개설순<span class="blind">으로 리스트 정렬</span></a>';
				}

				# 행사일순 ord
				if (in_array($ord, array('2a','2d'))) {
					echo '<a href="?&mode='.$mode.'&ord=2'.(($ord == '2d') ? 'a' : 'd').'" class="btn_order '.(((in_array($ord, array('2a','2d'))) && $ord == '2a') ? 'asc' : 'desc').'">행사일순<span class="blind">으로 리스트 정렬</span></a>';
				}
				else {
					echo '<a href="?&mode='.$mode.'&ord=2d" class="btn_order">행사일순<span class="blind">으로 리스트 정렬</span></a>';
				}

				# 이름순 ord
				if (in_array($ord, array('3a','3d'))) {
					echo '<a href="?&mode='.$mode.'&ord=3'.(($ord == '3d') ? 'a' : 'd').'" class="btn_order '.(((in_array($ord, array('3a','3d'))) && $ord == '3a') ? 'asc' : 'desc').'">이름순<span class="blind">으로 리스트 정렬</span></a>';
				}
				else {
					echo '<a href="?&mode='.$mode.'&ord=3d" class="btn_order">이름순<span class="blind">으로 리스트 정렬</span></a>';
				}
				?>
			</div>
		</div>
		<div class="fR taL">
			<?php /*<span class="dsIB mr30">
				<span class="dsIB vaM mr10">배열</span>
				<a href="" class="btn_list_type type1 on"><span class="blind">리스트 앨범형으로 보기</span></a>
				<a href="" class="btn_list_type type2"><span class="blind">리스트 게시판형으로 보기</span></a>
			</span>
			<div class="res_mv" style="height:5px;"></div>*/ ?>
			<span class="dsIB">
				<span class="dsIB vaM mr10">개수</span>
				<a href="javascript:void(0)" onclick="evt.paging('12')" class="btn_list_limit <?php if ($_COOKIE['evt_rows'] == '12') echo('on')?>"><span class="blind">리스트 </span>12개 <span class="blind">씩 보기</span></a>
				<a href="javascript:void(0)" onclick="evt.paging('24')" class="btn_list_limit <?php if (in_array($_COOKIE['evt_rows'], array('','24'))) echo('on')?>"><span class="blind">리스트 </span>24개 <span class="blind">씩 보기</span></a>
				<a href="javascript:void(0)" onclick="evt.paging('48')" class="btn_list_limit <?php if ($_COOKIE['evt_rows'] == '48') echo('on')?>"><span class="blind">리스트 </span>48개 <span class="blind">씩 보기</span></a>
			</span>
		</div>
		<div class="clearfix"></div>
	</div>
	<div class="sch_btm">
				
		<?php 
			if ( $is_admin == "super" ) {
				?>
				<div style="text-align: right; display: inline-block; ">
					<button type="button" onclick=" document.location.href = '/evt/evt.list.excel.php'; " style="background: #5eaad0; border: 0; outline: 0; color: #FFF; padding: 5px 15px;">전체 엑셀 다운로드</button>
				</div>
				<?php
			}
		?>
		<span class="sch_ipt r_mv">
			<label for="stx" class="blind">검색 입력창</label>
			<input type="text" id="stx" name="stx" value="<?php echo(F_hsc($stx))?>" title="리스트내 검색 입력창" />
			<a href="javascript:void(0);" onclick="$('#frmSch').submit();" class="sch_btn"><span class="blind">리스트 내 검색 버튼</span><span class="ico_search2"></span></a>
		</span>
	</div>

	</form>
</div>

<ul class="event_lst">
	<?php
	if (sql_num_rows($row) <= 0) {
		echo '<li class="nodata">등록된 행사가 없습니다.</li>';
	}
	else {
		while ($rs1 = sql_fetch_array($row)) {
			?>
	<li>
		<a href="evt.read.php?<?php echo($qstr)?>&EM_IDX=<?php echo($rs1['EM_IDX'])?>">
			<?php
			if ($rs1['EP_IDX'] != "") {
				?>
			<span class="nx-label label-project">공모</span>
				<?php
			}
			?>
			<div class="img_wrap1">
				<div class="img_wrap2">
					<?php
					# 썸네일 생성
					$thumb = thumbnail($rs1['bf_file'], G5_PATH."/data/file/NX_EVENT_MASTER", G5_PATH."/data/file/NX_EVENT_MASTER", 456, 456, false);
					if (!is_null($thumb)) {
						echo '<img src="/data/file/NX_EVENT_MASTER/'.$thumb.'" alt="'.F_hsc($rs1['bf_source']).'" class="img" />';
					}
					else {
						echo '<img src="'.G5_URL.'/img/no_img.jpg'.'" alt="'.F_hsc($rs1['bf_source']).'" class="img" />';
					}
					unset($thumb);
					?>
				</div>
			</div>
			<div class="status"><?php
				if ($rs1['EM_JOIN_S_DATE'] <= date('Y-m-d H:i') && $rs1['EM_JOIN_E_DATE'] >= date('Y-m-d H:i')) echo '<span class="receipt">접수중</span>';
				else if ($rs1['EM_JOIN_E_DATE'] < date('Y-m-d H:i')) echo '<span class="end">마감</span>';
				else echo '<span class="ready">준비중</span>';
			?></div>
			<p class="tit"><?php echo(F_hsc($rs1['EM_TITLE']))?></p>
			<dl class="ppl">
				<dt>인원 :</dt>
				<dd><?php echo(($rs1['EM_JOIN_MAX'] > 0) ? number_format($rs1['EM_JOIN_MAX']).'명' : '제한없음')?></dd>
			</dl>
			<dl class="date">
				<dt>일자</dt>
				<dd><?php
					echo F_hsc(str_replace('-','.', $rs1['EM_S_DATE']));
					
					# 시작일 = 종료일 일 경우 시작일만 표시
					if ($rs1['EM_S_DATE'] != $rs1['EM_E_DATE']) {
						echo ' ~ ' . F_hsc(str_replace('-', '.', $rs1['EM_E_DATE']));
					}
				?></dd>
			</dl>
		</a>
	</li>
			<?php
		}
	}
	?>
</ul>

<div class="pagenate_wrap">
	<div class="list-page text-center">
		<?php
		$prev_part_href = '';
		$next_part_href = '';
		if ($ord || $stx) {
			$patterns = array('#&amp;page=[0-9]*#', '#&amp;spt=[0-9\-]*#');

			$prev_spt = $spt - $config['cf_search_part'];
			if (isset($min_spt) && $prev_spt >= $min_spt) {
				$qstr1 = preg_replace($patterns, '', $qstr);
				$prev_part_href = './evt.list.php?'.$qstr1.'&amp;page=1';
			}

			$next_spt = $spt + $config['cf_search_part'];
			if ($next_spt < 0) {
				$qstr1 = preg_replace($patterns, '', $qstr);
				$next_part_href = './evt.list.php?'.$qstr1.'&amp;page=1';
			}
		}
		?>
		<ul class="pagination pagination-sm en">
			<?php if($prev_part_href) { ?>
				<li><a href="<?php echo $prev_part_href;?>">이전검색</a></li>
			<?php } ?>
			<?php echo apms_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, './evt.list.php?'.$qstr.'&amp;page=');?>
			<?php if($next_part_href) { ?>
				<li><a href="<?php echo $next_part_href;?>">다음검색</a></li>
			<?php } ?>
		</ul>
	</div>
</div>

<script>
//<![CDATA[
$(function() {
	$('#EC_IDX_ALL').click(function(e) {
		e.preventDefault();
		scate.all();
	});
});

var evt = {
	paging: function(v) {
		if (parseInt(v) <= 0) return;

		var cName = 'evt_rows';
		var cValue = v;
		var cDay = 1;
		var expire = new Date();
		expire.setDate(expire.getDate() + cDay);
		cookies = cName + '=' + escape(cValue) + '; path=/ ';
		if(typeof cDay != 'undefined') cookies += ';expires=' + expire.toGMTString() + ';';
		document.cookie = cookies;

		window.location.reload();
	}
}

var scate = {
	all: function() {
		window.location.href = 'evt.list.php?mode=<?php echo(urlencode($mode))?>&ord=<?php echo(urlencode($ord))?>&stx=<?php echo(urlencode($stx))?>&page=1';
	}
	, chk: function() {
		var v = '';
		$(':checkbox[name=EC_IDX]:checked').each(function(s) {
			v += '|'+$(this).val();
		});
		v = v.replace(/\|\|/g, '');

		window.location.href = 'evt.list.php?mode=<?php echo(urlencode($mode))?>&ord=<?php echo(urlencode($ord))?>&stx=<?php echo(urlencode($stx))?>&page=1&scate='+v;
	}
	, link: function(c) {
		var c = (c == '' || isNaN(c)) ? '' : parseInt(c);
		window.location.href = 'evt.list.php?mode=<?php echo(urlencode($mode))?>&ord=<?php echo(urlencode($ord))?>&stx=<?php echo(urlencode($stx))?>&page=1&scate='+c;
	}
}
//]]>
</script>

<?php 
	# 메뉴 표시에 사용할 변수 
	if ($mode == 'project') {
		$_gr_id = 'project';  // 게시판 그룹 ID - 현재 메뉴표시에 사용
		$pid = ($pid) ? $pid : 'project';   // Page ID
	}
	else {
		$_gr_id = 'gseek';  // 게시판 그룹 ID - 현재 메뉴표시에 사용
		$pid = ($pid) ? $pid : 'evtlist';   // Page ID
	}

	include "../page/inc.page.menu.php";

	include_once('../bbs/_tail.php');
?>