<?php

	include_once("../common.php");
    include_once(G5_LIB_PATH.'/apms.thema.lib.php');

    if ( $is_admin != "super" ) {
        goto_url("/");
    }
    
    header('Content-Type:text/csv;charset=UTF-8;');

	$g5['title'] = $fm['fm_subject'];


    # 리스트 조회
	$sql =   "SELECT 
                C.COURSE_ID,
                C.INST_ID,
                C.COURSE_URL,
                C.RECEIVE_START_DT,
                C.RECEIVE_END_DT,
                C.EDU_LOCATION_DESC,
                C.COURSE_START_DT,
                C.COURSE_END_DT,
                C.INQUIRY_TEL_NO,
                I.INST_NM, 
                I.SIGUNGU_CD, 
                I.INST_DESC,
                I.HOMEPAGE_URL, 
                COURSE_PTTN_CD, 
                IFNULL(SUBSTR(C.COURSE_NM, 1, (500/CAST(BIT_LENGTH(SUBSTR(C.COURSE_NM,1,1))/8 AS UNSIGNED))),'') AS COURSE_NM, 
                I.INST_NM, 
                I.HOMEPAGE_URL, 
                IFNULL(SUBSTR(C.ENROLL_AMT, 1, (200/CAST(BIT_LENGTH(SUBSTR(C.ENROLL_AMT,1,1))/8 AS UNSIGNED))),'') AS ENROLL_AMT, 
                REPLACE(C.EDU_QUOTA_CNT, '명', '') AS EDU_QUOTA_CNT 
            FROM   institutes AS I 
                JOIN   courses AS C ON   I.INST_ID = C.INST_ID 
	        WHERE  C.COLLECTED_DATE >= '2018.01.01' 
	        ORDER BY C.COLLECTED_DATE DESC";
    $item = sql_query($sql);
    
    $ret = array();
    while ($row = sql_fetch_array($item)) {

        $row["INST_DESC"] = strip_tags(trim($row["INST_DESC"]));

        if ($row["SIGUNGU_CD"] == '4105') $row["SIGUNGU_CD"] = "부천시";
        if ($row["SIGUNGU_CD"] == '4111') $row["SIGUNGU_CD"] = "수원시";
        if ($row["SIGUNGU_CD"] == '4113') $row["SIGUNGU_CD"] = "성남시";
        if ($row["SIGUNGU_CD"] == '4115') $row["SIGUNGU_CD"] = "의정부시";
        if ($row["SIGUNGU_CD"] == '4117') $row["SIGUNGU_CD"] = "안양시";
        if ($row["SIGUNGU_CD"] == '4121') $row["SIGUNGU_CD"] = "광명시";
        if ($row["SIGUNGU_CD"] == '4122') $row["SIGUNGU_CD"] = "평택시";
        if ($row["SIGUNGU_CD"] == '4125') $row["SIGUNGU_CD"] = "동두천시";
        if ($row["SIGUNGU_CD"] == '4127') $row["SIGUNGU_CD"] = "안산시";
        if ($row["SIGUNGU_CD"] == '4128') $row["SIGUNGU_CD"] = "고양시";
        if ($row["SIGUNGU_CD"] == '4129') $row["SIGUNGU_CD"] = "과천시";
        if ($row["SIGUNGU_CD"] == '4136') $row["SIGUNGU_CD"] = "남양주시";
        if ($row["SIGUNGU_CD"] == '4139') $row["SIGUNGU_CD"] = "시흥시";
        if ($row["SIGUNGU_CD"] == '4141') $row["SIGUNGU_CD"] = "군포시";
        if ($row["SIGUNGU_CD"] == '4143') $row["SIGUNGU_CD"] = "의왕시";
        if ($row["SIGUNGU_CD"] == '4145') $row["SIGUNGU_CD"] = "하남시";
        if ($row["SIGUNGU_CD"] == '4146') $row["SIGUNGU_CD"] = "용인시";
        if ($row["SIGUNGU_CD"] == '4150') $row["SIGUNGU_CD"] = "이천시";
        if ($row["SIGUNGU_CD"] == '4157') $row["SIGUNGU_CD"] = "김포시";
        if ($row["SIGUNGU_CD"] == '4159') $row["SIGUNGU_CD"] = "화성시";
        if ($row["SIGUNGU_CD"] == '4161') $row["SIGUNGU_CD"] = "광주시";
        if ($row["SIGUNGU_CD"] == '4163') $row["SIGUNGU_CD"] = "양주시";
        if ($row["SIGUNGU_CD"] == '4165') $row["SIGUNGU_CD"] = "포천시";
        if ($row["SIGUNGU_CD"] == '4180') $row["SIGUNGU_CD"] = "연천군";
        if ($row["SIGUNGU_CD"] == '4183') $row["SIGUNGU_CD"] = "양평군";
        if ($row["SIGUNGU_CD"] == '4137') $row["SIGUNGU_CD"] = "오산시";

        $ret[] = $row;
    }


    download_send_headers("우리동네 강좌정보_" . date("Y-m-d") . ".csv");
    echo array2csv($ret);
    die();



function array2csv(array &$array) {
   if (count($array) == 0) {
     return null;
   }
   ob_start();
   $df = fopen("php://output", 'w');
   fputcsv($df, array_keys(reset($array)));
   foreach ($array as $row) {
      fputcsv($df, $row);
   }
   fclose($df);
   return ob_get_clean();
}

function download_send_headers($filename) {
    // disable caching
    $now = gmdate("D, d M Y H:i:s");
    header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
    header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
    header("Last-Modified: {$now} GMT");

    // force download  
    header("Content-Type: application/force-download;charset=UTF-8;");
    header("Content-Type: application/octet-stream;charset=UTF-8;");
    header("Content-Type: application/download;charset=UTF-8;");

    // disposition / encoding on response body
    header("Content-Disposition: attachment;filename={$filename}");
    header("Content-Transfer-Encoding: binary");


    // Generate the server headers
    header('Expires: 0');
    
    echo "\xEF\xBB\xBF"; // 얠 넣어줘야 안깨짐...

}
?>