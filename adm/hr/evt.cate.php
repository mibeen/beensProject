<?php
	$sub_menu = "990100";
	include_once('./_common.php');

	auth_check($auth[$sub_menu], "r,w,d");

	$g5[title] = "사업관리";
	include_once("../inc/pop.top.php");


	$sql = "Select EC_IDX, EC_NAME From NX_EVENT_CATE Order By EC_SEQ Asc, EC_IDX Desc";
	$row = sql_query($sql);
?>

<div style="display:none;">
	<textarea id="cd-item" disabled><?php
		echo '<div id="cate-item-[#rndcode#]" class="nx-cate-item">';
			echo '<input type="text" id="EC_NAME_[#rndcode#]" name="EC_NAME" class="nx-ips1" />';
			echo '<a href="javascript:void(0)" onclick="cate.save(\'[#rndcode#]\')" class="save nx-btn-s2">저장</a> ';
			echo '<a href="javascript:void(0)" onclick="cate.cancel(\'[#rndcode#]\')" class="cancel nx-btn-s3">취소</a>';
		echo '</div>';
	?></textarea>
</div>

<div style="padding: 15px;">
	<h1 class="nx-tit1">카테고리</h1>

	<div id="cate-outer" class="nx-cate-lst">
		
		<?php
		if (sql_num_rows($row) <= 0) {
			echo '<p>등록된 정보가 없습니다.</p>';
		}
		else {
			$s = 0;
			while ($rs1 = sql_fetch_array($row)) {
				?>
		<div id="cate-item-<?php echo($rs1['EC_IDX'])?>" class="nx-cate-item">
			<input type="text" id="EC_NAME_<?php echo($rs1['EC_IDX'])?>" name="EC_NAME" class="nx-ips1" value="<?php echo($rs1['EC_NAME'])?>" />
			<a href="javascript:void(0)" onclick="cate.edit('<?php echo($rs1['EC_IDX'])?>');" class="edit nx-btn-s2">수정</a>
			<a href="javascript:void(0)" onclick="cate.del('<?php echo($rs1['EC_IDX'])?>');" class="del nx-btn-s4">삭제</a>
		</div>
				<?php
				$s++;
			}
		}
		?>

		<div id="cate-item-holder" class="nx-cate-item" style="display:none">&nbsp;</div>
	</div>
	<div class="taC mt15">
		<a href="javascript:void(0)" onclick="cate.add();" class="nx-btn2">+ 추가</a>
	</div>

	<div class="nx-top-bd taR mt30">
		<a href="javascript:self.close();" class="nx-btn-b3">닫기</a>
	</div>
</div>

<script src="/adm/lib/jquery-ui.min.js"></script>
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

		var name = $("#EC_NAME_"+c);
		if (name.val() == '') {
			alert("카테고리명 정보를 입력해 주세요.");
			name.focus();
			return;
		}

		var form = new FormData();
		form.append('m', 'add');
		form.append('EC_NAME', name.val());

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


		var name = $("#EC_NAME_"+c);
		if (name.val() == '') {
			alert("카테고리명 정보를 입력해 주세요.");
			name.focus();
			return;
		}

		var form = new FormData();
		form.append('m', 'edit');
		form.append('EC_IDX', c);
		form.append('EC_NAME', name.val());
		
		Z.act(form);
	}
	, del: function(c) {
		var Z = this;
		Z.init(c);

		if (confirm("삭제된 정보는 복구가 불가능 합니다.\n\n삭제하시겠습니까?"))
		{
			var form = new FormData();
			form.append('m', 'del');
			form.append('EC_IDX', c);

			Z.act(form);
		}
	}
	, act: function(f) {
		if (f === undefined) return;

		$.ajax({
			url: 'evt.cateProc.php', 
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
	include_once("../inc/pop.btm.php");
?>
