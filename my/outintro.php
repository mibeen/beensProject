<?php
include_once("../common.php");
include_once(G5_LIB_PATH.'/nx.lib.php');
include_once(G5_LIB_PATH.'/thumbnail.lib.php'); //썸네일 라이브러리 로딩


# 로그인 chk
if ($member['mb_id'] == '') {
    header("/");
    exit();
}
                                                              
include_once('../bbs/_head.php');

?>
<p class="nx_page_tit">탈퇴하기</p>

<iframe id="iframe0" onLoad="calcHeight('0');" src="https://www.gillapi.or.kr/memb/out.intro" scrolling="no" frameborder="0" width="100%" height="1000px" ></iframe>

<script>
		
function calcHeight(num) {
	
    //find the height of the internal page
    var the_height=
    document.getElementById("iframe"+num).contentWindow.
    document.body.scrollHeight;

    alert("the_height = "+ the_height);
    
    //change the height of the iframe
    document.getElementById("iframe"+num).height=
    the_height;
}

</script>

<?php 
	# 메뉴 표시에 사용할 변수 
	$_gr_id = 'nx05';  // 게시판 그룹 ID - 현재 메뉴표시에 사용
	$pid = ($pid) ? $pid : 'myudong';   // Page ID

	// include "../page/inc.page.menu.php";	 // 마이페이지에선 사용안함

	include_once('../bbs/_tail.php');
?>

