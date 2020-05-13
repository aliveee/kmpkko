<?php

namespace Action;

class Dispatcher extends \Action\Base
{
    public function execute()
    {
        //просто прокидываем дальше
        return $this->defaultAction();
    }

    public function defaultAction(){
        //$qs = \Lib\App::get("qs");
        //echo "0";exit;
        $page_action = new Page();
        if (!$page_action->execute()) {
            $catalog_action = new Catalog();
            if (!$catalog_action->execute()) {
                $article_action = new Articles();
                if (!$article_action->execute()) {
                    $page_action->gotoErrorPage();
                }
            }
        }
    }
}