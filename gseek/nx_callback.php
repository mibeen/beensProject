<?php

include_once("../gseek/api.php");
include_once("../lib/common.lib.php");


$http_host = $_SERVER['HTTP_HOST'];
$request_uri = $_SERVER['REQUEST_URI'];
$url = 'http://' . $http_host . $request_uri;



define('GSK_I_CODE', '5954afb715693');
define('GSK_I_ID', 'korkkh@test.com');
define('GSK_I_NAME', '김경호');

//값을 정상적으로 받아오는지 체크 후 
//멤버정보 다시 세팅
unset($member);

$member = array(
			'mb_id'       => GSK_I_ID,
			'mb_password' => '0000',
			'mb_email'    => GSK_I_ID,
			'mb_nick'     => GSK_I_NAME,
			'mb_name'     => GSK_I_NAME,
			'mb_level'    => 2,
			'mb_point'    => 0
		);

$id00 = generateRandomString(15);
//$_SESSION['ss_oauth_member_no'] = 'fcb_'. $id00;
//$_SESSION['ss_oauth_member_fcb_'.$id00.'_info'] = $member;

set_session('ss_oauth_member_no',                               'fcb_'.$id00);
set_session('ss_oauth_member_fcb_Test_info', $member);

					/*

$oauth = new FACEBOOK_OAUTH(G5_FACEBOOK_CLIENT_ID, G5_FACEBOOK_SECRET_KEY);

if($oauth->check_valid_state_token($_GET['state'])) {
    if($oauth->get_access_token($_GET['code'])) {
        if($oauth->check_valid_access_token()) {
            $oauth->get_profile();

            //var_dump($oauth->profile); exit;

            if($oauth->profile->id) {
                $email = $oauth->profile->email;
                $info  = get_oauth_member_info($oauth->profile->id, $oauth->profile->name, 'facebook');

                if($info['id']) {
                    unset($member);

                    $member = array(
                                'mb_id'       => $info['id'],
                                'mb_password' => $info['pass'],
                                'mb_email'    => $email,
                                'mb_nick'     => $info['nick'],
                                'mb_name'     => $oauth->profile->name,
                                'mb_level'    => 2,
                                'mb_point'    => 0
                            );

                    set_session('ss_oauth_member_no',                               'fcb_'.$oauth->profile->id);
                    set_session('ss_oauth_member_fcb_'.$oauth->profile->id.'_info', $member);
                }
            } else {
                alert_close('서비스 장애 또는 정보가 올바르지 않습니다.');
            }
        } else {
            alert_close('토큰 정보가 올바르지 않습니다.');
        }
    } else {
        alert_close('서비스 장애 또는 정보가 올바르지 않습니다.');
    }
} else {
    alert_close('올바른 방법으로 이용해 주십시오.');


*/











/*
	
    function check_valid_access_token()
    {
        $url = $this->token_url.'?client_id='.$this->client_id.'&client_secret='.$this->secret_key.'&grant_type=client_credentials';

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $json = curl_exec($ch);
        curl_close($ch);

        if($json) {
            $result = json_decode($json);

            $this->app_access_token = $result->access_token;
        }

        if(!$this->access_token || !$this->app_access_token)
            return false;

        $url = $this->token_debug.'?input_token='.$this->access_token.'&access_token='.$this->app_access_token;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $json = curl_exec($ch);
        curl_close($ch);

        if($json) {
            $result = json_decode($json);

            return ($result->data->is_valid && $result->data->app_id == $this->client_id);
        } else {
            return false;
        }
    }
	*/
	//goto_url(G5_URL);

	//Header("Location:".G5_URL); 



	function generateRandomString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}



?>