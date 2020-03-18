<?php

namespace Lib;

class GoodHelper
{
    public static $DEFAULT_AVAILABILITY = GOOD_STATUS_NA;
    public static $IMPORT_MISSED_AVAILABILITY = GOOD_STATUS_ONTHEWAY;
    public static $AVAILABILITY_STRICT = [GOOD_STATUS_NA=>"нет в наличии", GOOD_STATUS_AVAILABLE=>"В наличии", GOOD_STATUS_DELAY=>"Под заказ 1-3 дня", GOOD_STATUS_ONTHEWAY=>"В пути"];
    public static $AVAILABILITY = [""=>"нет в наличии", GOOD_STATUS_NA=>"нет в наличии", GOOD_STATUS_AVAILABLE=>"В наличии", GOOD_STATUS_DELAY=>"Под заказ 1-3 дня", GOOD_STATUS_ONTHEWAY=>"В пути"];
    public static $AVAILABILITY_KEYS = ["нет в наличии"=>GOOD_STATUS_NA, "в наличии"=>GOOD_STATUS_AVAILABLE, "под заказ 1-3 дня"=>GOOD_STATUS_DELAY, "в пути"=>GOOD_STATUS_ONTHEWAY];

    public static function getGoodLiteralByCount($n){
        $map = [0=>"товар",1=>"товар" ,2=>"товара",3=>"товара",4=>"товара",5=>"товаров",6=>"товаров",7=>"товаров",8=>"товаров",9=>"товаров"];
        return $map[$n%10];
    }

    public static function GetUrl($c_path, $c_link, $g_link){
        if(SHORT_URL_MODE)
            return "/".$g_link."/";
        else
            return '/catalog'.($c_path?"/".$c_path:"")."/".$c_link."/".$g_link."/";
    }

    /**
     * @param array $row товар
     * @param int $w ширина
     * @param int $h  длина
     * @return string
     */
    public static function GetImgUrl($row,$w,$h, $file_name=null){
        if(GOOD_IMAGE_USER_MODE /*&& SHORT_URL_MODE*/){
            //return "/uploads/good/".($w??"-")."x".($h??"-")."/".$row["id"]."/".$row["img"];
            return "/".$row["link"]."/".($w??"-")."x".($h??"-")."/".($file_name??pathinfo($row["img"],PATHINFO_FILENAME).".jpg");
        }else
            return "/uploads/good/".($w??"-")."x".($h??"-")."/".($file_name??$row["id"].".jpg");
    }

    public static function getAvailabilityName($availability){
        return self::$AVAILABILITY[$availability];
    }

    public static function getButtonName($availability){
        $map = [""=>"В корзину", "na"=>"В корзину", "available"=>"Купить", "delay"=>"В корзину", "ontheway"=>"Уведомить"];
        return $map[$availability];
    }
}