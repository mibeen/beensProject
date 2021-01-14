<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$page_skin_url.'/page.css">', 0);

// 탭설정
$page_tabs = array();
if(isset($bo_table) && $bo_table && $board['bo_category_list']) {
	$page_tabs = explode('|', $board['bo_category_list']);
}

$page_tabs_cnt = count($page_tabs);

// 헤더
if($header_skin)
	include_once('./header.php');
?>

<?php if($page_tabs_cnt) { 
	if(!$sca) $sca = $view['ca_name'];
?>
	<div class="page-tabs-box">
		<nav class="page-select visible-xs">
			<select name="sca" onchange="location='./board.php?bo_table=<?php echo $bo_table; ?>&sca=' + encodeURIComponent(this.value);" class="form-control input-sm">
				<?php for ($i=0; $i < $page_tabs_cnt; $i++) { ?>
					<option value="<?php echo $page_tabs[$i];?>"<?php if($page_tabs[$i] === $sca) echo ' selected';?>>
						<?php echo $page_tabs[$i];?>
					</option>
				<?php } ?>
			</select>
		</nav>
		<nav class="page-tabs hidden-xs">
			<div class="tabs">
				<ul class="nav nav-tabs en font-12">
					<?php for ($i=0; $i < $page_tabs_cnt; $i++) { ?>
						<li<?php if($page_tabs[$i] == $sca) echo ' class="active"';?>>
							<a href="<?php echo G5_BBS_URL;?>/board.php?bo_table=<?php echo $bo_table;?>&amp;sca=<?php echo urlencode($page_tabs[$i]);?>">
								<?php echo $page_tabs[$i];?>
							</a>
						</li>
					<?php } ?>
				</ul>
			</div>
		</nav>
	
		<h1>
			<?php echo ($view['as_icon']) ? apms_fa($view['as_icon']) : '';?>
			<?php echo get_text($view['wr_subject']); ?>
		</h1>

		<div class="clearfix"></div>
	</div>
	<div class="h15"></div>
<?php } ?>

<?php echo $page_content; ?>

<div class="page-icon">
	<?php 
		$sns_img = $page_skin_url.'/img';
		echo  get_sns_share_link('facebook', $sns_url, $sns_title, $sns_img.'/sns_fb.png').' ';
		echo  get_sns_share_link('twitter', $sns_url, $sns_title, $sns_img.'/sns_twt.png').' ';
		echo  get_sns_share_link('googleplus', $sns_url, $sns_title, $sns_img.'/sns_goo.png').' ';
		echo  get_sns_share_link('kakaostory', $sns_url, $sns_title, $sns_img.'/sns_kakaostory.png').' ';
		echo  get_sns_share_link('kakaotalk', $sns_url, $sns_title, $sns_img.'/sns_kakao.png', $img['src']).' ';
		echo  get_sns_share_link('naverband', $sns_url, $sns_title, $sns_img.'/sns_naverband.png').' ';
	?>
	<?php if($scrap_href) { ?>
		<a href="<?php echo $scrap_href;  ?>" target="_blank" onclick="win_scrap(this.href); return false;" title="스크랩">
			<img src="<?php echo $page_skin_url;?>/img/scrap.png" alt="스크랩">
		</a>
	<?php } ?>
</div>