<?php

namespace Action;

use Model\Common\Feature;

class Catalog extends \Action\Base
{


    /**
     * отображение каталога
     * @return bool
     */
    public function defaultAction(){
        //найти каталог
        $catalog_model = new \Model\Common\Catalog();
        $qs = \Lib\App::get("qs");
        $catalog_home = count($qs)==1 && $qs[0]='catalog';
        if($catalog_home)
            $catalog = ["id"=>0];
        else {
            if (SHORT_URL_MODE) {
                $catalog = $catalog_model->getByLink(end($qs));
                if ($catalog)
                    $catalog = $catalog[0];
            } else
                $catalog = $catalog_model->getBySlugs($qs);
        }

        if($catalog===false) {
            $r = (new Good())->execute();
            if(!$r){
                (new Page())->gotoErrorPage();
            }else
                return $r;
        }

        if($catalog){
            $good_model = new \Model\Common\Good();
            $active_catalog_ids = array_merge(explode(',',$catalog["ids_path"]),array($catalog["id"]));
            $subcategories = $catalog_model->getSubcategories($catalog["id"]);

            //хк
            $active_catalogs = $catalog_model->getBreadcrumbs(implode(',',$active_catalog_ids));
            $breadcrumbs = ["Главная"=>"/",'Каталог'=>count($qs)==1?'':'/catalog/'];
            foreach($active_catalogs as $c){
                if($c!=end($active_catalogs)) {
                    $breadcrumbs[$c["name"]] = \Lib\CatalogHelper::GetUrl($c["path"],$c["link"]);
                } else {
                    $breadcrumbs[$c["name"]] = "";
                }
            }
            //---------получаем список товаров
            //все id товаров текущего каталога без учета фильтров, пейджинга и сортировки
            $goods = $good_model->getByCatalogs($catalog["id"],"id");
            //список id товаров текущего каталога без учета фильтров, пейджинга и сортировки
            $gids = implode(',', $goods);
            if($gids) {
                //количество товаров всего
                $goods_count = $good_model->getCountByFeatures([],$gids);
                //сортировка
                $sort = \Lib\SortHelper::getSort();
                //пейджинг
                $current_catalog_page = \Lib\PagingHelper::getPage();
                $catalog_pages = \Lib\PagingHelper::getMaxPage($goods_count);
                //если передан номер несуществующей страницы
                if($current_catalog_page<0 || $current_catalog_page>=$catalog_pages)
                    return false;
                //товар для текущей выдачи
                $goods = $good_model->getByFeatures([],$gids,$sort, $current_catalog_page*PAGING_PER_PAGE,PAGING_PER_PAGE);
            }

            $feature_model = new Feature();
            foreach($goods as $_k=>$_good){
                $goods[$_k]["features"] = $feature_model->getByGood($_good["id"]);
            }

            $site = new \Lib\Site();

            //апдейтим мета данные по шаблонам метаданных их настроек
            $common_data = $site->getCommonFrontendData();
            if(!$common_data["page"]["title"]) {
                $common_data["page"]["title"] = $catalog["title"]? $catalog["title"]:$catalog["name"];
                /*$t = \Lib\App::get("settings")->title_category_template;
                $common_data["page"]["title"] =
                    $catalog["title"]?
                        $catalog["title"]:
                        ($t?\Lib\Helper::parseThroughTemplate($t, ["name"=>$catalog["name"]]):$catalog["name"]);*/
            }

            if(!$common_data["page"]["keywords"]) {
                $common_data["page"]["keywords"] = $catalog["keywords"]? $catalog["keywords"]:$catalog["name"];
                /*$t = \Lib\App::get("settings")->keywords_category_template;
                $common_data["page"]["keywords"] = $catalog["keywords"]?
                    $catalog["keywords"]:
                    ($t?\Lib\Helper::parseThroughTemplate($t, ["name"=>$catalog["name"]]):$catalog["name"]);*/
            }
            if(!$common_data["page"]["description"]) {
                $common_data["page"]["description"] = $catalog["description"]? $catalog["description"]:$catalog["name"];
                /*$t = \Lib\App::get("settings")->description_category_template;
                $common_data["page"]["description"] = $catalog["description"]?
                    $catalog["description"]:
                    ($t?\Lib\Helper::parseThroughTemplate($t, ["name"=>$catalog["name"]]):$catalog["name"]);*/
            }

            $common_data["page"]["h1"] = $catalog["name2"]?$catalog["name2"]:$catalog["name"];

            //апдейтим мета данные из связки каталог-бренд если она прописана в бд

            $this->pass(
                array_merge($common_data,
                    array(
                        "catalog_home" => $catalog_home,
                        "active_catalog_ids" => $active_catalog_ids,
                        "catalog"=>$catalog,
                        "goods" => $catalog["show_goods"]?$goods:[],
                        "goods_count" => $goods_count,
                        "breadcrumbs" => $breadcrumbs,
                        "subcategories"=>$subcategories,
                        "sort"=>$sort,
                        "sort_options"=>\Lib\SortHelper::getSortOptions(),
                        "current_catalog_page"=>$current_catalog_page,
                        "catalog_pages"=>$catalog_pages,
                        "page_type"=>"category",
                    )
                )
            );
            //echo '<br/>Время выполнения скрипта: '.round(microtime(true) - $start, 4).' сек.';
            $this->view = \Lib\App::get('root_directory').'views/layouts/catalog.php';
            $this->display($this->view);

            return true;
        }
    }


