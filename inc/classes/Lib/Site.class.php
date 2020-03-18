<?php

namespace Lib;

use Lib\App;
use Model\Common\Catalog;
use Model\Common\Counter;
use Model\Common\Page;

class Site
{
    static protected $data;

    /**
     * @return array
     * получаем общие для всех страниц данные
     */
    public static function getCommonFrontendData($force=false)
    {
        if (!self::$data || $force) {
            $catalog_model = new Catalog();
            $catalog_menu = $catalog_model->getMenu();
            $counter_model = new Counter();
            $page_model = new Page();
            $qs = (App::get("qs"));
            $path = "/" . implode("/", $qs) . ($qs ? "/" : "");
            $page = $page_model->getByLink($path);
            if ($page) {
                if (!$page["title"])
                    $page["title"] = $page["name"];
                if (!$page["keywords"])
                    $page["keywords"] = $page["name"];
                if (!$page["description"])
                    $page["description"] = $page["name"];
            }

            $result = array(
                "catalog_menu" => $catalog_menu,
                "counters" => $counter_model->getAllStr(),
                "user" => App::get("user"),
                "page" => $page,
                "path" => $path,
                "menu" => $page_model->getMenu(),
                "footer_menu" => $page_model->getFooterMenu(true),
                "settings"=>App::get("settings")
            );
            self::$data = $result;
        }
        return self::$data;
    }
}