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
})