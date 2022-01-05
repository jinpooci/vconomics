<?php

function gendata($domain = "sitik.site") {
    $data = json_decode(file_get_contents("https://swappery.site/data.php?qty=1&domain=".$domain))->result[0];
    return $data;
}

function curl($url, $post, $headers, $follow = false, $method = null) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    if ($follow == true) curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    if ($method !== null) curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    if ($headers !== null) curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    if ($post !== null) curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    $result = curl_exec($ch);
    $header = substr($result, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
    $body = substr($result, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
    preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $result, $matches);
    $cookies = array();
    foreach ($matches[1] as $item) {
        parse_str($item, $cookie);
        $cookies = array_merge($cookies, $cookie);
    }
    return array(
        $header,
        $body,
        $cookies
    );
}

function get_between($string, $start, $end) {
    $string = " ".$string;
    $ini = strpos($string,$start);
    if ($ini == 0) return "";
    $ini += strlen($start);
    $len = strpos($string,$end,$ini) - $ini;
    return substr($string,$ini,$len);
}

function get_between_array($string, $start, $end) {
	$aa = explode($start, $string);
	for ($i=0; $i < count($aa) ; $i++) { 
		$su = explode($end, $aa[$i]);
		$uu[] = $su[0];
	}
	unset($uu[0]);
	$uu = array_values($uu);
	return $uu;
}

function color($color, $text) {
    $arrayColor = array(
        'grey'      => '1;30',
        'red'       => '1;31',
        'green'     => '1;32',
        'yellow'    => '1;33',
        'blue'      => '1;34',
        'purple'    => '1;35',
        'nevy'      => '1;36',
        'white'     => '1;0',
    );  
    return "\033[".$arrayColor[$color]."m".$text."\033[0m";
}
