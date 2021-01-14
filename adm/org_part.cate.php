<?php
	$sub_menu = "990300";
	include_once('_common.php');

	auth_check($auth[$sub_menu], "r,w,d");

	$g5[title] = "조직도-부서";
	include_once("inc/pop.top.php");


	$sql = "Select OP_IDX, OP_SEQ, OP_NAME"
		. "		From ORG_PART"
		. "	Where OP_DDATE is null And OP_PARENT_IDX is null And OP_PARENT2_IDX is not null"
		. "	Order By OP_SEQ Asc, OP_IDX Asc"
		;
	$row = sql_query($sql);
	$cnt = sql_num_rows($row);
?>

<div style="display:none;">
	<textarea id="cd-item" disabled><?php
		echo '<div id="cate-item-[#rndcode#]" class="nx-cate-item">';
			echo '<input type="text" id="OP_NAME_[#rndcode#]" name="OP_NAME" class="nx-ips1" />';
			echo '<a href="javascript:void(0)" onclick="cate.save(\'[#rndcode#]\')" class="save nx-btn-s2">저장</a> ';
			echo '<a href="javascript:void(0)" onclick="cate.cancel(\'[#rndcode#]\')" class="cancel nx-btn-s3">취소</a>';
		echo '</div>';
	?></textarea>
</div>

<div style="padding: 15px;">
	<h1 class="nx-tit1">본부명</h1>

	<div id="cate-outer" class="nx-cate-lst">
		
		<?php
		if ($cnt <= 0) {
			echo '<p>등록된 정보가 없습니다.</p>';
		}
		else {
			$s = 0;
			while ($rs1 = sql_fetch_array($row)) {
				?>
		<div id="cate-item-<?php echo($rs1['OP_IDX'])?>"<?php if($s > 0) {echo(' class="mt20"');}?>>
			<div class="nx-cate-item">
				<span class="nx-slt seq">
					<select id="OP_SEQ_<?php echo($rs1['OP_IDX'])?>" name="OP_SEQ">
						<?php
						$sql = "Select OP.OP_IDX"
							."		From ORG_PART As OP"
							."	Where OP.OP_DDATE is null And OP.OP_PARENT_IDX is null And OP.OP_PARENT2_IDX is not null"
							;
						$db2 = sql_query($sql);
						$s = 1;
						while ($rs2 = sql_fetch_array($db2)) {
							?>
						<option value="<?php echo($s)?>" <?php if ($rs1['OP_SEQ'] == $s) echo('selected');?>><?php echo($s)?></option>
							<?php
							$s++;
						}
						?>
					</select>
					<span class="ico_select"></span>
				</span>
				<a href="javascript:void(0)" onclick="cate.edit('<?php echo($rs1['OP_IDX'])?>');" class="edit nx-btn-s2">수정</a>
				<a href="javascript:void(0)" onclick="cate.del('<?php echo($rs1['OP_IDX'])?>');" class="del nx-btn-s4">삭제</a>	
			</div>
			<div class="mt5">
				<input type="text" id="OP_NAME_<?php echo($rs1['OP_IDX'])?>" name="OP_NAME" class="nx-ips1" value="<?php echo($rs1['OP_NAME'])?>" />
			</div>
		</div>
				<?php
				$s++;
			}
		}
		?>
	</div>

	<?php
	if ($cnt < 4) {
		?>
	<div class="taC mt15">
		<a href="javascript:void(0)" onclick="cate.add();" class="nx-btn2">+ 추가</a>
	</div>
		<?php
	}
	?>

	<div class="nx-top-bd taR mt30">
		<a href="javascript:self.close();" class="nx-btn-b3">닫기</a>
	</div>
</div>


<script>
//<![CDATA[
var outer = $('#cate-outer');
var cate = {
	init: function(c) {
		if (c == '') return;
	}
	, add: function() {
		var Z = this;
		Z.init();

		outer.append($('#cd-item').val().replace(/\[#rndcode#\]/g, uniqid()));
	}
	, save: function(c) {
		var Z = this;
		Z.init();

		var seq = $("#OP_SEQ_"+c)
			, name = $("#OP_NAME_"+c);
		if (name.val() == '') {
			alert("본부명 정보를 입력해 주세요.");
			name.focus();
			return;
		}

		var form = new FormData();
		form.append('m', 'add');
		form.append('OP_SEQ', seq.val());
		form.append('OP_NAME', name.val());

		Z.act(form);
	}
	, cancel: function(c) {
		var Z = this;
		Z.init();

		$('#cate-item-'+c).remove();
	}
	, edit: function(c) {
		var Z = this;
		Z.init(c);


		var seq = $("#OP_SEQ_"+c)
			, name = $("#OP_NAME_"+c);
		if (name.val() == '') {
			alert("본부명 정보를 입력해 주세요.");
			name.focus();
			return;
		}

		var form = new FormData();
		form.append('m', 'edit');
		form.append('OP_IDX', c);
		form.append('OP_SEQ', seq.val());
		form.append('OP_NAME', name.val());
		
		Z.act(form);
	}
	, del: function(c) {
		var Z = this;
		Z.init(c);

		if (confirm("삭제된 정보는 복구가 불가능 합니다.\n\n삭제하시겠습니까?"))
		{
			var form = new FormData();
			form.append('m', 'del');
			form.append('OP_IDX', c);

			Z.act(form);
		}
	}
	, act: function(f) {
		if (f === undefined) return;

		$.ajax({
			url: 'org_part.cateProc.php', 
			type: 'POST', 
			dataType: 'json', 
			data: f, 
			processData: false, 
			contentType: false
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
//]]>
</script>
<?php
	include_once("inc/pop.btm.php");
?>
