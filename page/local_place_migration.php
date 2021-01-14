<?php
//if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가 
include_once("../bbs/_common.php");
include_once(G5_LIB_PATH.'/apms.thema.lib.php');
include_once('../bbs/_head.php');


$file_bo_table = 'local_place';


# 지역 정보를 지역명=>idx key/value 페어의 배열로 저장
$AREA = [];
$sql = "Select la_idx, la_name From local_place_area Where la_ddate is null";
$db1 = sql_query($sql);

while($rs1 = sql_fetch_array($db1)) {
	$AREA[$rs1['la_name']] = $rs1['la_idx'];
}


# 기존 우리동네 학습공간 정보
$sql = "Select * From g5_write_area";
$db1 = sql_query($sql);

while($rs1 = sql_fetch_array($db1)) {
	$la_idx = $AREA[$rs1['ca_name']];
	$mb_id = '';
	$lp_name = $rs1['wr_subject'];
	$lp_tel = $rs1['wr_2'];
	$lp_address = $rs1['wr_3'];
	$lp_intro = $rs1['wr_content'];
	$lp_info = $rs1['wr_1'];
	$lp_use_yn = 'Y';
	$lp_wdate = $rs1['wr_datetime'];
	$lp_wip = $rs1['wr_ip'];

	$sql = "Insert Into local_place (
				la_idx
				, mb_id
				, lp_name
				, lp_tel
				, lp_address
				, lp_intro
				, lp_info
				, lp_use_yn
				, lp_wdate
				, lp_wip
			) values (
				'" . mres($la_idx) . "'
				, ''
				, '" . mres($lp_name) . "'
				, '" . mres($lp_tel) . "'
				, '" . mres($lp_address) . "'
				, '" . mres($lp_intro) . "'
				, '" . mres($lp_info) . "'
				, '" . mres($lp_use_yn) . "'
				, '" . mres($lp_wdate) . "'
				, '" . mres($lp_wip) . "'
			)";
	sql_query($sql);


	# set : lp_idx
	$lp_idx = sql_insert_id();


	$sql = "Select * From g5_board_file Where bo_table = 'area' And wr_id = '" . mres($rs1['wr_id']) . "'";
	$db2 = sql_query($sql);

	while($rs2 = sql_fetch_array($db2)) {
		$sql = "Insert Into g5_board_file (
				bo_table
				, wr_id
				, bf_no
				, bf_source
				, bf_file
				, bf_download
				, bf_content
				, bf_filesize
				, bf_width
				, bf_height
				, bf_type
				, bf_datetime
			) values (
				'" . mres($file_bo_table) . "'
				, '" . mres($lp_idx) . "'
				, '" . mres($rs2['bf_no']) . "'
				, '" . mres($rs2['bf_source']) . "'
				, '" . mres($rs2['bf_file']) . "'
				, '" . mres($rs2['bf_download']) . "'
				, '" . mres($rs2['bf_content']) . "'
				, '" . mres($rs2['bf_filesize']) . "'
				, '" . mres($rs2['bf_width']) . "'
				, '" . mres($rs2['bf_height']) . "'
				, '" . mres($rs2['bf_type']) . "'
				, '" . mres($rs2['bf_datetime']) . "'
			)";
		sql_query($sql);

		// 원본파일을 복사하고 퍼미션을 변경
	    @copy(G5_DATA_PATH.'/file/area/'.$rs2['bf_file'], G5_DATA_PATH.'/file/'.$file_bo_table.'/'.$rs2['bf_file']);
	    // @chmod(G5_DATA_PATH.'/file/'.$file_bo_table.'/'.$rs2['bf_file'], G5_FILE_PERMISSION);
	}
}


include_once('../bbs/_tail.php');
?>