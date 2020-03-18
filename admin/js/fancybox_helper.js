function show_modal(option) {

    var config = {

        //height: 200,

        //width: 500,

        //autoDimensions: false,



        //scrolling: 'auto',

        padding: 20,

        closeBtn: true,

        title: false,

        

        //transitionIn: 'elastic',

        //transitionOut: 'elastic',



        centerOnScroll: true,

        hideOnOverlayClick: false

    };

    $.extend(config, option);



    if (isInt(config.height) || isInt(config.width))

        config.autoDimensions = false;

    

    $.fancybox(config);

}



function show_modal_wait() {

    show_modal({

        height: 100,

        width: 200,

        closeBtn: false,

        showCloseButton: false,

        content: '<img style="position: absolute;left: 70px;top: 15px;" src="images/loading.gif" />'

    });

}



function modal_resize() {

    $('#fancybox-wrap, #fancybox-content, #fancybox-content > div').css('height', 'auto');

    $.fancybox.resize();

}



function fancybox_confirm(msg, callback) {

	var ret = false;

    show_modal({

    	content: "<div style=\"margin:1px;width:600px;font-size: 24px;padding: 20px;text-align: center;\">"+msg+"<div style=\"text-align:right;margin-top:10px;\"><input class=\"fancyConfirm_ok\" style=\"margin: 50px 10px 5px 10px;padding: 5px 20px;\" type=\"button\" value=\"Да\"><input class=\"fancyconfirm_cancel\" style=\"margin: 50px 10px 5px 10px;padding: 5px 20px;\" type=\"button\" value=\"Нет\"></div></div>",

        afterShow: function() {

            $(".fancyconfirm_cancel").click(function() {

                ret = false;

                $.fancybox.close();

            });

            $(".fancyConfirm_ok").click(function() {

                ret = true;

                $.fancybox.close();

            });

        },

        onClosed: function() {

            if (typeof callback == 'function' && ret == true) { 

                callback.call(this); 

            }

        }

    });

}



function fancybox_info(msg, timeout) {
    
    show_modal({

        content: "<div style=\"margin:1px;width:500px;font-size: 24px;padding: 20px;text-align: center;\">"+msg+"</div>",

        afterShow: function() {
            setTimeout(function(){ $.fancybox.close(); }, timeout);

        }

    });

}