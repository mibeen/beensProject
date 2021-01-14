<?php
	include_once("./_common.php");


	if (!defined('_GNUBOARD_')) exit();
	if ((int)$EM_IDX <= 0 || (int)$EJ_IDX <= 0 || is_null($member['mb_id'])) exit();


	#----- get : FORM BUILDER INSERTED DATA ----------#
	$sql = "Select FV.FI_IDX, FV.FO_IDX, FV.FV_VAL, FV.FV_EXT1, FV.FV_EXT2, FV.FV_FILEYN, FV.FV_RNDCODE"
		. "			, FI.FI_SEQ, FI.FI_KIND, FI.FI_NAME, FI.FI_TYPE"
		. "			, FO.FO_VAL"
		. "			, Case When FI.FI_KIND in('A','B','E','F','G') Then FV.FV_VAL When FI.FI_KIND in('C','D') Then FO.FO_VAL Else '' End As EL_VAL"
		. "		From NX_EVENT_FORM_VAL As FV"
		. "			Inner Join NX_EVENT_FORM_ITEM As FI On FI.FI_IDX = FV.FI_IDX"
		. "				And FI.FI_DDATE is null And FI.FI_USE_YN = 'Y'"
		. "			Left Join NX_EVENT_FORM_OPT As FO On FO.FO_IDX = FV.FO_IDX"
		. "				And FO.FO_DDATE is null And FO.FI_IDX = FI.FI_IDX"
		. "		Where FI.EM_IDX = '" . mres($EM_IDX) . "'"
		. " 		And FV.EJ_IDX = '" . mres($EJ_IDX) . "'"
		. " 		And FV.mb_id = '" . mres($member['mb_id']) . "'"
		. "		Order By FI.FI_SEQ Asc"
		;
	$sdb1 = sql_query($sql);

	$FV_EXT1 = array();
	$FV_EXT2 = array();
	$EL_VAL = array();
	while($srs1 = sql_fetch_array($sdb1))
	{
		$FV_EXT1[$srs1['FI_IDX'].'_'.$srs1['FO_IDX']] = $srs1['FV_EXT1'];
		$FV_EXT2[$srs1['FI_IDX'].'_'.$srs1['FO_IDX']] = $srs1['FV_EXT2'];
		$EL_VAL[$srs1['FI_IDX'].'_'.$srs1['FO_IDX']] = $srs1['EL_VAL'];
	}
	unset($sdb1, $srs1);
	#----- get : FORM BUILDER INSERTED DATA ----------#


	# get data
	$sql = "Select FI.*"
		. "		From NX_EVENT_FORM_ITEM As FI"
		. "		Where FI.FI_DDATE is null And FI.FI_USE_YN = 'Y' And FI.EM_IDX = '" . mres($EM_IDX) . "'"
		. "		Order By FI.FI_SEQ Asc"
		;
	$sdb1 = sql_query($sql);

	$s = 0;
	while($srs1 = sql_fetch_array($sdb1))
	{
		$FI_IDX = $srs1['FI_IDX'];
		$FI_NAME = $srs1['FI_NAME'];

		$_ID_ = 'IPT_'.$FI_IDX;		# 객체의 ID
		$_NAME_ = $_ID_;

		$req .= ($srs1['FI_REQ_YN'] == 'Y') ? '|'.$srs1['FI_IDX'] : '';
		?>
		<tr class="tit_IPT_<?php echo($FI_IDX)?>">
			<th><?php echo(F_hsc($FI_NAME))?><?php if($srs1['FI_REQ_YN'] == 'Y') { ?> *<?php } ?></th>
			<td fi_kind="<?php echo($srs1['FI_KIND'])?>" style="min-height:40px;line-height:40px">
				<?php
				# 값이 화면에 표시 되었는지 여부
				$_bo = false;

				# 한줄텍스트/여러줄텍스트/날짜
				if (in_array($srs1['FI_KIND'], array('A','B','E'))) {
					echo $EL_VAL[$FI_IDX.'_0'] . '&nbsp;';
					$_bo = true;
				}
				# get : 단일/다중 선택 항목
				else if (in_array($srs1['FI_KIND'], array('C','D')))
				{
					$sql = "Select FO_IDX"
						. "		From NX_EVENT_FORM_OPT"
						. "		Where FO_DDATE is null And FI_IDX = '" . mres($FI_IDX) . "'"
						. "		Order By FO_SEQ Asc, FO_IDX Desc"
						;
					$sdb2 = sql_query($sql);

					while ($srs2 = sql_fetch_array($sdb2)) {
						$_t = $EL_VAL[$FI_IDX.'_'.$srs2['FO_IDX']];
						if (!is_null($_t)) {
							echo $_t . '&nbsp;';
							$_bo = true;
						}
					}
					unset($srs2, $sdb2);

				}
				# 주소
				else if ($srs1['FI_KIND'] == 'F')
				{
					if (!is_null($FV_EXT1[$FI_IDX.'_0'])) {
						echo '<div style="height:40px">'.$FV_EXT1[$FI_IDX.'_0'].'&nbsp;</div>';
					}
					if (!is_null($FV_EXT2[$FI_IDX.'_0'])) {
						echo '<div style="height:40px">'.$FV_EXT2[$FI_IDX.'_0'].'&nbsp;</div>';
					}
					
					echo '<div style="height:40px">'.$EL_VAL[$FI_IDX.'_0'].'&nbsp;</div>';
					$_bo = true;
				}
				# 파일
				else if ($srs1['FI_KIND'] == 'G') {
					set_session('ss_view_NX_EVENT_FORM_VAL_'.$FI_IDX, TRUE);

					$_fs = get_file('NX_EVENT_FORM_VAL', $EJ_IDX);
					// for ($k = 0; $k < $_fs['count']; $k++) {
						$_f = $_fs[$FI_IDX];

						if ($_f['file'] != '') {
							echo '<a href="'.$_f['href'].'">'.F_hsc($_f['source']).'</a>';
							$_bo = true;
						}
					// }
					unset($_fs);
				}

				# 화면에 표시되는 값이 없을 경우 '공백' 을 찍어줌
				if (!$_bo) {
					echo '&nbsp;';
				}
				?>
			</td>
		</tr>
		<?php
	}
?>
