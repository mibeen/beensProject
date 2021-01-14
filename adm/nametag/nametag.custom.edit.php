<?php
	$sub_menu = "990600";
	include_once('./_common.php');
	include_once('./nametag.custom.err.php');

	auth_check($auth[$sub_menu], "w");


	$sql = "Select ENC.ENC_IDX, ENC.ENC_TITLE"
		."		, ENT.ENT_IDX"
		."	From NX_EVENT_NAMETAG_CUSTOM As ENC"
		."		Left Join NX_EVENT_NAMETAG_TEMPLATE As ENT On ENT.ENC_IDX = ENC.ENC_IDX"
		."			And ENT.ENT_DDATE is null"
		."	Where ENC.ENC_DDATE is null"
		."		And ENC.ENC_IDX = '" . mres($ENC_IDX) . "'"
		."	Limit 1"
		;
	$row = sql_fetch($sql);


	$g5[title] = "명찰제작";
	include_once('../admin.head.php');
?>

<div class="taR mb10">
	<a href="nametag.custom.list.php" class="nx-btn-b3">목록으로</a>
</div>

<form id="frmAct" name="frmAct" onsubmit="return false">
	<input type="hidden" name="ENC_IDX" value="<?php echo $ENC_IDX?>" />
	<table border="0" cellpadding="0" cellspacing="0" class="nx-t-read1">
		<colgroup>
			<col width="130"><col width="">
		</colgroup>
		<tbody>
			<tr>
				<th>행사명</th>
				<td>
					<input type="text" name="ENC_TITLE" id="ENC_TITLE" value="<?php echo($row['ENC_TITLE'])?>" class="nx-ips1 wl">
				</td>
			</tr>
			<tr>
				<th>템플릿</th>
				<td>
					<span class="nx-slt dsIB">
						<select id="ENT_IDX" name="ENT_IDX">
							<option value="">템플릿을 선택하세요</option>
							<?php
							# 행사에 연결되지 않은 템플릿 목록
							$sql = "Select ENT_IDX, ENT_TITLE From NX_EVENT_NAMETAG_TEMPLATE Where ENT_DDATE is null And EM_IDX is null And ENC_IDX is null Order By ENT_IDX Desc";
							$sdb1 = sql_query($sql);

							while ($srs1 = sql_fetch_array($sdb1)) {
								echo '<option value="'.(int)$srs1['ENT_IDX'].'">'.F_hsc($srs1['ENT_TITLE']).'</option>';
							}
							unset($srs1, $sdb1);
							?>
						</select>
						<span class="ico_select"></span>
					</span>

					<a href="javascript:void(0)" onclick="nt.copy()" class="nx-btn-b2">가져오기</a>
				</td>
			</tr>
		</tbody>
	</table>
</form>

<script>
//<![CDATA[
var nt = {
	pdf: function() {
		<?php
		# 템플릿이 지정되었을 경우만 PDF 다운로드 가능
		if ((int)$row['ENT_IDX'] > 0) {
			?>
		var url = 'nametag.custom.pdf.php';
		window.open("" ,"nametag_pdf", "scrollbars=yes");

		var f = document.frmAct;
		f.action = url;
		f.method = 'post';
		f.target = 'nametag_pdf';
		
		f.submit();
			<?php
		}
		?>
	}
	, copy: function() {
		var _c = $('#ENC_TITLE');
		if (_c.val() == '') {
			alert("행사명을 입력해 주세요.");
			_c.focus(); return;
		}

		var _t = $('#ENT_IDX');
		if (_t.val() == '') {
			alert("템플릿 정보를 선택해 주세요.");
			_t.focus(); return;
		}

		if (confirm("선택하신 템플릿을 가져오시겠습니까?\n\n이전 정보가 있을 경우 신규 템플릿 정보로 대체 됩니다.")) {
			var f = new FormData();
			f.append('ENC_IDX', '<?php echo $ENC_IDX?>');
			f.append('ENC_TITLE', _c.val());
			f.append('ENT_IDX', _t.val());

			$.ajax({
				url: 'nametag.custom.copyProc.php', 
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
				window.location.href = json.redir;
			})
			.fail(function(a, b, c) {
				alert(a.statusText+' ('+a.status+')\n\n'+a.responseText);
			});
		}
	}
};
//]]>
</script>


