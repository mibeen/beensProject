<?php
	include_once './_common.php';
	include_once('./evt.err.php'); 	# $sub_menu = ($EP_IDX > 0) ? '980100' : '990100'
	if (!defined('_GNUBOARD_')) exit;
	auth_check($auth[$sub_menu], "r");


	# chk : rfv.
	if ($FI_IDX == "") {
		F_script("잘못된 접근 입니다.", "history.back();");
	}


	# get data
	$sql = "Select FI.*"
		. "		From NX_EVENT_FORM_ITEM As FI"
		. "		{$wh} And FI.FI_IDX = '" . mres($FI_IDX) . "' limit 1"
		;
	$db1 = sql_query($sql);

	if (sql_num_rows($db1) == 0)
	{
		unset($db1);
		F_script("요청하신 정보가 존재하지 않습니다.", "history.back();");
	}

	$rs1 = sql_fetch_array($db1);

	$mb_id = $rs1["mb_id"];
	$FI_KIND = $rs1["FI_KIND"];
	$FI_NAME = $rs1["FI_NAME"];
	$FI_EXPL = $rs1["FI_EXPL"];
	$FI_REQ_YN = $rs1["FI_REQ_YN"];
	$FI_COND_YN = $rs1["FI_COND_YN"];
	$FI_USE_YN = $rs1["FI_USE_YN"];
	$FI_DEFAULT_YN = $rs1["FI_DEFAULT_YN"];
	$FI_TYPE = $rs1["FI_TYPE"];
	$FI_EXT_TYPE = $rs1["FI_EXT_TYPE"];
	$FI_MAX_SIZE = $rs1["FI_MAX_SIZE"];

	unset($db1, $rs1);


	include "../inc/pop.top.php";
?>

