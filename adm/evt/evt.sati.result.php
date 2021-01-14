<?php
	include_once('./_common.php');
	include_once('./evt.err.php'); 	# $sub_menu = ($EP_IDX > 0) ? '980100' : '990100'

	auth_check($auth[$sub_menu], "r");


	# chk : rfv.
	if ($EM_IDX <= 0) {
		header("location:evt.list.php?".$epTail);
		exit();
	}


	# set : variables
	$ty = (isset($_POST['ty']) && $_POST['ty'] != '') ? $_POST['ty'] : $_GET['ty'];


	# re-define
	$ty = (in_array($ty, array('total','person'))) ? (string)$ty : 'total';


	# chk : event main record
	$row = sql_fetch("Select EM_TITLE From NX_EVENT_MASTER Where EM_DDATE is null And EM_IDX = '" . mres($EM_IDX) . "'");
	if (is_null($row['EM_TITLE'])) {
		F_script("잘못된 접근 입니다.", "evt.list.php");
	}
	$EM_TITLE = $row['EM_TITLE'];
	unset($row);


	$g5[title] = ($sub_menu == '980100') ? "공모사업 행사 만족도관리" : "만족도관리";
	include_once('../admin.head.php');
?>

<div class="nx-tit1 mt0 mb10">행사명 : <?php echo(F_hsc($EM_TITLE))?></div>

<div class="psR pt20">
	<ul class="nx-tab1">
		<li><a href="evt.sati.php?<?php echo($epTail)?>EM_IDX=<?php echo($EM_IDX)?>">만족도설문</a></li>
		<li><a href="evt.sati.result.php?<?php echo($epTail)?>EM_IDX=<?php echo($EM_IDX)?>" class="aon">참여현황</a></li>
	</ul>
	<div style="position:absolute;top:0;right:0;">
		<a href="evt.list.php?<?php echo($epTail)?>" class="nx-btn-b3">목록으로</a>
	</div>
</div>

<?php /*
<div class="ofH mb10 pb10" style="border-bottom: 1px solid #d5d5d5;">
	<div class="fL">
		<span class="nx-slt">
			<select id="ty" name="ty" onchange="tyChg()">
				<option value="total" <?php if ($ty != 'person') echo('selected');?>>전체통계</option>
				<option value="person" <?php if ($ty == 'person') echo('selected');?>>참여자별</option>
			</select>
			<span class="ico_select"></span>
		</span>
	</div>
	<div class="fR">
		<?php
		# 엑셀은 '참여자별' 일 경우만
		if ($ty == 'person') {
			echo '<a href="javascript:sati.excel();" class="nx-btn-b2">엑셀 다운로드</a>';
		}
		?>
	</div>
</div> */
?>

