<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
?>

<form name="fwrite" id="fwrite" method="post" autocomplete="off" role="form" class="form">
<input type="hidden" name="w" value="<?php echo $w ?>">
<input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
<input type="hidden" name="wr_id" value="<?php echo $wr_id ?>">
<input type="hidden" name="sca" value="<?php echo $sca ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="spt" value="<?php echo $spt ?>">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">

	<div style="max-width:400px; margin:0px auto;">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">Select Form</h4>
			</div>
			<div class="panel-body">
				<ul style="margin:0px; padding:0px; padding-left:20px; list-style:none;">
				<?php for ($k=0; $k<count($formlist); $k++) { ?>
					<li class="radio">
						<label>
							<input type="radio" name="page_form" value="<?php echo $formlist[$k];?>"<?php echo get_checked($page_form, $formlist[$k]);?>>
							<?php echo $formlist[$k];?>
						</label>
					</li>
				<?php } ?>
				</ul>
			</div>
		</div>
		
		<div class="text-center">
			<button type="submit" class="btn btn-color btn-sm"><i class="fa fa-check"></i> <b>확인</b></button>
			<a href="./board.php?bo_table=<?php echo $bo_table ?>" class="btn btn-black btn-sm" role="button">취소</a>
		</div>
	</div>
	
</form>