<?php
	include_once('./_common.php');
	include_once('./evt.err.php'); 	# $sub_menu = ($EP_IDX > 0) ? '980100' : '990100'
	include_once G5_EDITOR_PATH.'/'.$config['cf_editor'].'/editor.lib.php';

	auth_check($auth[$sub_menu], "w");


	if ($EP_IDX != "") {
		$sql = "Select EP_TITLE"
			. " From NX_EVENT_PROJECT"
			. " Where EP_DDATE is null And EP_IDX = '" . mres($EP_IDX) . "'"
			. " Limit 1"
			;
		$db_ep = sql_fetch($sql);
	}


	# 임시 data 삭제
	NX_EVENT_FORM_BUILDER::SET_DEL_TEMP();


	$g5[title] = ($sub_menu == '980100') ? "공모사업 행사등록" : "사업관리";
	include_once('../admin.head.php');
	include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');
?>

<form id="frmAdd" name="frmAdd" enctype="multipart/form-data" method="post" onsubmit="return false;">
<input type="submit" style="width:1px;height:1px;position:absolute;top:-10000px;left:-10000px" />
<input type="hidden" id="EP_IDX" name="EP_IDX" value="<?php echo($EP_IDX)?>">

<div class="ofH mb10">
	<?php
	if ($EP_IDX != "") {
		?>
	<div class="fL" style="width: 800px;">
		<span class="dsIB mr15" style="font-weight: bold; color: #444;">공모사업명</span> 
		<input type="text" id="EP_TITLE" name="EP_TITLE" class="nx-ips1 wl" value="<?php echo($db_ep['EP_TITLE'])?>" disabled>
	</div>
		<?php
	}
	?>
	<div class="fR">
		<a href="javascript:onclCancel();" class="nx-btn-b3">뒤로</a>
	</div>
</div>

