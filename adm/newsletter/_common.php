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
	$SC_START_DATE = (isset($_POST['SC_START_DATE']) && $_POST['SC_START_DATE'] != '') ? $_POST['SC_START_DATE'] : $_GET['SC_START_DATE'];
	$SC_END_DATE = (isset($_POST['SC_END_DATE']) && $_POST['SC_END_DATE'] != '') ? $_POST['SC_END_DATE'] : $_GET['SC_END_DATE'];
	$SC_WORD = (isset($_POST['SC_WORD']) && $_POST['SC_WORD'] != '') ? $_POST['SC_WORD'] : $_GET['SC_WORD'];
	$page = (isset($_POST['page']) && $_POST['page'] != '') ? $_POST['page'] : $_GET['page'];


	# re-define
	$SC_WORD = trim($SC_WORD);
	$page = (is_numeric($page)) ? (int)$page : 1;


	$phpTail = "SC_START_DATE=" . urlencode($SC_START_DATE)
		. "&SC_END_DATE=" . urlencode($SC_END_DATE)
		. "&SC_WORD=" . urlencode($SC_WORD)
		. "&";


	# 메일 발송
	function F_NEWSLETTER_SEND($NS_IDX, $sendmail, $from, $subject, $body) {
		$sql = "Select NT.NM_IDX, NT.NT_EMAIL"
			."      , NM.NM_CODE"
			."  From NX_NEWSLETTER_TARGET As NT"
			."      Left Join NX_NEWSLETTER_MEMBER As NM On NM.NM_IDX = NT.NM_IDX And NM.NM_DDATE is null"
			."  Where NT.NS_IDX = '" . mres($NS_IDX) . "' And NT.NT_SDATE is null"
			."  Order By NT.NM_IDX Asc"
			;
		$row = sql_query($sql);

		if(sql_num_rows($row) > 0) {
			# update : NX_NEWSLETTER_SEND
			$sql = "Update NX_NEWSLETTER_SEND Set"
				." NS_SDATE = now()"
				.", NS_SIP = inet_aton('" . mres($_SERVER['REMOTE_ADDR']) . "')"
				." Where NS_IDX = '" . mres($NS_IDX) . "' Limit 1"
				;
			sql_query($sql);


			$SEND_CNT = 0;
			while($rs1 = sql_fetch_array($row)) {
				if($rs1['NM_CODE'] != "") {
					$content = '<div style="width:700px; margin:0 auto; background: #E3EFF8;">
					<table style="width:100%;padding-top:20px;border-spacing:0;border-collapse:collapse;font-family:\'Nanum Gothic\',\'나눔고딕\',NanumGothic,\'맑은 고딕\',\'Malgun Gothic\',\'돋움\',dotum,Tahoma,Sans-serif" border="0" cellpadding="0" cellspacing="0" align="center">
					<tbody>
					<tr>
						<td bgcolor="#E3EFF8" style="width: 30px;">
						  <div></div>
						</td>
						<td bgcolor="#E3EFF8" style="width: 640px;" colspan="5">
						  <div><a href="https://www.gill.or.kr"><img src="http://file.gill.center/wp/wp-content/uploads/gill-center/2018-email/01/logo.jpg" alt="경기도평생교육진흥원"></a></div>
						</td>
						<td bgcolor="#E3EFF8" style="width: 30px;">
						  <div></div>
						</td>
					  </tr>

					  <tr>
						<td bgcolor="#E3EFF8" style="width: 30px; height: 40px;">
						  <div></div>
						</td>
						<td style="background: #FFF;">
						  <div></div>
						</td>
						<td style="background: #FFF;">
						  <div></div>
						</td>
						<td style="background: #FFF;">
						  <div></div>
						</td>
						<td style="background: #FFF;">
						  <div></div>
						</td>
						<td style="background: #FFF;">
						  <div></div>
						</td>
						<td bgcolor="#E3EFF8" style="width: 30px;">
						  <div></div>
						</td>
					  </tr>

					  <tr>
						<td bgcolor="#E3EFF8" style="width: 30px;">
						  <div></div>
						</td>
						<td style="width: 49px; background: #FFF;">
						  <div></div>
						</td>
						<td colspan="3" style="background: #FFF;">
						  <div>
						  ' . $body . '
						  </div>
						</td>
						<td style="width: 49px; background: #FFF;">
						  <div></div>
						</td>
						<td bgcolor="#E3EFF8" style="width: 30px;">
						  <div></div>
						</td>
					  </tr>

					  <tr>
						<td bgcolor="#E3EFF8" style="width: 30px;">
						  <div></div>
						</td>
						<td style="background: #FFF;">
						  <div></div>
						</td>
						<td colspan="3" style="background: #FFF;">
						  <table style="width: 100%;">
							<tr>
							  <td style="padding:15px 0;border-top:1px solid #d5d5d5;">
								<table style="width:100%;border-spacing: 0; border-collapse: collapse;" border="0" cellpadding="0" cellspacing="0">
								<tbody>
								<tr>
								  <td style="padding-right:10px;">
									<table style="width:100%;border-spacing: 0; border-collapse: collapse;" border="0" cellpadding="0" cellspacing="0">
									<tbody>
									<tr>
									  <td style="font-size:12px;line-height:150%;font-size:14px;color:#888;">본 이메일은 수신자의 요청에 따라 발송되는 경기도평생교육진흥원 웹진입니다.</td>
									</tr>
									<tr>
									  <td style="font-size:12px;line-height:150%;font-size:14px;color:#888;">구독 취소를 원하시면 ‘구독취소’ 버튼을 눌러주세요.</td>
									</tr>
									</tbody>
									</table>
								  </td>
								  <td width="100">
									<table style="width:100%;border-spacing: 0; border-collapse: collapse;" border="0" cellpadding="0" cellspacing="0">
									<tbody>
									<tr>
									  <td><a href="' . $_SERVER["REQUEST_SCHEME"] . '://' . $_SERVER["HTTP_HOST"] . '/bbs/board.php?bo_table=subscription&wr_id=2&NM_CODE=' . urlencode($rs1['NM_CODE']) . '" target="_blank" style="display:inline-block; padding: 8px 20px; background-color:#ec6f6f; font-size:14px; color:#fff; text-decoration:none;">구독취소</a></td>
									</tr>
									</tbody>
									</table>
								  </td>
								</tr>
								</tbody>
								</table>
							  </td>
							</tr>
						  </table>
						</td>
						<td style="background: #FFF;">
						  <div></div>
						</td>
						<td bgcolor="#E3EFF8" style="width: 30px;">
						  <div></div>
						</td>
					  </tr>

					  <tr>
						<td bgcolor="#E3EFF8" style="width: 30px; height: 150px;">
						  <div></div>
						</td>
						<td bgcolor="#E3EFF8" colspan="5">
						  <div>

							<table>
							  <tr>
								<td style="width: 115px; padding: 13px 0 0 15px; vertical-align: top;">
                                  <img src="http://file.gill.center/wp/wp-content/uploads/gill-center/2019-email/01/gg_slogan_grey_flogo.png" alt="새로운 경기 로고">
                                  <img src="http://file.gill.center/wp/wp-content/uploads/gill-center/2019-email/01/flogo.png" alt="굿모닝 경기 로고">
                                </td>
								<td>
								  <p style="color:#666;font-size:11px;line-height: 1.2;">본 메일은 발신전용으로 회신을 통한 문의는 아래 연락처를 이용하여 주십시오.</p>
								  <p style="color:#666;font-size:11px;line-height: 1.2; margin-top: 10px"><span>경기도 수원시 장안구 <span style="display:none;">파장동</span> 경수대로 1150</span> (파장동 179) 대표전화 : 031-547-6500 이메일 : <a href="mailto:gill@gill.or.kr">gill@gill.or.kr</a></p>
								  <p style="color:#666;font-size:11px;line-height: 1.2; margin-top: 10px;">ⓒ평생교육, 길을 열다</p>
								</td>
							  </tr>
							</table>

						  </div>
						</td>
						<td bgcolor="#E3EFF8" style="width: 30px;">
						  <div></div>
						</td>
					  </tr>
					</tbody>
					</table>
					</div>';


					// $sendmail->send_mail($rs1['NT_EMAIL'], $from, $subject,ㅋ $content);
			        SendMailDirectsend($subject, $content, $from, $rs1['NT_EMAIL']);
			        
			        // DirectSend 즉시발송 분당 300건 제한 적용 : 2019.03.01 00:00 부터 적용
			        $SEND_CNT++;
			        if ($SEND_CNT >= 300) {
			            $SEND_CNT = 0;
			            usleep(60000000); // 1분 휴식
			        }
				}


				# update : NX_NEWSLETTER_TARGET
				$sql = "Update NX_NEWSLETTER_TARGET Set"
					." NT_SDATE = now()"
					.", NT_SIP = inet_aton('" . mres($_SERVER['REMOTE_ADDR']) . "')"
					." Where NS_IDX = '" . mres($NS_IDX) . "' And NM_IDX = '" . mres($rs1['NM_IDX']) . "' Limit 1"
					;
				sql_query($sql);
			}


			# update : NX_NEWSLETTER_SEND
			$sql = "Update NX_NEWSLETTER_SEND Set"
				." NS_STATUS = 'B'"
				.", NS_SDATE = now()"
				.", NS_SIP = inet_aton('" . mres($_SERVER['REMOTE_ADDR']) . "')"
				." Where NS_IDX = '" . mres($NS_IDX) . "' Limit 1"
				;
			sql_query($sql);
		}
	}
?>