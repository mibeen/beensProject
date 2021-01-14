<?php


# get THEMA
$sql    = "SELECT data_1 FROM g5_apms_data WHERE type = 11 LIMIT 0, 1";
$result = sql_fetch($sql);

define(THEMA, $result['data_1']);


// 위젯 삽입 기능 메인화면 전용
function nx_widget_box($font = ' font-22 ')
{
    global $g5;

    #공통
    $func    = 'apms_widget';
    $inc_row = 1; // 현재 row
    $pre_row = 0; // 이전 row
    $widget  = '';

    # 테이블 확인
    $result = sql_query("show tables like 'nx_widget'", false);
    $rows   = sql_num_rows($result);

    if($rows < 1){
        #테이블 생성
        $tb_query = "
            CREATE TABLE `nx_widget` (
              `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
              `row` int(10) NOT NULL,
              `nx_order` int(10) NOT NULL DEFAULT 0,
              `title` varchar(255) NOT NULL DEFAULT '',
              `data` varchar(255) NOT NULL DEFAULT 'CMB-wm1',
              `name` varchar(255) NOT NULL DEFAULT 'basic-post-list',
              `option` varchar(255) NOT NULL DEFAULT '',
              `link` varchar(255) NOT NULL DEFAULT '',
              `target` varchar(255) NOT NULL DEFAULT '_self',
              `size` tinyint(2) NOT NULL DEFAULT 12,
              `size_md` int(2) NOT NULL DEFAULT 4,
              `size_sm` int(2) NOT NULL DEFAULT 4,
              `size_xs` int(2) NOT NULL DEFAULT 4,
              `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
              `created_by` varchar(255) NOT NULL,
              `deleted` varchar(1) NOT NULL DEFAULT 'N',
              `deleted_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
              `deleted_by` varchar(255) NOT NULL DEFAULT '',
              PRIMARY KEY (`id`)
            )
        ";
        $result = sql_query($tb_query, true);
        if(!$result) die(sql_error());
    }


    /*
     * Select dataese
     */
    $sql = "SELECT * FROM nx_widget WHERE deleted='N' ORDER BY row ASC, nx_order ASC";
    $result = sql_query($sql, true);

    # 가변 함수 실행
    for($i=0; $nx = sql_fetch_array($result); $i++){
        //같은 row일 때,
        $inc_row = $nx['row'];

        if($inc_row != $pre_row){
            //처음에는 row가 생성되고, 이후에는 nx[row]가 변경될 때마다 row를 닫은 후, 다시 row 생성한다.
            $widget .= $i == 0 ?  "<div class=\"row\">" : "<div class=\"clearfix\"></div></div><div class=\"row\">";
        }

        $col_class = " col-lg-".$nx['size']."  col-md-".$nx['size_md']."  col-sm-".$nx['size_sm']."  col-xs-".$nx['size_xs']." ";

        $view_more = strlen($nx['link']) > 1 ? "<a href=\"" . $nx['link'] . "\" target=\"" . $nx['target'] . "\" class=\"pull-right nx_btn2\">더보기</a>" : "";

        $view_title = "";

        if(strlen($nx['title']) > 1 || strlen($nx['link']) > 1 ){
            $view_title .= "
                    <div class=\"div-title-underbar clearfix\">
                        ". $view_more ."
                        <span class=\"div-title " . $font . "\">
                            <b>" . $nx['title'] . "</b>
                        </span>
                    </div>";
        }

        # 2017-11-17 Hoyeongkim
        # 위젯 종류 중, basic-title 이거나, basic-outlogin일 경우에는,
        # 타이을 및 아울라인 박스를 모두 나오지 않게 해 둔다.
        $widget_class = 'nx_widget_box';
        if($nx['name'] == 'basic-title' || $nx['name'] == 'basic-outlogin' || $nx['name'] == 'nx-widget-img'){
          $widget_class = 'nx_widget_box2';
          $view_title = "";
        }


        $widget .= "<div class=\"". $col_class ." res_sm\"><div class='".$widget_class."'>

                    <!-- " . $nx['title'] . " 시작 -->
                    ".$view_title."
                    <div class=\"widget-box\">
                        " . $func( $nx['name'] , $nx['data'] , 'height=260px', 'auto=0') . "
                    </div>
                    <!-- " . $nx['title'] . " 끝 -->
                    </div></div>";

        //prev update
        $pre_row = $inc_row;

    }
    $widget .= "<div class=\"clearfix\"></div></div>";

    //return $list;
    echo $widget;
}

//nx_widget_list($sql_query); 관리자에 위젯 삽입
function nx_widget_list($sql_data){

    $pre_row = 0;
    $widget_options = nx_get_widget();
    $target_options = array('_blank','_self','_parent','_top');

    $btn_addWidget  = "<button type='button' name='addWidget' class='btn btn-primary col-md-12'>새 위젯 추가</button>";
    $btn_addrows    = "<button type='button' name='addrows' class='col-md-12 btn btn-danger'>새 열 추가</button><div class='clearfix'></div>";

    $widget = $btn_addrows;

    for($i=0; $nx = sql_fetch_array($sql_data); $i++){

        $id         = $nx['id'];
        $row        = $nx['row'];
        $title      = $nx['title'];
        $data       = $nx['data'];
        $name       = $nx['name'];
        $option     = $nx['option'];
        $link       = $nx['link'];
        $target     = $nx['target'];
        $size       = $nx['size'];
        $respon_md  = $nx['size_md'];
        $respon_sm  = $nx['size_sm'];
        $respon_xs  = $nx['size_xs'];
        $created_at = $nx['created_at'];
        $created_by = $nx['created_by'];
        $inc_row = $row;

        if($inc_row != $pre_row){
            //처음에는 row가 생성되고, 이후에는 nx[row]가 변경될 때마다 row를 닫은 후, 다시 row 생성한다.
            $widget .= $i == 0 ?  "<div class=\"row\">" : "</div></div>".$btn_addrows."<div class=\"row\">";

            $widget .= "<h3 class='title'>".$row."번째 열</h3><div class='nx-dragable-wrap'>";
        }

        $widget .= "
            <div class='col-md-".$size." nx-wrap'>
              <input type='hidden' name='id[]' value='".$id."'>
              <input type='hidden' name='row[]' value='".$row."'>
              <div class='widget-box'>
                <div class=\"text-right\"><button type=\"button\" class=\"btn btn-default btn-close\"><i class=\"fa fa-times-circle\"></i></button></div>
                <div>
                    <label for='title' class='col-md-3'>위젯 이름</label>
                    <input type='text' name='title[]' value='".$title."' id='title' class='col-md-9'>
                    <div class=\"clearfix\"></div>
                </div>
                <div class=\"hidden\">
                    <label for='data' class='col-md-3'>데이터</label>
                    <input type='text' name='data[]' value='".$data."' class='col-md-9' placeholder='DB관련입니다. 입력하지 않으면 자동으로 들어갑니다.'>
                    <div class=\"clearfix\"></div>
                </div>
                <div>
                    <label for='name' class='col-md-3'>위젯 종류</label>
                    <select name='name[]' id='name' class='col-md-9'>
                    ".nx_option_sort($widget_options, $name)."
                    </select>
                    <div class=\"clearfix\"></div>
                </div>
                <div>
                    <label for='option' class='col-md-3'>옵션</label>
                    <input type='text' name='option[]' value='".$option."' class='col-md-9'>
                    <div class=\"clearfix\"></div>
                </div>
                <div>
                    <label for='link' class='col-md-3'>링크</label>
                    <input type='text' name='link[]' value='".$link."' class='col-md-5'>
                    <select name='target[]' id='target'>
                        ".nx_option_sort($target_options, $target)."
                    </select>
                    <div class=\"clearfix\"></div>
                </div>
                <div>
                    <label for='size[]' class='col-md-3'>사이즈 : (1199px~)</label>
                    <input type='number' name='size[]' value='".$size."' class='col-md-2'><div class='col-md-2'>/12</div>
                    <div class=\"clearfix\"></div>
                </div>
                <div>
                    <label for='respon_md[]' class='col-md-3'>사이즈 : (991px~)</label>
                    <input type='number' name='respon_md[]' value='".$respon_md."' class='col-md-2'><div class='col-md-2'>/12</div>
                    <div class=\"clearfix\"></div>
                </div>
                <div>
                    <label for='respon_sm[]' class='col-md-3'>사이즈 : (767px~)</label>
                    <input type='number' name='respon_sm[]' value='".$respon_sm."' class='col-md-2'><div class='col-md-2'>/12</div>
                    <div class=\"clearfix\"></div>
                </div>
                <div>
                    <label for='respon_xs[]' class='col-md-3'>사이즈 : (480px~)</label>
                    <input type='number' name='respon_xs[]' value='".$respon_xs."' class='col-md-2'><div class='col-md-2'>/12</div>
                    <div class=\"clearfix\"></div>
                </div>

                <div class='btn-box'>".$btn_addWidget."</div>
                <div class=\"clearfix\"></div>

            </div></div>
        ";

        //prev update
        $pre_row = $inc_row;
    }

    $widget .= "</div></div>" . $btn_addrows;

    return $widget;
}

# 위젯 종류를 가져온다.
function nx_get_widget(){

    global $g5;

    $entrys = array();
    $path = G5_PATH . '/thema/'.THEMA.'/widget';

    $dirs = dir($path);
    while(false !== ($entry = $dirs->read())){
        if(($entry != '.') && ($entry != '..') && ($entry != 'scss')){
            $entrys['dir'][] = $entry;
        }else{
            $entrys['file'][] = $entry;
        }
    }

    $dirs->close();

    return $entrys['dir'];
}

function nx_option_sort($options, $data){

#    print_r($options);

    $widget_sort   = $options;
    $widget_option = '';
    for($i=0; $i<sizeof($widget_sort); $i++){
        if($data == $widget_sort[$i]){
            $widget_option .= "<option value='".$widget_sort[$i]."' selected>".$widget_sort[$i]."</option>";
        }else{
            $widget_option .= "<option value='".$widget_sort[$i]."'>".$widget_sort[$i]."</option>";
        }
    }

    return $widget_option;

}

/**
 * Lightens/darkens a given colour (hex format), returning the altered colour in hex format.7
 * @param str $hex Colour as hexadecimal (with or without hash);
 * @percent float $percent Decimal ( 0.2 = lighten by 20%(), -0.4 = darken by 40%() )
 * @return str Lightened/Darkend colour as hexadecimal (with hash);
 * https://gist.github.com/stephenharris/5532899
 */
function color_luminance( $hex, $percent ) {
    
    // validate hex string
    
    $hex = preg_replace( '/[^0-9a-f]/i', '', $hex );
    $new_hex = '#';
    
    if ( strlen( $hex ) < 6 ) {
        $hex = $hex[0] + $hex[0] + $hex[1] + $hex[1] + $hex[2] + $hex[2];
    }
    
    // convert to decimal and change luminosity
    for ($i = 0; $i < 3; $i++) {
        $dec = hexdec( substr( $hex, $i*2, 2 ) );
        $dec = min( max( 0, $dec + $dec * $percent ), 255 ); 
        $new_hex .= str_pad( dechex( $dec ) , 2, 0, STR_PAD_LEFT );
    }       
    
    return $new_hex;
}

function color_change($hex, $num1=0, $num2=0, $num3=0){

    // validate hex string
    
    $hex = preg_replace( '/[^0-9a-f]/i', '', $hex );
    $new_hex = '#';
    
    $red   = hexdec(substr($hex,0,2));
    $green = hexdec(substr($hex,2,2));
    $blue  = hexdec(substr($hex,4,2));


    $new_r = (($red+$num1) <= 255 && ($red+$num1) >= 0 ) ? $red+$num1 : $red;
    $new_g = (($green+$num2) <= 255 && ($green+$num2) >= 0 ) ? $green+$num2 : $green;
    $new_b = (($blue+$num3) <= 255 && ($blue+$num3) >= 0 ) ? $blue+$num3 : $blue;

    $new_hex .= dechex($new_r) . dechex($new_g) . dechex($new_b);

    return $new_hex;
}


// category를 가져온다.
function nx_get_category(){

    global $menu;

    $category = "<i class=\"fa fa-home\"></i>";

    for($i=0; $i < count($menu); $i++){
        if($menu[$i]['on'] == 'on' ){
                
            $category .= " / " . $menu[$i]['name'];


            //depth2
            for($m=0; $m < count($menu[$i]['sub']); $m++){
                //echo $m;
                if($menu[$i]['sub'][$m]['on'] == 'on'){
                    //2depth를 리턴한다.
                    $category .= " / " .  $menu[$i]['sub'][$m]['name'];
                }
            }
            //depth2 end

            return $category;
        }
    }
}

?>