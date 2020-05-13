<?php

namespace Action;

class Articles extends \Action\Base
{
    public function defaultAction(){
        $articles_model = new \Model\Common\Article();

        $qs = \Lib\App::get("qs");

        if(count($qs)>1) {

            $article = $articles_model->getByLink($qs[1]);
            if($article){
                $next = $articles_model->getNext($article["date"]);
                $prev = $articles_model->getPrev($article["date"]);
                //хк
                $breadcrumbs = array("Главная"=>"/","Статьи"=>"/articles/",$article["name"]=>"");

                $page["name"] = $article["name"];

                $site = new \Lib\Site();
                $common_data = $site->getCommonFrontendData();
                if(!$common_data["page"]){
                    $common_data["page"] = array(
                        "title"=>$article["title"]?$article["title"]:$article["name"],
                        "keywords"=>$article["keywords"]?$article["keywords"]:$article["name"],
                        "description"=>$article["description"]?$article["description"]:$article["name"],
                        "name"=>$article["name"],
                        "h1"=>$article["name"]
                    );
                }

                $this->pass(
                    array_merge($common_data,
                        array(
                            "bg_class"=>"bg-light-grey",
                            "article" => $article,
                            "next" =>$next,
                            "prev" =>$prev,
                            "breadcrumbs" => $breadcrumbs,
                            "sort_options"=>\Lib\SortHelper::getSortOptions(),
                            "page_type"=>"other",
                            "component"=>"article"
                        ))
                );
            }else{
                $page_action = new \Action\Page();
                $page_action->gotoErrorPage();
            }
        }else{
            //хк
            $breadcrumbs = array("Главная"=>"/","Статьи"=>"");

            $site = new \Lib\Site();
            $common_data = $site->getCommonFrontendData();
            if(!$common_data["page"]){
                $common_data["page"] = array(
                    "title"=>"Полезная информация",
                    "keywords"=>"Полезная информация",
                    "description"=>"Полезная информация",
                    "name"=>"Полезная информация",
                    "h1"=>"Полезная информация"
                );
            }

            $articles = $articles_model->getLast(900);

            $this->pass(
                array_merge($common_data,array(
                    "bg_class"=>"bg-light-grey",
                    "articles" => $articles,
                    "breadcrumbs" => $breadcrumbs,
                    "sort_options"=>\Lib\SortHelper::getSortOptions(),
                    "page_type"=>"other",
                    "component"=>"articles"
                ))
            );
        }

        $this->view = \Lib\App::get('root_directory').'views/layouts/common.php';
        $this->display($this->view);

        return true;
    }
}