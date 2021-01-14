<?php
?>
<ul class="learn_lst1">
	<?php 
	if (Count($response_json_obj[result]) == 0) {
		?>
		<li class="nodata">수강 중인 학습이 없습니다.</li>
		<?php
	}
	else {
		foreach ($response_json_obj[result] as $key => $value) {
			?>
			<li>
				<a href="nx_learn_view.php?SG_CODE=<?php echo $value[SG_CODE]; ?>">
					<div class="img_wrap1">
						<div class="img_wrap2">
							<img src="<?php echo $value[main_img]?>" onerror="this.onerror=null;this.src='<?php echo $value[main_eimg]?>';">
						</div>
					</div>
					<div class="nx_txt_wrap">
						<p class="nx_tit">
							<?php echo $value[title]; ?>
						</p>
						<p class="name">
							<?php echo $value[study_lecturer]; ?>&nbsp;
						</p>
						<div class="nx_progress">
							<span class="txt">진도율</span>
							<div class="bar">
								<div class="gage" style="width: <?php echo $value[study_progress]; ?>%;">
								</div>
							</div>
							<span class="percent"><?php echo $value[study_progress]; ?>%</span>
						</div>
					</div>
				</a>
			</li>
			<?php
		}
	} 
	?>
</ul>