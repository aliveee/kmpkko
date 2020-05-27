<?php
/**
 * Created by PhpStorm.
 * User: Павел
 * Date: 21.03.2019
 * Time: 10:42
 */

namespace Lib;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailHelper
{
    public static function send($to,$subject,$bbody,$from=null){
        if(!$from)
            $from = \Lib\App::get('settings')->email_letter;

        $tos = explode(',',$to);

        $mail = new PHPMailer(true);
        try {
            foreach($tos as $_to) {
                $mail->setFrom($from, \Lib\App::get("settings")->title);
                $mail->addAddress($_to);
                $mail->isHTML(true);
                $mail->CharSet = 'UTF-8';
                $mail->Encoding = 'base64';
                $mail->Subject = $subject;
                $mail->Body = $bbody;
                $mail->send();
            }
        } catch (Exception $e) {
            //echo $e->getMessage();
            //TODO
        }
    }
}