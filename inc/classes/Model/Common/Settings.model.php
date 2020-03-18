<?php

namespace Model\Common;

class Settings extends \Model\Base
{
    static protected $data;
    /**
     * @var string
     */
    protected $table = PRX.'settings';

    public function __get($name)
    {
        $getter = 'get'.$name;
        if (method_exists($this, $getter)) {
            return $this->$getter();
        } else {
            if (!isset(self::$data[$name])) {
                self::$data[$name] = $this->getById($name)["value"];
            }
            return self::$data[$name];
        }
    }
}