<div class="ofH" style="padding:15px">
	<form id="frmEdit" name="frmEdit" onsubmit="return onsuEdit();">
	<input type="hidden" id="FI_IDX" name="FI_IDX" value="<?php echo($FI_IDX)?>" />
	<input type="hidden" id="EM_IDX" name="EM_IDX" value="<?php echo($EM_IDX)?>" />
	<input type="hidden" id="FI_RNDCODE" name="FI_RNDCODE" value="<?php echo($FI_RNDCODE)?>" />

	<h1 class="nx-tit1" style="margin-top:0">항목 편집</h1>
	<table border="0" cellpadding="0" cellspacing="0" class="nx-t-read2">
		<colgroup>
			<col width="130"><col width="">
		</colgroup>
		<tbody>
			<tr>
				<th>항목명</th>
				<td>
					<input type="text" id="FI_NAME" name="FI_NAME" value="<?php echo(F_hsc($FI_NAME))?>" maxlength="50" class="nx-ips1" />
				</td>
			</tr>
			<tr>
				<th>항목종류</th>
				<td>
					<?php
					echo '<input type="hidden" id="FI_KIND" name="FI_KIND" value="'.F_hsc($FI_KIND).'" />';
					echo NX_EVENT_FORM_BUILDER::GET_FI_KIND_TO_STR($FI_KIND);
					?>

					<div id="div_FO" class="mt10" style="clear:both; margin-top:4px;<?php if($FI_KIND != "C" && $FI_KIND != "D") { ?> display:none;<?php } ?>"></div>
					<div id="div_OPT" class="mt10" style="clear:both;<?php if($FI_KIND != "C" && $FI_KIND != "D") { ?> display:none;<?php } ?>">
						<div class="mb10">
							<input type="text" id="OPT_VAL" name="OPT_VAL" maxlength="50" class="nx-ips1 wm" />
							<a href="javascript:oncl_OPT('Add');" class="nx-btn-s2">추가</a>
							<a id="a_Edit" href="javascript:oncl_OPT('Edit');" class="nx-btn-s2" style="display:none;">수정</a>
						</div>
						<ul id="ul_OPT_LIST" class="nx-ips1" style="height:200px; overflow:auto;">
							<?php
							# get data
							$sql = "Select FO.*"
								. "		From NX_EVENT_FORM_OPT As FO"
								. "		Where FO.FO_DDATE is null And FO.FI_IDX = '" . mres($FI_IDX) . "'"
								. "		Order By FO.FO_SEQ Asc, FO.FO_IDX Asc"
								;
							$db1 = sql_query($sql);

							while($rs1 = sql_fetch_array($db1)) {
								echo '<li val="'.$rs1["FO_IDX"].'" txt="'.F_hsc($rs1["FO_VAL"]).'" onclick="oncl_OPT_LIST(this);" style="cursor:default;">';
								echo F_hsc($rs1["FO_VAL"]);
								echo '</li>';
							}

							unset($db1, $rs1);
							?>
						</ul>
						<div class="mt10">
							<a href="javascript:oncl_OPT('Up');" class="nx-btn-s2">위로</a>
							<a href="javascript:oncl_OPT('Down');" class="nx-btn-s2">이래로</a>
							<a href="javascript:oncl_OPT('Asc')" class="nx-btn-s2">오름차순</a>
							<a href="javascript:oncl_OPT('Del')" class="nx-btn-s2">삭제</a>
						</div>
					</div>

				</td>
			</tr>

			<tr id="tr_FI_TYPE" style="display:none;">
				<th>항목타입</th>
				<td>
					<span class="nx-slt">
						<select id="FI_TYPE_A" name="FI_TYPE_A">
							<option value=""></option>
							<?php
							$_its_ = NX_EVENT_FORM_BUILDER::GET_FI_TYPE_TO_STR('A');
							for ($i = 0; $i < Count($_its_); $i++)
							{
								$_it_ = $_its_[$i];
								$preSel = ($_it_['val'] == $FI_TYPE) ? " selected" : "";
								echo '<option value="'.$_it_['val'].'" FI_KIND="'.$_it_['FI_KIND'].'"'.$preSel.'>'.$_it_['str'].'</option>';
							}
							unset($_its_, $preSel);
							?>
						</select>
						<span class="ico_select"></span>
					</span>
					<span class="nx-slt">
						<select id="FI_TYPE_E" name="FI_TYPE_E">
							<option value=""></option>
							<?php
							$_its_ = NX_EVENT_FORM_BUILDER::GET_FI_TYPE_TO_STR('E');
							for ($i = 0; $i < Count($_its_); $i++)
							{
								$_it_ = $_its_[$i];
								$preSel = ($_it_['val'] == $FI_TYPE) ? " selected" : "";
								echo '<option value="'.$_it_['val'].'" FI_KIND="'.$_it_['FI_KIND'].'"'.$preSel.'>'.$_it_['str'].'</option>';
							}
							unset($_its_, $preSel);
							?>
						</select>
						<span class="ico_select"></span>
					</span>
				</td>
			</tr>

			<tr id="tr_FI_EXT_TYPE" style="<?php if ($FI_KIND != 'G') echo('display:none;')?>">
				<th>확장자 체크</th>
				<td>
					<div class="chk1_wrap dsIB">
						<input type="checkbox" id="FI_EXT_TYPE_YN" name="FI_EXT_TYPE_YN" class="chk1" value="Y" <?php if($FI_EXT_TYPE != "") { echo('checked'); } ?> /><label for="FI_EXT_TYPE_YN"><span class="chkbox"><span class="ico_check"></span></span><span class="txt">사용</span></label>
					</div>
					<div id="div_FI_EXT_TYPE" style="<?php if ($FI_EXT_TYPE == '') echo('display:none;');?>">
						<input type="text" id="FI_EXT_TYPE" name="FI_EXT_TYPE" value="<?php echo F_hsc($FI_EXT_TYPE)?>" maxlength="80" class="nx-ips1 wl" style="margin:5px 0" />
						<p class="taL">(허용할 확장자를 "," 로 입력해 주세요.)</p>
					</div>
				</td>
			</tr>
			<tr id="tr_FI_MAX_SIZE" style="<?php if ($FI_KIND != 'G') echo('display:none;')?>">
				<th>용량</th>
				<td>
					<div class="chk1_wrap dsIB">
						<input type="checkbox" id="FI_MAX_SIZE_YN" name="FI_MAX_SIZE_YN" class="chk1" value="Y" <?php if((int)$FI_MAX_SIZE > 0) { echo('checked'); } ?> /><label for="FI_MAX_SIZE_YN"><span class="chkbox"><span class="ico_check"></span></span><span class="txt">사용</span></label>
					</div>
					<div id="div_FI_MAX_SIZE" style="<?php if ((int)$FI_MAX_SIZE <= 0) echo('display:none');?>">
						<input type="number" id="FI_MAX_SIZE" name="FI_MAX_SIZE" value="<?php echo ((int)$FI_MAX_SIZE/1024/1024)?>" class="nx-ips1 ws" min="1" max="1000" style="margin:5px 0" /> MB
						<p class="taL">(제한용량을 MB 단위로 입력해 주세요.)</p>
					</div>
				</td>
			</tr>

			<tr>
				<th>항목설명</th>
				<td><textarea id="FI_EXPL" name="FI_EXPL" class="nx-ips1" rows="3" cols="1" style="min-height:60px;"><?php echo(F_hsc($FI_EXPL))?></textarea></td>
			</tr>
			<tr>
				<th>필수입력항목</th>
				<td>
					<div class="chk1_wrap dsIB mr15">
						<input type="checkbox" id="FI_REQ_YN" name="FI_REQ_YN" class="chk1" value="Y"<?php if($FI_REQ_YN == "Y") { ?> checked="checked"<?php } ?>><label for="FI_REQ_YN"><span class="chkbox"><span class="ico_check"></span></span><span class="txt">필수</span></label>
					</div>
				</td>
			</tr>

			<?php
			if ($EM_IDX > 0) {
				$_wh = " And FI.EM_IDX = '" . mres($EM_IDX) . "'";
			}
			else {
				$_wh = " And FI.FI_RNDCODE = '" . mres($FI_RNDCODE) . "'";
			}
			

			# get data
			$sql = "Select FI.*"
				."		, (Select Count(*) From NX_EVENT_FORM_COND As FC Where FC.FI_IDX = '" . mres($FI_IDX) . "' And FC.FC_FI_IDX = FI.FI_IDX) As FC_CNT"
				."	From NX_EVENT_FORM_ITEM As FI"
				."	Where FI.FI_DDATE is null"
				."		{$_wh}"
				."		And FI.mb_id = '" . mres($member['mb_id']) . "'"
				."		And FI.FI_KIND In('C', 'D')"
				."		And FI.FI_IDX != '" . mres($FI_IDX) . "'"
				."		And FI.FI_IDX Not In(Select FC.FI_IDX From NX_EVENT_FORM_COND As FC Where FC.FC_FI_IDX = '" . mres($FI_IDX) . "')"
				."	Order By FI.FI_SEQ Asc, FI.FI_IDX Desc"
				;
			$db1 = sql_query($sql);

			unset($_wh);

			if(sql_num_rows($db1) > 0) {
				?>
			<tr>
				<th>사용조건</th>
				<td>
					<div class="chk1_wrap">
						<?php
						echo '<input type="checkbox" id="FI_COND_YN" name="FI_COND_YN" class="chk1" value="Y" onclick="oncl_COND(\'\');" '.(($FI_COND_YN == "Y") ? 'checked' : '').' />';
						echo '<label for="FI_COND_YN"><span class="chkbox"><span class="ico_check"></span></span><span class="txt">추가</span></label>';
						?>
					</div>
					<div id="div_COND" class="mt10" style="padding: 10px 15px;background:#eee;<?php if($FI_COND_YN != "Y") echo('display:none;'); ?>">
						<?php
						$s = 0;
						while($rs1 = sql_fetch_array($db1)) {
							?>
						<div class="chk1_wrap">
							<?php
							echo '<input type="checkbox" id="FC_FI_IDX_'.$s.'" name="FC_FI_IDX['.$s.']" value="'.$rs1["FI_IDX"].'" class="chk1" onclick="oncl_COND(\''.$s.'\');" '.(($rs1["FC_CNT"] > 0) ? 'checked' : '').' />';
							echo '<label for="FC_FI_IDX_'.$s.'"><span class="chkbox"><span class="ico_check"></span></span><span class="txt">';
							echo ' '.F_hsc($rs1["FI_NAME"]);
							echo '</span></label>';
							?>
						</div>

						<div id="div_COND_<?php echo($s)?>" class="chk1_wrap mb mt5" style="margin-left:40px;<?php if($rs1["FC_CNT"] == 0) echo('display:none;'); ?>">
							<?php
							# get data
							$sql = "Select FO.*"
								. "			, Case When FC.FC_FO_IDX is null Then 'N' Else 'Y' End FC_YN"
								. "		From NX_EVENT_FORM_OPT As FO"
								. "			Left Join NX_EVENT_FORM_COND As FC On FC.FI_IDX = '" . mres($FI_IDX) . "' And FC.FC_FI_IDX = FO.FI_IDX And FC.FC_FO_IDX = FO.FO_IDX"
								. "		Where FO.FO_DDATE is null And FO.FI_IDX = '" . mres($rs1["FI_IDX"]) . "'"
								. "		Order By FO.FO_SEQ Asc, FO.FO_IDX Asc"
								;
							$db2 = sql_query($sql);

							$t = 0;
							while ($rs2 = sql_fetch_array($db2))
							{
								echo '<input type="checkbox" id="FC_FO_IDX_'.$s.'_'.$t.'" name="FC_FO_IDX['.$s.']['.$t.']" value="'.$rs2["FO_IDX"].'" class="chk1" '.(($rs2["FC_YN"] == "Y") ? 'checked' : '').' />';
								echo '<label for="FC_FO_IDX_'.$s.'_'.$t.'"><span class="chkbox"><span class="ico_check"></span></span><span class="txt">';
								echo ' '.F_hsc($rs2["FO_VAL"]);
								echo '</span></label>';

								$t++;
							}

							unset($db2, $rs2);
							?>
						</div>
							<?php
							$s++;
						}
						unset($db1, $rs1);
						?>

					</div>

				</td>
			</tr>
				<?php
			}
			?>

			<tr>
				<th>사용</th>
				<td>
					<div class="chk1_wrap dsIB mr15">
						<input type="checkbox" id="FI_USE_YN" name="FI_USE_YN" class="chk1" value="Y"<?php if($FI_USE_YN == "Y") { ?> checked="checked"<?php } ?>><label for="FI_USE_YN"><span class="chkbox"><span class="ico_check"></span></span><span class="txt">사용</span></label>
					</div>
				</td>
			</tr>

		</tbody>
	</table>
	<div class="taC mt20">
		<?php
		if($FI_DEFAULT_YN != "Y") {
			?>
		<div class="fL">
			<a href="javascript:" onclick="onclDel();" class="nx-btn4">삭제</a>
		</div>
			<?php
		}
		?>
		<div class="fR">
			<a href="javascript:" onclick="if (onsuEdit()) { document.frmEdit.submit(); }" class="nx-btn2">수정</a>
			<a href="javascript:self.close();" class="nx-btn3">취소</a>
		</div>
	</div>

	<div style="width:0; height:0; overflow:hidden;"><input type="submit" /></div>
	</form>
