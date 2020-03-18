<?php

namespace Action;

class Base
{
    protected $data;
    protected $assigned_data;

    public function execute()
    {
        $qs = \Lib\App::get("qs");
        //print_r($qs);exit;

        $action = (count($qs)>1?$qs[1]:"default").'Action';
        if ( !method_exists($this,$action) ){
            $action = "defaultAction";
        }
        if ( !method_exists($this,$action) )
        {
            $page_action = new \Action\Page();
            $page_action->gotoErrorPage();
            exit;
        }else {
            //echo "111";
            return $this->$action();
        }
    }

    /**
     * отображаем страницу (имя файла шаблона без расширения из папки /views/layouts
     * @param $view
     */
    public function displayLayout($view)
    {
        echo $this->render(\Lib\App::get('root_directory')."views/layouts/{$view}.php");
    }

    public function display($view = '')
    {
        if ( IS_AJAX )
        {
            header("Content-Type: application/json");
            echo json_encode($this->assigned_data,JSON_PRETTY_PRINT);
        }
        else {
            echo $this->render($view);
        }
    }

    public function pass($data){
        $this->assigned_data = $data;
        //print_r($this->assigned_data);
    }

    protected function assign($name,$value)
    {
        $this->assigned_data[$name] = $value;
    }


    protected function render($view,$data=[])
    {
        $content = '';
        if ( $view && file_exists($view) )
        {
            empty($this->assigned_data) || extract($this->assigned_data);
            empty($data) || extract($data);
            ob_start();
            include $view;
            $content = ob_get_clean();
        }else{
            //$page_action = new \Action\Page();
            //$page_action->gotoErrorPage();
            //exit;
            throw new \Exception("view not found $view");

        }
        return $content;
    }
}