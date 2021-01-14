<?php
    include_once('./_common.php');

    if($is_guest) {
        alert_script("로그인 후 이용 가능 합니다.", "parent.$('#viewModal').modal('hide');");
    }

    include_once('../bbs/_head.php');


    # set : variables
    $year = $_GET['year'];
    $month = $_GET['month'];
    $day = $_GET['day'];


    # re-define
    $year = ($year != '') ? preg_replace('/[^0-9]/', '', $year) : date('Y');
    $month = ($month != '') ? preg_replace('/[^0-9]/', '', $month) : date('n');
    $day = ($day != '') ? preg_replace('/[^0-9]/', '', $day) : date('d');


    # 필요한 날짜, 시간 정의
    $last_day = date("t", strtotime($year . '-' . $month . '-1'));


    # chk : rfv.
    if ($lp_idx <= 0) alert('잘못된 접근입니다.');


    $sql = "Select lp.lp_idx, lp.lp_name
                    , la.la_name
                From local_place As lp
                    Inner Join local_place_area As la On la.la_idx = lp.la_idx
                where lp_idx = '" . mres($lp_idx) . "'"
        ;
    $LP = sql_fetch($sql);

    if ($LP['lp_idx'] == '') alert('잘못된 접근입니다.');


    #----- get : 수정일 경우 해당 글의 정보
    # 신청/보류 + 입실일이 오늘 전 일 경우만 수정 페이지 접근 가능
    if ((int)$lr_idx > 0) {
        $LR = sql_fetch(
            "Select *
                From local_place_req
                Where lr_ddate is null
                    And lr_status = 'A'
                    And lp_idx = '" . mres($lp_idx) . "'
                    And lr_idx = '" . mres($lr_idx) . "'
                    And DATE_FORMAT(lr_sdate, '%Y-%m-%d') >= DATE_FORMAT(now(), '%Y-%m-%d')
                Order By lr_idx Desc
                Limit 1"
        );
        if (is_null($LR['lr_idx'])) {
            unset($LR);
            alert_script("잘못된 접근 입니다.", "parent.$('#viewModal').modal('hide');");
        }

        # 기존 정보를 기반으로 할 경우 날짜 정보도 해당 기준으로 변경
        $_t = explode('-', date('Y-m-d', strtotime($LR['lr_sdate'])));
        $year = $_t[0];
        $month = $_t[1];
        $day = $_t[2];

        unset($_t);
    }
    else {
        $LR = array();
    }
    #####


    # get : 당일의 예약
    $sql = "Select lr.*"
        ."  From local_place_req As lr"
        ."  Where lr.lr_ddate is null"
        ."      And year(lr.lr_sdate) = '" . mres($year) . "'"
        ."      And month(lr.lr_sdate) = '" . mres($month) . "'"
        ."      And day(lr.lr_sdate) = '" . mres($day) . "'"
        ."      And lr.lp_idx = '" . mres($lp_idx) . "'"
        ."      And lr.lr_idx != '" . mres($lr_idx) . "'"
        ."      And lr.lr_status In ('A', 'B')"
        ."  Order By hour(lr.lr_sdate) Asc"
        ;

    $db1 = sql_query($sql);
    $req_list = array();
    while ($rs1 = sql_fetch_array($db1)) {
        $req_list[] = $rs1;
    }


    # 슬라이더 초기값
    $LR['sdate'] = ($lr_idx != '') ? date('G:i', strtotime($LR['lr_sdate'])) : $time_min['text'];
    $LR['edate'] = ($lr_idx != '') ? date('G:i', strtotime($LR['lr_edate'])) : $time_max['text'];

    $_sdate = explode(':', $LR['sdate']);
    $_edate = explode(':', $LR['edate']);
    $LR['sdate_val'] = ((int)$_sdate[0] * 60) + (int)$_sdate[1];
    $LR['edate_val'] = ((int)$_edate[0] * 60) + (int)$_edate[1];

    unset($_sdate, $_edate);

    $can_time = ($lr_idx == '' && Count($req_list) > 0) ? false : true;
?>

