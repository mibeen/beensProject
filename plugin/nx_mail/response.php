<?php
  include '../../common.php';
  

  // MAIL API Return_url 샘플 예제입니다.

  // return값을 전달받아 기록할 log 위치를 지정합니다.

  // 발송결과값은 JOSON형식 POST값으로 전달됩니다.

  // 발송옵션 리턴값은 get방식으로 전달됩니다.


  $fp = @fopen(G5_DATA_PATH.'/log_directsend/log_mail'.date('Ymd'), 'a');

  if ($fp) {

    @fwrite($fp, print_r("Time : " . date("Y-m-d H:i:s",time()), true ) ."\n");

    @fclose($fp);

  }

   

  $fp = @fopen(G5_DATA_PATH.'/log_directsend/log_mail'.date('Ymd'), 'a');

  if ($fp && $_GET) {

    @fwrite($fp, print_r("Option return : ",true) ."\n");

    @fwrite($fp, print_r( $_GET, true ) ."\n\n");

    @fclose($fp);

  }

   

  if (isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {

    $_POST = array_merge($_POST, (array) json_decode(trim(file_get_contents('php://input')), true));

  }

   

  error_log(json_encode($_POST));

   

  $fp = @fopen(G5_DATA_PATH.'/log_directsend/log_mail'.date('Ymd'), 'a');

  if ($fp && $_POST) {

    @fwrite($fp, print_r("Sending result : ",true) ."\n");

    @fwrite($fp, print_r( json_encode($_POST), true ) ."\n\n\n");

    @fclose($fp);

  }
?>