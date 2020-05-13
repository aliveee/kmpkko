<?php

namespace Action;


class Sitemap_xml extends \Action\Base
{
    public function defaultAction()
    {
        //каталог+страницы+новости+статьи
        $page_model = new \Model\Common\Page();
        $articles_model = new \Model\Common\Article();
        $catalog_model = new \Model\Common\Catalog();
        $good_model =new \Model\Common\Good();

        $pages = $page_model->getAllWhere("hide=0 and is_service<>1");
        $articles = $articles_model->getAllWhere("hide=0");
        $catalogs = $catalog_model->getAllWhere("hide=0");
        $ids = array_column($catalogs,"id");
        $goods = $good_model->getByCatalogs(implode(',',$ids));

        $this->pass(
            array(
                "catalogs" => $catalogs,
                "pages" =>$pages,
                "articles" => $articles,
                "goods"=>$goods
            )
        );
        header('Content-type: text/xml');
        $this->view = \Lib\App::get('root_directory').'views/layouts/sitemap.php';
        $this->display($this->view);
        return true;

    }
}