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
                        <div class="row align-items-center justify-content-start">
                            <div class="col-auto">
                                <a class="header__contact phone" href="tel:8 (492) 325-70-50">8 (492) 325-70-50</a>
                            </div>
                            <div class="col-auto">
                                <a class="header__contact phone" href="tel:8 (492) 325-76-28">8 (492) 325-76-28</a>
                            </div>
                            <div class="col-auto">
                                <a class="header__contact skype" href="skype:teplo-resurs?call">teplo-resurs</a>
                            </div>
                            <div class="col-auto">
                                <a class="header__contact email" href="mailto:info@pkko.ru">info@pkko.ru</a>
                            </div>
                            <div class="col-auto mt-3 d-block d-lg-none">
                                <a class="header__contact location" href="javascript:;">г. Ковров Владимирской области, ул. Космонавтов, д.1</a>
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
                                <ul class="justify-content-end justify-content-xl-start">
                                    <li><a href="#">ПРОДУКЦИЯ</a></li>
                                    <li class="d-none d-lg-flex d-xl-none nav__more"><a href="javascript:$('.nav__hidden').toggleClass('active')">Еще</a></li>
                                    <li class="nav__hidden">
                                        <ul>
                                            <li><a href="#">СЕРВИС</a></li>
                                            <li><a href="#">ВЫПОЛНЕННЫЕ ПРОЕКТЫ</a></li>
                                            <li><a href="#">КОНТАКТЫ</a></li>
                                        </ul>
                                    </li>
                                    <li class="active"><a href="#">ЦЕНЫ</a></li>
                                </ul>
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