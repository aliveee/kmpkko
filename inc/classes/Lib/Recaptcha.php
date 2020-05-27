<?php
/**
 * Created by PhpStorm.
 * User: Павел
 * Date: 05.02.2020
 * Time: 12:14
 */

namespace Lib;


class Recaptcha
{
    public static function verify(){
        if(!defined('RECAPTCHA_ENABLED') || !RECAPTCHA_ENABLED){
            return true;
        }
        $recaptcha_result=json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".RECAPTCHA_SECRET_KEY."&response=".\Lib\Helper::clean($_POST["recaptcha"])."&remoteip=".Helper::getIp()),true);
        return $recaptcha_result['success'];
    }
}