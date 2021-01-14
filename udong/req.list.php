<?php
    include_once('./_common.php');

    if($is_guest) {
        alert('로그인 후 이용 가능 합니다.', './place.read.php?lp_idx='.$lp_idx);
    }

    include_once('../_head.php');


    # set : variables
    $sar = $_GET['sar'];
    $year = $_GET['year'];
    $month = $_GET['month'];


    # re-define
    $sar = preg_replace('/[^0-9]/', '', $sar);
    $year = ($year != '') ? preg_replace('/[^0-9]/', '', $year) : date('Y');
    $month = ($month != '') ? preg_replace('/[^0-9]/', '', $month) : date('n');


    $qstr = '';

    if ($sar) $qstr .= '&amp;sar=' . urlencode($sar);


    # 필요한 날짜, 시간 정의
    $today = date('Y-m-d');
    $tomorrow = date('Y-m-d',strtotime($today . "+1 days"));
    $last_day = date("t", strtotime($year . '-' . $month . '-1'));
    $now_hour = date('G');


    $prev_year = $year;
    $next_year = $year;
    $next_month = $month + 1;
    if($next_month >= 13) {
        $next_month = 1;
        $next_year = $year + 1;
    }
    $prev_month = $month - 1;
    if($prev_month <= 0) {
        $prev_month = 12;
        $prev_year = $year - 1;
    }


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


    // list image
    $sql = "Select bf_file, bf_source From {$g5['board_file_table']} Where bo_table = '{$_file_table}' And wr_id = '" . mres($lp_idx) . "' Order By bf_no Asc limit 1";
    $_FL = sql_fetch($sql);

    $LP['bf_file'] = $_FL['bf_file'];
    $LP['bf_source'] = $_FL['bf_source'];

    unset($_FL);


    # get : 이번 달의 예약
    for ($i = 1; $i <= $last_day; $i++) {
        $sql = "Select lr.*"
            ."          , m.mb_nick"
            ."      From local_place_req As lr"
            ."          Inner Join g5_member As m On m.mb_id = lr.mb_id"
            ."      Where lr.lp_idx = '" . mres($lp_idx) . "'"
            ."          And year(lr.lr_sdate) = '" . mres($year) . "'"
            ."          And month(lr.lr_sdate) = '" . mres($month) . "'"
            ."          And day(lr.lr_sdate) = '" . mres($i) . "'"
            ."          And lr.lr_status In ('A', 'B')"
            ."          And lr.lr_ddate is null"
            ."      Order by lr.lr_sdate";

        $result_req = sql_query($sql);
        $day_list[] = $result_req;
    }

    $day_cnt = count($day_list);
    $week_array = array('', '월', '화', '수', '목', '금', '토', '일');


    //날짜선택기
    apms_script('datepicker');

    $is_modal_detail = apms_script('modal');
    $is_modal_js = apms_script('modal_pop');
?>

