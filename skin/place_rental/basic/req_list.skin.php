<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// 선택옵션으로 인해 셀합치기가 가변적으로 변함
$colspan = 6;

if ($is_checkbox) $colspan++;

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$qa_skin_url.'/style.css">', 0);
?>


<div class="data_ct">
    <div class="coron_reserve_wrap">
        <div class="info">
            <p class="region"><?php echo $view['PA_NAME'] . " " . $view['PM_NAME']; ?></p>
            <p class="tit"><?php echo $view['PS_NAME']; ?></p>
            <div class="img_wrap">
                <?php echo $view['himg_str']; ?>
                <a href="<?php echo $detail_href?>" onclick='window.open(this.href, "", "left=10,top=10,width=500,height=400"); return false;' class="btn_detail"><?php echo $view['PA_GUBUN_TITLE']?>소개 &gt;</a>
            </div>
        </div>

        <div class="lst_wrap">
            <div class="date">
                <span class="vaM"><?php echo $year?>년 <?php echo $month?>월</span> 
                <a href="./place_rental_req_list.php?pm_id=<?php echo $pm_id?>&amp;ps_id=<?php echo $ps_id?>&amp;year=<?php echo $prev_year?>&amp;month=<?php echo $prev_month?>"><</a>&nbsp;
                <img src="../thema/hub/assets/imgs/ico/ico_calendar.png" alt="" class="vaM" />
                &nbsp;<a href="./place_rental_req_list.php?pm_id=<?php echo $pm_id?>&amp;ps_id=<?php echo $ps_id?>&amp;year=<?php echo $next_year?>&amp;month=<?php echo $next_month?>">></a>
            </div>
            <table cellspacing="0" cellpadding="0" border="0" class="lst">
                <colgroup>
                    <col width="70px" /><col width="" /><col width="100px" />
                </colgroup>
                <?php
                $next_show_yn_hide = 0;
                $today = date('Y-m-d');
                $req_timetable = array();
                $j = count($day_list);
                $week_array = array('', '월', '화', '수', '목', '금', '토', '일');

                for ($i = 0; $i < $j; $i++) {
                    $idx = ($i + 1);
                    $day = $idx;
                    if(strlen($day) == 1) { $day = "0".$idx; }

                    $holiday_yn = 0;
                    $week = date('Y-m-d', strtotime($year . "-" . $month . "-" . $idx));
                    $week = date('N', strtotime($week));
                    if($week == 7) {
                        $holiday_yn = 1;
                    }
                    $week = $week_array[$week];
                    if($week != '') {
                        $day = $day . " (" . $week . ")";
                    }

                    $result_req_row = $day_list[$i];

                    $c_date = date('Y-m-d', strtotime($year . '-' . $month . '-' . $idx));

                    $show_link_yn = true;
                    if($today > $c_date) {
                        $show_link_yn = false;
                    }

                    if($next_show_yn_hide > 1) {
                        $next_show_yn_hide--;
                        $show_link_yn = false;
                    }

                    if($view['PS_GUBUN'] == 'B') {
                        $req_timetable[$idx] = 1;
                    }
                ?>
                <tr>
                    <td align="center" <?php if($holiday_yn == 1) { echo('class="holiday"'); } ?>><?php echo $day; ?></td>
                    <td>
                        <?php
                            for ($ii=0; $row=sql_fetch_array($result_req_row); $ii++) {
                                if($ii == 0) { echo("<ul>"); }

                                $req_PR_IDX = $row['PR_IDX'];
                                $req_status = $row['PR_STATUS'];
                                $req_p_cnt = $row['PR_P_CNT'];
                                $req_tel = $row['PR_TEL'];
                                $req_info = $row['PR_CONT'];
                                $req_mb_no = $row['mb_no'];

                                if($view['PS_GUBUN'] == 'A') {
                                    $req_sdate = date('H:i', strtotime($row['PR_SDATE']));
                                    $req_edate = date('H:i', strtotime($row['PR_EDATE']));
                                    $req_edate_show = $req_edate;

                                    $timetable_sdate = date('G', strtotime($row['PR_SDATE']));
                                    $timetable_edate = date('G', strtotime($row['PR_EDATE']));

                                    if($req_status == 'A' || $req_status == 'B') {
                                        $req_timetable[$idx][] = array('sdate' => $timetable_sdate, 'edate' => $timetable_edate);
                                    }
                                }
                                else if($view['PS_GUBUN'] == 'B') {
                                    $req_sdate = date('Y-m-d', strtotime($row['PR_SDATE']));
                                    $req_edate = date('Y-m-d', strtotime($row['PR_EDATE']));
                                    $req_edate_show = $req_edate;
                                    
                                    $date1 = new DateTime($req_sdate);
                                    $date2 = new DateTime($req_edate);
                                    $req_edate = $date1->diff($date2)->days;

                                    if($req_edate > 1) {
                                        $next_show_yn_hide = $req_edate;
                                    }

                                    if($req_status == 'A' || $req_status == 'B') {
                                        $req_timetable[$idx] = 0;
                                        $show_link_yn = false;
                                    }
                                    else {
                                        $req_timetable[$idx] = 1;
                                    }
                                }

                                $t_member = sql_fetch(" select mb_nick, mb_no from {$g5['member_table']} where mb_no = TRIM('$req_mb_no') ");
                                
                                $req_title = ($ii+1) . '. ' . $t_member['mb_nick'] . ' (' . $req_sdate . '~' . $req_edate_show . ')';

                                $req_status_title = '';
                                if($req_status == 'A') { 
                                    $req_status_title = '신청'; 
                                    $req_status_str = 'apply';
                                }
                                else if($req_status == 'B') { 
                                    $req_status_title = '승인';
                                    $req_status_str = 'approve';
                                }
                                else if($req_status == 'C') {
                                    $req_status_title = '보류';
                                    $req_status_str = 'hold';
                                }

                                $is_mine = false;
                                $link_href_tag = '<span class="data ' . $req_status_str . ' req_list_link req_list_type_'.$req_status.'"><span class="s1">'.$req_title.'</span><span class="s2">'.$req_status_title.'</span></span>';
                                if(($row['mb_no'] == $member['mb_no']) || $is_admin == 'super') {
                                    $is_mine = true;
                                    $link_href_tag = '<a class="data ' . $req_status_str. '" href="javascript:void(0);" onclick="alter_req(this)" class="req_list_link req_list_type_'.$req_status.'"><span class="s1">'.$req_title.'</span><span class="s2">'.$req_status_title.'</span></a>';
                                }
                            ?>
                            <li>
                                <?php if($is_admin == 'super' || $is_mine) { ?>
                                <input type="hidden" name="t_pr_id" value="<?php echo $req_PR_IDX?>">
                                <input type="hidden" name="t_day" value="<?php echo $idx?>">
                                <input type="hidden" name="t_sdate" value="<?php echo $req_sdate?>">
                                <input type="hidden" name="t_edate" value="<?php echo $req_edate?>">
                                <input type="hidden" name="t_p_cnt" value="<?php echo $req_p_cnt?>">
                                <input type="hidden" name="t_tel" value="<?php echo $req_tel?>">
                                <input type="hidden" name="t_info" value="<?php echo $req_info?>">
                                <input type="hidden" name="t_status" value="<?php echo $req_status?>">
                                <input type="hidden" name="t_member" value="<?php echo $t_member['mb_nick']?>">
                                <?php } ?>
                                <?php echo $link_href_tag?>
                            </li>
                        <?php } ?>

                        <?php if($ii == 0) { ?>
                        <span><?php echo('신청현황이 없습니다.'); ?></span>
                        <?php } else { ?>
                        </ul>
                        <?php } ?>
                    </td>
                    <td>
                        <?php if($show_link_yn) { ?>
                        <a href="javascript:void(0);" onclick="prepare_req(<?php echo $idx?>)" class="btn_reserve">신청하기</a>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>

<a href="<?php echo $list_href?>">뒤로</a>

<div id="centermodal" class="ctmodal" style="width: 600px; height: 480px; max-width: 90%; max-height: 90%; box-shadow: rgb(0, 0, 0) 0px 0px 0px">
    <div class="top">
        <p data-id="title" class="tit"><?php echo $view['PA_GUBUN_TITLE']?> 신청</p>
        <a data-id="close" href="javascript:void(0)" onclick="close_popup()" class="cls"><span></span><span></span></a>
    </div>
    <div class="ct" data-id="cont">
        <form name="req_form" id="req_form" action="./place_rental_req_write_update.php" method="post" onsubmit="return false">
        <input type="hidden" name="pm_id" value="<?php echo $pm_id?>">
        <input type="hidden" name="ps_id" value="<?php echo $ps_id?>">
        <input type="hidden" name="pr_id" value="">
        <input type="hidden" name="year" value="<?php echo $year?>">
        <input type="hidden" name="month" value="<?php echo $month?>">
        <input type="hidden" name="gubun" value="<?php echo $view['PS_GUBUN']?>">
        <input type="hidden" name="w" value="">
        <input type="hidden" name="day" value="">
        <div class="coron_apply">
            <p class="page_tit">입실정보</p>
            <p class="tit"><span class="region"><?php echo $view['PA_NAME']; ?></span> <span class="name"><?php echo $view['PS_NAME']; ?></span></p>
            <table cellspacing="0" cellpadding="0" border="0" class="coron_ts1 br_t br_b mt20">
                <colgroup>
                    <col width="100px" /><col width="" />
                </colgroup>
                <tr>
                    <th>입실일</th>
                    <td><?php echo $year?>-<?php echo $month?>-<span class="day_span"></span></td>
                </tr>
                <tr>
                    <th>시간</th>
                    <?php if($view['PS_GUBUN'] == 'A') { ?>
                    <td>
                        <span class="nx_slt1 ws2">
                            <select name="sdate" id="sdate" onchange="sdate_changed()">
                                <option value="">시작시간</option>
                            </select>
                            <span class="ico">▼</span>
                        </span>
                        부터
                        <span class="nx_slt1 ws2">
                            <select name="edate" id="edate">
                                <option value="">종료시간</option>
                            </select>
                            <span class="ico">▼</span>
                        </span>
                        까지
                    </td>
                    <?php } else if($view['PS_GUBUN'] == 'B') { ?>
                    <td>
                        <span class="nx_slt1 ws2">
                            <select name="edate" id="edate" onchange="edate_changed()">
                                <option value="">기간</option>
                            </select>
                            <span class="end_date_text">퇴실일 : <span class="end_date_span"></span></span>
                        </span>
                    </td>
                    <?php } ?>
                </tr>
                <tr>
                    <th>인원</th>
                    <td><input type="text" id="p_cnt" name="p_cnt" class="nx_ips5 ws2" /> 명</td>
                </tr>
            </table>

            <p class="page_tit mt30">고객정보</p>
            <table cellspacing="0" cellpadding="0" border="0" class="coron_ts1">
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
                    <th>연락처</th>
                    <td>
                        <span class="nx_slt1 ws2">
                            <select name="tel1" id="">
                                <option value="">선택해주세요.</option>
                                <option value="010">010</option>
                                <option value="070">070</option>
                            </select>
                            <span class="ico">▼</span>
                        </span>
                        <input type="text" id="tel2" name="tel2" class="nx_ips5 ws2" />
                        <input type="text" id="tel3" name="tel3" class="nx_ips5 ws2" />
                    </td>
                </tr>
                <tr>
                    <th>추가요구사항</th>
                    <td><textarea id="cont" name="cont" class="nx_ips5"></textarea></td>
                </tr>
            </table>

            <div class="taC mt20">
                <input type="submit" class="add_req_btn nx_btn5" onclick="form_submit(1, this)" value="신청하기">
                <?php if($is_admin == 'super') { ?>
                <input type="submit" class="del_req_btn nx_btn7" onclick="form_submit(2, this.value)" value="삭제하기">
                <?php } else { ?>
                <input type="submit" class="cancel_req_btn nx_btn7" onclick="form_submit(2, this.value)" value="취소하기">
                <?php } ?>
            </div>
        </div>
        </form>
    </div>
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
<script>
var gubun = "<?php echo $view['PS_GUBUN'];?>";
var year = parseInt("<?php echo $year;?>", 10);
var month = parseInt("<?php echo $month;?>", 10);
var last_day = parseInt("<?php echo $last_day;?>", 10);
var req_timetable = '<?php echo json_encode($req_timetable)?>';
var next_month_day_list = JSON.parse('<?php echo json_encode($next_month_day_list)?>');

var s_date_list = [];
function prepare_req(day) {
    $('#centermodal').show();

    $('[name=pr_id]').val('');
    $('[name=sdate]').val('');
    $('[name=edate]').val('');
    $('[name=p_cnt]').val('');
    $('[name=tel1]').val('');
    $('[name=tel2]').val('');
    $('[name=tel3]').val('');
    $('[name=cont]').val('');
    $('.mb_nick').text('<?php echo $member["mb_nick"]?>');

    $('.end_date_text').hide();
    $('.end_date_span').text('');
    $('.add_req_btn').val('신청하기');
    $('[name=day]').val(day);
    $('[name=w]').val('');
    $('.day_span').text(day);

    if(gubun == 'A') {
        var date_limit = JSON.parse(req_timetable)[day];
        make_sdate_select(date_limit, '', '');
    }
    else if(gubun == 'B') {
        make_sdate_select_b(day, '');
    }
}

function make_sdate_select_b(day, addDay) {
    var edate_select = $('#edate');

    edate_select.empty();
    edate_select.append($('<option>', {
        value: '',
        text: '기간'
    }));

    var date_limit = JSON.parse(req_timetable);
    var t_day = day;
    var next_month_change_flag = 0;
            
    for(var i = 0; i < 10; i++) {
        var idx = parseInt(i + 1, 10);

        var target_value = date_limit[t_day];
        if(target_value === undefined && next_month_change_flag == 0) {
            next_month_change_flag = 1;

            date_limit = next_month_day_list;
            t_day = 1;

            i--;
            continue;
        }

        if(t_day == addDay) { target_value = 1; }

        if(target_value == 1) {
            edate_select.append($('<option>', {
                value: idx,
                text: idx + '박'
            }));

            t_day++;
        }
        else {
            break;
        }
    }
}

function make_sdate_select(date_limit, c_sdate, c_edate) {
    var sdate_select = $('#sdate');
    s_date_list = [];
    
    for(var i = 9; i <= 20; i++) {
        s_date_list.push(i.toString());
    }

    if(c_sdate != '') { c_sdate = parseInt(c_sdate.split(":")[0], 10); }
    if(c_edate != '') { c_edate = parseInt(c_edate.split(":")[0], 10); }

    if(date_limit) {
        var j = date_limit.length;
        for(var i = 0; i < j; i++) {
            var t_obj = date_limit[i];

            var t_sdate = '';
            var t_edate = '';
            for(var v in t_obj) {
                if(v == 'sdate') {
                    t_sdate = t_obj[v].toString();
                }
                if(v == 'edate') {
                    t_edate = t_obj[v].toString();
                }
            }

            // remove current sdate, edate
            if(c_sdate == t_sdate && c_edate == t_edate) {
                continue;
            }
            
            var jj = t_edate - t_sdate;

            for(var ii = 0; ii < jj; ii++) {
                var delete_sdate = parseInt(t_sdate, 10) + ii;

                var index = s_date_list.indexOf(delete_sdate.toString());
                if (index >= 0) {
                  s_date_list.splice( index, 1 );
                }    
            }
        }
    }

    sdate_select.empty();
    sdate_select.append($('<option>', {
        value: '',
        text: '시작시간'
    }));

    var j = s_date_list.length;
    for(var i = 0; i < j; i++) {
        var t_val = s_date_list[i];
    
        if(t_val.length == 1) { t_val = "0" + t_val; }

        sdate_select.append($('<option>', {
            value: t_val + ':00',
            text: t_val + ':00'
        }));
    }
}

function sdate_changed() {
    var sdate_select = $('#sdate');
    var edate_select = $('#edate');
    
    edate_select.empty();
    edate_select.append($('<option>', {
        value: '',
        text: '종료시간'
    }));

    var first_val = sdate_select.val();
    if(!first_val) { return; }

    if(first_val != '') { first_val = parseInt(first_val.split(":")[0], 10); }

    var j = s_date_list.length;
    var last_t_val = 0;
    for(var i = 0; i < j; i++) {
        t_val = s_date_list[i];

        if(t_val < first_val) { continue; }

        t_val++;

        if(last_t_val == 0) { last_t_val = t_val; }
        else {
            if((last_t_val + 1) < t_val) {
                break;
            }
            else {
                last_t_val = t_val;
            }
        }
    
        if(t_val.length == 1) { t_val = "0" + i; }

        edate_select.append($('<option>', {
            value: t_val + ':00',
            text: t_val + ':00'
        }));
    }
}

function close_popup() {
    $('#centermodal').hide();
}

function alter_req(newThis) {
    var t_p = $(newThis).parent();
    var t_pr_id = t_p.find('[name=t_pr_id]').val();
    var t_day = t_p.find('[name=t_day]').val();
    var t_sdate = t_p.find('[name=t_sdate]').val();
    var t_edate = t_p.find('[name=t_edate]').val();
    var t_p_cnt = t_p.find('[name=t_p_cnt]').val();
    var t_tel = t_p.find('[name=t_tel]').val();
    var t_info = t_p.find('[name=t_info]').val();
    var t_status = t_p.find('[name=t_status]').val();
    var t_member = t_p.find('[name=t_member]').val();

    if(gubun == 'A') {
        var date_limit = JSON.parse(req_timetable)[t_day];
        make_sdate_select(date_limit, t_sdate, t_edate);
    }
    else {
        make_sdate_select_b(t_day, t_day);
    }

    if(t_pr_id) {
        $('[name=pr_id]').val(t_pr_id);
    }

    if(t_day) {
        $('[name=day]').val(t_day);
        $('.day_span').text(t_day);
    }
        
    if(t_sdate) {
        $('[name=sdate]').val(t_sdate);
    }

    if(gubun == 'A') {
        sdate_changed();

        if(t_edate) {
            $('[name=edate]').val(t_edate);
        }
    }
    else {
        if(t_edate) {
            $('[name=edate]').val(t_edate);
            edate_changed();
        }
    }

    if(t_p_cnt) {
        $('[name=p_cnt]').val(t_p_cnt);
    }

    var t_tel_ary = t_tel.split('-');

    if(t_tel_ary.length > 0 && t_tel_ary[0] != '') {
        $('[name=tel1]').val(t_tel_ary[0]);
    }
    if(t_tel_ary.length > 1 && t_tel_ary[1] != '') {
        $('[name=tel2]').val(t_tel_ary[1]);
    }
    if(t_tel_ary.length > 2 && t_tel_ary[2] != '') {
        $('[name=tel3]').val(t_tel_ary[2]);
    }

    if(t_info) {
        $('[name=cont]').val(t_info);
    }

    if(t_member) {
        $('.mb_nick').text(t_member);
    }

    $('.add_req_btn').val('편집하기');
    $('[name=w]').val('u');
    $('#centermodal').show();

    <?php if($is_admin != 'super') { ?>
        if(t_status == 'B') {
            $('.add_req_btn').hide();
        }
    <?php } ?>
}

function form_submit(newMethod, newValue) {
    if(newMethod == '1') {
        document.getElementById('req_form').submit();
    }
    else if(newMethod == '2') {
        var confirm_msg = '';
        if(newValue == '삭제하기') { confirm_msg = '삭제하시겠습니까?'; }
        if(newValue == '취소하기') { confirm_msg = '취소하시겠습니까?'; }
        
        var confirm_yn = confirm(confirm_msg);
        if(confirm_yn) {
            document.getElementById('req_form').action = './place_rental_req_write_delete.php';
            document.getElementById('req_form').submit();
        }
    }
}

function edate_changed() {
    var t_e_date = $('[name=edate] option:selected').val();
    if(t_e_date != '' && t_e_date !== undefined) {
        $('.end_date_text').show();

        var t_year = year;
        var t_month = month;
        var t_day = parseInt($('[name=day]').val(), 10) + parseInt(t_e_date, 10);

        if(t_day === undefined) { return; }

        if(t_day > last_day) {
            t_day = t_day - last_day;
            t_month++;
        }

        if(t_month > 12) {
            t_month = t_month - 12;
            t_year++;
        }
        
        $('.end_date_span').text(t_year + '-' + t_month + '-' + t_day);
    }
}

</script>
<!-- } 게시판 목록 끝 -->