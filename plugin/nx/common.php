<?php
	if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가


	# sns 공유 button token (TEMP)
	define('NX__SNS_BTN_TOKEN', 'ra-58bff1ea9221b257');

	# daum map api token
	define('NX__DAUM_MAP_TOKEN', 'b603af8794432617e1d364bb0c3576ff');


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
?>
