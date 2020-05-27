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
                [
                    "banners" => (new \Model\Common\Banner())->getMain(5),
                    "catalog_menu" => (new \Model\Common\Catalog())->getMenu(true),
                    "page_type"=>"home",
                    "articles"=>(new Article())->getMain(),
                    "projects" => (new \Model\Base(PRX."project"))->getAllWhere("hide=0 and is_main=1","sort,date_created desc,name")
                ]
            )
        );
        $this->displayLayout('index');
    }
}