<?php
?>
<ul class="learn_lst1">
	<?php 
	if (Count($response_json_obj[result]) == 0) {
		?>
		<li class="nodata">찜한 학습이 없습니다.</li>
		<?php
	}
	else {
		foreach ($response_json_obj[result] as $key => $value) {
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
						<div class="nx_ofH mt10">
							<p class="name nx_fL mt0"><?php echo $value[study_lecturer]; ?>&nbsp;</p>
							<a onclick="winPopup('<?php echo G5_URL; ?>/gseek/nx_learn_state.php?REQUEST_API_KEY=<?php echo GSK_client_id; ?>&REQUEST_API_CODE=learn_read&ck_I_CODE=<?php echo GSK_I_CODE; ?>&SG_CODE=<?php echo $value[SG_CODE]; ?>&REQUEST_DATA=study_basket_del&GO_URL=http://xopenapi.gseek.4csoft.com/openapi/request/content_api.gm');" class="cancel nx_fR"><span class="ico_heart"></span> 찜 취소</a>
						</div>
						<div class="star_rate_wrap">
							<div class="star_rate">
								<?php echo(showStarIco($value[study_star])); ?>
							</div>
							<?php /*<span class="cnt">(<?php //echo $value[study_star_cnt]; ?>)</span> */ ?>
							<span class="cnt">(<?php echo(number_format($value[study_star], 2))?>)</span>
						</div>
						<a onclick="winPopup('<?php echo G5_URL; ?>/gseek/nx_learn_state.php?REQUEST_API_KEY=<?php echo GSK_client_id; ?>&REQUEST_API_CODE=learn_read&ck_I_CODE=<?php echo GSK_I_CODE; ?>&SG_CODE=<?php echo $value[SG_CODE]; ?>&REQUEST_DATA=study_lectures_apply&GO_URL=http://xopenapi.gseek.4csoft.com/openapi/request/content_api.gm');" class="nx_btn">수강 신청하기</a>
					</div>
				</div>
			</li>
			<?php
		}
	} 
	?>
</ul>