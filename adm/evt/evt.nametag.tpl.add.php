<?php
	include_once('./_common.php');
	include_once('./evt.err.php'); 	# $sub_menu = ($EP_IDX > 0) ? '980100' : '990100'

	auth_check($auth[$sub_menu], "w");


	/*	maxSize
		300dpi 기준 12x18cm (cm)
		72dpi 기준 340x510 (px)
	*/
	$maxSize = ['ww'=>(int)120, 'hh'=>(int)180];


	$g5[title] = ($sub_menu == '980100') ? "공모사업 행사 명찰관리" : "사업관리";
	include_once('../admin.head.php');
?>
<script src="<?php echo(G5_JS_URL)?>/spectrum-master/spectrum.js"></script>
<link rel="stylesheet" href="<?php echo(G5_JS_URL)?>/spectrum-master/spectrum.css">

<div class="taR mb10">
	<a href="javascript:void(0)" onclick="tpl.cancel();" class="nx-btn-b3">뒤로</a>
</div>

<form id="frmAdd" name="frmAdd" enctype="multipart/form-data" onsubmit="return false;">
<input type="hidden" name="EP_IDX" value="<?php echo($EP_IDX)?>">

<table border="0" cellpadding="0" cellspacing="0" class="nx-t-read1">
	<colgroup>
		<col width="130"><col width="">
	</colgroup>
	<tbody>
		<tr>
			<th>템플릿명</th>
			<td>
				<input type="text" id="ENT_TITLE" name="ENT_TITLE" maxlength="100" class="nx-ips1 wl" />
			</td>
		</tr>
		<tr>
			<th>사이즈</th>
			<td><?php
				echo '<span class="dsIB mr20">가로 ';
				echo '<input type="text" id="ENT_WIDTH" name="ENT_WIDTH" class="nx-ips1 ws2" maxlength="3" placeholder="'.$maxSize['ww'].'" /> mm</span> ';
				echo '<span class="dsIB mr20">세로 ';
				echo '<input type="text" id="ENT_HEIGHT" name="ENT_HEIGHT" class="nx-ips1 ws2" maxlength="3" placeholder="'.$maxSize['hh'].'" /> mm</span>';
				echo '<span id="span_ENT_WIDTH" class="dsIB">0</span>px';
				echo ' X ';
				echo '<span id="span_ENT_HEIGHT" class="dsIB">0</span>px';
			?></td>
		</tr>
		<tr>
			<th>배경이미지</th>
			<td>
				<p class="red text-left">* JPG 형태의 이미지만 올려주세요</p>
				<input type="file" id="ent_file" name="ent_file[]" class="dsIB" accept="image/jpeg" />
				<span class="nx-tip">(2배 이미지를 올리시면 선명합니다.)</span>
			</td>
		</tr>

		<tr>
			<th>A 내용</th>
			<td>
				항목명
				<span class="nx-slt mr15">
					<select id="ENT_F1_KIND" name="ENT_F1_KIND">
						<option value="">미선택</option>
						<option value="NAME">이름</option>
						<option value="MOBILE">휴대전화번호</option>
						<option value="EMAIL">이메일</option>
						<option value="ORG">소속</option>
						<option value="EM_TITLE">행사명</option>
					</select>
					<span class="ico_select"></span>
				</span>

				크기
				<span class="nx-slt mr15">
					<select id="ENT_F1_SIZE" name="ENT_F1_SIZE">
						<?php
						for ($k = 12; $k <= 48; $k+=2) {
							echo '<option value="'.$k.'" '.(($k == 20) ? 'selected' : '').'>'.$k.'</option>';
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
							echo '<option value="'.$k.'" '.(($k == 10) ? 'selected' : '').'>'.$k.'</option>';
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
							echo '<option value="'.$k.'" '.(($k == 10) ? 'selected' : '').'>'.$k.'</option>';
						}
						?>
					</select>
					<span class="ico_select"></span>
				</span>

				정렬
				<span class="nx-slt mr15">
					<select id="ENT_F1_ALIGN" name="ENT_F1_ALIGN">
						<option value="LEFT">왼쪽</option>
						<option value="CENTER" selected>가운데</option>
						<option value="RIGHT">오른쪽</option>
					</select>
					<span class="ico_select"></span>
				</span>

				색상
				<input type="text" id="ENT_F1_COLOR" name="ENT_F1_COLOR" value="#247de9" maxlength="7" class="mr15" />

				<div class="nx-namep-deco-wrap mr15">
					<input type="checkbox" id="ENT_F1_BOLD" name="ENT_F1_BOLD" value="Y" class="nx-namep-deco" /><label for="ENT_F1_BOLD">B</label>
					<input type="checkbox" id="ENT_F1_UNDERLINE" name="ENT_F1_UNDERLINE" value="Y" class="nx-namep-deco" /><label for="ENT_F1_UNDERLINE" class="underline">U</label>
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
						<option value="NAME">이름</option>
						<option value="MOBILE">휴대전화번호</option>
						<option value="EMAIL">이메일</option>
						<option value="ORG">소속</option>
						<option value="EM_TITLE">행사명</option>
					</select>
					<span class="ico_select"></span>
				</span>

				크기
				<span class="nx-slt mr15">
					<select id="ENT_F2_SIZE" name="ENT_F2_SIZE">
						<?php
						for ($k = 12; $k <= 48; $k+=2) {
							echo '<option value="'.$k.'" '.(($k == 20) ? 'selected' : '').'>'.$k.'</option>';
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
							echo '<option value="'.$k.'" '.(($k == 10) ? 'selected' : '').'>'.$k.'</option>';
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
							echo '<option value="'.$k.'" '.(($k == 10) ? 'selected' : '').'>'.$k.'</option>';
						}
						?>
					</select>
					<span class="ico_select"></span>
				</span>

				정렬
				<span class="nx-slt mr15">
					<select id="ENT_F2_ALIGN" name="ENT_F2_ALIGN">
						<option value="LEFT">왼쪽</option>
						<option value="CENTER" selected>가운데</option>
						<option value="RIGHT">오른쪽</option>
					</select>
					<span class="ico_select"></span>
				</span>

				색상
				<input type="text" id="ENT_F2_COLOR" name="ENT_F2_COLOR" value="#5f5f5f" maxlength="7" class="mr15" />

				<div class="nx-namep-deco-wrap mr15">
					<input type="checkbox" id="ENT_F2_BOLD" name="ENT_F2_BOLD" value="Y" class="nx-namep-deco" /><label for="ENT_F2_BOLD">B</label>
					<input type="checkbox" id="ENT_F2_UNDERLINE" name="ENT_F2_UNDERLINE" value="Y" class="nx-namep-deco" /><label for="ENT_F2_UNDERLINE" class="underline">U</label>
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
						<option value="NAME">이름</option>
						<option value="MOBILE">휴대전화번호</option>
						<option value="EMAIL">이메일</option>
						<option value="ORG">소속</option>
						<option value="EM_TITLE">행사명</option>
					</select>
					<span class="ico_select"></span>
				</span>

				크기
				<span class="nx-slt mr15">
					<select id="ENT_F3_SIZE" name="ENT_F3_SIZE">
						<?php
						for ($k = 12; $k <= 48; $k+=2) {
							echo '<option value="'.$k.'" '.(($k == 20) ? 'selected' : '').'>'.$k.'</option>';
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
							echo '<option value="'.$k.'" '.(($k == 10) ? 'selected' : '').'>'.$k.'</option>';
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
							echo '<option value="'.$k.'" '.(($k == 10) ? 'selected' : '').'>'.$k.'</option>';
						}
						?>
					</select>
					<span class="ico_select"></span>
				</span>

				정렬
				<span class="nx-slt mr15">
					<select id="ENT_F3_ALIGN" name="ENT_F3_ALIGN">
						<option value="LEFT">왼쪽</option>
						<option value="CENTER" selected>가운데</option>
						<option value="RIGHT">오른쪽</option>
					</select>
					<span class="ico_select"></span>
				</span>

				색상
				<input type="text" id="ENT_F3_COLOR" name="ENT_F3_COLOR" value="#000000" maxlength="7" class="mr15" />

				<div class="nx-namep-deco-wrap mr15">
					<input type="checkbox" id="ENT_F3_BOLD" name="ENT_F3_BOLD" value="Y" class="nx-namep-deco" /><label for="ENT_F3_BOLD">B</label>
					<input type="checkbox" id="ENT_F3_UNDERLINE" name="ENT_F3_UNDERLINE" value="Y" class="nx-namep-deco" /><label for="ENT_F3_UNDERLINE" class="underline">U</label>
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
							echo '<option value="'.$k.'" '.(($k == 13) ? 'selected' : '').'>'.$k.'</option>';
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
							echo '<option value="'.$k.'" '.(($k == 10) ? 'selected' : '').'>'.$k.'</option>';
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
							echo '<option value="'.$k.'" '.(($k == 10) ? 'selected' : '').'>'.$k.'</option>';
						}
						?>
					</select>
					<span class="ico_select"></span>
				</span>

				정렬
				<span class="nx-slt mr15">
					<select id="ENT_F4_ALIGN" name="ENT_F4_ALIGN">
						<option value="LEFT">왼쪽</option>
						<option value="CENTER">가운데</option>
						<option value="RIGHT" selected>오른쪽</option>
					</select>
					<span class="ico_select"></span>
				</span>

				색상
				<input type="text" id="ENT_F4_COLOR" name="ENT_F4_COLOR" value="#222222" maxlength="7" class="mr15" />

				<div class="nx-namep-deco-wrap mr15">
					<input type="checkbox" id="ENT_F4_BOLD" name="ENT_F4_BOLD" value="Y" class="nx-namep-deco" /><label for="ENT_F4_BOLD">B</label>
					<input type="checkbox" id="ENT_F4_UNDERLINE" name="ENT_F4_UNDERLINE" value="Y" class="nx-namep-deco" /><label for="ENT_F4_UNDERLINE" class="underline">U</label>
				</div>
			</td>
		</tr>

		<tr>
			<th>항목 위치 조정</th>
			<td>
				<script src="/adm/lib/jquery-ui.min.js"></script>
				<div id="prev-outer" class="nx-template-preview" style="width:<?php echo($maxSize['ww'])?>px;height:<?php echo($maxSize['hh'])?>px;overflow:hidden;">
					<img id="prev-img" src="../imgs/space.gif" style="overflow:hidden;display:block;position:absolute;top:0;left:0;bottom:0;right:0;z-index:0" />
					<div id="prev-item1" class="item" style="overflow:hidden;position:absolute;top:31px;left:10px;right:10px;width:calc(100%-20px);height:20px;font-size:20px;color:#247de9;z-index:1;cursor:pointer;">경기도평생교육진흥원 행사명</div>
					<div id="prev-item2" class="item" style="overflow:hidden;position:absolute;top:65px;left:10px;right:10px;width:calc(100%-20px);height:20px;font-size:20px;color:#5f5f5f;z-index:1;cursor:pointer;">경기도평생교육진흥원</div>
					<div id="prev-item3" class="item" style="position:absolute;top:100px;left:10px;right:10px;width:calc(100%-20px);height:20px;font-size:20px;color:#000000;z-index:1;cursor:pointer;">홍길동</div>

					<span id="prev-item4" class="serial-num" style="position:absolute;bottom:10px;left:10px;right:10px;width:calc(100%-20px);height:13px;font-size:13px;line-height:13px;color:#222222;z-index:1;cursor:pointer;text-align:right;"><?php echo(substr(date('Ymd'), 2))?>-A001</span>
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
	<div class="fL"></div>
	<div class="fR">
		<a href="javascript:void(0)" onclick="tpl.save();" class="nx-btn-b2">저장</a>
		<a href="javascript:void(0)" onclick="tpl.cancel();" class="nx-btn-b3">취소</a>
	</div>
