<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$qa_skin_url.'/style.css">', 0);

?>
<div>
	<form id="req_form" name="req_form" action="./place_rental_req_write_update.php" method="post" onsubmit="return false">
	<input type="submit" style="width:1px;height:1px;position:absolute;top:-10000px;left:-10000px" />

	<input type="hidden" name="pm_id" value="<?php echo $pm_id?>" />
	<input type="hidden" name="ps_id" value="<?php echo $ps_id?>" />
	<input type="hidden" name="pr_id" value="<?php echo $pr_id?>" />
	<input type="hidden" name="year" value="<?php echo $year?>" />
	<input type="hidden" name="month" value="<?php echo $month?>" />
	<input type="hidden" name="day" value="<?php echo $day?>" />
	<input type="hidden" name="gubun" value="<?php echo $view['PS_GUBUN']?>" />
	<input type="hidden" name="w" value="<?php if ($pr_id != '') echo('u')?>" />

	<div id="title-outer" class="event_apply_top" style="display:none">
		<div class="top_tit">신청하기</div>
		<a href="javascript:void(0);" onclick="req.close();" class="cls"><span class="ico_x"></span></a>
	</div>

	<div id="cont-outer" class="coron_apply">
		<p class="page_tit">
			입실정보 <span class="small red">(* : 필수 입력 항목)</span>
			<?php 
			if($pm_id == '8'){
			?>	</p><p class="page_tit" style="font-size:12px;">사전신청 완료 후, 반드시 031-770-1500 으로 연락주시어 예약 확정을 받으시길 바랍니다.
			<?php 
			}
			?>
		</p>
		<p class="tit"><span class="region"><?php echo $view['PA_NAME']; ?></span> <span class="name"><?php echo $view['PS_NAME']; ?></span></p>
		<table cellspacing="0" cellpadding="0" border="0" class="coron_ts1 br_t br_b mt20">
			<colgroup>
				<col width="100px" /><col width="" />
			</colgroup>
			<tr>
				<th>입실일</th>
				<td><?php echo("{$year}-{$month}-{$day}")?></td>
			</tr>
			
			<?php
			# 강의실
			if($view['PS_GUBUN'] == 'A') {
				?>
			<tr>
				<th>시간 <span class="red">*</span></th>
				<td>
					<span class="nx_slt1 ws2">
						<?php
						# 기본 시간 배열
						$sdates = array();
						for ($k = 9; $k < 20; $k++) { $sdates[] = $k; }

						# 기본 시간 배열에서 예약할 수 없는 시간 제거
						$no_req_hh = array();   # 예약이 안되는 시간
						for ($k = 0; $k < Count($req_list); $k++) {
							$_vs = (int)$req_list[$k]['PR_SDATE_HH'];
							$_ve = (int)$req_list[$k]['PR_EDATE_HH'];

							for ($_k = $_vs; $_k < $_ve; $_k++) {
								if (($key = array_search($_k, $sdates)) !== false) {
									unset($sdates[$key]);
									$no_req_hh[] = $_k;
								}
							}
						}

						# 배열 key 재정렬
						$sdates = array_values($sdates);
						$no_req_hh = array_values($no_req_hh);
						?>
						<select name="sdate" id="sdate" onchange="_sdate.chg()">
							<option value="">시작시간</option>
							<?php
							# 예약가능 시간 표시
							foreach ($sdates as $k => $v) {
								$_v = ($v < 10) ? "0{$v}" : $v;
								
								$preSel = '';
								if ($req_view['PR_SDATE'] != '' && $_v) {
									if (date('H', strtotime($req_view['PR_SDATE'])) == $_v) {
										$preSel = 'selected';
									}
								}

								echo '<option value="'.$_v.':00" '.$preSel.'>'.$_v.':00</option>';
							}

							unset($preSel);
							?>
						</select>
						<span class="ico">▼</span>
					</span>
					부터
					<span class="nx_slt1 ws2">
						<select name="edate" id="edate">
							<option value="">종료시간</option>
						</select>
						<span class="ico">▼</span>
					</span>
					까지
				</td>
			</tr>
				<?php
			}
			# 숙소
			else if($view['PS_GUBUN'] == 'B') {
				?>
			<tr>
				<th>숙박기간 <span class="red">*</span></th>
				<td>
					<span class="nx_slt1 ws2">
						<?php
						# 기본 날짜(시작) 배열
						$sdates = array();
						// for ($k = $day + 1; $k <= $last_day; $k++) { $sdates[] = $k; }
						for ($k = $day; $k <= $day + 9; $k++) {
							$sdates[] = ($k > $last_day) ? $k - $last_day : $k;
						}

						# 기본 날짜 배열에서 예약할 수 없는 날짜 제거
						$no_req_dd = array();       # 예약이 안되는 날짜
						for ($k = 0; $k < Count($req_list); $k++) {
							$_vs = (int)$req_list[$k]['PR_SDATE_DD'];
							$_ve = (int)$req_list[$k]['PR_EDATE_DD'];

							for ($_k = $_vs; $_k < $_ve; $_k++) {
								if (($key = array_search($_k, $sdates)) !== false) {
									// unset($sdates[$key]);
									$sdates[$key] = 0;
									$no_req_dd[] = $_k;
								}
							}
						}

						# 배열 key 재정렬
						$sdates = array_values($sdates);
						$no_req_dd = array_values($no_req_dd);
						?>
						<select name="edate" id="edate" onchange="_edate.chg()">
							<option value="">기간</option>
							<?php
							# 예약가능 날짜 표시
							for ($k = 0; $k < Count($sdates); $k++) {
								if ($sdates[$k] == 0) break;
								$preSel = '';
								if ($req_view['PR_SDATE'] != '' && $req_view['PR_EDATE']) {
									$_sdate = strtotime($req_view['PR_SDATE']);
				                    $_edate = strtotime($req_view['PR_EDATE']);
				                    if ($k + 1 == (($_edate - $_sdate) / 86400))
				                    		$preSel = ' selected';
								}
								echo '<option value="'.($k + 1).'"'.$preSel.'>'.($k + 1).'박</option>';
							}
							/*
							for ($k = ($day + 1); $k <= $no_req_dd[0]; $k++) {
								echo '<option value="'.($k - $day).'">'.($k - $day).'박</option>';
							}
							*/
							?>
						</select>
						<span class="ico">▼</span>
					</span>
				</td>
			</tr>
			<tr style="height:40px">
				<th>퇴실일 <span class="red">*</span></th>
				<td id="edate_str">숙박기간을 선택해 주세요.</td>
			</tr>
				<?php
			}
			?>

			<tr>
				<th>인원 <span class="red">*</span></th>
				<td><input type="text" id="p_cnt" name="p_cnt" value="<?php echo($req_view['PR_P_CNT'])?>" maxlength="6" class="nx_ips5 ws2" /> 명</td>
			</tr>
		</table>

		<p class="page_tit mt30">고객정보</p>
		<table cellspacing="0" cellpadding="0" border="0" class="coron_ts1">
			<colgroup>
				<col width="100px" /><col width="" />
			</colgroup>
			<tr>
				<th>이름</th>
				<td>
					<span class="mb_nick"><?php echo $member['mb_nick']?></span>
				</td>
			</tr>
			<tr>
				<th>휴대폰 <span class="red">*</span></th>
				<td><?php
					if (substr_count($req_view['PR_TEL'], '-') == 2) {
						$_t = explode('-', $req_view['PR_TEL']);
					}
					else {
						$_t = explode('-', '--');
					}

					echo '<input type="tel" id="tel1" name="tel1" value="'.htmlspecialchars($_t[0]).'" maxlength="4" oninput="maxLengthCheck(this)" class="nx_ips5 ws3" /> ';
					echo '<input type="tel" id="tel2" name="tel2" value="'.htmlspecialchars($_t[1]).'" maxlength="4" oninput="maxLengthCheck(this)" class="nx_ips5 ws3" /> ';
					echo '<input type="tel" id="tel3" name="tel3" value="'.htmlspecialchars($_t[2]).'" maxlength="4" oninput="maxLengthCheck(this)" class="nx_ips5 ws3" />';

					unset($_t);
				?></td>
			</tr>
			<tr>
				<th>추가요구사항</th>
				<td><textarea id="cont" name="cont" class="nx_ips5"><?php echo htmlspecialchars($req_view['PR_CONT'])?></textarea></td>
			</tr>
		</table>

		<div class="taC mt20">
			<?php
			if ($pr_id != '') {
				echo '<input type="button" class="add_req_btn nx_btn5" onclick="req.onsu()" value="저장하기"> ';
				echo '<input type="button" class="cancel_req_btn nx_btn7" onclick="req.close()" value="닫기">';
			}
			else {
				echo '<input type="button" class="add_req_btn nx_btn5" onclick="req.onsu()" value="신청하기">';
			}
			?>
		</div>
	</div>

	</form>
