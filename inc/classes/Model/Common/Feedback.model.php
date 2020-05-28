<?php
/**
 * Created by PhpStorm.
 * User: Павел
 * Date: 09.02.2019
 * Time: 12:23
 */

namespace Model\Common;


class Feedback extends \Model\Base
{
    /**
     * @var string
     */
    protected $table = PRX.'feedback';

    public function save($name,$email, $phone, $message, $referer){
        $sql = "insert into {$this->table}(name,email, phone, message, referer)
          values('$name','$email', '$phone', '$message', '$referer')";
        //echo $sql;
        $this->query($sql);
        $feedback_id = $this->getInsertId();
        //echo $feedback_id;
        return $feedback_id;
    }

    public function saveOpt($name,$email, $message){
        $sql = "insert into {$this->table}(name,email, message)
          values('$name','$email', '$message')";
        //echo $sql;
        $this->query($sql);
        $feedback_id = $this->getInsertId();
        //echo $feedback_id;
        return $feedback_id;
    }
}