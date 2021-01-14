<?php
	define('G5_IS_ADMIN', true);
	include_once ('../../common.php');
	include_once(G5_ADMIN_PATH.'/admin.lib.php');
	include_once(G5_ADMIN_PATH.'/admin.nx.lib.php');


	$_POST    = array_map_deep('stripslashes',  $_POST);
	$_GET     = array_map_deep('stripslashes',  $_GET);
	$_COOKIE  = array_map_deep('stripslashes',  $_COOKIE);
	$_REQUEST = array_map_deep('stripslashes',  $_REQUEST);


	# set : variables
	$EM_IDX = (isset($_POST['EM_IDX']) && $_POST['EM_IDX'] != '') ? $_POST['EM_IDX'] : $_GET['EM_IDX'];
	$FI_IDX = (isset($_POST['FI_IDX']) && $_POST['FI_IDX'] != '') ? $_POST['FI_IDX'] : $_GET['FI_IDX'];
	$FI_RNDCODE = (isset($_POST['FI_RNDCODE']) && $_POST['FI_RNDCODE'] != '') ? $_POST['FI_RNDCODE'] : $_GET['FI_RNDCODE'];


	# re-define
	$FI_IDX = (CHK_NUMBER($FI_IDX) > 0) ? (int)$FI_IDX : "";
	$FI_RNDCODE = trim($FI_RNDCODE);


	# set : wh
	$wh = "Where FI.FI_DDATE is null";
	// $wh = "Where FI.FI_DDATE is null And FI.mb_id = '" . mres($member['mb_id']) . "'";
