<?php
$sub_menu = "990200";
include_once('./_common.php');

check_demo();

$c_ip = $_SERVER['REMOTE_ADDR'];

if ($_POST['act_button'] == "지역추가") {
	
	if ($is_admin != 'super')
        alert('지역 추가는 최고관리자만 가능합니다.');

	auth_check($auth[$sub_menu], 'w');

	$sql = " insert into {$g5['place_rental_area_table']}
                    set PA_NAME             = '{$_POST['PA_NAME']}',
                        PA_WDATE            = now(),
                        PA_WIP              = '{$c_ip}' ";
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

	        $sql = " update {$g5['place_rental_area_table']}
	                    set PA_NAME             = '{$_POST['PA_NAME'][$k]}',
	                        PA_MDATE            = now(),
	                        PA_MIP              = '{$c_ip}'
	                  where PA_IDX              = '{$_POST['PA_IDX'][$k]}' ";
	        sql_query($sql);
	    }

	} else if ($_POST['act_button'] == "선택삭제") {

	    if ($is_admin != 'super')
	        alert('지역 삭제는 최고관리자만 가능합니다.');

	    auth_check($auth[$sub_menu], 'd');

	    check_admin_token();

	    // _PLACE_DELETE_ 상수를 선언해야 board_delete.inc.php 가 정상 작동함
	    define('_PLACE_DELETE_', true);

	    for ($i=0; $i<count($_POST['chk']); $i++) {
	        // 실제 번호를 넘김
	        $k = $_POST['chk'][$i];

	        // include 전에 $bo_table 값을 반드시 넘겨야 함
	        $tmp_PA_IDX = trim($_POST['PA_IDX'][$k]);
	        include ('./place_delete.inc.php');
	    }
	} 
}

goto_url('./place_list.php?'.$qstr);
?>
