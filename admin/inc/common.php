<?php

ini_set('session.gc_maxlifetime', '86400'); // время жизни сессии
session_set_cookie_params(0, '/', substr($HH,0,4)=='www.' ? trim($HH,'w') : '.'.$HH); // распростроняем сессию на домены/поддомены

try {
    require_once($_SERVER["DOCUMENT_ROOT"].'/inc/common.php');
    \Lib\App::startAdmin(DOCUMENT_ROOT,ROOT_PATH_ADMIN);
} catch ( Exception $e ) {
    echo $e->getMessage();
}

$DR = $_SERVER['DOCUMENT_ROOT'];
$HH = $_SERVER['HTTP_HOST'];

error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set('display_errors', 1);
ini_set('max_execution_time', '300');
ini_set('upload_max_filesize', '10M');
ini_set('max_file_uploads', '299');
ini_set('post_max_size', '15M');
//ini_set('memory_limit', '32M');
@session_start();


if(!@$_SESSION['priv'] && !@$free_access)
{
	header('Location: login.php?action=vhodc&urlback='.$_SERVER['REQUEST_URI']);
	exit();
}
// ограничение доступа
$php_self = basename($_SERVER['PHP_SELF']);
if(!@$free_access && $_SESSION['priv'] != 'admin' && $php_self != 'action.php' && !strpos(",,{$_SESSION['priv']['priv']},", ",{$php_self},"))
{
	if($php_self == 'index.php')
	{
		header('location: '.current(explode(',',$_SESSION['priv']['priv'])));
		exit;
	}
	else
		die('Доступ запрещен');
}

require('db.php'); //коннектимся к базе
require('utils.php'); //разные полезные функции
require('tree.php'); //работа с деревом
require('advanced/advanced.php'); //"навороты" к сайту

require('special.php'); //функции специально для админки
require('menu.php'); // левое меню и группы ссылок для верхнего меню админки
//require('phpzip.php'); //класс для архивирования файла/каталога
//require('getfile.php'); // набор функций для получения страницы сайта, например для парсинга
//require('simple_html_dom.php'); // HTML парсер
//require('cy_pr.php'); // определение тИЦ и PR

$action = mysql_real_escape_string(@$_GET['action']);
$show = mysql_real_escape_string(@$_GET['show']);

require('special2.php'); //функции специально для данной системы
?>