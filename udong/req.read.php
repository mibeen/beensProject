<?php
    include_once('./_common.php');


    if($is_guest) {
        alert_script("로그인 후 이용 가능 합니다.", "parent.$('#viewModal').modal('hide');");
    }

    include_once('../bbs/_head.php');


    # chk : rfv.
    if ((int)$lr_idx <= 0) {
        alert_script("잘못된 접근 입니다.", "parent.$('#viewModal').modal('hide');");
    }


    #----- get : local_place_req
    $LR = sql_fetch(
        "Select lr.*"
        ."      , lp.lp_name"
        ."      , la.la_name"
        ."      , M.mb_nick"
        ."  From local_place_req As lr"
        ."      Inner Join local_place As lp On lp.lp_idx = lr.lp_idx"
        ."          And lp.lp_ddate is null"
        ."      Inner Join local_place_area As la On la.la_idx = lp.la_idx"
        ."          And la.la_ddate is null"
        ."      Inner Join {$g5['member_table']} As M On M.mb_id = lr.mb_id"
        ."  Where lr.lr_ddate is null"
        ."      And lr.lr_idx = '" . mres($lr_idx) . "'"
        ."      And lr.mb_id = '" . mres($member['mb_id']) . "'"
        ."  Order By lr.lr_idx Desc"
        ."  Limit 1"
    );
    if (is_null($LR['lr_idx'])) {
        unset($LR);
        alert_script("잘못된 접근 입니다.", "parent.$('#viewModal').modal('hide');");
    }


    $str_lr_status = ['A'=>'신청', 'B'=>'승인', 'C'=>'미승인', 'D'=>'승인취소'];
    $str_cancel_reason = ['A'=>'일정변경 필요', 'B'=>'시설 이용불가'];
?>

<div>
    <form id="req_form" name="req_form" method="post" onsubmit="return false">
    <input type="submit" style="width:1px;height:1px;position:absolute;top:-10000px;left:-10000px" />

    <input type="hidden" name="lp_idx" value="<?php echo $lp_idx?>" />
    <input type="hidden" name="lr_idx" value="<?php echo $lr_idx?>" />

    <div id="title-outer" class="event_apply_top" style="display:none">
        <div class="top_tit">신청하기</div>
        <a href="javascript:void(0);" onclick="req.close();" class="cls"><span class="ico_x"></span></a>
    </div>

    <div id="cont-outer" class="coron_apply">
        <p class="page_tit">공간이용신청서</p>
        <p class="tit">
            <span class="name"><?php echo $LR['lp_name']; ?></span>
        </p>
        <table cellspacing="0" cellpadding="0" border="0" class="coron_ts1 br_t br_b mt20">
            <colgroup>
                <col width="100px" /><col width="" />
            </colgroup>
            <tr>
                <th>이름</th>
                <td>
                    <span class="mb_nick"><?php echo $member['mb_nick']?></span>
                </td>
            </tr>
            <tr>
                <th>휴대폰</th>
                <td><?php echo(F_hsc($LR['lr_tel']))?></td>
            </tr>
            <tr>
                <th>이메일</th>
                <td><?php echo(F_hsc($LR['lr_email']))?></td>
            </tr>
            <tr>
                <th>이용희망일시</th>
                <td><?php echo(date('Y-m-d H:i', strtotime($LR['lr_sdate'])) . ' ~ ' . date('H:i', strtotime($LR['lr_edate'])))?></td>
            </tr>
            <tr>
                <th class="vaT">이용목적</th>
                <td><?php echo(nl2br(F_hsc($LR['lr_usage'])))?></td>
            </tr>
            <tr>
                <th>이용인원</th>
                <td><?php echo(F_hsc($LR['lr_p_cnt']))?> 명</td>
            </tr>
            <tr>
                <th class="vaT">추가요구사항</th>
                <td><?php echo(nl2br(F_hsc($LR['lr_cont'])))?></td>
            </tr>
            <tr>
                <th>진행상태</th>
                <td><?php echo($str_lr_status[$LR['lr_status']])?></td>
            </tr>
            <?php
            if ($LR['lr_status'] == 'B' && $LR['lr_manager_notice'] != '') {
                ?>
            <tr>
                <th class="vaT">안내사항</th>
                <td><?php echo(F_hsc($LR['lr_manager_notice']))?></td>
            </tr>
                <?php
            }

            if ($LR['lr_status'] == 'D') {
                ?>
            <tr>
                <th class="vaT">취소 사유</th>
                <td><?php echo($str_cancel_reason[$LR['lr_cancel_reason']])?></td>
            </tr>
                <?php
            }
            ?>

        </table>

        <div class="taC mt20">
            <?php
            if ($LR['lr_status'] == 'A') {
                echo '<a href="./req.add.php?lp_idx='.$lp_idx.'&lr_idx='.$lr_idx.'&pim=1" class="nx_btn5">수정하기</a> ';
            }
            if ($LR['lr_status'] == 'A' || $LR['lr_status'] == 'B') {
                echo '<a href="javascript:req.cancel();" class="nx_btn8">취소하기</a>';
            }
            ?>
            <a href="javascript:req.close();" class="nx_btn7">닫기</a>   
        </div>
    </div>

    </form>
</div>

<script>
//<![CDATA[
var req = {
    cancel: function() {
        if (confirm("예약을 취소하시겠습니까? 취소 후에는 복구가 불가능합니다."))
        {
            $.ajax({
                url: 'req.cancelProc.php',
                type: 'POST',
                dataType: 'json',
                data: $('#req_form').serialize()
            })
            .done(function(json) {
                if (!json.success) {
                    if (json.msg) alert(json.msg);
                    return;
                }

                if (json.msg) alert(json.msg);
                if (opener) {
                    opener.location.reload();
                    self.close();
                }
                else {
                    parent.$('#viewModal').modal('hide');
                    parent.location.reload();
                }
            })
            .fail(function(a, b, c) {
                alert(a.statusText+' ('+a.status+')\n\n'+a.responseText);
            });
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

<?php
include_once('../_tail.php');
?>