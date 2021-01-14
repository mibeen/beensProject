<?php
  function SendMailDirectsend($subject, $body, $sender, $recipients) {
    if ($subject == "" || $body == "" || $sender == "" || $recipients == "") return;

    $ch = curl_init();

    /* 
     * subject  : 받을 mail 제목.
     * body  : 받을 mail 본문.
     * sender : 발송자 메일주소
     * sender_name : 발송자 이름
     * username : directsend 발급 ID
     * recipients : 발송 할 고객 이메일 , 로 구분함. ex) aaa@naver.com,bbb@nate.com (공백제거해서 입력해 주세요!!)
     * key : directsend 발급 api key
     * return_url : 실제 발송결과를 return 받을 URL
     * 
     * 각 번호가 유효하지않을 경우에는 발송이 되지 않습니다.
     */ 

    /* 여기서부터 수정해주시기 바랍니다. */
    
    // $subject = "[회원가입] 고객님 환영합니다.";
    // $body = "고객님 환영합니다.";
    // $sender = "no-reply@gill.center";
    $sender_name = "경기도평생교육진흥원";
    $username = "devgmooc";
    // $recipients = "test1@directsend.co.kr,test2@directsend.co.kr";
    $key = "xgvHmJDoS8Ylqx5";

    $bodytag = '1';  //HTML이 기본값 입니다. 메일 내용을 텍스트로 보내실 경우 주석을 해제 해주시기 바랍니다.

    // 실제 발송성공실패 여부를 받기 원하실 경우 주석을 해제하신 후 결과를 받을 URL을 입력해 주시기 바랍니다.
    $return_url = G5_PLUGIN_URL."/nx_mail/response.php";

    $open = 1;  // open 결과를 받으려면 주석을 해제 해주시기 바랍니다.
    $click = 1; // click 결과를 받으려면 주석을 해제 해주시기 바랍니다.
    $check_period = 3;  // 트래킹 기간을 지정하며 3 / 7 / 15 / 30 일을 기준으로 지정하여 발송해 주시기 바랍니다. (단, 지정을 하지 않을 경우 결과를 받을 수 없습니다.)
    // 아래와 같이 http://domain 일 경우 http://domain?type=[click | open | reject]&mail_id=[MailID]&email=[Email] 과 같은 형식으로 request를 보내드립니다.
    $option_return_url = "http://domain/";

    // 예약발송 파라미터 추가
    $mail_type = 'NORMAL'; // NORMAL - 즉시발송 / ONETIME - 1회예약 / WEEKLY - 매주정기예약 / MONTHLY - 매월정기예약 / YEARLY - 매년정기예약
    $start_reserve_time = date('Y-m-d H:i:s'); //  발송하고자 하는 시간
    $end_reserve_time = date('Y-m-d H:i:s'); //  발송이 끝나는 시간 1회 예약일 경우 $start_reserve_time = $end_reserve_time
    // WEEKLY | MONTHLY | YEARLY 일 경우에 시작 시간부터 끝나는 시간까지 발송되는 횟수 Ex) type = WEEKLY, start_reserve_time = '2017-05-17 13:00:00', end_reserve_time = '2017-05-24 13:00:00' 이면 remained_count = 2 로 되어야 합니다.
    $remained_count = 1;

    $agreement_text = '본메일은 [$NOW_DATE] 기준, 회원님의 수신동의 여부를 확인한 결과 회원님께서 수신동의를 하셨기에 발송되었습니다.';
    $deny_text = "메일 수신을 원치 않으시면 [" . '$DENY_LINK' . "]를 클릭하세요.\nIf you don't want this type of information or e-mail, please click the [" . '$EN_DENY_LINK' . "]";
    $sender_info_text = "사업자 등록번호:-- 소재지:ㅇㅇ시(도) ㅇㅇ구(군) ㅇㅇ동 ㅇㅇㅇ번지 TEL:--\nEmail: <a href='mailto:test@directsend.co.kr'>test@directsend.co.kr</a>";
    $logo_state = 1; // logo 사용시 1 / 사용안할 시 0
    $logo_path = 'http://logoimage.com/image.png';

    // 첨부파일의 URL을 보내면 DirectSend에서 파일을 download 받아 발송처리를 진행합니다. 파일은 개당5MB 이하로 발송을 해야 하며, 파일의 구분자는 '|(shift+\)'로 사용하며 5개까지만 첨부가 가능합니다.
    $file_url = 'http://domain/test.png|http://domain/test1.png';
    // 첨부파일의 이름을 지정할 수 있도록 합니다. 첨부파일의 이름은 순차적(http://domain/test.png - image.png, http://domain/test1.png - image2.png) 와 같이 적용이 되며, file_name을 지정하지 않은 경우 마지막의 파일의 이름이로 메일에 보여집니다.
    $file_name = 'image.png|image2.png';

    /* 여기까지 수정해주시기 바랍니다. */

    $subject = base64_encode($subject);
    $body = base64_encode($body);
    $sender_name = base64_encode($sender_name);

    $postvars = "subject=" . urlencode($subject)
      . "&body=" . urlencode($body)
    . "&sender=" . urlencode($sender)
      . "&sender_name=" . urlencode($sender_name)
    . "&username=" . urlencode($username)
    . "&recipients=" . urlencode($recipients)

    // 예약 관련 파라미터 사용할 경우 주석 해제
  //  . "&mail_type=" . urlencode($mail_type)
  //  . "&start_reserve_time=" . urlencode($start_reserve_time)
  //  . "&end_reserve_time=" . urlencode($end_reserve_time)
  //  . "&remained_count=" . urlencode($remained_count)

    // 필수 안내문구를 추가할 경우 주석 해제
  //  . "&agreement_text=" . urlencode(base64_encode($agreement_text))
  //  . "&deny_text=" . urlencode(base64_encode($deny_text))
  //  . "&sender_info_text=" . urlencode(base64_encode($sender_info_text))
  //  . "&logo_state=" . urlencode($logo_state)
  //  . "&logo_path=" . urlencode($logo_path)

    // 메일 발송결과를 받고 싶은 URL 
   . "&return_url=" . urlencode($return_url) // return_url이 있는 경우 주석해제 바랍니다.

    // 발송결과 측정 항목을 사용할 경우 주석 해제
  //  . "&option_return_url=" . urlencode($option_return_url)
  //  . "&open=" . urlencode($open)
  //  . "&click=" . urlencode($click)
  //  . "&check_period=" . urlencode($check_period)

    // 첨부파일이 있는 경우 주석 해제
  //  . "&file_url=" . urlencode($file_url)
  //  . "&file_name=" . urlencode($file_name)

      // 메일 내용 텍스트로 보내실 경우 주석 해제
  //  . "&bodytag=" . urlencode($bodytag)
    . "&key=" . urlencode($key);


    // return_url이 없는 경우 사용하는 URL
    // $url = "https://directsend.co.kr/index.php/api/v2/mail/utf8"; // Server의 인코딩이 utf8일때 사용하세요.
    //$url = "https://directsend.co.kr/index.php/api/v2/mail";  // Server의 인코딩이 euc-kr일때 사용하세요.

    // return_url이 있을 경우 사용하는 URL
    $url = "https://directsend.co.kr/index.php/api/result_v2/mail/utf8";  // Server의 인코딩이 utf8일때 사용하세요.
    //$url = "https://directsend.co.kr/index.php/api/result_v2/mail"; // Server의 인코딩이 euc-kr일때 사용하세요.

    curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_POST, true);
    curl_setopt($ch,CURLOPT_POSTFIELDS, $postvars);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,3);
    curl_setopt($ch,CURLOPT_TIMEOUT, 20);
    $response = curl_exec($ch);
   
    /* 
    * response의 실패
    *  {"status":101} 
    */
      
    /* 
    * response 성공
    * {"status":0}
    * 성공 코드번호.
    */
      
    /* 
        status code
          0   : 정상발송
          100 : POST validation 실패
          101 : 회원정보가 일치하지 않음
          102 : subject, message 정보가 없습니다.
          103 : sender 이메일이 유효하지 않습니다.
          104 : recipient 이메일이 유효하지 않습니다.
          105 : 본문에 포함하면 안되는 확장자가 있습니다.
          106 : body validation 실패
          107 : 받는 사람이 없습니다.
    109 : return_url이 없습니다.
    110 : 첨부파일이 없습니다.
    111 : 첨부파일의 개수가 5개를 초과합니다.
    112 : 첨부파일의 Size가 5MB를 초과합니다.
    113 : 첨부파일이 다운로드 되지 않았습니다.
          205 : 잔액부족
          999 : Internal Error.
      */ 

    // print $response;
    curl_close ($ch); 
  }
?>