<div class="data_ct">
    <?php
        include "./inc.page.title.php";
    ?>

    <div class="coron_reserve_wrap mt40">
        <div class="info">
            <?php /*<p class="region"><?php echo $LP['la_name']; ?></p>*/?>
            <p class="tit"><?php echo $LP['lp_name']; ?></p>
            <div class="img_wrap">
                <?php
                # 썸네일 생성
                $thumb = thumbnail($LP['bf_file'], G5_PATH."/data/file/{$_file_table}", G5_PATH."/data/file/{$_file_table}", 480, 270, true);

                if (!is_null($thumb)) {
                    echo '<img src="/data/file/'.$_file_table.'/'.$thumb.'" alt="'.htmlspecialchars($LP['bf_source']).'" class="img" />';
                }
                else {
                    echo '<div class="empty_img"></div>';
                }
                unset($thumb);
                ?>
            </div>
        </div>

        <div class="lst_wrap">
            <div>
                <div class="go_list">
                    <a role="button" href="./place.read.php?lp_idx=<?php echo($lp_idx)?><?php echo($qstr)?>" class="btn btn-black btn-sm">
                        <i class="fa fa-chevron-left "></i><span class="hidden-xs"> 뒤로</span>
                    </a>
                </div>
                <div id="date-area" class="date">
                    <span class="vaM"><?php echo $year?>년 <?php echo $month?>월</span> 
                    <a href="./req.list.php?lp_idx=<?php echo $lp_idx?>&amp;year=<?php echo $prev_year?>&amp;month=<?php echo $prev_month?>"><</a>
                    <span id="req_datepicker" class="dsIB psR input-group">
                        <span class="input-group-addon">
                            <img src="../thema/hub/assets/imgs/ico/ico_calendar.png" alt="" class="vaM" />
                        </span>
                        <input type="hidden" id="req_datepicker2"">
                    </span>
                    <a href="./req.list.php?lp_idx=<?php echo $lp_idx?>&amp;year=<?php echo $next_year?>&amp;month=<?php echo $next_month?>">></a>
                </div>
            </div>
            <table cellspacing="0" cellpadding="0" border="0" class="lst">
                <colgroup>
                    <col width="70px" /><col width="" /><col width="100px" />
                </colgroup>
                <?php
                for ($i = 0; $i < $day_cnt; $i++) {
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

                    $c_date = date('Y-m-d', strtotime($year . '-' . $month . '-' . $idx));

                    $req_time = $time_val_gap; // 예약시간이 가득차면 $req_time이 0이 되어 신청버튼 보여지지 않게함

                    $show_link_yn = true;
                    $my_req_cnt = 0;

                    # 오늘 포함 이전날짜 신청 불가, 18시 이후에는 익일 신청 불가
                    if($today >= $c_date || ($tomorrow == $c_date && $now_hour >= 18)) {
                        $show_link_yn = false;
                    }
                    ?>
                <tr>
                    <td align="center" <?php if($holiday_yn == 1) { echo('class="holiday"'); } ?>><?php echo $day; ?></td>

                    <td>
                        <?php
                        for ($k = 0; $row=sql_fetch_array($day_list[$i]); $k++)
                        {
                            if($k == 0) { 
                                echo '<ul>';
                            }

                            $req_sdate = date('H:i', strtotime($row['lr_sdate']));
                            $req_edate = date('H:i', strtotime($row['lr_edate']));
                            $req_status = $row['lr_status'];


                            # 모든 시간이 예약되었는지 확인
                            $_shour = date('G', strtotime($row['lr_sdate']));
                            $_smin = date('i', strtotime($row['lr_sdate']));
                            $_stime_val = ($_shour * 60) + (int)$_smin;

                            $_ehour = date('G', strtotime($row['lr_edate']));
                            $_emin = date('i', strtotime($row['lr_edate']));
                            $_etime_val = ($_ehour * 60) + (int)$_emin;

                            $req_time = $req_time - ($_etime_val - $_stime_val);


                            # 관리자가 아니면 자신의 이름만 보임
                            $t_mb_nick = 'OOO';
                            if (!$member['admin']) {
                                if ($row['mb_id'] == $member['mb_id']) {
                                    $t_mb_nick = $member['mb_nick'];
                                }
                            }
                            else {
                                $t_mb_nick = $row['mb_nick'];
                            }


                            # 자신의 예약 카운트
                            if ($row['mb_id'] == $member['mb_id']) {
                                $my_req_cnt++;
                            }


                            # 예약 내역 출력 설정
                            $req_title = ($k+1) . '. ' . $t_mb_nick. ' (' . $req_sdate . '~' . $req_edate . ')';

                            $req_status_title = '';
                            if($req_status == 'A') { 
                                $req_status_title = '신청'; 
                                $req_status_str = 'apply';
                            }
                            else if($req_status == 'B') { 
                                $req_status_title = '승인';
                                $req_status_str = 'approve';
                            }


                            # 자신의 글만 상세/수정 가능
                            if ($row['mb_id'] == $member['mb_id'])
                            {
                                $link_href_tag = '<a href="./req.read.php?lp_idx='.$lp_idx.'&lr_idx='.$row['lr_idx'].'" class="data ' . $req_status_str . ' req_list_link req_list_type_'.$req_status.'" data-modal-title="학습공간 예약변경" '.$is_modal_js.'><span class="s1">'.$req_title.'</span><span class="s2">'.$req_status_title.'</span></a>';
                            }
                            else {
                                $link_href_tag = '<span class="data ' . $req_status_str . ' req_list_link req_list_type_'.$req_status.'"><span class="s1">'.$req_title.'</span><span class="s2">'.$req_status_title.'</span></span>';
                            }

                            echo '<li>'.$link_href_tag.'</li>';
                        }
                        
                        if($k == 0) {
                            echo '<span>예약현황이 없습니다.</span>';
                        }
                        else {
                            echo '</ul>';
                        }
                        ?>
                    </td>

                    <td>
                        <?php
                        if($show_link_yn && $req_time != 0) {
                            if ($my_req_cnt > 0) {
                                echo '<a href="./req.add.php?lp_idx='.$lp_idx.'&year='.$year.'&month='.$month.'&day='.$idx.'" class="btn_reserve complete" data-modal-title="학습공간 예약하기" '.$is_modal_js.'>신청완료</a>';
                            }
                            else {
                                echo '<a href="./req.add.php?lp_idx='.$lp_idx.'&year='.$year.'&month='.$month.'&day='.$idx.'" class="btn_reserve" data-modal-title="학습공간 예약하기" '.$is_modal_js.'>신청하기</a>';
                            }
                        }
                        else {
                            echo('&nbsp;');                            
                        }
                        ?>
                    </td>
                </tr>
                    <?php
                }
                ?>
            </table>
        </div>
    </div>

</div>

<script>
function schedule_change() {
    var url;
    var selDate = $("#req_datepicker2").val();
    var strDate = selDate.split('-');

    if(strDate[1].substr(0,1) == "0") {
        strDate[1] = strDate[1].substr(1,1);
    }

    url = '?lp_idx=<?php echo($lp_idx)?>&year=' + strDate[0] + '&month=' + strDate[1];

    document.location.href = url;
}

$(function () {
    $('#req_datepicker').datetimepicker({
        viewMode: 'months',
        dayViewHeaderFormat: "YYYY년 MMMM",
        defaultDate: "<?php echo $year;?>-<?php echo sprintf("%02d",$month);?>",
        format: 'YYYY-MM',
        locale: 'ko',
        widgetParent: $('#date-area'),
        widgetPositioning: {
            horizontal: 'left',
            vertical: 'auto'
        }
    });

    $('#req_datepicker').on("dp.change",function (e) {
        schedule_change();
    });
});
</script>

<?php
# 메뉴 표시에 사용할 변수 
$_gr_id = 'gill';  // 게시판 그룹 ID - 현재 메뉴표시에 사용
$pid = ($pid) ? $pid : 'udong';   // Page ID

include "../page/inc.page.menu.php";
?>

<?php
include_once('../_tail.php');
?>