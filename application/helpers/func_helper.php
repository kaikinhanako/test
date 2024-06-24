<?php
/*
 * application/helpers/functions.php
 */

if (!function_exists('jump')) {

	/**
	 * 
	 * @param string $path target url
	 * @param array $parameters get request params in associative array
	 */
	function jump($path, $parameters = null) {
		$url = ($path ? : '') . (empty($parameters) ? "" : ('?' . http_build_query($parameters)));
		header('Location: ' . $url);
		exit;
	}

}

if (!function_exists('jump_back')) {

	function jump_back($num = 1) {
		?><script type="text/javascript">window.history.go(-<?= $num ?>)</script><?php
		exit;
	}

}

if (!function_exists('is_numonly')) {

	function is_numonly($value) {
		return preg_match("/^\d+$/", $value);
	}

}

if (!function_exists('urlencode_array')) {

	function urlencode_array(array $strs) {
		$encodeds = array();
		foreach ($strs as $str) {
			$encodeds[] = urlencode($str);
		}
		return $encodeds;
	}

}



if (!function_exists('array_filter_values')) {

	/**
	 * trim empty value and renumber keys
	 * @param array $array
	 * @return array result array
	 */
	function array_filter_values(array $array) {
		return array_values(array_filter($array, 'strlen'));
	}

}

if (!function_exists('h')) {

	/**
	 * just htmlspecialchars action and return result
	 * omit function name in coding
	 * @param string $string
	 * @return string
	 */
	function h($string) {
		return htmlspecialchars($string);
	}

}

if (!function_exists('trim_bothend_space')) {

	function trim_bothend_space($str) {
		return preg_replace('#[\s 　]*(.*?)[\s 　]*#u', '\1', $str);
	}

}

if (!function_exists('array_reflect_func')) {

	function array_reflect_func(array $array, $callback) {
		if (!is_callable($callback)) {
			return FALSE;
		}

		foreach ($array as &$value) {
			if (is_array($value)) {
				$value = array_reflect_func($value, $callback);
			} else {
				$value = call_user_func($callback, $value);
			}
		}
		return $array;
	}

}

if (!function_exists('create_std_obj')) {

	/**
	 * 
	 * @param array $fields in array field_name => field_value
	 * @return stdClass
	 */
	function create_std_obj(array $fields) {
		$obj = new stdClass();
		foreach ($fields as $name => $value) {
			$obj->{$name} = $value;
		}
		return $obj;
	}

}

if (!function_exists('count_value')) {

	function count_value($data, $fieldname = NULL) {
		$result = array();
		foreach ($data as $datum) {
			$value = (isset($fieldname) ? $datum->{$fieldname} : $datum);
			@$result[$value] ++;
		}
		return $result;
	}

}

if (!function_exists('to_time_resolution')) {

	/**
	 * 
	 * @param int $time
	 * @return stdClass
	 */
	function to_time_resolution($time, $is_unit = FALSE) {
		$times = new stdClass();
		$times->d = $times->df = $times->h = $times->m = 0;
		if ($time < 0) {
			return $times;
		}

		$times->df = round($time / 86400, 1);
		$times->d = floor($times->df);
		$time = $time % 86400;
		$times->h = floor($time / 3600);
		$time = $time % 3600;
		$times->m = floor($time / 60);
		if ($is_unit) {
			$times->df = ($times->df) ? $times->df . '日' : 0;
			$times->d = ($times->d ) ? $times->d . '日' : 0;
			$times->h = ($times->h ) ? $times->h . '時間' : 0;
			$times->m = ($times->m ) ? $times->m . '分' : 0;
		}
		return $times;
	}

}

if (!function_exists('to_time_resolution_str')) {

	function to_time_resolution_str($time, $is_full = FALSE) {
		$times = to_time_resolution($time, TRUE);
		$str = '';
		$df = TRUE;
		if ($times->d) {
			$str .= $times->d;
			// not add min (when not $is_full)
			$df = $is_full;
		}
		$str .= $times->h ? : '';
		$str .= ($times->m && $df) ? $times->m : '';
		return $str;
	}

}

if (!function_exists('fix_url')) {

	function fix_url($url) {
		if (!preg_match('#^http#u', $url)) {
			$url = 'http:' . $url;
		}
		return $url;
	}

}

if (!function_exists('isset_just')) {

	function isset_just($value) {
		return isset($value) ? $value : FALSE;
	}

}