</div>


<script>
//<![CDATA[
$(document).ready(function() {
	$("#FI_REQ_YN").click(function() {
		if($(this).is(":checked") && $("#FI_COND_YN").is(":checked")) {
			alert("필수인 항목은 사용조건을 추가할 수 없습니다.");
			$(this).prop("checked", false);
		}
	});

	$("#FI_COND_YN").click(function() {
		if($(this).is(":checked") && $("#FI_REQ_YN").is(":checked")) {
			alert("사용조건이 있는 항목은 필수로 사용하실 수 없습니다.");
			$(this).prop("checked", false);
			oncl_COND("");
		}
	});


	// evt. FI_KIND change
	var evt_change_FI_KIND = function() {
		$('#FI_KIND').off('change').change(function(e) {
			var v = $(this).val();

			<?php # 한줄텍스트 예외처리 ?>
			if (v == 'A') {
				$('#tr_FI_TYPE').show();
				$('#FI_TYPE_A').parent().show();
				$('#FI_TYPE_E').parent().hide();
				$('#FI_TYPE_A').focus();

				$('#tr_FI_EXT_TYPE').hide();
				$('#tr_FI_MAX_SIZE').hide();
			}
			<?php # 날짜 예외처리 ?>
			else if (v == 'E') {
				$('#tr_FI_TYPE').show();
				$('#FI_TYPE_A').parent().hide();
				$('#FI_TYPE_E').parent().show();
				$('#FI_TYPE_E').focus();

				$('#tr_FI_EXT_TYPE').hide();
				$('#tr_FI_MAX_SIZE').hide();
			}
			<?php /* 파일 예외처리 */ ?>
			else if (v == 'G') {
				$('#tr_FI_EXT_TYPE').show();
				$('#tr_FI_MAX_SIZE').show();
			}
			else {
				$('#tr_FI_TYPE').hide();
				$('#tr_FI_EXT_TYPE').hide();
				$('#tr_FI_MAX_SIZE').hide();
			}

			if(v == "C" || v == "D") {
				$("#div_FO").show();
				$("#div_OPT").show();
			} else {
				$("#div_FO").hide();
				$("#div_OPT").hide();
			}
		});
	}
	evt_change_FI_KIND();

	$('#FI_EXT_TYPE_YN').click(function() {
		var $this = $(this);
		if ($this.prop('checked')) {
			$('#div_FI_EXT_TYPE').show();
		}
		else {
			$('#div_FI_EXT_TYPE').hide();
		}
	});

	$('#FI_MAX_SIZE_YN').click(function() {
		var $this = $(this);
		if ($this.prop('checked')) {
			$('#div_FI_MAX_SIZE').show();
		}
		else {
			$('#div_FI_MAX_SIZE').hide();
		}
	});


	$('#FI_KIND').trigger('change');

	$("#FI_NAME").focus();
});