</div>
<div class="taR mt10">
</div>

<script>
//<![CDATA[
var prev_outer = $('#prev-outer'), prev_img = $('#prev-img'), prev_item1 = $('#prev-item1'), prev_item2 = $('#prev-item2'), prev_item3 = $('#prev-item3'), prev_item4 = $('#prev-item4')
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

	$('#ENT_TITLE').focus();
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

		/*
		var _t = $('#ENT_F1_KIND');
		if (_t.val() == '') {
			alert('A 내용 항목을 선택해 주세요.');
			_t.focus(); return;
		}
		*/
		var _t = $('#ENT_F1_COLOR');
		if (_t.val() == '') {
			alert('A 내용 색상값을 입력해 주세요.');
			_t.focus(); return;
		}

		/*
		var _t = $('#ENT_F2_KIND');
		if (_t.val() == '') {
			alert('B 내용 항목을 선택해 주세요.');
			_t.focus(); return;
		}
		*/
		var _t = $('#ENT_F2_COLOR');
		if (_t.val() == '') {
			alert('B 내용 색상값을 입력해 주세요.');
			_t.focus(); return;
		}

		/*
		var _t = $('#ENT_F3_KIND');
		if (_t.val() == '') {
			alert('C 내용 항목을 선택해 주세요.');
			_t.focus(); return;
		}
		*/
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
			var f = new FormData($('#frmAdd')[0]);
			f.append('ENT_F1_Y', prev_item1.css('top').replace('px',''));
			f.append('ENT_F2_Y', prev_item2.css('top').replace('px',''));
			f.append('ENT_F3_Y', prev_item3.css('top').replace('px',''));
			f.append('ENT_F4_Y', prev_item4.css('top').replace('px',''));

			$.ajax({
				url: 'evt.nametag.tpl.addProc.php?<?php echo($epTail)?>',
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
	, cancel: function() {
		window.location.href = "evt.nametag.tpl.list.php<?php echo($epTail)?>";
	}
}
//]]>
</script>
<?php
	//마지막 인클루드
	include_once G5_ADMIN_PATH."/admin.tail.php";
?>
