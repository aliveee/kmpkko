<?php
/**
 * Created by PhpStorm.
 * User: Павел
 * Date: 09.02.2019
 * Time: 12:23
 */

namespace Model\Common;


class Good extends \Model\Base
{
    /**
     * @var string
     */
    protected $table = PRX.'good';
    protected $table_feature_good = PRX.'feature_good';
    //protected $table_good_catalog = PRX.'good_catalog';

    public static function AddToCatalog($gid,$cid){
        $good_catalog_model = new \Model\Stp\GoodCatalog();
        return $good_catalog_model->insert(array("id_catalog"=>$cid, "id_good"=>$gid));
    }

    /**
     * получить массив возможных полей для импорта
     * @return array
     */
    public function getImportFields(){
        return array(
            "article"=>array("name"=>"Артикул","is_mandatory"=>true, "type"=>"Строка"),
            "brand"=>array("name"=>"Бренд","is_mandatory"=>false, "external"=>"brand", "model"=>"Brand", "type"=>"Строка"),
            "id_supplier"=>array("name"=>"Поставщик", /*"external"=>"catalog", "model"=>"Catalog",*/ "type"=>"Число"),//?
            "name"=>array("name"=>"Название","is_mandatory"=>true, "type"=>"Строка"),
            "model"=>array("name"=>"Модель", "type"=>"Строка"),
            "unit"=>array("name"=>"Единица измерения", "type"=>"Строка"),
            "text"=>array("name"=>"Описание", "type"=>"Строка","convert_new_line"=>true),
            "price"=>array("name"=>"Цена", "type"=>"Число", "is_price"=>true),
            "price_old"=>array("name"=>"Старая цена", "type"=>"Число", "is_price"=>true),
            "id_catalog"=>array("name"=>"Категория", /*"external"=>"catalog", "model"=>"Catalog",*/ "type"=>"Число"),//?
            "hide"=>array("name"=>"Скрыть", "type"=>"Логическое"),
            "ymarket"=>array("name"=>"Маркет", "type"=>"Логическое"),
            "new"=>array("name"=>"Новинка", "type"=>"Логическое"),
            "hit"=>array("name"=>"Хит продаж", "type"=>"Логическое"),
            "spec"=>array("name"=>"Акция", "type"=>"Логическое"),
            "availability"=>array("name"=>"Наличие", "type"=>"Варианты: <nobr>".implode('</nobr>, <nobr>',array_keys(\Lib\GoodHelper::$AVAILABILITY_KEYS))."</nobr>"),
            "title"=>array("name"=>"title", "type"=>"Строка"),
            "keywords"=>array("name"=>"keywords", "type"=>"Строка"),
            "description"=>array("name"=>"description", "type"=>"Строка"),
            "weight"=>array("name"=>"Вес (кг)", "type"=>"Число", "need_processing"=>true),
            "volume"=>array("name"=>"Объем (м3)", "type"=>"Число", "need_processing"=>true),
            "image1"=>array("name"=>"Изображение1", "type"=>"Ссылка", "is_image"=>true),
            "image2"=>array("name"=>"Изображение2", "type"=>"Ссылка", "is_image"=>true),
            "image3"=>array("name"=>"Изображение3", "type"=>"Ссылка", "is_image"=>true),
            "image4"=>array("name"=>"Изображение4", "type"=>"Ссылка", "is_image"=>true),
            "image5"=>array("name"=>"Изображение5", "type"=>"Ссылка", "is_image"=>true)
        );
    }

    /**
     * получить товары по списку id для вывода последних просмотренных товаров
     * @param $ids
     */
    public function getByIds($ids,$sort=SORT_DEFAULT,$offset=0,$limit=PAGING_PER_PAGE){
        $ids = trim($ids,",");
        switch($sort){
            case SORT_PRICE_ASC:$sort="g.price asc";break;
            case SORT_PRICE_DESC:$sort="g.price desc";break;
            case SORT_AVAILABILITY_DESC:$sort="g.availability asc";break;
            default:$sort="FIELD(g.id, {$ids})";
        }

        if(!$ids)
            return false;
        $sql = "
            select g.*,c.path as c_path,c.link as c_link 
            from {$this->table} g inner join ".PRX."catalog c on g.id_catalog=c.id 
            where g.id in ({$ids})"
            .($sort?" order by {$sort}":"")
            .($limit?" limit {$limit}":"")
            .($offset?" offset {$offset}":"");

        return $this->getAll($sql);
    }

