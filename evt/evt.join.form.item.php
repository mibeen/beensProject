<?php
	include_once("./_common.php");


	if (!defined('_GNUBOARD_')) exit;
	if ((int)$EM_IDX <= 0) exit;


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
			<td>
				<?php
				# case : 한줄텍스트(A)
				if ($srs1['FI_KIND'] == 'A')
				{
					# input type 정의
					if (in_array($srs1['FI_TYPE'], array('TEL','EMAIL','URL','NUMBER'))) {
						$type = strtolower($srs1['FI_TYPE']);
					}
					else {
						$type = 'text';
					}


					# 전화번호 형식
					if ($srs1['FI_KIND'] == 'A' && $srs1['FI_TYPE'] == 'TEL')
					{
						echo '<input type="'.$type.'" id="'.$_ID_.'1" name="'.$_NAME_.'1" maxlength="4" class="nx_ips5 ws3" fb_name="'.F_hsc($FI_NAME).'" /> -';
						echo '<input type="'.$type.'" id="'.$_ID_.'2" name="'.$_NAME_.'2" maxlength="4" class="nx_ips5 ws3" fb_name="'.F_hsc($FI_NAME).'" /> -';
						echo '<input type="'.$type.'" id="'.$_ID_.'3" name="'.$_NAME_.'3" maxlength="4" class="nx_ips5 ws3" fb_name="'.F_hsc($FI_NAME).'" />';
					}
					# 기타
					else {
						echo '<input type="'.$type.'" id="'.$_ID_.'" name="'.$_NAME_.'" maxlength="250" class="nx_ips5" fb_name="'.F_hsc($FI_NAME).'" />';
					}
				}

				# case : 여러줄텍스트(B)
				else if ($srs1['FI_KIND'] == 'B') {
					echo '<textarea id="'.$_ID_.'" name="'.$_NAME_.'" class="nx_ips5" fb_name="'.F_hsc($FI_NAME).'" style="min-height:100px"></textarea>';
				}

				#---------- 단일/다중 선택 처리 ----------#
				else if ($srs1['FI_KIND'] == 'C' || $srs1['FI_KIND'] == 'D')
				{
					# get : 단일/다중 선택 항목
					$sql = "Select *"
						. "		From NX_EVENT_FORM_OPT"
						. "		Where FO_DDATE is null And FI_IDX = '" . mres($srs1['FI_IDX']) . "'"
						. "		Order By FO_SEQ Asc, FO_IDX Desc"
						;
					$sdb2 = sql_query($sql);

					$cnt = sql_num_rows($sdb2);
					$ss = 0;
					while ($srs2 = sql_fetch_array($sdb2))
					{
						$FO_IDX = $srs2['FO_IDX'];
						$FO_VAL = $srs2['FO_VAL'];

						# case : 단일선택
						if ($srs1['FI_KIND'] == 'C')
						{
							# 두개 이상일 경우
							if ($cnt > 2)
							{
								if ($ss == 0)
								{
									echo '<span class="mr5">';
										echo '<span class="nx_slt1 wa">';
											echo '<select id="'.$_ID_.'" name="'.$_NAME_.'" class="slt2" fb_name="'.F_hsc($FI_NAME).'">';
												echo '<option value="">선택해 주세요.</option>';
								}
								

												echo '<option value="'.$FO_IDX.'">'.F_hsc($FO_VAL).'</option>';

								if ($ss == $cnt - 1)
								{
											echo '</select>';
											echo '<span class="ico">▼</span>';
										echo '</span>';
									echo '</span>';
								}
							}
							else {
								echo '<div class="radio2_wrap">';
									echo '<input type="radio" id="'.$_ID_.'_'.$FO_IDX.'" name="'.$_NAME_.'" value="'.$FO_IDX.'" class="radio2 cls_'.$_ID_.'" fb_name="'.F_hsc($FI_NAME).'" />';
									echo '<label for="'.$_ID_.'_'.$FO_IDX.'">';
										echo '<span class="radbox"><span></span></span>';
										echo '<span class="txt">'.F_hsc($FO_VAL).'</span>';
									echo '</label>';
								echo '</div>';
							}
						}
						# case : 다중선택
						else if ($srs1['FI_KIND'] == 'D')
						{
							echo '<div class="chk2_wrap">';
								echo '<input type="checkbox" id="IPT_FO_'.$FO_IDX.'" name="IPT_FO_'.$FO_IDX.'" value="'.$FO_IDX.'" class="chk2 cls_'.$_ID_.'" fb_name="'.F_hsc($FI_NAME).'" />';
								echo '<label for="IPT_FO_'.$FO_IDX.'">';
									echo '<span class="chkbox"><span class="ico_check"></span></span>';
									echo '<span class="txt">'.F_hsc($FO_VAL).'</span>';
								echo '</label>';
							echo '</div>';
						}

						$ss++;
					}
				}

				# case : 날짜
				else if ($srs1['FI_KIND'] == 'E') {
					# set : FI_NAME(s)
					$_t_ = explode(' ', '-- :');
					$_exp_ = explode('-', $_t_[0]);
					$YY = $_exp_[0];
					$MM = $_exp_[1];
					$DD = $_exp_[2];

					$_exp_ = explode(':', $_t_[1]);
					$HH = $_exp_[0];
					$II = $_exp_[1];
					unset($_t_,$_exp_);
					?>
					<span class="dsIB mb5">
						<span class="mr5">
							<span class="nx_slt1 wa">
								<select id="<?php echo($_ID_)?>_YY" name="<?php echo($_NAME_)?>_YY" class="date_<?php echo($_ID_)?>" fb_name="<?php echo(F_hsc($FI_NAME))?>">
									<option value=""></option>
									<?php
									$fi = (date('Y')+5);
									while ($fi >= 1900) {
										$preSel = ($fi == $YY) ? ' selected' : '';
										echo '<option value="'.$fi.'"'.$preSel.'>'.$fi.'</option>';

										$fi--;
									}
									unset($preSel,$fi);
									?>
								</select>
								<span class="ico">▼</span>
							</span> 년
						</span>

						<span class="mr5">
							<span class="nx_slt1 wa">
								<select id="<?php echo($_ID_)?>_MM" name="<?php echo($_NAME_)?>_MM" class="date_<?php echo($_ID_)?>" fb_name="<?php echo(F_hsc($FI_NAME))?>">
									<option value=""></option>
									<?php
									for ($fi = 1; $fi <= 12; $fi++) {
										$preSel = ($fi == $MM) ? ' selected' : '';
										$fii = ($fi < 10) ? '0'.$fi : $fi;
										echo '<option value="'.$fii.'"'.$preSel.'>'.$fii.'</option>';
									}
									unset($preSel,$fi,$fii);
									?>
								</select>
								<span class="ico">▼</span>
							</span> 월
						</span>

						<span class="mr5">
							<span class="nx_slt1 wa">
								<select id="<?php echo($_ID_)?>_DD" name="<?php echo($_NAME_)?>_DD" class="date_<?php echo($_ID_)?>" fb_name="<?php echo(F_hsc($FI_NAME))?>">
									<option value=""></option>
									<?php
									for ($fi = 1; $fi <= 31; $fi++) {
										$preSel = ($fi == $DD) ? ' selected' : '';
										$fii = ($fi < 10) ? '0'.$fi : $fi;
										echo '<option value="'.$fii.'"'.$preSel.'>'.$fii.'</option>';
									}
									unset($preSel,$fi,$fii);
									?>
								</select>
								<span class="ico">▼</span>
							</span> 일
						</span>
					</span>

					<?php
					if ($srs1['FI_TYPE'] == 'DATETIME') {
						?>
					<span class="dsIB mb5">
						<span class="mr5">
							<span class="nx_slt1 wa">
								<select id="<?php echo($_ID_)?>_HH" name="<?php echo($_NAME_)?>_HH" class="date_<?php echo($_ID_)?>" fb_name="<?php echo(F_hsc($FI_NAME))?>">
									<option value=""></option>
									<?php
									for ($fi = 0; $fi <= 23; $fi++) {
										$preSel = ($fi == $HH && $HH != '') ? ' selected' : '';
										$fii = ($fi < 10) ? '0'.$fi : $fi;
										echo '<option value="'.$fii.'"'.$preSel.'>'.$fii.'</option>';
									}
									unset($preSel,$fi,$fii);
									?>
								</select>
								<span class="ico">▼</span>
							</span> :
						</span>

						<span class="mr5">
							<span class="nx_slt1 wa">
								<select id="<?php echo($_ID_)?>_II" name="<?php echo($_NAME_)?>_II" class="date_<?php echo($_ID_)?>" fb_name="<?php echo(F_hsc($FI_NAME))?>">
									<option value=""></option>
									<?php
									for ($fi = 0; $fi <= 59; $fi+=5) {
										$preSel = ($fi == $II && $II != '') ? ' selected' : '';
										$fii = ($fi < 10) ? '0'.$fi : $fi;
										echo '<option value="'.$fii.'"'.$preSel.'>'.$fii.'</option>';
									}
									unset($preSel,$fi,$fii);
									?>
								</select>
								<span class="ico">▼</span>
							</span>
						</span>
						<?php
					}
					?>
					</span>
					<?php
				}

				# case : 주소
				else if ($srs1['FI_KIND'] == 'F')
				{
					echo '<input type="text" id="'.$_ID_.'_EXT1" name="'.$_NAME_.'_EXT1" maxlength="7" class="nx_ips5 ws mb5" fb_name="'.F_hsc($FI_NAME).'" /> ';
					echo '<a href="javascript:oncl_zipcode(\''.$_ID_.'_EXT1\', \''.$_ID_.'_EXT2\', \''.$_ID_.'\');" class="event_form_btn1">주소찾기</a>';
					echo '<br />';
					echo '<input type="text" id="'.$_ID_.'_EXT2" name="'.$_NAME_.'_EXT2" maxlength="50" class="nx_ips5 mb5" fb_name="'.F_hsc($FI_NAME).'" />';
					echo '<br />';
					echo '<input type="text" id="'.$_ID_.'" name="'.$_NAME_.'" maxlength="50" class="nx_ips5" fb_name="'.F_hsc($FI_NAME).'" />';
				}

				# case : 파일
				else if ($srs1['FI_KIND'] == 'G')
				{
					echo '<input type="file" id="'.$_ID_.'" name="'.$_NAME_.'[]" class="nx_ips5 wm mb5" fb_name="'.F_hsc($FI_NAME).'" />';

					if ($srs1['FI_EXT_TYPE'] != '' || $srs1['FI_MAX_SIZE'] > 0) {
						$_ret = '<div class="nx_expl">';

						if ($srs1['FI_EXT_TYPE'] != '') {
							$_ret .= '허용타입 ('.strtolower(implode(', ', explode(',', $srs1['FI_EXT_TYPE']))).')';
						}

						if ($srs1['FI_MAX_SIZE'] > 0) {
							if ($_ret != '') $_ret .= ' &nbsp; ';
							$_ret .= '최대 용량 ('.@($srs1['FI_MAX_SIZE']/1024/1024).' MB)';
						}

						$_ret .= '</div>';
						echo $_ret;
						unset($_ret);
					}
				}

				if($srs1['FI_EXPL'] != "") {
					echo '<div class="nx_expl">'.nl2br(F_hsc($srs1['FI_EXPL'])).'</div>';
				}
				?>
			</td>
		</tr>
		<?php
	}
	unset($srs1, $sdb1);
