<?php

namespace Model\Common;

use Lib\CatalogHelper;

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

    public function getFooterMenu()
    {
        $menu = [];
        $menu_catalog = $this->getAll("SELECT c.*,c2.link as c_path FROM ".PRX."catalog c left join ".PRX."catalog c2 on c.id_parent=c2.id WHERE c.`menu_down`=1 and c.hide=0 order by c2.sort,c.sort");
        foreach($menu_catalog as $_k=>$_mc){
            $menu_catalog[$_k]['link'] = CatalogHelper::GetUrl($_mc['c_path'],$_mc['link']);
        }
        $menu_info = $this->getAll("SELECT * FROM {$this->table} WHERE `menu_down`=1 and hide=0 order by sort");
        $menu[] = ["submenu"=>$menu_catalog,"name"=>"Продукция"];
        $menu[] = ["submenu"=>$menu_info,"name"=>"Информация"];
        /*if($with_submenu){
            foreach($menu as $_k=>$_m){
                $menu[$_k]["submenu"] = $this->getAll("SELECT * FROM {$this->table} WHERE hide=0 and id_parent='{$_m['id']}' order by sort");
            }
        }*/
        return $menu;
    }
}