        /*
         * получить товары принадлежащие непосредственно переданному каталогу
         */
    public function getByCatalog($cid)
    {
        //echo "SELECT g.*,concat(c.path,'/',c.link) as path FROM {$this->table} g inner join ".PRX."catalog c on g.id_catalog=c.id  WHERE g.id_catalog='{$cid}' AND g.hide='0' ORDER BY g.sort,g.id";
        return $this->getAll("SELECT g.*,c.path as c_path,c.link as c_link FROM {$this->table} g inner join ".PRX."catalog c on g.id_catalog=c.id  WHERE g.id_catalog='{$cid}' AND g.hide='0' ORDER BY g.sort,g.id");
    }

    /*
     * получить товары по списку id каталогов - используется для отображения товаров текущего каталога включая подкаталоги
     */
    public function getByCatalogs($ids, $column="",$offset=0,$limit=0)
    {
        if($column)
            return $this->getColumn("SELECT g.{$column} FROM {$this->table} g inner join ".PRX."catalog c on g.id_catalog=c.id  WHERE find_in_set(g.id_catalog,'{$ids}') AND g.hide='0' ORDER BY g.sort,g.id".($limit?" limit {$limit} offset {$offset}":""));
        else
            return $this->getAll("SELECT g.*,c.path as c_path,c.link as c_link FROM {$this->table} g inner join ".PRX."catalog c on g.id_catalog=c.id  WHERE find_in_set(g.id_catalog,'{$ids}') AND g.hide='0' ORDER BY g.sort,g.id".($limit?" limit {$limit} offset {$offset}":""));
    }

    /*
     * получить количество товаровв по списку id каталогов - используется для разбиения sitemaps
     */
    public function getCountByCatalogs($ids)
    {
        return $this->getField("SELECT count(*) FROM {$this->table} g inner join ".PRX."catalog c on g.id_catalog=c.id  WHERE find_in_set(g.id_catalog,'{$ids}') AND g.hide='0' and price>0 ORDER BY g.sort,g.id");
    }

    public function getByLink($link){
        return $this->getRow("SELECT g.*,c.path as c_path,c.link as c_link FROM {$this->table} g inner join ".PRX."catalog c on g.id_catalog=c.id  WHERE g.link='{$link}' AND g.hide='0' ORDER BY g.sort,g.id");
    }

    /*
     * найти товар по ссылке в определенном каталоге
     */
    public function getByLinkAndCatalog($link,$catalog){
        $rows = $this->getRow("SELECT g.*,c.path as c_path,c.link as c_link FROM {$this->table} g inner join ".PRX."catalog c on g.id_catalog=c.id  WHERE g.link='{$link}' ".($catalog?"and g.id_catalog ='{$catalog}'":"")." AND g.hide='0' ORDER BY g.sort,g.id");
        return $rows?$rows:false;
    }

    public function getHits($limit=12){
        $rows = $this->getAll("SELECT g.*,c.path as c_path,c.link as c_link FROM {$this->table} g inner join ".PRX."catalog c on g.id_catalog=c.id  WHERE g.hit=1 AND g.hide='0' ORDER BY RAND(),g.sort,g.id limit $limit");
        return $rows?$rows:false;
    }

    public function getHitsIds(){
        $ids = $this->getColumn("SELECT g.id FROM {$this->table} g WHERE g.hit=1 AND g.hide=0");
        return $ids?$ids:false;
    }

    public function getPromos($limit=12){
        $rows = $this->getAll("SELECT g.*,c.path as c_path,c.link as c_link FROM {$this->table} g inner join ".PRX."catalog c on g.id_catalog=c.id  WHERE g.spec=1 AND g.hide='0' ORDER BY RAND(),g.sort,g.id limit $limit");
        return $rows?$rows:false;
    }

    public function getPromosIds(){
        $ids = $this->getColumn("SELECT g.id FROM {$this->table} g WHERE g.spec=1 AND g.hide=0");
        return $ids?$ids:false;
    }

    public function getNew($limit=12){
        $rows = $this->getAll("SELECT g.*,c.path as c_path,c.link as c_link FROM {$this->table} g inner join ".PRX."catalog c on g.id_catalog=c.id  WHERE g.new=1 AND g.hide='0' ORDER BY RAND(),g.sort,g.id limit $limit");
        return $rows?$rows:false;
    }

    public function getNewIds(){
        $ids = $this->getColumn("SELECT g.id FROM {$this->table} g WHERE g.new=1 AND g.hide=0");
        return $ids?$ids:false;
    }

