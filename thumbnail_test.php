<?php

include_once './_common.php'; //공통 함수
include_once(G5_LIB_PATH.'/thumbnail.lib.php'); 

$img = "2040074503_G8uSbslC_2452d498a61e24314ca5a3890e4d8afff85204a9.jpg"; 

#해당하는 
$source_path = G5_PATH . "/data/file/area"; 
$target_path = G5_PATH . "/data/file/area"; 


$thumb = thumbnail($img,$source_path,$target_path,80,50,true);

echo "<img src=\"/data/file/area/{$thumb}\" >";


?>