    public function makeImagesAction(){
        $catalog_model = new \Model\Common\Catalog();
        $ids = $catalog_model->getCatalogsHavingGoods();
        foreach($ids as $row){
            if(!file_exists(\Lib\App::get("root_directory")."/uploads/catalog/".$row["id"].".jpg")) {
                copy(\Lib\App::get("root_directory") . "/uploads/good/" . $row["gid"] . ".jpg", \Lib\App::get("root_directory") . "/uploads/catalog/" . $row["id"] . ".jpg");
                echo "<br/>".\Lib\App::get("root_directory") . "/uploads/good/" . $row["gid"] . ".jpg";
            }
        }
    }

    /**
     * аякс запрос при изменении фильтра по количеству товаров
     * @return bool
     */
    public function filterAction(){
        $catalog_model = new \Model\Common\Catalog();
        $qs = \Lib\App::get("qs");
        $catalog = $catalog_model->getBySlugs($qs);
        if($catalog===false) {
            //проверим, не выбор ли бренда
            $brand_model = new \Model\Common\Brand();
            $brand = $brand_model->getByLink(end($qs));
            if($brand){
                array_pop($qs);
                $catalog = $catalog_model->getBySlugs($qs);
            }
        }
        if($catalog===false)
            return false;

        $all_subcategories_ids = $catalog_model->getSubcategoriesIds($catalog["ids_path"]?$catalog["ids_path"].",".$catalog["id"]:$catalog["id"]);
        $good_model = new \Model\Common\Good();
        //все id товаров текущего каталога без учета фильтров, пейджинга и сортировки
        $goods = $good_model->getByCatalogs($all_subcategories_ids?$all_subcategories_ids.",".$catalog["id"]:$catalog["id"],"id");
        //список id товаров текущего каталога без учета фильтров, пейджинга и сортировки
        $gids = implode(',', $goods);
        if($gids) {
            $filters = \Lib\FeatureHelper::getFeatures($catalog, $gids);
            $count = $good_model->getCountByFeatures($filters,$gids);
        }
        else{
            $count = 0;
        }

        $this->pass(array('result'=>$count/*,'filters'=>$filters*/));
        $this->display($this->view);
        return true;
    }


    /**
     * если нажата кнопка показать еще
     * рендерим страницу товаров и отдаем
     */
    public function moreAction(){
        //найти каталог
        $catalog_model = new \Model\Common\Catalog();
        $qs = \Lib\App::get("qs");
        $catalog = $catalog_model->getBySlugs($qs);

        if($catalog===false) {
            //проверим, не выбор ли бренда
            $brand_model = new \Model\Common\Brand();
            $brand = $brand_model->getByLink(end($qs));
            if($brand){
                array_pop($qs);
                $catalog = $catalog_model->getBySlugs($qs);
            }
        }
        if($catalog===false)
            return false;
        if($catalog){
            $good_model = new \Model\Common\Good();
            //---------получаем список товаров
            $all_subcategories_ids = $catalog_model->getSubcategoriesIds($catalog["ids_path"]?$catalog["ids_path"].",".$catalog["id"]:$catalog["id"]);

            //все id товаров текущего каталога без учета фильтров, пейджинга и сортировки
            $goods = $good_model->getByCatalogs($all_subcategories_ids?$all_subcategories_ids.",".$catalog["id"]:$catalog["id"],"id");
            //список id товаров текущего каталога без учета фильтров, пейджинга и сортировки
            $gids = implode(',', $goods);
            if($gids) {
                //фильтры
                $filters = \Lib\FeatureHelper::getFeatures($catalog, $gids);
                //количество товаров всего
                $goods_count = $good_model->getCountByFeatures($filters,$gids);
                //сортировка
                $sort = \Lib\SortHelper::getSort();
                //пейджинг
                $current_catalog_page = \Lib\PagingHelper::getPage();
                $catalog_pages = \Lib\PagingHelper::getMaxPage($goods_count);
                //если передан номер несуществующей страницы
                if($current_catalog_page<0 || $current_catalog_page>=$catalog_pages)
                    return false;
                //товар для текущей выдачи
                $goods = $good_model->getByFeatures($filters,$gids,$sort, $current_catalog_page*PAGING_PER_PAGE,PAGING_PER_PAGE);
            }

            $this->pass(array(
                "cart" => \Lib\CartHelper::getCart(),
                "goods"=>$goods,
            ));

            echo $this->render(\Lib\App::get('root_directory').'views/layouts/catalog_page.php');

            return true;
        }
    }


}