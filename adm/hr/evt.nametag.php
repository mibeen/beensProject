<?php
	include_once('./_common.php');
	include_once('./evt.err.php'); 	# $sub_menu = ($EP_IDX > 0) ? '980100' : '990100'

	if ($EP_IDX != "") {
		$auth['980100'] = 'r,w';
	}

	auth_check($auth[$sub_menu], "w");


	# chk : rfv.
	if ($EM_IDX <= 0) {
		header("location:evt.list.php?".$epTail);
		exit();
	}


	$sql = "Select EM.EM_IDX, EM.EM_TITLE"
		."		, ENT.ENT_IDX"
		."	From NX_EVENT_MASTER As EM"
		."		Left Join NX_EVENT_NAMETAG_TEMPLATE As ENT On ENT.EM_IDX = EM.EM_IDX"
		."			And ENT.ENT_DDATE is null"
		."	Where EM.EM_DDATE is null"
		."		And EM.EM_IDX = '" . mres($EM_IDX) . "'"
		."	Limit 1"
		;
	$row = sql_fetch($sql);
	if (is_null($row['EM_IDX'])) {
		F_script("요청하신 정보가 존재하지 않습니다.", "window.location.href='evt.list.php?".$epTail."';");
	}


	$g5[title] = ($sub_menu == '980100') ? "공모사업 행사 명찰관리" : "사업관리";
	include_once('../admin.head.php');
?>

<div class="taR mb10">
	<a href="evt.list.php?<?php echo($epTail)?>" class="nx-btn-b3">목록으로</a>
</div>

