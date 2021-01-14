<script>
//<![CDATA[
$(document).ready(function() {
	<?php
	$_single_max_cnt = 2;


	# get data
	$sql = "Select FC.*"
		."			, FI.FI_KIND"
		."			, (Select Count(*) From NX_EVENT_FORM_OPT Where FO_DDATE is null And FI_IDX = FI.FI_IDX) As FO_CNT"
		."		From NX_EVENT_FORM_COND As FC"
		."			Inner Join NX_EVENT_FORM_ITEM As FI On FI.FI_IDX = FC.FC_FI_IDX"
		."		Where FI.FI_DDATE is null"
		."			And FI.FI_USE_YN = 'Y'"
		."			And FI.FI_KIND In('C', 'D')"
		."			And FI.EM_IDX = '" . mres($EM_IDX) . "'"
		."		Order By FC.FI_IDX Asc, FC.FC_FI_IDX Asc, FC.FC_FO_IDX Asc"
		;
	$sdb1 = sql_query($sql);

	$Pre_FI_IDX = "";
	$Pre_FC_FI_IDX = "";
	$arr_FC_FI_IDX = array();
	$arr_FI_KIND = array();
	$arr_FO_CNT = array();
	$ifs = "";
	$s = 0;
	while($srs1 = sql_fetch_array($sdb1)) {
		if($Pre_FI_IDX != $srs1["FI_IDX"]) {
			if($s > 0) {
				for($i = 0; $i < count($arr_FC_FI_IDX); $i++) {
					if($arr_FI_KIND[$i] == "C") {
						if($arr_FO_CNT[$i] > (int)$_single_max_cnt) {
							?>
							$('#IPT_<?php echo($arr_FC_FI_IDX[$i])?>').change(function() {
							<?php
						} else {
							?>
							$('input:radio[name="IPT_<?php echo($arr_FC_FI_IDX[$i])?>"]').change(function() {
							<?php
						}
					} else if($arr_FI_KIND[$i] == "D") {
						?>
						$('.cls_IPT_<?php echo($arr_FC_FI_IDX[$i])?>').click(function() {
						<?php
					}
					?>
						if(<?php echo($ifs)?>) {
							$('.tit_IPT_<?php echo($Pre_FI_IDX)?>').show();
						} else {
							$('.tit_IPT_<?php echo($Pre_FI_IDX)?>').hide();
							//if($('#IPT_<?php echo($Pre_FI_IDX)?>').length > 0) $('#IPT_<?php echo($Pre_FI_IDX)?>').val('');
							//if($('.cls_IPT_<?php echo($Pre_FI_IDX)?>').length > 0) $('.cls_IPT_<?php echo($Pre_FI_IDX)?>').prop('checked', false);
						}
					});
					<?php
				}

				$arr_FC_FI_IDX = array();
				$arr_FI_KIND = array();
				$arr_FO_CNT = array();
				$ifs = "";
			}
		}

		$ifs .= ($ifs == "") ? "" : " || ";

		if($srs1["FI_KIND"] == "C") {
			if($srs1["FO_CNT"] > (int)$_single_max_cnt) {
				$ifs .= "$('#IPT_" . $srs1["FC_FI_IDX"] . "').val() == '" . $srs1["FC_FO_IDX"] . "'";
			} else {
				$ifs .= "$('input:radio[name=\"IPT_" . $srs1["FC_FI_IDX"] . "\"]:checked').val() == '" . $srs1["FC_FO_IDX"] . "'";
			}
		} else if($srs1["FI_KIND"] == "D") {
			$ifs .= "$('#IPT_FO_" . $srs1["FC_FO_IDX"] . "').is(':checked')";
		}

		if($Pre_FI_IDX != $srs1["FI_IDX"] || $Pre_FC_FI_IDX != $srs1["FC_FI_IDX"]) {
			$arr_FC_FI_IDX[] = $srs1["FC_FI_IDX"];
			$arr_FI_KIND[] = $srs1["FI_KIND"];
			$arr_FO_CNT[] = $srs1["FO_CNT"];
		}

		$Pre_FI_IDX = $srs1["FI_IDX"];
		$Pre_FC_FI_IDX = $srs1["FC_FI_IDX"];
		$s++;
	}

	if($s > 0) {
		for($i = 0; $i < count($arr_FC_FI_IDX); $i++) {
			if($arr_FI_KIND[$i] == "C") {
				if($arr_FO_CNT[$i] > (int)$_single_max_cnt) {
					?>
					$('#IPT_<?php echo($arr_FC_FI_IDX[$i])?>').change(function() {
					<?php
				} else {
					?>
					$('input:radio[name="IPT_<?php echo($arr_FC_FI_IDX[$i])?>"]').change(function() {
					<?php
				}
			} else if($arr_FI_KIND[$i] == "D") {
				?>
				$('.cls_IPT_<?php echo($arr_FC_FI_IDX[$i])?>').click(function() {
				<?php
			}
			?>
				if(<?php echo($ifs)?>) {
					$('.tit_IPT_<?php echo($Pre_FI_IDX)?>').show();
				} else {
					$('.tit_IPT_<?php echo($Pre_FI_IDX)?>').hide();
					//if($('#IPT_<?php echo($Pre_FI_IDX)?>').length > 0) $('#IPT_<?php echo($Pre_FI_IDX)?>').val('');
					//if($('.cls_IPT_<?php echo($Pre_FI_IDX)?>').length > 0) $('.cls_IPT_<?php echo($Pre_FI_IDX)?>').prop('checked', false);
				}
			});
			<?php
		}
	}

	unset($sdb1, $srs1);
	?>

	COND_ADD();
});

<?php
# get data
$sql = "Select FC.FC_FI_IDX, FI.FI_KIND"
	."			, (Select Count(*) From NX_EVENT_FORM_OPT Where FO_DDATE is null And FI_IDX = FI.FI_IDX) As FO_CNT"
	."		From NX_EVENT_FORM_COND As FC"
	."			Inner Join NX_EVENT_FORM_ITEM As FI On FI.FI_IDX = FC.FC_FI_IDX"
	."		Where FI.FI_DDATE is null"
	."			And FI.FI_USE_YN = 'Y'"
	."			And FI.FI_KIND In('C', 'D')"
	."			And FI.EM_IDX = '" . mres($EM_IDX) . "'"
	."		Group By FC.FC_FI_IDX, FI.FI_KIND"
	;
$sdb1 = sql_query($sql);
?>
function COND_ADD() {
	<?php
	while($srs1 = sql_fetch_array($sdb1)) {
		if($srs1["FI_KIND"] == "C") {
			if($srs1["FO_CNT"] > (int)$_single_max_cnt) {
				?>
				$('#IPT_<?php echo($srs1["FC_FI_IDX"])?>').trigger('change');
				<?php
			} else {
				?>
				$('input:radio[name="IPT_<?php echo($srs1["FC_FI_IDX"])?>"]:eq(0)').trigger('change');
				<?php
			}
		} else if($srs1["FI_KIND"] == "D") {
			?>
			$('.cls_IPT_<?php echo($srs1["FC_FI_IDX"])?>').trigger('click').trigger('click');
			<?php
		}

		$s++;
	}
	?>
}
<?php
unset($sdb1, $srs1);
?>
//]]>
</script>
