<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

?>
<style>
.nx_quick_menu {
	position: absolute;
	top: 20px;
	left: calc(100% + 15px);
	z-index: 100;
	width: 120px;
}
.nx_quick_menu .nx_tit_wrap {
	display: table;
	width: 100%;
	height: 62px;
	background: #ffd839;
	border-radius: 6px 6px 0 0;
}
.nx_quick_menu .nx_tit_wrap .nx_tit {
	display: table-cell;
	text-align: center;
	vertical-align: middle;
	font-size: 16px;
	color: #fff;
}
.nx_quick_menu .nx_ct_wrap {
	overflow: hidden;
	padding-top: 15px;
	border: 1px solid #e5e5e5;
	border-top: none;
	text-align: center;
	background: #fff;
	border-radius: 0 0 6px 6px;
}
.nx_quick_menu .nx_ct_wrap .nx_lecture {
	padding: 0 10px 10px;
	border-bottom: 1px solid #e5e5e5;
}
.nx_quick_menu .nx_ct_wrap .nx_lecture .nx_tit {
	margin-bottom: 12px;
	font-size: 16px;
}
.nx_quick_menu .nx_ct_wrap .nx_lecture .lst {
	padding: 0;
	list-style: none;
}
.nx_quick_menu .nx_ct_wrap .nx_lecture .lst li {
	margin-top: 12px;
}
.nx_quick_menu .nx_ct_wrap .nx_lecture .lst li:first-child {
	margin-top: 0;
}
.nx_quick_menu .nx_ct_wrap .nx_lecture .lst li a {
	display: block;
}
.nx_quick_menu .nx_ct_wrap .nx_lecture .lst li a:hover .txt {
	text-decoration: underline;
}
.nx_quick_menu .nx_ct_wrap .nx_lecture .lst li a img {
	display: block;
}
.nx_quick_menu .nx_ct_wrap .nx_lecture .lst li a .txt {
	margin-top: 5px;
	text-align: left;
	overflow: hidden;
	text-overflow: ellipsis;
	display: -webkit-box;
	-webkit-line-clamp: 2;
	-webkit-box-orient: vertical;
	word-wrap: break-word;
	line-height: 1.3em;
	max-height: 2.6em !important;
	word-break: break-all;
}
.nx_quick_menu .nx_ct_wrap .nx_link {
	display: block;
	margin: 15px 0;
	padding: 0 10px;
}
.nx_quick_menu .nx_ct_wrap .nx_link:hover .txt {
	text-decoration: underline;
}
.nx_quick_menu .nx_ct_wrap .nx_link img {
	display: block;
	margin: 0 auto;
}
.nx_quick_menu .nx_ct_wrap .nx_link .txt {
	line-height: 1.3;
	font-size: 12px;
}
.nx_quick_menu .nx_ct_wrap .nx_btn_top {
	display: block;
	height: 30px;
	margin-top: 15px;
	line-height: 30px;
	text-align: center;
	background: #ffd839;
	transition: background 0.25s ease-in-out;
	color: #a36813;
}
.nx_quick_menu .nx_ct_wrap .nx_btn_top:hover {
	background: #ffce06;
}

</style>
<script>
$(document).ready(function(){
	var obj = $.cookie('learn');

// var jsonInfo = JSON.stringify(obj);
// var jsonobj = JSON.parse(jsonInfo);
	var addTag = "";
	var json_obj = $.parseJSON(obj);
	var f = 0;
	console.log(json_obj);
	for (var i in json_obj) 
	{
		if(json_obj[i].title != "" && json_obj[i].img != "" && json_obj[i].link != "")
		{
			addTag = addTag + "<li>";
			addTag = addTag + "<a href='"+ json_obj[i].link +"'>";
			addTag = addTag + "<img src='"+ json_obj[i].img +"'>";
			console.log(json_obj[i].title);
			addTag = addTag + "<p class='txt'>"+ json_obj[i].title.substring(0,8) +"</p></a></li>";

			f++;

			if(f == 5)
				break;
		}
		
	}
	$("#learnDiv").html(addTag);
});
</script>
		<div class="nx_quick_menu">
			<div class="nx_tit_wrap">
				<div class="nx_tit">QUICK<br>MENU</div>
			</div>
			<div class="nx_ct_wrap">
				<div class="nx_lecture">
					<p class="nx_tit">최근 본 학습</p>
					<ul class="lst" id="learnDiv">
						
					</ul>
				</div>
				<a href="https://gseek.kr" class="nx_link" target="_blank">
					<img src="<?php echo(THEMA_URL)?>/assets/img/gseek_logo.png" alt="" width="86" title="">
					<p class="txt mt10">지식 홈페이지 바로가기</p>
				</a>
				<a href="" class="nx_btn_top">▲ TOP</a>
			</div>
		</div>