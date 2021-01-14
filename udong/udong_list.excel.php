<?php

include_once("../common.php");
include_once(G5_LIB_PATH.'/apms.thema.lib.php');

if ( $is_admin != "super" ) {
	goto_url("/");
}

header('Content-Type:text/csv;charset=UTF-8;');


$cond =  "WHERE  1=1 AND lp.lp_ddate is null And lp.lp_use_yn = 'Y'";

$sql = " Select lp.lp_name, lp.lp_tel, lp.lp_email, lp.lp_address, lp.lp_intro, lp.lp_info
				, la.la_name
			From local_place As lp
				Inner Join local_place_area As la On la.la_idx = lp.la_idx {$cond}";
$item = sql_query($sql);

$ret = array();
while ($row = sql_fetch_array($item)) {

	$row["lp_intro"] = trim(strip_tags($row["lp_intro"]));
	$row["lp_info"] = trim(strip_tags($row["lp_info"]));

	if(strpos($row["lp_intro"], ',') !== false || strpos($row["lp_intro"], '"') !== false || strpos($row["lp_intro"], "\n") !== false) {
        //$row["lp_intro"] = '"' . str_replace('"', '""', $row["lp_intro"]) . '"';
    }
	
	if(strpos($row["lp_info"], ',') !== false || strpos($row["lp_info"], '"') !== false || strpos($row["lp_info"], "\n") !== false) {
        //$row["lp_info"] = '"' . str_replace('"', '""', $row["lp_info"]) . '"';
    }

	$row["lp_intro"] = str_replace('-', ' -', $row["lp_intro"]);
	$row["lp_info"] = str_replace('-', ' -', $row["lp_info"]);


	if (!$row["lp_email"]) {
		$row["lp_email"] = '';
	}

	$ret[] = $row;
}


download_send_headers("우리동네 학습공간_" . date("Y-m-d") . ".csv");
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
