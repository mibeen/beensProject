<?php
	#---------- about : NX_SMS ----------#
	class NX_SMS
	{
		public static function SEND($v=array())
		{
			# set : variables
			$SCHEDULE_TYPE		= $v['SCHEDULE_TYPE'];
			$SMS_MSG			= $v['SMS_MSG'];
			$SEND_TIME			= $v['SEND_TIME'];
			$CALLBACK			= $v['CALLBACK'];
			$CALLEE_NO			= $v['CALLEE_NO'];


			# re-define
			$SCHEDULE_TYPE = (in_array($SCHEDULE_TYPE, array('0','1'))) ? $SCHEDULE_TYPE : '0';
			$SMS_MSG = ($SMS_MSG != "") ? $SMS_MSG : '';
			$SEND_TIME = ($SEND_TIME != "") ? $SEND_TIME : date("YmdHis");
			$CALLBACK = ($CALLBACK != "") ? $CALLBACK : '0315476500';
			$CALLEE_NO = trim($CALLEE_NO);


			# chk : rfv.
			if($SMS_MSG == "" || $CALLBACK == "" || $CALLEE_NO == "") return false;


			// SMS 전송
			$conn = new mysqli("10.100.50.35", "smshub", "smshub@2018", "smshub");

			$sql = "set names utf8";
			$conn->query($sql);

			if(strlen(iconv("utf-8", "euc-kr", $SMS_MSG)) <= 90) {
				$sql = "Insert Into SMS_SEND("
					. "SCHEDULE_TYPE"
					. ", SMS_MSG"
					. ", SAVE_TIME"
					. ", SEND_TIME"
					. ", CALLBACK"
					. ", CALLEE_NO"
					. ") Values("
					. "'" . $conn->real_escape_string($SCHEDULE_TYPE) . "'"
					. ", '" . $conn->real_escape_string($SMS_MSG) . "'"
					. ", date_format(now(), '%Y%m%d%H%i%s')"
					. ", " . (($SCHEDULE_TYPE = "0") ? "date_format(now(), '%Y%m%d%H%i%s')" : ("'" . $conn->real_escape_string($SEND_TIME) . "'"))
					. ", '" . $conn->real_escape_string($CALLBACK) . "'"
					. ", '" . $conn->real_escape_string($CALLEE_NO) . "'"
					. ")";

				$conn->query($sql);
			} else {
				$sql = "Insert Into LMS_SEND("
					. "SCHEDULE_TYPE"
					. ", SUBJECT"
					. ", LMS_MSG"
					. ", SAVE_TIME"
					. ", SEND_TIME"
					. ", CALLBACK"
					. ", CALLEE_NO"
					. ", MSG_TYPE"
					. ") Values("
					. "'" . $conn->real_escape_string($SCHEDULE_TYPE) . "'"
					. ", ''"
					. ", '" . $conn->real_escape_string($SMS_MSG) . "'"
					. ", date_format(now(), '%Y%m%d%H%i%s')"
					. ", " . (($SCHEDULE_TYPE = "0") ? "date_format(now(), '%Y%m%d%H%i%s')" : ("'" . $conn->real_escape_string($SEND_TIME) . "'"))
					. ", '" . $conn->real_escape_string($CALLBACK) . "'"
					. ", '" . $conn->real_escape_string($CALLEE_NO) . "'"
					. ", '0'"
					. ")";

				$conn->query($sql);
			}

			$conn = null;


			return true;
		}
	}
?>
