<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

#print_r($menu);
#die();
?>

<?php if($is_top_nav == "float"){ // 좌측형 ?>

	<div class="nav-visible">
		<div class="at-container">
			<div class="nav-top nav-float nav-slide">
				<ul id="mnb" class="menu-ul">

				<?php
					for ($i=1; $i < $menu_cnt; $i++) {

						if(!$menu[$i]['gr_id']) continue;
				?>
					<li class="menu-li <?php echo $menu[$i]['on'];?>">
						<a class="menu-a nav-height" href="<?php echo $menu[$i]['href'];?>"<?php echo $menu[$i]['target'];?>>
							<?php echo $menu[$i]['name'];?>
							<?php if($menu[$i]['new'] == "new") { ?>
								<i class="fa fa-bolt new"></i>
							<?php } ?>
						</a>
						<?php if($menu[$i]['is_sub']) { //Is Sub Menu ?>
							<div class="sub-slide sub-1div">
								<ul class="sub-1dul subm-w pull-left">
								<?php
									$smw1 = 1; //나눔 체크
									for($j=0; $j < count($menu[$i]['sub']); $j++) {
								?>
									<?php if($menu[$i]['sub'][$j]['sp']) { //나눔 ?>
										</ul>
										<ul class="sub-1dul subm-w pull-left">
									<?php $smw1++; } // 나눔 카운트 ?>

									<?php if($menu[$i]['sub'][$j]['line']) { //구분라인 ?>
										<li class="sub-1line"><a><?php echo $menu[$i]['sub'][$j]['line'];?></a></li>
									<?php } ?>

									<li class="sub-1dli <?php echo $menu[$i]['sub'][$j]['on'];?>">
										<a href="<?php echo $menu[$i]['sub'][$j]['href'];?>" class="sub-1da"<?php echo $menu[$i]['sub'][$j]['target'];?>>
											<?php echo $menu[$i]['sub'][$j]['name'];?>
											<?php if($menu[$i]['sub'][$j]['new'] == "new") { ?>
												<i class="fa fa-bolt sub-1new"></i>
											<?php } ?>
										</a>
									</li>
								<?php } //for ?>
								</ul>
								<?php $smw1 = ($smw1 > 1) ? $is_subw * $smw1 : 0; //서브메뉴 너비 ?>
								<div class="clearfix"<?php echo ($smw1) ? ' style="width:'.$smw1.'px;"' : '';?>></div>
							</div>
						<?php }  ?>
					</li>
				<?php } //for ?>
				</ul>
			</div><!-- .nav-top -->
		</div>	<!-- .nav-container -->
	</div><!-- .nav-visible -->

<?php } else { // 배분형 ?>

	<div class="nav-visible">
		<div class="at-container">
			<div class="nav-top nav-both nav-slide">
				<ul id="mnb" class="menu-ul">
				<?php 
				 ?>
				<?php
					for ($i=1; $i < $menu_cnt; $i++) {

						if(!$menu[$i]['gr_id']) continue;

						if( $menu[$i]['show'] == 1){

							// 그룹접근
				            $sql = " SELECT count(*) AS cnt FROM {$g5['group_member_table']} WHERE gr_id = '{$menu[$i]['gr_id']}' AND mb_id = '{$member['mb_id']}' ";
				            $row = sql_fetch($sql);
				            if (!$row['cnt']) {
				                continue;
				            }

						}

						## 링크 설정
						
						# 주메뉴 - 1.주메뉴에 직접 연결된 링크 2.첫번째로 등장한 타이틀메뉴가 아닌 메뉴의 링크
						# 타이틀메뉴 - 타이틀메뉴 바로 다음 메뉴의 링크
						$mna_link = 'javascript:void(0);'; // 주메뉴 링크 - 수정전 $menu[$i]['href']
						if ($menu[$i]['href'] != "") $mna_link = $menu[$i]['href'];

						$arr_depth2_link = array();
						$is_tit = false;
						$tit_exist = false;
						for ($j = 0; $j < count($menu[$i]['sub']); $j++) {
						    #j+변수의 outboundIndex Exception  방지를 위해 처리 
						    $nextDepth2Title = 'N';
						    if ($j + 1 < count($menu[$i]['sub'])) {
						        $nextDepth2Title = $menu[$i]['sub'][$j+1]['depth2'];
						    }
						    
						    #2depth title이 아닐때
							if ($menu[$i]['sub'][$j]['depth2'] != 'Y') { 
								if ($mna_link == 'javascript:void(0);') {
									$mna_link = $menu[$i]['sub'][$j]['href']; 
								}
								# 직전에 타이틀메뉴가 있었다면 현재 메뉴의 링크를 배열에 저장
								if ($is_tit) {
									$arr_depth2_link[] = $menu[$i]['sub'][$j]['href'];
									$is_tit = false;
								}
							}
							#타이틀 체크를 했지만, 2depth 메뉴처럼 쓰고 싶은경우
							else if($menu[$i]['sub'][$j]['depth2'] == 'Y' && $nextDepth2Title == 'Y' ){ 
							    $arr_depth2_link[] = $menu[$i]['sub'][$j]['href'];
							    $is_tit = false;
							}
							#2depth title 일때
							else { 
							    if ($is_tit){
							        $arr_depth2_link[] = 'javascript:void(0);';
							    }
								$is_tit = true;
								$tit_exist = true;

								# 타이틀 메뉴가 가장 마지막 메뉴이면 링크없음 -> 자신의 링크로 그대로 사용
								if ($j + 1 == count($menu[$i]['sub'])) {
							        $arr_depth2_link[] = $menu[$i]['sub'][$j]['href'];
							        //$arr_depth2_link[] = 'javascript:void(0);';
								}
							}
						}
				?>
					<li class="menu-li <?php echo $menu[$i]['on'];?>">
						<a class="menu-a nav-height" href="<?php echo($mna_link)?>"<?php echo $menu[$i]['target'];?>>
							<?php echo $menu[$i]['name'];?>
							<?php if($menu[$i]['new'] == "new") { ?>
								<i class="fa fa-bolt new"></i>
							<?php } ?>
						</a>
						<?php if($menu[$i]['is_sub']) { //Is Sub Menu ?>
							<div class="sub-slide sub-1div">
								<ul class="sub-1dul">
								<?php for($j=0; $j < count($menu[$i]['sub']); $j++) { ?>

									<?php
									# 타이틀 메뉴가 있으면 다른 메뉴는 보이지 않음
									if ($tit_exist && $menu[$i]['sub'][$j]['depth2'] != 'Y') {
										continue;
									}

									# 타이틀 메뉴가 있으면 미리 생성한 배열에서, 없으면 기존 링크를 사용
									$link_text = "";
									if ($menu[$i]['sub'][$j]['depth2'] != 'Y') {
										$link_text = $menu[$i]['sub'][$j]['href'];
									} 
									else {
										$link_text = array_shift($arr_depth2_link);
									}
									?>

									<?php if($menu[$i]['sub'][$j]['line']) { //구분라인 ?>
										<li class="sub-1line"><a><?php echo $menu[$i]['sub'][$j]['line'];?></a></li>
									<?php } ?>

									<li class="sub-1dli <?php echo $menu[$i]['sub'][$j]['on'];?>">
										<a href="<?php echo($link_text)?>" class="sub-1da"<?php echo $menu[$i]['sub'][$j]['target'];?>>
											<?php echo $menu[$i]['sub'][$j]['name'];?>
											<?php if($menu[$i]['sub'][$j]['new'] == "new") { ?>
												<i class="fa fa-bolt sub-1new"></i>
											<?php } ?>
										</a>
									</li>
								<?php } //for ?>
								</ul>
							</div>
						<?php } else if($menu[$i]['gr_id'] == "learning")
							echo str_replace('display: none; visibility: visible;','',$Menu_Html_Tag); ?>
					</li>
				<?php } //for ?>
				</ul>
			</div><!-- .nav-top -->
		</div>	<!-- .nav-container -->
	</div><!-- .nav-visible -->

<?php } ?>

<script>
$(function(){

	var setInt = "";

	// $(document).on('mouseover', '.pc-menu .nav-top', function(e){
	// 	$(this).find('.sub-slide').stop().slideDown();

	// })

	/*

	$('.pc-menu .nav-top').hover(function(){
		// $(this).find('.sub-slide').stop().show();
		$(".nx_pc_header_wrap").stop().addClass('active');
	},function(){
		// $(this).find('.sub-slide').stop().hide();
		$(".nx_pc_header_wrap").stop().removeClass('active');
	})

	*/

})
</script>
