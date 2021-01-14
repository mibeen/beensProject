<?php
define('G5_IS_ADMIN', true);
include_once $_SERVER['DOCUMENT_ROOT'] . '/common.php';
include_once(G5_ADMIN_PATH.'/admin.lib.php');

if( isset($token) ){
    $token = @htmlspecialchars(strip_tags($token), ENT_QUOTES);
}
?>