<table border="0" cellpadding="0" cellspacing="0" class="nx-t-read1">
	<colgroup>
		<col width="130"><col width="">
	</colgroup>
	<tbody>
		<tr>
			<th>카테고리</th>
			<td>
				<span class="nx-slt">
					<select id="EC_IDX" name="EC_IDX">
						<option value="">카테고리 선택</option>
						<?php
						$sql = "Select EC_IDX, EC_NAME From NX_EVENT_CATE Order By EC_SEQ Asc, EC_IDX Desc";
						$db1 = sql_query($sql);

						while ($rs1 = sql_fetch_array($db1)) {
							echo '<option value="'.(int)$rs1['EC_IDX'].'">'.htmlspecialchars($rs1['EC_NAME']).'</option>';
						}
						unset($db1, $rs1);
						?>
					</select>
					<span class="ico_select"></span>
				</span>
			</td>
		</tr>
		<tr>
			<th><span class="red">*</span>제목</th>
			<td><input type="text" id="EM_TITLE" name="EM_TITLE" maxlength="100" class="nx-ips1 wl" /></td>
		</tr>
		<tr>
			<th><span class="red">*</span>분류</th>
			<td>
				<div class="radio1_wrap">
					<input type="radio" id="EM_TYPE1" name="EM_TYPE" class="radio1" value="1" /><label for="EM_TYPE1"><span class="radbox"><span></span></span><span class="txt">행사</span></label>
					<input type="radio" id="EM_TYPE2" name="EM_TYPE" class="radio1" value="2" /><label for="EM_TYPE2"><span class="radbox"><span></span></span><span class="txt">교육</span></label>
				</div>
			</td>
		</tr>
		<tr>
			<th><span class="red">*</span>승인방식</th>
			<td>
				<div class="radio1_wrap">
					<input type="radio" id="EM_JOIN_TYPE1" name="EM_JOIN_TYPE" class="radio1" value="1" /><label for="EM_JOIN_TYPE1"><span class="radbox"><span></span></span><span class="txt">선착순</span></label>
					<input type="radio" id="EM_JOIN_TYPE2" name="EM_JOIN_TYPE" class="radio1" value="2" /><label for="EM_JOIN_TYPE2"><span class="radbox"><span></span></span><span class="txt">승인</span></label>
				</div>
			</td>
		</tr>

		<tr>
			<th>자동알림</th>
			<td>
				<div class="chk1_wrap">
					<input type="checkbox" id="EM_NOTI_EMAIL" name="EM_NOTI_EMAIL" class="chk1" value="Y" /><label for="EM_NOTI_EMAIL"><span class="chkbox"><span class="ico_check"></span></span><span class="txt">e-mail</span></label>
					<input type="checkbox" id="EM_NOTI_SMS" name="EM_NOTI_SMS" class="chk1" value="Y" checked /><label for="EM_NOTI_SMS"><span class="chkbox"><span class="ico_check"></span></span><span class="txt">SMS</span></label>
				</div>
			</td>
		</tr>

		<tr>
			<th><span class="red">*</span>행사 시작일</th>
			<td>
				<!-- <span class="dsIB mr15">
					<span class="nx-slt">
						<select id="EM_S_DATE1" name="EM_S_DATE1">
							<option value="">년</option>
							<?php
							for ($i = 2017; $i <= (date('Y') + 5); $i++) {
								echo '<option value="'.(int)$i.'">'.(int)$i.'</option>';
							}
							?>
						</select>
						<span class="ico_select"></span>
					</span>
					<span class="nx-slt">
						<select id="EM_S_DATE2" name="EM_S_DATE2">
							<option value="">월</option>
							<?php
							for ($i = 1; $i <= 12; $i++) {
								$_i = ($i < 10) ? ('0'.$i) : $i;
								echo '<option value="'.$_i.'">'.$_i.'</option>';
							}
							?>
						</select>
						<span class="ico_select"></span>
					</span>
					<span class="nx-slt">
						<select id="EM_S_DATE3" name="EM_S_DATE3">
							<option value="">일</option>
							<?php
							for ($i = 1; $i <= 31; $i++) {
								$_i = ($i < 10) ? ('0'.$i) : $i;
								echo '<option value="'.$_i.'">'.$_i.'</option>';
							}
							?>
						</select>
						<span class="ico_select"></span>
					</span>
				</span> -->
				<input type="text" name="EM_S_DATE" id="EM_S_DATE" class="nx-ips1 ws" readonly>
			</td>
		</tr>
		<tr>
			<th><span class="red">*</span>행사 종료일</th>
			<td>
				<!-- <span class="dsIB mr15">
					<span class="nx-slt">
						<select id="EM_E_DATE1" name="EM_E_DATE1">
							<option value="">년</option>
							<?php
							for ($i = 2017; $i <= (date('Y') + 5); $i++) {
								echo '<option value="'.(int)$i.'">'.(int)$i.'</option>';
							}
							?>
						</select>
						<span class="ico_select"></span>
					</span>
					<span class="nx-slt">
						<select id="EM_E_DATE2" name="EM_E_DATE2">
							<option value="">월</option>
							<?php
							for ($i = 1; $i <= 12; $i++) {
								$_i = ($i < 10) ? ('0'.$i) : $i;
								echo '<option value="'.$_i.'">'.$_i.'</option>';
							}
							?>
						</select>
						<span class="ico_select"></span>
					</span>
					<span class="nx-slt">
						<select id="EM_E_DATE3" name="EM_E_DATE3">
							<option value="">일</option>
							<?php
							for ($i = 1; $i <= 31; $i++) {
								$_i = ($i < 10) ? ('0'.$i) : $i;
								echo '<option value="'.$_i.'">'.$_i.'</option>';
							}
							?>
						</select>
						<span class="ico_select"></span>
					</span>
				</span> -->
				<input type="text" name="EM_E_DATE" id="EM_E_DATE" class="nx-ips1 ws" readonly>
			</td>
		</tr>
		<tr>
			<th><span class="red">*</span>시작시간</th>
			<td>
				<?php /*<span class="dsIB mr15">
					<span class="nx-slt">
						<select id="EM_S_TIME1" name="EM_S_TIME1">
							<?php
							for ($i = 0; $i <= 23; $i++) {
								$_i = ($i < 10) ? ('0'.$i) : $i;
								echo '<option value="'.$_i.'">'.$_i.'</option>';
							}
							?>
						</select>
						<span class="ico_select"></span>
					</span>
					:
					<span class="nx-slt">
						<select id="EM_S_TIME2" name="EM_S_TIME2">
							<?php
							for ($i = 0; $i <= 59; $i++) {
								$_i = ($i < 10) ? ('0'.$i) : $i;
								echo '<option value="'.$_i.'">'.$_i.'</option>';
							}
							?>
						</select>
						<span class="ico_select"></span>
					</span>
				</span>*/ ?>
				<input type="number" id="EM_S_TIME1" name="EM_S_TIME1" class="nx-ips1 ws3" min="0" max="23">
				:
				<input type="number" id="EM_S_TIME2" name="EM_S_TIME2" class="nx-ips1 ws3" min="0" max="59">
			</td>
		</tr>
		<tr>
			<th><span class="red">*</span>종료시간</th>
			<td>
				<?php /*<span class="dsIB mr15">
					<span class="nx-slt">
						<select id="EM_E_TIME1" name="EM_E_TIME1">
							<?php
							for ($i = 0; $i <= 23; $i++) {
								$_i = ($i < 10) ? ('0'.$i) : $i;
								echo '<option value="'.$_i.'">'.$_i.'</option>';
							}
							?>
						</select>
						<span class="ico_select"></span>
					</span>
					:
					<span class="nx-slt">
						<select id="EM_E_TIME2" name="EM_E_TIME2">
							<?php
							for ($i = 0; $i <= 59; $i++) {
								$_i = ($i < 10) ? ('0'.$i) : $i;
								echo '<option value="'.$_i.'">'.$_i.'</option>';
							}
							?>
						</select>
						<span class="ico_select"></span>
					</span>
				</span>*/ ?>
				<input type="number" id="EM_E_TIME1" name="EM_E_TIME1" class="nx-ips1 ws3" min="0" max="23">
				:
				<input type="number" id="EM_E_TIME2" name="EM_E_TIME2" class="nx-ips1 ws3" min="0" max="59">
			</td>
		</tr>
		<tr>
			<th>이수시간</th>
			<td><input type="number" id="EM_CERT_TIME" name="EM_CERT_TIME" class="nx-ips1 ws3" min="0"> 시간&nbsp;&nbsp;&nbsp;<input type="number" id="EM_CERT_MINUTE" name="EM_CERT_MINUTE" class="nx-ips1 ws3" min="0"> 분</td>
		</tr>
		<tr>
			<th><span class="red">*</span>신청기간</th>
			<td>
				<span class="dsIB mr10">
					<span class="nx-slt">
						<select id="EM_JOIN_S_DATE1" name="EM_JOIN_S_DATE1">
							<option value="">년</option>
							<?php
							for ($i = 2017; $i <= (date('Y') + 5); $i++) {
								echo '<option value="'.(int)$i.'">'.(int)$i.'</option>';
							}
							?>
						</select>
						<span class="ico_select"></span>
					</span>
					<span class="nx-slt">
						<select id="EM_JOIN_S_DATE2" name="EM_JOIN_S_DATE2">
							<option value="">월</option>
							<?php
							for ($i = 1; $i <= 12; $i++) {
								$_i = ($i < 10) ? ('0'.$i) : $i;
								echo '<option value="'.$_i.'">'.$_i.'</option>';
							}
							?>
						</select>
						<span class="ico_select"></span>
					</span>
					<span class="nx-slt">
						<select id="EM_JOIN_S_DATE3" name="EM_JOIN_S_DATE3">
							<option value="">일</option>
							<?php
							for ($i = 1; $i <= 31; $i++) {
								$_i = ($i < 10) ? ('0'.$i) : $i;
								echo '<option value="'.$_i.'">'.$_i.'</option>';
							}
							?>
						</select>
						<span class="ico_select"></span>
					</span>
				</span>
				<span class="dsIB vaT" style="width:120px;">
					<input type="number" id="EM_JOIN_S_TIME1" name="EM_JOIN_S_TIME1" min="0" max="23" class="nx-ips1 ws3" placeholder="시" /> : 
					<input type="number" id="EM_JOIN_S_TIME2" name="EM_JOIN_S_TIME2" min="0" max="59" class="nx-ips1 ws3" placeholder="분" />
				</span>
				~
				<span class="dsIB mr10">
					<span class="nx-slt">
						<select id="EM_JOIN_E_DATE1" name="EM_JOIN_E_DATE1">
							<option value="">년</option>
							<?php
							for ($i = 2017; $i <= (date('Y') + 5); $i++) {
								echo '<option value="'.(int)$i.'">'.(int)$i.'</option>';
							}
							?>
						</select>
						<span class="ico_select"></span>
					</span>
					<span class="nx-slt">
						<select id="EM_JOIN_E_DATE2" name="EM_JOIN_E_DATE2">
							<option value="">월</option>
							<?php
							for ($i = 1; $i <= 12; $i++) {
								$_i = ($i < 10) ? ('0'.$i) : $i;
								echo '<option value="'.$_i.'">'.$_i.'</option>';
							}
							?>
						</select>
						<span class="ico_select"></span>
					</span>
					<span class="nx-slt">
						<select id="EM_JOIN_E_DATE3" name="EM_JOIN_E_DATE3">
							<option value="">일</option>
							<?php
							for ($i = 1; $i <= 31; $i++) {
								$_i = ($i < 10) ? ('0'.$i) : $i;
								echo '<option value="'.$_i.'">'.$_i.'</option>';
							}
							?>
						</select>
						<span class="ico_select"></span>
					</span>
				</span>
				<span class="dsIB vaT" style="width:120px;">
					<input type="number" id="EM_JOIN_E_TIME1" name="EM_JOIN_E_TIME1" min="0" max="23" class="nx-ips1 ws3" placeholder="시" /> : 
					<input type="number" id="EM_JOIN_E_TIME2" name="EM_JOIN_E_TIME2" min="0" max="59" class="nx-ips1 ws3" placeholder="분" />
				</span>
			</td>
		</tr>
		<tr>
			<th><span class="red">*</span>인원</th>
			<td>
				<div class="chk1_wrap dsIB mr15">
					<input type="checkbox" id="EM_JOIN_MAX_YN" name="EM_JOIN_MAX_YN" class="chk1" value="Y" /><label for="EM_JOIN_MAX_YN"><span class="chkbox"><span class="ico_check"></span></span><span class="txt">인원제한</span></label>
				</div>
				<input type="number" id="EM_JOIN_MAX" name="EM_JOIN_MAX" maxlength="10" min="1" max="9999999" class="nx-ips1 ws2" /> 명
			</td>
		</tr>
		<tr>
			<th><span class="red">*</span>장소</th>
			<td>
				<input type="text" id="EM_ADDR" name="EM_ADDR" maxlength="100" class="nx-ips1 wl" placeholder="주소를 입력해 주세요." />
			</td>
		</tr>
		<tr>
			<th><span class="red">*</span>행사 담당자명</th>
			<td>
				<input type="text" id="EM_CG_NAME" name="EM_CG_NAME" maxlength="30" class="nx-ips1 ws2" />
			</td>
		</tr>
		<tr>
			<th><span class="red">*</span>담당자 연락처</th>
			<td>
				<input type="tel" id="EM_CG_TEL1" name="EM_CG_TEL1" maxlength="4" class="nx-ips1 ws3" /> -
				<input type="tel" id="EM_CG_TEL2" name="EM_CG_TEL2" maxlength="4" class="nx-ips1 ws3" /> -
				<input type="tel" id="EM_CG_TEL3" name="EM_CG_TEL3" maxlength="4" class="nx-ips1 ws3" />
			</td>
		</tr>
		<tr>
			<th><span class="red">*</span>담당자 e-mail</th>
			<td>
				<input type="email" id="EM_CG_EMAIL" name="EM_CG_EMAIL" maxlength="200" class="nx-ips1 wm" />
			</td>
		</tr>
		<tr>
			<th>확인증 타이틀</th>
			<td>
				<input type="text" id="EM_CERT_TITLE" name="EM_CERT_TITLE" maxlength="20" class="nx-ips1 wm" />
			</td>
		</tr>
		<tr>
			<th>확인증 소속 표시</th>
			<td>
				<div class="radio1_wrap">
					<input type="radio" id="EM_CERT_ORG_YN1" name="EM_CERT_ORG_YN" class="radio1" value="Y" checked="checked" /><label for="EM_CERT_ORG_YN1"><span class="radbox"><span></span></span><span class="txt">표시</span></label>
					<input type="radio" id="EM_CERT_ORG_YN2" name="EM_CERT_ORG_YN" class="radio1" value="N" /><label for="EM_CERT_ORG_YN2"><span class="radbox"><span></span></span><span class="txt">표시 안함</span></label>
				</div>
			</td>
		</tr>
		<tr>
			<th>생년월일 입력 여부</th>
			<td>
				<div class="radio1_wrap">
					<input type="radio" id="EM_REQUIRE_BIRTH_YN1" name="EM_REQUIRE_BIRTH_YN" class="radio1" value="Y" /><label for="EM_REQUIRE_BIRTH_YN1"><span class="radbox"><span></span></span><span class="txt">추가</span></label>
					<input type="radio" id="EM_REQUIRE_BIRTH_YN2" name="EM_REQUIRE_BIRTH_YN" class="radio1" value="N" checked="checked" /><label for="EM_REQUIRE_BIRTH_YN2"><span class="radbox"><span></span></span><span class="txt">추가 안함</span></label>
					<span class="nx-tip dsIB vaM ml10">이수증 출력에도 반영됩니다.</span>
				</div>
			</td>
		</tr>
		<tr>
			<th><span class="red">*</span>공개</th>
			<td>
				<div class="radio1_wrap">
					<input type="radio" id="EM_OPEN_YN1" name="EM_OPEN_YN" class="radio1" value="Y" /><label for="EM_OPEN_YN1"><span class="radbox"><span></span></span><span class="txt">공개</span></label>
					<input type="radio" id="EM_OPEN_YN2" name="EM_OPEN_YN" class="radio1" value="N" /><label for="EM_OPEN_YN2"><span class="radbox"><span></span></span><span class="txt">미공개</span></label>
				</div>
			</td>
		</tr>
		
		<tr>
			<th>리스트이미지</th>
			<td>
				<input type="file" name="em_file[]" class="nx-ips1 wl" />
				<span class="dsB mt5">(size: 456x456)</span>
			</td>
		</tr>
		<tr>
			<th>대표이미지</th>
			<td>
				<input type="file" name="em_file[]" class="nx-ips1 wl" />
				<span class="dsB mt5">(size: 396x396)</span>
			</td>
		</tr>

		<tr>
			<th>첨부파일</th>
			<td>
				<input type="file" name="em_file[]" class="nx-ips1 wl" />
			</td>
		</tr>

		<tr>
			<th>내용등록</th>
			<td>
				<?php
				$placehold_txt = "※ 한글(hwp)에 있는 것을 그대로 복사해 붙여 넣으면 웹사이트에 예상하지 못한 영향을 줄 수 있습니다. 한글에서 복사한 내용을 메모장 등에 한번 옮겼다가 에디터에 붙여 주세요.<br>※ 에디터에서 경기천년체를 사용할 수 있도록 글꼴 추가했습니다. 글꼴 선택 목록을 확인해 주세요.";
				?>
				<?php echo editor_html('EM_CONT', $placehold_txt, true) ?>
			</td>
		</tr>
	</tbody>
