<header>
    <div class="menu-mobile">
        <div class="menu-mobile__inner">
            <div class="container d-block d-lg-none menu-mobile__header">
                <div class="row align-items-center justify-content-between">
                    <div class="col">Меню</div>
                    <div class="col-auto" onclick="$('.menu-mobile').toggleClass('active')">
                        <img src="/img/close.svg"/>
                    </div>
                </div>
            </div>
            <div class="menu-mobile__scroll">
                <div class="header__top bg-grey">
                    <div class="container">
                        <div class="row align-items-center justify-content-end">
                            <div class="col-auto">
                                <a class="header__contact phone" href="tel:<?=$settings->contact_phone1?>"><?=$settings->contact_phone1?></a>
                            </div>
                            <div class="col-auto">
                                <a class="header__contact phone" href="tel:<?=$settings->contact_phone2?>"><?=$settings->contact_phone2?></a>
                            </div>
                            <div class="col-auto">
                                <a class="header__contact skype" href="skype:<?=$settings->contact_skype?>?call"><?=$settings->contact_skype?></a>
                            </div>
                            <div class="col-auto">
                                <a class="header__contact email" href="mailto:<?=contact_email?>"><?=$settings->contact_email?></a>
                            </div>
                            <div class="col-auto mt-3 d-block d-lg-none">
                                <a class="header__contact location" href="javascript:;"><?=$settings->contact_address?></a>
                            </div>
                        </div>
                        <div class="mt-4 d-block d-lg-none text-center mb-4">
                            <a href="" class="social"><img src="/img/icons/inst.svg"></a>
                            <a href="" class="social"><img src="/img/icons/yout.svg"></a>
                            <a href="" class="social"><img src="/img/icons/vk.svg"></a>
                            <a href="" class="social"><img src="/img/icons/fb.svg"></a>
                        </div>
                    </div>
                </div>
                <div class="header__bottom bg-white">
                <div class="container">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto d-none d-lg-block">
                            <div class="logo">
                                <a href="/"><img src="/img/logo.svg"/></a>
                            </div>
                        </div>
                        <div class="col">
                            <nav>
                                <ul class="justify-content-end justify-content-xl-start"><?
                                    if($menu) {
                                        $menu_first = @$menu[0];
                                        ?>
                                        <li><a href="<?= $menu_first["link"] ?>"><?= $menu_first["name"] ?></a></li><?
                                        if (count($menu) > 2) {
                                            ?>
                                            <li class="d-none d-lg-flex d-xl-none nav__more"><a
                                                        href="javascript:$('.nav__hidden').toggleClass('active')">Еще</a>
                                            </li>
                                            <li class="nav__hidden">
                                            <ul><?
                                                for ($i = 1; $i < count($menu) - 1; $i++) {
                                                    $_menu = $menu[$i];
                                                    ?>
                                                    <li><a href="<?= $_menu["link"] ?>"><?= $_menu["name"] ?></a></li><?
                                                }
                                                ?>
                                            </ul>
                                            </li><?
                                        }
                                        if (count($menu) > 1) {
                                            $menu_last = @$menu[count($menu) - 1];
                                            ?>
                                            <li class="_active"><a
                                                    href="<?= $menu_last["link"] ?>"><?= $menu_last["name"] ?></a>
                                            </li><?
                                        }
                                    }
                                ?></ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
    <div class="header__mobile bg-white d-block d-lg-none">
        <div class="container">
            <div class="row align-items-center justify-content-between">
                <div class="col-auto">
                    <div class="logo">
                        <a href="/"><img src="/img/logo.svg"/></a>
                    </div>
                </div>
                <div class="col-auto burger" onclick="$('.menu-mobile').toggleClass('active')">
                    <img src="/img/burger.svg" alt="">
                </div>
            </div>
        </div>
    </div>
</header>