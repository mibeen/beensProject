<?php
	include_once("./_common.php");
	include_once(G5_LIB_PATH.'/apms.thema.lib.php');

	
	# 로그인 chk
	if (is_null($member['mb_id'])) {
		exit();
	}


	// 테마설정
	$at = apms_gr_thema();
	if(!defined('THEMA_PATH')) {
		include_once(G5_LIB_PATH.'/apms.thema.lib.php');
	}


	# set : variables
	$EM_IDX = $_REQUEST['EM_IDX'];
	$EJ_IDX = $_REQUEST['EJ_IDX'];
	$onload = $_REQUEST['onload'];
	

	# re-define
	$EM_IDX = CHK_NUMBER($EM_IDX);
	$EJ_IDX = CHK_NUMBER($EJ_IDX);


	# chk : rfv.
	if ($EM_IDX <= 0 || $EJ_IDX <= 0) exit;


	$sql = "Select EM.EM_TITLE, EM.EM_CERT_TITLE, EM.EM_CERT_TIME, EM.EM_CERT_MINUTE, EM.EM_CERT_ORG_YN, EM.EM_REQUIRE_BIRTH_YN, EM.EM_S_DATE, EM.EM_E_DATE, EM.EM_DATE_YN"
		."		, DATE_FORMAT(EM.EM_S_DATE, '%Y. %m. %d.') As EM_S_DATE"
		."		, DATE_FORMAT(EM.EM_E_DATE, '%Y. %m. %d.') As EM_E_DATE"
		."		, EJ.*"
		."		, EC.EC_NAME"
		."	From NX_EVENT_MASTER As EM"
		."		Inner Join NX_EVENT_JOIN As EJ On EJ.EM_IDX = EM.EM_IDX"
		."			And EJ.EJ_DDATE is null"
		."			And EJ.mb_id = '" . mres($member['mb_id']) . "'"
		."		Left Join NX_EVENT_CATE As EC On EC.EC_IDX = EM.EC_IDX"
		."	Where EM.EM_DDATE is null"
		."		And EM.EM_IDX = '" . mres($EM_IDX) . "'"
		."		And EJ.EJ_IDX = '" . mres($EJ_IDX) . "'"
		."	Limit 1"
		;
	$rs1 = sql_fetch($sql);
	if (is_null($rs1['EM_IDX'])) {
		unset($rs1);
		F_script("존재하지 않는 정보 입니다.", "parent.self.close();");
	}
	$DB_EJ = $rs1;
	unset($rs1);


	$EC_NAME = (in_array($DB_EJ['EC_NAME'], array('행사', '모집', '교육'))) ? $DB_EJ['EC_NAME'] : '행사';

	$MSG = array('행사' => '참가', '모집' => '참가', '교육' => '이수');
	$MSG = ($MSG[$EC_NAME] != "") ? $MSG[$EC_NAME] : '참가';
?>
<!doctype html>
<html lang="ko">
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="<?php echo(THEMA_URL)?>/colorset/Basic/certiprint.css" type="text/css">
	<?php
	# ie에서 css 추가 - 머리말, 꼬리말 보이지 않게.
	$ua = htmlentities($_SERVER['HTTP_USER_AGENT'], ENT_QUOTES, 'UTF-8');
	if (preg_match('~MSIE|Internet Explorer~i', $ua) || (strpos($ua, 'Trident/7.0; rv:11.0') !== false)) {
		?>
		<style>
		@page {
			margin: 0 -15cm;
		}
		@media print {
			html {
				margin: 0 15cm;
			}
			.page {
				background-size: 180mm auto;
			}
			.page .img_frame {
				width: 192mm;
				height: 279.1mm;
			}
		}
		</style>
		<?php
	}
	?>
