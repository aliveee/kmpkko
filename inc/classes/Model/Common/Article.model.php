<?php
/**
 * Created by PhpStorm.
 * User: Павел
 * Date: 09.02.2019
 * Time: 12:23
 */

namespace Model\Common;


class Article extends \Model\Base
{
    /**
     * @var string
     */
    protected $table = PRX.'article';

    public function getLast($limit){
        return $this->getAll(" SELECT * FROM {$this->table} WHERE `hide` = 0 ORDER BY date desc limit $limit");
    }

    public function getByLink($link)
    {
        $link = \Lib\App::get('db')->real_escape_string($link);
        return $this->getRow("SELECT * FROM {$this->table} WHERE link = '{$link}' and `hide` = 0");
    }

    public function getNext($date){
        return $this->getRow("SELECT * FROM {$this->table} WHERE date>'$date'  and `hide` = 0 order by date limit 1");
    }

    public function getPrev($date){
        return $this->getRow("SELECT * FROM {$this->table} WHERE date<'$date'  and `hide` = 0 order by date desc limit 1");
    }

    public function getMain($limit=3){
        return $this->getAll(" SELECT * FROM {$this->table} WHERE `is_main` = 1 ORDER BY date desc limit $limit");
    }
}