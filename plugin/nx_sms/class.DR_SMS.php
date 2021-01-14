<?php
    #---------- about : DirectSend_SMS ----------#
    class DR_SMS
    {
		public static function SEND($v=array())
		{
			# set : variables
			$SCHEDULE_TYPE		= $v['SCHEDULE_TYPE']; //타입을 받으나 사용하지 않음
			$SMS_MSG			= $v['SMS_MSG'];
			$SEND_TIME			= $v['SEND_TIME'];
			$CALLBACK			= $v['CALLBACK'];
			$CALLEE_NO			= $v['CALLEE_NO'];


			# re-define
			$SCHEDULE_TYPE = (in_array($SCHEDULE_TYPE, array('0','1'))) ? $SCHEDULE_TYPE : '0';
			$SMS_MSG = ($SMS_MSG != "") ? $SMS_MSG : '';
			$SEND_TIME = ($SEND_TIME != "") ? $SEND_TIME : date("YmdHis");
			$CALLBACK = ($CALLBACK != "") ? $CALLBACK : '0315476500';   //발신번호 
			$CALLEE_NO = trim($CALLEE_NO); //수신번호


			# chk : rfv.
			if($SMS_MSG == "" || $CALLBACK == "" || $CALLEE_NO == "") return false;

            ?>
            <?php
//start
$ch = curl_init();


$title = "MMS/LMS 제목입니다.";
//$message = '[$NAME]님 알림 문자 입니다. 전화번호 : [$MOBILE] 비고1 : [$NOTE1] 비고2 : [$NOTE2] 비고3 : [$NOTE3] 비고4 : [$NOTE4] 비고5 : [$NOTE5] ';             //필수입력
$message = $SMS_MSG;             //필수입력
$sender = $CALLBACK;       //발신번호             //필수입력
$username = "devgmooc";       //다이렉트샌드 ID         //필수입력
$key = "xgvHmJDoS8Ylqx5";     //다이렉트샌드 KEY      //필수입력
$receiver = '{"mobile":"'.$CALLEE_NO.'"}';

$receiver = '['.$receiver.']';

// 주소록을 사용하길 원하실 경우 아래 주석을 해제하신 후, 사이트에 등록한 주소록 번호를 입력해주시기 바랍니다.
//$address_books = "0,1,2";      //사이트에 등록한 발송 할 주소록 번호 , 로 구분함 (ex. 0,1,2)

// 예약발송 정보 추가
$sms_type = 'NORMAL'; // NORMAL - 즉시발송 / ONETIME - 1회예약 / WEEKLY - 매주정기예약 / MONTHLY - 매월정기예약
$start_reserve_time = date('Y-m-d H:i:s'); //  발송하고자 하는 시간(시,분단위까지만 가능) (동일한 예약 시간으로는 200회 이상 API 호출을 할 수 없습니다.)
$end_reserve_time = date('Y-m-d H:i:s'); //  발송이 끝나는 시간 1회 예약일 경우 $start_reserve_time = $end_reserve_time
// WEEKLY | MONTHLY 일 경우에 시작 시간부터 끝나는 시간까지 발송되는 횟수 Ex) type = WEEKLY, start_reserve_time = '2017-05-17 13:00:00', end_reserve_time = '2017-05-24 13:00:00' 이면 remained_count = 2 로 되어야 합니다.
$remained_count = 1;
// 예약 수정/취소 API는 소스 하단을 참고 해주시기 바랍니다.

// 실제 발송성공실패 여부를 받기 원하실 경우 아래 주석을 해제하신 후, 사이트에 등록한 URL 번호를 입력해 주시기 바랍니다.
//$return_url_yn = TRUE;        //return_url 사용시 필수 입력
//$return_url = 33;            //개발서버 33  운영 36

/* 여기까지 수정해주시기 바랍니다. */
$message = str_replace(' ', ' ', $message);  //유니코드 공백문자 치환
$message = str_replace('"', '\'', $message);  //유니코드 공백문자 치환

//echo $message;

$postvars = '"title":"'.$title.'"';
$postvars = $postvars.', "message":"'.$message.'"';
$postvars = $postvars.', "sender":"'.$sender.'"';
$postvars = $postvars.', "username":"'.$username.'"';
$postvars = $postvars.', "receiver":'.$receiver.'';
$postvars = $postvars.', "key":"'.$key.'"';
$postvars = '{'.$postvars.'}';      //JSON 데이터

$url = "https://directsend.co.kr/index.php/api_v2/sms_change_word";         //URL

//헤더정보
$headers = array("cache-control: no-cache","content-type: application/json; charset=utf-8");

curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch,CURLOPT_POST, true);
curl_setopt($ch,CURLOPT_POSTFIELDS, $postvars);
curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,3);
curl_setopt($ch,CURLOPT_TIMEOUT, 20);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$response = curl_exec($ch);

