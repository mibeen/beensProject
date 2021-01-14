<?php
$sub_menu = "990410";
include_once('./_common.php');

auth_check($auth[$sub_menu], "r");

$SC_START_DATE = $_GET['SC_START_DATE'];
$SC_END_DATE = $_GET['SC_END_DATE'];
$SC_WORD = $_GET['SC_WORD'];

# wh
$wh = "Where NM.NM_DDATE is null";

if($SC_START_DATE != "") {
    $wh .= " And NM.NM_WDATE >= '" . mres($SC_START_DATE) . "'";
}
if($SC_END_DATE != "") {
    $wh .= " And NM.NM_WDATE < '" . mres(date("Y-m-d", mktime(0, 0, 0, substr($SC_END_DATE, 5, 2), substr($SC_END_DATE, 8, 2) + 1, substr($SC_END_DATE, 0, 4)))) . "'";
}
if($SC_WORD != "") {
    $wh .= " And (NM.NM_NAME like '%" . mres($SC_WORD) . "%' Or NM.NM_EMAIL like '%" . mres($SC_WORD) . "%')";
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
$sql = "Select NM.*"
    ."	From NX_NEWSLETTER_MEMBER As NM"
        ."	{$wh}"
        ."	Order By NM.NM_IDX Desc"
            ;
$db1 = sql_query($sql);

if (sql_num_rows($db1) <= 0) {
    F_script("뉴스레터 구독자가 없습니다.", "history.back();");
}
else
{
    header("Content-Type: text/csv; charset=utf-8");
    header("Content-Disposition: attachment; fileName=".iconv('utf-8', 'euc-kr', '뉴스레터구독자')."_" . date("ymd") . ".csv");
    header("Content-Description: PHP" . phpversion() . " Generated Data");
    echo("\xEF\xBB\xBF");
 
    # header
    echo F_CSVSTR("NO.");
    echo "," . F_CSVSTR("이름");
    echo "," . F_CSVSTR("이메일");
    echo "," . F_CSVSTR("구독신청일");
    
    echo("\r\n");

    $name_arr = array();
    $email_arr = array();
    $wdate_arr = array();
    
    $s = 0;
    while ($rs1 = sql_fetch_array($db1))
    {
        echo F_CSVSTR($s + 1);
        echo "," . F_CSVSTR(F_hsc($rs1['NM_NAME']));
        echo "," . F_CSVSTR(F_hsc($rs1['NM_EMAIL']));
        echo "," . F_CSVSTR(F_hsc($rs1['NM_WDATE']));
        
        echo("\r\n");
        $s++;
    }
}

exit;
?>
