<?php
$sub_menu = "200610";
include_once('./_common.php');

auth_check($auth[$sub_menu], "r");

# 최고관리자만 열람 가능
if ($is_admin != 'super') {
    alert("최고관리자만 열람 가능합니다." ,G5_ADMIN_URL);
}

auth_check($auth[$sub_menu], "r");


$SC_PL_S_DATE = $_GET['SC_PL_S_DATE'];
$SC_PL_E_DATE = $_GET['SC_PL_E_DATE'];
$stx = $_GET['stx'];
$mb_name = $_GET['mb_name'];


#----- wh
$wh = "Where 1 = 1";

if ($SC_PL_S_DATE != '') {
    $wh .= " And DATE(PL.PL_WDATE) >= '" . mres($SC_PL_S_DATE) . "'";
}
if ($SC_PL_E_DATE != '') {
    $wh .= " And DATE(PL.PL_WDATE) <= '" . mres($SC_PL_E_DATE) . "'";
}

if ($stx != '') {
    $wh .= " And M.mb_id like '%" . mres($stx) . "%'";
}

if ($mb_name != '') {
    $wh .= " And M.mb_name like '%" . mres($mb_name) . "%'";
}

//echo"<script>console.log('".$wh."');</script>";
#####


/*	CSV 용 string 으로 변환
 - 전달된 값에 '"' 를 더함
 - 전달된 값의 '"' 를 '""' 로 치환
 */
function F_CSVSTR($v)
{
    if ($v == '') return;
    
    $ret = "\"" . str_replace("\"", "\"\"", $v) . "\"";
    return $ret;
}


#----- get : 예약 목록
$sql = "Select PL.PL_FILE_NAME, PL.PL_TASK, PL.PL_WDATE, PL.PL_WIP
			, M.mb_id, M.mb_name
    	From PRIVACY_LOG As PL
        		Inner Join g5_member As M on M.mb_id = PL.mb_id
            	{$wh}
            Order By PL_IDX Desc"
            ;
$db1 = sql_query($sql);

if (sql_num_rows($db1) <= 0) {
    F_script("개인정보 접근이력이 없습니다.", "history.back();");
}
else
{
    header("Content-Type: text/csv; charset=utf-8");
    header("Content-Disposition: attachment; fileName=".iconv('utf-8', 'euc-kr', '개인정보접근이력')."_" . date("ymd") . ".csv");
    header("Content-Description: PHP" . phpversion() . " Generated Data");
    echo("\xEF\xBB\xBF");
    
    $str_lr_status = ['A'=>'신청', 'B'=>'승인', 'C'=>'미승인', 'D'=>'승인취소'];
    
    $PL_FILE_NAME_STR = array(
        '/adm/member_list.php' => '회원 목록'
        , '/adm/member_list_update.php' => '회원 목록'
        , '/adm/member_form.php' => '회원 상세'
        , '/adm/member_form_update.php' => '회원 상세'
        , '/adm/evt/evt.join.list.php' => '사업관리 신청자'
        , '/adm/evt/evt.join.read.php' => '사업관리 신청자'
        , '/adm/evt/evt.attend.list.php' => '사업관리 참석자'
        , '/adm/evt/evt.attend.read.php' => '사업관리 참석자'
        , '/adm/evt/evt.attend.excel.php' => '사업관리 참석자'
        , '/adm/rent/place_rental_req_view.php' => '대관관리 예약'
        , '/adm/rent/place_rental_req_proc.php' => '대관관리 예약'
        , '/adm/newsletter/subscriber.list.php' => '뉴스레터 구독자'
        , '/adm/udong/place.req.read.php' => '우리동네 학습공간 예약'
        , '/adm/udong/req.timeline.php' => '우리동네 학습공간 전체현황'
    );
    $PL_TASK_STR = array('list' => '목록 열람', 'read' => '상세 열람', 'edit' => '수정', 'delete' => '삭제', 'excel'=>'엑셀다운로드');
    
    
    # header
    echo F_CSVSTR("NO.");
    echo "," . F_CSVSTR("아이디");
    echo "," . F_CSVSTR("이름");
    echo "," . F_CSVSTR("작업위치");
    echo "," . F_CSVSTR("수행작업");
    echo "," . F_CSVSTR("접속일자");
    echo "," . F_CSVSTR("접속IP");
    
    echo("\r\n");

    $id_arr = array();
    $file_arr = array();
    $task_arr = array();
    
    $s = 0;
    while ($rs1 = sql_fetch_array($db1))
    {
        $id = F_hsc($rs1['mb_id']);         
        $file = F_hsc($PL_FILE_NAME_STR[$rs1['PL_FILE_NAME']]);
        $task = F_hsc($PL_TASK_STR[$rs1['PL_TASK']]);
        
        if($id_arr[$id] == null){
            $id_arr[$id] = 1;
        }else{
            $id_arr[$id] = $id_arr[$id]+1;
        }
        if($file_arr[$file] == null){
            $file_arr[$file] = 1;
        }else{
            $file_arr[$file] = $file_arr[$file]+1;
        }
        if($task_arr[$task] == null){
            $task_arr[$task] = 1;
        }else{
            $task_arr[$task] = $task_arr[$task]+1;
        }
        
        echo F_CSVSTR($s + 1);
        echo "," . F_CSVSTR($id);
        echo "," . F_CSVSTR(F_hsc($rs1['mb_name']));
        echo "," . F_CSVSTR($file);
        echo "," . F_CSVSTR($task);
        echo "," . F_CSVSTR(F_hsc($rs1['PL_WDATE']));
        echo "," . F_CSVSTR(F_hsc(long2ip(sprintf("%d", $rs1['PL_WIP']))));
        
        echo("\r\n");
        $s++;
    }
    /*echo"<script>console.log('count : ".count($id_arr)."');</script>";
    $arrr = array_keys($id_arr);
    for($i=0 ; $i < count($id_arr) ; $i++){
        echo"<script>console.log('key".$i." : ".$arrr[$i]."');</script>";
        echo"<script>console.log('value : ".$id_arr[$arrr[$i]]."');</script>";
    }
   */
    echo("\r\n");
    echo F_CSVSTR("아이디");
    echo("\r\n");
    $arrr_id = array_keys($id_arr);
    for($i=0 ; $i < count($id_arr) ; $i++){
        echo F_CSVSTR($arrr_id[$i]). "," . F_CSVSTR($id_arr[$arrr_id[$i]]) . "\r\n";
    }
    
    echo("\r\n");
    echo F_CSVSTR("작업위치");
    echo("\r\n");
    $arrr_file = array_keys($file_arr);
    for($i=0 ; $i < count($file_arr) ; $i++){
        echo F_CSVSTR($arrr_file[$i]) . "," . F_CSVSTR($file_arr[$arrr_file[$i]]) . "\r\n";
    }
    
    echo("\r\n");
    echo F_CSVSTR("수행작업");
    echo("\r\n");
    $arrr_task = array_keys($task_arr);
    for($i=0 ; $i < count($task_arr) ; $i++){
        echo F_CSVSTR($arrr_task[$i]) . "," . F_CSVSTR($task_arr[$arrr_task[$i]]) . "\r\n";
    }
   
}

exit;
?>
