<?php
	$sub_menu = "990100";
	include_once('./_common.php');

	
	# set : variables
	$nums = $_POST['nums'];


	# 전체 항목 pk 를 배열에 담음
	$sql = "Select ST_IDX From NX_SATI_TPL Order By ST_IDX Asc";
	$db1 = sql_query($sql);

	$db_pks = array();
	while ($rs1 = sql_fetch_array($db1)) {
		$db_pks[] = $rs1['ST_IDX'];
	}


	$_t = explode('|', $nums);
	for ($i = 0; $i < Count($_t); $i++)
	{
		$num = $_t[$i];
		if ($num == '') continue;


		# set : variables
		$ST_IDX = $_POST["ST_IDX_{$num}"];
		$ST_QUES = $_POST["ST_QUES_{$num}"];


		# re-define
		$ST_IDX = CHK_NUMBER($ST_IDX);


		# update
		if ($ST_IDX > 0) {
			$sql = "Update NX_SATI_TPL Set"
				."		ST_QUES = '" . mres($ST_QUES) . "'"
				."		, ST_MDATE = now()"
				."		, ST_MIP = inet_aton('" . mres($_SERVER['REMOTE_ADDR']) . "')"
				."	Where ST_IDX = '" . mres($ST_IDX) . "'"
				."	Limit 1"
				;

			# 전체 pk 배열에서 해당 value 제거
			if (($key = array_search($ST_IDX, $db_pks)) !== false) {
				array_splice($db_pks, $key, 1);
			}
		}
		# insert
		else {
			$sql = "Insert Into NX_SATI_TPL(ST_QUES, ST_WDATE, ST_WIP) values("
				."'" . mres($ST_QUES) . "'"
				.", now()"
				.", inet_aton('" . mres($_SERVER['REMOTE_ADDR']) . "')"
				.")"
				;
		}

		sql_query($sql);
	}


	# 삭제 대상 제거
	$_bo = false;
	for ($i = 0; $i < Count($db_pks); $i++)
	{
		$sql = "Delete From NX_SATI_TPL Where ST_IDX = '" . mres($db_pks[$i]) . "' Limit 1";
		sql_query($sql);

		$_bo = true;
	}

	# 삭제 한 항목이 있을 경우 table optimize
	if ($_bo) {
		$sql = "Optimize table `NX_SATI_TPL`";
		sql_query($sql);
	}


	echo_json(array(
		'success'=>(boolean)true,
		'msg'=>"저장되었습니다."
	));
?>
