<?php
	$sub_menu = "200600";
	include_once('./_common.php');


	# 최고관리자만 열람 가능
	if ($is_admin != 'super') {
		alert("최고관리자만 열람 가능합니다." ,G5_ADMIN_URL);
	}

	auth_check($auth[$sub_menu], "r");

	
	# set : variables
	$SC_LL_S_DATE		= $_REQUEST["SC_LL_S_DATE"];
	$SC_LL_E_DATE		= $_REQUEST["SC_LL_E_DATE"];
	$stx				= $_REQUEST["stx"];
	$mb_name				= $_REQUEST["mb_name"];
	$page				= $_REQUEST["page"];


	# re-define : variables
	$page				= (is_numeric($page)) ? (int)$page : 1;


	# wh
	$wh = "Where 1 = 1";
	
	if ($SC_LL_S_DATE != '') {
		$wh .= " And DATE(LL.LL_WDATE) >= '" . mres($SC_LL_S_DATE) . "'";
	}
	if ($SC_LL_E_DATE != '') {
		$wh .= " And DATE(LL.LL_WDATE) <= '" . mres($SC_LL_E_DATE) . "'";
	}

	if ($stx != '') {
		$wh .= " And M.mb_id like '%" . mres($stx) . "%'";
	}

	if ($mb_name != '') {
		$wh .= " And M.mb_name like '%" . mres($mb_name) . "%'";
	}


	$sql = "Select Count(*) As cnt"
		."	From LOGIN_LOG As LL"
		."		Inner Join g5_member As M on M.mb_no = LL.mb_no"
		."	{$wh}"
		;
	$row = sql_fetch($sql);
	$total_count = $row['cnt'];

	$rows = 50;
	$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
	if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
	$from_record = ($page - 1) * $rows; // 시작 열을 구함


	$sql = "Select LL.LL_WDATE, LL.LL_WIP"
		."	, M.mb_id, M.mb_name"
		."	From LOGIN_LOG As LL"
		."		Inner Join g5_member As M on M.mb_no = LL.mb_no"
		."	{$wh}"
		."	Order By LL_IDX Desc"
		."	Limit {$from_record}, {$rows} "
		;
	$row = sql_query($sql);


	$g5[title] = "관리회원 접속로그";
	include_once(G5_ADMIN_PATH.'/admin.head.php');
	include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');
?>

<div class="sch_box">
	<form id="frmSch" name="frmSch" method="get" action="<?php echo($_SERVER['PHP_SELF'])?>">
	<input type="submit" style="width:1px;height:1px;position:absolute;top:-10000px;left:-10000px" />
	
	<div class="sch_opt1">
		<div class="sch_opt2">
			<span class="sch_term" data-tit="접속일자">
				<input type="text" id="SC_LL_S_DATE" name="SC_LL_S_DATE" class="nx-ips1" value="<?php echo($SC_LL_S_DATE)?>" style="max-width:140px;">

				<span class="tilde">~</span>

				<input type="text" id="SC_LL_E_DATE" name="SC_LL_E_DATE" class="nx-ips1" value="<?php echo($SC_LL_E_DATE)?>" style="max-width:140px;">
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

	</form>
</div>

<table border="0" cellpadding="0" cellspacing="0" class="nx-t-list1">
	<colgroup>
		<col width="100">
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
			<th>접속일자</th>
			<th>접속 IP</th>
		</tr>
	</thead>
	<tbody>
		<?php
		if (sql_num_rows($row) <= 0) {
			echo '<tr><td colspan="5">선택된 기간동안 관리자 접속 이력이 없습니다.</td></tr>';
		}
		else {
			$s = 0;
			while ($rs1 = sql_fetch_array($row)) {
				?>
		<tr>
			<td><?php echo($total_count - $from_record - $s)?></td>
			<td><?php echo(F_hsc($rs1['mb_id']))?></td>	
			<td><?php echo(F_hsc($rs1['mb_name']))?></td>	
			<td><?php echo($rs1['LL_WDATE'])?></td>
			<td><?php echo(long2ip(sprintf("%d", $rs1['LL_WIP'])))?></td>	
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

	$pagelist = get_paging(50, $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?".$qstr);
	echo $pagelist;
?>

<script>
//<![CDATA[
$(function(){
    $("#SC_LL_S_DATE, #SC_LL_E_DATE").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "2016:c+5" });
});
//]]>
</script>

<?php
	//마지막 인클루드
	include_once G5_ADMIN_PATH."/admin.tail.php";
?>
