<?php
	$sub_menu = "990200";
	include_once('./_common.php');

	auth_check($auth[$sub_menu], 'w,d');


	if (isset($_POST['PS_IDX']) && $_POST['PS_IDX']) {
	    $PS_IDX = preg_replace('/[^0-9]/', '', $_POST['PS_IDX']);
	} else {
	    alert("잘못된 접근입니다.");
	}

	if (isset($_POST['PR_IDX']) && $_POST['PR_IDX']) {
	    $PR_IDX = preg_replace('/[^0-9]/', '', $_POST['PR_IDX']);
	} else {
	    $PR_IDX = 0;
	}


	# get : record
    $db_pr = sql_fetch(
    	"Select PR.*"
    	."		, PS.PS_GUBUN, PS.PS_NAME"
    	."		, PA.PA_NAME"
    	."		, M.mb_nick"
    	."	From PLACE_RENTAL_REQ As PR"
    	."		Inner Join PLACE_RENTAL_SUB As PS On PS.PS_IDX = PR.PS_IDX"
    	."			And PS.PS_DDATE is null"
    	."		Inner Join PLACE_RENTAL_MASTER As PM On PM.PM_IDX = PS.PM_IDX"
    	."			And PM.PM_DDATE is null"
    	."		Inner Join PLACE_RENTAL_AREA As PA On PA.PA_IDX = PM.PA_IDX"
    	."			And PA.PA_DDATE is null"
    	."		Inner Join {$g5['member_table']} As M On M.mb_no = PR.mb_no"
    	."	Where PR.PR_DDATE is null"
    	."		And PR.PS_IDX = '{$PS_IDX}'"
    	."		And PR.PR_IDX = '{$PR_IDX}'"
    	."	Order By PR.PR_IDX Desc"
    	."	Limit 1"
    );
    if (is_null($db_pr['PR_IDX'])) {
    	unset($db_pr);
    	alert_script("존재하지 않는 정보 입니다.", "self.close();");
    }

    
    # define additional value
    switch ($db_pr['PS_GUBUN']) {
    	case 'A':
    		$db_pr['ps_gubun_str'] = '강의실';
    		break;
    	case 'B':
    		$db_pr['ps_gubun_str'] = '숙소';
    		break;
    	
    	default:
    		break;
    }


    # 관리자 개인정보 접근이력 기록
    nx_privacy_log('read', 'PLACE_RENTAL_REQ', $PR_IDX);


	include "../inc/pop.top.php";
?>
<div>
    <?php /*
    <p class="req_popup_titile"><?php echo $db_pr['ps_gubun_str']?> 신청<a href="javascript:void(0);" onclick="self.close()"><span class="ico_x"></span></a></p>
    */ ?>
    <div class="req_wrap">
        <div class="nx-tit1">예약정보</div>
        <table border="0" cellpadding="0" cellspacing="0" class="nx-t-read2">
            <colgroup>
                <col width="130"><col width="">
            </colgroup>
            <tbody>
                <tr>
                    <th><?php echo($db_pr['ps_gubun_str'])?></th>
                    <td><strong><?php echo($db_pr['PS_NAME'])?></strong></td>
                </tr>
                <tr>
                    <th>입실일</th>
                    <td><?php
                    	echo date('Y-m-d', strtotime($db_pr['PR_SDATE']));
                    ?></td>
                </tr>
                <?php if($db_pr['PS_GUBUN'] == 'A') { ?>
                <tr>
                    <th>시간</th>
                    <td><?php
                    	echo date('H:i', strtotime($db_pr['PR_SDATE'])) .' ~ '. date('H:i', strtotime($db_pr['PR_EDATE']));
                    ?></td>
                </tr>
                <?php } else if($db_pr['PS_GUBUN'] == 'B') { ?>
                <tr>
                    <th>기간</th>
                    <td><?php
                    	$_sdate = strtotime($db_pr['PR_SDATE']);
                    	$_edate = strtotime($db_pr['PR_EDATE']);
                    	echo (($_edate - $_sdate) / 86400).'일';
                    ?></td>
                </tr>
                <tr>
                    <th>퇴실일</th>
                    <td id="edate_str"><?php
                    	echo date('Y-m-d', strtotime($db_pr['PR_EDATE']));
                    ?></td>
                </tr>
                <?php } ?>
                <tr>
                    <th>인원</th>
                    <td><?php echo number_format($db_pr['PR_P_CNT']);?> 명</td>
                </tr>
            </tbody>
        </table>

        <div class="nx-tit1">고객정보</div>
        <table border="0" cellpadding="0" cellspacing="0" class="nx-t-read2">
            <colgroup>
                <col width="110"><col width="">
            </colgroup>
            <tbody>
                <tr>
                    <th>이름</th>
                    <td><?php echo $db_pr['mb_nick']?></td>
                </tr>
                <tr>
                    <th>연락처</th>
                    <td><?php echo $db_pr['PR_TEL']?></td>
                </tr>
                <tr>
                    <th>추가요구사항</th>
                    <td><textarea name="cont" id="cont" class="nx-ips1" style="min-height: 90px;" disabled><?php echo htmlspecialchars($db_pr['PR_CONT'])?></textarea></td>
                </tr>
            </tbody>
        </table>
        <div class="taC mt20">
            <?php
            if ($db_pr['PR_STATUS'] != 'B') {
            	echo '<button onclick="req.appr()" class="nx-btn2">승인하기</button> ';
            }
            if ($db_pr['PR_STATUS'] != 'C') {
            	// echo '<button onclick="req.hold()" class="nx-btn3">보류하기</button> ';
            }
            ?>
            <button onclick="req.del()" class="nx-btn4">삭제하기</button>
            <button onclick="window.close();" class="nx-btn3">닫기</button>
        </div>
    </div>
</div>

<div style="display:none">
	<form id="frmact" name="frmact" onsubmit="return false">
		<input type="hidden" name="pm_idx" value="<?php echo($PM_IDX)?>" />
		<input type="hidden" name="ps_idx" value="<?php echo($PS_IDX)?>" />
		<input type="hidden" name="pr_idx" value="<?php echo($PR_IDX)?>" />
	</form>
</div>

<script>
//<!CDATA[[
var pm_idx = '', ps_idx = '', pr_idx = '';
var req = {
	appr: function() {
		if (confirm("승인하시겠습니까?")) {
			this.do('B');
		}
	}
	, hold: function() {
		if (confirm("보류하시겠습니까?")) {
			this.do('C');
		}
	}
	, del: function() {
		if (confirm("삭제된 정보는 복구가 불가능 합니다.\n\n삭제 하시겠습니까?")) {
			this.do('D');
		}
	}
	, do: function(m) {
		if ((['B','C','D']).indexOf(m) === -1) return;

		var f = new FormData($('#frmact')[0]);
		f.append('m', m);

		$.ajax({
			url: 'place_rental_req_proc.php',
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
