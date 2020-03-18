<?php
/**
 * Created by PhpStorm.
 * User: Павел
 * Date: 09.02.2019
 * Time: 12:23
 */

namespace Model\Common;


class Counter extends \Model\Base
{
    /**
     * @var string
     */
    protected $table = PRX.'counter';

    public function getAllStr(){
        return implode(" ",array_column($this->getAll("select html from {$this->table} ORDER BY sort,id"),"html"));
        //return $this->getField("SELECT group_concat(html SEPARATOR  ' ') FROM {$this->table} ORDER BY sort,id");
    }
}