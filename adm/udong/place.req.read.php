<?php
	$sub_menu = "970100";
	include_once('./_common.php');
    include_once('./place.req.err.php');

	auth_check($auth[$sub_menu], 'w,d');


    # set : variables
    $lp_idx = $_REQUEST['lp_idx'];
    $lr_idx = $_REQUEST['lr_idx'];


    # re-define
    $lp_idx = (CHK_NUMBER($lp_idx) > 0) ? (int)$lp_idx : '';
    $lr_idx = (CHK_NUMBER($lr_idx) > 0) ? (int)$lr_idx : '';


    # chk : rfv.
    if ($lp_idx == '' || $lr_idx == '') alert('잘못된 접근입니다.');


	# get : record
    $db_lr = sql_fetch(
    	"Select lr.*"
        ."      , lp.lp_name"
    	."		, la.la_name"
    	."		, M.mb_nick"
    	."	From local_place_req As lr"
    	."		Inner Join local_place As lp On lp.lp_idx = lr.lp_idx"
    	."			And lp.lp_ddate is null"
    	."		Inner Join local_place_area As la On la.la_idx = lp.la_idx"
    	."			And la.la_ddate is null"
    	."		Inner Join {$g5['member_table']} As M On M.mb_id = lr.mb_id"
    	."	Where lr.lr_ddate is null"
    	."		And lr.lr_idx = '{$lr_idx}'"
    	."	Order By lr.lr_idx Desc"
    	."	Limit 1"
    );
    if (is_null($db_lr['lr_idx'])) {
    	unset($db_lr);
    	alert_script("존재하지 않는 정보 입니다.", "self.close();");
    }

    
    # 관리자 개인정보 접근이력 기록
    nx_privacy_log('read', 'local_place_req', $lr_idx);


	include "../inc/pop.top.php";
?>
<script src="<?php echo(G5_JS_URL)?>/nx.jquery.centermodal/jquery.centermodal.min.js"></script>
<link rel="stylesheet" href="<?php echo(G5_JS_URL)?>/nx.jquery.centermodal/style.css">

<div>
    <div class="req_wrap">
        <div class="nx-tit1">공간이용신청정보</div>
        <table border="0" cellpadding="0" cellspacing="0" class="nx-t-read2">
            <colgroup>
                <col width="130"><col width="">
            </colgroup>
            <tbody>
                <tr>
                    <th>장소명</th>
                    <td><strong><?php echo(F_hsc($db_lr['lp_name']))?></strong></td>
                </tr>
                <tr>
                    <th>이름</th>
                    <td><?php echo F_hsc($db_lr['mb_nick'])?></td>
                </tr>
                <tr>
                    <th>휴대폰</th>
                    <td><?php echo F_hsc($db_lr['lr_tel'])?></td>
                </tr>
                <tr>
                    <th>이메일</th>
                    <td><?php echo F_hsc($db_lr['lr_email'])?></td>
                </tr>
                <tr>
                    <th>이용희망일시</th>
                    <td><?php
                    	echo date('Y-m-d H:i', strtotime($db_lr['lr_sdate'])) .' ~ '. date('H:i', strtotime($db_lr['lr_edate']));
                    ?></td>
                </tr>
                <tr>
                    <th>이용목적</th>
                    <td><?php echo F_hsc($db_lr['lr_usage'])?></td>
                </tr>
                <tr>
                    <th>이용인원</th>
                    <td><?php echo number_format($db_lr['lr_p_cnt']);?> 명</td>
                </tr>
                <tr>
                    <th>추가요구사항</th>
                    <td><textarea name="cont" id="cont" class="nx-ips1" style="min-height: 90px;" disabled><?php echo htmlspecialchars($db_lr['lr_cont'])?></textarea></td>
                </tr>
            </tbody>
        </table>

        <div class="taC mt20">
            <?php
            if ($db_lr['lr_status'] == 'B') {
                echo '<button onclick="req.cancel_pop()" class="nx-btn4">승인취소</button>';
            }
            else {
                echo '<button onclick="req.appr_pop()" class="nx-btn2">승인</button> ';
                if ($db_lr['lr_status'] != 'C') {
                    echo '<button onclick="req.hold()" class="nx-btn3">미승인</button> ';
                }
            }
            ?>
            <button onclick="window.close();" class="nx-btn3">닫기</button>
        </div>
    </div>
