<?php
	if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

$_t = '
<!DOCTYPE html>
<head>
	<title></title>
	<meta http-equiv="content-type" content="application/xhtml+xml; charset=utf-8" />
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
	a[x-apple-data-detectors] {color: inherit !important;text-decoration: none !important;font-size: inherit !important;font-family: inherit !important;font-weight: inherit !important;line-height: inherit !important;}
	@media screen and (max-width: 519px) {
		/* ALLOWS FOR FLUID TABLES */
		.wrapper {width: 100% !important; max-width: 100% !important;}
		/* ADJUSTS LAYOUT OF LOGO IMAGE */
		.logo img {margin: 0 auto !important;}
		.img-max {max-width: 100% !important; width: 100% !important; height: auto !important;}
		/* FULL-WIDTH TABLES */
		.responsive-table {width: 100% !important;}
		/* UTILITY CLASSES FOR ADJUSTING PADDING ON MOBILE */
		.padding {padding: 10px 5% 15px 5% !important;}
		.padding-meta {padding: 30px 5% 0px 5% !important;text-align: center;}
		.no-padding {padding: 0 !important;}
		.section-padding {padding: 30px 15px 50px 15px !important;}
		/* ADJUST BUTTONS ON MOBILE */
		.mobile-button-container {margin: 0 auto;width: 100% !important;}
		.mobile-button {padding: 15px !important;border: 0 !important;font-size: 16px !important;display: block !important;}
		/* NX custom */
		.nx-tit1 {font-size: 20px !important;}
	}
	/* ANDROID CENTER FIX */
	div[style*="margin: 16px 0;"] { margin: 0 !important; }
	</style>
</head>
<body style="margin: 0 !important; padding: 0 !important;">
	<div style="max-width:800px; margin:0 auto;">
	<table style="width:100%;border-spacing:0;border-collapse:collapse;font-family:\'Nanum Gothic\',\'나눔고딕\',NanumGothic,\'맑은 고딕\',\'Malgun Gothic\',\'돋움\',dotum,Tahoma,Sans-serif" border="0" cellpadding="0" cellspacing="0" align="center">
		<tbody>
		<tr>
			<td bgcolor="#e4eff8" style="padding: 25px 30px;">
				<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td align="left" style="padding-bottom: 15px;">
						<!--[if (gte mso 9)|(IE)]>
						<table align="left" border="0" cellspacing="0" cellpadding="0" width="260">
						<tr>
						<td align="left" valign="top" width="260">
						<![endif]-->
						<table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 260px;" class="responsive-table">
							<tr>
								<td>
									<!-- HERO IMAGE -->
									<table width="100%" border="0" cellspacing="0" cellpadding="0" class="wrapper">
										<tr>
											<td class="padding" align="left">
												<img src="'.(($_SERVER['HTTPS'] == 'on') ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'].'/img/mail/logo.png" width="260" border="0" alt="경기도평생교육진흥원" style="display:block; color: #666666;  font-family: Helvetica, arial, sans-serif; font-size: 16px;" class="img-max">
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
				<tr>
					<td bgcolor="#fff" style="padding: 50px 20px;">
						<table border="0" cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td align="center" style="padding-bottom:45px; line-height:150%; font-size:28px; color:#888;" class="nx-tit1">정상적으로 승인되었습니다.</td>
						</tr>
						<tr>
							<td align="center">
								<!--[if (gte mso 9)|(IE)]>
								<table align="center" border="0" cellspacing="0" cellpadding="0" width="400">
								<tr>
								<td align="center" valign="top" width="400">
								<![endif]-->
								<table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 400px;" class="responsive-table">
								<tr>
									<td>
										<!-- HERO IMAGE -->
										<table width="100%" border="0" cellspacing="0" cellpadding="0" class="wrapper">
										<tr>
											<td class="padding" align="center">
												<img src="[#MAIN_IMG#]" width="400" border="0" alt="" style="display: block;" class="img-max">
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
						<tr>
							<td align="center" style="padding: 25px 0 15px; line-height: 150%; font-size: 20px; color: #000;">
								[#EM_TITLE#]
							</td>
						</tr>
						<tr>
							<td align="center" style="padding-bottom: 5px; line-height: 150%; font-size: 16px; color: #666;">
								신청하신 온라인 참가 승인되었습니다.
							</td>
						</tr>
						<tr>
							<td align="center" style="padding-bottom: 25px; line-height: 150%; font-size: 16px; color: #666;">
								이용해주셔서 감사합니다.
							</td>
						</tr>
						<tr>
							<td align="center">
								<a href="[#LINK#]" style="display: inline-block; padding: 12px 45px; background: #3f9de3; font-size: 16px; color: #fff; text-decoration: none;">자세히보기</a>
							</td>
						</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td style="padding:25px 15px 0;">
						<table border="0" cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td align="left" valign="top" width="72">
								<!--[if (gte mso 9)|(IE)]>
								<table align="left" border="0" cellspacing="0" cellpadding="0" width="72">
								<tr>
								<td align="left" valign="top" width="72">
								<![endif]-->
								<table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 72px;" class="responsive-table">
									<tr>
										<td>
											<!-- HERO IMAGE -->
											<table width="100%" border="0" cellspacing="0" cellpadding="0" class="wrapper">
												<tr>
													<td class="padding" align="left">
														<img src="'.(($_SERVER['HTTPS'] == 'on') ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'].'/img/mail/f_logo.png" width="72" border="0" alt="굿모닝 경기" style="display: block; color: #666666;  font-family: Helvetica, arial, sans-serif; font-size: 16px;" class="img-max">
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
							<td style="padding: 8px 0 0 25px;">
								<table>
								<tr>
									<td style="line-height: 130%; font-size: 12px; color: #666;">
										[16207] 경기도 수원시 장안구 경수대로 1150 13층 (재)경기도평생교육진흥원
									</td>
								</tr>
								<tr>
									<td style="line-height: 130%; font-size: 12px; color: #666;">
										대표전화 031-547-6500 | Fax 031-547-6565 | E-mail gill@gill.or.kr
									</td>
								</tr>
								<tr>
									<td style="line-height: 130%; font-size: 12px; color: #666;">
										저작권 ⓒ (재)경기도평생교육진흥원 | Powered by GSEEK
									</td>
								</tr>
								<tr>
									<td style="line-height: 130%; font-size: 12px; color: #666;">
										본 메일은 발신전용으로 회신을 통한 문의는 처리되지 않습니다.
									</td>
								</tr>
								</table>
							</td>
						</tr>
						</table>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		</tbody>
	</table>
	</div>
</body>
</html>
';
?>
