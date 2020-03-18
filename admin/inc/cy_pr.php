<?
// тИЦ
function getCy($url)
{
	$html = file_curl_contents("http://bar-navig.yandex.ru/u?ver=2&show=32&url=http://{$url}");

	$is_find = preg_match("/value=\"(.\d*)\"/", $html, $tic);
	
	return $is_find < 1 ? 0 : $tic[1];
}
 
 // PR
function getPR($url)
{
	$url = 'info:'.urldecode($url);
	$checksum = checksum(strord($url));
	$html = file_curl_contents("http://www.google.com/search?client=navclient-auto&ch=6{$checksum}&features=Rank&q={$url}");
	preg_match('/Rank_([0-9]+):([0-9]+):([0-9]+)/si', $html, $r);
	return (int)$r[3];
}
// доп. функции для определения PR
function to_int_32 (&$x) {
	$z = hexdec(80000000);
	$y = (int) $x;
	if($y ==- $z && $x <- $z){
	 $y = (int) ((-1) * $x);
	 $y = (-1) * $y;
	}
	$x = $y;
}  
function zero_fill ($a, $b) {
	$z = hexdec(80000000);
	if ($z & $a) {
		$a = ($a >> 1);
		$a &= (~$z);
		$a |= 0x40000000;
		$a = ($a >> ($b - 1));
	} else {
		$a = ($a >> $b);
	}
	return $a;
}
function mix($a, $b, $c) {
	$a -= $b; $a -= $c; to_int_32($a); $a = (int)($a ^ (zero_fill($c,13)));
	$b -= $c; $b -= $a; to_int_32($b); $b = (int)($b ^ ($a<<8));
	$c -= $a; $c -= $b; to_int_32($c); $c = (int)($c ^ (zero_fill($b,13)));
	$a -= $b; $a -= $c; to_int_32($a); $a = (int)($a ^ (zero_fill($c,12)));
	$b -= $c; $b -= $a; to_int_32($b); $b = (int)($b ^ ($a<<16));
	$c -= $a; $c -= $b; to_int_32($c); $c = (int)($c ^ (zero_fill($b,5)));
	$a -= $b; $a -= $c; to_int_32($a); $a = (int)($a ^ (zero_fill($c,3)));
	$b -= $c; $b -= $a; to_int_32($b); $b = (int)($b ^ ($a<<10));
	$c -= $a; $c -= $b; to_int_32($c); $c = (int)($c ^ (zero_fill($b,15)));
	return array($a,$b,$c);
}
function checksum ($url, $length = null, $init = 0xE6359A60) {
	if (is_null($length)) {
		$length = sizeof($url);
	}
	$a = $b = 0x9E3779B9;
	$c = $init;
	$k = 0;
	$len = $length;
	while($len >= 12) {
	$a += ($url[$k+0] + ($url[$k+1] << 8) + ($url[$k+2] << 16) + ($url[$k+3] << 24));
	$b += ($url[$k+4] + ($url[$k+5] << 8) + ($url[$k+6] << 16) + ($url[$k+7] << 24));
	$c += ($url[$k+8] + ($url[$k+9] << 8) + ($url[$k+10] << 16) + ($url[$k+11] << 24));
	$mix = mix($a,$b,$c);
	$a = $mix[0]; $b = $mix[1]; $c = $mix[2];
	$k += 12;
	$len -= 12;
	}
	$c += $length;
	switch($len) {
		case 11: $c += ($url[$k + 10] << 24);
		case 10: $c += ($url[$k + 9] << 16);
		case 9: $c += ($url[$k + 8] << 8);
		case 8: $b += ($url[$k + 7] << 24);
		case 7: $b += ($url[$k + 6] << 16);
		case 6: $b += ($url[$k + 5] << 8);
		case 5: $b += ($url[$k + 4]);
		case 4: $a += ($url[$k + 3] << 24);
		case 3: $a += ($url[$k + 2] << 16);
		case 2: $a += ($url[$k + 1] << 8);
		case 1: $a += ($url[$k + 0]);
	}
	$mix = mix($a, $b, $c);
	return $mix[2];
}
function strord($string) {
	for($i = 0; $i < strlen($string); $i++) {
		$result[$i] = ord($string{$i});
	}
	return $result;
}
function format_number ($number='', $divchar = ',', $divat = 3) {
	$decimals = '';
	$formatted = '';
	if (strstr($number, '.')) {
		$pieces = explode('.', $number);
		$number = $pieces[0];
		$decimals = '.' . $pieces[1];
	} else {
		$number = (string) $number;
	}
	if (strlen($number) <= $divat)
		return $number;
		$j = 0;
	for ($i = strlen($number) - 1; $i >= 0; $i--) {
		if ($j == $divat) {
			$formatted = $divchar . $formatted;
			$j = 0;
		}
		$formatted = $number[$i] . $formatted;
		$j++;
	}
	return $formatted . $decimals;
}

?>