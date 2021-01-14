<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$qa_skin_url.'/style.css">', 0);
?>

<script src="<?php echo G5_JS_URL; ?>/viewimageresize.js"></script>

<div class="data_ct">
    <div class="coron_info_wrap">
        <div class="title_img_wrap">
            <div class="title_img">
                <div class="inner">
                    <!-- 이미지 썸네일 사이즈 704x396 -->
                    <?php echo $view['himg_str']?>
                </div>
            </div>
        </div>
        <div class="coron_info">
            <p class="region"><?php echo $view['PA_NAME']; ?></p>
            <p class="tit"><?php echo $view['PM_NAME']; ?></p>
            <div class="coron_opt">
                <dl>
                    <dt>담당자</dt>
                    <dd><?php echo $view['PM_CHARGE']; ?></dd>
                </dl>
                <dl>
                    <dt>대표전화</dt>
                    <dd><?php echo $view['PM_TEL']; ?></dd>
                </dl>
                <dl>
                    <dt>대표메일</dt>
                    <dd><?php echo $view['PM_EMAIL']; ?></dd>
                </dl>
                <dl>
                    <dt>주소</dt>
                    <dd><?php echo $view['PM_ADDRESS']; ?></dd>
                </dl>
            </div>
        </div>
    </div>   
    
    <p class="coron_read_tit">장소소개</p>
    <p>
        <?php echo $view['PM_INFO']; ?>
    </p>

    <!-- 강의실 리스트 -->
    <div class="coron_detail_lst mt30">
        <p class="p1">강의실</p>
        <ul class="lst">
            <?php
            for ($i=0; $i<count($row_sub_1); $i++) {
            ?>
            <li>
                <?php echo $row_sub_1[$i]['himg_str']; ?>
                <p class="tit"><?php echo $row_sub_1[$i]['PS_NAME']; ?></p>
                <div class="btn_wrap">
                    <a href="<?php echo $row_sub_1[$i]['view_href']; ?>" onclick='window.open(this.href, "", "left=10,top=10,width=500,height=400"); return false;' class="detail">상세보기</a>
                    <!--a href="<?php echo $row_sub_1[$i]['view_href']; ?>"  class="detail">상세보기</a-->
                    <a href="<?php echo $row_sub_1[$i]['req_href']; ?>" class="reserve">예약하기</a>
                </div>
            </li>
            <?php } ?>
            <?php
            if ($i == 0) {
                echo "<li>게시물이 없습니다.</li>";
            }
            ?>
        </ul>

        <p class="p1 mt30">숙소</p>
        <ul class="lst">
            <?php
            for ($i=0; $i<count($row_sub_2); $i++) {
            ?>
            <li>
                <?php echo $row_sub_2[$i]['himg_str']; ?>
                <p class="tit"><?php echo $row_sub_2[$i]['PS_NAME']; ?></p>
                <div class="btn_wrap">
                    <a href="<?php echo $row_sub_2[$i]['view_href']; ?>" onclick='window.open(this.href, "", "left=10,top=10,width=500,height=400"); return false;' class="detail">상세보기</a>
                    <a href="<?php echo $row_sub_2[$i]['req_href']; ?>" class="reserve">예약하기</a>
                </div>
            </li>
            <?php } ?>
            <?php
            if ($i == 0) {
                echo "<li>게시물이 없습니다.</li>";
            }
            ?>
        </ul>
    </div>

    <!-- 사진 -->
    <p class="coron_read_tit mt30">사진</p>
    <ul class="coron_gall">
        <?php
        for ($i=0; $i<count($place_image_list); $i++) {
        ?>
        <li>
            <a href="javascript:void(0)">
                <div class="img_wrap1">
                    <div class="img_wrap2">
                        <?php echo $place_image_list[$i]['himg_str']; ?>
                    </div>
                </div>
            </a>
        </li>

        <?php } ?>
        <?php
        if ($i == 0) {
            echo "<p>게시물이 없습니다.</p>";
        }
        ?>
    </ul>
    
        
    <!-- 위치 -->
    <div class="place_map_wrap">
        <h4>위치</h4>
    </div>

    <br>
    <br>
    
</div>

<a href="<?php echo $list_href?>">뒤로</a>

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