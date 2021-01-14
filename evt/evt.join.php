<?php
	include_once("./_common.php");


	# chk : 로그인
	if ($member['mb_id'] == '') {
		F_script("잘못된 접근 입니다.", "if(opener) { self.close(); } else { parent.$('#viewModal').modal('hide'); }");
	}


	// 테마설정
	$at = apms_gr_thema();
	if(!defined('THEMA_PATH')) {
		include_once(G5_LIB_PATH.'/apms.thema.lib.php');
	}

	include_once(G5_PATH.'/head.sub.php');
	if(!USE_G5_THEME) @include_once(THEMA_PATH.'/head.sub.php');


	# set : variables
	$EM_IDX = $_GET['EM_IDX'];
	$REJOIN = $_GET['REJOIN'];


	# re-define
	$EM_IDX = CHK_NUMBER($EM_IDX);
	$REJOIN = ($REJOIN == 'Y') ? $REJOIN : 'N';
	if ($EM_IDX <= 0) {
		F_script("잘못된 접근 입니다.", "if(opener) { self.close(); } else { parent.$('#viewModal').modal('hide'); }");
	}


	$sql = "Select EM.*"
		."		, (Select Count(EJ_IDX) From NX_EVENT_JOIN Where EM_IDX = EM.EM_IDX And EJ_DDATE is null) As CNT1"
		."		, (Select Count(EJ_IDX) From NX_EVENT_JOIN Where EM_IDX = EM.EM_IDX And EJ_DDATE is null And EJ_STATUS = '2') As CNT2"
		."		, (Select Count(EJ_IDX) From NX_EVENT_JOIN Where EM_IDX = EM.EM_IDX And EJ_DDATE is null And EJ_STATUS = '2' And (EJ_JOIN_CHK1 = 'Y' And EJ_JOIN_CHK2 = 'Y')) As CNT3"
		."		, (Select Count(EJ_IDX) From NX_EVENT_JOIN Where EM_IDX = EM.EM_IDX And EJ_DDATE is null And mb_id = '" . mres($member['mb_id']) . "') As CNT4"
		."		, FL.bf_file, bf_source"
		."	From NX_EVENT_MASTER As EM"
		."		Left Join {$g5['board_file_table']} As FL On FL.bo_table = 'NX_EVENT_MASTER' And FL.wr_id = EM.EM_IDX And FL.bf_no = '1'"
		."	Where EM.EM_DDATE is null"
		."		And EM.EM_IDX = '" . mres($EM_IDX) . "'"
		."	Limit 1"
		;
	$rs1 = sql_fetch($sql);
	if (is_null($rs1['EM_IDX'])) {
		unset($rs1);
		F_script("존재하지 않는 정보 입니다.", "if(opener) { self.close(); } else { parent.$('#viewModal').modal('hide'); }");
	}


	#----- 신청방식에 따른 rfv chk.
	# 선착순
	if ($rs1['EM_JOIN_TYPE'] == '1') {
		# 자동승인된 인원이 인원제한에 도달했을 경우 신청 불가
		/*if ($rs1['EM_JOIN_MAX'] <= $rs1['CNT2']) {
			F_script("최대 인원이 신청되어, 추가 신청할 수 없습니다.", "if(opener) { self.close(); } else { parent.$('#viewModal').modal('hide'); }");
		}*/

		# 자동승인된 인원이 인원제한에 도달했을 경우 추가 신청자는 대기 상태로 표시됨
		if ($rs1['EM_JOIN_MAX'] <= $rs1['CNT2']) {
			//F_script("최대 인원이 신청되어, 대기 인원으로 등록 됩니다.", '');
			F_script("최대 인원이 신청되어, 추가 신청할 수 없습니다.", '');
		}
	}
	#####


	#----- 접수기간 chk.
	# 접수기간 = 신청하기
	if ($rs1['EM_JOIN_S_DATE'] <= date('Y-m-d H:i') && $rs1['EM_JOIN_E_DATE'] >= date('Y-m-d H:i')) {
		# 한번만 접수 가능, 재신청이 아닐때
		if ($rs1['CNT4'] > 0 && $REJOIN != 'Y') {
			F_script("이미 신청한 행사 입니다.", "if(opener) { self.close(); } else { parent.$('#viewModal').modal('hide'); }");
		}
	}
	# 접수기간 종료 = 마감
	else if ($rs1['EM_JOIN_E_DATE'] < date('Y-m-d H:i')) {
		F_script("행사가 마감 되었습니다.", "if(opener) { self.close(); } else { parent.$('#viewModal').modal('hide'); }");
	}
	# 기타 = 접수준비중
	else {
		F_script("접수 준비중 입니다.", "if(opener) { self.close(); } else { parent.$('#viewModal').modal('hide'); }");
	}
	#####


	# get : theme 정보
	$sql ="select data_1 from g5_apms_data where type ='11'";
	$row = sql_fetch($sql);

	define('THEMA_URL', G5_URL.'/thema/'.$row['data_1']);
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.min.js" integrity="sha384-FzT3vTVGXqf7wRfy8k4BiyzvbNfeYjK+frTVqZeNDFl8woCbF0CYG6g2fMEFFo/i" crossorigin="anonymous"></script>

