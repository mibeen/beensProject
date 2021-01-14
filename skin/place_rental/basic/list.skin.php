<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// 선택옵션으로 인해 셀합치기가 가변적으로 변함
$colspan = 6;

if ($is_checkbox) $colspan++;

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$qa_skin_url.'/style.css">', 0);
?>
    
    <?php /* ?>
     <!-- 게시판 페이지 정보 및 버튼 시작 { -->
    <div class="bo_fx">
        <?php if ($admin_href || $write_href) { ?>
        <ul class="btn_bo_user">
            <?php if ($admin_href) { ?><li><a href="<?php echo $admin_href ?>" class="btn_admin">관리자</a></li><?php } ?>
        </ul>
        <?php } ?>
    </div>
    <!-- } 게시판 페이지 정보 및 버튼 끝 -->

    <h2>대관 현황</h2>
    
    <!-- 게시판 검색 시작 { -->
    <fieldset id="bo_sch" class="place_sch">
        <legend>게시물 검색</legend>

        <form name="fsearch" id="fsearch" method="get" action="./place_rental_list.php">
            <input type="hidden" name="pm_id" value="<?php echo $pm_id?>">
            <input type="hidden" name="ps_id" value="<?php echo $ps_id?>">
            <input type="hidden" name="sal" id="sal" value="<?php echo $sal?>">
            <input type="hidden" name="sco" id="sco" value="<?php echo $sco?>">
            <label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
            <div class="fsearch_left_wrap">
                <span>지역 </span>
                <select name="sar" id="sar" onchange="document.getElementById('fsearch').submit()">
                    <option value="">전체</option>
                    <?php
                    for ($i = 0; $i < $j_area; $i++) { 
                    ?>
                    <option value="<?php echo $area_result[$i]['PA_IDX']?>" <?php if($area_result[$i]['PA_IDX'] == $sar) { echo(' selected'); }?>><?php echo $area_result[$i]['PA_NAME']?></option>
                    <?php } ?>
                </select>
                <input type="text" name="stx" value="<?php echo stripslashes($stx) ?>" id="stx" class="frm_input" size="15" maxlength="15">
                <input type="submit" value="검색" class="btn_submit">
            </div>
            <div class="sal_sco_wrap">
                <div class="sal_wrap">
                    <span>배열</span>
                    <a <?php if($sal == '1') { echo('class="active"'); } ?> href="javascript:void(0)" onclick="document.getElementById('sal').value = 1; document.getElementById('fsearch').submit();">배열 1</a>
                    <a <?php if($sal == '2') { echo('class="active"'); } ?> href="javascript:void(0)" onclick="document.getElementById('sal').value = 2; document.getElementById('fsearch').submit();">배열 2</a>
                </div>
                &nbsp;
                <div class="sco_wrap">
                    <span>개수</span>
                    <a <?php if($sco == '1') { echo('class="active"'); } ?> href="javascript:void(0)" onclick="document.getElementById('sco').value = 1; document.getElementById('fsearch').submit();">12개</a>
                    <a <?php if($sco == '2') { echo('class="active"'); } ?> href="javascript:void(0)" onclick="document.getElementById('sco').value = 2; document.getElementById('fsearch').submit();">24개</a>
                    <a <?php if($sco == '3') { echo('class="active"'); } ?> href="javascript:void(0)" onclick="document.getElementById('sco').value = 3; document.getElementById('fsearch').submit();">48개</a>
                </div>
            </div>
        </form>
    </fieldset>
    <!-- } 게시판 검색 끝 -->
    */ ?>

<div class="data_ct">
    <form name="fsearch" id="fsearch" method="get" action="./place_rental_list.php">
        <input type="hidden" name="sar" id="sar" value="<?php echo $sar?>">
        <ul class="tab1">
            <?php
            for ($i = 0; $i < $j_area; $i++) { 
            ?>
            <li <?php if($area_result[$i]['PA_IDX'] == $sar) { echo(' class="on"'); }?>><a href="javascript:void(0);" onclick="document.getElementById('sar').value = '<?php echo $area_result[$i]['PA_IDX']?>'; document.getElementById('fsearch').submit();"><?php echo $area_result[$i]['PA_NAME']?></a></li>
            <?php } ?>
        </ul>
    </form>
    <?php if($total_count == '1') { ?>
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

    <?php } else { ?>
    <ul class="coron_lst">
        <?php
        for ($i=0; $i<count($list); $i++) {
        ?>
        <li>
            <a href="<?php echo $list[$i]['view_href']; ?>">
                <div class="img_wrap1">
                    <div class="img_wrap2">
                        <?php echo($list[$i]['himg_str']); ?>
                    </div>
                </div>
                <p class="region"><?php echo $list[$i]['PA_NAME']; ?></p>
                <p class="tit"><?php echo $list[$i]['PM_NAME']; ?></p>
                <p class="info"><?php echo $list[$i]['REQ_INFO']; ?></p>
            </a>
        </li>
        <?php } ?>
        <?php
        if($i == 0) { ?>
            <li class="empty_list"><p>게시물이 없습니다.</p></li>
        <?php } ?>  
    </ul>
    
    <?php } ?>
</div>

<?php if($is_checkbox) { ?>
<noscript>
<p>자바스크립트를 사용하지 않는 경우<br>별도의 확인 절차 없이 바로 선택삭제 처리하므로 주의하시기 바랍니다.</p>
</noscript>
<?php } ?>

<!-- 페이지 -->
<?php echo $list_pages;  ?>

<?php if ($is_checkbox) { ?>
<script>
function all_checked(sw) {
    var f = document.fqalist;

    for (var i=0; i<f.length; i++) {
        if (f.elements[i].name == "chk_qa_id[]")
            f.elements[i].checked = sw;
    }
}

function fqalist_submit(f) {
    var chk_count = 0;

    for (var i=0; i<f.length; i++) {
        if (f.elements[i].name == "chk_qa_id[]" && f.elements[i].checked)
            chk_count++;
    }

    if (!chk_count) {
        alert(document.pressed + "할 게시물을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "선택삭제") {
        if (!confirm("선택한 게시물을 정말 삭제하시겠습니까?\n\n한번 삭제한 자료는 복구할 수 없습니다"))
            return false;
    }

    return true;
}
</script>
<?php } ?>
<!-- } 게시판 목록 끝 -->