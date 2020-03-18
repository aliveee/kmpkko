<?php
/**
 * Created by PhpStorm.
 * User: Павел
 * Date: 02.08.2019
 * Time: 14:46
 */

namespace Lib;


class CatalogHelper
{
    public static function GetUrl($c_path, $c_link){
        if(SHORT_URL_MODE)
            return "/".$c_link."/";
        else
            return '/catalog'.($c_path?"/".$c_path:"")."/".$c_link."/";
    }
}