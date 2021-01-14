<?php
	include_once('./_common.php');
	include_once('./evt.err.php'); 	# $sub_menu = ($EP_IDX > 0) ? '980100' : '990100'

	auth_check($auth[$sub_menu], "r");


	# chk : rfv.
	if ($EM_IDX <= 0) {
		header("location:evt.list.php?".$epTail);
		exit();
	}


	# chk : event main record
	$row = sql_fetch("Select EM_TITLE From NX_EVENT_MASTER Where EM_DDATE is null And EM_IDX = '" . mres($EM_IDX) . "'");
	if (is_null($row['EM_TITLE'])) {
		F_script("잘못된 접근 입니다.", "evt.list.php");
	}
	$EM_TITLE = $row['EM_TITLE'];
	unset($row);


	$g5[title] = ($sub_menu == '980100') ? "공모사업 행사 만족도관리" : "만족도관리";
	include_once('../admin.head.php');
	include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');
?>

<div style="display:none;">
	<textarea id="cd-item" disabled><?php
		echo '<div id="sati-item-[#num#]" class="nx-poll-ques-item" style="padding:0 50px 0 0">';
			echo '<input type="hidden" id="ESD_IDX_[#num#]" name="ESD_IDX_[#num#]" value="" />';
			echo '<input type="text" id="ESD_QUES_[#num#]" name="ESD_QUES_[#num#]" value="" class="nx-ips1" maxlength="300" />';
			echo '<a href="javascript:void(0)" onclick="sati.del(\'[#num#]\');" class="del"><span class="ico_minus1"></span></a>';
		echo '</div>';
	?></textarea>
</div>

<div class="nx-tit1 mt0 mb10">행사명 : <?php echo(F_hsc($EM_TITLE))?></div>

<div class="psR pt20">
	<ul class="nx-tab1">
		<li><a href="evt.sati.php?<?php echo($epTail)?>EM_IDX=<?php echo($EM_IDX)?>" class="aon">만족도설문</a></li>
		<li><a href="evt.sati.result.php?<?php echo($epTail)?>EM_IDX=<?php echo($EM_IDX)?>">참여현황</a></li>
	</ul>
	<div style="position:absolute;top:0;right:0;">
		<input type="text" id="evturl" name="evturl" value="<?php echo($_SERVER['HTTP_HOST'].'/evt/evt.sati.php?EM_IDX='.$EM_IDX)?>" style="position:absolute;top:-10000px;left:-10000px" />
		<a href="javascript:void(0)" onclick="sati.copyurl();" class="nx-btn-b2">URL복사</a>
		<a href="evt.list.php?<?php echo($epTail)?>" class="nx-btn-b3">목록으로</a>
	</div>
</div>

<?php
	# get : master record
	$row = sql_fetch("Select * From NX_EVT_SATI_MA Where EM_IDX = '" . mres($EM_IDX) . "' Limit 1");
	if (!is_null($row['EM_IDX'])) {
		$ESM_S_DATE = $row['ESM_S_DATE'];
		$ESM_E_DATE = $row['ESM_E_DATE'];
	}
	unset($row);


	# get : detail record
	$sql = "Select a.*"
		."		, b.*"
		."	From NX_EVT_SATI_DE As a"
		."		Inner Join NX_EVT_SATI_MA As b On b.EM_IDX = a.EM_IDX"
		."	Where b.EM_IDX = '" . mres($EM_IDX) . "'"
		."	Order By a.ESD_IDX Asc"
		;
	$db1 = sql_query($sql);
?>
<div id="nodata-outer" class="nx-box3 taC" style="<?php if (sql_num_rows($db1) > 0) echo('display:none')?>">
	<a href="javascript:void(0)" onclick="sati.init();" class="nx-btn-b2" style="width: 140px;">설문 생성하기</a>
</div>