?>
<?php
	class NX_EVT_MA
	{
		# JOIN_PREFIX 생성
		public static function GET_JOIN_PREFIX($v)
		{
			global $member;


			# chk : rfv.
			if ($member['mb_id'] == '') return (array('success'=>(boolean)false, 'msg'=>"로그인 후 이용 가능 합니다."));
			if ($v == '') return (array('success'=>(boolean)false, 'msg'=>"필수 항목이 누락되었습니다."));


			# set : EM_JOIN_PREFIX
			$row = sql_fetch("Select EM_JOIN_PREFIX From NX_EVENT_MASTER Where EM_S_DATE = '" . mres($v) . "' Order By EM_IDX Desc Limit 1");
			if (is_null($row['EM_JOIN_PREFIX'])) {
				$EM_JOIN_PREFIX = 'A';
			}
			else {
				$EM_JOIN_PREFIX = strtoupper($row['EM_JOIN_PREFIX']);

				# ZZ 까지 허용함
				$_bo = true;
				
				$_len = strlen($EM_JOIN_PREFIX);

				# 정보가 없을 경우(exception)
				if ($_len == 0) {
					$EM_JOIN_PREFIX = 'A';
				}
				# 1자리일 경우
				else if ($_len == 1) {
					# Z 일 경우 = AA
					if (ord($EM_JOIN_PREFIX) >= 90) {
						$EM_JOIN_PREFIX = 'AA';
					}
					else {
						$EM_JOIN_PREFIX = chr(ord($EM_JOIN_PREFIX) + 1);
					}
				}
				# 2자리일 경우
				else if ($_len == 2) {
					$_str = array(substr($EM_JOIN_PREFIX, 0, 1), substr($EM_JOIN_PREFIX, -1));
					
					# 두번째 자리가 'Z'
					if (ord($_str[1]) >= 90) {
						# ZZ 일 경우 중단
						if (ord($_str[0]) >= 90) {
							$_bo = false;
						}
						# 앞자리+1, A
						else {
							$EM_JOIN_PREFIX = chr(ord($_str[0]) + 1) . 'A';
						}
					}
					# 앞자리, 두번째자리+1
					else {
						$EM_JOIN_PREFIX = $_str[0] . chr(ord($_str[1] + 1));
					}
				}
				# 두자리 초과
				else {
					$_bo = false;
				}

				if (!$_bo) {
					return (array(
						'success'=>(boolean)false, 
						'msg'=>"1일 최대 행사 제한에 도달했습니다.\n\n시스템 관리자에게 문의해 주세요."
					));
				}
			}
			unset($row);

			return (array('success'=>(boolean)true, 'EM_JOIN_PREFIX'=>$EM_JOIN_PREFIX));
		}
	}



	class NX_EVENT_FORM_BUILDER
	{
		/* 2시간 경과된 임시 data 삭제 */
		public static function SET_DEL_TEMP()
		{
			global $member;


			# chk : rfv.
			if ($member['mb_id'] == '') return;


			$sql = "Select FI_IDX From NX_EVENT_FORM_ITEM Where (EM_IDX is null And FI_RNDCODE is not null) And (FI_WDATE < date_add(now(), interval - 2 hour)) Order By FI_IDX Asc";
			$db1 = sql_query($sql);

			$s = 0;
			while ($rs1 = sql_fetch_array($db1))
			{
				# XX_FORM_OPT data 삭제
				$sql = "Delete From NX_EVENT_FORM_OPT Where FI_IDX = '" . mres($rs1['FI_IDX']) . "'";
				sql_query($sql);

				# XX_FORM_COND data 삭제
				$sql = "Delete From NX_EVENT_FORM_COND Where FI_IDX = '" . mres($rs1['FI_IDX']) . "'";
				sql_query($sql);

				# XX_FORM_VAL data 삭제
				$sql = "Delete From NX_EVENT_FORM_VAL Where FI_IDX = '" . mres($rs1['FI_IDX']) . "'";
				sql_query($sql);

				# XX_FORM_ITEM data 삭제
				$sql = "Delete From NX_EVENT_FORM_ITEM Where FI_IDX = '" . mres($rs1['FI_IDX']) . "' Limit 1";
				sql_query($sql);

				$s++;
			}


			# 삭제된 data 가 있을 경우 table optimize
			if ($s > 0)
			{
				$sql = "Optimize table `NX_EVENT_FORM_OPT`";
				sql_query($sql);

				$sql = "Optimize table `NX_EVENT_FORM_COND`";
				sql_query($sql);

				$sql = "Optimize table `NX_EVENT_FORM_VAL`";
				sql_query($sql);

				$sql = "Optimize table `NX_EVENT_FORM_ITEM`";
				sql_query($sql);
			}
		}


		/* data 삭제 */
		public static function SET_DEL($v=array())
		{
			global $member, $is_admin;


			# set : variables
			$EM_IDX = $v['EM_IDX'];


			# re-define
			$EM_IDX = CHK_NUMBER($EM_IDX);


			# chk : rfv.
			if ($member['mb_id'] == '' || !$is_admin || $EM_IDX <= 0) return;


			$sql = "Select FI_IDX From NX_EVENT_FORM_ITEM Where EM_IDX = '" . mres($EM_IDX) . "' Order By FI_IDX Asc";
			$db1 = sql_query($sql);

			$s = 0;
			while ($rs1 = sql_fetch_array($db1))
			{
				# XX_FORM_OPT data 삭제
				$sql = "Delete From NX_EVENT_FORM_OPT Where FI_IDX = '" . mres($rs1['FI_IDX']) . "'";
				sql_query($sql);

				# XX_FORM_COND data 삭제
				$sql = "Delete From NX_EVENT_FORM_COND Where FI_IDX = '" . mres($rs1['FI_IDX']) . "'";
				sql_query($sql);

				# XX_FORM_VAL data 삭제
				$sql = "Delete From NX_EVENT_FORM_VAL Where FI_IDX = '" . mres($rs1['FI_IDX']) . "'";
				sql_query($sql);

				# XX_FORM_ITEM data 삭제
				$sql = "Delete From NX_EVENT_FORM_ITEM Where FI_IDX = '" . mres($rs1['FI_IDX']) . "' Limit 1";
				sql_query($sql);

				$s++;
			}


			# 삭제된 data 가 있을 경우 table optimize
			if ($s > 0)
			{
				$sql = "Optimize table `NX_EVENT_FORM_OPT`";
				sql_query($sql);

				$sql = "Optimize table `NX_EVENT_FORM_COND`";
				sql_query($sql);

				$sql = "Optimize table `NX_EVENT_FORM_VAL`";
				sql_query($sql);

				$sql = "Optimize table `NX_EVENT_FORM_ITEM`";
				sql_query($sql);
			}
		}


		#---------- get : 입력항목타입 (FI_TYPE)
		public static function GET_FI_TYPE_TO_STR($v1='',$v2='')
		{
			if ($v1 == 'A') {
				$ret = array(
					array('FI_KIND'=>'A', 'val'=>'NORMAL', 'str'=>"일반문장")
					, array('FI_KIND'=>'A', 'val'=>'TEL', 'str'=>"전화번호")
					, array('FI_KIND'=>'A', 'val'=>'EMAIL', 'str'=>"이메일")
					, array('FI_KIND'=>'A', 'val'=>'URL', 'str'=>'URL')
					, array('FI_KIND'=>'A', 'val'=>'NUMBER', 'str'=>"숫자")
					);
			}
			else if ($v1 == 'E') {
				$ret = array(
					array('FI_KIND'=>'E', 'val'=>'DATE', 'str'=>"날짜")
					, array('FI_KIND'=>'E', 'val'=>'DATETIME', 'str'=>"날짜+시간")
					);
			}
			else {
				$ret = array();
			}

			# 지정된 값이 있을 경우 해당값의 str 을 return
			if ($v1 != '' && $v2 != '')
			{
				for ($i = 0; $i < Count($ret); $i++) {
					if ($ret[$i]['FI_KIND'] == $v1 && $ret[$i]['val'] == $v2) {
						return $ret[$i]['str'];
						break;
					}
				}

				if ($v1 == 'A') {
					return 'NORMAL';
				} else if ($v1 == 'E') {
					return 'DATE';
				} else if ($v1 == 'I') {
					return 'SINGLE';
				} else {
					return '';
				}
			}

			return $ret;
		}
		#----------

		
		#---------- get : 입력항목종류 (FI_KIND)
		public static function GET_FI_KIND_TO_STR($v='')
		{
			$ret = array(
				array('val'=>'A', 'str'=>"한줄텍스트")
				, array('val'=>'B', 'str'=>"여러줄텍스트")
				, array('val'=>'C', 'str'=>"단일선택")
				, array('val'=>'D', 'str'=>"다중선택")
				, array('val'=>'E', 'str'=>"날짜")
				, array('val'=>'F', 'str'=>"주소")
				, array('val'=>'G', 'str'=>"파일")
				);

			# 지정된 값이 있을 경우 해당값의 str 을 return
			if ($v != '')
			{
				for ($i = 0; $i < Count($ret); $i++) {
					if ($ret[$i]['val'] == $v) {
						return $ret[$i]['str'];
						break;
					}
				}

				return '';
			}

			return $ret;
		}
		#----------

	}
?>
