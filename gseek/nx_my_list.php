<?php
include_once("../common.php");

include_once(G5_LIB_PATH.'/apms.thema.lib.php');

// 설정값 불러오기
// $is_faq_sub = false;
// @include_once($faq_skin_path.'/config.skin.php');

$g5['title'] = $fm['fm_subject'];


include_once('../bbs/_head.php');



$tab_no = $_REQUEST['tab'];
switch($tab_no)
{
	case "1":
		$pageTitle = "나의 학습";
		$response_json_obj = GetMyList();
		$classActive1 = " class='on'";
		$inPage = "nx_my_in01.php";
	break;
	case "2":
		$pageTitle = "찜한 학습";
		$response_json_obj = GetMyZzimList();
		$classActive2 = " class='on'";
		$inPage = "nx_my_in02.php";
	break;
	case "3":
		$pageTitle = "완료한 학습";
		$response_json_obj = GetMyEndList();
		$classActive3 = " class='on'";
		$inPage = "nx_my_in03.php";
	break;
	case "4":
		$pageTitle = "내가 쓴 톡톡";
		$response_json_obj = GetMyEndList();
		$classActive4 = " class='on'";
		$inPage = "nx_my_in04.php";
	break;
	default:
		$pageTitle = "나의 학습";
		$response_json_obj = GetMyList();
		$classActive1 = " class='on'";
		$inPage = "nx_my_in01.php";
	break;
}

$PAGE = 1;
$PAGE = $_REQUEST[PAGE];
if(!$PAGE) $PAGE = 1;

?>
<p class="nx_page_tit">
<?php echo $pageTitle; ?>
</p>

<div class="page-wrap">
	<div class="data_ct">

		<?php if(!$_SESSION['ID']){ ?>
				<a onclick="win_open();">
				<p class="sch_result" style="margin-bottom:25px; padding:30px 0px 30px 0px;">
					로그인 후 이용 가능합니다.
				</p>
				</a>
		<?php } ?>

		<ul class="tab1">
			<li<?php echo $classActive1; ?>><a href="?tab=1">수강 중 학습</a></li>
			<li<?php echo $classActive2; ?>><a href="?tab=2">찜한 학습</a></li>
			<li<?php echo $classActive3; ?>><a href="?tab=3">완료한 학습</a></li>
			<li<?php echo $classActive4; ?>><a href="?tab=4">내가 쓴 톡톡</a></li>
		</ul>
		<?php include($inPage ); ?>
	</div>

	<iframe id="iframe1" frameborder="0" style="width:100%; height:0px;"></iframe>

	<script>
	function SetIFrame(url, obj, t, pkey, SG_CODE)
	{
	url = url +"?SG_CODE="+((SG_CODE != '') ? SG_CODE : '<?php echo ($_REQUEST[SG_CODE])?>');
		
		switch(t)
		{
			case "talk":
				url = url +"&SC_CONT="+ $(obj).val()+"&t="+t;
			break;

			case "talkReRe":
				url = url +"&SC_CONT="+ $(obj).val()+"&t="+t+"&SC_PARENT_IDX="+pkey;
			break;

			case "sugang":
				var st = $("input:radio[name=rdo_star]:checked").val();
				if(!st)st = "0";
				url = url +"&SM_CONT="+ $(obj).val()+"&SM_STAR="+st+"&t="+t;
			break;
		}

		$('#iframe1').attr('src', url);
	}

	function SetTalkDel(url, vkey, t, SG_CODE)
	{
		url = url +"?SG_CODE="+((SG_CODE != '') ? SG_CODE : '<?php echo ($_REQUEST[SG_CODE])?>');

		switch(t)
		{
			case "talk":
				url = url +"&SC_IDX="+vkey+"&t="+t;
			break;

			case "sugang":
				url = url +"&SM_IDX="+vkey+"&t="+t;
			break;
		}

		$('#iframe1').attr('src', url);
	}

	function winPopup(url)
	{
		window.open(url, "_blank", 'width=500,height=600');
	}
	</script>

</div>
<?php 
	# 메뉴 표시에 사용할 변수 
	$_gr_id = 'nx05';  // 게시판 그룹 ID - 현재 메뉴표시에 사용
	$pid = ($pid) ? $pid : 'myedu';   // Page ID

	include "../page/inc.page.menu.php";

	include_once('../bbs/_tail.php');
?>