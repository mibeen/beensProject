<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
?>
<div class="nx-page"><!--
	<p class="nx-slogan1">
		<span class="s1">“즐기는 평생교육으로 <span class="point"><em class="dot">더</em> 잘 살고, <em class="dot">더</em> 품격있고, <em class="dot">더</em> 행복한</span> 경기도민”</span>
		을 만들기 위해 노력하는 기관
	</p>-->

	<ul class="nx-tab1 ethics ">
		<li><a href="/bbs/board.php?bo_table=ethical&wr_id=1">윤리경영소개</a></li>
		<li><a href="/bbs/board.php?bo_table=ethical&wr_id=2">윤리헌장 · 행동강령</a></li>
		<li><a href="/bbs/board.php?bo_table=ethical&wr_id=3">윤리경영 비전체계</a></li>
		<li<?php if($bo_table == 'ethical_ceo'){echo(' class="on"');}?>><a href="/bbs/board.php?bo_table=ethical_ceo">CEO 의지표명</a></li>
		<li<?php if($bo_table == 'ethical_edu'){echo(' class="on"');}?>><a href="/bbs/board.php?bo_table=ethical_edu">직원 윤리경영 교육 프로그램</a></li>
		<li><a href="/bbs/board.php?bo_table=ethical&wr_id=4">윤리경영 전담조직</a></li>
	</ul>
</div>