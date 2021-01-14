<?php
include_once('./_common.php');

if (!$is_designer)
    alert("관리자만 접근이 가능합니다.");

$type = $_POST['sw_type'];
$code = $_POST['sw_code'];
$thema = $_POST['sw_thema'];
$colorset = $_POST['colorset'];

if($type && $thema) {

	$str = apms_pack($_POST['at_set']);

	// 등록여부 체크
	$data = sql_fetch(" select * from {$g5['apms_data']} where type = '$type' and data_q = '$code' ");

	if($data['id']) {
		sql_query(" update {$g5['apms_data']} set data_set = '$str', data_1 = '$thema' where type = '$type' and data_q = '$code' ");
	} else {
		sql_query(" insert {$g5['apms_data']} set type = '$type', data_q = '$code', data_1 = '$thema', data_set = '$str' ");
	}

	// 컬러셋
	if($colorset) {
		if($type == "11") { //커뮤니티 기본 PC 컬러셋
			sql_query(" update {$g5['config_table']} set as_color = '{$colorset}' ");
		} else if($type == "12") { //커뮤니티 그룹 PC 컬러셋
			sql_query(" update {$g5['group_table']} set as_color = '{$colorset}' where gr_id = '{$code}' ");
		} else if($type == "13") { //커뮤니티 기본 모바일 컬러셋
			sql_query("update {$g5['config_table']} set as_mobile_color = '{$colorset}'");
		} else if($type == "14") { //커뮤니티 그룹 모바일 컬러셋
			sql_query(" update {$g5['group_table']} set as_mobile_color = '{$colorset}' where gr_id = '{$code}' ");
		} else if($type == "15") { //쇼핑몰 기본 PC 컬러셋
			sql_query(" update {$g5['g5_shop_default_table']} set as_color = '{$colorset}' ");
		} else if($type == "16") { //쇼핑몰 분류 PC 컬러셋
			sql_query(" update {$g5['g5_shop_category_table']} set as_color = '{$colorset}' where ca_id = '{$code}' ");
		} else if($type == "17") { //쇼핑몰 기본 모바일 컬러셋
			sql_query(" update {$g5['g5_shop_default_table']} set as_mobile_color = '{$colorset}' ");
		} else if($type == "18") { //쇼핑몰 분류 모바일 컬러셋
			sql_query(" update {$g5['g5_shop_category_table']} set as_mobile_color = '{$colorset}' where ca_id = '{$code}' ");
		}
	}
}

if ($url) {
	$link = urldecode($url);
} else  {
    $link = G5_URL;
}

goto_url($link);

?>