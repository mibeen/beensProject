<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가 
?>

<?php if($is_top_nav == "float"){ // 좌측형 ?>

	<div class="nav-visible">
		<div class="at-container">
			<div class="nav-top nav-float nav-slide">
				<ul class="menu-ul">
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

	<div class="nx-mnb-wrap1">
		<div class="nx-mnb-wrap2">
			<ul id="mnb" class="nx-mnb<?php if(!$is_main){echo(' sub');}?>">
			<?php 
				for ($i=1; $i < $menu_cnt; $i++) {

					if(!$menu[$i]['gr_id']) continue;						
			?>
				<li class="<?php echo $menu[$i]['on'];?>">
					<a href="<?php echo $menu[$i]['href'];?>"<?php echo $menu[$i]['target'];?>>
						<div class="cell">
							<span class="txt">
								<?php echo $menu[$i]['name'];?>
							</span>
						</div>
					</a>
					<?php if($menu[$i]['is_sub']) { //Is Sub Menu ?>
						<ul class="nx-snb">
						<?php for($j=0; $j < count($menu[$i]['sub']); $j++) { ?>
							<li>
								<a href="<?php echo $menu[$i]['sub'][$j]['href'];?>" class="<?php echo $menu[$i]['sub'][$j]['on'];?>"<?php echo $menu[$i]['sub'][$j]['target'];?>>
									<?php echo $menu[$i]['sub'][$j]['name'];?>
								</a>
							</li>
						<?php } //for ?>
						</ul>
					<?php } else if($menu[$i]['gr_id'] == "gseek")
						echo $Menu_Html_Tag; ?>
				</li>
			<?php } //for ?>
				<!-- <div class="clearfix"></div> -->
			</ul>
		</div>
	</div>

<?php } ?>

<script>
//<![CDATA[
$('#mnb').mouseover(function() {
	$('#mnb .nx-snb').stop().slideDown();
	$(document).on('touchend', function(e)
	{
		var container = $('#mnb');

		// if the target of the click isn't the container nor a descendant of the container
		if (!container.is(e.target) && container.has(e.target).length === 0) 
		{
			$('#mnb .nx-snb').stop().slideUp();
			$(document).off('touchend');
		}
	});
}).mouseleave(function() {
	setTimeout(function() {
		if ($('#mnb').is(':hover')) return;
		else $('#mnb .nx-snb').stop().slideUp();
	}, 500);
});
//]]>
</script>