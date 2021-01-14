<?php
if (!defined('_GNUBOARD_')) exit;

$begin_time = get_microtime();

#include_once(G5_PATH.'/head.sub.php');
include_once G5_PATH . '/adm/inc/top.gm';

function print_menu1($key, $no)
{
    global $menu;

    $str = print_menu2($key, $no);

    return $str;
}

function print_menu2($key, $no)
{
    global $menu, $auth_menu, $is_admin, $auth, $g5, $sub_menu;

    $str .= "<ul class=\"snb\">";
    for($i=1; $i<count($menu[$key]); $i++)
    {
        if ($is_admin != 'super' && (!array_key_exists($menu[$key][$i][0],$auth) || !strstr($auth[$menu[$key][$i][0]], 'r')))
            continue;

        if (($menu[$key][$i][4] == 1 && $gnb_grp_style == false) || ($menu[$key][$i][4] != 1 && $gnb_grp_style == true)) $gnb_grp_div = 'gnb_grp_div';
        else $gnb_grp_div = '';

        if ($menu[$key][$i][4] == 1) $gnb_grp_style = 'gnb_grp_style';
        else $gnb_grp_style = '';

        #gnb 클래스 넣는걸 무효화 //김호영 2017.10.19
        $gnb_grp_div = $gnb_grp_style = '';

        #submenu > .aon //김호영 2017.10.19
        $aon = '';
        if(substr($sub_menu, 0, 6) == substr($menu[$key][$i][0],0,6)){
            $aon = " aon ";
            #$aon = $sub_menu;
        }
        #$aon = substr($menu[$key][$i][0],0,6);

        $str .= '<li class="gnb_2dli"><a href="'.$menu[$key][$i][2].'" class="gnb_2da '. $aon .$gnb_grp_style.' '.$gnb_grp_div.'">'.$menu[$key][$i][1].'</a></li>';

        $auth_menu[$menu[$key][$i][0]] = $menu[$key][$i][1];
    }
    $str .= "</ul>";

    return $str;
}
?>
<style>
a.btn_frmline {
    margin: 0;
    padding: 0;
    border: 0;
    background: #c44c40;
    padding: 0 15px;
    border: 0;
    height: 30px;
    color: #fff;
    cursor: pointer;
    border-radius: 3px;
    font-weight: bold;
}
</style>
<script>
var tempX = 0;
var tempY = 0;

function imageview(id, w, h)
{

    menu(id);

    var el_id = document.getElementById(id);

    //submenu = eval(name+".style");
    submenu = el_id.style;
    submenu.left = tempX - ( w + 11 );
    submenu.top  = tempY - ( h / 2 );

    selectBoxVisible();

    if (el_id.style.display != 'none')
        selectBoxHidden(id);
}
</script>