<?php
/*----------------------------------------------------------------------*/
# 연결된 템플릿 정보가 없을 경우 보이지 않음
if ((int)$row['ENT_IDX'] > 0)
{
	/*	maxSize
		300dpi 기준 12x18cm (cm)
		72dpi 기준 340x510 (px)
	*/
	$maxSize = ['ww'=>(int)120, 'hh'=>(int)180];


	# get data
	$db_ent = sql_fetch("Select * From NX_EVENT_NAMETAG_TEMPLATE Where ENT_DDATE is null And ENT_IDX = '" . mres($row['ENT_IDX']) . "' Limit 1");
	if (is_null($db_ent['ENT_IDX'])) {
		F_script("요청하신 정보가 존재하지 않습니다.", "window.location.href='evt.nametag.tpl.list.php?".$epTail."';");
	}


	$ENT_WIDTH = round($db_ent['ENT_WIDTH'] * (72 / 25.4));
	$ENT_HEIGHT = round($db_ent['ENT_HEIGHT'] * (72 / 25.4));
	?>
<script src="<?php echo(G5_JS_URL)?>/spectrum-master/spectrum.js"></script>
<link rel="stylesheet" href="<?php echo(G5_JS_URL)?>/spectrum-master/spectrum.css">

<form id="frmEdit" name="frmEdit" enctype="multipart/form-data" onsubmit="return false;">
<input type="hidden" id="ENC_IDX" name="ENC_IDX" value="<?php echo $ENC_IDX?>" />
<input type="hidden" id="ENT_IDX" name="ENT_IDX" value="<?php echo((int)$row['ENT_IDX'])?>" />

<table border="0" cellpadding="0" cellspacing="0" class="nx-t-read1 mt40">
	<colgroup>
		<col width="130"><col width="">
	</colgroup>
	<tbody>
		<tr>
			<th>사이즈</th>
			<td><?php
				echo '<span class="dsIB mr20">가로 ';
				echo '<input type="text" id="ENT_WIDTH" name="ENT_WIDTH" value="'.F_hsc($db_ent['ENT_WIDTH']).'" class="nx-ips1 ws2" maxlength="3" placeholder="'.$maxSize['ww'].'" /> mm</span> ';
				echo '<span class="dsIB mr20">세로 ';
				echo '<input type="text" id="ENT_HEIGHT" name="ENT_HEIGHT" value="'.F_hsc($db_ent['ENT_HEIGHT']).'" class="nx-ips1 ws2" maxlength="3" placeholder="'.$maxSize['hh'].'" /> mm</span>';
				echo '<span id="span_ENT_WIDTH" class="dsIB">'.$ENT_WIDTH.'</span>px';
				echo ' X ';
				echo '<span id="span_ENT_HEIGHT" class="dsIB">'.$ENT_HEIGHT.'</span>px';
			?></td>
		</tr>
		<?php
			$_dir = "NX_EVENT_NAMETAG_TEMPLATE";
			$_files = get_file('NX_EVENT_NAMETAG_TEMPLATE', $row['ENT_IDX']);
		?>
		<tr>
			<th>배경이미지</th>
			<td>
				<p class="red text-left">* JPG 형태의 이미지만 올려주세요</p>
				<input type="file" id="ent_file" name="ent_file[]" class="dsIB" accept="image/*" />
				<span class="nx-tip">(2배 이미지를 올리시면 선명합니다.)</span>

				<?php
				$prev_img = 'data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==';

				if (!is_null($_files[0])) {
					$_file = $_files[0];
					if (file_exists(G5_DATA_PATH.'/file/'.$_dir.'/'.$_file['file'])) {
						echo '<div style="margin-top:5px;">';
							echo '파일명 : '.$_file['source'].' &nbsp; ';
							echo '<input type="checkbox" id="ent_file_del_0" name="ent_file_del_0" value="Y" /> <label for="ent_file_del_0">삭제</label><br/>';
						echo '</div>';
						// echo '<img src="'.$_file['path'].'/'.$_file['file'].'" alt="" style="max-width:300px;margin-top:5px;" />';

						$prev_img = $_file['path'].'/'.$_file['file'];
					}
				}
				?>
			</td>
		</tr>
		<tr>
			<th>엑셀업로드</th>
			<td>
				<div class="nx-box3 mb20">
	                <p class="taL">
	                    <span class="nx-tip">엑셀파일 작성 가이드</span><br>
	                    A, B, C, D 열에 내용을 입력할 수 있습니다. (행사명, 이름, 소속, 명찰번호 등)<br>
	                    A열 - A열 내용 / B열 - B열 내용 / C열 - C열 내용 / D열 - D열 내용의 스타일이 적용이 됩니다.<br>
	                    첫째 행(1행) 부터 내용을 입력해주세요.
	                </p>
	                <table class="tbl_frm01" style="width:700px; background: #fff">
	                    <colgroup>
	                        <col width="60"><col width="160"><col width="160"><col width="160"><col width="160">
	                    </colgroup>
	                    <thead>
	                        <tr>
	                            <th style="padding: 5px 0;">&nbsp;</th>
	                            <th style="padding: 5px 0;">A</th>
	                            <th style="padding: 5px 0;">B</th>
	                            <th style="padding: 5px 0;">C</th>
	                            <th style="padding: 5px 0;">D</th>
	                        </tr>
	                    </thead>
	                    <tbody>
	                        <tr>
	                            <th>1</th>
	                            <td style="text-align: center;">진흥원간담회</td>
	                            <td style="text-align: center;">소속1</td>
	                            <td style="text-align: center;">아무개</td>
	                            <td style="text-align: center;">gill-180611A-001</td>
	                        </tr>
	                        <tr>
	                            <th>2</th>
	                            <td style="text-align: center;">진흥원간담회</td>
	                            <td style="text-align: center;">소속2</td>
	                            <td style="text-align: center;">홍길동</td>
	                            <td style="text-align: center;">gill-180611A-002</td>
	                        </tr>
	                        <tr>
	                            <th>3</th>
	                            <td style="text-align: center;">진흥원간담회</td>
	                            <td style="text-align: center;">소속2</td>
	                            <td style="text-align: center;">김철수</td>
	                            <td style="text-align: center;">gill-180611A-003</td>
	                        </tr>
	                    </tbody>
	                </table>
	            </div>
	            <input type="file" name="ent_file[]" class="dsIB" accept=".xlsx" />
				<span class="nx-tip">(.xlsx 확장자 파일을 올려주세요.)</span>

				<?php
				if (!is_null($_files[1])) {
					$_file = $_files[1];
					if (file_exists(G5_DATA_PATH.'/file/'.$_dir.'/'.$_file['file'])) {
						echo '<div style="margin-top:5px;">';
							echo '파일명 : '.$_file['source'].' &nbsp; ';
							echo '<input type="checkbox" id="ent_file_del_1" name="ent_file_del_1" value="Y" /> <label for="ent_file_del_1">삭제</label><br/>';
						echo '</div>';
					}
				}
				?>
			</td>
		</tr><?php unset($_dir, $_files); ?>

		<tr>
			<th>A열 내용</th>
			<td>
				크기
				<span class="nx-slt mr15">
					<select id="ENT_F1_SIZE" name="ENT_F1_SIZE">
						<?php
						for ($k = 12; $k <= 48; $k+=2) {
							echo '<option value="'.$k.'" '.(($k == $db_ent['ENT_F1_SIZE']) ? 'selected' : '').'>'.$k.'</option>';
						}
						?>
					</select>
					<span class="ico_select"></span>
				</span>

				왼쪽여백
				<span class="nx-slt mr15">
					<select id="ENT_F1_LEFT" name="ENT_F1_LEFT">
						<?php
						for ($k = 0; $k <= 50; $k+=5) {
							echo '<option value="'.$k.'" '.(($k == $db_ent['ENT_F1_LEFT']) ? 'selected' : '').'>'.$k.'</option>';
						}
						?>
					</select>
					<span class="ico_select"></span>
				</span>

				오른쪽여백
				<span class="nx-slt mr15">
					<select id="ENT_F1_RIGHT" name="ENT_F1_RIGHT">
						<?php
						for ($k = 0; $k <= 50; $k+=5) {
							echo '<option value="'.$k.'" '.(($k == $db_ent['ENT_F1_RIGHT']) ? 'selected' : '').'>'.$k.'</option>';
						}
						?>
					</select>
					<span class="ico_select"></span>
				</span>

				정렬
				<span class="nx-slt mr15">
					<select id="ENT_F1_ALIGN" name="ENT_F1_ALIGN">
						<option value="LEFT" <?php if ($db_ent['ENT_F1_ALIGN'] == 'LEFT') echo('selected');?>>왼쪽</option>
						<option value="CENTER" <?php if ($db_ent['ENT_F1_ALIGN'] == 'CENTER') echo('selected');?>>가운데</option>
						<option value="RIGHT" <?php if ($db_ent['ENT_F1_ALIGN'] == 'RIGHT') echo('selected');?>>오른쪽</option>
					</select>
					<span class="ico_select"></span>
				</span>

				색상
				<input type="text" id="ENT_F1_COLOR" name="ENT_F1_COLOR" value="<?php echo(F_hsc('#'.$db_ent['ENT_F1_COLOR']))?>" maxlength="7" class="mr15" />

				<div class="nx-namep-deco-wrap mr15">
					<input type="checkbox" id="ENT_F1_BOLD" name="ENT_F1_BOLD" value="Y" class="nx-namep-deco" <?php if ($db_ent['ENT_F1_BOLD'] == 'Y') echo('checked')?> /><label for="ENT_F1_BOLD">B</label>
					<input type="checkbox" id="ENT_F1_UNDERLINE" name="ENT_F1_UNDERLINE" value="Y" class="nx-namep-deco" <?php if ($db_ent['ENT_F1_UNDERLINE'] == 'Y') echo('checked')?> /><label for="ENT_F1_UNDERLINE" class="underline">U</label>
				</div>
			</td>
		</tr>

		<tr>
			<th>B열 내용</th>
			<td>
				크기
				<span class="nx-slt mr15">
					<select id="ENT_F2_SIZE" name="ENT_F2_SIZE">
						<?php
						for ($k = 12; $k <= 48; $k+=2) {
							echo '<option value="'.$k.'" '.(($k == $db_ent['ENT_F2_SIZE']) ? 'selected' : '').'>'.$k.'</option>';
						}
						?>
					</select>
					<span class="ico_select"></span>
				</span>

				왼쪽여백
				<span class="nx-slt mr15">
					<select id="ENT_F2_LEFT" name="ENT_F2_LEFT">
						<?php
						for ($k = 0; $k <= 50; $k+=5) {
							echo '<option value="'.$k.'" '.(($k == $db_ent['ENT_F2_LEFT']) ? 'selected' : '').'>'.$k.'</option>';
						}
						?>
					</select>
					<span class="ico_select"></span>
				</span>

				오른쪽여백
				<span class="nx-slt mr15">
					<select id="ENT_F2_RIGHT" name="ENT_F2_RIGHT">
						<?php
						for ($k = 0; $k <= 50; $k+=5) {
							echo '<option value="'.$k.'" '.(($k == $db_ent['ENT_F2_RIGHT']) ? 'selected' : '').'>'.$k.'</option>';
						}
						?>
					</select>
					<span class="ico_select"></span>
				</span>

				정렬
				<span class="nx-slt mr15">
					<select id="ENT_F2_ALIGN" name="ENT_F2_ALIGN">
						<option value="LEFT" <?php if ($db_ent['ENT_F2_ALIGN'] == 'LEFT') echo('selected');?>>왼쪽</option>
						<option value="CENTER" <?php if ($db_ent['ENT_F2_ALIGN'] == 'CENTER') echo('selected');?>>가운데</option>
						<option value="RIGHT" <?php if ($db_ent['ENT_F2_ALIGN'] == 'RIGHT') echo('selected');?>>오른쪽</option>
					</select>
					<span class="ico_select"></span>
				</span>

				색상
				<input type="text" id="ENT_F2_COLOR" name="ENT_F2_COLOR" value="<?php echo(F_hsc('#'.$db_ent['ENT_F2_COLOR']))?>" maxlength="7" class="mr15" />

				<div class="nx-namep-deco-wrap mr15">
					<input type="checkbox" id="ENT_F2_BOLD" name="ENT_F2_BOLD" value="Y" class="nx-namep-deco" <?php if ($db_ent['ENT_F2_BOLD'] == 'Y') echo('checked')?> /><label for="ENT_F2_BOLD">B</label>
					<input type="checkbox" id="ENT_F2_UNDERLINE" name="ENT_F2_UNDERLINE" value="Y" class="nx-namep-deco" <?php if ($db_ent['ENT_F2_UNDERLINE'] == 'Y') echo('checked')?> /><label for="ENT_F2_UNDERLINE" class="underline">U</label>
				</div>
			</td>
		</tr>

		<tr>
			<th>C열 내용</th>
			<td>
				크기
				<span class="nx-slt mr15">
					<select id="ENT_F3_SIZE" name="ENT_F3_SIZE">
						<?php
						for ($k = 12; $k <= 48; $k+=2) {
							echo '<option value="'.$k.'" '.(($k == $db_ent['ENT_F3_SIZE']) ? 'selected' : '').'>'.$k.'</option>';
						}
						?>
					</select>
					<span class="ico_select"></span>
				</span>

				왼쪽여백
				<span class="nx-slt mr15">
					<select id="ENT_F3_LEFT" name="ENT_F3_LEFT">
						<?php
						for ($k = 0; $k <= 50; $k+=5) {
							echo '<option value="'.$k.'" '.(($k == $db_ent['ENT_F3_LEFT']) ? 'selected' : '').'>'.$k.'</option>';
						}
						?>
					</select>
					<span class="ico_select"></span>
				</span>

				오른쪽여백
				<span class="nx-slt mr15">
					<select id="ENT_F3_RIGHT" name="ENT_F3_RIGHT">
						<?php
						for ($k = 0; $k <= 50; $k+=5) {
							echo '<option value="'.$k.'" '.(($k == $db_ent['ENT_F3_RIGHT']) ? 'selected' : '').'>'.$k.'</option>';
						}
						?>
					</select>
					<span class="ico_select"></span>
				</span>

				정렬
				<span class="nx-slt mr15">
					<select id="ENT_F3_ALIGN" name="ENT_F3_ALIGN">
						<option value="LEFT" <?php if ($db_ent['ENT_F3_ALIGN'] == 'LEFT') echo('selected');?>>왼쪽</option>
						<option value="CENTER" <?php if ($db_ent['ENT_F3_ALIGN'] == 'CENTER') echo('selected');?>>가운데</option>
						<option value="RIGHT" <?php if ($db_ent['ENT_F3_ALIGN'] == 'RIGHT') echo('selected');?>>오른쪽</option>
					</select>
					<span class="ico_select"></span>
				</span>

				색상
				<input type="text" id="ENT_F3_COLOR" name="ENT_F3_COLOR" value="<?php echo(F_hsc('#'.$db_ent['ENT_F3_COLOR']))?>" maxlength="7" class="mr15" />

				<div class="nx-namep-deco-wrap mr15">
					<input type="checkbox" id="ENT_F3_BOLD" name="ENT_F3_BOLD" value="Y" class="nx-namep-deco" <?php if ($db_ent['ENT_F3_BOLD'] == 'Y') echo('checked')?> /><label for="ENT_F3_BOLD">B</label>
					<input type="checkbox" id="ENT_F3_UNDERLINE" name="ENT_F3_UNDERLINE" value="Y" class="nx-namep-deco" <?php if ($db_ent['ENT_F3_UNDERLINE'] == 'Y') echo('checked')?> /><label for="ENT_F3_UNDERLINE" class="underline">U</label>
				</div>
			</td>
		</tr>

		<tr>
			<th>D열 내용</th>
			<td>
				크기
				<span class="nx-slt mr15">
					<select id="ENT_F4_SIZE" name="ENT_F4_SIZE">
						<?php
						for ($k = 12; $k <= 48; $k+=2) {
							echo '<option value="'.$k.'" '.(($k == $db_ent['ENT_F4_SIZE']) ? 'selected' : '').'>'.$k.'</option>';
						}
						?>
					</select>
					<span class="ico_select"></span>
				</span>

				왼쪽여백
				<span class="nx-slt mr15">
					<select id="ENT_F4_LEFT" name="ENT_F4_LEFT">
						<?php
						for ($k = 0; $k <= 50; $k+=5) {
							echo '<option value="'.$k.'" '.(($k == $db_ent['ENT_F4_LEFT']) ? 'selected' : '').'>'.$k.'</option>';
						}
						?>
					</select>
					<span class="ico_select"></span>
				</span>

				오른쪽여백
				<span class="nx-slt mr15">
					<select id="ENT_F4_RIGHT" name="ENT_F4_RIGHT">
						<?php
						for ($k = 0; $k <= 50; $k+=5) {
							echo '<option value="'.$k.'" '.(($k == $db_ent['ENT_F4_RIGHT']) ? 'selected' : '').'>'.$k.'</option>';
						}
						?>
					</select>
					<span class="ico_select"></span>
				</span>

				정렬
				<span class="nx-slt mr15">
					<select id="ENT_F4_ALIGN" name="ENT_F4_ALIGN">
						<option value="LEFT" <?php if ($db_ent['ENT_F4_ALIGN'] == 'LEFT') echo('selected');?>>왼쪽</option>
						<option value="CENTER" <?php if ($db_ent['ENT_F4_ALIGN'] == 'CENTER') echo('selected');?>>가운데</option>
						<option value="RIGHT" <?php if ($db_ent['ENT_F4_ALIGN'] == 'RIGHT') echo('selected');?>>오른쪽</option>
					</select>
					<span class="ico_select"></span>
				</span>

				색상
				<input type="text" id="ENT_F4_COLOR" name="ENT_F4_COLOR" value="<?php echo(F_hsc('#'.$db_ent['ENT_F4_COLOR']))?>" maxlength="7" class="mr15" />

				<div class="nx-namep-deco-wrap mr15">
					<input type="checkbox" id="ENT_F4_BOLD" name="ENT_F4_BOLD" value="Y" class="nx-namep-deco" <?php if ($db_ent['ENT_F4_BOLD'] == 'Y') echo('checked')?> /><label for="ENT_F4_BOLD">B</label>
					<input type="checkbox" id="ENT_F4_UNDERLINE" name="ENT_F4_UNDERLINE" value="Y" class="nx-namep-deco" <?php if ($db_ent['ENT_F4_UNDERLINE'] == 'Y') echo('checked')?> /><label for="ENT_F4_UNDERLINE" class="underline">U</label>
				</div>
			</td>
		</tr>

		<tr>
			<th>항목 위치 조정</th>
			<td>
				<script src="/adm/lib/jquery-ui.min.js"></script>
				<div id="prev-outer" class="nx-template-preview" style="width:<?php echo((int)$ENT_WIDTH)?>px;height:<?php echo((int)$ENT_HEIGHT)?>px;overflow:hidden;">
					<?php
					echo '<img id="prev-img" src="'.$prev_img.'" style="display:block;position:absolute;top:0;left:0;bottom:0;right:0;z-index:0" />';


					$_f1_sty = 'position:absolute;z-index:1;cursor:pointer;'
						.'top:'.$db_ent['ENT_F1_Y'].'px;'
						.'left:'.$db_ent['ENT_F1_LEFT'].'px;'
						.'right:'.$db_ent['ENT_F1_RIGHT'].'px;'
						.'width:calc(100%-'.($db_ent['ENT_F1_LEFT']+$db_ent['ENT_F1_RIGHT']).'px);'
						.'height:'.$db_ent['ENT_F1_SIZE'].'px;'
						.'font-size:'.$db_ent['ENT_F1_SIZE'].'px;'
						.'text-align:'.$db_ent['ENT_F2_ALIGN'].';'
						;
					$_f1_sty .= ($db_ent['ENT_F1_COLOR'] != '') ? 'color:#'.$db_ent['ENT_F1_COLOR'].';' : '#247de9';
					if ($db_ent['ENT_F1_BOLD'] == 'Y') $_f1_sty .= 'font-weight:bold;';
					if ($db_ent['ENT_F1_UNDERLINE'] == 'Y') $_f1_sty .= 'text-decoration:underline;';


					$_f2_sty = 'position:absolute;z-index:1;cursor:pointer;'
						.'top:'.$db_ent['ENT_F2_Y'].'px;'
						.'left:'.$db_ent['ENT_F2_LEFT'].'px;'
						.'right:'.$db_ent['ENT_F2_RIGHT'].'px;'
						.'width:calc(100%-'.($db_ent['ENT_F2_LEFT']+$db_ent['ENT_F2_RIGHT']).'px);'
						.'height:'.$db_ent['ENT_F2_SIZE'].'px;'
						.'font-size:'.$db_ent['ENT_F2_SIZE'].'px;'
						.'text-align:'.$db_ent['ENT_F2_ALIGN'].';'
						;
					$_f2_sty .= ($db_ent['ENT_F2_COLOR'] != '') ? 'color:#'.$db_ent['ENT_F2_COLOR'].';' : '#5f5f5f';
					if ($db_ent['ENT_F2_BOLD'] == 'Y') $_f2_sty .= 'font-weight:bold;';
					if ($db_ent['ENT_F2_UNDERLINE'] == 'Y') $_f2_sty .= 'text-decoration:underline;';


					$_f3_sty = 'position:absolute;z-index:1;cursor:pointer;'
						.'top:'.$db_ent['ENT_F3_Y'].'px;'
						.'left:'.$db_ent['ENT_F3_LEFT'].'px;'
						.'right:'.$db_ent['ENT_F3_RIGHT'].'px;'
						.'width:calc(100%-'.($db_ent['ENT_F3_LEFT']+$db_ent['ENT_F3_RIGHT']).'px);'
						.'height:'.$db_ent['ENT_F3_SIZE'].'px;'
						.'font-size:'.$db_ent['ENT_F3_SIZE'].'px;'
						.'text-align:'.$db_ent['ENT_F3_ALIGN'].';'
						;
					$_f3_sty .= ($db_ent['ENT_F3_COLOR'] != '') ? 'color:#'.$db_ent['ENT_F3_COLOR'].';' : '#000000';
					if ($db_ent['ENT_F3_BOLD'] == 'Y') $_f3_sty .= 'font-weight:bold;';
					if ($db_ent['ENT_F3_UNDERLINE'] == 'Y') $_f3_sty .= 'text-decoration:underline;';


					$_f4_sty = 'position:absolute;z-index:1;cursor:pointer;'
						.'top:'.$db_ent['ENT_F4_Y'].'px;'
						.'left:'.$db_ent['ENT_F4_LEFT'].'px;'
						.'right:'.$db_ent['ENT_F4_RIGHT'].'px;'
						.'width:calc(100%-'.($db_ent['ENT_F4_LEFT']+$db_ent['ENT_F4_RIGHT']).'px);'
						.'height:'.$db_ent['ENT_F4_SIZE'].'px;'
						.'font-size:'.$db_ent['ENT_F4_SIZE'].'px;'
						.'text-align:'.$db_ent['ENT_F4_ALIGN'].';'
						;
					$_f4_sty .= ($db_ent['ENT_F4_COLOR'] != '') ? 'color:#'.$db_ent['ENT_F4_COLOR'].';' : '#222';
					if ($db_ent['ENT_F4_BOLD'] == 'Y') $_f4_sty .= 'font-weight:bold;';
					if ($db_ent['ENT_F4_UNDERLINE'] == 'Y') $_f4_sty .= 'text-decoration:underline;';


					echo '<div id="prev-item1" class="item" style="overflow:hidden;'.$_f1_sty.'">A열 입력 내용</div>';
					echo '<div id="prev-item2" class="item" style="overflow:hidden;'.$_f2_sty.'">B열 입력 내용</div>';
					echo '<div id="prev-item3" class="item" style="overflow:hidden;'.$_f3_sty.'">C열 입력 내용</div>';
					echo '<span id="prev-item4" style="'.$_f4_sty.'" class="serial-num">D열 입력 내용</span>';

					unset($_f1_sty, $_f2_sty, $_f3_sty, $_f4_sty);
					?>
				</div>

				<script>
				//<![CDATA[
					$(function() {
						$('#prev-item1').draggable({containment:'parent',axis:'y'});
						$('#prev-item2').draggable({containment:'parent',axis:'y'});
						$('#prev-item3').draggable({containment:'parent',axis:'y'});
						$('#prev-item4').draggable({containment:'parent',axis:'y'});
					});
				//]]>
				</script>
			</td>
		</tr>
	</tbody>
</table>
</form>

<div class="ofH mt10">
	<div class="fL">
		<a href="javascript:void(0)" onclick="tpl.del();" class="nx-btn-b4">삭제</a>
	</div>
	<div class="fR">
		<a href="javascript:void(0)" onclick="tpl.save();" class="nx-btn-b2">저장</a>
		<a href="javascript:void(0)" onclick="nt.pdf()" class="nx-btn-b2" style="background-color:#3561b4">PDF 다운로드</a>
	</div>
</div>

<script>
//<![CDATA[
var prev_outer = $('#prev-outer'), prev_item1 = $('#prev-item1'), prev_item2 = $('#prev-item2'), prev_item3 = $('#prev-item3'), prev_item4 = $('#prev-item4')
	, f1_color_outer = $('#f1-color-outer'), f2_color_outer = $('#f2-color-outer')
	, maxSize = {'ww':'<?php echo($maxSize['ww'])?>', 'hh':'<?php echo($maxSize['hh'])?>'}
;
$(function() {
	$('#ENT_WIDTH').on({
		keyup: function(e) {
			var v = $(this).val();
			if (v != '' && !isNaN(v)) {
				$('#span_ENT_WIDTH').text(Math.round(parseInt(v) * (72 / 25.4)));
			}
		}
		, change: function(e) {
			var $this = $(this), v = $this.val();
			if (v != '' && isNaN(v)) {
				alert("사이즈(가로) 정보를 숫자로 입력해 주세요.");
				$this.focus(); return;
			}
			if (v < 35 || v > parseInt(maxSize['ww'])) {
				alert('35~'+maxSize['ww']+' 이내의 숫자로 입력해 주세요.');
				v = maxSize['ww'];
				$this.val(v).focus();
				$('#span_ENT_WIDTH').text(Math.round(parseInt(v) * (72 / 25.4)));
			}

			prev_outer.css('width',Math.round(parseInt(v) * (72 / 25.4))+'px');
		}
	});
	$('#ENT_HEIGHT').on({
		keyup: function(e) {
			var v = $(this).val();
			if (v != '' && !isNaN(v)) {
				$('#span_ENT_HEIGHT').text(Math.round(parseInt(v) * (72 / 25.4)));
			}
		}
		, change: function(e) {
			var $this = $(this), v = $this.val();
			if (v != '' && isNaN(v)) {
				alert("사이즈(세로) 정보를 숫자로 입력해 주세요.");
				$this.focus(); return;
			}
			if (v < 35 || v > parseInt(maxSize['hh'])) {
				alert('35~'+maxSize['hh']+' 이내의 숫자로 입력해 주세요.');
				v = maxSize['hh'];
				$this.val(v).focus();
				$('#span_ENT_HEIGHT').text(Math.round(parseInt(v) * (72 / 25.4)));
			}

			prev_outer.css('height',Math.round(parseInt(v) * (72 / 25.4))+'px');
		}
	});

	$('#ENT_F1_SIZE, #ENT_F2_SIZE, #ENT_F3_SIZE, #ENT_F4_SIZE').change(function(e) {
		var $this = $(this), id = $this.attr('id'), v = $this.val(), seq = '';
		if (v == '') return;

		if (id.indexOf('_F1_') !== -1) seq = 1;
		else if (id.indexOf('_F2_') !== -1) seq = 2;
		else if (id.indexOf('_F3_') !== -1) seq = 3;
		else if (id.indexOf('_F4_') !== -1) seq = 4;

		$('#prev-item'+seq).css({'font-size':v+'px',height:v+'px'});
	});

	$('#ENT_F1_LEFT, #ENT_F2_LEFT, #ENT_F3_LEFT, #ENT_F4_LEFT').change(function(e) {
		var $this = $(this), id = $this.attr('id'), v = $this.val(), seq = '';
		if (v == '') return;

		if (id.indexOf('_F1_') !== -1) seq = 1;
		else if (id.indexOf('_F2_') !== -1) seq = 2;
		else if (id.indexOf('_F3_') !== -1) seq = 3;
		else if (id.indexOf('_F4_') !== -1) seq = 4;

		$('#prev-item'+seq).css({'left':v+'px',width:'calc(100%-'+(parseInt(v)+parseInt($('#ENT_F'+seq+'_RIGHT').val()))+'px)'});
	});

	$('#ENT_F1_RIGHT, #ENT_F2_RIGHT, #ENT_F3_RIGHT, #ENT_F4_RIGHT').change(function(e) {
		var $this = $(this), id = $this.attr('id'), v = $this.val(), seq = '';
		if (v == '') return;

		if (id.indexOf('_F1_') !== -1) seq = 1;
		else if (id.indexOf('_F2_') !== -1) seq = 2;
		else if (id.indexOf('_F3_') !== -1) seq = 3;
		else if (id.indexOf('_F4_') !== -1) seq = 4;

		$('#prev-item'+seq).css({'right':v+'px',width:'calc(100%-'+(parseInt(v)+parseInt($('#ENT_F'+seq+'_LEFT').val()))+'px)'});
	});

	$('#ENT_F1_ALIGN, #ENT_F2_ALIGN, #ENT_F3_ALIGN, #ENT_F4_ALIGN').change(function(e) {
		var $this = $(this), id = $this.attr('id'), v = $this.val(), seq = '';
		if (v == '') return;

		if (id.indexOf('_F1_') !== -1) seq = 1;
		else if (id.indexOf('_F2_') !== -1) seq = 2;
		else if (id.indexOf('_F3_') !== -1) seq = 3;
		else if (id.indexOf('_F4_') !== -1) seq = 4;

		$('#prev-item'+seq).css({'text-align':v.toLowerCase()});

		if (v == 'LEFT') {
			$('#prev-item'+seq).css({'left': $('#ENT_F'+seq+'_LEFT').val()+'px'});
		}
		else if (v == 'RIGHT') {
			$('#prev-item'+seq).css({'right': $('#ENT_F'+seq+'_RIGHT').val()+'px'});
		}
	});

	$("#ENT_F1_COLOR, #ENT_F2_COLOR, #ENT_F3_COLOR, #ENT_F4_COLOR").spectrum({
	    change: function(color) {
    		var $this = $(this), id = $this.attr('id'), seq = '';

    		$('#'+id).val(color.toHex());

    		if (id.indexOf('_F1_') !== -1) seq = 1;
    		else if (id.indexOf('_F2_') !== -1) seq = 2;
    		else if (id.indexOf('_F3_') !== -1) seq = 3;
    		else if (id.indexOf('_F4_') !== -1) seq = 4;

			$('#prev-item'+seq).css('color',color.toHexString());
	    }
	});

	$('#ENT_F1_BOLD, #ENT_F2_BOLD, #ENT_F3_BOLD, #ENT_F4_BOLD').click(function(e) {
		var $this = $(this), id = $this.attr('id'), v = $this.val()
			, seq = ''
			;

		if (id.indexOf('_F1_') !== -1) seq = 1;
		else if (id.indexOf('_F2_') !== -1) seq = 2;
		else if (id.indexOf('_F3_') !== -1) seq = 3;
		else if (id.indexOf('_F4_') !== -1) seq = 4;

		$('#prev-item'+seq).css('font-weight', (($this.prop('checked')) ? 'bold' : 'normal'));
	});

	$('#ENT_F1_UNDERLINE, #ENT_F2_UNDERLINE, #ENT_F3_UNDERLINE, #ENT_F4_UNDERLINE').click(function(e) {
		var $this = $(this), id = $this.attr('id'), v = $this.val()
			, seq = ''
			;

		if (id.indexOf('_F1_') !== -1) seq = 1;
		else if (id.indexOf('_F2_') !== -1) seq = 2;
		else if (id.indexOf('_F3_') !== -1) seq = 3;
		else if (id.indexOf('_F4_') !== -1) seq = 4;

		$('#prev-item'+seq).css('text-decoration', (($this.prop('checked')) ? 'underline' : 'none'));
	});

	var file = document.querySelector('#ent_file');
	file.onchange = function () { 
	    var fileList = file.files;

	    var fileName = file.value.toLowerCase().split('.').reverse();

	    if ( fileName[0].length > 1 && (fileName[0] != 'jpg' && fileName[0] != 'jpeg') )
	    {
	    	alert('jpg/jpeg 파일만 등록할 수 있습니다.');
	    	// value 초기화
	    	file.value = '';
	    	return false;
	    }
	    
	    
	    // 읽기
	    var reader = new FileReader();
	    reader.readAsDataURL(fileList[0]);

	    reader.onload = function() {
	        var tempImage = new Image();
	        tempImage.src = reader.result;
	        
	        tempImage.onload = function() {
	            var canvas = document.createElement('canvas');
	            var canvasContext = canvas.getContext("2d");
	            
	            <?php /* 원본이미지 width를 기준으로 modify height 조정 */ ?>
	            var o_ww = this.width, o_hh = this.height, e_ww = parseInt(Math.round(parseInt($('#ENT_WIDTH').val()) * (72 / 25.4)));
	            var ratio = (o_ww > e_ww) ? parseInt(o_ww/e_ww) : 1;
	            var _ww = parseInt(e_ww);
	            var _hh = parseInt(o_hh/ratio);

	            canvas.width = _ww;
	            canvas.height = _hh;
	            
	            canvasContext.drawImage(this, 0, 0, _ww, _hh);
	            var dataURI = canvas.toDataURL("image/jpeg");

	            document.querySelector('#prev-img').src = dataURI;
	        };
	    }; 
	};
});

var tpl = {
	save: function() {
		var _t = $('#ENT_TITLE');
		if (_t.val() == '') {
			alert('템플릿명 정보를 입력해 주세요.');
			_t.focus(); return;
		}

		var _t = $('#ENT_WIDTH'), _tv = _t.val();
		if (_tv == '' || isNaN(_tv)) {
			alert('사이즈(가로) 정보를 숫자로 바르게 입력해 주세요.');
			_t.focus(); return;
		}
		var _t = $('#ENT_HEIGHT'), _tv = _t.val();
		if (_tv == '' || isNaN(_tv)) {
			alert('사이즈(세로) 정보를 숫자로 바르게 입력해 주세요.');
			_t.focus(); return;
		}

		var _t = $('#ENT_F1_COLOR');
		if (_t.val() == '') {
			alert('A열 내용 색상값을 입력해 주세요.');
			_t.focus(); return;
		}

		var _t = $('#ENT_F2_COLOR');
		if (_t.val() == '') {
			alert('B열 내용 색상값을 입력해 주세요.');
			_t.focus(); return;
		}

		var _t = $('#ENT_F3_COLOR');
		if (_t.val() == '') {
			alert('C열 내용 색상값을 입력해 주세요.');
			_t.focus(); return;
		}

		var _t = $('#ENT_F4_COLOR');
		if (_t.val() == '') {
			alert('D열 내용 색상값을 입력해 주세요.');
			_t.focus(); return;
		}

		if (confirm("입력하신 사항으로 진행하시겠습니까?"))
		{
			var f = new FormData($('#frmEdit')[0]);
			f.append('ENT_F1_Y', prev_item1.css('top').replace('px',''));
			f.append('ENT_F2_Y', prev_item2.css('top').replace('px',''));
			f.append('ENT_F3_Y', prev_item3.css('top').replace('px',''));
			f.append('ENT_F4_Y', prev_item4.css('top').replace('px',''));

			$.ajax({
				url: 'nametag.custom.editProc.php',
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
	, del: function() {
		if (confirm("삭제된 정보는 복구가 불가능 합니다.\n\n삭제 하시겠습니까?"))
		{
			var f = new FormData();
			f.append('ENC_IDX', '<?php echo $ENC_IDX?>');
			f.append('ENT_IDX', '<?php echo $db_ent['ENT_IDX']?>');

			$.ajax({
				url: 'nametag.custom.delProc.php', 
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
}
//]]>
</script>
	<?php
}
/*----------------------------------------------------------------------*/
?>

<?php
	//마지막 인클루드
	include_once G5_ADMIN_PATH."/admin.tail.php";
?>
