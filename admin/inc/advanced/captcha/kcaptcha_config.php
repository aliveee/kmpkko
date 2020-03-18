<?php

// оепебнд жберю хг HTML б RGB
function html2rgb($color='#FFFFFF')
{
	$color = substr($color, 1);
	
	list($r, $g, $b) = strlen($color)==6
		? array($color[0].$color[1], $color[2].$color[3], $color[4].$color[5])
		: array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
	
	return array(hexdec($r), hexdec($g), hexdec($b));
}


# KCAPTCHA configuration file

$alphabet = "0123456789abcdefghijklmnopqrstuvwxyz"; # do not change without changing font files!

# symbols used to draw CAPTCHA
//$allowed_symbols = "0123456789"; #digits
$allowed_symbols = "23456789abcdeghkmnpqsuvxyz"; #alphabet without similar symbols (o=0, 1=l, i=j, t=f)

# folder with fonts
$fontsdir = 'fonts';	

# CAPTCHA string length
$length = mt_rand(3,4); # random 5 or 6
//$length = 6;

# CAPTCHA image size (you do not need to change it, whis parameters is optimal)
$width = 70;
$height = 50;

# symbol's vertical fluctuation amplitude divided by 2
$fluctuation_amplitude = 5;

# increase safety by prevention of spaces between symbols
$no_spaces = true;

# show credits
$show_credits = false; # set to false to remove credits line. Credits adds 12 pixels to image height
$credits = 'www.captcha.ru'; # if empty, HTTP_HOST will be shown

# CAPTCHA image colors (RGB, 0-255)
//$foreground_color = array(0, 0, 0);
//$background_color = array(220, 230, 255);
$foreground_color = isset($_GET["fc"]) ? html2rgb('#'.$_GET["fc"]) : array(mt_rand(0,100), mt_rand(0,100), mt_rand(0,100));
$background_color = isset($_GET["bc"]) ? html2rgb('#'.$_GET["bc"]) : array(mt_rand(200,255), mt_rand(200,255), mt_rand(200,255));

# JPEG quality of CAPTCHA image (bigger is better quality, but larger file size)
$jpeg_quality = 90;
?>