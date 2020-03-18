<?php
/**
 * Created by PhpStorm.
 * User: Павел
 * Date: 09.02.2019
 * Time: 12:23
 */

namespace Model\Common;


class Feature extends \Model\Base
{
    /**
     * @var string
     */
    protected $table = PRX.'feature';
    protected $table_fv = PRX.'feature_good';
    protected $table_catalog = PRX.'catalog';
    protected $table_good = PRX.'good';
    protected $table_feature_catalog = PRX.'feature_catalog';

    /*
     * Получить характеристики для списка id каталогов с учетом того, какие характеристики есть у товаров
     */
    /*public function getFeaturesGoodsByCatalogIds($ids)
    {
        $sql = "
        SELECT f.*
        FROM `{$this->table}` f
        INNER JOIN `{$this->table_fv}` fv ON f.id=fv.`id_feature`
        INNER JOIN `{$this->table_good}` g ON fv.`id_good`=g.id
        INNER JOIN `{$this->table_catalog}` c ON g.`id_catalog`=c.id
        WHERE find_in_set(c.id,'{$ids}') AND vitrina=1
        GROUP BY f.id
        ORDER BY f.sort";
        //echo $sql;
        return $this->getAll($sql);
    }*/

    /**
     * Получить характеристики товара
     * @param $gid
     */
    public function getByGood($gid){
        $sql = "
        SELECT f.*,fv.value FROM `{$this->table}` f
        INNER JOIN `{$this->table_fv}` fv ON fv.id_feature = f.id
        WHERE fv.id_good={$gid} and fv.value>'' ORDER BY f.sort";
        //echo $sql;
        return $this->getAll($sql);
    }

    /**
     * Получить уникальные характеристики у нескольких товаров (для сравнения)
     * @param $gid
     */
    public function getByGoods($gids){
        $sql = "
        SELECT distinct f.* FROM `{$this->table}` f
        INNER JOIN `{$this->table_fv}` fv ON fv.id_feature = f.id
        WHERE fv.id_good in ({$gids}) and fv.value>'' ORDER BY f.sort";
        //echo $sql;
        return $this->getAll($sql);
    }

    /*
     * Получить характеристики для списка id каталогов без учета того, какие характеристики есть у товаров
     */
    public function getFeaturesByCatalogIds($ids)
    {
        $sql = "
        SELECT f.* FROM `{$this->table}` f
        INNER JOIN `{$this->table_feature_catalog}` fc ON fc.id_feature = f.id
        WHERE FIND_IN_SET(fc.id_catalog,'{$ids}') AND f.vitrina=1 GROUP BY f.id ORDER BY f.sort";
        //echo $sql;
        return $this->getAll($sql);
    }

    public function getOptions($fid, $gids){
        return $this->getAll("select id,value from {$this->table_fv} where id_feature='{$fid}' and value>'' and find_in_set(id_good,'{$gids}') group by value order by value");
    }

    /**
     * Характеристика по названию
     * @param $name
     * @return array|bool
     * @throws \Github\Exception
     */
    public function getByName($name)
    {
        return $this->getRow("SELECT * FROM {$this->table} WHERE name = '{$name}'");
    }

    /**
     * Характеристика по ID импорта
     * @param $name
     * @return array|bool
     * @throws \Github\Exception
     */
    public function getByImportId($id_import)
    {
        return $this->getRow("SELECT * FROM {$this->table} WHERE id_import = '{$id_import}'");
    }

    /*public function getMinMaxOptions($fid, $gids){
        $sql = "select min(value)as min, max(value) as max from {$this->table_fv} where id_feature='{$fid}' and value>'' and find_in_set(id_good,'{$gids}')";
        //echo $sql;
        return $this->getAll($sql);
    }*/

    /**
     * Привязка хар-ки к каталогу
     * @param $id_catalog
     * @param $id_feature
     * @return mixed
     * @throws \Github\Exception
     */
    public function insertFeatureCatalog($id_catalog,$id_feature){
        $this->query("insert ignore into ".$this->table_feature_catalog."(id_catalog,id_feature) values('{$id_catalog}','{$id_feature}')");
        return $this->getInsertId();
    }

    public function insertFeatureGood($id_good,$id_feature,$value){
        $this->query("insert ignore into ".$this->table_fv."(id_good,id_feature,value) values('{$id_good}','{$id_feature}','{$value}')");
        return $this->getInsertId();
    }

}