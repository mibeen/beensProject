<?php
	# (TEMP) 수정 필요
	$sub_menu = "990100";
	include_once('./_common.php');

	auth_check($auth[$sub_menu], "r");


	# set : variables
	$EJ_IDX = (isset($_POST['EJ_IDX']) && $_POST['EJ_IDX'] != '') ? $_POST['EJ_IDX'] : $_GET['EJ_IDX'];


	# re-define
	$EJ_IDX = CHK_NUMBER($EJ_IDX);


	# chk : rfv.
	if ($EM_IDX <= 0) exit;
	if ($EJ_IDX <= 0) exit;


	$g5[title] = "만족도관리";
	include_once("../inc/pop.top.php");


	# get : 
	$sql = "Select EJ.EJ_NAME, EJ.EJ_JOIN_CODE"
		."	From NX_EVENT_JOIN As EJ"
		."		Inner Join NX_EVENT_MASTER As EM On EM.EM_IDX = EJ.EM_IDX"
		."			And EM.EM_DDATE is null"
		."	Where EJ.EM_IDX = '" . mres($EM_IDX) . "'"
		."		And EJ.EJ_IDX = '" . mres($EJ_IDX) . "'"
		."	Order By EJ.EJ_IDX Desc"
		."	Limit 1"
		;
	$row = sql_fetch($sql);
	
	if (is_null($row['EJ_NAME'])) {
		F_script("존재하지 않는 정보 입니다.", "self.close();");
	}
	$EJ_NAME = $row['EJ_NAME'];
	$EJ_JOIN_CODE = $row['EJ_JOIN_CODE'];
	unset($row);


	
	# get : 설문입력정보
	$sql = "Select ES_IDX, EV_VAL"
		."	From NX_EVENT_SATISFY_VAL"
		."	Where EV_DDATE is null"
		."		And EM_IDX = '" . mres($EM_IDX) . "'"
		."		And EJ_IDX = '" . mres($EJ_IDX) ."'"
		;
	$db1 = sql_query($sql);

	$ev_val = array();
	while ($rs1 = sql_fetch_array($db1)) {
		$ev_val[$rs1['ES_IDX'].'_'.$rs1['EV_VAL']] = $rs1['EV_VAL'];
	}
	unset($rs1, $db1);
?>

<div style="padding: 15px;">
	<h1 class="nx-tit1"><?php echo(F_hsc($EJ_NAME))?> (접수번호 <?php echo(F_hsc($EJ_JOIN_CODE))?>)</h1>
	<div class="nx-box1">
		<div class="nx-poll-result-lst">
			<?php
			# get : 설문 항목
			$sql = "Select ES.ES_IDX, ES.ES_QUESTION"
				." 	From NX_EVENT_SATISFY As ES"
				." 	Where ES.ES_DDATE is null"
				."		And ES.EM_IDX = '" . mres($EM_IDX) . "'"
				." 	Order By ES_IDX Asc"
				;
			$db1 = sql_query($sql);

			$s = 0;
			while ($rs1 = sql_fetch_array($db1)) {
				?>
			<div class="nx-poll-result-item">
				<div class="tit"><?php echo($s + 1)?>. <?php echo(F_hsc($rs1['ES_QUESTION']))?></div>
				<div class="radio1_wrap mb ml20">
					<?php
					$_arr = array(
						'매우 그렇지 않다'
						, '그렇지 않다'
						, '보통이다'
						, '그렇다'
						, '매우 그렇다'
					);

					for ($i = 0; $i < Count($_arr); $i++)
					{
						$num = uniqid();
						echo '<input type="radio" id="EV_VAL_'.$num.'" name="EV_VAL_'.$num.'" class="radio1" disabled '.((!is_null($ev_val[$rs1['ES_IDX'].'_'.($i + 1)])) ? 'checked' : '').'>';
						echo '<label for="EV_VAL_'.$num.'"><span class="radbox"><span></span></span><span class="txt">';
						echo $_arr[$i];
						echo '</span></label>';
					}
					?>
				</div>
			</div>
				<?php
				$s++;
			}
			unset($rs1, $db1);
			?>
		</div>
	</div>
	<div class="taC mt20">
		<a href="javascript:self.close();" class="nx-btn3">닫기</a>
	</div>
</div>

<?php
	include_once("../inc/pop.btm.php");
?>
