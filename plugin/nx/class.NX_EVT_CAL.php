<?php
	#---------- about : NX_EVT_CAL ----------#
	class NX_EVT_CAL
	{
		public static function SET_ADD($v=array())
		{
			# set : variables
			$EM_IDX    = $v['EM_IDX'];
			$EM_TITLE  = $v['EM_TITLE'];
			$EM_S_DATE = $v['EM_S_DATE'];
			$EM_E_DATE = $v['EM_E_DATE'];
			$EM_CONT   = $v['EM_CONT'];


			# re-define
			$EM_IDX = ((int)$EM_IDX > 0) ? (int)$EM_IDX : '';


			# chk : rfv.
			if ($EM_IDX == '' || $EM_TITLE == '' || $EM_S_DATE == '' || $EM_E_DATE == '') return false;


			# Set default value;
			$wr_num = get_next_num("g5_write_calendar");
			$ca_name = "";
			$wr_option = 'html1';
			$wr_1 = date("Ymd", strtotime($EM_S_DATE));
			$wr_2 = date("Ymd", strtotime($EM_E_DATE));


			# 기타 (사용하지 않음)
			$wr_password = $member['mb_password'];
			$wr_name     = $member['mb_name'];
			$wr_email    = $member['mb_email'];
			$wr_homepage = "";
			$wr_writedate= date("Y-m-d H:i:s");
			for($i=4; $i < 11; $i++){
			$var = "wr_$i";
			}
			$as_re_mb    = $member['mb_id'];
			$as_re_name  = $member['mb_name'];
			$as_tag      = "";
			$as_map      = "";
			$as_icon     = "";
			$sql_ip = '';
			if (!$is_admin){
			$sql_ip = " , wr_ip = '{$_SERVER['REMOTE_ADDR']}' ";
			}
			$sql_password = $wr_password ? " , wr_password = '".get_encrypt_string($wr_password)."' " : "";

			//color = deepblue 고정.


			# insert
			$sql = "
					INSERT INTO 
						g5_write_calendar
					SET wr_num       = '$wr_num',
						wr_reply     = 0,
						wr_comment   = 0,
						ca_name      = '$ca_name',
						wr_option    = '$wr_option',
						wr_subject   = '" . mres($EM_TITLE) . "',
						wr_content   = '" . mres($EM_CONT) . "',
						wr_link1     = '',
						wr_link2     = '',
						wr_link1_hit = 0,
						wr_link2_hit = 0,
						wr_hit       = 0,
						wr_good      = 0,
						wr_nogood    = 0,
						mb_id        = '" . mres($member['mb_id']) . "',
						wr_password  = '$wr_password',
						wr_name      = '" . mres($wr_name) . "',
						wr_email     = '" . mres($wr_email) . "',
						wr_homepage  = '" . mres($wr_homepage) . "',
						wr_datetime  = '{$wr_writedate}',
						wr_last      = '".G5_TIME_YMDHIS."',
						wr_ip        = '{$_SERVER['REMOTE_ADDR']}',
						wr_1         = '$wr_1',
						wr_2         = '$wr_2',
						wr_3         = 'deepblue',
						wr_4         = '" . mres($EM_IDX) . "',
						wr_5         = '',
						wr_6         = '',
						wr_7         = '',
						wr_8         = '',
						wr_9         = '',
						wr_10        = '',
						as_re_mb     = '$as_re_mb',
						as_re_name   = '$as_re_name',
						as_tag       = '$as_tag',
						as_map       = '$as_map',
						as_icon      = '$as_icon' ";

			sql_query($sql);
			$wr_id = sql_insert_id();



			# chk result;
			if(!$wr_id){
				alert("캘린더 수정에 실패했습니다.");
				return false;
			}

			return true;
		}


		public static function SET_EDIT($v=array())
		{
			# set : variables
			$EM_IDX    = $v['EM_IDX'];
			$EM_TITLE  = $v['EM_TITLE'];
			$EM_S_DATE = $v['EM_S_DATE'];
			$EM_E_DATE = $v['EM_E_DATE'];
			$EM_CONT   = $v['EM_CONT'];


			# re-define
			$EM_IDX = ((int)$EM_IDX > 0) ? (int)$EM_IDX : '';


			# chk : rfv.
			if ($EM_IDX == '' || $EM_TITLE == '' || $EM_S_DATE == '' || $EM_E_DATE == '') return false;


			# Set default value;
			$wr_1 = date("Ymd", strtotime($EM_S_DATE));
			$wr_2 = date("Ymd", strtotime($EM_E_DATE));


			# 기타 (사용하지 않음)
			$sql_ip = '';
			if (!$is_admin){
			$sql_ip = " , wr_ip = '{$_SERVER['REMOTE_ADDR']}' ";
			}
			$sql_password = $wr_password ? " , wr_password = '".get_encrypt_string($wr_password)."' " : "";


			# insert
			$sql = " 
					UPDATE 
						g5_write_calendar
			        SET wr_subject  = '" . mres($EM_TITLE) . "',
			            wr_content  = '" . mres($EM_CONT) . "',
						wr_1        = '" . mres($wr_1) . "',
						wr_2        = '" . mres($wr_2) . "'
						{$sql_ip}
						{$sql_password}
			        WHERE wr_4 = '" . mres($EM_IDX) . "' ";
			$result = sql_query($sql, true);


			# chk result
			if(!$result){
				alert("캘린더 수정에 실패했습니다.");
				return false;
			}

			return true;
		}

		public static function SET_DEL($v=array()){
			# set : variables
			$EM_IDX    = $v['EM_IDX'];

			$sql = "
					DELETE FROM 
						g5_write_calendar
					WHERE 
						wr_4 = '{$EM_IDX}' ";
			$result = sql_query($sql, true);

			if(!$result){
				alert('캘린더에서 행사 삭제에 실패했습니다.');
				return false;
			}

			return true;

		}
	}
?>