// onsubmit
function onsuEdit()
{
	if($("#FI_NAME").val().replace(/(^\s*)|(\s*$)/g, "") == "") {
		alert("항목이름을 입력해 주세요.");
		$("#FI_NAME").focus();
		return false;
	}

	if($("#FI_KIND").val() == "") {
		alert("항목종류를 선택해 주세요.");
		return false;
	}

	if($("#FI_KIND").val() == "A") {
		if($("#FI_TYPE_A").val().replace(/(^\s*)|(\s*$)/g, "") == "") {
			alert("항목타입을 선택해 주세요.");
			$("#FI_TYPE_A").focus();
			return false;
		}
	} else if($("#FI_KIND").val() == "E") {
		if($("#FI_TYPE_E").val().replace(/(^\s*)|(\s*$)/g, "") == "") {
			alert("항목타입을 선택해 주세요.");
			$("#FI_TYPE_E").focus();
			return false;
		}
	} else if($("#FI_KIND").val() == "C" || $("#FI_KIND").val() == "D") {
		if($("#ul_OPT_LIST li").size() <= 0) {
			alert("옵션을 추가해 주세요.");
			$("#OPT_VAL").focus();
			return false;
		}

		var html = [];
		var h = 0;

		for(var i = 0; i < $("#ul_OPT_LIST li").size(); i++) {
			html[h++] = '<input type="hidden" id="FO_IDX_' + i + '" name="FO_IDX[]" value="" />';
			html[h++] = '<input type="hidden" id="FO_VAL_' + i + '" name="FO_VAL[]" value="" />';
		}

		$("#div_FO").html(html.join(''));

		for(var i = 0; i < $("#ul_OPT_LIST li").size(); i++) {
			$("#FO_IDX_" + i).val($("#ul_OPT_LIST li:eq(" + i + ")").attr("val"));
			$("#FO_VAL_" + i).val($("#ul_OPT_LIST li:eq(" + i + ")").attr("txt"));
		}
	}

	if (confirm("입력하신 내용으로 진행하시겠습니까?")) {
		$.ajax({
			url: 'evt.form.item.editProc.php<?php echo($epTail)?>', type: 'POST', dataType: 'json', data: $('#frmEdit').serialize()
		})
		.done(function(json) {
			if (!json.success) {
				if (json.msg) alert(json.msg);
				return;
			}

			if (json.msg) alert(json.msg);
			if (json.redir) opener.window.location.href = json.redir;
			self.close();
		})
		.fail(function(a, b, c) {
			alert(a.statusText+' ('+a.status+')\n\n'+a.responseText);
			//alert(a.responseText);
		});		
	}

	return false;
}

