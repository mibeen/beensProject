<?php
include_once('./_common.php');
$nx_id = $_GET['popup_no'];

$sql = " SELECT * FROM {$g5['new_win_table']}
          WHERE '".G5_TIME_YMDHIS."' BETWEEN nw_begin_time AND nw_end_time
            AND nw_device IN ( 'both', 'pc' )
            AND nw_id = '{$nx_id}'
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
    $nw_href          = $nw['nw_href'];
    $nw_target        = $nw['nw_target'];

    switch ($nw_view_status) {
        case 'window':
            # 윈도우 레이어일 때, Default입니다.


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

        default :
        	die('No data');
        	break;
    }

}


include_once G5_PATH . "head.php";
echo "<style>*{padding:0;margin:0;}.hd_pops_footer{bottom:0px;left:-1px;right:0;position: absolute;}</style>";
echo "<link rel=\"stylesheet\" href=\"/css/default.css?ver=161101\">";
echo $popup_layer;

if($i == 0){
	echo "<script>window.close();</script>";
}

?>
<script>
// 자바스크립트에서 사용하는 전역변수 선언
var g5_url       = "<?php echo G5_URL ?>";
var g5_bbs_url   = "<?php echo G5_BBS_URL ?>";
var g5_is_member = "<?php echo isset($is_member)?$is_member:''; ?>";
var g5_is_admin  = "<?php echo isset($is_admin)?$is_admin:''; ?>";
var g5_is_mobile = "<?php echo G5_IS_MOBILE ?>";
var g5_bo_table  = "<?php echo isset($bo_table)?$bo_table:''; ?>";
var g5_sca       = "<?php echo isset($sca)?$sca:''; ?>";
var g5_pim       = "<?php echo APMS_PIM ?>";
var g5_editor    = "<?php echo ($config['cf_editor'] && $board['bo_use_dhtml_editor'])?$config['cf_editor']:''; ?>";
var g5_responsive    = "<?php echo (_RESPONSIVE_) ? 1 : '';?>";
var g5_cookie_domain = "<?php echo G5_COOKIE_DOMAIN ?>";
<?php if($is_admin || defined('G5_IS_ADMIN')) { ?>
var g5_admin_url = "<?php echo G5_ADMIN_URL; ?>";
<?php } ?>
var g5_purl = "<?php echo $seometa['url']; ?>";
</script>
<script src="/js/jquery-1.11.3.min.js"></script>
<script src="/js/common.js"></script>
<script src="/gseek/jquery.cookie.js"></script>
<script>
$(function() {
    $(".hd_pops_reject").click(function() {
        var id = $(this).attr('class').split(' ');
        var ck_name = id[1];
        var exp_time = parseInt(id[2]);
        $("#"+id[1]).css("display", "none");
        set_cookie(ck_name, 1, exp_time, g5_cookie_domain);
        window.close();
    });
    $('.hd_pops_close').click(function() {
        //console.log('cl');
        var idb = $(this).attr('class').split(' ');
        $('#'+idb[1]).css('display','none');
        $(this).closest('.hd_pops').css('display','none');
        window.close();
    });
    $("#hd").css("z-index", 1000);

    /*$('.btn_cls_ban2').on('click', function(e){
        $(this).closest('.popup-top').css('display','none');
    })*/
    });
</script>