    public function getBrandIds($bid){
        $ids = $this->getColumn("SELECT g.id FROM {$this->table} g inner join ".PRX."catalog c on c.id=g.id_catalog WHERE c.hide=0 and g.hide=0 and g.id_maker={$bid}");
        return $ids?$ids:false;
    }

    public function searchByName($name, $limit=10){
        $suggestions = $this->getAll("SELECT g.id, g.article, g.name, g.link, c.id as c_id, c.path as c_path, c.link as c_link FROM {$this->table} g inner join ".PRX."catalog c on g.id_catalog=c.id WHERE (g.name LIKE '%{$name}%' OR g.article LIKE '%{$name}%') and g.hide=0 and g.price>0 ORDER BY g.name LIMIT {$limit}");
        if(!$suggestions)
        {
            $suggestions = $this->getAll("SELECT g.id, g.article, g.name, g.link, c.id as c_id, c.path as c_path, c.link as c_link FROM {$this->table} g inner join ".PRX."catalog c on g.id_catalog=c.id WHERE ".\Lib\SqlHelper::getWhere($name,'g.name,g.article')." and g.hide=0 and g.price>0 ORDER BY g.name LIMIT {$limit}");
        }
        return $suggestions;
    }

    /**
     * результаты поиска но названию
     * @param $name
     * @param int $limit
     * @return array|bool
     * @throws \Github\Exception
     */
    public function searchFullByName($name){
        $goods = $this->getAll("SELECT g.*, c.id as c_id, c.path as c_path, c.link as c_link FROM {$this->table} g inner join ".PRX."catalog c on g.id_catalog=c.id WHERE (g.name LIKE '%{$name}%' OR g.article LIKE '%{$name}%') and g.hide=0 and g.price>0 ORDER BY g.name");
        if(!$goods)
        {
            $goods = $this->getAll("SELECT g.*, c.id as c_id, c.path as c_path, c.link as c_link FROM {$this->table} g inner join ".PRX."catalog c on g.id_catalog=c.id WHERE ".\Lib\SqlHelper::getWhere($name,'g.name,g.article')." and g.hide=0 and g.price>0 ORDER BY g.name");
        }
        return $goods;
    }

    public function getMinMaxPriceByIds($ids){
        return $this->getRow("SELECT ROUND(MIN(price)) AS min, ROUND(MAX(price)) AS max FROM {$this->table} WHERE id IN ({$ids})");
    }

    public function getCountByFeatures($filters, $gids){
        $feature_joins = array();
        $where = array();
        foreach($filters as $f){
            $alias = "fv".$f['id'];
            if($f["id"]=="price"){
                $v1  = $f["values"][1];
                $v2 = $f["values"][2];
                if($f["values"] && ($v1 || $v1)) {
                    $where[] = ($v1?" and g.price>={$v1}":"").($v2?" and g.price<={$v2}":"");
                }
            }elseif(is_array($f["values"]) && $f["id"]=="maker" && count($f["values"])) {
                $values = array_filter($f["values"]);
                if ($values) {
                    $where[] = " and g.id_maker in ('".implode("','",$values)."')";
                }
            }elseif($f["type"]=="диапазон") {
                $v1  = $f["values"][1];
                $v2 = $f["values"][2];
                if ($f["values"] && ($v1 || $v2)) {
                     $feature_joins[] = "inner join {$this->table_feature_good} {$alias} on {$alias}.id_good=g.id and {$alias}.id_feature='{$f['id']}' ".($v1?" and {$alias}.value>='".$v1."'":"").($v2?" and {$alias}.value<='".$v2."'":""); //CAST({$alias}.value AS DECIMAL(10,2))
                }
            }elseif(is_array($f["values"]) && $f["type"]=="список" && count($f["values"])){
                $values = array_filter($f["values"]);
                if ($values) {
                    $feature_joins[] = "inner join {$this->table_feature_good} fv{$f['id']} on {$alias}.id_good=g.id and {$alias}.id_feature='{$f['id']}' and {$alias}.value in ('".implode("','",$values)."')";
                }
            }
        }
        $sql = "
                select count(*) 
                from {$this->table} g
                ".implode(' ',$feature_joins)."
                where g.id in ({$gids})".implode(' ',$where);
        //echo $sql;
        return $this->getField($sql);
    }