</div>

<script>
//<![CDATA[
var year = parseInt('<?php echo $year?>');
var month = parseInt('<?php echo $month?>', 10);
var day = parseInt('<?php echo $day?>', 10);
var last_day = parseInt("<?php echo $last_day;?>", 10);

<?php
#----- 강의실
if ($view['PS_GUBUN'] == 'A') {
	?>
var no_req_hh = JSON.parse('<?php echo json_encode($no_req_hh)?>').sort(function(a, b){return a-b});
var _sdate = {
	chg: function() {
		var v = $('#sdate').val(), edate = $('#edate');
		if (v == '') {
			edate.html('<option value="">종료시간</option>');
			return;
		}
		
		var min = parseInt(v.substr(0, 2), 10), max = 0;
		for (var i = 0; i < no_req_hh.length; i++) {
			if (no_req_hh[i] > min) {
				max = no_req_hh[i];
				break;
			}
		}

		if (max <= 0) max = 20;

		var opts = [], ii = 0;
		for (var i = min + 1; i <= max; i++) {
			ii = (i < 10) ? '0'+i : i;
			opts.push('<option value="'+ii+':00">'+ii+':00</option>');
		}
		edate.html(opts);
	}
}
	<?php
}
#----- 숙소
else {
	?>
var _edate = {
	chg: function() {
		var v = $('#edate').val(), edate_str = $('#edate_str');

		if (v == '') {
			edate_str.text('숙박기간을 선택해 주세요.');
		}
		else {
			var t_year = year;
			var t_month = month;
			var t_day = parseInt(day, 10) + parseInt(v, 10);

			if (t_day > last_day) {
				t_day = t_day - last_day;
				t_month++;
			}

			if (t_month > 12) {
				t_month = t_month - 12;
				t_year++;
			}

			t_month = (t_month < 10) ? '0'+t_month : t_month;
			t_day = (t_day < 10) ? '0'+t_day : t_day;

			edate_str.text(t_year+'-'+t_month+'-'+t_day);
		}
	}
}
	<?php
}
#####
?>

