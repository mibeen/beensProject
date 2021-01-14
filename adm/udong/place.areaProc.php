<?php
$sub_menu = "970100";
include_once('./_common.php');

check_demo();

$c_ip = $_SERVER['REMOTE_ADDR'];

if ($_POST['act_button'] == "지역추가") {
	
	if ($is_admin != 'super')
        alert('지역 추가는 최고관리자만 가능합니다.');

	auth_check($auth[$sub_menu], 'w');

	$sql = " insert into local_place_area
                    set la_name             = '" . mres($_POST['la_name']) . "',
                        la_wdate            = now(),
                        la_wip              = '{$c_ip}' ";
	sql_query($sql);

}
else {

	if (!count($_POST['chk'])) {
	    alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");
	}

	if ($_POST['act_button'] == "선택수정") {

	    auth_check($auth[$sub_menu], 'w');

	    for ($i=0; $i<count($_POST['chk']); $i++) {

	        // 실제 번호를 넘김
	        $k = $_POST['chk'][$i];

	        $sql = " update local_place_area
	                    set la_name             = '" . mres($_POST['la_name'][$k]) . "',
	                        la_mdate            = now(),
	                        la_mip              = '{$c_ip}'
	                  where la_idx              = '" . mres($_POST['la_idx'][$k]) . "'";
	        sql_query($sql);
	    }

	} else if ($_POST['act_button'] == "선택삭제") {

	    if ($is_admin != 'super')
	        alert('지역 삭제는 최고관리자만 가능합니다.');

	    auth_check($auth[$sub_menu], 'd');

	    check_admin_token();

	    // _PLACE_AREA_DELETE_ 상수를 선언해야 board_delete.inc.php 가 정상 작동함
	    define('_PLACE_AREA_DELETE_', true);

	    for ($i=0; $i<count($_POST['chk']); $i++) {
	        // 실제 번호를 넘김
	        $k = $_POST['chk'][$i];

	        // include 전에 $bo_table 값을 반드시 넘겨야 함
	        $tmp_la_idx = trim($_POST['la_idx'][$k]);
	        include ('./inc.place.area.delete.php');
	    }
	} 
}

goto_url('./place.area.php');
?>
