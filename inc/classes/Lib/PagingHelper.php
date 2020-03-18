<?php
/**
 * Created by PhpStorm.
 * User: Павел
 * Date: 28.03.2019
 * Time: 14:57
 */

namespace Lib;


class PagingHelper
{
    public static function getPage(){
        return intval($_GET["page"]);
    }

    public static function getMaxPage($items){
        return ceil($items / PAGING_PER_PAGE);
    }
}