function oncl_OPT_LIST(opt) {
	$("#ul_OPT_LIST li").css({color: "", backgroundColor: ""}).prop('selected', false);
	$(opt).css({color: "#ffffff", backgroundColor: "#3399ff"}).prop("selected", true);

	var opt = $("#ul_OPT_LIST li:selected");

	if(opt.size() > 0) {
		$("#OPT_VAL").val(opt.attr("txt"));
		$("#a_Edit").show();
	}
}

function oncl_OPT(mode) {
	//alert($("#ul_OPT_LIST li").size());

	if(mode == "Add") {
		if($("#OPT_VAL").val().replace(/(^\s*)|(\s*$)/g, "") == "") {
			//alert("옵션값 정보를 입력해 주세요.");
			$("#OPT_VAL").focus();
			return;
		}

		$("#ul_OPT_LIST").append('<li val="" txt="' + $("#OPT_VAL").val() + '" onclick="oncl_OPT_LIST(this);" style="cursor:default;">' + $("#OPT_VAL").val() + '</li>');
		$("#OPT_VAL").focus();
	} else if(mode == "Edit") {
		if($("#OPT_VAL").val().replace(/(^\s*)|(\s*$)/g, "") == "") {
			//alert("옵션값 정보를 입력해 주세요.");
			$("#OPT_VAL").focus();
			return;
		}

		var opt = $("#ul_OPT_LIST li:selected");

		if(opt.size() > 0) {
			opt.attr("txt", $("#OPT_VAL").val());
			opt.html($("#OPT_VAL").val());
			$("#OPT_VAL").focus();
		}
	} else if(mode == "Del") {
		var opt = $("#ul_OPT_LIST li:selected");

		if(opt.size() > 0) {
			opt.remove();
		}
	} else if(mode == "Up") {
		var opt = $("#ul_OPT_LIST li:selected");

		if(opt.size() > 0) {
			opt.insertBefore(opt.prev());
		}
	} else if(mode == "Down") {
		var opt = $("#ul_OPT_LIST li:selected");

		if(opt.size() > 0) {
			opt.insertAfter(opt.next());
		}
	} else if(mode == "Asc") {
		var txt = [];

		for(var i = 0; i < $("#ul_OPT_LIST li").size(); i++) {
			txt[i] = $("#ul_OPT_LIST li:eq(" + i + ")").attr("txt");
		}

		txt.sort();

		for(var i = 0; i < txt.length - 1; i++) {
			var opt = $("#ul_OPT_LIST li:gt(" + i + ")[txt='" + txt[i] + "']");

			if(opt.size() > 0) {
				opt.insertBefore( $("#ul_OPT_LIST li:eq(" + i + ")"));
			}
		}
	}

	$("#OPT_VAL").val("");
	$("#a_Edit").hide();
}

