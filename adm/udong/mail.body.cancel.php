<?php
	if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

$_t = '
<style>
/* CLIENT-SPECIFIC STYLES */
body, table, td, a{-webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;} /* Prevent WebKit and Windows mobile changing default text sizes */
table, td{mso-table-lspace: 0pt; mso-table-rspace: 0pt;} /* Remove spacing between tables in Outlook 2007 and up */
img{-ms-interpolation-mode: bicubic;} /* Allow smoother rendering of resized image in Internet Explorer */
/* RESET STYLES */
img{border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none;}
table{border-collapse: collapse !important;}
body{height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important;}
/* iOS BLUE LINKS */
a[x-apple-data-detectors] {
	color: inherit !important;
	text-decoration: none !important;
	font-size: inherit !important;
	font-family: inherit !important;
	font-weight: inherit !important;
	line-height: inherit !important;
}
</style>
<div style="max-width:800px;">
<table style="width:100%;border-spacing:0;border-collapse:collapse;font-family:\'Nanum Gothic\',\'나눔고딕\',NanumGothic,\'맑은 고딕\',\'Malgun Gothic\',\'돋움\',dotum,Tahoma,Sans-serif" border="0" cellpadding="0" cellspacing="0">
	<tbody>
	<tr>
		<td style="padding: 25px 30px;">
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td style="line-height:150%; font-size:16px; color:#222;">[#mb_nick#]님,</td>
			</tr>
			<tr>
				<td style="line-height:150%; font-size:16px; color:#222;">[#lp_name#] 우리동네 학습공간 이용 신청이 승인취소되었습니다.</td>
			</tr>
			<tr>
				<td style="padding: 25px 15px 15px; line-height: 150%; font-size: 14px; color: #000;">[공간 이용 신청서] 내용</td>
			</tr>
			<tr>
				<td style="padding: 0 15px 30px;">
					<table border="1" cellpadding="5" cellspacing="0">
					<tr>
						<td style="font-size: 13px;color:#222">기관명</td>
						<td style="font-size: 13px;color:#222">[#lp_name#]</td>
					</tr>
					<tr>
						<td style="font-size: 13px;color:#222">이름</td>
						<td style="font-size: 13px;color:#222">[#mb_nick#]</td>
					</tr>
					<tr>
						<td style="font-size: 13px;color:#222">전화번호</td>
						<td style="font-size: 13px;color:#222">[#lr_tel#]</td>
					</tr>
					<tr>
						<td style="font-size: 13px;color:#222">이메일</td>
						<td style="font-size: 13px;color:#222">[#lr_email#]</td>
					</tr>
					<tr>
						<td style="font-size: 13px;color:#222">이용희망일시</td>
						<td style="font-size: 13px;color:#222">[#DATE#]</td>
					</tr>
					<tr>
						<td style="font-size: 13px;color:#222">이용목적</td>
						<td style="font-size: 13px;color:#222">[#lr_usage#]</td>
					</tr>
					<tr>
						<td style="font-size: 13px;color:#222">이용인원</td>
						<td style="font-size: 13px;color:#222">[#lr_p_cnt#]</td>
					</tr>
					<tr>
						<td style="font-size: 13px;color:#222">추가요구사항</td>
						<td style="font-size: 13px;color:#222">[#lr_cont#]</td>
					</tr>
					<tr>
						<td style="font-size: 13px;color:#222">승인취소사유</td>
						<td style="font-size: 13px;color:#222">[#CANCEL_REASON#]</td>
					</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td style="padding-bottom:20px;line-height:150%; font-size:14px; color:#222;">이용신청 현황은 마이페이지-[우리동네 학습공간]에서 확인할 수 있습니다.</td>
			</tr>
			<tr>
				<td style="line-height:150%; font-size:14px; color:#222;">감사합니다.</td>
			</tr>
			<tr>
				<td style="padding-bottom: 20px;line-height:150%; font-size:14px; color:#222;">경기도평생교육진흥원</td>
			</tr>
			<tr>
				<td align="left">
					<!--[if (gte mso 9)|(IE)]>
					<table align="left" border="0" cellspacing="0" cellpadding="0" width="260">
					<tr>
					<td align="left" valign="top" width="260">
					<![endif]-->
					<table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 260px;">
						<tr>
							<td>
								<!-- HERO IMAGE -->
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td class="padding" align="left">
											<img src="'.(($_SERVER['HTTPS'] == 'on') ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'].'/img/mail/logo.png" width="260" border="0" alt="평생교육, 길을 열다" style="display:block; color: #666666;  font-family: Helvetica, arial, sans-serif; font-size: 16px;">
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
					<!--[if (gte mso 9)|(IE)]>
					</td>
					</tr>
					</table>
					<![endif]-->
				</td>
			</tr>
			</table>
		</td>
	</tr>
	</tbody>
</table>
</div>
';
?>
