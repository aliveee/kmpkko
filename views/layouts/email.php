<?php
/**
 * Шаблон письма
 * Created by PhpStorm.
 * User: Павел
 * Date: 08.04.2019
 * Time: 14:37
 */
?>
<div style="font-family: Tahoma, Arial, Helvetica">
    <div style="background-color:#F5FBFF;text-align:center;padding:32px;">
        <a href="<?=PROTOCOL?>://<?=SUBDOMAIN?>.<?=DOMAIN?>">
            <img src="<?=PROTOCOL?>://<?=SUBDOMAIN?>.<?=DOMAIN?>/img/email/logo.svg" />
        </a>
    </div>
    <div style="background-color:#F5FBFF;">
        <div style="padding:32px 24px;max-width:558px;box-sizing: border-box;margin:0 auto;background-color: #fff;border: 1px solid rgba(207, 218, 226, 0.5);box-sizing: border-box;">
            <?=$body?>
        </div>
    </div>
    <div style="background-color:#F5FBFF;text-align:center;padding:32px;font-size: 12.8px;line-height: 22px;color: #6D7D8F;">
        <div>
            <?=\Lib\App::get("settings")->footer_copyright1?>
        </div>
        <div>
            <img  style="opacity:0.5;vertical-align: middle" src="<?=PROTOCOL?>://<?=SUBDOMAIN?>.<?=DOMAIN?>/img/email/phone.png" />&nbsp;
            <a style="color: #6D7D8F;text-decoration:none;" href="tel:<?=\Lib\App::get("settings")->phone?>" style="vertical-align: middle">
                <?=\Lib\App::get("settings")->phone?>
            </a>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <img style="opacity:0.5;vertical-align: middle" src="<?=PROTOCOL?>://<?=SUBDOMAIN?>.<?=DOMAIN?>/img/email/email.png" />&nbsp;
            <a  style="color: #6D7D8F;text-decoration:none;" href="mailto:<?=\Lib\App::get("settings")->email_letter?>" style="vertical-align: middle">
                <?=\Lib\App::get("settings")->email_letter?>
            </a>
        </div>
        <div>
            <?=\Lib\App::get("settings")->footer_address?>
        </div>
    </div>
