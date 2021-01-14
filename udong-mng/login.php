<?php
	define('G5_IS_ADMIN', true);
	include_once ('../common.php');


	$g5['title'] = '로그인';

	if ($is_member){
		goto_url(G5_URL.'/udong-mng/');
	}

	if (isset($_GET['url'])){
		$url = clean_xss_tags(trim($_GET['url']));
	}
?>

<head>
	<meta name="viewport" content="initial-scale=1, maximum-scale=1">
	<title><?php echo $g5['title'] ?></title>
</head>

<section id="login-wrap">
	<div class="login-box">
		<div class="login">
			<p>
				<?php
						$footer_dir = "mlogo";
						$footer_file = G5_DATA_PATH.'/member/'.$footer_dir.'/mb_mlogo.png';
						if (file_exists($footer_file)) {
							$footer_url = G5_DATA_URL.'/member/'.$footer_dir.'/mb_mlogo.png';
							#echo '<img src="'.$footer_url.'" alt="">';
							echo "<img src=\"" . G5_DATA_URL . "/member/" . $footer_dir . "/mb_mlogo.png\" alt=\"\">";
						}
						else
						{
							echo $page_title;
						}
						?>
			</p>	
			<form action="/bbs/login_check_ud.php" method="POST">
				<input type="hidden" name="url" value="<?php echo $url ?>">
				<input type="text" name="mb_id" id="mb_id" value="" placeholder="User ID">
				<input type="password" name="mb_password" id="mb_password" placeholder="User Password">
				<button type="submit">로그인</button>
			</form>
		</div>
		<address>Copyright (C) 경기도평생교육진흥원 All right reserved.</address>
	</div>
</section>

<style>
	@import url(//fonts.googleapis.com/earlyaccess/notosanskr.css);

	*{padding:0; margin:0;}
	#login-wrap{display: table; width: 100%; height: 100%; background: url(/adm/img/login_bg.jpg) 50% 50% / cover no-repeat;}
	.login-box{display: table-cell; vertical-align: middle; text-align: center;}
	.login{max-width: 500px; padding: 70px 30px; background: rgba(255,255,255,1); margin: 0 auto; border-radius: 5px; box-shadow: 0 0 7px 0px #666;
		-webkit-box-sizing: border-box;
		-moz-box-sizing: border-box;
		box-sizing: border-box;
	}

	.login-box h2{max-width: 500px; padding: 20px 0; margin: 0 auto; font-size: 21px; font-weight: bold; color: #FFF;}
	.login-box address{max-width: 500px; padding: 20px 0; margin: 0 auto; font-size: 14px; color: #FFF; font-style: normal; font-family: 'Noto Sans KR', sans-serif;}
	.login p{padding-bottom: 20px;}
	.login p img {max-width: 265px;}

	.login input{width: 100%; padding: 15px 10px; margin-bottom: 20px; border-radius: 5px; border: 1px solid #ddd;}
	.login button{width: 100%; padding: 20px 10px; background: #2F63AF; color: #FFF; font-size: 17px; border-radius: 5px; border: 1px solid #ddd;}
	.login p{font-size: 20px; text-align: center; font-weight: bold; margin-bottom: 15px;}

	@media screen and (max-width: 667px){
		#login-wrap{padding: 20px 0;}
		.login {max-width: 90%; margin: 0 auto; padding: 50px 25px;}
		.login p img{max-width: 200px; display: block; margin: 0 auto;}
		.login button{padding: 13px 10px;}

		 address{text-shadow: 1px 1px #999;}

		.login input{box-shadow: none; outline: none; -webkit-appearance: none;}
	}
</style>