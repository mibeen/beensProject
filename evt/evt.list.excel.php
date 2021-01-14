<?php

	include_once("../common.php");
    include_once(G5_LIB_PATH.'/apms.thema.lib.php');
	include_once(G5_LIB_PATH.'/thumbnail.lib.php'); //썸네일 라이브러리 로딩

    if ( $is_admin != "super" ) {
        goto_url("/");
    }
    
    header('Content-Type:text/csv;charset=UTF-8;');

	$g5['title'] = $fm['fm_subject'];


    # 리스트 조회
	$sql = "SELECT EM.*
				, (Select Count(EJ_IDX) From NX_EVENT_JOIN Where EM_IDX = EM.EM_IDX And EJ_DDATE is null) As CNT1
				, (Select Count(EJ_IDX) From NX_EVENT_JOIN Where EM_IDX = EM.EM_IDX And EJ_DDATE is null And EJ_STATUS = '2') As CNT2
				, (Select Count(EJ_IDX) From NX_EVENT_JOIN Where EM_IDX = EM.EM_IDX And EJ_DDATE is null And EJ_STATUS = '2' And (EJ_JOIN_CHK1 = 'Y' And EJ_JOIN_CHK2 = 'Y')) As CNT3
			From NX_EVENT_MASTER As EM
				Left Join {$g5['board_file_table']} As FL On FL.bo_table = 'NX_EVENT_MASTER' And FL.wr_id = EM.EM_IDX And FL.bf_no = '0' "
		;
    $item = sql_query($sql);
    
    $ret = array();
    while ($row = sql_fetch_array($item)) {
        $row["EM_CONT"] = strip_tags($row["EM_CONT"]);
        $ret[] = $row;
    }


    download_send_headers("모집 정보_" . date("Y-m-d") . ".csv");
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