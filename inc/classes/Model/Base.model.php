<?php

namespace Model;

/**
 * Class Base
 * @package Model
 */
class Base
{
    /**
     * @var string
     */
    protected $table;

    /**
     * @var string первичный ключ таблицы
     */
    protected $primary = "id";


    /**
     * Base constructor.
     * @param string $table
     */
    public function __construct()
    {
    }

    /**
     * @return mixed
     */
    public function getTable()
    {
        return $this->table;
    }


    /**
     * @param $q
     * @return resource
     */
    public function query($q)
    {
        if ($result = \Lib\App::get('db')->query($q)) {
            return true;
        }else
            throw new \Exception(\Lib\App::get('db')->error);
    }

    public function getInsertId(){
        return \Lib\App::get('db')->insert_id;
    }

    public function getField($q)
    {
        if ($result = \Lib\App::get('db')->query($q)) {
            $row = $result->fetch_row();
            $result->close();
            return $row[0];
        }else
            throw new \Exception(\Lib\App::get('db')->error);

    }

    /**
     * @param $q
     * @return array|bool
     */
    public function getRow($q)
    {
        if ($result = \Lib\App::get('db')->query($q)) {
            $row = $result->fetch_assoc();
            $result->close();
            return $row?$row:false;
        }else
            throw new \Exception(\Lib\App::get('db')->error);

    }


    /**
     * @param $q
     * @return array|bool
     */
    public function getAll($q = '')
    {
        $q = $q ? $q : 'SELECT * FROM '.$this->table;
        if ($result = \Lib\App::get('db')->query($q)) {
            $arr = $result->fetch_all(MYSQLI_ASSOC);
            $result->close();
            return $arr?$arr:false;
        }else
            throw new \Exception(\Lib\App::get('db')->error);
    }

    /**
     * все записи с условием
     * @param string $where
     * @return mixed
     * @throws Exception
     */
    public function getAllWhere($where='', $order='')
    {
        $q = 'SELECT * FROM '.$this->table.($where?" where ".$where:"").($order?" order by ".$order:"");
        if ($result = \Lib\App::get('db')->query($q)) {
            $arr = $result->fetch_all(MYSQLI_ASSOC);
            $result->close();
            return $arr;
        }else
            throw new \Exception(\Lib\App::get('db')->error);
    }

    /**
     * @param $q
     * @return array|bool
     */
    public function getColumn($q = '')
    {
        $q = $q ?: 'SELECT * FROM '.$this->table;
        if ($result = \Lib\App::get('db')->query($q)) {
            $arr = $result->fetch_all(MYSQLI_NUM);
            $arr = array_column($arr,0);
            $result->close();
            return $arr;
        }else
            throw new \Exception(\Lib\App::get('db')->error);
    }


    /**
     * @param $id
     * @return array|bool
     */
    public function getById($id)
    {
        return $this->primary
            ? $this->getRow("SELECT * FROM {$this->table} WHERE {$this->primary} = '$id'")
            : false;
    }


    /**
     * @return string
     */
    protected function getScheme()
    {
        return '';
    }

    /**
     * апдейт записи
     * @param $fields
     * @param $id
     * @return bool
     * @throws Exception
     */
    public function update($fields, $id=0){
        $set=[];
        foreach ($fields as $name=>$value){
            $set[] = "`{$name}`='{$value}'";
        }
        $sql = "update ".$this->table." set ".implode(",",$set). ($id?" where ".$this->primary."={$id}":"");
        //echo $sql.";<br/>";
        $this->query($sql);
        return true;
    }

    /**
     * инсерт записи
     * @param $fields
     * @return bool
     * @throws Exception
     */
    public function insert($fields){
        $set=[];
        foreach ($fields as $name=>$value){
            $set[] = "`{$name}`='{$value}'";
        }
        $this->query("insert into ".$this->table."(`".implode("`,`",array_keys($fields))."`) values('".implode("','",$fields)."')");
        return $this->getInsertId();
    }

    /**
     * @param $id
     * @return bool
     * @throws Exception
     */
    public function delete($id){
        $sql = "delete from ".$this->table." where ".$this->primary."={$id}";
        $this->query($sql);
        return true;
    }
}