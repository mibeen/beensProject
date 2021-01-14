<?php
	$sub_menu = "100210";
	include_once('./_common.php');


	# 최고관리자만 열람 가능
	if ($is_admin != 'super') {
		alert("최고관리자만 열람 가능합니다." ,G5_ADMIN_URL);
	}

	
	# set : variables
	$SC_AU_S_DATE		= $_REQUEST["SC_AU_S_DATE"];
	$SC_AU_E_DATE		= $_REQUEST["SC_AU_E_DATE"];
	$stx				= $_REQUEST["stx"];
	$page				= $_REQUEST["page"];


	# re-define : variables
	$page				= (is_numeric($page)) ? (int)$page : 1;


	# wh
	$wh = "Where 1 = 1";
	
	if ($SC_AU_S_DATE != '') {
		$wh .= " And DATE(au_datetime) >= '" . mres($SC_AU_S_DATE) . "'";
	}
	if ($SC_AU_E_DATE != '') {
		$wh .= " And DATE(au_datetime) <= '" . mres($SC_AU_E_DATE) . "'";
	}

	if ($stx != '') {
		$wh .= " And mb_id like '%" . mres($stx) . "%'";
	}

	
	$sql = "Select Count(*) As cnt From"
		."	("
		."		("
		."			Select count(*)"
		."			From g5_auth"
		."			{$wh}"
		."			Group By mb_id, au_datetime"
		."		)"
		."		UNION ALL"
		."		("
		."			Select count(*)"
		."			From g5_auth_log"
		."			{$wh}"
		."			Group By mb_id, au_datetime"
		."		)"
		."	) X"
		;
	$row = sql_fetch($sql);
	$total_count = $row['cnt'];

	$rows = 50;
	$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
	if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
	$from_record = ($page - 1) * $rows; // 시작 열을 구함


	$sql = "("
		."		Select count(*) As cnt, mb_id, au_mb_id, au_datetime"
		."		From g5_auth"
		."		{$wh}"
		."		Group By mb_id, au_datetime"
		."	)"
		."	UNION ALL"
		."	("
		."		Select count(*) As cnt, mb_id, au_mb_id, au_datetime"
		."		From g5_auth_log"
		."		{$wh}"
		."		Group By mb_id, au_datetime"
		."	)"
		."	Order By au_datetime Desc, mb_id Asc"
		."	Limit {$from_record}, {$rows} "
		;
	$row = sql_query($sql);


	$g5[title] = "관리권한설정이력";
	include_once(G5_ADMIN_PATH.'/admin.head.php');
	include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');
?>

<div class="sch_box">
	<form id="frmSch" name="frmSch" method="get" action="<?php echo($_SERVER['PHP_SELF'])?>">
	<input type="submit" style="width:1px;height:1px;position:absolute;top:-10000px;left:-10000px" />
	
	<div class="sch_opt1">
		<div class="sch_opt2">
			<span class="sch_term" data-tit="권한설정일자">
				<input type="text" id="SC_AU_S_DATE" name="SC_AU_S_DATE" class="nx-ips1" value="<?php echo($SC_AU_S_DATE)?>" style="max-width:140px;">

				<span class="tilde">~</span>

				<input type="text" id="SC_AU_E_DATE" name="SC_AU_E_DATE" class="nx-ips1" value="<?php echo($SC_AU_E_DATE)?>" style="max-width:140px;">
			</span>

			<span class="sch_ipt wm2" data-tit="대상아이디">
				<input type="text" id="stx" name="stx" class="nx-ips1" value="<?php echo(F_hsc($stx))?>" />
			</span>
		</div>
	</div>

	<a href="javascript:void(0);" onclick="$('#frmSch').submit();" class="btn_sch"><span class="ico_search2"></span></a>

	</form>
</div>

<table border="0" ceAUpadding="0" ceAUspacing="0" class="nx-t-list1">
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
			<th>대상아이디</th>
			<th>권한부여자 아이디</th>
			<th>설정일자</th>
			<th>권한변경 메뉴 개수</th>
			<th>자세히보기</th>
		</tr>
	</thead>
	<tbody>
		<?php
		if (sql_num_rows($row) <= 0) {
			echo '<tr><td colspan="5">검색조건에 맞는 관리권한설정이력이 없습니다.</td></tr>';
		}
		else {
			$s = 0;
			while ($rs1 = sql_fetch_array($row)) {
				?>
		<tr id="au_<?php echo($s)?>" data-cnt="<?php echo($rs1['cnt'])?>" data-mb-id="<?php echo($rs1['mb_id'])?>" data-au-datetime="<?php echo($rs1['au_datetime'])?>">
			<td><?php echo($total_count - $from_record - $s)?></td>
			<td><?php echo(F_hsc($rs1['mb_id']))?></td>	
			<td><?php echo(($rs1['au_mb_id'] != '') ? F_hsc($rs1['au_mb_id']) : 'admin')?></td>	
			<td><?php echo(($rs1['au_datetime'] != '') ? $rs1['au_datetime'] : '-')?></td>
			<td><?php echo($rs1['cnt'])?></td>
			<td><a href="javascript:auth_detail.check(<?php echo($s)?>);" style="font-size: 24px;"><i class="fa fa-arrow-circle-down down"></i><i class="fa fa-arrow-circle-up up" style="display: none;"></i></a></td>	
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
    $("#SC_AU_S_DATE, #SC_AU_E_DATE").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "2016:c+5" });
});

var auth_detail = {
	check: function(num) {
		// detail이 생성되었는지 확인
		if ($('#au_'+num+'_detail').length > 0) {
			if ($('#au_'+num+'_detail').is(":visible")) this.hide(num);
			else this.show(num);

			return;
		}

		// 생성되지 않았으면 init
		this.init(num);
	}
	, init: function(num) {
		var Z = this;
		$el = $('#au_'+num);

		$.ajax({
			url: 'auth_log.detail.php',
			type: 'POST',
			dataType: 'json',
			data: {mb_id: $el.attr('data-mb-id'), au_datetime: $el.attr('data-au-datetime'), cnt: $el.attr('data-cnt')},
		})
		.done(function(json) {
			if (!json.success) {
				if (json.msg) alert(json.msg);
				return;
			}

			var html = "";
			var itms_len = json.itms.length;
			for (var i = 0; i < itms_len; i++) {
				var itm = json.itms[i];

				html += '<div class="nx-auth-row">'
				html += '	<span class="nx-auth-opt">메뉴: '+itm.au_menu+'</span>';
				html += '	<span class="nx-auth-opt">변경 전: '+itm.before_auth+'</span>';
				html += '	<span class="nx-auth-opt">변경 후: '+itm.after_auth+'</span>';
				html += '</div>'
			}

			html = '<tr id="au_'+num+'_detail" style="display:none;"><td colspan="6">' + html + '</td></tr>';

			$el.after(html);

			Z.show(num);
		})
		.fail(function(a, b, c) {
			alert(a.statusText+' ('+a.status+')\n\n'+a.responseText);
			//alert(a.responseText);
		});
	}
	, show: function(num) {
		$('#au_'+num+'_detail').slideDown('fast');
		$('.down', '#au_'+num).hide();
		$('.up', '#au_'+num).show();
	}
	, hide: function(num) {
		$('#au_'+num+'_detail').slideUp('fast');
		$('.up', '#au_'+num).hide();
		$('.down', '#au_'+num).show();
	}
}
//]]>
</script>

<?php
	//마지막 인클루드
	include_once G5_ADMIN_PATH."/admin.tail.php";
?>
