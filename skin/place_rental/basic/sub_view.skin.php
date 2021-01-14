<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$qa_skin_url.'/style.css">', 0);
?>

<script src="<?php echo G5_JS_URL; ?>/viewimageresize.js"></script>


<div class="coron_detail">
    <?php /*
    <p class="wrap_title"><?php echo $view['PA_GUBUN_TITLE']?> 소개<a href="javascript:void(0)" onclick="window.close();">닫기</a></p>
    <br>
    */ ?>
    <p class="region"><?php echo $view['PA_NAME'] . " " . $view['PM_NAME']; ?></p>
    <p class="tit"><?php echo $view['PS_NAME']; ?></p>
    <div class="ct">
        <?php echo $view['PS_INFO']; ?>
    </div>
    <p class="coron_read_tit mt30">사진</p>
    <ul class="coron_gall">
        <?php
        for ($i=0; $i<count($place_image_list); $i++) {
        ?>
        <li>
            <a href="#">
                <div class="img_wrap1">
                    <?php echo $place_image_list[$i]['himg_str']; ?>
                </div>
            </a>
        </li>
        <?php } ?>
    </ul>
</div>

<script>
$(function() {
    $("a.view_image").click(function() {
        window.open(this.href, "large_image", "location=yes,links=no,toolbar=no,top=10,left=10,width=10,height=10,resizable=yes,scrollbars=no,status=no");
        return false;
    });

    // 이미지 리사이즈
    $("#bo_v_atc").viewimageresize();
});
</script>