</table>

<div class="mt30">
	<?php
	$FI_RNDCODE = uniqid();
	?>
	<input type="hidden" id="FI_RNDCODE" name="FI_RNDCODE" value="<?php echo($FI_RNDCODE)?>" />
	<iframe id="if_form" name="if_form" src="evt.form.item.list.php?<?php echo($epTail)?>EM_IDX=<?php echo($EM_IDX)?>&FI_RNDCODE=<?php echo($FI_RNDCODE)?>" frameborder="0" height="400" style="width:100%"></iframe>
</div>

<div class="ofH mt10">
	<div class="fL">
		<a href="javascript:onclPreview();" class="nx-btn-b2">미리보기</a>
	</div>
	<div class="fR">
		<a href="javascript:onclOnsu();" class="nx-btn-b2">등록</a>
		<a href="javascript:onclCancel();" class="nx-btn-b3">뒤로</a>	
	</div>
</div>

</form>

<script>
//<![CDATA[
$(function() {
	var j_max = $('#EM_JOIN_MAX'), j_max_yn = $('#EM_JOIN_MAX_YN');

	j_max.on({
		keyup: function() {
			var v = $(this).val();
			j_max_yn.prop('checked', ((!isNaN(v) && parseInt(v) > 0) ? true : false));
		}
	});

	j_max_yn.click(function(e) {
		if ($(this).prop('checked')) {
			j_max.focus();
		}
	});
});

