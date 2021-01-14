<?php
	$sub_menu = "200610";
	include_once('./_common.php');


	# 최고관리자만 열람 가능
	if ($is_admin != 'super') {
		alert("최고관리자만 열람 가능합니다." ,G5_ADMIN_URL);
	}

	auth_check($auth[$sub_menu], "r");

	
	# set : variables
	$SC_PL_S_DATE		= $_REQUEST["SC_PL_S_DATE"];
	$SC_PL_E_DATE		= $_REQUEST["SC_PL_E_DATE"];
	$stx				= $_REQUEST["stx"];
	$mb_name				= $_REQUEST["mb_name"];
	$page				= $_REQUEST["page"];


	# re-define : variables
	$page				= (is_numeric($page)) ? (int)$page : 1;


	# wh
	$wh = "Where 1 = 1";
	
	if ($SC_PL_S_DATE != '') {
		$wh .= " And DATE(PL.PL_WDATE) >= '" . mres($SC_PL_S_DATE) . "'";
	}
	if ($SC_PL_E_DATE != '') {
		$wh .= " And DATE(PL.PL_WDATE) <= '" . mres($SC_PL_E_DATE) . "'";
	}

	if ($stx != '') {
		$wh .= " And M.mb_id like '%" . mres($stx) . "%'";
	}

	if ($mb_name != '') {
		$wh .= " And M.mb_name like '%" . mres($mb_name) . "%'";
	}


	$sql = "Select Count(*) As cnt"
		."	From PRIVACY_LOG As PL"
		."		Inner Join g5_member As M on M.mb_id = PL.mb_id"
		."	{$wh}"
		;
	$row = sql_fetch($sql);
	$total_count = $row['cnt'];

	$rows = 50;
	$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
	if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
	$from_record = ($page - 1) * $rows; // 시작 열을 구함


	$sql = "Select PL.PL_FILE_NAME, PL.PL_TASK, PL.PL_WDATE, PL.PL_WIP"
		."	, M.mb_id, M.mb_name"
		."	From PRIVACY_LOG As PL"
		."		Inner Join g5_member As M on M.mb_id = PL.mb_id"
		."	{$wh}"
		."	Order By PL_IDX Desc"
		."	Limit {$from_record}, {$rows} "
		;
	$row = sql_query($sql);


	$PL_FILE_NAME_STR = array(
		'/adm/member_list.php' => '회원 목록'
		, '/adm/member_list_update.php' => '회원 목록'
		, '/adm/member_form.php' => '회원 상세'
		, '/adm/member_form_update.php' => '회원 상세'
		, '/adm/evt/evt.join.list.php' => '사업관리 신청자'
		, '/adm/evt/evt.join.read.php' => '사업관리 신청자'
		, '/adm/evt/evt.attend.list.php' => '사업관리 참석자'
		, '/adm/evt/evt.attend.read.php' => '사업관리 참석자'
		, '/adm/evt/evt.attend.excel.php' => '사업관리 참석자'
		, '/adm/rent/place_rental_req_view.php' => '대관관리 예약'
		, '/adm/rent/place_rental_req_proc.php' => '대관관리 예약'
		, '/adm/newsletter/subscriber.list.php' => '뉴스레터 구독자'
		, '/adm/udong/place.req.read.php' => '우리동네 학습공간 예약'
		, '/adm/udong/req.timeline.php' => '우리동네 학습공간 전체현황'
		);
	$PL_TASK_STR = array('list' => '목록 열람', 'read' => '상세 열람', 'edit' => '수정', 'delete' => '삭제', 'excel'=>'엑셀다운로드');


	$g5[title] = "개인정보 접근이력";
	include_once(G5_ADMIN_PATH.'/admin.head.php');
	include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');
?>

<div class="sch_box">
	<form id="frmSch" name="frmSch" method="get" action="<?php echo($_SERVER['PHP_SELF'])?>">
	<input type="submit" style="width:1px;height:1px;position:absolute;top:-10000px;left:-10000px" />
	
	<div class="sch_opt1">
		<div class="sch_opt2">
			<span class="sch_term" data-tit="접속일자">
				<input type="text" id="SC_PL_S_DATE" name="SC_PL_S_DATE" class="nx-ips1" value="<?php echo($SC_PL_S_DATE)?>" style="max-width:140px;">

				<span class="tilde">~</span>

				<input type="text" id="SC_PL_E_DATE" name="SC_PL_E_DATE" class="nx-ips1" value="<?php echo($SC_PL_E_DATE)?>" style="max-width:140px;">
			</span>

			<span class="sch_ipt wm2" data-tit="아이디">
				<input type="text" id="stx" name="stx" class="nx-ips1" value="<?php echo(F_hsc($stx))?>" />
			</span>

			<span class="sch_ipt wm2" data-tit="이름">
				<input type="text" id="mb_name" name="mb_name" class="nx-ips1" value="<?php echo(F_hsc($mb_name))?>" />
			</span>
		</div>
	</div>

	<a href="javascript:void(0);" onclick="$('#frmSch').submit();" class="btn_sch"><span class="ico_search2"></span></a>
	<a href="javascript:void(0)" onclick="excel();" class="nx-btn-b1">엑셀다운로드</a>
	</form>
</div>

<table border="0" cellpadding="0" cellspacing="0" class="nx-t-list1">
	<colgroup>
		<col width="100">
		<col width="">
		<col width="">
		<col width="">
		<col width="">
		<col width="">
		<col width="">
	</colgroup>
	<thead>
		<tr>
			<th>번호</th>
			<th>아이디</th>
			<th>이름</th>
			<th>작업위치</th>
			<th>수행작업</th>
			<th>접속일자</th>
			<th>접속 IP</th>
		</tr>
	</thead>
	<tbody>
		<?php
		if (sql_num_rows($row) <= 0) {
			echo '<tr><td colspan="7">검색된 개인정보 접근이력이 없습니다.</td></tr>';
		}
		else {
			$s = 0;
			while ($rs1 = sql_fetch_array($row)) {
				?>
		<tr>
			<td><?php echo($total_count - $from_record - $s)?></td>
			<td><?php echo(F_hsc($rs1['mb_id']))?></td>	
			<td><?php echo(F_hsc($rs1['mb_name']))?></td>	
			<td><?php echo(F_hsc($PL_FILE_NAME_STR[$rs1['PL_FILE_NAME']]))?></td>	
			<td><?php echo(F_hsc($PL_TASK_STR[$rs1['PL_TASK']]))?></td>	
			<td><?php echo($rs1['PL_WDATE'])?></td>
			<td><?php echo(long2ip(sprintf("%d", $rs1['PL_WIP'])))?></td>	
		</tr>
				<?php
				$s++;
			}
		}
		?>
	</tbody>
</table>

<?php
	$qstr .= "&amp;page=";

	$pagelist = get_paging(10, $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?".$qstr);
	echo $pagelist;
?>

<script>
//<![CDATA[
$(function(){
    $("#SC_PL_S_DATE, #SC_PL_E_DATE").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "2016:c+5" });
});
//]]>
function excel(){
	window.location.href = 'privacy_log.excel.php?SC_PL_S_DATE='+$('#SC_PL_S_DATE').val()+'&SC_PL_E_DATE='+$('#SC_PL_E_DATE').val()+'&stx='+$('#stx').val()+'&mb_name='+$('#mb_name').val();
}
</script>

<?php
	//마지막 인클루드
	include_once G5_ADMIN_PATH."/admin.tail.php";
?>
