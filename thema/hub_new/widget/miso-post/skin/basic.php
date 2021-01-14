<?php
if (!defined('_GNUBOARD_')) exit; //개별 페이지 접근 불가

// 썸네일 없슴
$wset['thumb_w'] = $wset['thumb_h'] = '';

// 추출
$list = miso_widget_list($wset, $widget_path, $widget_url);
$list_cnt = count($list);

if(!$list_cnt && $is_ajax) return;

?>

<?php if(!$is_ajax) { ?>
<ul class="post post-list post-margin">
<?php } ?>
<li class="item">
<?php
// 리스트
for ($i=0; $i < $list_cnt; $i++) { 
?>
	<?php if($i > 0 && $i%$is_sero == 0) { ?>
		</li>
		<li class="item">
	<?php } ?>

		<p class="title"><a href="<?php echo $list[$i]['href'];?>" <?php echo $target;?>><?php echo $list[$i]['subject'];?></a></p>
		<p class="date"><a href="<?php echo $list[$i]['href'];?>" <?php echo $target;?>>
			<?php echo apms_date($list[$i]['date'], 'orangered', 'H:i', 'm.d', 'm.d'); ?>
		</a></p>

		<?php /*
		<a href="<?php echo $list[$i]['href'];?>"<?php echo $list[$i]['target'];?> class="item-box ellipsis">
			<span class="pull-right">
				<?php if($list[$i]['comment']) { ?>
					<span class="count">
						+<?php echo $list[$i]['comment'];?>
					</span>
				<?php } ?>
				&nbsp;
				<span class="font-12">
					<?php echo apms_date($list[$i]['date'], 'orangered', 'H:i', 'm.d', 'm.d'); ?>
				</span>
			</span>
			<?php echo $list[$i]['icon'];?>
			<?php echo $list[$i]['bullet'];?>
			<?php echo $list[$i]['subject'];?>
		</a> 
		*/ ?>
<?php } ?>
<?php if(!$list_cnt) { ?>
	<a href="#nopost" class="item-box ellipsis text-muted">
		게시물이 없습니다.
	</a>
<?php } ?>
</li>
<?php if(!$is_ajax) { ?>
</ul>
<?php } ?>



<style>
	#<?php echo($widget_id); ?> .title a {
		display: block;
		width: 100%;
		overflow: hidden;
		-ms-text-overflow: ellipsis;
		text-overflow: ellipsis;
		white-space: nowrap;
	}
	/* custom Hoyeongkim */
	#<?php echo($widget_id); ?> ul{
	    margin-bottom: 0;
	    padding: 0;
	    list-style: none;
	}
	#<?php echo($widget_id); ?> li{
		padding: 10px 0;
	    font-size: 13px;
	    border-bottom: 1px solid #EDEDED;
	}
	#<?php echo($widget_id); ?> a{
	    display: block;
	}
	#<?php echo($widget_id); ?> li > p {
		float: left;
	}
	#<?php echo($widget_id); ?> li:after {
		content:'';clear: both;display: block;
	}
	#<?php echo($widget_id); ?> li .title:last-child {
	    float: none;
	}
	#<?php echo($widget_id); ?> li .title:nth-last-child(n+2) {
	     width: calc(100% - 50px);   
	}
	#<?php echo($widget_id); ?> li .title a{
	    display: block; width: 100%;
	    overflow: hidden; -ms-text-overflow: ellipsis;
	    text-overflow: ellipsis;
	    white-space: nowrap;
	}
	#<?php echo($widget_id); ?> li .date {
		width: 50px;
	    text-align: right;
	    color: #999;
	}
	#<?php echo($widget_id); ?> li .date a{
	    color: inherit;
	}
	#<?php echo($widget_id); ?> li:first-child {
	    border-top: none
	}
	#<?php echo($widget_id); ?> .post-list li:last-child {
	    padding-bottom: 0
	}
	#<?php echo($widget_id); ?> .post-list li b {
	    letter-spacing: -1px;
	    padding-right: 1px
	}
	#<?php echo($widget_id); ?> .post-list li .name {
	    letter-spacing: -1px;
	    color: #888;
	    padding-left: 4px;
	    padding-right: 1px
	}
	#<?php echo($widget_id); ?> .post-list .txt-normal {
	    letter-spacing: 0
	}
	#<?php echo($widget_id); ?> .post-none {
	    padding: 50px 10px;
	    text-align: center;
	    color: #888
	}
	#<?php echo($widget_id); ?> .wr-text {
	    font-family: dotum;
	    font-size: 11px;
	    letter-spacing: -1px;
	    line-height: 11px;
	    font-weight: normal
	}
	#<?php echo($widget_id); ?> .wr-icon {
	    display: inline-block;
	    padding: 0px;
	    margin: 0px;
	    line-height: 12px;
	    vertical-align: middle;
	    background-repeat: no-repeat;
	    background-position: 0px 0px
	}
	#<?php echo($widget_id); ?> .wr-new {
	    width: 12px;
	    height: 12px;
	    background-image: url("./img/icon_new.gif")
	}
	#<?php echo($widget_id); ?> .wr-secret {
	    width: 12px;
	    height: 12px;
	    background-image: url("./img/icon_secret.gif")
	}
	#<?php echo($widget_id); ?> .wr-video {
	    width: 12px;
	    height: 12px;
	    background-image: url("./img/icon_video.gif")
	}
	#<?php echo($widget_id); ?> .wr-image {
	    width: 12px;
	    height: 12px;
	    background-image: url("./img/icon_image.gif")
	}
	#<?php echo($widget_id); ?> .wr-file {
	    width: 12px;
	    height: 12px;
	    background-image: url("./img/icon_file.gif")
	}
	#<?php echo($widget_id); ?> .nx_post_title {
	    margin: 5px 0 0;
	    font-size: 16px;
	    color: #222
	}
</style>