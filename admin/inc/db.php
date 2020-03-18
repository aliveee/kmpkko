<?php
/*    include($DR."/inc/config-local.php");
	$mysql_conn = array('host'=>DB_HOST, 'login'=>DB_USER, 'pwd'=>DB_PASS, 'db'=>DB_NAME);
					  
	$dblink = mysql_connect($mysql_conn['host'],$mysql_conn['login'],$mysql_conn['pwd']) or exit('Database connection error');
	mysql_select_db($mysql_conn['db']) or exit('Database not found');

	//mysql_query("SET NAMES cp1251");
	//setlocale(LC_ALL, 'ru_RU.CP1251'); // установка русской локации (корректно начинает работать strtolower() и др.)
	
	header('Content-Type: text/html; charset=utf-8');
	mysql_query("SET NAMES utf8");
	//setlocale(LC_ALL, 'ru_RU.UTF-8');
	mb_internal_encoding('UTF-8');
	
	//date_default_timezone_set('Europe/Moscow'); // на тут случай, если функция date() касячит (для php) 
	//mysql_query("SET time_zone = '+04:00'"); (для mysql) 
*/
	$prx = PRX;
    define('DB_PREFIX', $prx);
    define('DIR_CACHE', $_SERVER['DOCUMENT_ROOT'].'/cache/');


function mysql_real_escape_string($str){
    return \Lib\App::get('db')->real_escape_string($str);
}
function mysql_query($sql){
    return \Lib\App::get('db')->query($sql);
}

function mysql_fetch_assoc($res){
    if($res)
        return $res->fetch_assoc();
}
function mysql_fetch_array($res){
    if($res)
        return $res->fetch_array();
}
function mysql_fetch_row($res){
    if($res)
        return $res->fetch_row();
}

function mysql_num_fields($res){
    if($res)
        return $res->field_count;
}
function mysql_result($result , $row, $field=0){
    $r = mysqli_fetch_row($result);
    return $r[$field];
}
function mysql_num_rows($res){
    if($res)
        return mysqli_num_rows($res);
}
function mysql_escape_string($str){
    return \Lib\App::get('db')->real_escape_string($str);
}
function mysql_error(){
    return \Lib\App::get('db')->error;
}
function mysql_insert_id(){
    return \Lib\App::get('db')->insert_id;
}
?>