<link rel="stylesheet" href="<?php echo(G5_PLUGIN_URL)?>/jquery-ui-slider/jquery-ui.min.css">
<script src="<?php echo(G5_PLUGIN_URL)?>/jquery-ui-slider/jquery-ui.min.js"></script>

<div>
    <form id="req_form" name="req_form" action="./req.addProc.php" method="post" onsubmit="return false">
    <input type="submit" style="width:1px;height:1px;position:absolute;top:-10000px;left:-10000px" />

    <input type="hidden" name="lp_idx" value="<?php echo $lp_idx?>" />
    <input type="hidden" name="lr_idx" value="<?php echo $lr_idx?>" />
    <input type="hidden" name="year" value="<?php echo $year?>" />
    <input type="hidden" name="month" value="<?php echo $month?>" />
    <input type="hidden" name="day" value="<?php echo $day?>" />
    <input type="hidden" name="w" value="<?php if ($lr_idx != '') echo('u')?>" />

    <div id="title-outer" class="event_apply_top" style="display:none">
        <div class="top_tit">신청하기</div>
        <a href="javascript:void(0);" onclick="req.close();" class="cls"><span class="ico_x"></span></a>
    </div>

    <div id="cont-outer" class="coron_apply">
        <p class="page_tit">공간이용신청서</p>
        <p class="tit">
            <?php /*<span class="region"><?php echo $LP['la_name']; ?></span>*/ ?>
            <span class="name"><?php echo $LP['lp_name']; ?></span>
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
                <td><?php
                    if (substr_count($LR['lr_tel'], '-') == 2) {
                        $_t = explode('-', $LR['lr_tel']);
                    }
                    else {
                        $_t = explode('-', '--');
                    }

                    echo '<input type="tel" id="lr_tel1" name="lr_tel1" value="'.htmlspecialchars($_t[0]).'" maxlength="4" oninput="maxLengthCheck(this)" class="nx_ips5 ws3" /> ';
                    echo '<input type="tel" id="lr_tel2" name="lr_tel2" value="'.htmlspecialchars($_t[1]).'" maxlength="4" oninput="maxLengthCheck(this)" class="nx_ips5 ws3" /> ';
                    echo '<input type="tel" id="lr_tel3" name="lr_tel3" value="'.htmlspecialchars($_t[2]).'" maxlength="4" oninput="maxLengthCheck(this)" class="nx_ips5 ws3" />';

                    unset($_t);
                ?></td>
            </tr>
            <tr>
                <th>이메일</th>
                <td>
                    <input type="lr_email" id="lr_email" name="lr_email" value="<?php echo($LR['lr_email'])?>" maxlength="40" class="nx_ips5 wm" />
                </td>
            </tr>
            <tr>
                <th class="vaT">이용희망일시</th>
                <td>
                    <?php echo("{$year}-{$month}-{$day}")?>

                    <?php # time slider ?>
                    <div id="time-range" class="mt10 mb20">
                        <input type="hidden" name="sdate" id="sdate" value="<?php echo($LR['sdate'])?>">
                        <input type="hidden" name="edate" id="edate" value="<?php echo($LR['edate'])?>">
                        <p class="mb10">
                            예약 시간: <span class="slider-time"><?php echo($LR['sdate'])?></span> - <span class="slider-time2"><?php echo($LR['edate'])?></span>
                            <span id="range-tip-msg" class="ml10" style="color: #3f9de3;<?php if(!$can_time){echo('display:none;');}?>">슬라이더 바를 조절하여 일정을 선택해주세요.</span>
                            <span id="range-fail-msg" class="ml10" style="color:#ee6868;<?php if($can_time){echo('display:none;');}?>">(선택한 범위내에 다른 예약이 있습니다. 시간을 조절해주세요.)</span>
                        </p>
                        <div class="sliders_step1 nx-time-range1">
                            <div id="slider-range">
                                <?php
                                for ($i = 0; $i < Count($req_list); $i++) {
                                    $_itm = $req_list[$i];

                                    $_shour = date('G', strtotime($_itm['lr_sdate']));
                                    $_smin = date('i', strtotime($_itm['lr_sdate']));
                                    $_stime_val = ($_shour * 60) + (int)$_smin;

                                    $_ehour = date('G', strtotime($_itm['lr_edate']));
                                    $_emin = date('i', strtotime($_itm['lr_edate']));
                                    $_etime_val = ($_ehour * 60) + (int)$_emin;


                                    if ($_stime_val == $time_start['value']) {
                                        $_left = 0;
                                    }
                                    else {
                                        $_left = round((($_stime_val - $time_start['value']) / $time_val_gap) * 100, 2);
                                    }

                                    $_width =  round((($_etime_val - $_stime_val) / $time_val_gap) * 100, 2);
                                    ?>
                                    <div data-type="disabled" data-min="<?php echo($_stime_val)?>" data-max="<?php echo($_etime_val)?>" class="disabled-range ui-slider-range" style="left: <?php echo($_left)?>%; width: <?php echo($_width)?>%;"></div>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <th>이용목적</th>
                <td><input type="text" id="lr_usage" name="lr_usage" value="<?php echo($LR['lr_usage'])?>" class="nx_ips5" /></td>
            </tr>
            <tr>
                <th>이용인원</th>
                <td><input type="text" id="lr_p_cnt" name="lr_p_cnt" value="<?php echo($LR['lr_p_cnt'])?>" maxlength="6" class="nx_ips5 ws2" /> 명</td>
            </tr>
            <tr>
                <th>추가요구사항</th>
                <td><textarea id="lr_cont" name="lr_cont" class="nx_ips5"><?php echo htmlspecialchars($LR['lr_cont'])?></textarea></td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="nx_tit3 mt30">개인정보 수집·이용 동의서</div>
                    <div style="padding: 10px; border: 1px solid #ccc;">
                        <p>
                            개인정보 수집·이용 내역<br>
                            1. 수집목적 : 우리동네 학습공간 공간 이용 매칭<br>
                            2. 수집항목 : 성명, 핸드폰 번호, 이메일 주소<br>
                            3. 보유기간 : 경기도평생교육진흥원 우리동네 학습공간 공간 이용 신청 내역 삭제 시까지 <br>
                            <br>
                            위의 개인정보 수집·이용에 대한 동의를 거부할 권리가 있습니다.<br>
                            그러나 동의를 거부할 경우 우리동네 학습공간 서비스를 이용하실 수 없습니다.
                        </p>
                    </div>
                    <div class="taR mt10">
                        <span class="dsIB vaM mr10">개인정보 수집·이용에 동의하시면 동의함을 선택해 주십시오.</span>
                        <span class="dsIB vaM">
                            <label class="control-label sp-label">
                                <input type="radio" name="AGREE1" id="AGREE1_Y" value="Y" class="mt0"> 동의함
                            </label>
                            <label class="control-label sp-label">
                                <input type="radio" name="AGREE1" id="AGREE1_N" value="N" class="mt0"> 동의안함
                            </label>
                        </span>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="nx_tit3 mt20">개인정보 제3자 제공 동의서</div>
                    <div style="padding: 10px; border: 1px solid #ccc;">
                        <p>
                            개인정보 제3자 제공 내역<br>
                            1. 제공받는 자 : 우리동네 학습공간 시설주 및 담당자<br>
                            2. 이용 목적 : 우리동네 학습공간 공간 이용 매칭<br>
                            3. 제공 항목 : 성명, 핸드폰 번호, 이메일 주소<br>
                            4. 제공 받는 자의 보유기간 : 개인정보 제공자의 내용 변경 및 파기요구 시까지<br>
                            <br>
                            위의 개인정보 제공에 대한 동의를 거부할 권리가 있습니다.<br>
                            그러나 동의를 거부할 경우 우리동네 학습공간 서비스를 이용하실 수 없습니다.
                        </p>
                    </div>
                    <div class="taR mt10">
                        <span class="dsIB vaM mr10">개인정보 제3자 제공 동의서에 동의하시면 동의함을 선택해 주십시오.</span>
                        <span class="dsIB vaM">
                            <label class="control-label sp-label">
                                <input type="radio" name="AGREE2" id="AGREE2_Y" value="Y" class="mt0"> 동의함
                            </label>
                            <label class="control-label sp-label">
                                <input type="radio" name="AGREE2" id="AGREE2_N" value="N" class="mt0"> 동의안함
                            </label>
                        </span>
                    </div>
                </td>
            </tr>
        </table>

        <div class="taC mt20">
            <?php
            if ($lr_idx != '') {
                echo '<input type="button" class="add_req_btn nx_btn5" onclick="req.onsu()" value="저장하기"> ';
                echo '<input type="button" class="cancel_req_btn nx_btn7" onclick="req.close()" value="닫기">';
            }
            else {
                echo '<input type="button" class="add_req_btn nx_btn5" onclick="req.onsu()" value="신청하기">';
            }
            ?>
        </div>
    </div>

    </form>
</div>

<script>
//<![CDATA[
var year = parseInt('<?php echo $year?>');
var month = parseInt('<?php echo $month?>', 10);
var day = parseInt('<?php echo $day?>', 10);
var last_day = parseInt("<?php echo $last_day;?>", 10);

var can_time = '<?php echo($can_time)?>';


var req = {
    onsu: function() {
        var _t = $('#lr_tel1'), _tv = _t.val();
        if (_tv == '' || isNaN(_tv)) {
            alert("휴대폰 정보를 바르게 입력해 주세요.");
            _t.focus(); return;
        }
        var _t = $('#lr_tel2'), _tv = _t.val();
        if (_tv == '' || isNaN(_tv)) {
            alert("휴대폰 정보를 바르게 입력해 주세요.");
            _t.focus(); return;
        }
        var _t = $('#lr_tel3'), _tv = _t.val();
        if (_tv == '' || isNaN(_tv)) {
            alert("휴대폰 정보를 바르게 입력해 주세요.");
            _t.focus(); return;
        }

        var _t = $('#lr_email');
        if (_t.val() == '') {
            alert("이메일 정보를 입력해 주세요.");
            _t.focus(); return;
        }

        var _t = $('#sdate');
        if (_t.val() == '') {
            alert("시간(시작) 정보를 선택해 주세요.");
            _t.focus(); return;
        }
        var _t = $('#edate');
        if (_t.val() == '') {
            alert("시간(종료) 정보를 선택해 주세요.");
            _t.focus(); return;
        }

        if ($('#sdate').val() == $('#edate').val()) {
            alert("예약 시작시간과 종료시간은 같을 수 없습니다.");
            $('#sdate').focus(); return;
        }

        if (!can_time) {
            alert("예약할 수 없는 시간입니다. 시간을 변경해주세요.");
            $('#sdate').focus(); return;
        }

        var _t = $('#lr_usage');
        if (_t.val() == '') {
            alert("이용목적 정보를 입력해 주세요.");
            _t.focus(); return;
        }

        var _t = $('#lr_p_cnt'), _tv = _t.val();
        if (_tv == '' || isNaN(_tv)) {
            alert("이용인원 정보를 숫자로 입력해 주세요.");
            _t.focus(); return;
        }

        if ($(':radio[name="AGREE1"]:checked').val() != 'Y') {
            alert("개인정보 수집·이용에 동의해주세요.");
            $('#AGREE1_Y').focus(); return;
        }

        if ($(':radio[name="AGREE2"]:checked').val() != 'Y') {
            alert("개인정보 제3자 제공 동의서에 동의해주세요.");
            $('#AGREE2_Y').focus(); return;
        }

        if (confirm("입력하신 사항으로 진행하시겠습니까?")) {
            document.getElementById('req_form').submit();
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


function maxLengthCheck(object){
    if (object.value.length > object.maxLength){
        object.value = object.value.slice(0, object.maxLength);
    }
}


<?php /* 새창으로 열었을 경우 title, cont 영역 수정 */ ?>
$(function() {
    if (opener) {
        $('#title-outer').css({marginBottom:'20px'}).show();
        $('#cont-outer').css({padding:'0 20px 40px 20px'}).show();
    }
});


$("#slider-range").slider({
    range: true,
    min: <?php echo($time_start['value'])?>,
    max: <?php echo($time_end['value'])?>,
    step: <?php echo($time_step)?>,
    values: [<?php echo($time_min['value'])?>, <?php echo($time_max['value'])?>],
    slide: function (e, ui) {
        var hours1 = Math.floor(ui.values[0] / 60);
        var minutes1 = ui.values[0] - (hours1 * 60);

        if (hours1.length == 1) hours1 = '0' + hours1;
        if (minutes1.length == 1) minutes1 = '0' + minutes1;
        if (minutes1 == 0) minutes1 = '00';

        <?php
        # 12시간제
        /*
        if (hours1 >= 12) {
            if (hours1 == 12) {
                hours1 = hours1;
                minutes1 = minutes1 + " PM";
            } else {
                hours1 = hours1 - 12;
                minutes1 = minutes1 + " PM";
            }
        } else {
            hours1 = hours1;
            minutes1 = minutes1 + " AM";
        }
        if (hours1 == 0) {
            hours1 = 12;
            minutes1 = minutes1;
        }
        */
        ?>

        $('#time-range .slider-time').html(hours1 + ':' + minutes1);

        var hours2 = Math.floor(ui.values[1] / 60);
        var minutes2 = ui.values[1] - (hours2 * 60);

        if (hours2.length == 1) hours2 = '0' + hours2;
        if (minutes2.length == 1) minutes2 = '0' + minutes2;
        if (minutes2 == 0) minutes2 = '00';

        if (hours2 == 24) {
            hours2 = 11;
            minutes2 = 59;
        }
        <?php
        # 12시간제
        /*
        if (hours2 >= 12) {
            if (hours2 == 12) {
                hours2 = hours2;
                minutes2 = minutes2 + " PM";
            } else if (hours2 == 24) {
                hours2 = 11;
                minutes2 = "59 PM";
            } else {
                hours2 = hours2 - 12;
                minutes2 = minutes2 + " PM";
            }
        } else {
            hours2 = hours2;
            minutes2 = minutes2 + " AM";
        }
        */
        ?>

        $('.slider-time2').html(hours2 + ':' + minutes2);
    },
    stop: function( event, ui ) {
        var hours = Math.floor(ui.value / 60);
        var minutes = ui.value - (hours * 60);

        // 시작시간 변경
        if (ui.handleIndex == 0) {
            $('#sdate').val(hours + ':' + pad(minutes, 2));
        }
        // 종료시간 변경
        else {
            $('#edate').val(hours + ':' + pad(minutes, 2));
        }


        // 예약가능 여부 표시
        var bo_can = true;
        var _values = $("#slider-range").slider('values');

        $('#slider-range [data-type="disabled"]').each(function() {
            if (!bo_can) return;

            var _min = $(this).attr('data-min');
            var _max = $(this).attr('data-max');

            if (_values[0] >= _min && _values[0] < _max) bo_can = false; // 시작시간
            if (_values[1] > _min && _values[1] <= _max) bo_can = false; // 종료시간
            if (_values[0] < _min && _values[1] > _max) bo_can = false; // 시작, 종료 사이에 다른 예약
        });

        if (!bo_can) {
            $('#range-tip-msg').hide();
            $('#range-fail-msg').show();
            can_time = false;
        }
        else {
            $('#range-tip-msg').show();
            $('#range-fail-msg').hide();
            can_time = true;
        }
    },
    <?php
    if ($lr_idx != '') {
        ?>
    create: function() {
        $("#slider-range").slider( "values", [ <?php echo($LR['sdate_val'])?>, <?php echo($LR['edate_val'])?> ] );
    }
        <?php
    }
    ?>
});


// 숫자 앞에 size 만큼 0 붙임
function pad(num, size) {
    var s = num+"";
    while (s.length < size) s = "0" + s;
    return s;
}
//]]>
</script>

<?php
include_once('../_tail.php');
?>