<form id="frmAct" name="frmAct" onsubmit="return false">
	<input type="hidden" name="EM_IDX" value="<?php echo $EM_IDX?>" />
	<table border="0" cellpadding="0" cellspacing="0" class="nx-t-read1">
		<colgroup>
			<col width="130"><col width="">
		</colgroup>
		<tbody>
			<tr>
				<th>행사명</th>
				<td>
					<?php
					if ((int)$row['ENT_IDX'] > 0) {
						echo '<input type="text" name="EM_TITLE" value="'.$row['EM_TITLE'].'" class="nx-ips1 wl">';
						echo help('변경한 행사명은 저장되지 않습니다. 행사명을 변경한 상태에서 PDF 다운로드 버튼을 누르세요.');
					}
					else echo F_hsc($row['EM_TITLE'])
					?>
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
							$sql = "Select ENT_IDX, ENT_TITLE From NX_EVENT_NAMETAG_TEMPLATE Where ENT_DDATE is null And EM_IDX is null Order By ENT_IDX Desc";
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
		var url = 'evt.nametag.pdf.php?<?php echo($epTail)?>';
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
		var _t = $('#ENT_IDX');
		if (_t.val() == '') {
			alert("템플릿 정보를 선택해 주세요.");
			_t.focus(); return;
		}

		if (confirm("선택하신 템플릿을 가져오시겠습니까?\n\n이전 정보가 있을 경우 신규 템플릿 정보로 대체 됩니다.")) {
			var f = new FormData();
			f.append('EM_IDX', '<?php echo $EM_IDX?>');
			f.append('ENT_IDX', _t.val());

			$.ajax({
				url: 'evt.nametag.tpl.copyProc.php?<?php echo($epTail)?>', 
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
				window.location.href = "evt.nametag.php?<?php echo($epTail)?>EM_IDX=<?php echo $EM_IDX?>";
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
<input type="hidden" id="EP_IDX" name="EP_IDX" value="<?php echo $EP_IDX?>" />
<input type="hidden" id="EM_IDX" name="EM_IDX" value="<?php echo $EM_IDX?>" />
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
		<tr>
			<th>배경이미지</th>
			<td>
				<p class="red text-left">* JPG 형태의 이미지만 올려주세요</p>
				<input type="file" id="ent_file" name="ent_file[]" class="dsIB" accept="image/*" />
				<span class="nx-tip">(2배 이미지를 올리시면 선명합니다.)</span>

				<?php
				$_dir = "NX_EVENT_NAMETAG_TEMPLATE";
				$_files = get_file('NX_EVENT_NAMETAG_TEMPLATE', $row['ENT_IDX']);
				$prev_img = 'data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==';
				for ($k = 0; $k < Count($_files); $k++) {
					$_file = $_files[$k];

					if (!$_file['file']) continue;

					if (file_exists(G5_DATA_PATH.'/file/'.$_dir.'/'.$_file['file'])) {
						echo '<div style="margin-top:5px;">';
							echo '파일명 : '.$_file['source'].' &nbsp; ';
							echo '<input type="checkbox" id="ent_file_del_'.$k.'" name="ent_file_del[]" value="Y" /> <label for="ent_file_del_'.$k.'">삭제</label><br/>';
						echo '</div>';
						// echo '<img src="'.$_file['path'].'/'.$_file['file'].'" alt="" style="max-width:300px;margin-top:5px;" />';

						$prev_img = $_file['path'].'/'.$_file['file'];
					}
				}
				unset($_dir, $_files);
				?>
			</td>
		</tr>

		<tr>
			<th>A 내용</th>
			<td>
				항목명
				<span class="nx-slt mr15">
					<select id="ENT_F1_KIND" name="ENT_F1_KIND">
						<option value="">미선택</option>
						<option value="NAME" <?php if ($db_ent['ENT_F1_KIND'] == 'NAME') echo('selected');?>>이름</option>
						<option value="MOBILE" <?php if ($db_ent['ENT_F1_KIND'] == 'MOBILE') echo('selected');?>>휴대전화번호</option>
						<option value="EMAIL" <?php if ($db_ent['ENT_F1_KIND'] == 'EMAIL') echo('selected');?>>이메일</option>
						<option value="ORG" <?php if ($db_ent['ENT_F1_KIND'] == 'ORG') echo('selected');?>>소속</option>
						<option value="EM_TITLE" <?php if ($db_ent['ENT_F1_KIND'] == 'EM_TITLE') echo('selected');?>>행사명</option>
					</select>
					<span class="ico_select"></span>
				</span>

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
			<th>B 내용</th>
			<td>
				항목명
				<span class="nx-slt mr15">
					<select id="ENT_F2_KIND" name="ENT_F2_KIND">
						<option value="">미선택</option>
						<option value="NAME" <?php if ($db_ent['ENT_F2_KIND'] == 'NAME') echo('selected');?>>이름</option>
						<option value="MOBILE" <?php if ($db_ent['ENT_F2_KIND'] == 'MOBILE') echo('selected');?>>휴대전화번호</option>
						<option value="EMAIL" <?php if ($db_ent['ENT_F2_KIND'] == 'EMAIL') echo('selected');?>>이메일</option>
						<option value="ORG" <?php if ($db_ent['ENT_F2_KIND'] == 'ORG') echo('selected');?>>소속</option>
						<option value="EM_TITLE" <?php if ($db_ent['ENT_F2_KIND'] == 'EM_TITLE') echo('selected');?>>행사명</option>
					</select>
					<span class="ico_select"></span>
				</span>

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
			<th>C 내용</th>
			<td>
				항목명
				<span class="nx-slt mr15">
					<select id="ENT_F3_KIND" name="ENT_F3_KIND">
						<option value="">미선택</option>
						<option value="NAME" <?php if ($db_ent['ENT_F3_KIND'] == 'NAME') echo('selected');?>>이름</option>
						<option value="MOBILE" <?php if ($db_ent['ENT_F3_KIND'] == 'MOBILE') echo('selected');?>>휴대전화번호</option>
						<option value="EMAIL" <?php if ($db_ent['ENT_F3_KIND'] == 'EMAIL') echo('selected');?>>이메일</option>
						<option value="ORG" <?php if ($db_ent['ENT_F3_KIND'] == 'ORG') echo('selected');?>>소속</option>
						<option value="EM_TITLE" <?php if ($db_ent['ENT_F3_KIND'] == 'EM_TITLE') echo('selected');?>>행사명</option>
					</select>
					<span class="ico_select"></span>
				</span>

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
			<th>코드</th>
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


					$_arr = ['NAME'=>'홍길동', 'MOBILE'=>'010-1234-1234', 'EMAIL'=>'example@example.com', 'ORG'=>'경기도평생교육진흥원', 'EM_TITLE'=>'경기도평생교육진흥원 행사'];


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


					echo '<div id="prev-item1" class="item" style="overflow:hidden;'.$_f1_sty.'">'.(($db_ent['ENT_F1_KIND'] == '') ? '' : ('(A)'.F_hsc($_arr[$db_ent['ENT_F1_KIND']]))).'</div>';
					echo '<div id="prev-item2" class="item" style="overflow:hidden;'.$_f2_sty.'">'.(($db_ent['ENT_F2_KIND'] == '') ? '' : ('(B)'.F_hsc($_arr[$db_ent['ENT_F2_KIND']]))).'</div>';
					echo '<div id="prev-item3" class="item" style="overflow:hidden;'.$_f3_sty.'">'.(($db_ent['ENT_F3_KIND'] == '') ? '' : ('(C)'.F_hsc($_arr[$db_ent['ENT_F3_KIND']]))).'</div>';
					echo '<span id="prev-item4" style="'.$_f4_sty.'" class="serial-num">'.substr(date('Ymd'), 2).'-A001</span>';

					unset($_arr, $_f1_sty, $_f2_sty, $_f3_sty, $_f4_sty);
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

	$('#ENT_F1_KIND, #ENT_F2_KIND, #ENT_F3_KIND').change(function(e) {
		var $this = $(this), id = $this.attr('id'), v = $this.val()
			, cfval = {'NAME':'홍길동', 'MOBILE':'010-1234-1234', 'EMAIL':'example@example.com', 'ORG':'경기도평생교육진흥원', 'EM_TITLE':'경기도평생교육진흥원 행사명'};
		if (id.indexOf('_F1_') !== -1) {
			prev_item1.text((v == '') ? '' : ('(A)'+cfval[v]));
		}
		else if (id.indexOf('_F2_') !== -1) {
			prev_item2.text((v == '') ? '' : ('(B)'+cfval[v]));
		}
		else if (id.indexOf('_F3_') !== -1) {
			prev_item3.text((v == '') ? '' : ('(C)'+cfval[v]));
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
			alert('A 내용 색상값을 입력해 주세요.');
			_t.focus(); return;
		}

		var _t = $('#ENT_F2_COLOR');
		if (_t.val() == '') {
			alert('B 내용 색상값을 입력해 주세요.');
			_t.focus(); return;
		}

		var _t = $('#ENT_F3_COLOR');
		if (_t.val() == '') {
			alert('C 내용 색상값을 입력해 주세요.');
			_t.focus(); return;
		}

		var _t = $('#ENT_F4_COLOR');
		if (_t.val() == '') {
			alert('코드 색상값을 입력해 주세요.');
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
				url: 'evt.nametag.tpl.editProc.php?<?php echo($epTail)?>',
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
			f.append('EM_IDX', '<?php echo $EM_IDX?>');
			f.append('ENT_IDX', '<?php echo $db_ent['ENT_IDX']?>');

			$.ajax({
				url: 'evt.nametag.tpl.delProc.php?<?php echo($epTail)?>', 
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
