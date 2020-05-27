<?php

namespace Action;

use Model\Common\Feature;

class Projects extends \Action\Base
{
    public function defaultAction(){
        $common_data = (new  \Lib\Site())->getCommonFrontendData();
        $breadcrumbs = ["Главная"=>"/",'Проекты'=>'/projects/'];
        $projects = (new \Model\Base(PRX."project"))->getAllWhere("hide=0","sort,date_created desc,name");
        foreach($projects as $_k=>$_project){
            if(file_exists($img = '/uploads/project/'.$_project['id'].'.jpg')){
                $projects[$_k]['images'][] = $img;
            }
            $files = glob(DOCUMENT_ROOT . '/uploads/project/'.$_project['id'].'_*.*');
            foreach ($files as $fn) {
                $fn = basename($fn);
                $projects[$_k]['images'][] = '/uploads/project/'.$fn;
            }
        }
        $this->pass(
            array_merge($common_data,
                array(
                    "projects"=>$projects,
                    "breadcrumbs" => $breadcrumbs,
                    "page_type"=>"custom",
                    "component"=>"projects",
                    "bg_class"=>"bg-light-grey",
                    "js"=>['<link href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" rel="stylesheet" />',
                            '<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>']
                )
            )
        );
        $this->view = \Lib\App::get('root_directory').'views/layouts/common.php';
        $this->display($this->view);

        return true;
    }
}