<link rel="stylesheet" href="<?php echo(THEMA_URL)?>/colorset/Basic/colorset.css" type="text/css">
<form id="frmJoin" name="frmJoin" method="post" enctype="multipart/form-data" onsubmit="return false;">
<input type="hidden" id="EM_IDX" name="EM_IDX" value="<?php echo($EM_IDX)?>" />
<input type="hidden" id="REJOIN" name="REJOIN" value="<?php echo($REJOIN)?>" />
<input type="hidden" id="EM_REQUIRE_BIRTH_YN" name="EM_REQUIRE_BIRTH_YN" value="<?php echo($rs1['EM_REQUIRE_BIRTH_YN'])?>" />

<div id="title-outer" class="event_apply_top" style="display:none">
	<div class="top_tit">참가신청</div>
	<a href="javascript:void(0);" onclick="ejoin.close();" class="cls"><span class="ico_x"></span></a>
</div>

<div id="cont-outer" class="event_apply">
	<p class="page_tit">행사정보</p>

	<?php
	$wname = array('일','월','화','수','목','금','토');
	
	echo '<p class="event_date">행사기간 : <span class="dsIB"><span class="dsIB">';
	echo $rs1['EM_S_DATE'].' ('.$wname[date('w', strtotime($rs1['EM_S_DATE']))].') '.substr($rs1['EM_S_TIME'], 0, 5);
	echo ' ~</span> <span class="dsIB">';
	echo $rs1['EM_E_DATE'].' ('.$wname[date('w', strtotime($rs1['EM_E_DATE']))].') '.substr($rs1['EM_E_TIME'], 0, 5);
	echo '</span></span></p>';
	
	unset($wname);
	?>

	<p class="tit"><span class="name"><?php echo(F_hsc($rs1['EM_TITLE']))?></span></p>

	<p class="page_tit br_t mt30">참가자 정보 <span class="red small">(* : 필수 입력 항목)</span></p>
	<table cellspacing="0" cellpadding="0" border="0" class="event_ts1">
		<colgroup>
			<col width="100px" /><col width="" />
		</colgroup>
		<tr>
			<th>이름<span class="red">*</span></th>
			<td>
				<input type="text" id="EJ_NAME" name="EJ_NAME" value="<?php echo(F_hsc($member['mb_name']))?>" maxlength="50" class="nx_ips5 ws" />
			</td>
		</tr>
		<tr>
			<th>연락처<span class="red">*</span></th>
			<td>
				<input type="tel" id="EJ_MOBILE1" name="EJ_MOBILE1" value="" maxlength="4" class="nx_ips5 ws2" />
				<input type="tel" id="EJ_MOBILE2" name="EJ_MOBILE2" value="" maxlength="4" class="nx_ips5 ws2" />
				<input type="tel" id="EJ_MOBILE3" name="EJ_MOBILE3" value="" maxlength="4" class="nx_ips5 ws2" />
			</td>
		</tr>
		<tr>
			<th>이메일<span class="red">*</span></th>
			<td>
				<input type="email" id="EJ_EMAIL" name="EJ_EMAIL" value="" maxlength="300" class="nx_ips5 wm" />
			</td>
		</tr>
		<tr>
			<th>소속<span class="red">*</span></th>
			<td>
				<input type="text" id="EJ_ORG" name="EJ_ORG" value="" maxlength="50" class="nx_ips5 wm" />
				<div class="nx_expl dsIB">소속이 없을 시 '없음'이라고 적으면 됩니다. </div>
			</td>
		</tr>
		<?php
		if ($rs1['EM_REQUIRE_BIRTH_YN'] == 'Y') {
			?>
		<tr>
			<th>생년월일<span class="red">*</span></th>
			<td>
				<span class="dsIB mb5">
					<span class="mr5">
						<span class="nx_slt1 wa">
							<select id="EJ_BIRTH_YY" name="EJ_BIRTH_YY">
								<option value=""></option>
								<?php
								$fi = (date('Y'));
								while ($fi >= (date('Y')-105)) {
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
							<select id="EJ_BIRTH_MM" name="EJ_BIRTH_MM">
								<option value=""></option>
								<?php
								for ($fi = 1; $fi <= 12; $fi++) {
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
							<select id="EJ_BIRTH_DD" name="EJ_BIRTH_DD">
								<option value=""></option>
								<?php
								for ($fi = 1; $fi <= 31; $fi++) {
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
			</td>
		</tr>			
			<?php
		}
		?>
		
		<?php
			# form 입력 항목
			include_once "evt.join.form.item.php";
		?>

	</table>

	<div class="taC mt30">
		<a href="javascript:void(0)" onclick="ejoin.add();" class="event_apply_btn1">신청하기</a>
		<?php
		if ($REJOIN == 'Y') {
			?>
		<a href="javascript:void(0)" onclick="ejoin.close();" class="event_apply_btn2">취소</a>
			<?php
		}
		?>
	</div>
</div>

</form>

<script>
//<![CDATA[
var ejoin = {
	add: function() {
		if (confirm("신청하시겠습니까?")) {
			<?php /* Formdata object가 ie10 이상부터 지원되어 jquery.form.js 플러그인을 활용함 */ ?>

			var ajaxOpt = {
				url: 'evt.joinProc.php',
				type: 'POST',
				dataType: 'json',
				processData: false, 
				contentType: false
			}

			var form = $("#frmJoin").ajaxSubmit(ajaxOpt);
			var _ajax = form.data('jqxhr');

			_ajax.done(function(json) {
				if (!json.success) {
					if (json.msg) alert(json.msg);
					return;
				}

				if (json.msg) alert(json.msg);				
				if (opener) {
					if (json.redir) opener.location.href = json.redir;
					self.close();
				}
				else {
					if (json.redir) parent.location.href = json.redir;
					// parent.$('#viewModal').modal('hide');
				}
			})
			.fail(function(a, b, c) {
				// alert(a.statusText+' ('+a.status+')\n\n'+a.responseText);
			});
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

<?php /* 새창으로 열었을 경우 title, cont 영역 수정 */ ?>
$(function() {
	if (opener) {
		$('#title-outer').css({marginBottom:'20px'}).show();
		$('#cont-outer').css({padding:'0 20px 40px 20px'}).show();
	}
});
//]]>
</script>
<?php
	include "evt.form.cond.php";

	if(!USE_G5_THEME) @include_once(THEMA_PATH.'/tail.sub.php');
	include_once(G5_PATH.'/tail.sub.php');
?>
