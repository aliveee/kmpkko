<?php

namespace Model\Common;

class Page extends \Model\Base
{
    /**
     * @var string
     */
    protected $table = PRX.'page';


    public function getByLink($link)
    {
        $link = \Lib\App::get('db')->real_escape_string($link);
        $sql = "SELECT * FROM {$this->table} WHERE link = '{$link}'";
        return $this->getRow($sql);
    }

    public function getMenu()
    {
        return $this->getAll("SELECT * FROM {$this->table} WHERE menu=1 and hide=0 order by sort");
    }

    public function getFooterMenu($with_submenu=false)
    {
        $menu = $this->getAll("SELECT * FROM {$this->table} WHERE `menu_down`=1 and hide=0 order by sort");
        if($with_submenu){
            foreach($menu as $_k=>$_m){
                $menu[$_k]["submenu"] = $this->getAll("SELECT * FROM {$this->table} WHERE hide=0 and id_parent='{$_m['id']}' order by sort");
            }
        }
        return $menu;
    }
}