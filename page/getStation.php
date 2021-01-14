<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/common.php';

function addhttp($url) {
    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
        $url = "http://" . $url;
    }
    return $url;
}


# 지역명
$id    = clean_xss_tags($_POST['id']);
$page  = isset($_POST['page']) ? clean_xss_tags($_POST['page']) : 1 ;
$block = (int) 10; //블록 수

$start = ($page - 1) * $block;

if(!$id){
    die("Error : No Location Value");
}

#get all;
$sql    = "SELECT location, name, edu, tel, addr, web FROM gle_location WHERE deleted = 'N' AND location = '{$id}' ORDER BY name ASC";
$result = sql_query($sql);
$num    = sql_num_rows($result); // 총 row 수
$total  = (int) ceil($num / $block); //총 블록 수

#get Current
$sql    = "SELECT location, name, edu, tel, addr, web FROM gle_location WHERE deleted = 'N' AND location = '{$id}' ORDER BY name ASC LIMIT $start, 10";
$result = sql_query($sql);

$html   = "";

for($i=0; $row = sql_fetch_array($result) ; $i++){
    
    $location = $row['location'];
    $name     = $row['name'];
    $edu      = $row['edu'];
    $tel      = $row['tel'];
    $addr     = $row['addr'];
    $web      = $row['web'];
    
    if(strlen($web) > 1){
        $web = "<a href=\"".addhttp($web)."\" target=\"_blank\"><i class=\"fa fa-home\"></i></a>";
    }
    
    $html .= "
  <tr>
    <td> ". ($i+1) . " </td>
    <td> ". $name . " </td>
    <td> ". $edu . " </td>
    <td class=\"tel\"> ". $tel . " </td>
    <td class=\"addr\"> ". $addr . " </td>
    <td class=\"web\"> ". $web . " </td>
  </tr>
  ";
}

if( $num < 1 ){
    $html = "<tr><td colspan=\"6\" align=\"center\">설치된 센터가 없습니다.</tr>";
}

$pagelist =  apms_paging($block, $page, $total, '' );


$return['html'] = $html;
$return['paging'] = $pagelist;

#print_r($return);

echo json_encode($return, JSON_UNESCAPED_UNICODE);

?>
