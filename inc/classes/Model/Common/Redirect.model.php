<?php

namespace Model\Common;

class Redirect extends \Model\Base
{
    /**
     * @var string
     */
    protected $table = PRX.'redirect';

    public function getByLink($link)
    {
        $link = \Lib\App::get('db')->real_escape_string($link);
        //$sql = "SELECT * FROM {$this->table} WHERE old_url = '{$link}'";
        return $this->getRow("SELECT * FROM {$this->table} WHERE old_url = '{$link}'");
    }
}