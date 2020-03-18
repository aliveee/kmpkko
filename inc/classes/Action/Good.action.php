<?php

namespace Action;

use Model\Common\Feature;

class Good extends \Action\Base
{
    public function defaultAction(){
        $qs = (\Lib\App::get("qs"));
        //print_r($qs);
        //array_shift($qs);
        //print_r($qs);
        $catalog_model = new \Model\Common\Catalog();

        if(!SHORT_URL_MODE) {
            $catalog = $catalog_model->getBySlugs(array_slice($qs, 0, count($qs) - 1));
            if ($catalog === false) {
                return false;
            }
        }

        //найти товар
        $good_model = new \Model\Common\Good();

        $link = end($qs);

        if(!SHORT_URL_MODE) {
            $good = $good_model->getByLinkAndCatalog($link, $catalog["id"]);
        }else{
            $good = $good_model->getByLink($link);
        }

        if($good===false) {
            return false;
        }
        else{
            if(SHORT_URL_MODE) {
                $catalog = $catalog_model->getById($good["id_catalog"]);
                if ($catalog === false) {
                    return false;
                }
            }

            $active_catalog_ids = array_merge(explode(',',$catalog["ids_path"]),array($catalog["id"]));

            //хк
            $active_catalogs = $catalog_model->getBreadcrumbs(implode(',',$active_catalog_ids));
            $breadcrumbs = ["Главная"=>"/",'Каталог'=>count($qs)==1?'':'/catalog/'];
            foreach($active_catalogs as $c){
                $breadcrumbs[$c["name"]] = \Lib\CatalogHelper::GetUrl($c["path"],$c["link"]);
            }
            $breadcrumbs[$good["name"]]="";

            //изображения
            $files=GLOB(DOCUMENT_ROOT . "/uploads/good/" . $good["id"]. "_{*.*}", GLOB_BRACE);
            $images=[];
            foreach($files as $f){
                $pi = pathinfo($f,PATHINFO_FILENAME);
                if(basename($f)!=$good["img"])
                    $images[]=$pi.".jpg";
                else
                    $main_image = $pi.".jpg";
            }

            $feature_model = new \Model\Common\Feature();

            $site = new \Lib\Site();
            $common_data = $site->getCommonFrontendData();

            if(!$common_data["page"]["title"]) {
                $t = \Lib\App::get("settings")->title_good_template;
                $common_data["page"]["title"] = $good["title"]?$good["title"]:($t?str_replace("{unit}",$good["unit"],str_replace("{price}",intval($good["price"]),str_replace("{name}",$good["name"],$t))):$good["name"]);
            }
            if(!$common_data["page"]["keywords"]) {
                $t = \Lib\App::get("settings")->keywords_good_template;
                $common_data["page"]["keywords"] = $good["keywords"]?$good["keywords"]:($t?str_replace("{unit}",$good["unit"],str_replace("{price}",intval($good["price"]),str_replace("{name}",$good["name"],$t))):$good["name"]);
            }
            if(!$common_data["page"]["description"]) {
                $t = \Lib\App::get("settings")->description_good_template;
                $common_data["page"]["description"] = $good["description"]?$good["description"]:($t?str_replace("{unit}",$good["unit"],str_replace("{price}",intval($good["price"]),str_replace("{name}",$good["name"],$t))):$good["name"]);
            }

            $this->pass(
                array_merge($common_data,
                    array(
                        "active_catalog_ids" => array_merge(explode(',',$catalog["ids_path"]),array($catalog["id"])),
                        "catalog"=>$catalog,
                        "good" => $good,
                        "features"=>(new Feature())->getByGood($good["id"]),
                        "breadcrumbs" => $breadcrumbs,
                        "images"=>$images,
                        "main_image" => $main_image,
                        "features" => $feature_model->getByGood($good["id"]),
                        "page_type"=>"product",
                        "js"=>array("<script src='/js/good.js'></script>")
                    )
                )
            );
            //echo '<br/>Время выполнения скрипта: '.round(microtime(true) - $start, 4).' сек.';
            $this->view = \Lib\App::get('root_directory').'views/layouts/good.php';
            $this->display($this->view);

            return true;
        }
        return false;

    }
}