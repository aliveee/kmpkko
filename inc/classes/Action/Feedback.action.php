<?php

namespace Action;

use Lib\Recaptcha;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Feedback extends \Action\Base
{
    public function defaultAction(){

        if(!Recaptcha::verify()){
            $this->pass(array(
                "result" => false,
                "message"=>"Извините, Google распознал Ваш запрос как спам"
            ));
            $this->display();
            exit;
        }

        $feedback_model = new \Model\Common\Feedback();
        foreach($_POST as $k=>$v)
        {
            $$k = \Lib\Helper::clean($v);
        }
        //TODO проверку данных

        $id = $feedback_model->save($name,$email, $phone, $message);

        $this->pass(array("name" => $name, "email"=>$email,"phone"=>$phone,"message"=>$message));
        $html = $this->render(\Lib\App::get('root_directory').'views/email/feedback.php');
        $body = $this->render(\Lib\App::get('root_directory').'views/layouts/email.php',array("body"=> $html));
        \Lib\MailHelper::send(\Lib\App::get("settings")->email, 'Обратная связь с сайта', $body);


        $this->pass(array(
            "result" => $id>0,
            "message"=>$id>0?"Сообщение отправлено":"Произошла ошибка. Попробуйте через некоторое время",
            "reset_form"=>$id>0
        ));
        $this->display();
    }
}