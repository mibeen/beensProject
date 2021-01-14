<?php
//if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once("../bbs/_common.php");
include_once(G5_LIB_PATH.'/apms.thema.lib.php');
include_once('../bbs/_head.php');

$is_modal_js = apms_script('modal');
?>

<h3 class="nx_page_tit">조직도</h3>
<div class="data_ct">
	<div class="org_wrap">
		<?php
		# record chk
		$row = sql_fetch("Select Count(OS_IDX) As cnt From ORG_STAFF Where OS_DDATE is null And OP_GUBUN = 'S'");
		$cnt = $row['cnt'];
		unset($row);
		?>
		<div class="head circle">
			<a <?php echo(($cnt > 0) ? 'href="/page/organic.part.php?OP_GUBUN=S"' . $is_modal_js : 'href="javascript:void(0);" style="cursor:default"')?> data-modal-title="직원정보">
				<div class="tb">
					<div class="td">이사장</div>
				</div>
			</a>
		</div>
		<?php
		# record chk
		$row = sql_fetch("Select Count(OS_IDX) As cnt From ORG_STAFF Where OS_DDATE is null And OP_GUBUN = 'A'");
		$cnt = $row['cnt'];
		unset($row);
		?>
		<div class="left square">
			<a <?php echo(($cnt > 0) ? 'href="/page/organic.part.php?OP_GUBUN=A"' . $is_modal_js : 'href="javascript:void(0);" style="cursor:default"')?> data-modal-title="직원정보">
				<div class="tb">
					<div class="td">이사회</div>
				</div>
			</a>
		</div>
		<?php
		# record chk
		$row = sql_fetch("Select Count(OS_IDX) As cnt From ORG_STAFF Where OS_DDATE is null And OP_GUBUN = 'B'");
		$cnt = $row['cnt'];
		unset($row);
		?>
		<div class="depth2 square">
			<a <?php echo(($cnt > 0) ? 'href="/page/organic.part.php?OP_GUBUN=B"' . $is_modal_js : 'href="javascript:void(0);" style="cursor:default"')?> data-modal-title="직원정보">
				<div class="tb">
					<div class="td">원장</div>
				</div>
			</a>
		</div>
		<?php
		$sql = "Select OP.OP_IDX, OP.OP_PARENT2_IDX, OP.OP_SEQ, OP.OP_NAME"
			. "		, (Select Count(OS.OS_IDX) From ORG_STAFF As OS Where OS.OS_DDATE is null And OS.OP_PARENT_IDX = OP.OP_IDX And OS.OP_GUBUN = 'D') As cnt"
			. "		From ORG_PART As OP"
			. "	Where OP.OP_DDATE is null And OP.OP_PARENT_IDX is null And OP.OP_PARENT2_IDX is not null"
			. "	Order by OP.OP_SEQ Asc"
			;
		$db1 = sql_query($sql);

		$max = $db1->num_rows;
		if ($db1->num_rows > 0) {
			?>
		<div class="branch_wrap">
			<ul class="branch">
				<?php
				$s = 1;
				while($rs1 = sql_fetch_array($db1)) {
					?>
					<li>
						<?php
						if ($rs1['OP_PARENT2_IDX'] != 12) {
							$sql = "Select OP.OP_IDX, OP.OP_NAME"
								. "		, (Select Count(OS.OS_IDX) From ORG_STAFF As OS Where OS.OS_DDATE is null And OS.OP_GUBUN = 'C') As cnt"
								. "		From ORG_PART As OP"
								. "	Where OP.OP_DDATE is null And OP.OP_IDX = '" . mres($rs1['OP_PARENT2_IDX']) . "'"
								;
							$db2 = sql_query($sql);

							while($rs2 = sql_fetch_array($db2)) {
								?>
						<div class="depth1 square">
							<a <?php echo(($cnt > 0) ? 'href="/page/organic.part.php?OP_GUBUN=C"' . $is_modal_js : 'href="javascript:void(0);" style="cursor:default"')?> data-modal-title="직원정보">
								<div class="tb">
									<div class="td"><?php echo($rs2['OP_NAME'])?></div>
								</div>
							</a>
						</div>
								<?php
							}
						} unset($db2, $rs2);
						?>

						<div class="<?php echo(($rs1['OP_PARENT2_IDX'] == 12) ? 'depth2' : 'depth1')?> square pass">
							<a <?php echo(($rs1['cnt'] > 0) ? 'href="/page/organic.part.php?OP_GUBUN=D&OP_IDX='.$rs1['OP_IDX'].'"' . $is_modal_js : 'href="javascript:void(0);" style="cursor:default"')?> data-modal-title="직원정보">
								<div class="tb">
									<div class="td"><?php echo(preg_replace('/\s/', '<br>', $rs1['OP_NAME'], 1))?></div>
								</div>
							</a>
						</div>

						<?php 
						$sql = "Select OP.OP_IDX, OP.OP_NAME"
							. "		, (Select Count(OS.OS_IDX) From ORG_STAFF As OS Where OS.OS_DDATE is null And OS.OP_IDX = OP.OP_IDX) As cnt"
							. "		From ORG_PART As OP"
							. "	Where OP.OP_DDATE is null And OP.OP_PARENT_IDX = '" . mres($rs1['OP_IDX']) . "'"
							. "	Order by OP.OP_SEQ Asc"
							;
						$db2 = sql_query($sql);

						if ($db2->num_rows > 0) {
							?>
						<div class="part">
							<?php
							while($rs2 = sql_fetch_array($db2)) {
								?>
							<a <?php echo(($rs2['cnt'] > 0) ? 'href="/page/organic.part.php?OP_GUBUN=E&OP_IDX='.$rs2['OP_IDX'].'"' . $is_modal_js : 'href="javascript:void(0);" style="cursor:default"')?> data-modal-title="직원정보">
								<div class="tb">
									<div class="td"><?php echo($rs2['OP_NAME'])?></div>
								</div>
							</a>
								<?php
							}
							?>
						</div>
							<?php
						} unset($db2, $rs2);
						?>
					</li>
					<?php
					$s++;
				}
				?>
				<li style="margin-right: 500px;">
					<?php
					$sql = "Select OP.OP_IDX, OP.OP_NAME"
						. "		, (Select Count(OS.OS_IDX) From ORG_STAFF As OS Where OS.OS_DDATE is null And OS.OP_IDX = OP.OP_IDX) As cnt"
						. "		From ORG_PART As OP"
						. "	Where OP.OP_DDATE is null And OP.OP_PARENT_IDX = '35'"
						. "	Order by OP.OP_SEQ Asc"
						;
					$db2 = sql_query($sql);

					if ($db2->num_rows > 0) {
						?>
					<div class="part nopath" >
						<?php
						while($rs2 = sql_fetch_array($db2)) {
							?>
						<a <?php echo(($rs2['cnt'] > 0) ? 'href="/page/organic.part.php?OP_GUBUN=E&OP_IDX='.$rs2['OP_IDX'].'"' . $is_modal_js : 'href="javascript:void(0);" style="cursor:default"')?> data-modal-title="직원정보">
							<div class="tb">
								<div class="td"><?php echo($rs2['OP_NAME'])?></div>
							</div>
						</a>
							<?php
						}
						?>
					</div>
						<?php
					} unset($db2, $rs2);
					?>
				</li>
			</ul>
		</div>
			<?php
		}
		unset($rs1, $db1, $max, $s);
		?>
	</div>

	<div class="m_org_wrap">
		<?php
		# record chk
		$row = sql_fetch("Select Count(OS_IDX) As cnt From ORG_STAFF Where OS_DDATE is null And OP_GUBUN = 'S'");
		$cnt = $row['cnt'];
		unset($row);
		?>
		<div class="head circle">
			<?php
			if ($cnt > 0) {
				echo '<a href="/page/organic.part.php?OP_GUBUN=S" '.$is_modal_js.'>';
			}
			else {
				echo '<a href="javascript:void(0);">';
			}
			?>
				<div class="tb">
					<div class="td">이사장</div>
				</div>
			</a>
		</div>
		<?php
		# record chk
		$row = sql_fetch("Select Count(OS_IDX) As cnt From ORG_STAFF Where OS_DDATE is null And OP_GUBUN = 'A'");
		$cnt = $row['cnt'];
		unset($row);
		?>
		<div class="right square">
			<?php
			if ($cnt > 0) {
				echo '<a href="/page/organic.part.php?OP_GUBUN=A" '.$is_modal_js.'>';
			}
			else {
				echo '<a href="javascript:void(0);">';
			}
			?>
				<div class="tb">
					<div class="td">이사회</div>
				</div>
			</a>
		</div>
		<?php
		# record chk
		$row = sql_fetch("Select Count(OS_IDX) As cnt From ORG_STAFF Where OS_DDATE is null And OP_GUBUN = 'B'");
		$cnt = $row['cnt'];
		unset($row);
		?>
		<div class="depth2 square">
			<?php
			if ($cnt > 0) {
				echo '<a href="/page/organic.part.php?OP_GUBUN=B" '.$is_modal_js.'>';
			}
			else {
				echo '<a href="javascript:void(0);">';
			}
			?>
				<div class="tb">
					<div class="td">원장</div>
				</div>
			</a>
		</div>
		<?php
		$sql = "Select OP.OP_IDX, OP.OP_PARENT2_IDX, OP.OP_SEQ, OP.OP_NAME"
			. "		, (Select Count(OS.OS_IDX) From ORG_STAFF As OS Where OS.OS_DDATE is null And OS.OP_PARENT_IDX = OP.OP_IDX And OS.OP_GUBUN = 'D') As cnt"
			. "		From ORG_PART As OP"
			. "	Where OP.OP_DDATE is null And OP.OP_PARENT_IDX is null And OP.OP_PARENT2_IDX is not null"
			. "	Order by OP.OP_SEQ Asc"
			;
		$db1 = sql_query($sql);

		$max = $db1->num_rows;
		if ($db1->num_rows > 0) {
			?>
		<div class="branch_wrap">
			<ul class="branch">
				<?php
				$s = 1;
				while($rs1 = sql_fetch_array($db1)) {
					?>
					<li<?php if($max == $s) echo(' class="last"')?>>
						<?php
						if ($rs1['OP_PARENT2_IDX'] != 12) {
							$sql = "Select OP.OP_IDX, OP.OP_NAME"
								. "		, (Select Count(OS.OS_IDX) From ORG_STAFF As OS Where OS.OS_DDATE is null And OS.OP_GUBUN = 'C') As cnt"
								. "		From ORG_PART As OP"
								. "	Where OP.OP_DDATE is null And OP.OP_IDX = '" . mres($rs1['OP_PARENT2_IDX']) . "'"
								;
							$db2 = sql_query($sql);

							while($rs2 = sql_fetch_array($db2)) {
								?>
						<div class="depth1 square nopath">
							<?php
							if ($rs2['cnt'] > 0) {
								echo '<a href="/page/organic.part.php?OP_GUBUN=C" '.$is_modal_js.'>';
							}
							else {
								echo '<a href="javascript:void(0);">';
							}
							?>
								<div class="tb">
									<div class="td"><?php echo($rs2['OP_NAME'])?></div>
								</div>
							</a>
						</div>
								<?php
							}
						} unset($db2, $rs2);
						?>

						<div class="depth1 square<?php if ($rs1['OP_PARENT2_IDX'] == 12) echo(' nopath')?>">
							<?php
							if ($rs1['cnt'] > 0) {
								echo '<a href="/page/organic.part.php?OP_GUBUN=D&OP_IDX='.$rs1['OP_IDX'].'" '.$is_modal_js.'>';
							}
							else {
								echo '<a href="javascript:void(0);">';
							}
							?>
								<div class="tb">
									<div class="td"><?php echo($rs1['OP_NAME'])?></div>
								</div>
							</a>
						</div>

						<?php
						$sql = "Select OP.OP_IDX, OP.OP_NAME"
							. "		, (Select Count(OS.OS_IDX) From ORG_STAFF As OS Where OS.OS_DDATE is null And OS.OP_IDX = OP.OP_IDX) As cnt"
							. "		From ORG_PART As OP"
							. "	Where OP.OP_DDATE is null And OP.OP_PARENT_IDX = '" . mres($rs1['OP_IDX']) . "'"
							. "	Order by OP.OP_SEQ Asc"
							;
						$db2 = sql_query($sql);

						if ($db2->num_rows > 0) {
							?>
						<div class="part">
							<?php
							while($rs2 = sql_fetch_array($db2)) {
								?>
							<?php
							if ($rs2['cnt'] > 0) {
								echo '<a href="/page/organic.part.php?OP_GUBUN=E&OP_IDX='.$rs2['OP_IDX'].'" '.$is_modal_js.'>';
							}
							else {
								echo '<a href="javascript:void(0);">';
							}
							?>
								<div class="tb">
									<div class="td"><?php echo($rs2['OP_NAME'])?></div>
								</div>
							</a>
								<?php
							}
							?>
						</div>
							<?php
						} unset($db2, $rs2);
						?>
					</li>
					<?php
					$s++;
				}
				?>
				<li class="no_parent">
					<?php
					$sql = "Select OP.OP_IDX, OP.OP_NAME"
						. "		, (Select Count(OS.OS_IDX) From ORG_STAFF As OS Where OS.OS_DDATE is null And OS.OP_IDX = OP.OP_IDX) As cnt"
						. "		From ORG_PART As OP"
						. "	Where OP.OP_DDATE is null And OP.OP_PARENT_IDX = '35'"
						. "	Order by OP.OP_SEQ Asc"
						;
					$db2 = sql_query($sql);

					if ($db2->num_rows > 0) {
						?>
					<div class="part nopath">
						<?php
						while($rs2 = sql_fetch_array($db2)) {
							?>
						<?php
						if ($rs2['cnt'] > 0) {
							echo '<a href="/page/organic.part.php?OP_GUBUN=E&OP_IDX='.$rs2['OP_IDX'].'" '.$is_modal_js.'>';
						}
						else {
							echo '<a href="javascript:void(0);">';
						}
						?>
							<div class="tb">
								<div class="td"><?php echo($rs2['OP_NAME'])?></div>
							</div>
						</a>
							<?php
						}
						?>
					</div>
						<?php
					} unset($db2, $rs2);
					?>
				</li>
			</ul>
		</div>
			<?php
		}
		unset($rs1, $db1, $max, $s);
		?>
	</div>
</div>

<?php 
# 메뉴 표시에 사용할 변수 
$_gr_id = 'about';  // 게시판 그룹 ID - 현재 메뉴표시에 사용
$pid = ($pid) ? $pid : 'organic';   // Page ID

include "../page/inc.page.menu.php";

include_once('../bbs/_tail.php');
?>