if (!function_exists('is_today')) {

	function is_today($timestamp) {
		if (!is_integer($timestamp)) {
			$timestamp = strtotime($timestamp);
		}
		return date('Y-m-d', $timestamp) === date('Y-m-d');
	}

}

if (!function_exists('date_mysql_timestamp')) {

	function date_mysql_timestamp($time = NULL) {
		return $time ? date(MYSQL_TIMESTAMP, $time) : date(MYSQL_TIMESTAMP);
	}

}

if (!function_exists('is_pc_viewport')) {

	function is_pc_viewport($user_agent) {
//		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		$lib = explode(',', ' iPhone, iPod, Android');
		foreach ($lib as $str) {
			if (strpos($user_agent, $str) !== FALSE) {
				return FALSE;
			}
		}
		return TRUE;
	}

}

if (!function_exists('multi_split')) {

	function multi_split($str) {
		return preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY);
	}

}

if (!function_exists('mb_multi_strlen')) {

	function mb_multi_strlen($str) {
		return count(multi_split($str));
	}

}

if (!function_exists('get_defined_constants_fillter')) {

	function get_defined_constants_fillter($needle) {
		$arr = array();
		foreach (get_defined_constants(TRUE) as $key => $value) {
			if (strpos($key, $needle) !== FALSE) {
				$arr[$key] = $value;
			}
		}
		return $arr;
	}

}

if (!function_exists('wrap_taglink')) {

	function wrap_taglink($str) {
		$pattern = '/#(w*[一-龠_ぁ-ん_ァ-ヴー]+|[a-zA-Z0-9]+|[a-zA-Z0-9]w*)/u';
		$replacement = '<a class="tag-link" href="' . base_url(PATH_TAG) . '/$1">$0</a>';
		return preg_replace($pattern, $replacement, $str);
	}

}

if (!function_exists('wrap_taglink_only')) {

	function wrap_taglink_only(Tagobj $tag, $view_count = TRUE) {
		$badge = '';
		if ($view_count) {
			$badge = '<span class="badge">' . $tag->count . '</span>';
		}
		return '<a class="tag-link" href="' . base_url(PATH_TAG . urlencode($tag->text)) . '">#' . $tag->text . $badge . '</a>';
	}

}

if (!function_exists('updatePing')) {

	function updatePing($host, $path, $title, $url) {
		$title_esc = htmlspecialchars($title);
		$content = <<<EOF
<?xml version="1.0"?>
<methodCall>
    <methodName>weblogUpdates.ping</methodName>
    <params>
        <param>
            <value>$title_esc</value>
        </param>
        <param>
            <value>$url</value>
        </param>
    </params>
</methodCall>
EOF;
		$req = "POST $path HTTP/1.0\r\n"
			. "Host: $host\r\n"
			. "Content-Type: text/xml\r\n"
			. "Content-Length: " . strlen($content) . "\r\n"
			. "\r\n"
			. $content;
		$sock = @fsockopen($host, 80, $errno, $errstr, 2.0);
		$result = "$host";
		if ($sock) {
			fputs($sock, $req);
			while (!feof($sock)) {
				$result .= fread($sock, 1024);
			}
		}
		$res = (strpos($result, '<boolean>0</boolean>') !== FALSE) ? 'OK' : 'NG';
		echo "[${res}] $host <br>";
		return $result;
	}

}

if (!function_exists('array_flatten')) {

	function array_flatten($array) {
		static $tmp;
		if (is_array($array))
			foreach ($array as $val)
				array_flatten($val);
		else
			$tmp[] = $array;
		return $tmp;
	}

}


if (!function_exists('array_rand_values')) {

	function array_rand_values(array $array, $num = 1) {
		$keys = array_rand($array, $num);
		if ($num == 1) {
			return $array[$keys];
		}
		$tmp = array();
		foreach ($keys as $key) {
			$tmp[] = $array[$key];
		}
		return $tmp;
	}

}

if (!function_exists('time_to_str_ms')) {

	function time_to_str_ms($num) {
		$h = floor($num / (60 * 60 * 1000));
		$num %= 60 * 60 * 1000;
		$m = floor($num / (60 * 1000));
		$num %= 60 * 1000;
		$s = floor($num / 1000);
		$ms = $s % 1000;
		return ($h ? "{$h}時間" : '') . ($m ? "{$m}分" : '') . "{$s}秒{$ms}";

	}

}
