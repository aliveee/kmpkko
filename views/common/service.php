<?php
/**
 * Здесь сервисные вещи (диалоги, плашки и т.п.)
 * Created by PhpStorm.
 * User: Павел
 * Date: 23.05.2019
 * Time: 10:59
 */
?>
<div id="alert_container" class="alert-container">
</div>

<div class="modal fade" id="feedback-form" role="dialog" data-keyboard="true" tabindex="-1" >
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <? include DOCUMENT_ROOT."/views/modal/feedback.php" ?>
        </div>
    </div>
</div>
<?

if(defined('RECAPTCHA_ENABLED') && RECAPTCHA_ENABLED) {
    ob_start();
    ?>
    <script src="https://www.google.com/recaptcha/api.js?render=<?= RECAPTCHA_SITE_KEY ?>"></script>
    <script>
        $(function () {
            grecaptcha.ready(function () {
                grecaptcha.execute('<?=RECAPTCHA_SITE_KEY?>', {action: 'homepage'}).then(function (token) {
                    $('form [name=recaptcha]').val(token);
                });
            });
        })
    </script><?
    $_JS[] = ob_get_clean();
}