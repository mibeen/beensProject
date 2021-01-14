<?php
    if (!defined('_GNUBOARD_')) exit;

    function mres($v) {
        global $g5;

        $ret = ($v != '') ? mysqli_real_escape_string($g5['connect_db'], $v) : "";
        return $ret;
    }

    /*
        숫자인지 확인 후 integer 형식으로 return
        v : 확인이 필요한 값
        m : 1(0제외), 2(0포함)
    */
    function CHK_NUMBER($v, $m=1) {
        if ($m == 1) {
            $bo = (boolean)(is_numeric($v) && (int)$v > 0);
        }
        else {
            $bo = (boolean)(is_numeric($v));
        }

        return ($bo) ? (int)$v : 0;
    }


    # short : htmlspecialchars
    function F_hsc($str) {
        # 정규식을 통한 script 제거
        $str = preg_replace("!<script(.*?)<\/script>!is","",$str);
        return htmlspecialchars($str);
    }


    # short : echo json
    if (!function_exists('echo_json')) {
        function echo_json($outData, $charset="UTF-8")
        {
            header( "Content-type: application/json; charset={$charset}" );
            echo json_encode($outData); exit();
        }
    }


    function F_YN($val, $mode="N")
    {
        $mode = ($mode == "N") ? "N" : "Y";

        switch($val) {
            case "Y":
                return "Y";
                break;
            case "N":
                return "N";
                break;
            default:
                return "{$mode}";
        }
    }


    # alert msg.
    function F_script($val, $linkVal, $charset="UTF-8")
    {
        $rtnVal = "";
        if ( $val != "" || $linkVal != "" )
        {
            $rtnVal = "<meta http-equiv=\"content-type\" content=\"text/html; charset={$charset}\" />" . chr(13) . chr(10)
                . "<script type=\"text/javascript\">" . chr(13) . chr(10);

            if ($val != "") { $rtnVal .= "alert(\"{$val}\");" . chr(13) . chr(10); }
            if ($linkVal != "") { $rtnVal .= $linkVal . chr(13) . chr(10); }
            
            $rtnVal .= "</script>";
        }
        echo($rtnVal);
        exit();
    }


    # chk : 올바른 날짜 형식인지
    if (!function_exists('F_chkDate')) {
        function F_chkDate($val1)
        {
            if (substr_count($val1, "-") != 2) return false;
            
            $exp_val = explode("-", $val1);
            return (@checkdate($exp_val[1], $exp_val[2], $exp_val[0])) ? true : false;
        }
    }


    # get character size (even if characterset different)
    if (!function_exists('utf8_strcut'))
    {
        function utf8_strcut( $str, $size )
        {
            $substr = substr( $str, 0, $size * 2 );
            $multi_size = preg_match_all( '/[\\x80-\\xff]/', $substr, $multi_chars );

            if ( $multi_size > 0 )
                $size = $size + intval( $multi_size / 3 ) - 1;

            if ( strlen( $str ) > $size )
            {
                $str = substr( $str, 0, $size );
                $str = preg_replace( '/(([\\x80-\\xff]{3})*?)([\\x80-\\xff]{0,2})$/', '$1', $str );
                $str .= '...';
            }

            return $str;
        }
    }


    # 요일정보
    function F_Week($val, $mode=false) {
        switch($val) {
            case "0":
                $ret = "일";
                break;
            case "1":
                $ret = "월";
                break;
            case "2":
                $ret = "화";
                break;
            case "3":
                $ret = "수";
                break;
            case "4":
                $ret = "목";
                break;
            case "5":
                $ret = "금";
                break;
            case "6":
                $ret = "토";
                break;
            default:
                $ret = "";
        }

        if ($mode) $ret .= "요일";
        
        return $ret;
    }
?>