<?php

namespace Action;

use Lib\Site;
use Model\Common\Article;

class Index extends \Action\Base
{
    public function defaultAction(){
        $common_data = Site::getCommonFrontendData();
        $common_data["page"]["head_include"] = \Lib\App::get("settings")->head_index;

        $this->pass(
            array_merge($common_data,
                array(
                    "banners" => (new \Model\Common\Banner())->getMain(5),
                    "catalog_menu" => (new \Model\Common\Catalog())->getMenu(true),
                    "page_type"=>"home",
                    "articles"=>(new Article())->getMain()
                )
            )
        );
        $this->displayLayout('index');
    }
}