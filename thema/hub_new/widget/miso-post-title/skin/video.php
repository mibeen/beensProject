<?php
if (!defined('_GNUBOARD_')) exit; //개별 페이지 접근 불가

$wset['thumb_w'] = $wset['thumb_h'] = 10;

// 추출
$list = miso_widget_list($wset, $widget_path, $widget_url);
$list_cnt = count($list);

// 모바일 추출
//$wmset['data'] = $wset['data'];
//$list_m = miso_widget_list($wmset, $widget_path, $widget_url);
//$list_cnt_m = count($list_m);

?>
<style>
	.display_991 { display: block; }
	.display_991_m { display: none; }
	@media only screen and (max-width: 991px) {
		.display_991 { display: none; }
		.display_991_m { display: block; }
		.img-raster3 { background: none; }
		.img-opa30 { opacity: 0; -webkit-opacity: 0; -moz-opacity: 0; -khtml-opacity: 0; }
	}
</style>
<?php 
if($is_slider_m == 'slider')  { ?>

	<div class="full-zone display_991_m">
			<ul class="bxslider title-basic en">
			
			<?php
			// 리스트
			$str_m = $wmset['uniq'];
			$arr_m = explode(',', $str_m);

			for ($i=0; $i < count($arr_m); $i++) { 
				// 배열값이 빈값일경우 패스
				if ($arr_m[$i] == '') continue;

				$img_m =  $arr_m[$i].'_img';
				$txt1_m = $arr_m[$i].'_txt1';
				$txt2_m = $arr_m[$i].'_txt2';
				$barcolor_m = $arr_m[$i].'_barcolor';
				$url_m = $arr_m[$i].'_url';
				$target_m = $arr_m[$i].'_target';
				$rank_m = $arr_m[$i].'_rank';
				$fsize1_m = $arr_m[$i].'_fsize1';
				$fcolor1_m = $arr_m[$i].'_fcolor1';
				$fsize2_m = $arr_m[$i].'_fsize2';
				$fcolor2_m = $arr_m[$i].'_fcolor2';

				// 이미지
				$bgimg = '';
				if($wmset[$img_m]) {
					$bgimg = ' style="background-image: url(\''.$wmset[$img_m].'\');"';
				}

				$fsize1  = trim($wmset[$fsize1_m])  != '' ? 'font-size: ' . $wmset[$fsize1_m].'px;'   : '';
				$fcolor1 = trim($wmset[$fcolor1_m]) != '' ? 'color: ' . $wmset[$fcolor1_m].';' : '';
				$fsize2   = trim($wmset[$fsize2_m]) != '' ? 'font-size: ' . $wmset[$fsize2_m].'px;'   : '';
				$fcolor2 = trim($wmset[$fcolor2_m]) != '' ? 'color: ' . $wmset[$fcolor2_m].';' : '';
			?>
			<li class="item<?php echo $bgcolor;?>"<?php echo $bgimg;?>>
				
				<div class="img-tbl">
					<div class="img-cell full-zone">
						<div class="title-content">
							<?php if($wmset[$txt1_m]) { ?>
								<a href="<?php echo $wmset[$url_m];?>"<?php echo $wmset[$target_m];?>>
									<h3 style="<?php echo $fsize1 . $fcolor1 ?>"><?php echo $wmset[$txt1_m];?></h3>
								</a>
							<?php } ?>
							<?php if($wmset[$txt1_m] && $wmset[$txt2_m]) { ?>
								<hr class="bg-<?php echo $bg;?>" style="<?php if (trim($wmset[$barcolor_m]) != '') echo 'background: ' . $wmset[$barcolor_m]; ?>">
							<?php } ?>
							<?php if($wmset[$txt2_m]) { ?>
								<p style="<?php echo $fsize2 . $fcolor2 ?>"><?php echo $wmset[$txt2_m];?></p>
							<?php } ?>
						</div>
					</div>
				</div>
			</li>
			<?php } ?>
			</ul>
		</div>

	<?php if($is_slider == 'slider') { // 슬라이더 타입일 경우 ?>
		
		<div class="full-zone display_991">
			<ul class="bxslider title-basic en">
			<?php if($wset['cover']) { // 로고출력 ?>
				<li class="item" style="background-image : url('<?php echo $bg_img;?>');">
					<?php echo $raster;?>
					<div class="at-container">
						<div class="img-tbl">
							<div class="img-cell full-zone">
								<div class="title-content">
									<a href="<?php echo $at_href['home'];?>" class="title-logo hidden-xs hidden-sm">
										<img src="<?php echo ($wset['logo']) ? $wset['logo'] : THEMA_URL.'/logo.png';?>" alt="">
									</a>
									<div class="title-search">
										<form name="tsearch" method="get" action="<?php echo $at_href['search'];?>" onsubmit="return tsearch_submit(this);" role="form" class="form">
										<div class="input-group">
											<input type="text" name="stx" class="form-control" value="<?php echo $stxa;?>">
											<span class="input-group-btn">
												<button type="submit" class="btn btn-sm"><i class="fa fa-search fa-lg"></i></button>
											</span>
										</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</li>
			<?php } ?>
			<?php
			// 리스트
			for ($i=0; $i < $list_cnt; $i++) { 

				// 이미지
				$bg = substr($list[$i]['rank'], -1);
				$bgcolor = $bgimg = '';
				if($list[$i]['img']['src']) {
					$bgimg = ' style="background-image: url(\''.$list[$i]['img']['org'].'\');"';
				} else {
					$bgcolor = ' bg-'.$bg;
				}

				$fsize1  = trim($list[$i]['fsize1'])  != '' ? 'font-size: ' . $list[$i]['fsize1'] . 'px;'   : '';
				$fcolor1 = trim($list[$i]['fcolor1']) != '' ? 'color: ' . $list[$i]['fcolor1'] . ';' : '';
				$fsize2   = trim($list[$i]['fsize2']) != '' ? 'font-size: ' . $list[$i]['fsize2'] . 'px;'   : '';
				$fcolor2 = trim($list[$i]['fcolor2']) != '' ? 'color: ' . $list[$i]['fcolor2'] . ';' : '';
			?>
			<li class="item<?php echo $bgcolor;?>"<?php echo $bgimg;?>>
				<?php echo $raster;?>
				<div class="img-tbl">
					<div class="img-cell full-zone">
						<?php if(!$wset['text']) { // 내용출력 ?>
							<div class="title-content">
								<?php
									$video = apms_link_video($list[$i]['link'], 1);
									if($video) {
								?>
									<div class="title-video">
										<?php echo $video;?>
									</div>
								<?php } else { ?>
									<?php if($list[$i]['subject']) { ?>
										<a href="<?php echo $list[$i]['href'];?>"<?php echo $list[$i]['target'];?>>
											<h3 style="<?php echo $fsize1 . $fcolor1 ?>"><?php echo $list[$i]['subject'];?></h3>
										</a>
									<?php } ?>
									<?php if($list[$i]['subject'] && $list[$i]['content']) { ?>
										<hr class="bg-<?php echo $bg;?>" style="<?php if (trim($list[$i]['barcolor']) != '') echo 'background: ' . $list[$i]['barcolor']; ?>">
									<?php } ?>
									<?php if($list[$i]['content']) { ?>
										<p style="<?php echo $fsize2 . $fcolor2 ?>"><?php echo $list[$i]['content'];?></p>
									<?php } ?>
								<?php } ?>
							</div>
						<?php } ?>
					</div>
				</div>
			</li>
			<?php } ?>
			</ul>
		</div>

	<?php } else { ?>

		<?php echo $raster;?>
		<div class="at-container display_991">
			<div class="img-tbl">
				<div class="img-cell full-zone">
					<ul class="bxslider title-basic en">
					<?php if($wset['cover']) { // 로고출력 ?>
						<li class="item">
							<div class="img-tbl">
								<div class="img-cell">
									<div class="title-content">
										<a href="<?php echo $at_href['home'];?>" class="title-logo hidden-xs hidden-sm">
											<img src="<?php echo ($wset['logo']) ? $wset['logo'] : THEMA_URL.'/logo.png';?>" alt="">
										</a>
										<div class="title-search">
											<form name="tsearch" method="get" action="<?php echo $at_href['search'];?>" onsubmit="return tsearch_submit(this);" role="form" class="form">
											<div class="input-group">
												<input type="text" name="stx" class="form-control" value="<?php echo $stxa;?>">
												<span class="input-group-btn">
													<button type="submit" class="btn btn-sm"><i class="fa fa-search fa-lg"></i></button>
												</span>
											</div>
											</form>
										</div>
									</div>
								</div>
							</div>
						</li>
					<?php } ?>
					<?php
					// 리스트
					for ($i=0; $i < $list_cnt; $i++) { 
						$bg = substr($list[$i]['rank'], -1);

						$fsize1  = trim($list[$i]['fsize1'])  != '' ? 'font-size: ' . $list[$i]['fsize1'] . 'px;'   : '';
						$fcolor1 = trim($list[$i]['fcolor1']) != '' ? 'color: ' . $list[$i]['fcolor1'] . ';' : '';
						$fsize2   = trim($list[$i]['fsize2']) != '' ? 'font-size: ' . $list[$i]['fsize2'] . 'px;'   : '';
						$fcolor2 = trim($list[$i]['fcolor2']) != '' ? 'color: ' . $list[$i]['fcolor2'] . ';' : '';
					?>
					<li class="item">
						<?php if(!$wset['text']) { // 내용출력 ?>
							<div class="img-tbl">
								<div class="img-cell">
									<div class="title-content">
										<?php
											$video = apms_link_video($list[$i]['link'], 1);
											if($video) {
										?>
											<div class="title-video">
												<?php echo $video;?>
											</div>
										<?php } else { ?>
											<?php if($list[$i]['subject']) { ?>
												<a href="<?php echo $list[$i]['href'];?>"<?php echo $list[$i]['target'];?>>
													<h3 style="<?php echo $fsize1 . $fcolor1 ?>"><?php echo $list[$i]['subject'];?></h3>
												</a>
											<?php } ?>
											<?php if($list[$i]['subject'] && $list[$i]['content']) { ?>
												<hr class="bg-<?php echo $bg;?>" style="<?php if (trim($list[$i]['barcolor']) != '') echo 'background: ' . $list[$i]['barcolor']; ?>">
											<?php } ?>
											<?php if($list[$i]['content']) { ?>
												<p style="<?php echo $fsize2 . $fcolor2 ?>"><?php echo $list[$i]['content'];?></p>
											<?php } ?>
										<?php } ?>
									</div>
								</div>
							</div>
						<?php } ?>
					</li>
					<?php } ?>
					</ul>
				</div>
			</div>
		</div>

	<?php } 
 } else { 
	if($is_slider == 'slider') { // 슬라이더 타입일 경우 ?>

		<div class="full-zone">
			<ul class="bxslider title-basic en">
			<?php if($wset['cover']) { // 로고출력 ?>
				<li class="item" style="background-image : url('<?php echo $bg_img;?>');">
					<?php echo $raster;?>
					<div class="at-container">
						<div class="img-tbl">
							<div class="img-cell full-zone">
								<div class="title-content">
									<a href="<?php echo $at_href['home'];?>" class="title-logo hidden-xs hidden-sm">
										<img src="<?php echo ($wset['logo']) ? $wset['logo'] : THEMA_URL.'/logo.png';?>" alt="">
									</a>
									<div class="title-search">
										<form name="tsearch" method="get" action="<?php echo $at_href['search'];?>" onsubmit="return tsearch_submit(this);" role="form" class="form">
										<div class="input-group">
											<input type="text" name="stx" class="form-control" value="<?php echo $stxa;?>">
											<span class="input-group-btn">
												<button type="submit" class="btn btn-sm"><i class="fa fa-search fa-lg"></i></button>
											</span>
										</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</li>
			<?php } ?>
			<?php
			// 리스트
			for ($i=0; $i < $list_cnt; $i++) { 

				// 이미지
				$bg = substr($list[$i]['rank'], -1);
				$bgcolor = $bgimg = '';
				if($list[$i]['img']['src']) {
					$bgimg = ' style="background-image: url(\''.$list[$i]['img']['org'].'\');"';
				} else {
					$bgcolor = ' bg-'.$bg;
				}

				$fsize1  = trim($list[$i]['fsize1'])  != '' ? 'font-size: ' . $list[$i]['fsize1'] . 'px;'   : '';
				$fcolor1 = trim($list[$i]['fcolor1']) != '' ? 'color: ' . $list[$i]['fcolor1'] . ';' : '';
				$fsize2   = trim($list[$i]['fsize2']) != '' ? 'font-size: ' . $list[$i]['fsize2'] . 'px;'   : '';
				$fcolor2 = trim($list[$i]['fcolor2']) != '' ? 'color: ' . $list[$i]['fcolor2'] . ';' : '';
			?>
			<li class="item<?php echo $bgcolor;?>"<?php echo $bgimg;?>>
				<?php echo $raster;?>
				<div class="img-tbl">
					<div class="img-cell full-zone">
						<?php if(!$wset['text']) { // 내용출력 ?>
							<div class="title-content">
								<?php
									$video = apms_link_video($list[$i]['link'], 1);
									if($video) {
								?>
									<div class="title-video">
										<?php echo $video;?>
									</div>
								<?php } else { ?>
									<?php if($list[$i]['subject']) { ?>
										<a href="<?php echo $list[$i]['href'];?>"<?php echo $list[$i]['target'];?>>
											<h3 style="<?php echo $fsize1 . $fcolor1 ?>"><?php echo $list[$i]['subject'];?></h3>
										</a>
									<?php } ?>
									<?php if($list[$i]['subject'] && $list[$i]['content']) { ?>
										<hr class="bg-<?php echo $bg;?>" style="<?php if (trim($list[$i]['barcolor']) != '') echo 'background: ' . $list[$i]['barcolor']; ?>">
									<?php } ?>
									<?php if($list[$i]['content']) { ?>
										<p style="<?php echo $fsize2 . $fcolor2 ?>"><?php echo $list[$i]['content'];?></p>
									<?php } ?>
								<?php } ?>
							</div>
						<?php } ?>
					</div>
				</div>
			</li>
			<?php } ?>
			</ul>
		</div>

	<?php } else { ?>

		<?php echo $raster;?>
		<div class="at-container">
			<div class="img-tbl">
				<div class="img-cell full-zone">
					<ul class="bxslider title-basic en">
					<?php if($wset['cover']) { // 로고출력 ?>
						<li class="item">
							<div class="img-tbl">
								<div class="img-cell">
									<div class="title-content">
										<a href="<?php echo $at_href['home'];?>" class="title-logo hidden-xs hidden-sm">
											<img src="<?php echo ($wset['logo']) ? $wset['logo'] : THEMA_URL.'/logo.png';?>" alt="">
										</a>
										<div class="title-search">
											<form name="tsearch" method="get" action="<?php echo $at_href['search'];?>" onsubmit="return tsearch_submit(this);" role="form" class="form">
											<div class="input-group">
												<input type="text" name="stx" class="form-control" value="<?php echo $stxa;?>">
												<span class="input-group-btn">
													<button type="submit" class="btn btn-sm"><i class="fa fa-search fa-lg"></i></button>
												</span>
											</div>
											</form>
										</div>
									</div>
								</div>
							</div>
						</li>
					<?php } ?>
					<?php
					// 리스트
					for ($i=0; $i < $list_cnt; $i++) { 
						$bg = substr($list[$i]['rank'], -1);

						$fsize1  = trim($list[$i]['fsize1'])  != '' ? 'font-size: ' . $list[$i]['fsize1'] . ';'   : '';
						$fcolor1 = trim($list[$i]['fcolor1']) != '' ? 'color: ' . $list[$i]['fcolor1'] . ';' : '';
						$fsize2   = trim($list[$i]['fsize2']) != '' ? 'font-size: ' . $list[$i]['fsize2'] . ';'   : '';
						$fcolor2 = trim($list[$i]['fcolor2']) != '' ? 'color: ' . $list[$i]['fcolor2'] . ';' : '';
					?>
					<li class="item">
						<?php if(!$wset['text']) { // 내용출력 ?>
							<div class="img-tbl">
								<div class="img-cell">
									<div class="title-content">
										<?php
											$video = apms_link_video($list[$i]['link'], 1);
											if($video) {
										?>
											<div class="title-video">
												<?php echo $video;?>
											</div>
										<?php } else { ?>
											<?php if($list[$i]['subject']) { ?>
												<a href="<?php echo $list[$i]['href'];?>"<?php echo $list[$i]['target'];?>>
													<h3 style="<?php echo $fsize1 . $fcolor1 ?>"><?php echo $list[$i]['subject'];?></h3>
												</a>
											<?php } ?>
											<?php if($list[$i]['subject'] && $list[$i]['content']) { ?>
												<hr class="bg-<?php echo $bg;?>" style="<?php if (trim($list[$i]['barcolor']) != '') echo 'background: ' . $list[$i]['barcolor']; ?>">
											<?php } ?>
											<?php if($list[$i]['content']) { ?>
												<p style="<?php echo $fsize2 . $fcolor2 ?>"><?php echo $list[$i]['content'];?></p>
											<?php } ?>
										<?php } ?>
									</div>
								</div>
							</div>
						<?php } ?>
					</li>
					<?php } ?>
					</ul>
				</div>
			</div>
		</div>

	<?php } 
 } ?>