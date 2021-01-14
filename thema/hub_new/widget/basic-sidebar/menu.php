<?php
if (!defined('_GNUBOARD_')) exit; //개별 페이지 접근 불가


include(G5_PATH. "/gseek/nx_gseek_menu.php");
?>

<div class="sidebar-menu panel-group" id="<?php echo $menu_id;?>" role="tablist" aria-multiselectable="true">
	<?php 
	for($i=1; $i < $menu_cnt; $i++) { 
		$cate_id = $menu_id.'_c'.$i;
		$sub_id = $menu_id.'_s'.$i;


		if( $menu[$i]['show'] == 1){
			// 그룹접근 - 관리자 메뉴용.

			// 그룹접근
            $sql = " SELECT count(*) AS cnt FROM {$g5['group_member_table']} WHERE gr_id = '{$menu[$i]['gr_id']}' AND mb_id = '{$member['mb_id']}' ";
            $row = sql_fetch($sql);
            if (!$row['cnt']) {
                continue;
            }

		}

		if($menu[$i]['is_sub'] || $menu[$i]['gr_id'] == "learning") { //서브메뉴가 있을 때
			# 1.주메뉴에 직접 연결된 링크 2.첫번째로 등장한 타이틀메뉴가 아닌 메뉴의 링크를 가져옴
			$side_mna_link = 'javascript:void(0);';
			if ($menu[$i]['href'] != "") $side_mna_link = $menu[$i]['href'];

			if ($side_mna_link == 'javascript:void(0);') {
				for ($j = 0; $j < $menu[$i]['sub']; $j++) {
					if ($menu[$i]['sub'][$j]['depth2'] != 'Y') {
						$side_mna_link = $menu[$i]['sub'][$j]['href'];
						break;
					}
				}
			}
			?>
			<div class="panel">
				<div class="ca-head<?php echo ($menu[$i]['on'] == "on") ? ' active' : '';?>" role="tab" id="<?php echo $cate_id;?>">
					<a href="#<?php echo $sub_id;?>" data-toggle="collapse" data-parent="#<?php echo $menu_id;?>" aria-expanded="true" aria-controls="<?php echo $sub_id;?>" class="is-sub">
						<span class="ca-href pull-right" onclick="sidebar_href('<?php echo $side_mna_link; ?>');">&nbsp;</span>
						<?php echo $menu[$i]['name']; ?>
						<?php echo ($menu[$i]['new'] == "new") ? $menu_new : '';?>
					</a>
				</div>
				<div id="<?php echo $sub_id;?>" class="panel-collapse collapse<?php echo ($menu[$i]['on'] == "on") ? ' in' : '';?>" role="tabpanel" aria-labelledby="<?php echo $cate_id;?>">
					<ul class="ca-sub">
					<?php
					if(count($menu[$i]['sub']) == 0 && $menu[$i]['gr_id'] == "learning")
					{
						echo $Menu_Side_Tag;
					}
					for($j=0; $j < count($menu[$i]['sub']); $j++) { 
					    $nextDepth2Title = 'N';
					    if ($j + 1 < count($menu[$i]['sub'])) {
					        $nextDepth2Title = $menu[$i]['sub'][$j+1]['depth2'];
					    }
						?>
						<?php if($menu[$i]['sub'][$j]['line']) { //구분라인 ?>
							<li class="ca-line">
								<?php echo $menu[$i]['sub'][$j]['line'];?>
							</li>
						<?php } ?>
						<li class="<?php if($menu[$i]['sub'][$j]['depth2'] == 'Y') {echo('tit-menu');} ?><?php echo ($menu[$i]['sub'][$j]['on'] == "on") ? ' on' : '';?>">
							<a href="<?php 
							if($menu[$i]['sub'][$j]['depth2'] != 'Y'){
							    echo($menu[$i]['sub'][$j]['href']);
							}else if($menu[$i]['sub'][$j]['depth2'] == 'Y' && $nextDepth2Title == 'Y'){
							    echo($menu[$i]['sub'][$j]['href']);
							}else{
							    echo('javascript:void(0)');
							}
							?>"
							
						    <?php echo $menu[$i]['sub'][$j]['target'];?>>
								<?php echo $menu[$i]['sub'][$j]['name']; ?>
								<?php echo ($menu[$i]['sub'][$j]['new'] == "new") ? $menu_new : '';?>
							</a>
						</li>
					<?php } ?>
					</ul>
				</div>
			</div>
		<?php } else { ?>
			<div class="panel">
				<div class="ca-head<?php echo ($menu[$i]['on'] == "on") ? ' active' : '';?>" role="tab">
					<a href="<?php echo $menu[$i]['href'];?>"<?php echo $menu[$i]['target'];?> class="no-sub">
						<?php echo $menu[$i]['name'];?>
						<?php echo ($menu[$i]['new'] == "new") ? $menu_new : '';?>
					</a>
				</div>
			</div>
		<?php } ?>
	<?php } ?>

	<?php
	# 마이페이지 메뉴일 경우 고정 메뉴 노출
	global $_gr_id, $pid;
	if ($_gr_id == 'nx05') {
		?>
		<div class="panel">
			<div class="ca-head active" role="tab" id="sidebar_menu_c_nx05">
				<a href="#sidebar_menu_s_nx05" data-toggle="collapse" data-parent="#<?php echo $menu_id;?>" aria-expanded="true" aria-controls="sidebar_menu_s_nx05" class="is-sub">
					마이페이지
				</a>
			</div>
			<div id="sidebar_menu_s_nx05" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="sidebar_menu_c_nx05">
				<ul class="ca-sub">
					<li<?php if($pid == 'myedu') {echo(' class="on"');}?>>
						<a href="/gseek/nx_my_list.php">나의 학습</a>
					</li>
					<li<?php if($pid == 'myevt') {echo(' class="on"');}?>>
						<a href="/my/evt.list.php">모집</a>
					</li>
					<li<?php if($pid == 'myrent') {echo(' class="on"');}?>>
						<a href="/my/rent.list.php">대관</a>
					</li>
					<li<?php if($pid == 'editprofile') {echo(' class="on"');}?>>
						<a href="javascript:sidebarGseekLink('<?php echo(GSK_www_URL)?>/memb/myss.edit.info.pwcheck');">개인정보수정</a>
					</li>
					<li<?php if($pid == 'editpw') {echo(' class="on"');}?>>
						<a href="javascript:sidebarGseekLink('<?php echo(GSK_www_URL)?>/memb/myss.edit.pw.pwcheck');">비밀번호변경</a>
					</li>
					<li<?php if($pid == 'myout') {echo(' class="on"');}?>>
						<a href="/my/outintro.php">탈퇴하기</a>
					</li>
				</ul>
			</div>
		</div>
		<?php
	}
	?>
</div>

<script>
//<![CDATA[
function sidebarGseekLink(url) {
	$(".sidebar-close").click();
	win_open2(url, '600');
}
//]]>
</script>