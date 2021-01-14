<?php
	include_once('./_common.php');
	include_once('./evt.err.php'); 	# $sub_menu = ($EP_IDX > 0) ? '980100' : '990100'
	include_once G5_EDITOR_PATH.'/'.$config['cf_editor'].'/editor.lib.php';
	include_once(G5_LIB_PATH.'/thumbnail.lib.php'); //썸네일 라이브러리 로딩

	auth_check($auth[$sub_menu], "r");


	# get data
	$sql = "Select EM.*"
		. " , EC.EC_NAME"
		. " , EP.EP_TITLE"
		. " From NX_EVENT_MASTER As EM"
		. " 	Left Join NX_EVENT_CATE As EC On EC.EC_IDX = EM.EC_IDX"
		. " 	Left Join NX_EVENT_PROJECT As EP On EP.EP_IDX = EM.EP_IDX"
		. " Where EM.EM_DDATE is null And EM.EM_IDX = '" . mres($EM_IDX) . "' Limit 1";
	$db1 = sql_query($sql);
	$db_em = sql_fetch_array($db1);


	$g5[title] = ($sub_menu == '980100') ? "공모사업 행사정보" : "사업관리";
	include_once('../admin.head.php');
?>

<div class="ofH mb10">
	<?php
	if ($EP_IDX != "") {
		?>
	<div class="fL" style="width: 800px;">
		<span class="dsIB mr15" style="font-weight: bold; color: #444;">공모사업명</span> 
		<input type="text" id="EP_TITLE" name="EP_TITLE" class="nx-ips1 wl" value="<?php echo($db_em['EP_TITLE'])?>" disabled>
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
			<td><?php echo($db_em['EC_NAME'])?></td>
		</tr>
		<tr>
			<th>제목</th>
			<td><?php echo(F_hsc($db_em['EM_TITLE']))?></td>
		</tr>
		<tr>
			<th>분류</th>
			<td><?php echo (($db_em['EM_TYPE'] == '1') ? '행사' : '교육')?></td>
		</tr>
		<tr>
			<th>승인방식</th>
			<td><?php echo (($db_em['EM_JOIN_TYPE'] == '1') ? '선착순' : '승인')?></td>
		</tr>

		<tr>
			<th>자동알림</th>
			<td>
				<div class="chk1_wrap">
					<input type="checkbox" id="EM_NOTI_EMAIL" name="EM_NOTI_EMAIL" class="chk1" value="Y" <?php if ($db_em['EM_NOTI_EMAIL'] == 'Y') echo('checked');?> disabled /><label for="EM_NOTI_EMAIL"><span class="chkbox"><span class="ico_check"></span></span><span class="txt">e-mail</span></label>
					<input type="checkbox" id="EM_NOTI_SMS" name="EM_NOTI_SMS" class="chk1" value="Y" <?php if ($db_em['EM_NOTI_SMS'] == 'Y') echo('checked');?> disabled /><label for="EM_NOTI_SMS"><span class="chkbox"><span class="ico_check"></span></span><span class="txt">SMS</span></label>
				</div>
			</td>
		</tr>
		
			<th>행사 시작일</th>
			<td><?php echo($db_em['EM_S_DATE'])?></td>
		</tr>

		<tr>
			<th>행사 종료일</th>
			<td><?php echo($db_em['EM_E_DATE'])?></td>
		</tr>
		
		<tr>
			<th>시작시간</th>
			<td><?php echo($db_em['EM_S_TIME'])?></td>
		</tr>
		
		<tr>
			<th>종료시간</th>
			<td><?php echo($db_em['EM_E_TIME'])?></td>
		</tr>

		<tr>
			<th>이수시간</th>
			<td>
				<?php if ($EM_CERT_TIME > 0){echo($EM_CERT_TIME . '시간');}?>
				<?php if ($EM_CERT_MINUTE > 0){echo(' ' . $EM_CERT_MINUTE . '분');}?>
			</td>
		</tr>
		
		<?php
		$EM_JOIN_S_DATE = new DATETIME($db_em['EM_JOIN_S_DATE']);
		$EM_JOIN_E_DATE = new DATETIME($db_em['EM_JOIN_E_DATE']);
		?>
		<tr>
			<th>신청기간</th>
			<td>
				<?php echo($EM_JOIN_S_DATE->format('Y-m-d H:i') . ' ~ ' . $EM_JOIN_E_DATE->format('Y-m-d H:i'))?>
			</td>
		</tr>
		
		<?php
		if ((int)$db_em['EM_JOIN_MAX'] > 0) {
			$EM_JOIN_MAX_YN = 'Y';
			$EM_JOIN_MAX = (int)$db_em['EM_JOIN_MAX'];
		}
		else {
			$EM_JOIN_MAX_YN = 'N';
			$EM_JOIN_MAX = '';
		}
		?>
		<tr>
			<th>인원</th>
			<td>
				<div class="chk1_wrap dsIB mr15">
					<input type="checkbox" id="EM_JOIN_MAX_YN" name="EM_JOIN_MAX_YN" class="chk1" value="Y" <?php if ($EM_JOIN_MAX_YN == 'Y') echo('checked');?> disabled /><label for="EM_JOIN_MAX_YN"><span class="chkbox"><span class="ico_check"></span></span><span class="txt">인원제한</span></label>
				</div>
				&nbsp;&nbsp;<?php echo($EM_JOIN_MAX)?>
			</td>
		</tr>
		<tr>
			<th>장소</th>
			<td><?php echo(F_hsc($db_em['EM_ADDR']))?></td>
		</tr>
		<tr>
			<th>행사 담당자명</th>
			<td><?php echo(F_hsc($db_em['EM_CG_NAME']))?></td>
		</tr>

		<tr>
			<th>담당자 연락처</th>
			<td><?php echo($db_em['EM_CG_TEL'])?></td>
		</tr>
		<tr>
			<th>담당자 e-mail</th>
			<td><?php echo($db_em['EM_CG_EMAIL'])?></td>
		</tr>
		<tr>
			<th>확인증 타이틀</th>
			<td><?php echo($db_em['EM_CERT_TITLE'])?></td>
		</tr>
		<tr>
			<th>확인증 소속 표시</th>
			<td><?php echo(($db_em['EM_CERT_ORG_YN'] == 'Y') ? '표시' : '표시 안함')?></td>
		</tr>
		<tr>
			<th>생년월일 입력 여부</th>
			<td><?php echo(($db_em['EM_REQUIRE_BIRTH_YN'] == 'Y') ? '추가' : '추가 안함')?></td>
		</tr>
		<tr>
			<th>공개</th>
			<td><?php echo(($db_em['EM_OPEN_YN'] == 'Y') ? '공개' : '미공개')?></td>
		</tr>
		
		<?php
		$_dir = "NX_EVENT_MASTER";
		$_files = get_file('NX_EVENT_MASTER', $EM_IDX);
		?>
		<tr>
			<th>리스트이미지</th>
			<td>
				<?php
				if (!is_null($_files[0])) {
					if (file_exists(G5_DATA_PATH.'/file/'.$_dir.'/'.$_files[0]['file'])) {
						set_session('ss_view_NX_EVENT_MASTER_'.$EM_IDX, TRUE);

						echo '<div style="margin-top:5px;">';
							echo '파일명 : <a href="'.$_files[0]['href'].'">'.$_files[0]['source'].'</a> &nbsp; ';
						echo '</div>';
						

						#이미지 디렉토리와, 썸네일 대상 디렉토리
						$s_path = G5_PATH . "/data/file/NX_EVENT_MASTER"; 
						$t_path = G5_PATH . "/data/file/NX_EVENT_MASTER"; 

						# 썸네일 생성
						$thumb = thumbnail($_files[0]['file'], $s_path, $t_path, 300, 300, true);
						echo '<img src="/data/file/NX_EVENT_MASTER/'.$thumb.'" alt="'.F_hsc($_files[0]['source']).'" style="margin-top:5px" />';

						unset($s_path, $t_path, $thumb);
					}
				}
				?>
			</td>
		</tr>
		<tr>
			<th>대표이미지</th>
			<td>
				<?php
				if (!is_null($_files[1])) {
					if (file_exists(G5_DATA_PATH.'/file/'.$_dir.'/'.$_files[1]['file'])) {
						set_session('ss_view_NX_EVENT_MASTER_'.$EM_IDX, TRUE);

						echo '<div style="margin-top:5px;">';
							echo '파일명 : <a href="'.$_files[1]['href'].'">'.$_files[1]['source'].'</a> &nbsp; ';
						echo '</div>';
						
						
						#이미지 디렉토리와, 썸네일 대상 디렉토리
						$s_path = G5_PATH . "/data/file/NX_EVENT_MASTER"; 
						$t_path = G5_PATH . "/data/file/NX_EVENT_MASTER"; 

						# 썸네일 생성
						$thumb = thumbnail($_files[1]['file'], $s_path, $t_path, 300, 169, true);
						echo '<img src="/data/file/NX_EVENT_MASTER/'.$thumb.'" alt="'.F_hsc($_files[1]['source']).'" style="margin-top:5px" />';

						unset($s_path, $t_path, $thumb);
					}
				}
				?>
			</td>
		</tr>

		<tr>
			<th>첨부파일</th>
			<td>
				<?php
				if (!is_null($_files[2])) {
					if (file_exists(G5_DATA_PATH.'/file/'.$_dir.'/'.$_files[2]['file'])) {
						set_session('ss_view_NX_EVENT_MASTER_'.$EM_IDX, TRUE);

						echo '<div style="margin-top:5px;">';
							echo '파일명 : <a href="'.$_files[2]['href'].'">'.$_files[2]['source'].'</a> &nbsp; ';
						echo '</div>';
					}
				}
				?>
			</td>
		</tr>
		<?php
		unset($_dir, $_files);
		?>

		<tr>
			<th>내용</th>
			<td>
				<div>
					<div style="display:none;"><textarea id="hd_content" rows="1" cols="1"><?php echo(F_hsc($db_em['EM_CONT']))?></textarea></div>
					<script type="text/javascript">
					//<![CDATA[
					document.write('<iframe src="about:blank" id="if_cont" name="if_cont" width="100%" height="100%" border="0" frameborder="0" framespacing="0" scrolling="auto" vspace="0" style="border:none; margin:0; padding:0; width:100%;"></iframe>');

					var innerVal = ''
					+ '<style type="text/css">@import url(//fonts.googleapis.com/earlyaccess/notosanskr.css);*{padding:0; margin:0;} body{font-size:14px; line-height:18px; font-family: \'Noto Sans KR\', sans-serif, Tahoma,Verdana,Arial} img {border:none; max-width: 100%;} p{margin: .5em auto;}</style>'
					+ '<div id="placeHolderContent" style="width:100%; overflow-y:hidden;">'+$('#hd_content').val()+'</div>'
					+ '<scr'+'ipt type="text/javascript" language="javascript" src="/lib/jquery-1.12.4.min.js"></scr'+'ipt>'
					+ '<scr'+'ipt type="text/javascript">'
					+ 'window.onload = function() { setTimeout( function() { parent.document.getElementById("if_cont").height = (Number(($("#placeHolderContent").css("height")).replace("px", "")) + 20); }, 1) };'
					+ '</scr'+'ipt>';

					frames.window["if_cont"].document.open();
					frames.window["if_cont"].document.write(innerVal);
					frames.window["if_cont"].document.close();
					//]]>
					</script>
				</div>
			</td>
		</tr>
	</tbody>
</table>

<div class="mt10" style="overflow:hidden">
	<div class="fR">
		<a href="javascript:onclPreview();" class="nx-btn-b2">미리보기</a>
		<a href="javascript:onclCancel();" class="nx-btn-b3">뒤로</a>
	</div>
</div>

</form>

<script>
//<![CDATA[
var onclCancel = function() {
	window.location.href="evt.list.php?<?php echo($epTail)?>";
}
var onclPreview = function() {
	oEditors.getById["EM_CONT"].exec("UPDATE_CONTENTS_FIELD", []);
	
	var f = document.frmEdit;
	f.action = '<?php echo(G5_URL)?>/evt/evt.read.preview.php';
	f.method = 'post';
	f.target = '_blank';
	f.submit();
}
//]]>
</script>

<?php
	//마지막 인클루드
	include_once G5_ADMIN_PATH."/admin.tail.php";
?>