<div id="data-outer" style="<?php if (sql_num_rows($db1) <= 0) echo('display:none');?>">
	<form id="frmAct" name="frmAct" onsubmit="return false;">
	<input type="hidden" id="EM_IDX" name="EM_IDX" value="<?php echo($EM_IDX)?>" />
	<input type="hidden" id="EP_IDX" name="EP_IDX" value="<?php echo($EP_IDX)?>" />
	
	<h3 class="nx-tit2 mb20">접수기간</h3>
	<table border="0" cellpadding="0" cellspacing="0" class="nx-t-read2">
		<colgroup>
			<col width="100"><col width="">
		</colgroup>
		<tbody>
			<tr>
				<th>시작일</th>
				<td>
					<input type="text" id="ESM_S_DATE" name="ESM_S_DATE" value="<?php echo(F_hsc($ESM_S_DATE))?>" maxlength="10" class="nx-ips1 ws" />
				</td>
			</tr>
			<tr>
				<th>종료일</th>
				<td>
					<input type="text" id="ESM_E_DATE" name="ESM_E_DATE" value="<?php echo(F_hsc($ESM_E_DATE))?>" maxlength="10" class="nx-ips1 ws" />
				</td>
			</tr>
		</tbody>
	</table>

	<h3 class="nx-tit2 mb20">질문관리</h3>
	<div id="sati-outer" class="nx-poll-ques-lst">
		<?php
		$nums = '';

		
		#----- 등록된 정보가 없을 경우 '기본설문' 을 먼저 뿌림
		if (sql_num_rows($db1) <= 0) {
			$sql = "Select ST_IDX, ST_QUES From NX_SATI_TPL Order By ST_IDX Asc";
			$db1 = sql_query($sql);

			while ($rs1 = sql_fetch_array($db1)) {
				$num = uniqid();
				$nums .= "|{$num}";
				?>
		<div id="sati-item-<?php echo($num)?>" class="nx-poll-ques-item" style="padding:0 50px 0 0">
			<input type="hidden" id="ESD_IDX_<?php echo($num)?>" name="ESD_IDX_<?php echo($num)?>" value="" />
			<input type="text" id="ESD_QUES_<?php echo($num)?>" name="ESD_QUES_<?php echo($num)?>" value="<?php echo(F_hsc($rs1['ST_QUES']))?>" class="nx-ips1" maxlength="300" />
			<a href="javascript:void(0)" onclick="sati.del('<?php echo($num)?>');" class="del"><span class="ico_minus1"></span></a>
		</div>
				<?php
			}
		}
		#####


		#----- 기 등록된 정보
		while ($rs1 = sql_fetch_array($db1)) {
			$num = uniqid();
			$nums .= "|{$num}";
			?>
		<div id="sati-item-<?php echo($num)?>" class="nx-poll-ques-item" style="padding:0 50px 0 0">
			<input type="hidden" id="ESD_IDX_<?php echo($num)?>" name="ESD_IDX_<?php echo($num)?>" value="<?php echo($rs1['ESD_IDX'])?>" />
			<input type="text" id="ESD_QUES_<?php echo($num)?>" name="ESD_QUES_<?php echo($num)?>" value="<?php echo(F_hsc($rs1['ESD_QUES']))?>" class="nx-ips1" maxlength="300" />
			<a href="javascript:void(0)" onclick="sati.del('<?php echo($num)?>');" class="del"><span class="ico_minus1"></span></a>
		</div>
			<?php
		}
		#####
		?>
	</div>
	
	<input type="hidden" id="nums" name="nums" value="<?php echo($nums)?>" />
	</form>

	<div class="taC mt15">
		<a href="javascript:void(0)" onclick="sati.add();" class="nx-btn2">+ 추가</a>
	</div>

	<div class="nx-top-bd taR mt30">
		<a href="javascript:void(0)" onclick="sati.save();" class="nx-btn-b2">저장</a>
		<a href="evt.list.php" class="nx-btn-b3">목록으로</a>
	</div>
</div>

<script>
//<![CDATA[
var outer = $('#sati-outer');
var sati = {
	init: function() {
		$('#nodata-outer').hide();
		$('#data-outer').fadeIn('fast');
	}
	, chk: function(c) {
		if (c == '') return;
	}
	, add: function() {
		var Z = this;
		Z.chk();

		var num = uniqid(), cd = $('#cd-item').val().replace(/\[#num#\]/g, num);
		var n_nums = $('#nums').val().split('|'+num).join('')+'|'+num;
		$('#nums').val(n_nums);

		outer.append(cd);

		$('#ESD_QUES_'+num).focus();
	}
	, del: function(c) {
		var Z = this;
		Z.init(c);

		var n_nums = $('#nums').val().split('|'+c).join('');
		$('#nums').val(n_nums);
		$('#sati-item-'+c).remove();
	}
	, save: function(c) {
		var Z = this;
		Z.chk();

		var _t = $('#ESM_S_DATE');
		if (_t.val() == '') {
			alert("접수시간(시작일) 정보를 입력해 주세요.");
			_t.focus(); return;
		}

		var _t = $('#ESM_E_DATE');
		if (_t.val() == '') {
			alert("접수시간(종료일) 정보를 입력해 주세요.");
			_t.focus(); return;
		}
		
		if (confirm("입력하신 사항으로 진행하시겠습니까?"))
		{
			$.ajax({
				url: 'evt.satiProc.php?<?php echo($epTail)?>', 
				type: 'POST', 
				dataType: 'json', 
				data: $('#frmAct').serialize()
			})
			.done(function(json) {
				if (!json.success) {
					if (json.msg) alert(json.msg);
					return;
				}

				if (json.msg) alert(json.msg);
				if (json.redir) window.location.href = json.redir;
			})
			.fail(function(a, b, c) {
				alert(a.statusText+' ('+a.status+')\n\n'+a.responseText);
				//alert(a.responseText);
			});
		}
	}
	, copyurl: function() {
		var copyText = document.getElementById("evturl");
		copyText.select();
		document.execCommand("Copy");
		alert('복사되었습니다.');
	}
}

$(function(){
    $("#ESM_S_DATE, #ESM_E_DATE").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "2016:c+5" });
});
//]]>
</script>
<?php
	//마지막 인클루드
	include_once G5_ADMIN_PATH."/admin.tail.php";
?>
