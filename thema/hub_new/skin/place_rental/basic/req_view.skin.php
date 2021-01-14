<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$qa_skin_url.'/style.css">', 0);

?>
<div>
    <form id="req_form" name="req_form" action="./place_rental_req_write_delete.php" method="post" onsubmit="return false">
    <input type="submit" style="width:1px;height:1px;position:absolute;top:-10000px;left:-10000px" />

    <input type="hidden" name="pm_id" value="<?php echo $pm_id?>" />
    <input type="hidden" name="ps_id" value="<?php echo $ps_id?>" />
    <input type="hidden" name="pr_id" value="<?php echo $pr_id?>" />
    <input type="hidden" name="year" value="<?php echo $year?>" />
    <input type="hidden" name="month" value="<?php echo $month?>" />
    <input type="hidden" name="day" value="<?php echo $day?>" />
    <input type="hidden" name="gubun" value="<?php echo $req_view['PS_GUBUN']?>" />
    <input type="hidden" name="w" value="<?php if ($pr_id != '') echo('u')?>" />

    <div id="title-outer" class="event_apply_top" style="display:none">
        <div class="top_tit">강의실 신청정보</div>
        <a href="javascript:void(0);" onclick="req.close();" class="cls"><span class="ico_x"></span></a>
    </div>

    <div id="cont-outer" class="coron_apply">
        <p class="page_tit">입실정보</p>
        <p class="tit"><span class="region"><?php echo htmlspecialchars($req_view['PA_NAME'])?></span> <span class="name"><?php echo htmlspecialchars($req_view['PS_NAME'])?></span></p>
        <table cellspacing="0" cellpadding="0" border="0" class="coron_ts1 br_t br_b mt20">
            <colgroup>
                <col width="100px" /><col width="" />
            </colgroup>
            <tr>
                <th>입실일</th>
                <td style="height:40px"><?php
                    echo date('Y-m-d', strtotime($req_view['PR_SDATE']));
                ?></td>
            </tr>
            
            <?php
            # 강의실
            if($req_view['PS_GUBUN'] == 'A') {
                ?>
            <tr>
                <th>시간</th>
                <td style="height:40px"><?php
                    echo date('H:i', strtotime($req_view['PR_SDATE'])) .' ~ '. date('H:i', strtotime($req_view['PR_EDATE']));
                ?></td>
            </tr>
                <?php
            }
            # 숙소
            else if($req_view['PS_GUBUN'] == 'B') {
                ?>
            <tr>
                <th>숙박기간</th>
                <td style="height:40px"><?php
                    $_sdate = strtotime($req_view['PR_SDATE']);
                    $_edate = strtotime($req_view['PR_EDATE']);
                    echo (($_edate - $_sdate) / 86400).'일';
                ?></td>
            </tr>
            <tr style="height:40px">
                <th>퇴실일</th>
                <td style="height:40px"><?php
                    echo date('Y-m-d', strtotime($req_view['PR_EDATE']));
                ?></td>
            </tr>
                <?php
            }
            ?>

            <tr>
                <th>인원</th>
                <td style="height:40px"><?php echo number_format($req_view['PR_P_CNT']);?> 명</td>
            </tr>
        </table>

        <p class="page_tit mt30">고객정보</p>
        <table cellspacing="0" cellpadding="0" border="0" class="coron_ts1">
            <colgroup>
                <col width="100px" /><col width="" />
            </colgroup>
            <tr>
                <th>이름</th>
                <td style="height:40px"><?php echo $req_view['mb_nick']?></td>
            </tr>
            <tr>
                <th>휴대폰</th>
                <td style="height:40px"><?php echo $req_view['PR_TEL']?></td>
            </tr>
            <tr>
                <th>추가요구사항</th>
                <td><textarea id="cont" name="cont" class="nx_ips5" disabled><?php echo htmlspecialchars($req_view['PR_CONT'])?></textarea></td>
            </tr>
        </table>

        <div class="taC mt20">
            <?php
            # 신청/보류 + 입실일 이전에만 '취소 가능'
            if ( in_array($req_view['PR_STATUS'], array('A','C')) && (strtotime(substr($req_view['PR_SDATE'], 0, 10)) >= strtotime(date('Y-m-d'))) ) {
                echo '<input type="button" class="cancel_req_btn nx_btn7" onclick="req.close()" value="닫기"> ';
                echo '<input type="button" class="add_req_btn nx_btn5" onclick="req.edit()" value="수정하기"> ';
                echo '<input type="button" class="cancel_req_btn nx_btn8" onclick="req.cancel()" value="취소하기">';
            }
            else {
                echo '<input type="button" class="cancel_req_btn nx_btn7" onclick="req.close()" value="닫기">';
            }
            ?>
        </div>
    </div>

    </form>
</div>

<script>
//<![CDATA[
var req = {
    edit: function() {
        window.location.href = './place_rental_req_write.php?pm_id=<?php echo $pm_id?>&ps_id=<?php echo $ps_id?>&pr_id=<?php echo $pr_id?>';
    }
    , cancel: function() {
        if (confirm("신청을 취소하시겠습니까?")) {
            var f = document.getElementById('req_form');
            f.action = './place_rental_req_write_delete.php';
            f.submit();
        }
    }
    , close: function() {
        if (opener) {
            self.close();
        }
        else {
            parent.$('#viewModal').modal('hide');
        }
    }
}


<?php /* 새창으로 열었을 경우 title, cont 영역 수정 */ ?>
$(function() {
    if (opener) {
        $('#title-outer').css({marginBottom:'20px'}).show();
        $('#cont-outer').css({padding:'0 20px 40px 20px'}).show();
    }
});
//]]>
</script>