/*
    * response의 실패
    * {"status":113, "msg":"UTF-8 인코딩이 아닙니다."}
    * 실패 코드번호, 내용
    *
    * status code 112 실패인 경우 인코딩 실패 문자열 return
    *  {"status":112, "msg": "message EUC-KR 인코딩에 실패 하였습니다.", "msg_detail":풰(13)}
    *  실패 코드번호, 내용, 인코딩 실패 문자열(문자열 위치)
*/

/*
    * response 성공
    * {"status":0}
    * 성공 코드번호 (성공코드는 다이렉트센드 DB서버에 정상수신됨을 뜻하며 발송성공(실패)의 결과는 발송완료 이후 확인 가능합니다.)
    *
    * 잘못된 번호가 포함된 경우
    * {"status":0, "msg":"유효하지 않는 번호를 제외하고 발송 완료 하였습니다.", "msg_detail":"error mobile : 01000000001aa, 010112"}
    * 성공 코드번호 (성공코드는 다이렉트센드 DB서버에 정상수신됨을 뜻하며 발송성공(실패)의 결과는 발송완료 이후 확인 가능합니다.), 내용, 잘못된 데이터
    *
*/

/*
    status code
    0   : 정상발송
    100 : POST validation 실패
    101 : sender 유효한 번호가 아님
    102 : receiver 유효한 번호가 아님
    103 : api key or user is invalid
    104 : recipient count = 0
    105 : message length = 0
    106 : message validation 실패
    107 : 이미지 업로드 실패
    108 : 이미지 갯수 초과
    109 : return_url이 없음
    110 : 이미지 용량 300kb 초과
    111 : 이미지 확장자 오류
    112 : euckr 인코딩 에러 발생
    113 : utf-8 인코딩 에러 발생
    114 : 예약정보가 유효하지 않습니다.
    200 : 동일 예약시간으로는 200회 이상 API 호출을 할 수 없습니다.
    201 : 분당 300회 이상 API 호출을 할 수 없습니다.
    205 : 잔액부족
    999 : Internal Error.
 */

//curl 에러 확인
if(curl_errno($ch)){
    echo 'Curl error: ' . curl_error($ch);
}else{
    // echo print_r($response);
}

curl_close ($ch);


/*
 * response 성공 json 데이터 양식
 * {result:1, message:"success", data:{~~}}
 *   data:total_count 예약 목록 전체 갯수
 *   data:list[~~]  예약 목록 정보
 *      번호	sms_reserve_no
 *      발생총건수	sms_reserve_count
 *      등록일	sms_reserve_date
 *      발송시작일	sms_reserve_startdate
 *      발송종료일	sms_reserve_enddate
 *      예약유형	sms_reserve_type       1 : 1회 / 2 : 매주 / 3 : 매달
 *      발송 총 건수	sms_send_count
 *      발송자 번호	sms_send_number
 *      문자 본문	sms_send_body
 *      인증여부	sms_reserve_auth_flag      0 : 비인증 / 1 : 인증
 *      문자 번호	sms_no
 *      MMS 이미지	mms_images
 *      바이트 수	byte

 * response 실패 json 데이터 양식
 * {result:200-1, message:에러 내용}

    status code
    1 : 성공
    200-1 : 파라미터 오류
    200-2 : 예약 목록 조회 오류

 * response 성공 json 데이터 양식
 * {result:1, message:success}
 */

/* ====================================== 예약 수정/취소 API 끝 ====================================== */
?>
            <?php 
		
			return true;
		}
	}
?>
