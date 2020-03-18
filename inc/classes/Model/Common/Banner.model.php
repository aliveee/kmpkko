<?php
/**
 * Created by PhpStorm.
 * User: Павел
 * Date: 09.02.2019
 * Time: 12:23
 */

namespace Model\Common;


class Banner extends \Model\Base
{
    /**
     * @var string
     */
    protected $table = PRX.'ebnr';

    public function getMain($limit=5){
        return $this->getAll(" SELECT * FROM {$this->table} WHERE `hide` = 0 and vitrina=1 ORDER BY sort limit $limit");
    }
}