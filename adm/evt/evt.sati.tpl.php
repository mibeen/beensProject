<?php
	$sub_menu = "990100";
	include_once('./_common.php');

	
	auth_check($auth[$sub_menu], "r,w,d");

	
	# get : record
	$sql = "Select ST_IDX, ST_QUES From NX_SATI_TPL Order By ST_IDX Asc";
	$row = sql_query($sql);


	include_once("../inc/pop.top.php");
?>

<div style="display:none;">
	<textarea id="cd-item" disabled><?php
		echo '<div id="tpl-item-[#num#]" class="nx-poll-ques-item" style="padding:0 50px 0 0">';
			echo '<input type="hidden" id="ST_IDX_[#num#]" name="ST_IDX_[#num#]" value="" />';
			echo '<input type="text" id="ST_QUES_[#num#]" name="ST_QUES_[#num#]" class="nx-ips1" maxlength="300" />';
			echo '<a href="javascript:void(0)" onclick="tpl.del(\'[#num#]\')" class="del"><span class="ico_minus1"></span></a>';
		echo '</div>';
	?></textarea>
</div>

<div style="padding: 15px;">
	<h1 class="nx-tit1">만족도 기본질문</h1>

	<form id="frmAct" name="frmAct" onsubmit="return false;">
	
	<div id="tpl-outer" class="nx-poll-ques-lst">

		<?php
		if (sql_num_rows($row) <= 0) {
			echo '<p>등록된 정보가 없습니다.</p>';
		}
		else {
			$nums = '';
			while ($rs1 = sql_fetch_array($row)) {
				$num = uniqid();
				$nums .= "|{$num}";
				?>
		<div id="tpl-item-<?php echo($num)?>" class="nx-poll-ques-item" style="padding:0 50px 0 0">
			<input type="hidden" id="ST_IDX_<?php echo($num)?>" name="ST_IDX_<?php echo($num)?>" value="<?php echo($rs1['ST_IDX'])?>" />
			<input type="text" id="ST_QUES_<?php echo($num)?>" name="ST_QUES_<?php echo($num)?>" class="nx-ips1" maxlength="300" value="<?php echo(F_hsc($rs1['ST_QUES']))?>" />
			<a href="javascript:void(0)" onclick="tpl.del('<?php echo($num)?>')" class="del"><span class="ico_minus1"></span></a>
		</div>
				<?php
			}
		}
		?>
	</div>
	
	<input type="hidden" id="nums" name="nums" value="<?php echo($nums)?>" />
	</form>
	
	<div class="taC mt15">
		<a href="javascript:void(0)" onclick="tpl.add();" class="nx-btn2">+ 추가</a>
	</div>

	<div class="nx-top-bd taR mt30">
		<a href="javascript:void(0)" onclick="tpl.save();" class="nx-btn-b2">저장</a>
		<a href="javascript:self.close()" class="nx-btn-b3">취소</a>
	</div>
</div>


<script>
//<![CDATA[
var outer = $('#tpl-outer');
var tpl = {
	init: function(c) {
		if (c == '') return;
	}
	, add: function() {
		var Z = this;
		Z.init();

		var num = uniqid(), cd = $('#cd-item').val().replace(/\[#num#\]/g, num);
		var n_nums = $('#nums').val().split('|'+num).join('')+'|'+num;
		$('#nums').val(n_nums);

		outer.append(cd);

		$('#ST_QUES_'+num).focus();
	}
	, del: function(c) {
		var Z = this;
		Z.init(c);

		var n_nums = $('#nums').val().split('|'+c).join('');
		$('#nums').val(n_nums);
		$('#tpl-item-'+c).remove();
	}
	, save: function(c) {
		var Z = this;
		Z.init();

		if (confirm("입력하신 사항으로 진행하시겠습니까?"))
		{
			$.ajax({
				url: 'evt.sati.tplProc.php', 
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
				self.close();
			})
			.fail(function(a, b, c) {
				alert(a.statusText+' ('+a.status+')\n\n'+a.responseText);
				//alert(a.responseText);
			});
		}
	}
}
//]]>
</script>
<?php
	include_once("../inc/pop.btm.php");
?>
