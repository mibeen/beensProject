<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$sql = " SELECT * FROM {$g5['new_win_table']}
          WHERE '".G5_TIME_YMDHIS."' BETWEEN nw_begin_time AND nw_end_time
            AND nw_device IN ( 'both', 'pc' )
          order by nw_id asc ";
$result  = sql_query($sql, false);

$popup_layer  = ""; //layer를 담는 그룹
$popup_window = ""; //window를 담는 그룹
$popup_top    = ""; //top을 담는 그룹
$theme_color  = "#000000"; //상단팝업 기본 색상

# 루프 시작
for ($i=0; $nw=sql_fetch_array($result); $i++){

    // 이미 체크 되었다면 Continue
    if ($_COOKIE["hd_pops_{$nw['nw_id']}"])
        continue;

    $nw_id            = $nw['nw_id'];
    $nw_device        = $nw['nw_device'];
    $nw_begin_time    = $nw['nw_begin_time'];
    $nw_end_time      = $nw['nw_end_time'];
    $nw_disable_hours = $nw['nw_disable_hours'];
    $nw_left          = $nw['nw_left'];
    $nw_top           = $nw['nw_top'];
    $nw_height        = $nw['nw_height'];
    $nw_width         = $nw['nw_width'];
    $nw_subject       = $nw['nw_subject'];
    $nw_content       = $nw['nw_content'];
    $nw_content_html  = $nw['nw_content_html'];
    $nw_background    = $nw['nw_background'];
    $nw_attachment    = $nw['nw_attachment'];
    $nw_view_status   = $nw['nw_view_status'];
    $template         = $nw['template'];
    $nx_href          = $nw['nx_href'];
    $nx_target        = $nw['nx_target'];

    switch ($nw_view_status) {
        case 'layer':
            # 팝업 레이어일 때, Default입니다.


            switch ($nw_attachment) {
                case 'A':
                    $attach   = 'initial';
                    $bgrepeat = 'no-repeat';
                    $onresize = 'none';
                    break;
                case 'B':
                    $attach   = 'cover';
                    $bgrepeat = 'no-repeat';
                    $onresize = 'both';
                    break;
                case 'C':
                    $attach   = 'initial';
                    $bgrepeat = 'repeat';
                    $onresize = 'both';
                    break;

                default:
                    $attach   = 'initial';
                    $bgrepeat = 'no-repeat';
                    $onresize = 'none';
                    break;
            }
            $nw_href = "";
            if(strlen($nw['nx_href']) > 1){
              $nw_href = "<a href=\"".$nw['nx_href']."\" target\"\" class=\"\" style=\"float:left;margin-left:5px;\"><i class=\"fa fa-link\"></i><span class=\"hidden-xs\"> 바로가기</span></a>";
            }

            $popup_layer .= "
                <div id=\"hd_pops_" . $nw['nw_id'] . "\" class=\"hd_pops\" style=\"top: " . $nw['nw_top'] . "px;left: " . $nw['nw_left']. "px; \">
                    <div class=\"hd_pops_con\" style=\"width: " . $nw['nw_width'] . "px;height: " . $nw['nw_height'] . "px; padding: ".$nw['nw_padding']."px; background-image:url(". $nw_background .");background-size:".$attach.";background-repeat: ". $bgrepeat . "\">
                         " . conv_content($nw['nw_content'], 1) . "
                    </div>
                    <div class=\"hd_pops_footer\">
                        {$nw_href}
                        <button style=\"\" class=\"hd_pops_reject hd_pops_" . $nw['nw_id'] . "  " . $nw['nw_disable_hours'] . "\"><i class=\"fa fa-times-circle\"></i><strong> " . $nw['nw_disable_hours'] . "</strong>시간 <span class=\"hidden-xs\">동안 다시 열람하지 않습니다.</span></button>
                        <button class=\"hd_pops_close hd_pops_" . $nw['nw_id'] . "\"><i class=\"fa fa-times-circle\"></i><span class=\"hidden-xs\"> 닫기</span></button>
                    </div>
                </div>
            ";
            break;

        case 'window':
            # 새 창 레이어일 때... 

            $popup_window .= "
                popup_open('".$nw_id."', '".$nw_width."','".$nw_height."','".$nw_left."','".$nw_top."');
            ";
            break;

        case 'top':
            # 상단 레이어일 때...
            # 상단 레이어는 한 개만 나오도록 한다. 마지막 1개만 들어가도록
            # 상단 레이어는 이미지 및 닫기 버튼만 들어간다.
            $popup_top = "<img src=\"".$nw_background."\" alt=\"\">";

            if( isset($nx_href) && strlen($nx_href) > 1){
                $popup_top = "<a href='" . $nx_href . "' target='" . $nx_target . "'>" . $popup_top . "</a>";
            }
            $popup_top .= "<button type=\"button\" class=\"hd_pops_close hd_pops_" . $nw['nw_id'] . " btn_cls_ban2\" id=\"top_email_banner_close\">닫기</button><button class=\"hd_pops_reject hd_pops_" . $nw['nw_id'] . "  " . $nw['nw_disable_hours'] . "\"><strong> " . $nw['nw_disable_hours'] . "</strong>시간 동안 다시 열람하지 않습니다.</button>";
            #$popup_top .= "<button type=\"button\" class=\"hd_pops_close hd_pops_" . $nw['nw_id'] . " btn_cls_ban2\" id=\"top_email_banner_close\">닫기</button>";

            $popup_top  = "<div class=\"popup-top-wrap\" id=\"hd_pops_" . $nw_id . "\">" . $popup_top . "</div>";
            break;

        default:
            # 기본 설정 : layer팝업과 동일...


            switch ($nw_attachment) {
                case 'A':
                    $attach   = 'initial';
                    $bgrepeat = 'no-repeat';
                    $onresize = 'none';
                    break;
                case 'B':
                    $attach   = 'cover';
                    $bgrepeat = 'no-repeat';
                    $onresize = 'both';
                    break;
                case 'C':
                    $attach   = 'initial';
                    $bgrepeat = 'repeat';
                    $onresize = 'both';
                    break;

                default:
                    $attach   = 'initial';
                    $bgrepeat = 'no-repeat';
                    $onresize = 'none';
                    break;
            }

            $popup_layer .= "
                <div id=\"hd_pops_" . $nw['nw_id'] . "\" class=\"hd_pops\" style=\"top: " . $nw['nw_top'] . "px;left: " . $nw['nw_left']. "px; background-image:url(". $nw_background .");background-size:".$attach.";background-repeat: ". $bgrepeat . "\">
                    <div class=\"hd_pops_con\" style=\"width: " . $nw['nw_width'] . "px;height: " . $nw['nw_height'] . "px; padding: ".$nw['nw_padding']."px\">
                         " . conv_content($nw['nw_content'], 1) . "
                    </div>
                    <div class=\"hd_pops_footer\">
                        <button class=\"hd_pops_reject hd_pops_" . $nw['nw_id'] . "  " . $nw['nw_disable_hours'] . "\"><strong> " . $nw['nw_disable_hours'] . "</strong>시간 동안 다시 열람하지 않습니다.</button>
                        <button class=\"hd_pops_close hd_pops_" . $nw['nw_id'] . "\">닫기</button>
                    </div>
                </div>
            ";
            break;

    }

}

