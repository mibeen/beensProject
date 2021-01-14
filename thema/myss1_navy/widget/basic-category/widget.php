<?php
if (!defined('_GNUBOARD_')) exit; //개별 페이지 접근 불가

global $menu;

include(G5_PATH. "/gseek/nx_gseek_menu.php");

//add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$widget_url.'/widget.css">', 0);

for ($i=0; $i < count($menu); $i++) {

	if($menu[$i]['on'] == "on" && $menu[$i]['is_sub']) {

?>
	<div class="basic-category nx_basic_category">
		<div class="ca-head">
			<?php echo $menu[$i]['name'];?>
		</div>
		<?php
		if(count($menu[$i]['sub']) == 0 && $menu[$i]['gr_id'] == "gseek")
		{
			echo $Menu_Left_Tag;
		}
		?>
		<?php for($j=0; $j < count($menu[$i]['sub']); $j++) { ?>
			<?php if($menu[$i]['sub'][$j]['line']) { //구분라인이 있을 때 ?>
				<div class="ca-line">
					<b><?php echo $menu[$i]['sub'][$j]['line'];?></b>
				</div>
			<?php } ?>
			<div class="ca-sub1 <?php echo $menu[$i]['sub'][$j]['on'];?>">
				<a href="<?php echo $menu[$i]['sub'][$j]['href'];?>"<?php echo $menu[$i]['sub'][$j]['target'];?> class="<?php echo ($menu[$i]['sub'][$j]['is_sub']) ? 'is' : 'no';?>-sub">
					<?php echo $menu[$i]['sub'][$j]['name'];?>
					<?php 
					/*
					if($menu[$i]['sub'][$j]['new'] == 'new') { 
					?>
						<i class="fa fa-bolt new"></i>
						<?php 
					} 
					*/
					?>
				</a>
			</div>
			<?php 
			/*
			if($menu[$i]['sub'][$j]['is_sub'] && $menu[$i]['sub'][$j]['on'] == 'on') { // 선택메뉴이면 서브 출력 ?>
				<ul class="ca-sub2">
				<?php for($k=0; $k < count($menu[$i]['sub'][$j]['sub']); $k++) { ?>
					<li class="<?php echo $menu[$i]['sub'][$j]['sub'][$k]['on']; ?>">
						<a href="<?php echo $menu[$i]['sub'][$j]['sub'][$k]['href'];?>"<?php echo $menu[$i]['sub'][$j]['sub'][$k]['target'];?>>
							<?php echo $menu[$i]['sub'][$j]['sub'][$k]['name'];?>
						</a>
					</li>
				<?php } ?>
				</ul>
			<?php }
			*/
			?>
		<?php } ?>
	</div>
<?php 
		break;
	} 
} 
?>
