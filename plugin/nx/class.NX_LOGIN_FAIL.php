<?php
	#---------- about LOGIN_FAIL ----------#
	class LOGIN_FAIL
	{
		# 로그인실패 추가
		public static function SET_ADD($v=array())
		{
			# set : variables
			$mb_no = $v['mb_no'];


			# chk : rfv.
			if ($mb_no == '') return (array('success'=>(boolean)false, 'msg'=>'필수 정보가 누락되었습니다.'));


			# get data
			$sql = "Select LF.LF_CNT"
				."		From LOGIN_FAIL As LF"
				."		Where LF.mb_no = '" . mres($mb_no) . "' And LF.LF_WDATE >= date_add(now(), interval -30 minute)"
				;
			$rs1 = sql_fetch($sql);

			if(!is_null($rs1['LF_CNT'])) {
				# set : LF_CNT
				$LF_CNT = $rs1['LF_CNT'] + 1;
			} else {
				# set : LF_CNT
				$LF_CNT = 1;
			}


			# insert : LOGIN_FAIL
			$sql = "Insert Into LOGIN_FAIL("
				."mb_no"
				.", LF_CNT"
				.", LF_WDATE"
				.", LF_WIP"
				.") values("
				."'" . mres($mb_no) . "'"
				.", '" . mres($LF_CNT) . "'"
				.", now()"
				.", '" . mres($_SERVER['REMOTE_ADDR']) . "'"
				.")"
				." On Duplicate key Update"
				." LF_CNT = '" . mres($LF_CNT) . "'"
				.", LF_WDATE = now()"
				.", LF_WIP = '" . mres($_SERVER['REMOTE_ADDR']) . "'"
				;
			sql_query($sql);


			return (array('success'=>(boolean)true));
		}


		# 로그인실패 카운트 증가
		public static function SET_UPDATE($v=array())
		{
			# set : variables
			$mb_no = $v['mb_no'];


			# chk : rfv.
			if ($mb_no == '') return (array('success'=>(boolean)false, 'msg'=>'필수 정보가 누락되었습니다.'));


			# get data
			$sql = "Select LF.LF_CNT"
				."		From LOGIN_FAIL As LF"
				."		Where LF.mb_no = '" . mres($mb_no) . "' And LF.LF_WDATE >= date_add(now(), interval -30 minute)"
				;
			$rs1 = sql_fetch($sql);

			if(is_null($rs1['LF_CNT'])) {
				return (array('success'=>(boolean)false));
			}

			$LF_CNT = $rs1['LF_CNT'] + 1;


			# Update : LOGIN_FAIL
			$sql = "Update LOGIN_FAIL Set"
				. "	LF_CNT = " . mres($LF_CNT)
				. "	, LF_WIP = '" . mres($_SERVER['REMOTE_ADDR']) . "'"
				. " Where mb_no = '" . mres($mb_no) . "'"
				;
			sql_query($sql);


			return (array('success'=>(boolean)true));
		}


		# 로그인실패 삭제
		public static function SET_DEL($v=array())
		{
			# set : variables
			$mb_no = $v['mb_no'];


			# chk : rfv.
			if ($mb_no == '') return (array('success'=>(boolean)false, 'msg'=>'필수 정보가 누락되었습니다.'));


			# chk : record exists
			$DB = LOGIN_FAIL::GET_READ(array('mb_no'=>$mb_no));
			if (!$DB['success']) {
				unset($DB);
				return (array('success'=>(boolean)false, 'msg'=>"존재하지 않는 정보입니다."));
			}


			# delete : LOGIN_FAIL
			$sql = "Delete From LOGIN_FAIL Where mb_no = '" . mres($mb_no) . "'";
			sql_query($sql);


			return (array('success'=>(boolean)true));
		}


		# 로그인 일시 중단 목록 불러오기
		public static function GET_BLOCK_LIST($v=array())
		{
			# set : variables
			$LEVEL_MIN = $v['LEVEL_MIN'];
			$LEVEL_MAX = $v['LEVEL_MAX'];


			# chk : rfv.
			if ($LEVEL_MAX < $LEVEL_MIN) return (array('success'=>(boolean)false, 'msg'=>'최대 레벨이 최소 레벨보다 작습니다.'));


			# get data
			$sql = "Select LF.mb_no, LF.LF_CNT, LF.LF_WDATE"
				."		, memb.mb_id"
				."		From LOGIN_FAIL As LF"
				."			Inner Join g5_member As memb On memb.mb_no = LF.mb_no"
				."		Where LF.LF_CNT >= 5"
				."			And LF.LF_WDATE >= date_add(now(), interval -30 minute)"
				."			And memb.mb_level >= " . mres($LEVEL_MIN)
				."			And memb.mb_level <= " . mres($LEVEL_MAX)
				;
			$db1 = sql_query($sql);

			$ret = array();
			$ret['success'] = (boolean)true;

			while ($rs1 = sql_fetch_array($db1)) {
				// 슈퍼어드민은 제외
				global $config;

				if ($config['cf_admin'] != $rs1['mb_id'] && !chk_multiple_admin($rs1['mb_id'], $config['as_admin'])) {
					$ret['itms'][] = array(
						'mb_no' => $rs1['mb_no'],
						'LF_CNT' => $rs1['LF_CNT'],
	 	 	 	 		'LF_WDATE' => $rs1['LF_WDATE'],

	 	 	 	 		'mb_id' => $rs1['mb_id']
						);
				}

			}

			return $ret;
		}


		# 로그인실패 불러오기
		public static function GET_READ($v=array())
		{
			# set : variables
			$mb_no = $v['mb_no'];


			# chk : rfv.
			if ($mb_no == '') return (array('success'=>(boolean)false, 'msg'=>'필수 정보가 누락되었습니다.'));


			# get data
			$sql = "Select LF.LF_CNT, LF.LF_WDATE"
				."		From LOGIN_FAIL As LF"
				."		Where LF.mb_no = '" . mres($mb_no) . "' And LF.LF_WDATE >= date_add(now(), interval -30 minute)"
				;
			$rs1 = sql_fetch($sql);

			if(!is_null($rs1['LF_CNT'])) {
				$ret = array('success'=>(boolean)true, 'LF_CNT'=>(int)$rs1['LF_CNT'], 'LF_WDATE'=>$rs1['LF_WDATE']);
			} else {
				$ret = array('success'=>(boolean)false, 'msg'=>"정보가 없습니다.");
			}
			unset($rs1, $db1);


			return $ret;
		}
	}
?>