</head>
<body>
	<div class="page" style="text-align:center">
		<img src="../img/certi/frame.png" alt="" class="img_frame">
		<div class="certi_paper">
			<div class="inner<?php if ((int)$DB_EJ['EM_CERT_TIME'] >= 1){echo(' cert_time');}?>">
				<?php /*<img src="../img/certi/bg_gg.png" alt="" class="img_gg">*/ ?>
				<img src="../img/certi/logo.png" alt="" class="logo">
				<p class="num">NO. <?php echo(F_hsc($DB_EJ['EJ_JOIN_CODE']))?>&nbsp;</p>
				<div class="certi_img">
					<div class="txt"><span><?php echo(($DB_EJ['EM_CERT_TITLE'] != '') ? $DB_EJ['EM_CERT_TITLE'] : '이 수 확 인 증')?></span></div>
					<?php /*<img src="../img/certi/certification.png" alt="">*/ ?>
				</div>
				<div class="certi_info">
					<div class="certi_info_p"><div class="tit_wrap"><div class="tit"><?php echo(mb_substr($EC_NAME, 0, 1))?> <?php echo(mb_substr($EC_NAME, 1, 1))?> 명</div> : </div><?php echo(F_hsc($DB_EJ['EM_TITLE']))?>&nbsp;</div>
					<?php
					if ($DB_EJ['EM_CERT_ORG_YN'] != 'N') {
						?>
					<div class="certi_info_p"><div class="tit_wrap"><div class="tit">소 속</div> : </div><?php echo(F_hsc($DB_EJ['EJ_ORG']))?>&nbsp;</div>
						<?php
					}
					?>
					
					<?php
					if ($DB_EJ['EM_DATE_YN'] != 'N') {
					?>
					<?php
					echo '<div class="certi_info_p"><div class="tit_wrap"><div class="tit">기 간</div> : </div>';
					echo F_hsc($DB_EJ['EM_S_DATE']);
					
					# 시작일 = 종료일 일 경우 시작일만 표시
					if ($DB_EJ['EM_S_DATE'] != $DB_EJ['EM_E_DATE']) {
						echo ' ~ ' . F_hsc($DB_EJ['EM_E_DATE']);
					}
					echo '&nbsp;</div>';
					}
					
                
					if ($DB_EJ['EM_CERT_TIME'] >= 1 || $DB_EJ['EM_CERT_MINUTE'] >= 1) {
						echo '<div class="certi_info_p"><div class="tit_wrap"><div class="tit">인 정 시 간</div> : </div>';
						if ($DB_EJ['EM_CERT_TIME'] >= 1) {
							echo $DB_EJ['EM_CERT_TIME'] . '시간 ';
						}
						if ($DB_EJ['EM_CERT_MINUTE'] >= 1) {
							echo $DB_EJ['EM_CERT_MINUTE'] . '분';
						}
						echo '&nbsp;</div>';
					}
					?>
					
					<div class="certi_info_p">
						<div class="tit_wrap"><div class="tit">이 름</div> : </div>
						<?php echo(F_hsc($DB_EJ['EJ_NAME']))?>&nbsp;
						<?php if($DB_EJ['EM_REQUIRE_BIRTH_YN'] == 'Y' && $DB_EJ['EJ_BIRTH'] != '') { echo('(' . str_replace('-', '.', $DB_EJ['EJ_BIRTH']) . ')'); } ?>
					</div> 
				</div>
				<p class="ct">
					위 사람은 경기도평생교육진흥원에서<br>
					주최하는 본 <?php echo($EC_NAME . (($MSG == "참가") ? '에' : '을'))?><br>
					<?php echo($MSG)?>하였음을 확인합니다.
				</p>
				<div class="btm">
					<p class="date"><?php echo(date('Y. m. d.'))?></p>
					<div class="auth_wrap">
						<p class="auth">경기도평생교육진흥원장</p>
						
						<img src="../img/certi/stamp.jpg" alt="" class="img_stamp">
					</div>
				</div>
			</div>
		</div>
	</div>

	<script>
	//<![CDATA[
	<?php
	if ($onload == 'Y') {
		?>
		window.onload = function() {
			window.print();
		}
		<?php
	}
	?>

	var _print = function() {
		window.print();
	}
	//]]>
	</script>
</body>
</html>
