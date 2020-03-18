<?php

namespace Lib;

use Model\Common\Redirect;
use Model\Common\Settings;

/**
 * Основной скрипт приложения
 * Class App
 * @package Lib
 */
class App
{
    /**
     * @var string корневая директория приложения
     */
    static protected $root_directory;

    /**
     * @var string корневой uri-путь до приложения
     */
    static protected $root_path;

    /**
     * @var string корневой uri-путь до админки
     */
    static protected $root_path_admin;

    /**
     * @var bool|array структура пути
     */
    static protected $qs;

    /**
     * @var bool|array структура пути в горбатом стиле
     */
    static protected $qsc;


    /**
     * @var boolean ресурс соединения с базой данных
     */
    static protected $db;

    /**
     * @var настроки из БД
     */
    static protected $settings;

    /**
     * @var авторизованный пользователь
     */
    static protected $user;

    /**
     * @var ошибка
     */
    static protected $error;


    static public function  db(){
        if ( defined('DB_HOST') ) {
            self::$db = new \mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            if (self::$db->connect_error) {
                throw new \Exception('Нет соединения с БД');
            }else{
                self::$db->set_charset("utf8");
            }
        }
    }

    /**
     * @param $root_directory
     * @param $root_path
     */
    static public function start($root_directory,$root_path)
    {
        self::$root_directory = rtrim($root_directory,DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
        self::$root_path = $root_path;
        self::parseRequestUri();

        //print_r(self::$qsc);
        //print_r($_SERVER);

        try {
            self::db();
        }catch(\Exception $ex){
            self::set("error",$ex->getMessage());
            $action = new \Action\ErrorPage();
            $action->execute();
            exit;
        }

        /*редиректы*/
        $redirect_model = new Redirect();
        $redirect = $redirect_model->getByLink($_SERVER["REQUEST_URI"]);
        if($redirect && !$redirect["hide"]) {
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: ".$redirect["new_url"]);
            exit();
        }

        self::$settings = new Settings();

        session_start();

        //авторизация
        self::$user = null;

        if($_SESSION["user"]){
            list($login, $password) = array($_SESSION['user']['email'], $_SESSION['user']['password']);
            unset($_SESSION['user']);
            if($login && $password){
                $user_model = new \Model\Stp\User();
                if($user = $user_model->auth($login,$password,true)){
                    self::$user = $user;
                    $_SESSION['user'] = $user;
                }
            }
        }
        //тест TODO убрать
        //$u_m = new \Model\Stp\User();
        //self::$user = $u_m->getById(1);
        //self::$user = array("id"=>1,"email"=>"comstars@rambler.ru","name"=>"Игорек","phone"=>"+734334344");

        $name_main_part = self::$qsc[0];//implode('',self::$qsc); //TODO здесь нужно определиться какую-то часть пути учитывать или весь путь
        if($name_main_part=="")
            $name_main_part = "Index";
        $action_class_file = self::$root_directory.implode(DIRECTORY_SEPARATOR,[
            'inc',
            'classes',
            'Action',
            "$name_main_part.action.php"
        ]);
        //echo $action_class_file;exit;
        //если не найден файл с указанным экшном, то скармливаем диспетчеру
        if(file_exists($action_class_file)) {
            $action_class = "\Action\\" . $name_main_part;//echo $action_class;exit;
            $action = new $action_class;
        }
        else {
            $action = new \Action\Dispatcher();
        }
        //echo $action_class_file;exit;
        $action->execute();
    }

    /**
     * @param $root_directory
     * @param $root_path
     */
    static public function startCli($root_directory,$root_path)
    {
        self::$root_directory = rtrim($root_directory,DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
        self::$root_path = $root_path;
        self::parseCliRequest();

        //print_r(self::$qsc);
        //print_r($_SERVER);

        try {
            self::db();
        }catch(\Exception $ex){
            self::set("error",$ex->getMessage());
            $action = new \Action\ErrorPage();
            $action->execute();
            exit;
        }

        self::$settings = new Settings();

        $name_main_part = self::$qsc[0];//implode('',self::$qsc); //TODO здесь нужно определиться какую-то часть пути учитывать или весь путь
        if($name_main_part=="")
            $name_main_part = "Index";
        $action_class_file = self::$root_directory.implode(DIRECTORY_SEPARATOR,[
                'inc',
                'classes',
                'Action',
                "$name_main_part.action.php"
            ]);
        //echo $action_class_file;exit;
        //если не найден файл с указанным экшном, то скармливаем диспетчеру
        if(file_exists($action_class_file)) {
            $action_class = "\Action\\" . $name_main_part;
            $action = new $action_class;
        }
        else {
            $action = new \Action\Dispatcher();
        }
        //echo $action_class_file;exit;
        $action->execute();
    }

    /**
     * @param $root_directory
     * @param $root_path_admin
     * @throws \Exception
     */
    static public function startAdmin($root_directory,$root_path_admin)
    {
        self::$root_directory = rtrim($root_directory,DIRECTORY_SEPARATOR)."/";
        self::$root_path_admin = $root_path_admin;
        self::parseRequestUri();

        //print_r(self::$qsc);
        //print_r($_SERVER);

        self::db();

        self::$settings = new Settings();

        session_start();

        //авторизация
        self::$user = null;

        /*if($_SESSION["user"]){
            list($login, $password) = array($_SESSION['user']['email'], $_SESSION['user']['password']);
            unset($_SESSION['user']);
            if($login && $password){
                $user_model = new \Model\Stp\User();
                if($user = $user_model->auth($login,$password,true)){
                    self::$user = $user;
                    $_SESSION['user'] = $user;
                }
            }
        }*/
        //тест TODO убрать
        //$u_m = new \Model\Stp\User();
        //self::$user = $u_m->getById(1);
        //self::$user = array("id"=>1,"email"=>"comstars@rambler.ru","name"=>"Игорек","phone"=>"+734334344");

        /*  $name_main_part = self::$qsc[0];//implode('',self::$qsc); //TODO здесь нужно определиться какую-то часть пути учитывать или весь путь
           if($name_main_part=="")
               $name_main_part = "Index";
           $action_class_file = self::$root_directory.implode(DIRECTORY_SEPARATOR,[
                   'inc',
                   'classes',
                   'Action',
                   'Admin',
                   "$name_main_part.action.php"
               ]);
           //echo $action_class_file;exit;
           //если не найден файл с указанным экшном, то скармливаем диспетчеру
           if(file_exists($action_class_file)) {
               $action_class = "\Action\\" . $name_main_part;
               $action = new $action_class;
           }
           else {
               $action = new \Action\Admin\Index();
           }
           $action->execute();*/
    }

    /**
     * @param $name
     * @return string|null
     */
    static public function get($name)
    {
        $value = null;
        switch ( $name )
        {
            case 'root_directory' :
                $value = self::$root_directory;
                break;
            case 'root_path' :
                $value = self::$root_path;
                break;
            case 'db' :
                $value = self::$db;
                break;
            case 'settings' :
                $value = self::$settings;
                break;
            case 'qs' :
                $value = self::$qs;
                break;
            case 'qsc' :
                $value = self::$qsc;
                break;
            case 'user' :
                $value = self::$user;
                break;
            case 'error' :
                $value = self::$error;
                break;

        }
        return $value;
    }

    static public function set($name,$value)
    {
        switch ( $name )
        {
            case 'user' :
                self::$user = $value;
                break;
            case 'error' :
                self::$error = $value;
                break;
        }
    }


    /**
     * @param $error_code
     */
    static public function gotoErrorPage($error_code)
    {
        switch ( $error_code )
        {
            case 403:
                header("HTTP/1.0 403 Forbidden");
                break;
            case 404:
                header("HTTP/1.1 404 Not Found");
                break;
        }

        //$file = self::$root_directory.implode(DIRECTORY_SEPARATOR,['m', $error_code, 'index.php']);
        //if ( file_exists($file) )
        //    require_once $file;

        exit;
    }


    /**
     * @return array|bool
     */
    static protected function parseRequestUri()
    {
        $_SERVER["QUERY_STRING"] = preg_replace("/\&/", "?",  $_SERVER["QUERY_STRING"], 1);
        $request_uri = preg_replace("/^".str_replace("/","\/",self::$root_path)."/i","", $_SERVER["QUERY_STRING"]);//REQUEST_URI

        self::$qs = self::$qsc = [];

        if ( !$request_uri )
            return false;

        $request_uri = explode('?', urldecode($request_uri));
//print_r($_GET);
        /*if ( $_GET )
            foreach ( $_GET as $k=>$g )
                unset($_GET[$k]);

        if ( isset($request_uri[1]) && ( $gets = explode('&',$request_uri[1]) ) ) {
            //print_r($gets);
            foreach ($gets as $get) {
                if ($get) {
                    $get = explode('=', $get);
                    $_GET[$get[0]] = isset($get[1]) ? $get[1] : '';
                }
            }
        }*/

        $request_uri = $request_uri[0];
        $request_uri = explode('/', $request_uri);

        foreach ( $request_uri as $e )
        {
            if ( $e=="" )
                continue;

            if ( strpos($e, '?') !== false )
            {
                $e = explode('?', $e);
                $e = $e[0];
                if ( !$e )
                    continue;
            }

            $e = mb_strtolower($e);
            self::$qs[] = $e;
            $_qsc = "";
            $qsc_arr = explode('-',$e);
            foreach($qsc_arr as $qsc_item)
                $_qsc.=ucfirst($qsc_item);
            self::$qsc[] = $_qsc;
        }
        return self::$qs;
    }

    /**
     * @return array|bool
     */
    static protected function parseCliRequest()
    {
        $options = getopt("a:m::");

        $e = mb_strtolower($options["a"]);
        self::$qs[0] = $e;
        self::$qsc[0] = ucfirst($e);

        if($options["m"])
            $_REQUEST["action"] = $options["m"];

        return self::$qs;
    }
}