function oncl_COND(val) {
	if(val == "") {
		if($("#FI_COND_YN").is(":checked")) {
			$("#div_COND").show();
		} else {
			$("#div_COND").hide();
		}
	} else {
		if($("#FC_FI_IDX_" + val).is(":checked")) {
			$("#div_COND_" + val).show();
		} else {
			$("#div_COND_" + val).hide();
		}
	}
}

// onclick : del
function onclDel()
{
	if (confirm("삭제된 정보는 복구가 불가능 합니다.\n\n삭제하시겠습니까?"))
	{
		var form = new FormData();
		form.append('FI_IDX', $('#FI_IDX').val());
		form.append('EM_IDX', $('#EM_IDX').val());
		form.append('FI_RNDCODE', $('#FI_RNDCODE').val());

		$.ajax({
			url: 'evt.form.item.delProc.php<?php echo($epTail)?>', 
			type: 'POST', 
			dataType: 'json', 
			data: form, 
			processData: false, 
			contentType: false
		})
		.done(function(json) {
			if (!json.success) {
				if (json.msg) alert(json.msg);
				return;
			}

			if (json.msg) alert(json.msg);
			if (json.redir) opener.window.location.href = json.redir;

		})
		.fail(function(a, b, c) {
			alert(a.statusText+' ('+a.status+')\n\n'+a.responseText);
			//alert(a.responseText);
		})
		.always(function() {
			try{
				self.close();
			}
			catch(e){alert(e.message);}
		});
	}
}
//]]>
</script>

<?php
	include "../inc/pop.btm.php";
?>
