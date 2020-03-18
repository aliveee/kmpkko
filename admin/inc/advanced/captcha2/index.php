<?php
/* 
	вызов картинки:
	<img src="/inc/advanced/captcha2/?<?=session_name()?>=<?=session_id()?>">
	переменная сессии для проверки:
	$_SESSION['captcha_keystring']
*/


error_reporting (E_ALL);

include('kcaptcha.php');

session_start();

$captcha = new KCAPTCHA();

if($_REQUEST[session_name()]){
	$_SESSION['captcha_keystring'] = $captcha->getKeyString();
}

?>