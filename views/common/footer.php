<?
?><footer class="bg-grey">
    <div class="footer__top">
        <div class="container">
            <div class="row"><?
                foreach ($footer_menu as $_menu) {
                    ?>
                    <div class="col-12 col-md-6 col-lg">
                        <div class="footer__menu closable">
                            <div class="footer__menu-title">
                                <?= $_menu["name"] ?>
                            </div>
                            <ul class="footer__menu-items"><?
                                foreach(@$_menu["submenu"] as $_submenu) {
                                    ?><li class="footer__menu-item">
                                        <a href="<?= $_submenu["link"] ?>"><?= $_submenu["name"] ?></a>
                                    </li><?
                                }
                            ?></ul>
                        </div>
                    </div><?
                }
                ?><div class="col-12 col-lg">
                    <div class="footer__menu">
                        <div class="footer__menu-title d-none d-lg-block">
                            Контакты
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-6 col-lg-12 mb-3">
                                <div>
                                    <a class="header__contact phone" href="tel:<?=$settings->contact_phone1?>"><?=$settings->contact_phone1?></a>
                                </div>
                                <div>
                                    <a class="header__contact phone" href="tel:<?=$settings->contact_phone2?>"><?=$settings->contact_phone2?></a>
                                </div>
                                <div>
                                    <a class="header__contact email" href="mailto:<?=$settings->contact_email?>"><?=$settings->contact_email?></a>
                                </div>
                                <div>
                                    <a class="header__contact skype" href="skype:<?=$settings->contact_skype?>?call"><?=$settings->contact_skype?></a>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-12">
                                <div>
                                    <span class="header__contact location" ><?=$settings->contact_address?></span>
                                </div>
                                <div class="mt-4">
                                    <a target="_blank" href="<?=$settings->social_insta?>" class="social"><img src="/img/icons/inst.svg"/></a>
                                    <a target="_blank" href="<?=$settings->social_youtube?>" class="social"><img src="/img/icons/yout.svg"/></a>
                                    <a target="_blank" href="<?=$settings->social_vk?>" class="social"><img src="/img/icons/vk.svg"/></a>
                                    <a target="_blank" href="<?=$settings->social_fb?>" class="social"><img src="/img/icons/fb.svg"/></a>
                                </div>
                                <div class="mt-5 d-none d-lg-block">
                                    <a href="/callback/" class="btn btn-primary" data-toggle="modal" data-target="#feedback-form">
                                        Запросить цену
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer__bottom">
        <div class="container text-center text-lg-left">
            <?=$settings->footer_copyright1?> <?=$settings->footer_copyright2?>
        </div>
    </div>
</footer>
<?=$counters?>