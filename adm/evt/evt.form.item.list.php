<?php
	include_once('./_common.php');
	include_once('./evt.err.php'); 	# $sub_menu = ($EP_IDX > 0) ? '980100' : '990100'
	auth_check($auth[$sub_menu], "r");

	$g5[title] = "사업관리";
	include_once("../inc/pop.top.php");


	# set : variables
	$EM_IDX = $_GET['EM_IDX'];
	$FI_RNDCODE = $_GET['FI_RNDCODE'];


	# re-define
	$EM_IDX = CHK_NUMBER($EM_IDX);


	# set : wh
	$wh = "Where FI.FI_DDATE is null";
	// $wh = "Where FI.FI_DDATE is null And FI.mb_id = '" . mres($member['mb_id']) . "'";

	if ($EM_IDX > 0) {
		$wh .= " And FI.EM_IDX = '" . mres($EM_IDX) . "'";
	}
	else {
		$wh .= " And FI.FI_RNDCODE = '" . mres($FI_RNDCODE) . "'";
	}


	$sql = "Select Count(*) As cnt1, ifnull(max(FI.FI_SEQ), 1) As cnt2"
		."	From NX_EVENT_FORM_ITEM As FI"
		."	{$wh}"
		."	Order By FI.FI_SEQ Asc, FI.FI_IDX Desc"
		;
	$sdb1 = sql_query($sql);
	$srs1 = sql_fetch_array($sdb1);
	$max = ($srs1['cnt1'] > $srs1['cnt2']) ? $srs1['cnt1'] : $srs1['cnt2'];
	unset($sdb1, $srs1);


	$sql = "Select FI.*"
		."		, (Select Count(distinct FC.FC_FI_IDX) From NX_EVENT_FORM_COND As FC Where FC.FI_IDX = FI.FI_IDX) As FC_CNT"
		."	From NX_EVENT_FORM_ITEM AS FI"
		."	{$wh}"
		."	Order By FI.FI_SEQ Asc, FI.FI_IDX Desc"
		;
	$sdb1 = sql_query($sql);
?>

<h3 class="nx-tit2">추가 신청내역 (이름, 휴대전화번호, 이메일, 소속 은 기본항목입니다.)</h3>
<div class="nx-box2">
	<div class="nx-form-lst">
		<?php
		if (sql_num_rows($sdb1) <= 0) {
			?>
		<div class="nx-form-item lh30">등록된 항목이 없습니다. '+ 추가' 버튼을 눌러 등록해 주세요.</div>
			<?php
		}
		else {
			$s = 0;
			while ($srs1 = sql_fetch_array($sdb1))
			{
				?>
		<div class="nx-form-item" style="<?php if ($srs1['FI_USE_YN'] != 'Y') echo('background:#d7d7d7;') ?>">
			<?php
			echo '<input type="hidden" id="FI_IDX_'.$s.'" name="FI_IDX_'.$s.'" value="'.$srs1['FI_IDX'].'" />';
			?>
			<span class="nx-slt vaM mr10">
				<select id="FI_SEQ_<?php echo($s)?>" name="FI_SEQ_<?php echo($s)?>" onchange="onch_mi_seq('<?php echo($s)?>','<?php echo($srs1['FI_SEQ'])?>')">
					<?php
					for ($j = 1; $j <= $max; $j++) {
						echo '<option value="'.$j.'" '.(($j == $srs1['FI_SEQ']) ? 'selected' : '').'>'.$j.'</option>';
					}
					?>
				</select>
				<span class="ico_select"></span>
			</span>
			<div class="fR ml15">
				<span class="dsIB mr10 vaM"><?php echo(NX_EVENT_FORM_BUILDER::GET_FI_KIND_TO_STR($srs1['FI_KIND']))?></span>
				<a href="javascript:void(0);" onclick="eform.edit('<?php echo($srs1['FI_IDX'])?>')" class="nx-btn-s2" style="width:68px;">수정</a>
				<?php /*<a href="" class="nx-btn-s4" style="width:68px;">삭제</a>*/ ?>
			</div>
			<?php echo(F_hsc($srs1['FI_NAME']))?>
			<?php /*<p>등록필수여부 : <?php echo($srs1['FI_REQ_YN'])?></p>
			<p>사용조건 : <?php if ($srs1['FI_COND_YN'] == 'Y') { echo(number_format($srs1['FC_CNT'])); } else { echo('없음'); } ?></p>*/ ?>
		</div>
				<?php
				$s++;
			}
		}
		?>
	</div>
	<div class="taC mt15">
		<a href="javascript:void(0)" onclick="eform.add()" class="nx-btn2">+ 추가</a>
	</div>
</div>

<script>
//<![CDATA[
var onch_mi_seq = function(num, mi_seq) {
	if (confirm("순서를 변경하시겠습니까?")) {
		var form = new FormData();
		form.append('FI_IDX', $('#FI_IDX_'+num).val());
		form.append('EM_IDX', '<?php echo($EM_IDX)?>');
		form.append('FI_RNDCODE', '<?php echo($FI_RNDCODE)?>');
		form.append('FI_SEQ', $('#FI_SEQ_'+num).val());

		$.ajax({
			url: 'evt.form.item.seqProc.php?<?php echo($epTail)?>', 
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

			window.location.reload();

		})
		.fail(function(a, b, c) {
			alert(a.statusText+' ('+a.status+')\n\n'+a.responseText);
			//alert(a.responseText);
		})
	}
	else {
		$('#FI_SEQ_'+num).val(mi_seq);
	}
}

var _c = '<?php echo($EM_IDX)?>', _r = '<?php echo($FI_RNDCODE)?>';
var eform = {
	add: function() {
		var url = 'evt.form.item.add.php?<?php echo($epTail)?>EM_IDX='+_c+'&FI_RNDCODE='+_r;
		window.open(url, 'form_item_add', 'width=600,height=600,scrollring=auto');
	}
	, edit: function(v) {
		var url = 'evt.form.item.edit.php?<?php echo($epTail)?>EM_IDX='+_c+'&FI_RNDCODE='+_r+'&FI_IDX='+v;
		window.open(url, 'form_item_edit', 'width=600,height=600,scrollring=auto');
	}
}

<?php /* iframe size 조정 */ ?>
var st = null;
var resizeIfr = function() {
	try{
		clearTimeout(st);
		st = setTimeout(function() {
			var pa = parent.document.getElementById('if_form'), modiheight = parseInt(document.body.offsetHeight + 20);
			pa.height = (modiheight <= 160) ? 160 : modiheight;
		}, 500);
	}
	catch(e){alert(e.message);}
}
//]]>
</script>

<?php
	include_once("../inc/pop.btm.php");
?>