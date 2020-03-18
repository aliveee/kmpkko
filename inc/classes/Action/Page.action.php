<?php

namespace Action;


use Lib\Site;

class Page extends \Action\Base
{
    public function defaultAction()
    {
        $common_data = Site::getCommonFrontendData();

        $page = $common_data["page"];

        if(!$page) {
            $this->gotoErrorPage();
            exit;
        }else{
            //хк
            $breadcrumbs = array("Главная"=>"/",$page["name"]=>"");

            $this->pass(
                array_merge(
                    $common_data,
                    array(
                        "page" =>$page,
                        "breadcrumbs" => $breadcrumbs,

                        "page_type"=>"page",
                        "component"=>"page"
                    )
                )
            );
            $this->view = \Lib\App::get('root_directory').'views/layouts/common.php';
            $this->display($this->view);
            return true;
        }
        return false;
    }

    public function gotoErrorPage(){
        $page_model = new \Model\Common\Page();
        $page = $page_model->getByLink("/404/");
        if(!$page){
            \Lib\App::gotoErrorPage(404);
        }else{
            $common_data = Site::getCommonFrontendData();

            $breadcrumbs = array("Главная"=>"/",$page["name"]=>"");

            $this->pass(
                array_merge(
                    $common_data,
                    array(
                        "page" =>$page,
                        "breadcrumbs" => $breadcrumbs,

                        "page_type"=>"page_not_found",
                        "component"=>"page"
                    )
                )
            );
            $this->view = \Lib\App::get('root_directory').'views/layouts/common.php';
            header("HTTP/1.1 404 Not Found");
            $this->display($this->view);
        }
    }
}