<?php
#----- 통계보기
if ($ty == 'total') {
	?>
<div class="nx-poll-result-lst">
	<?php
	#----- 설문 평균
	$sql = "Select Sum(ESV_VAL) / Count(*) As avg From NX_EVT_SATI_VAL Where EM_IDX = '" . mres($EM_IDX) . "'";
	$db1 = sql_query($sql);

	$avg = array();
	if ($rs1 = sql_fetch_array($db1)) {
		$avg = $rs1['avg'];
	}
	#####


	#----- 설문 항목 평균
	$sql = "Select ESD_IDX, Sum(ESV_VAL) / Count(*) As avg From NX_EVT_SATI_VAL Where EM_IDX = '" . mres($EM_IDX) . "' Group By ESD_IDX";
	$db1 = sql_query($sql);

	$ev_avg = array();
	while ($rs1 = sql_fetch_array($db1)) {
		$ev_avg[$rs1['ESD_IDX']] = $rs1['avg'];
	}
	#####


	#----- 설문 항목 답안 배열로 담기
	$sql = "Select ESD_IDX, ESV_VAL, Count(*) As cnt From NX_EVT_SATI_VAL Where EM_IDX = '" . mres($EM_IDX) . "' Group By ESD_IDX, ESV_VAL";
	$db1 = sql_query($sql);

	$ev_val = array();
	while ($rs1 = sql_fetch_array($db1)) {
		$ev_val[$rs1['ESD_IDX'].'_'.$rs1['ESV_VAL']] = $rs1['cnt'];
	}
	#####


	# get : 설문에 응한 전체 인원
		//Select Count(distinct ESV_RNDCODE) as cnt from NX_EVT_SATI_VAL Where EM_IDX = '" . mres($EM_IDX) . "'"
	$row = sql_fetch("Select Count(distinct ESV_RNDCODE) as sati_cnt, 
		(
			Select Count(*)
		    From NX_EVENT_JOIN
		    Where EM_IDX = '" . mres($EM_IDX) . "'And EJ_DDATE is null And EJ_JOIN_CHK1 = 'Y' AND EJ_JOIN_CHK2 = 'Y'
		) As join_cnt
		from NX_EVT_SATI_VAL Where EM_IDX = '" . mres($EM_IDX) . "'"
		);
	$sati_total = $row['sati_cnt'];
	$join_total = $row['join_cnt'];
	unset($row);


	echo '<div class="nx-tit2 taR mb20">';
	echo '		<span class="dsIB">응답인원 / 참석자 : '.number_format($sati_total).' / '. number_format($join_total) .'</span>';
	if ($join_total > 0) {
		echo '		<span class="dsIB ml40">응답율 : '. (round(($sati_total / $join_total), 3) * 100) .'%</span>';
		echo '		<span class="dsIB ml40">평균 : '. round($avg, 1) .'</span>';
	}
	echo '</div>';


	# get : 설문 항목
	$sql = "Select a.ESD_IDX, a.ESD_QUES"
		."	From NX_EVT_SATI_DE As a"
		."		Inner Join NX_EVT_SATI_MA As b On b.EM_IDX = a.EM_IDX"
		."	Where b.EM_IDX = '" . mres($EM_IDX) . "'"
		."	Order By a.ESD_IDX Asc"
		;
	$db1 = sql_query($sql);

	if (sql_num_rows($db1) <= 0) {
		echo '<div class="nodata">설문 항목을 먼저 생성해 주세요.</div>';
	}
	else {
		$s = 0;
		while ($rs1 = sql_fetch_array($db1)) {
			?>
	<div class="nx-poll-result-item">
		<div class="tit"><?php echo($s + 1)?>. <?php echo(F_hsc($rs1['ESD_QUES']))?> (평균 : <?php echo(round($ev_avg[$rs1['ESD_IDX']]))?>)</div>
		<ol class="nx-poll-opt-lst">
			<?php
			$_arr = array(
				'① 매우 그렇지 않다'
				, '② 그렇지 않다'
				, '③ 보통이다'
				, '④ 그렇다'
				, '⑤ 매우 그렇다'
			);

			$i = Count($_arr) - 1;
			while ($i > -1)
			{
				@$_per = (int)($ev_val[$rs1['ESD_IDX'].'_'.($i + 1)]/$sati_total * 100);
				?>
			<li>
				<div class="txt"><?php echo($_arr[$i])?></div>
				<div class="graph">
					<div class="gage">
						<div class="fill" style="width: <?php echo($_per)?>%;"></div>
					</div>
				</div>
				<div class="count"><?php echo((int)$ev_val[$rs1['ESD_IDX'].'_'.($i + 1)])?>명 (<?php echo($_per)?>%)</div>
			</li>
				<?php
				$i--;
			}

			unset($_arr);
			?>
		</ol>
	</div>
			<?php
			$s++;
		}
	}
	?>
</div>
	<?php
}

#----- 참여자별
// else if ($ty == 'person') {
else if (1==2) {
	?>
<table border="0" cellpadding="0" cellspacing="0" class="nx-t-list1">
	<colgroup>
		<col width="8%"><col width="10%"><col width="">
	</colgroup>
	<thead>
		<tr>
			<th>NO.</th>
			<th>참석자명</th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<?php
		# get : 설문참여자
		$sql = "Select EJ.EJ_IDX, EJ.EJ_NAME"
			."	From NX_EVENT_JOIN As EJ"
			."		Inner Join ("
			."			Select distinct(EJ_IDX) from NX_EVT_SATI_VAL Where EM_IDX = '" . mres($EM_IDX) . "' And EJ_IDX is not null"
			."		) As a On a.EJ_IDX = EJ.EJ_IDX"
			."	Where EJ.EJ_DDATE is null"
			."	Order By EJ.EJ_IDX Asc"
			;
		$db1 = sql_query($sql);
		if (sql_num_rows($db1) <= 0) {
			echo '<tr><td colspan="3">설문 참여자가 없습니다.</td></tr>';
		}
		else {
			$s = 0;
			while ($rs1 = sql_fetch_array($db1)) {
				?>
		<tr>
			<td><?php echo($s + 1)?></td>
			<td><a href="javascript:sati.pop('<?php echo($rs1['EJ_IDX'])?>');" class="color-tit"><?php echo(F_hsc($rs1['EJ_NAME']))?></a></td>
			<td class="taR"><a href="javascript:sati.pop('<?php echo($rs1['EJ_IDX'])?>');" class="nx-btn2 mr10">설문지보기</a></td>
		</tr>
				<?php
				$s++;
			}
		}
		?>
	</tbody>
</table>
	<?php
}
#####
?>

<div class="nx-top-bd taR mt30">
	<a href="evt.list.php?<?php echo($epTail)?>" class="nx-btn-b3">목록으로</a>
</div>

<script>
//<![CDATA[
var tyChg = function() {
	window.location.href = '?EM_IDX=<?php echo($EM_IDX)?>&ty='+$('#ty').val();
}

var sati = {
	pop: function(o) {
		window.open('evt.sati.result.pop.php?EM_IDX=<?php echo($EM_IDX)?>&EJ_IDX='+o, 'evt_sati_result_pop', 'width=600,height=600,scrollbars=yes');
	}
	, excel: function() {
		window.location.href = 'evt.sati.result.excel.php?<?php echo($epTail)?>EM_IDX=<?php echo($EM_IDX)?>';
	}
}
//]]>
</script>

<?php
	//마지막 인클루드
	include_once G5_ADMIN_PATH."/admin.tail.php";
?>