    /**
     * Получить товары по списку id товаров с учетом фильтров, сортировки и пейджинга
     * @param $filters
     * @param $gids
     * @param string $sort
     * @param int $offset
     * @param int $limit
     * @return array|bool
     * @throws \Github\Exception
     */
    public function getByFeatures($filters,$gids,$sort=SORT_DEFAULT,$offset=0,$limit=PAGING_PER_PAGE){
        switch($sort){
            case SORT_PRICE_ASC:$sort="g.price asc";break;
            case SORT_PRICE_DESC:$sort="g.price desc";break;
            case SORT_AVAILABILITY_DESC:$sort="g.availability asc";break;
            default:$sort="";
        }

        $feature_joins = array();
        $where = array();
        foreach($filters as $f){
            $alias = "fv".$f['id'];
            if($f["id"]=="price"){
                $v1  = $f["values"][1];
                $v2 = $f["values"][2];
                if($f["values"] && ($v1 || $v1)) {
                    $where[] = ($v1?" and g.price>={$v1}":"").($v2?" and g.price<={$v2}":"");
                }
            }elseif(is_array($f["values"]) && $f["id"]=="maker" && count($f["values"])) {
                $values = array_filter($f["values"]);
                if ($values) {
                    $where[] = " and g.id_maker in ('".implode("','",$values)."')";
                }
            }elseif($f["type"]=="диапазон") {
                $v1  = $f["values"][1];
                $v2 = $f["values"][2];
                if ($f["values"] && ($v1 || $v2)) {
                    $feature_joins[] = "inner join {$this->table_feature_good} {$alias} on {$alias}.id_good=g.id and {$alias}.id_feature='{$f['id']}' ".($v1?" and {$alias}.value>='".$v1."'":"").($v2?" and {$alias}.value<='".$v2."'":""); //CAST({$alias}.value AS DECIMAL(10,2))
                }
            }elseif(is_array($f["values"]) && $f["type"]=="список" && count($f["values"])){
                $values = array_filter($f["values"]);
                if ($values) {
                    $feature_joins[] = "inner join {$this->table_feature_good} fv{$f['id']} on {$alias}.id_good=g.id and {$alias}.id_feature='{$f['id']}' and {$alias}.value in ('".implode("','",$values)."')";
                }
            }
        }
        $sql = "
                select g.*,c.path as c_path,c.link as c_link
                from {$this->table} g
                ".implode(' ',$feature_joins)."
                inner join ".PRX."catalog c on g.id_catalog=c.id
                where g.id in ({$gids})"
                .implode(' ',$where)
                .($sort?" order by {$sort}":"")
                .($limit?" limit {$limit}":"")
                .($offset?" offset {$offset}":"");
        //echo $sql;
        return $this->getAll($sql);
    }

    /**
     * получить наличие по магазинам
     * @param $gid
     * @return array|bool
     * @throws \Github\Exception
     */
    public function getAvailabilityByGood($gid){
        $sql = "select a.n,s.name as shop from ".PRX."good_shop a inner join ".PRX."shop s on a.id_shop=s.id where a.id_good={$gid} and s.hide=0 order by s.sort";
        //echo $sql;
        return $this->getAll($sql);
    }

//depricated
    public function getSimilar($good,$limit=12){
        $sql = "SELECT g.*,c.path as c_path,c.link as c_link FROM {$this->table} g inner join ".PRX."catalog c on g.id_catalog=c.id WHERE id_catalog={$good["id_catalog"]} AND g.hide=0 AND g.price>0 AND g.id<>{$good["id"]} ORDER BY IF(g.price>{$good["price"]},g.price-{$good["price"]},10000000) ASC,IF(g.price>{$good["price"]},0,g.price)DESC LIMIT {$limit}";
        //echo $sql;
        return $this->getAll($sql);
    }

    /**
     * получить товар по артикулу
     * @param $article
     * @return array|bool
     * @throws \Github\Exception
     */
    public function getByArticle($article,$id_brand=''){
        $sql = "SELECT * FROM {$this->table} WHERE article = '$article'".($id_brand?" and id_maker='{$id_brand}'":"");
        //echo "<br/>".$sql;
        return $this->getRow($sql);
    }

    /**
     * Количество товаров из списка у которых доступность не соотвтетствует указанной
     * @param $ids
     * @param $availability
     * @return mixed
     * @throws \Github\Exception
     */
    public function getGoodsCountNotInStatus($ids, $availability='available'){
        $sql = "SELECT count(*) FROM {$this->table} WHERE id in ($ids) and availability != '$availability'";
        //echo $sql;
        return $this->getField($sql);
    }

}