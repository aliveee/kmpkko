$(document).ready(function() {

    $('.for-xs').addClass('hidden-lg hidden-md hidden-sm');
    $('.for-xs').show();


    $('.for-xs .header_selection.filt.center-xs').click(function(){
        $(this).parents('.full_filter').find('#frmFilter').toggle();
    })


    $('.shops_cart span').click(function(){
        $(this).parents('.col-lg-3').eq(0).find('label').click();
    });

    $('.cart_page .good-row').hover(function(){
        $(this).addClass('hov');
    }, function(){
        $(this).removeClass('hov');
    })

    $('#prod_compare').change(function(){
        if ($(this).prop('checked')==1)
        {
            toAjax('/compare.php?action=tocompare&id='+$(this).data('id'));
        }
        else
        {
            toAjax('/compare.php?action=clean&id='+$(this).data('id'));
        }
    });

    $('.ff-img').each(function(){
        $(this).wrap("<a class='ff-im' href='"+$(this).attr('src')+"' rel='groupimg'></a>");
    });

    $('.type_user input[type=radio]').change(function(){
        if ($(this).val()=='Юридическое лицо')
            $('.for_ur').show();
        else
            $('.for_ur').hide();
    });

    //----для выспывающего города ------
    function myCity()
    {
        var name=jQuery("#my-city").html();
        var code=jQuery("#my-city").attr('data-code');

        var today = new Date(); today.setTime( today.getTime() );
        var expires_date = new Date( today.getTime() + 1000 * 60 * 60 * 24 );
        document.cookie = "selectedRegionId="+encodeURIComponent(code)+";expires=" + expires_date.toGMTString()+";path=/;domain=.hilding-anders.ru";
        document.cookie = "selectedRegionName="+encodeURIComponent(name)+";expires=" + expires_date.toGMTString()+";path=/;domain=.hilding-anders.ru";
        document.cookie = "selectedRegion=1;expires=" + expires_date.toGMTString()+";path=/;domain=.hilding-anders.ru";
        //jQuery.post('/assets/snippets/address/services/selectRegion.php', {'city':name, 'city_code':code}, callback,'json');
        jQuery.colorbox.close();
    }

    var callback=function(){
        window.location.reload(true);
    };
    jQuery('.list_sity span').click(function(){
        var name=this.innerHTML, code=String(this.getAttribute('data-code'));
        jQuery('#currentRegion').text(name);
        jQuery('#city_a').html(name);

        jQuery.post('/assets/snippets/address/services/selectRegion.php', {'city':name, 'city_code':code}, callback,'json');

        window.location.reload(true);
    });

    function liFormat(row, i, num) {
        return row[0];
    }
    function selectItem(li) {

        /*
         if( li == null )
             var sValue = "Ничего не выбрано!";
         if( !!li.extra )
             var sValue = li.extra[1];
         else
             var sValue = li.selectValue;
         jQuery('#currentRegion').html(li.selectValue);

         var today = new Date(); today.setTime( today.getTime() );
         var expires_date = new Date( today.getTime() + 1000 * 60 * 60 * 24 );
         document.cookie = "selectedRegionId="+encodeURIComponent(sValue)+";expires=" + expires_date.toGMTString()+";path=/;domain=.shtora-tulpan.ru";
         document.cookie = "selectedRegionName="+encodeURIComponent(li.selectValue)+";expires=" + expires_date.toGMTString()+";path=/;domain=.shtora-tulpan.ru";
         document.cookie = "selectedRegion=; expires=Thu, 01-Jan-70 00:00:01 GMT";
         document.cookie = "selectedRegion=1;expires=" + expires_date.toGMTString()+";path=/;domain=.shtora-tulpan.ru";

         jQuery.post('/assets/snippets/address/services/selectRegion.php', {'city':li.selectValue, 'city_code' : sValue}, callback, 'json');
         jQuery.colorbox.close();
         */


        li=''+li;
        s=li.split(',');

        jQuery('#currentRegion').text(s[0]);


        jQuery.post('/assets/snippets/address/services/selectRegion.php', {'city':s[0], 'city_code' : s[3]}, callback, 'json');
        jQuery.colorbox.close();
    }


    $('.currentRegion').click(function(){
        $('.div_city').fadeToggle();
    })

    $('.eche').click(function(){
        $(this).parents('.item').find('.subitems2').show();
        $(this).parents('.item').find('.subitems').hide();
    });
    $('.eche2').click(function(){
        $(this).parents('.item').find('.subitems').show();
        $(this).parents('.item').find('.subitems2').hide();
    });

    $('.hover_').live('click',function(){
        location.href='/compare.php';
    });
    $('.hover2_').live('click',function(){
        location.href='/favorites.php';
    });

    $('#mobile_catalog .top_menu>.item').click(function(){
        $(this).find('.subitems').toggle();
    });

    $('.for_cat').live('click', function(){
        $('.for_mobile').toggle();
        $('.autocomplete-suggestions').css('minWidth',$('.search:visible').width());
    });

    $('.show_step').click(function(){
        $('.block-step').toggle();
        $(this).find('i').toggleClass('fa-flip-vertical');
    });

    if ($('#check_main').val()==1)
        var check_main=1; // главная страница

    $('.bxSlider').bxSlider({controls:false,auto:true,preloadImages:'visible',touchEnabled:false, onSliderLoad: function(){
            /*if (check_main==1)
              $('#hide_catalog').height($('.bxSlider>li').height()+24);*/
        }});

    $('.bxSlider-good').bxSlider({controls:false,auto:false, preloadImages:'visible', minSlides:3, maxSlides:3, moveSlides: 3, slideWidth: 160, pager:true});


    $('#show_g').click(function(){

        var inProgress = false;
        /* С какой статьи надо делать выборку из базы при ajax-запросе */
        var startFrom = 10;
        var begin_page=$('#begin_page').val();
        var cnt_page=$('#cnt_page').val();
        var showAs=$('#showAs').val();

        $.ajax({
            /* адрес файла-обработчика запроса */
            url: '/inc/obrabotchik.php',
            /* метод отправки данных */
            method: 'POST',
            /* данные, которые мы передаем в файл-обработчик */
            data: {
                "startFrom" : startFrom,
                "hidden_query": $('#hidden_query').val(),
                "begin_page": begin_page,
                "cnt_page": cnt_page,
                "showAs": showAs,
                "price_id": $('#price_id').val()
            },
            /* что нужно сделать до отправки запрса */
            beforeSend: function() {
                $('#loading').show();
                /* меняем значение флага на true, т.е. запрос сейчас в процессе выполнения */
                inProgress = true;}
            /* что нужно сделать по факту выполнения запроса */
        }).done(function(data){


            $('#loading').hide();

            /* Преобразуем результат, пришедший от обработчика - преобразуем json-строку обратно в массив */
            //data = jQuery.parseJSON(data);

            /* Если массив не пуст (т.е. статьи там есть) */
            if (data.length>0) {

                /* Делаем проход по каждому результату, оказвашемуся в массиве,
                где в index попадает индекс текущего элемента массива, а в data - сама статья */
                //$.each(data, function(index, data){

                /* Отбираем по идентификатору блок со статьями и дозаполняем его новыми данными */

                $("#all_goods").append(data);
                $('input.numinput').not('.active_num').numArr();
                $('.for-xs').addClass('hidden-lg hidden-md hidden-sm');
                initProductListHover();

                //$('input.numinput').numArr();

                //});

                $('.numinput').each(function(){
                    cur_a=$(this).parents('.item').find('.to_cart a.fb-ajax');
                    $(cur_a).attr('href', $(cur_a).data('href')+'&kol='+$('#kol'+$(cur_a).data('id')).val());
                });

                /* По факту окончания запроса снова меняем значение флага на false */
                inProgress = false;
                // Увеличиваем на 10 порядковый номер статьи, с которой надо начинать выборку из базы
                startFrom += 10;
                begin_page = parseInt(begin_page) + parseInt(cnt_page);

                $('#begin_page').val(begin_page);

                body_width();

            }});
    });

    $('#hide_catalog').mouseleave(function(){
        // alert("!");
        dr=setTimeout(function(){
            jQuery('div.top_line3 .top_menu > .item > .subitems').hide();
            jQuery('div.top_line3 .top_menu > .item').removeClass('active');
            /*if (check_main==1)
                $('#hide_catalog').height($('.bxSlider>li').height()+24).css({'overflow-y':'hidden'});
             */
            clearTimeout(dr);
        },300);
    });

    jQuery('div.top_line3 .top_menu > .item').mouseenter(function(){

        cur=$(this).data('settm');
        obj=$(this);

        cur=setTimeout(function(){
            show_fnct(obj);
        }, 0)

    }).mouseleave(function(){
        obj=$(this);
        clearTimeout($(this).data('settm'));
    });

    jQuery('div.top_line3 .top_menu > .item > .subitems > .item2').mouseenter(function(){

        cur=$(this).data('settm');
        obj=$(this);

        cur=setTimeout(function(){
            show_fnct1(obj);
        }, 0)

    }).mouseleave(function(){
        obj=$(this);
        clearTimeout($(this).data('settm'));
    });

    function hide_fnct(obj)
    {
        obj.removeClass('active').find('.subitems').hide();
    }

    function show_fnct(obj)
    {
        var subItems = obj.find('.subitems');
        jQuery('div.top_line3 .top_menu > .item').removeClass('active');
        jQuery('div.top_line3 .top_menu > .item > .subitems').hide();

        obj.addClass('active');

        /*
          if (check_main==1)
              $('#hide_catalog').css({'overflow':'inherit'}).height($('.top_menu').height());
        */
        //subItems.css('top','-'+offset.top+'px').show();             
        subItems.show();
    }

    function show_fnct1(obj)
    {
        var subItems1 = obj.find('.subitems1');
        jQuery('div.top_line3 .top_menu > .item > .subitems > .subitems1 > .item2').removeClass('active');
        jQuery('div.top_line3 .top_menu > .item > .subitems > .item2 > .subitems1').hide();
        obj.addClass('active');

        /*
         if (check_main==1)
         $('#hide_catalog').css({'overflow':'inherit'}).height($('.top_menu').height());
         */
        //subItems.css('top','-'+offset.top+'px').show();
        subItems1.show();
    }

    $('.selection .caption').click(function(){
        $(this).parent('.item').find('.cont').slideToggle();
        $(this).find('i').toggleClass('fa-caret-up')
    });


    $(".auth_buttons").click(function() {
        $(this).next().slideToggle();
    });
    $(".main_mnu_button").click(function() {
        $(".maian_mnu ul").slideToggle();
    });

    //Таймер обратного отсчета
    //Документация: http://keith-wood.name/countdown.html
    //<div class="countdown" date-time="2015-01-07"></div>
    var austDay = new Date($(".countdown").attr("date-time"));
    $(".countdown").countdown({until: austDay, format: 'yowdHMS'});

    //Попап менеджер FancyBox
    //Документация: http://fancybox.net/howto
    //<a class="fancybox"><img src="image.jpg" /></a>
    //<a class="fancybox" data-fancybox-group="group"><img src="image.jpg" /></a>
    $(".fancybox").fancybox();

    //Навигация по Landing Page
    //$(".top_mnu") - это верхняя панель со ссылками.
    //Ссылки вида <a href="#contacts">Контакты</a>
    $(".top_mnu").navigation();

    //Добавляет классы дочерним блокам .block для анимации
    //Документация: http://imakewebthings.com/jquery-waypoints/
    $(".block").waypoint(function(direction) {
        if (direction === "down") {
            $(".class").addClass("active");
        } else if (direction === "up") {
            $(".class").removeClass("deactive");
        };
    }, {offset: 100});

    //Плавный скролл до блока .div по клику на .scroll
    //Документация: https://github.com/flesler/jquery.scrollTo
    $("a.scroll").click(function() {
        $.scrollTo($(".div"), 800, {
            offset: -90
        });
    });

    //Каруселька
    //Документация: http://owlgraphic.com/owlcarousel/
    var owl = $(".carousel");
    owl.owlCarousel({
        items : 3,
        autoHeight : true
    });
    owl.on("mousewheel", ".owl-wrapper", function (e) {
        if (e.deltaY > 0) {
            owl.trigger("owl.prev");
        } else {
            owl.trigger("owl.next");
        }
        e.preventDefault();
    });
    $(".next_button").click(function() {
        owl.trigger("owl.next");
    });
    $(".prev_button").click(function() {
        owl.trigger("owl.prev");
    });

    //Кнопка "Наверх"
    //Документация:
    //http://api.jquery.com/scrolltop/
    //http://api.jquery.com/animate/
    $("#top").click(function () {
        $("body, html").animate({
            scrollTop: 0
        }, 800);
        return false;
    });

    //Аякс отправка форм
    //Документация: http://api.jquery.com/jquery.ajax/
    $("#callback").submit(function() {
        $.ajax({
            type: "GET",
            url: "mail.php",
            data: $("#callback").serialize()
        }).done(function() {
            alert("Спасибо за заявку!");
            setTimeout(function() {
                $.fancybox.close();
            }, 1000);
        });
        return false;
    });


    $('.search_str').autocomplete({
        serviceUrl:'/inc/action.php?action=search',
        deferRequestBy: 100, // задержка между запросами
        //maxHeight: 200, // Максимальная высота списка подсказок, в пикселях
        zIndex: 9999, // z-index списка
        minChars: 3, // Минимальная длина запроса для срабатывания автозаполнения
        onSelect: function(value, data){
            console.log(value);
            $(this).parents('form:eq(0)').submit(); }
    });

    $(".towns").autocomplete({
        serviceUrl:'/inc/action.php?action=search_city',
        deferRequestBy: 100, // задержка между запросами
        //maxHeight: 200, // Максимальная высота списка подсказок, в пикселях
        zIndex: 9999, // z-index списка
        minChars: 3, // Минимальная длина запроса для срабатывания автозаполнения
        //onSelect: function(value, data){ event.cancelBubble=true; }
        onSelect: function(suggestions){
            toAjax('/inc/action.php?action=city_site&city_site='+suggestions.value+'&noreload');
        }

        /* $(".towns").autocomplete({
                 serviceUrl:'/assets/snippets/address/services/listCity.php',
                 delay:300,//-задержка в миллисекундах. Если в течение этого времени пользователь не нажимал клавиши, активизируется запрос. Если используется локальный запрос (к данным, находящимся непосредственно в файле), задержку можно сильно уменьшить. Например до 40ms. (По умолчанию: 400).
                 minChars:-1,//-минимальное число символов, которое пользователь должен напечатать перед тем, как будет активизирван запрос. (По умолчанию: 1).
                 matchSubset:0,//-использовать ли кэш для уточнения запросов. Использование этой опции может сильно снизить нагрузку на сервер и увеличить производительность. Не забудьте при этом еще и установить для cacheLength значение побольше. Например 10. (По умолчанию: 1).
                 autoFill:true,//-когда Вы начинаете вводить текст, в поле ввода будет подставлено (и выделено) первое подходящее значение из списка. Если Вы продолжаете вводить текст, в поле ввода и далее будет подставляться подходящее значение, но уже с учетом введенного Вами текста. (По умолчанию: false).
                 matchContains:1,
                 cacheLength:30,//-число ответов от сервера, сохраняемых в кэше. Если установлено в 1 – кэширование данных отключено. Никогда не устанавливайте меньше единицы. (По умолчанию: 1).
                 selectFirst:true,//-если установить в true, то по нажатию клавиши Tab или Enter будет выбрано то значение, которое в данный момент установлено в элементе ввода. Если же имеется выбранный вручную («подсвеченный») результат из выпадающего списка, то будет выбран именно он. (По умолчанию: false).
                 formatItem:liFormat,//-JavaScript функция, которая поможет обеспечить дополнительную разметку элементов выпадающего списка. Функция будет вызываться для каждого элемента LI. Возвращаемые от сервера данные могут быть отображены в элементах LI выпадающего списка (см. второй пример). Принимает три параметра: строка результата, позиция строки в списке результатов, общее число элементов в списке результатов. (По умолчанию: none).
                 onSelect:selectItem,//-JavaScript функция, которая будет вызвана, когда элемент списка выбран. Принимает единственный параметр – выбранный элемент LI. Выбранный элемент будет иметь дополнительный атрибут «extra», значением которого будет являться массив всех ячеек строки, которая была получена в качестве ответа от сервера. (По умолчанию: none).
                 maxHeight:400,
                 width:439,
                 zIndex:9999
             });.result(function(event,data,formatted,selected) {
                    selectItem(data);
                 */

    });
});