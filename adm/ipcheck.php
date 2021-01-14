<?php
### 관리자 페이지 접근 가능 IP 

# DB에 입력되어있지 않아도 접근 가능한 IP
$thrupass_ip = array(
	'61.36.81.137', // 진흥원 전산실
	'121.153.17.7',
	'121.153.17.8',
    '112.169.91.71'
	);

$cf_adm_possible_ip = htmlspecialchars(trim($config['cf_10']));
$is_adm_possible_ip = false;
$pattern = explode("\n", $cf_adm_possible_ip);

# 스루패스 IP를 배열에 병합
$pattern = array_merge($pattern, $thrupass_ip);

for ($i=0; $i<count($pattern); $i++) {
    $pattern[$i] = trim($pattern[$i]);
    if (empty($pattern[$i]))
        continue;

    $pattern[$i] = str_replace(".", "\.", $pattern[$i]);
    $pattern[$i] = str_replace("+", "[0-9\.]+", $pattern[$i]);
    $pat = "/^{$pattern[$i]}$/";
    $is_adm_possible_ip = preg_match($pat, $_SERVER['REMOTE_ADDR']);
    if ($is_adm_possible_ip)
        break;
}
if (!$is_adm_possible_ip) {
	$root = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';

	header("Location: " . $root);
}
?>