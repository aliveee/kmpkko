<?php
/**
 * Created by PhpStorm.
 * User: Павел
 * Date: 09.02.2019
 * Time: 12:23
 */

namespace Model\Common;


class Catalog extends \Model\Base
{
    /**
     * @var string
     */
    protected $table = PRX.'catalog';


    public function getFirstLevel()
    {
        return $this->getAll("SELECT * FROM {$this->table} WHERE id_parent='0' AND hide='0' ORDER BY sort,id");
    }

    public function getByParent($pid)
    {
        return $this->getAll("SELECT * FROM {$this->table} WHERE id_parent='{$pid}' AND hide='0' ORDER BY sort,id");
    }

    public function getByLink($link){
        return $this->getAll("SELECT * FROM {$this->table} WHERE link='{$link}' AND hide='0' ORDER BY sort,id");
    }

    public function getByLinkAndParents($link,$parents){
        return $this->getAll("SELECT * FROM {$this->table} WHERE link='{$link}' ".($parents?"and id_parent in(".implode(',',$parents).")":"")." AND hide='0' ORDER BY sort,id");
    }

    /**
     * получить каталог по массиву слагов
     *
     * @param $slugs
     * @return bool|mixed
     */
    public function getBySlugs($slugs){
        $catalogs = $this->getByLink(end($slugs));
        if(!$catalogs)
            return false;
        $slugs = array_reverse($slugs);
        foreach($catalogs as $key=>$catalog) {
            $parent = $catalog;
            for ($i = 1; $i < count($slugs); $i++) {
                $slug = $slugs[$i];
                $parent = $this->getById($parent["id_parent"]);
                if(!$parent || $parent["link"]!=$slug) {
                    unset($parent[$key]);
                    continue;
                }else{
                    $catalogs[$key]["parents"][] = $parent;
                }
            }
        }
        reset($catalogs);
        if(count($catalogs))
            return current($catalogs);
        else
            return false;
    }

    //рассчитано только на 3 уровня
    public function getMenu($is_main=false){
        //$max_level = 4;
        $menu = array();

        $res = $this->getAll("SELECT id,id_parent,ids_parent,name,link,image,sort,path,ids_path,`level` FROM {$this->table} WHERE hide=0 ".($is_main?" and is_main=1 ":"")." ORDER BY `level`,sort");

            foreach($res as $item){
                if(!$item["ids_path"])
                    $ids = array();
                else
                    $ids = explode(',',$item["ids_path"]);
               switch(count($ids)){
                   case 0:
                       $menu[$item["id"]] =$item;
                       break;
                   case 1:
                       if(array_key_exists($ids[0],$menu))
                            $menu[$ids[0]]["submenu"][$item["id"]] =$item;
                       break;
                   case 2:
                       if(array_key_exists($ids[0],$menu) && array_key_exists($ids[1],$menu[$ids[0]]["submenu"]))
                            $menu[$ids[0]]["submenu"][$ids[1]]["submenu"][$item["id"]] =$item;
                       break;
                   case 3:
                       if(array_key_exists($ids[0],$menu) && array_key_exists($ids[1],$menu[$ids[0]]["submenu"])  && array_key_exists($ids[2],$menu[$ids[0]]["submenu"][$ids[1]]["submenu"]))
                            $menu[$ids[0]]["submenu"][$ids[1]]["submenu"][$ids[2]]["submenu"][$item["id"]] =$item;
                       break;
               }
            }

        return $menu;
    }

    public function searchByName($name, $limit=10){
        //echo "SELECT DISTINCT name FROM {$this->table} WHERE name LIKE '%{$name}%' and hide=0 ORDER BY name LIMIT {$limit}";
        return $this->getAll("SELECT id,name, path,link FROM {$this->table} WHERE name LIKE '%{$name}%' and hide=0 ORDER BY name LIMIT {$limit}");
    }

    public function getBreadcrumbs($catalog_ids){
        return $this->getAll("SELECT id,name, path,link FROM {$this->table} WHERE find_in_set(id,'{$catalog_ids}') and hide=0 ORDER BY level");
    }

    public function getSubcategories($id, $is_main=false){
        return $this->getAll("SELECT id,name,path,link,introtext FROM {$this->table} WHERE id_parent='{$id}' and hide=0 ".($is_main?" and is_main=1 ":"")." ORDER BY level,sort");
    }

    /*
     * получить все дочерние подкаталоги всех уровней
     */
    public function getSubcategoriesIds($ids_path){
        //echo "SELECT group_concat(id) FROM {$this->table} WHERE ids_path like '{$ids_path}%' and hide=0 ORDER BY level,sort";
        return $this->getField("SELECT group_concat(id) FROM {$this->table} WHERE ids_path like '{$ids_path}%' and hide=0 ORDER BY level,sort");
    }

    /*
     * получить подкатегории по списку Id Товаров
     */
    public function getSubcategoriesByGoodIds($ids){
        return $this->getAll("SELECT c.id,c.name, c.path,c.link FROM {$this->table} c inner join ".PRX."good g on g.id_catalog=c.id WHERE find_in_set(g.id,'{$ids}') and g.hide=0 group by c.id ORDER BY c.level,c.sort");
    }

    public function add($id,$id_parent,$name,$link){
        $sql = "insert into ".$this->table."(id,id_parent,name,link,is_new) values({$id},{$id_parent},'{$name}','{$link}',1)";
        echo "<br/>".$sql;
        $this->query($sql);
        return $this->getInsertId();
    }

    /**
     * получить каталоги у которых есть товары
     * @return array|bool
     * @throws \Github\Exception
     */
    public function getCatalogsHavingGoods(){
        return $this->getAll("SELECT c.id,g.id as gid FROM {$this->table} c inner join ".PRX."good g on g.id_catalog=c.id group by c.id");
    }

    public function getByName($name)
    {
        return $this->getRow("SELECT * FROM {$this->table} WHERE name = '{$name}'");
    }
}