<?php

include_once "./_common.php";

$sql_table  = "nx_widget";
$sql_common = "";

print_r($_POST);

$id        = $_POST['id'];
$nx_row    = $_POST['row'];
$title     = $_POST['title'];
$data      = $_POST['data'];
$name      = $_POST['name'];
$option    = $_POST['option'];
$link      = $_POST['link'];
$target    = $_POST['target'];
$size      = $_POST['size'];
$respon_md = $_POST['respon_md'];
$respon_sm = $_POST['respon_sm'];
$respon_xs = $_POST['respon_xs'];

$widget_length = sizeof($id);

#
#삭제를 위해 기존 위젯을 가져온다.
$query = "SELECT 
			id 
		FROM 
			{$sql_table} 
		WHERE 
			deleted='N' 
		ORDER BY id ASC";
$result = sql_query($query, true);

#기존 ID값들.
$prev_row = array();
for($i=0; $row = sql_fetch_array($result); $i++){
	$prev_row[$i] = $row['id'];
}


# array_diff = '차집합';
$complement = array_values(array_diff($prev_row, $id));

if(sizeof($complement) > 0){
	//차집합의 개수가 1 이상이면,
	for($i=0; $i < sizeof($complement); $i++){
		//삭제 프로세스
		$query = "UPDATE {$sql_table} SET
					deleted = 'Y',
					deleted_at = '".date('Y-m-d H:i:s')."',
					deleted_by = '".$member['mb_id']."'
				  WHERE id = {$complement[$i]}
		";
		$result = sql_query($query, true);
	}
}

#
# 위젯 INSERT 및 UPDATE
for($i=0; $i < $widget_length; $i++){

	# ID값이 있는 건 기존 위젯(UPDATE), 없는 건 새로운 위젯(INSERT)

	if( !$id[$i] ){
		$query = "
			INSERT INTO {$sql_table} SET
				row = '{$nx_row[$i]}',
				title = '{$title[$i]}',
				data = '{$data[$i]}',
				name = '{$name[$i]}',
				option = '{$option[$i]}',
				link = '{$link[$i]}',
				target = '{$target[$i]}',
				size = '{$size[$i]}',
				size_md = '{$respon_md[$i]}',
				size_sm = '{$respon_sm[$i]}',
				size_xs = '{$respon_xs[$i]}',
				created_by = '".$member['mb_id']."',
				nx_order = {$i}+1
		";
	}else{
		$query = "
			UPDATE {$sql_table} SET
				row = '{$nx_row[$i]}',
				title = '{$title[$i]}',
				data = '{$data[$i]}',
				name = '{$name[$i]}',
				option = '{$option[$i]}',
				link = '{$link[$i]}',
				target = '{$target[$i]}',
				size = '{$size[$i]}',
				size_md = '{$respon_md[$i]}',
				size_sm = '{$respon_sm[$i]}',
				size_xs = '{$respon_xs[$i]}',
				created_by = '".$member['mb_id']."',
				nx_order = {$i}+1
			WHERE id = {$id[$i]} AND deleted='N'
		";
	}

	//echo $query;

	$insert = sql_query($query, true);

	####//$data 값이 없으면 ID값을 넣어서 새롭게 부여해준다.
	if(strlen($data[$i]) < 1){
		//데이타 값이 없으면
		if(!$id[$i]){
			$id[$i] = sql_insert_id();
		}

		$data[$i] = 'widget-' . $id[$i];

		$apms_query = "INSERT INTO g5_apms_data SET
			type = 100,
			data_q = 'widget-".$id[$i]."',
			data_1 = 'a:1:{s:4:\"rows\";s:1:\"4\";}',
			data_set = 'a:35:{s:7:\"thumb_w\";s:3:\"478\";s:7:\"thumb_h\";s:3:\"190\";s:6:\"shadow\";s:0:\"\";s:4:\"rows\";s:1:\"4\";s:4:\"page\";s:0:\"\";s:4:\"main\";s:0:\"\";s:4:\"item\";s:1:\"1\";s:2:\"lg\";s:1:\"1\";s:2:\"md\";s:1:\"1\";s:2:\"sm\";s:1:\"1\";s:2:\"xs\";s:1:\"1\";s:3:\"gap\";s:0:\"\";s:3:\"lgg\";s:0:\"\";s:3:\"mdg\";s:0:\"\";s:3:\"smg\";s:0:\"\";s:3:\"xsg\";s:0:\"\";s:4:\"gapb\";s:0:\"\";s:3:\"lgb\";s:0:\"\";s:3:\"mdb\";s:0:\"\";s:3:\"smb\";s:0:\"\";s:3:\"xsb\";s:0:\"\";s:4:\"line\";s:0:\"\";s:5:\"modal\";s:0:\"\";s:5:\"dtype\";s:0:\"\";s:7:\"bo_list\";s:7:\"banner1\";s:7:\"gr_list\";s:0:\"\";s:7:\"ca_list\";s:0:\"\";s:7:\"newtime\";s:0:\"\";s:3:\"new\";s:3:\"red\";s:4:\"sort\";s:0:\"\";s:4:\"rank\";s:0:\"\";s:4:\"term\";s:0:\"\";s:7:\"dayterm\";s:0:\"\";s:7:\"mb_list\";s:0:\"\";s:5:\"cache\";s:0:\"\";}'
		";
		sql_query($apms_query, true);

		$query = "UPDATE {$sql_table} SET 
					data = '{$data[$i]}'
				  WHERE id='{$id[$i]}'
		";
		sql_query($query, true);

	}
	####

}

goto_url("./wedget.php");


?>