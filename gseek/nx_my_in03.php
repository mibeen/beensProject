<?php
?>
<ul class="learn_lst1">
	<?php 
	if (Count($response_json_obj[result]) == 0) {
		?>
		<li class="nodata">완료한 학습이 없습니다.</li>
		<?php
	}
	else {
		foreach ($response_json_obj[result] as $key => $value) {
			$result_ary = GetListItemView($value[SG_CODE]);
			?>
			<li>
				<div class="inner">
					<div class="img_wrap1">
						<div class="img_wrap2">
							<img src="<?php echo $value[main_img]?>" onerror="this.onerror=null;this.src='<?php echo $value[main_eimg]?>';">
						</div>
					</div>
					<div class="nx_txt_wrap">
						<p class="nx_tit"><a href="nx_learn_view.php?SG_CODE=<?php echo $value[SG_CODE]; ?>" target="_blank"><?php echo $value[title]; ?></a></p>
						<p class="name"><?php echo $value[study_lecturer]; ?>&nbsp;</p>
						<?php
						// 수료증 출력이 가능한 경우
						if($result_ary[study_can_certificate]=='Y' && $result_ary[buttion_certificate_name] != "") {
							?>
							<a href="<?php echo $result_ary[buttion_certificate_link]; ?>" target="_blank" class="nx_btn">수료증 보기</a>
							<?php
						}
						?>
					</div>
				</div>
			</li>
			<?php
		}
	} 
	?>
</ul>