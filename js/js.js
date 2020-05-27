$(function(){
    $('.gallery').slick({
        infinite: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        fade: true,
        autoplay: true,
        autoplaySpeed: 4000,
        arrows:false,
        dots:false,
        adaptiveHeight: true
    });

    $('.works__gallery').slick({
        infinite: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        fade: true,
        autoplay: false,
        arrows:true,
        dots:false,
        responsive: [
            {
                breakpoint: 767,
                settings: {
                    dots: true,
                    arrows:false
                }
            }
            ]
    });

    $('.uc__table').wrap("<div class='uc__table-wrp'></div>");

    $('.closable .footer__menu-title').click(function(){
        $(this).parents('.closable').toggleClass('opened');
    })

    $('input[data-inputmask]').inputmask();
    set_ajax();
})

function set_ajax() {
    $('form.js-ajax').each(function () {
        var frm = $(this);
        frm.ajaxForm({
            dataType: 'json',
            /*beforeSerialize: function($form, options) {
            },*/
            /*uploadProgress: function(event, position, total, percentComplete) {
            },*/
            beforeSubmit: function () {
                if (frm.data("loading-id"))
                    loadingStart(frm.data("loading-text"), frm.data("loading-id"));
            },
            error: function () {
                if (frm.data("loading-id"))
                    loadingFinish(frm.data("loading-id"));
            },
            success: function (data) {
                if (frm.data("loading-id"))
                    loadingFinish(frm.data("loading-id"));

                if (data.result == true) {
                    //при успехе обработчик настроенный на форме
                    if (frm.data("onsuccess")) {
                        var func = window[frm.data("onsuccess")];
                        if (func) {
                            func(data);
                        }
                    }
                    if (data.html_target && data.html) {
                        $(data.html_target).html(data.html);
                    }
                    if (data.js) {
                        var func = window[data.js];
                        if (func) {
                            func(data);
                        }
                    }
                    //сообщение message об успехе
                    if (data.message)
                        showAlert(data.message, 'success', 3000);
                    //сбросить форму
                    if (data.reset_form === true) {
                        frm[0].reset();
                    }
                    //удалить элементы remove
                    if (data.remove) {
                        $(data.remove).slideUp(function () {
                            $(this).remove()
                        });
                    }
                    //добавить html в append_html_to
                    if (data.append_html_to) {
                        $(data.append_html_to).append(data.html);
                    }
                    //открыть open
                    if (data.open) {
                        $(data.open).slideDown();
                    }
                    //закрыть close
                    if (data.close) {
                        $(data.open).slideUp();
                    }
                    //открыть goto_fancy в fancybox
                    if (data.goto_fancy) {
                        $('<a href="' + data.goto_fancy + '"></a>').fancybox(
                            {
                                type: 'iframe',
                                toolbar: false,
                                smallBtn: true,
                                opts: {
                                    clickSlide: false,
                                },
                                clickSlide: false,
                                iframe: {
                                    preload: true,
                                    css: {
                                        width: '660px',
                                    }
                                }
                            }
                        ).click();
                    } else if (data.goto) {
                        //совершить прееход на goto
                        if (data.goto_timeoute) {
                            //по таймауту goto_timeoute
                            setTimeout(function () {
                                window.top.location.href = data.goto;
                            }, data.goto_timeoute);
                        } else {
                            window.top.location.href = data.goto;
                        }
                    } else if(data.reload){
                        document.location.reload();
                    }
                    if (!data.goto || data.goto_timeoute) {
                        //скрыть форму если hide_form
                        if (data.hide_form) {
                            frm.slideUp();
                        }
                    }
                    $('.modal').modal('hide');
                } else {
                    //при ошибке обработчик настроенный на форме
                    if (frm.data("onerror")) {
                        var func = window[frm.data("onerror")];
                        if (func) {
                            func(data);
                        }
                    }
                    if (data.message)
                        showAlert(data.message, 'danger', 3000);
                }
                //alert("Thank you for your comment!");
            }
        });
    });
}

function showAlert(alert,type,dismiss){
    var alert = $('<div class="alert alert-'+type+' alert-dismissible" role="alert">'+alert+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button></div>');
    alert.appendTo('#alert_container').fadeIn();
    if(dismiss){
        setTimeout(function(){alert.fadeOut('fast',function(){$(this).remove()})},dismiss)
    }
}