?>

<!-- 윈도우 팝업 -->
<div class="popup-window">
    <script>
        function popup_open(num, width, height, top, left){
            window.open("/bbs/popup.php?popup_no=" + num ,"POPUPNAME_" + num, "width=" + width + ",height= " + height + ",toolbar=no, location=no, status=no, menubar=no, scrollbars=no, resizable=no, left= " + left + ", top=" + top);
        }

    <?php echo $popup_window; ?>
    </script>
</div>

<!-- 상단 팝업 -->
<div class="popup-top" style="background:<?php echo $theme_color ?>; text-align: center;">
    <?php echo $popup_top ?>
</div>

<!-- 레이어 팝업 -->
<div id="hd_pop">
    <h2>팝업레이어 알림</h2>

    <?php echo $popup_layer; ?>

<?php
if ($i == 0) echo '<span class="sound_only">팝업레이어 알림이 없습니다.</span>';
?>
</div>

<script>
$(function() {
    $(".hd_pops_reject").click(function() {
        var id = $(this).attr('class').split(' ');
        var ck_name = id[1];
        var exp_time = parseInt(id[2]);
        $("#"+id[1]).css("display", "none");
        set_cookie(ck_name, 1, exp_time, g5_cookie_domain);
    });
    $('.hd_pops_close').click(function() {
        //console.log('cl');
        var idb = $(this).attr('class').split(' ');
        $('#'+idb[1]).css('display','none');
        $(this).closest('.hd_pops').css('display','none');
    });
    $("#hd").css("z-index", 1000);

    /*$('.btn_cls_ban2').on('click', function(e){
        $(this).closest('.popup-top').css('display','none');
    })*/
    });
</script>
<!-- } 팝업레이어 끝 -->