</div>

<div style="display:none">
	<form id="frmact" name="frmact" onsubmit="return false">
		<input type="hidden" name="lp_idx" value="<?php echo($lp_idx)?>" />
		<input type="hidden" name="lr_idx" value="<?php echo($lr_idx)?>" />
        <input type="hidden" name="lr_manager_notice" id="lr_manager_notice" value="<?php echo($db_lr['lr_manager_notice'])?>">
        <input type="hidden" name="lr_cancel_reason" id="lr_cancel_reason" value="">
	</form>
</div>

<div id="pop_appr_msg" class="popup_wrap1" style="display:none;">
    <div class="popup_wrap2">
        <div class="nx-box3 ml10 mr10">
            <div class="nx-tit1">공간이용 신청을 승인하시겠습니까?</div>
            <p class="taL">공간이용 안내사항이 있을 시 작성해주세요.</p>
            <textarea id="pop_manager_notice" name="pop_manager_notice" class="nx-ips1" style="min-height:80px;"><?php echo(F_hsc($db_lr['lr_manager_notice']))?></textarea>
            <div class="taC mt10">
                <a href="javascript:req.appr();" class="nx-btn1">확인</a>
                <a href="javascript:void(0);" onclick="$('#pop_appr_msg').hide();" class="nx-btn3">취소</a>
            </div>
        </div>
    </div>    
</div>

<div id="pop_cancel_msg" class="popup_wrap1" style="display:none;">
    <div class="popup_wrap2">
        <div class="nx-box3 ml10 mr10">
            <div class="nx-tit1">정말 승인취소 하시겠습니까?</div>
            <p class="taL">승인 취소시 사전 신청자에게 양해를 구해주시기 바랍니다.</p>
            <div class="radio1_wrap taC">
                <input type="radio" id="pop_cancel_reason1" name="pop_cancel_reason" class="radio1" value="A" /><label for="pop_cancel_reason1"><span class="radbox"><span></span></span><span class="txt">일정변경 필요</span></label>
                <input type="radio" id="pop_cancel_reason2" name="pop_cancel_reason" class="radio1" value="B" /><label for="pop_cancel_reason2"><span class="radbox"><span></span></span><span class="txt">시설 이용불가</span></label>
            </div>
            <div class="taC mt20">
                <a href="javascript:req.cancel();" class="nx-btn1">확인</a>
                <a href="javascript:void(0);" onclick="$('#pop_appr_msg').hide();" class="nx-btn3">취소</a>
            </div>
        </div>
    </div>    
</div>

<script>
//<!CDATA[[
var lp_idx = '', lr_idx = '';
var req = {
    appr_pop: function() {
        $('#pop_appr_msg').show();
    }
	, appr: function() {
        if (confirm("승인 하시겠습니까?")) {
            $('#lr_manager_notice').val($('#pop_manager_notice').val());
    		this.do('B');
        }
	}
	, hold: function() {
		if (confirm("미승인 처리하시겠습니까?")) {
			this.do('C');
		}
	}
    , cancel_pop: function() {
        $('#pop_cancel_msg').show();
    }
	, cancel: function() {
        if ($('#pop_cancel_msg :radio[name="pop_cancel_reason"]:checked').length <= 0) {
            alert("취소사유를 선택해 주세요.");
            $('#pop_cancel_reason1').focus(); return;
        }

		if (confirm("승인취소 하시겠습니까?")) {
            $('#lr_cancel_reason').val($('#pop_cancel_msg :radio[name="pop_cancel_reason"]:checked').val());
			this.do('D');
		}
	}
	, do: function(m) {
		if ((['B','C','D']).indexOf(m) === -1) return;

		var f = new FormData($('#frmact')[0]);
		f.append('m', m);

		$.ajax({
			url: 'place.req.editProc.php',
			type: 'POST',
			dataType: 'json',
			data: f,
			processData: false,
			contentType: false
		})
		.done(function(json) {
			if (!json.success) {
				if (json.msg) alert(json.msg);
				return;
			}

			if (json.msg) alert(json.msg);
			opener.location.reload();
			self.close();
		})
		.fail(function(a, b, c) {
			alert(a.statusText+' ('+a.status+')\n\n'+a.responseText);
		});
	}
}
//]]>
</script>
<?php
	include "../inc/pop.btm.php";
?>
