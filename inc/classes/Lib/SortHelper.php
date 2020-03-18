<?php
/**
 * Created by PhpStorm.
 * User: Павел
 * Date: 28.03.2019
 * Time: 14:57
 */

namespace Lib;

class SortHelper
{
    public static function getSort(){
        $sort=intval($_GET["sort"]);
        if($sort){
            $_SESSION["sort"] = $sort;
        }else {
            $sort = $_SESSION["sort"];
        }
        if(!$sort){
            $sort = SORT_DEFAULT;
            $_SESSION["sort"] = $sort;
        }
        return $sort;
    }

    public static function getSortOptions(){
        return array(
            SORT_PRICE_ASC=>"по возрастанию цены",
            SORT_PRICE_DESC=>"по убыванию цены",
            SORT_AVAILABILITY_DESC=>"по наличию"
        );
    }

    public static function getPromoPageSortOptions(){
        return array(
            SORT_PRICE_ASC=>"по возрастанию цены",
            SORT_PRICE_DESC=>"по убыванию цены"
        );
    }
}