?>

<script type="text/javascript" src="<?php if($_SERVER["HTTPS"] == "on") { ?>https://spi.maps.daum.net/imap/map_js_init/postcode.v2.js<?php } else { ?>http://dmaps.daum.net/map_js_init/postcode.v2.js<?php } ?>"></script>
<script>
//<![CDATA[
function oncl_zipcode(ZIPCODE, ADDR1, ADDR2) {
	new daum.Postcode({
		oncomplete: function(data) {
			// 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

			// 각 주소의 노출 규칙에 따라 주소를 조합한다.
			// 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
			var fullAddr = ''; // 최종 주소 변수
			var extraAddr = ''; // 조합형 주소 변수

			// 사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
			if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
				fullAddr = data.roadAddress;

			} else { // 사용자가 지번 주소를 선택했을 경우(J)
				fullAddr = data.jibunAddress;
			}

			// 사용자가 선택한 주소가 도로명 타입일때 조합한다.
			if(data.userSelectedType === 'R'){
				//법정동명이 있을 경우 추가한다.
				if(data.bname !== ''){
					extraAddr += data.bname;
				}
				// 건물명이 있을 경우 추가한다.
				if(data.buildingName !== ''){
					extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
				}
				// 조합형주소의 유무에 따라 양쪽에 괄호를 추가하여 최종 주소를 만든다.
				fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
			}

			// 우편번호와 주소 정보를 해당 필드에 넣는다.
			$("#" + ZIPCODE).val(data.zonecode); //5자리 새우편번호 사용
			$("#" + ADDR1).val(fullAddr);

			// 커서를 상세주소 필드로 이동한다.
			$("#" + ADDR2).focus();
		}
	}).open();
}
//]]>
</script>