var req = {
	onsu: function() {
		<?php
		#----- 강의실
		if ($view['PS_GUBUN'] == 'A') {
			?>
		var _t = $('#sdate');
		if (_t.val() == '') {
			alert("시간(시작) 정보를 선택해 주세요.");
			_t.focus(); return;
		}
		var _t = $('#edate');
		if (_t.val() == '') {
			alert("시간(종료) 정보를 선택해 주세요.");
			_t.focus(); return;
		}
			<?php
		}
		#----- 숙소
		else {
			?>
		var _t = $('#edate');
		if (_t.val() == '') {
			alert("숙박기간 정보를 선택해 주세요.");
			_t.focus(); return;
		}
			<?php
		}
		#####
		?>

		var _t = $('#p_cnt'), _tv = _t.val();
		if (_tv == '' || isNaN(_tv)) {
			alert("인원 정보를 숫자로 입력해 주세요.");
			_t.focus(); return;
		}

		var _t = $('#tel1'), _tv = _t.val();
		if (_tv == '' || isNaN(_tv)) {
			alert("휴대폰 정보를 바르게 입력해 주세요.");
			_t.focus(); return;
		}
		var _t = $('#tel2'), _tv = _t.val();
		if (_tv == '' || isNaN(_tv)) {
			alert("휴대폰 정보를 바르게 입력해 주세요.");
			_t.focus(); return;
		}
		var _t = $('#tel3'), _tv = _t.val();
		if (_tv == '' || isNaN(_tv)) {
			alert("휴대폰 정보를 바르게 입력해 주세요.");
			_t.focus(); return;
		}

		if (confirm("입력하신 사항으로 진행하시겠습니까?")) {
			document.getElementById('req_form').submit();
		}
	}
	, close: function() {
		if (opener) {
			self.close();
		}
		else {
			parent.$('#viewModal').modal('hide');
		}
	}
}

<?php
# 수정 모드일 경우
if ($pr_id != '' && $view['PS_GUBUN'] == 'A') {
	?>
var st;
$(function() {
	$('#sdate').trigger('change');
	clearTimeout(st);
	st = setTimeout(function(){
		$('#edate').val('<?php echo(date('H', strtotime($req_view['PR_EDATE'])))?>:00');
	} , 100);
});
	<?php
}
#####
?>


function maxLengthCheck(object){
	if (object.value.length > object.maxLength){
		object.value = object.value.slice(0, object.maxLength);
	}    
}


<?php /* 새창으로 열었을 경우 title, cont 영역 수정 */ ?>
$(function() {
	if (opener) {
		$('#title-outer').css({marginBottom:'20px'}).show();
		$('#cont-outer').css({padding:'0 20px 40px 20px'}).show();
	}
});
//]]>
</script>