var onclCancel = function() {
	window.location.href="evt.list.php?<?php echo($epTail)?>";
}
var onclOnsu = function() {
	if ($('#EM_TITLE').val() == '') {
		alert("제목 정보를 입력해 주세요.");
		$('#EM_TITLE').focus(); return;
	}
	if ($(':radio[name="EM_TYPE"]:checked').length <= 0) {
		alert("분류 정보를 선택해 주세요.");
		$('#EM_TYPE1').focus(); return;
	}

	if ($(':radio[name="EM_JOIN_TYPE"]:checked').length <= 0) {
		alert("승인방식 정보를 선택해 주세요.");
		$('#EM_JOIN_TYPE1').focus(); return;
	}

	var _t = $('#EM_S_DATE');
	if (_t.val() == '') {
		alert("행사 시작일 정보를 선택해 주세요.");
		_t.focus(); return;
	}
	// var _t = $('#EM_S_DATE2');
	// if (_t.val() == '') {
	// 	alert("행사 시작일 정보를 선택해 주세요.");
	// 	_t.focus(); return;
	// }
	// var _t = $('#EM_S_DATE3');
	// if (_t.val() == '') {
	// 	alert("행사 시작일 정보를 선택해 주세요.");
	// 	_t.focus(); return;
	// }

	var _t = $('#EM_E_DATE');
	if (_t.val() == '') {
		alert("행사 종료일 정보를 선택해 주세요.");
		_t.focus(); return;
	}
	// var _t = $('#EM_E_DATE2');
	// if (_t.val() == '') {
	// 	alert("행사 종료일 정보를 선택해 주세요.");
	// 	_t.focus(); return;
	// }
	// var _t = $('#EM_E_DATE3');
	// if (_t.val() == '') {
	// 	alert("행사 종료일 정보를 선택해 주세요.");
	// 	_t.focus(); return;
	// }

	var _t = $('#EM_S_TIME1');
	if (_t.val() == '') {
		alert("시작시간 정보를 선택해 주세요.");
		_t.focus(); return;
	}
	var _t = $('#EM_S_TIME2');
	if (_t.val() == '') {
		alert("시작시간 정보를 선택해 주세요.");
		_t.focus(); return;
	}

	var _t = $('#EM_E_TIME1');
	if (_t.val() == '') {
		alert("종료시간 정보를 선택해 주세요.");
		_t.focus(); return;
	}
	var _t = $('#EM_E_TIME2');
	if (_t.val() == '') {
		alert("종료시간 정보를 선택해 주세요.");
		_t.focus(); return;
	}

	var j_s_date1 = $('#EM_JOIN_S_DATE1'), j_s_date2 = $('#EM_JOIN_S_DATE2'), j_s_date3 = $('#EM_JOIN_S_DATE3');
	if (j_s_date1.val() == '') {
		alert("신청기간(시작) 정보를 선택해 주세요.");
		j_s_date1.focus(); return;
	}
	if (j_s_date2.val() == '') {
		alert("신청기간(시작) 정보를 선택해 주세요.");
		j_s_date2.focus(); return;
	}
	if (j_s_date3.val() == '') {
		alert("신청기간(시작) 정보를 선택해 주세요.");
		j_s_date3.focus(); return;
	}

	var j_s_time1 = $('#EM_JOIN_S_TIME1'), j_s_time2 = $('#EM_JOIN_S_TIME2');
	if (j_s_time1.val() == '') {
		alert("신청기간(시작) 정보를 입력해 주세요.");
		j_s_time1.focus(); return;
	}
	if (j_s_time2.val() == '') {
		alert("신청기간(시작) 정보를 입력해 주세요.");
		j_s_time2.focus(); return;
	}

	var j_e_date1 = $('#EM_JOIN_E_DATE1'), j_e_date2 = $('#EM_JOIN_E_DATE2'), j_e_date3 = $('#EM_JOIN_E_DATE3');
	if (j_e_date1.val() == '') {
		alert("신청기간(종료) 정보를 선택해 주세요.");
		j_e_date1.focus(); return;
	}
	if (j_e_date2.val() == '') {
		alert("신청기간(종료) 정보를 선택해 주세요.");
		j_e_date2.focus(); return;
	}
	if (j_e_date3.val() == '') {
		alert("신청기간(종료) 정보를 선택해 주세요.");
		j_e_date3.focus(); return;
	}

	var j_e_time1 = $('#EM_JOIN_E_TIME1'), j_e_time2 = $('#EM_JOIN_E_TIME2');
	if (j_e_time1.val() == '') {
		alert("신청기간(종료) 정보를 입력해 주세요.");
		j_e_time1.focus(); return;
	}
	if (j_e_time2.val() == '') {
		alert("신청기간(종료) 정보를 입력해 주세요.");
		j_e_time2.focus(); return;
	}

	var t = $(':radio[name="EM_JOIN_TYPE"]:checked').val()
		, m = $('#EM_JOIN_MAX')
		, mv = m.val();
	if (t == '1' && (mv == '' || isNaN(mv) || parseInt(mv) <= 0)) {
		alert("선착순 방식일 경우 인원제한이 필수 입니다.");
		m.focus(); return;
	}

	var j_max = $('#EM_JOIN_MAX');
	if (j_max.val() != '' && isNaN(j_max.val())) {
		alert("인원제한 정보를 숫자로 입력해 주세요.");
		j_max.focus(); return;
	}

	if ($('#EM_ADDR').val() == '') {
		alert("장소 정보를 입력해 주세요.");
		$('#EM_ADDR').focus(); return;
	}

	if ($('#EM_CG_NAME').val() == '') {
		alert("행사 담당자명 정보를 입력해 주세요.");
		$('#EM_CG_NAME').focus(); return;
	}
	
	var tel1 = $('#EM_CG_TEL1'), tel2 = $('#EM_CG_TEL2'), tel3 = $('#EM_CG_TEL3');
	if (tel1.val() != '' || tel2.val() != '' || tel3.val() != '')
	{
		if (tel1.val() == '' || isNaN(tel1.val())) {
			alert("행사 담당자 연락처 정보를 입력해 주세요.");
			tel1.focus(); return;
		}
		if (tel2.val() == '' || isNaN(tel2.val())) {
			alert("행사 담당자 연락처 정보를 입력해 주세요.");
			tel2.focus(); return;
		}
		if (tel3.val() == '' || isNaN(tel3.val())) {
			alert("행사 담당자 연락처 정보를 입력해 주세요.");
			tel3.focus(); return;
		}
	}

	if ($(':radio[name="EM_OPEN_YN"]:checked').length <= 0) {
		alert("공개 상태를 선택해 주세요.");
		$('#EM_OPEN_YN1').focus(); return;
	}

	oEditors.getById["EM_CONT"].exec("UPDATE_CONTENTS_FIELD", []);

	if (confirm("입력하신 정보로 진행하시겠습니까?")) {
		var f = new FormData($('#frmAdd')[0]);

		$.ajax({
			url: 'evt.addProc.php',
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
	return;
}
var onclPreview = function() {
	oEditors.getById["EM_CONT"].exec("UPDATE_CONTENTS_FIELD", []);
	
	var f = document.frmAdd;
	f.action = '<?php echo(G5_URL)?>/evt/evt.read.preview.php';
	f.method = 'post';
	f.target = '_blank';
	f.submit();
}

$(function(){
    $("#EM_S_DATE, #EM_E_DATE").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "2016:c+5" });
});
//]]>
</script>

<?php
	//마지막 인클루드
	include_once G5_ADMIN_PATH."/admin.tail.php";
?>
