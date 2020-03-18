
/*добавляет пробелы в стоимости (напр. 1 000 000 000)*/
function get_word_pause(word){
word +='';
var ll = word.length;
word = word.split("").reverse().join("");
var new_word = '';
for (i=0; i<ll; i++){
	if (i%3==0){ new_word += ' ';}
	new_word +=	word[i];
}
word = new_word.split("").reverse().join("");
return word;
}
/*end*/    

function prepare(string) {
	string = str_replace('&lt;', '<', string);
	string = str_replace('&gt;', '>', string);
	string = str_replace('&amp;', '&', string);
	return string;
}

function str_replace(search, replace, subject, count) {
	f = [].concat(search),
		r = [].concat(replace),
		s = subject,
		ra = r instanceof Array, sa = s instanceof Array;
	s = [].concat(s);
	if (count) {
		this.window[count] = 0;
	}
	for (i = 0, sl = s.length; i < sl; i++) {
		if (s[i] === '') {
			continue;
		}
		for (j = 0, fl = f.length; j < fl; j++) {
			temp = s[i] + '';
			repl = ra ? (r[j] !== undefined ? r[j] : '') : r[0];
			s[i] = (temp).split(f[j]).join(repl);
			if (count && s[i] !== temp) {
				this.window[count] += (temp.length - s[i].length) / f[j].length;
			}
		}
	}
	return sa ? s : s[0];
}

function setCookie(key, value) {
            var expires = new Date();
            expires.setTime(expires.getTime() + (1 * 24 * 60 * 60 * 1000));
            document.cookie = key + '=' + value + ';expires=' + expires.toUTCString();
       }

function getCookie(key) {
            var keyValue = document.cookie.match('(^|;) ?' + key + '=([^;]*)(;|$)');
            return keyValue ? keyValue[2] : null;
        }


function init_ready()
{
    $('.autocomplete-suggestions').css('minWidth',$('.search:visible').width());
    
     try{
       $('.h_cat').live('mouseenter',function(){
          $(this).find('#hide_catalog').css({visibility:'visible'});
       }).live('mouseleave', function(){
          $(this).find('#hide_catalog').css({visibility:'hidden'});
       });        
     }
     catch(e){}


    try{
        
    $( "#towns" ).autocomplete({
    	      source: availableTags
    	    });
    } catch(e){}        
    
    
    $('.footer-2 .sec_ur:lt(2)').oneheight();
    
    $('.mp5').each(function(){
       $('.mp5[data-height="'+$(this).data('height')+'"], .mp6[data-height="'+$(this).data('height')+'"]').oneheight(); 
    });
    
    //$('.mp5,.mp6').oneheight();
    
      var out_div_pre;
      $('#frmFilter input[type=checkbox]').change(function(){
        //var ap = absPosition($('#'+$(this).attr('id_parent'))[0]);
        var ap = absPosition($(this));
//        var x = Math.round(ap.x)+50;
        var y = Math.round(ap.y) - 220;
        var x=265;
        
        try{ clearTimeout(out_div_pre); }catch(e){}
        $('.selection_hint').hide().css('left', x).css('top', y);
        toAjax($(this).parents('#frmFilter')[0]);
      });
      $('#frmFilter input[type=text]').change(function(){
        var ap = absPosition($('#'+$(this).attr('id_parent'))[0]);
        //var x = Math.round(ap.x)+50;
        var x=265;
        var y = Math.round(ap.y) + 100;

        try{ clearTimeout(out_div_pre); }catch(e){}
        $('.selection_hint').css('left', x).css('top', y);
        toAjax($(this).parents('#frmFilter')[0]);
       // toAjax($('#frmFilter')[0]);
      });        
        
    
    
    initProductListHover();
    InitSubitems();
    body_width();
    
    /*считает общую стоимость покупки*/
    function get_total_price(){
    
    var total = 0;
    total=parseInt($('#total_hidden').val());
    
    //$('#subtotal_inp').val(total);
    	//$('#subtotal').html(get_word_pause(total));
    if (total<4000 && total>0){
    	//$('#delivery_cost_block').css({'display':'block'});
        //$('.deliv_2').show();
    	delivery_cost = $('#delivery_cost_inp').val()-0;
        
        setCookie('delivery_summ',delivery_cost);
        
    	total+=delivery_cost;
    	$('.less3000').show();
        $('.more3000').hide();
    }  else {
    	//$('#delivery_cost_block').css({'display':'none'});
        //$('.deliv_2').hide();
    
        setCookie('delivery_summ',0);
    
        $('#delivery_cost3').html('БЕСПЛАТНО!');
        $('#_delivery_cost3').html('БЕСПЛАТНО!');
        $('#delivery_cost_inp').val(0);
    	$('.less3000').hide();
        $('.more3000').show();
    }
    	//total -= discount;
        //alert(total);
    
    $('#total').text(get_word_pause(total));
    $('#total_inp').val(total);    
    $('#total_step2').text(get_word_pause(total));
    $('#total_step21').text(get_word_pause(total));

    }

               
/*end*/

/*выбор города доставки формой*/
$('#city_search_form .sub').bind('click', function(){
   /* 
    var city = $('#city_search_form input[name="town"]').val();
	if (city == '') city = 'Москва';
    alert(city);
	if ($('.cities_list a:contains("'+city+'")').length == 0) {
		$('#city_search_form input[name="town"]').val('');
		return;
	}
	$('.cities_list a:contains("'+city+'"):first').trigger('click');*/
    top.topReload();  
 												   });
                                                   
/*end*/

/*при загрузкепрописывает сумму доставки в трех местах из инпута, куда записывается сумма доставки с сервера*/
if ($('#delivery_cost_inp').length){
	var dost = $('#delivery_cost_inp').val();
	$('#delivery_cost, #delivery_cost2, #delivery_cost3, #_delivery_cost3').text(get_word_pause(dost));
}
/*end*/

/*выбор города доставки из списка городов*/
$('#city_popup .cities_list li a').on('click', function(event){
	var city = $(this).attr('val');
	var city_val = $(this).data('ems');//attr('data-ems');
	var date = new Date(new Date().getTime() + 60*1000*60*24*30*365);
  	document.cookie="emscity="+city_val+"; path=/; expires="+date.toUTCString();


            $('.currentRegion').text(city);
            $('#towns').attr('placeholder',city);
            //$('.fancybox-close').trigger('click');

       jQuery.post('/assets/snippets/address/services/selectRegion.php', {'city':city},function(){
            window.location.reload(true);
       });
       

            
    
   //window.location.reload(true);
	return false
});

	var ems_city = getCookie('emscity');
    //var ems_city=$.cookie('emscity');
    if (!ems_city) ems_city='city--moskva';
    
	if ($('.cities_list a').length > 0 && ems_city != undefined) {
		$('.cities_list a').each(function(){
			if ($(this).attr('data-ems') == ems_city) {
				//$(this).trigger('click');
				return;
			}
		});
	}

/*end*/


    
    
  try{  
   $('.input').styler();
   $('.input_check').styler();
  }
  catch(e){}  

 
     try{
      $(".modif").jCarouselLite({
             btnNext: ".next",
             btnPrev: ".prev",
             vertical: false,
             visible:5,
             circular: false
         });
   } catch(e){} 
   
   
  try{
      $(".secondary").jCarouselLite({
             btnNext: ".next",
             btnPrev: ".prev",
             visible: 5,
             circular: false,
         });
   } catch(e){}


  try{
      $(".otzivy_sl").jCarouselLite({
             btnNext: ".next",
             btnPrev: ".prev",
             visible: 1,
             circular: false
         });
   } catch(e){}
   
}


function getTotalPriceGood()
{
    
}

function InitSubitems()
{
  myleft=$('.top_menu').width()-2;
  
 $('div.top_line3 .top_menu > .item').each(function(){
   subitems=$(this).find('.subitems');
   subitems.css({'left':myleft,'minHeight':$('.top_menu').height()});    
 });

 $('div.top_line3 .top_menu > .item .subitems').each(function(){
   subitems=$(this).find('.subitems1');
   subitems.css({'minHeight':$(this).height()+10});    
 });


}

function initProductListHover(){
	if($('.goods_tiles').size()){
		$('.goods_tiles>.row>div>.item').each(function(){
			var _this=$(this);
            
            hei_sec=_this.find('.sec_item').outerHeight();
           if (hei_sec!=0)
           {
			_this.find('.hover').css({
				'height':_this.outerHeight()+_this.find('.sec_item').outerHeight()
			})
           } 
		})
	}
}

function body_width()
{
   var width=$('body').outerWidth();
   if (width<768)
   {
    $('.good_page .col-xs-12').addClass('xs-rows');
    $('.tab_container').css({'marginLeft':'-15px','width':width});
    
   /* 
    var wid=0;
    $('ul.tabs15:visible li').each(function(){
       wid+=$(this).width(); 
    })
    
    if (wid>width)
     $('.pan').width(wid);
    else
     $('.pan').width(width);
    */ 
     
   }
   else
   {
    if (width>=768 && width<992)
    {
      $('#hide_catalog').height('auto!important');  
    }
    
    $('.goods_tiles .col-xs-6').removeClass('xs-rows');
    $('.tab_container').css('marginLeft','0px');
   }
   
   
     $('.tabs16:visible').each(function(){
        $('.tabs16:visible').eq(0).addClass('first active');
        $(this).parents('.tab_container2').find('.tab_content2:eq(0)').addClass('active');
        $(this).find('div:last').addClass('last');
     })   
   
     $('.tabs15:visible').each(function(){
        $(this).find('li:eq(0)').addClass('first active');
        $(this).parents('.tab_container').find('.tab_content:eq(0)').addClass('active');
        $(this).find('li:last').addClass('last');
     })
     
     $(".tabs15:visible li:not(.active)").live('click',function(){
      if ($(this).index('.tabs15:visible')>0)
      {
       var ind=$(this).index();
       $(this).addClass('active').siblings().removeClass('active'); 
       $(this).parents('.tab_container').find('.tab_content').eq(ind).addClass('active').siblings().removeClass('active');
      } 
    });


     $(".tab_container2 .tabs16").live('click',function(){
       var ind=$(this).index('.tabs16');
       $(this).toggleClass('active');
       $(this).parents('.tab_container2').find('.tab_content2').eq(ind).toggle();
    });

    $('.tab_container').css('opacity','1.0');
    $('.for_mobile').width($('body').width());

}

$(function(){
  
  $('#reg_form .phone').blur(function(){
    if ($(this).val()!='')
      $('#reg_form .email').removeClass('required','error').attr('placeholder','email');
    else
      $('#reg_form .email').addClass('required').attr('placeholder','email *');
  });

  $('#reg_form .email').blur(function(){
    if ($(this).val()!='')
      $('#reg_form .phone').removeClass('required','error').attr('placeholder','Телефон');
    else
      $('#reg_form .phone').addClass('required').attr('placeholder','Телефон *');
  });
  
  
  $('.tr_cab').hover(function(){
    $(this).toggleClass('active');
  });
  
  $('.order_description .item_cab .zg2').live('click',function(){
    $(this).toggleClass('active').parent('.item_cab').find('.cart_content').toggle();
    $(this).parent('.item_cab').find('.fa-angle-double-down').toggleClass('fa-rotate-180');
  })
  
  
  if ($( ".pan" ).length)
  {
    // Pan
    var img, margin;
    new Hammer( $( ".pan" )[ 0 ], {
      domEvents: true
    } );
    $( ".pan" ).on( "panstart", function( e ) {
      img = $( "ul.pan" );
      margin = parseInt( img.css( "margin-left" ), 10 );
    } );
    $( ".pan" ).on( "pan", function( e ) {
    console.log( "pan" );
      var delta = margin + e.originalEvent.gesture.deltaX;
      console.log( delta );
      if ( delta >= -($( ".pan" ).width()-$('body').width()) && delta <= 0 ) {
         img.css( {
        "margin-left": margin + e.originalEvent.gesture.deltaX
      } ); 
      }
    } );
  }
  
  $(window).resize(function(){
        initProductListHover();
        body_width();
  }) 
  
 
  $('#cart_form').submit(function(){
    $('.steps li').removeClass('active');
    $('.steps li.step2').addClass('active');
    $('.tab_content').removeClass('active');
    $('.tab_content:eq(1)').addClass('active');
    
    return false;
  });
  
  $('#cart_form_mobile').submit(function(){
      $('.act_header>div').removeClass('active');
      $('.act_header .step2').addClass('active');
      $('.for-xs .tab_content').removeClass('active');
      $('.for-xs .tab_content:eq(1)').addClass('active');
    
    return false;
  });  
  
  $('.reset').click(function(){

   if ($('.for-xs').css('display')=='none')
    { 
     $('.steps li').removeClass('active');
     $('.steps li.step1').addClass('active');
     $('.tab_content').removeClass('active');
     $('.tab_content:eq(0)').addClass('active');
    }
    else
    {
      $('.act_header>div').removeClass('active');
      $('.act_header .step1').addClass('active');
      $('.for-xs .tab_content').removeClass('active');
      $('.for-xs .tab_content:eq(0)').addClass('active');
    }
    
    return false;
  });  
 
 
 
 function sliderPrice()
 {
    //---слайдер цены ------------------------------------   
     if (jQuery( ".slider-range").length)
     {
       var slider = jQuery( ".slider-range" ).slider({
    	range: true,
    	step: 100,
    	min: parseInt($('.hidden_min').val()),
    	max: parseInt($('.hidden_max').val()),
    	values: [ $('.hidden_price1').val(), $('.hidden_price2').val() ],
    	slide: function( event, ui ) {
    		jQuery('.price1').val( ui.values[0] );
    		jQuery('.price2').val( ui.values[1] );
            jQuery('.price1').change();
     	}
      });
    	jQuery('.price1').val( slider.slider("values",0) );
    	jQuery('.price2').val( slider.slider("values",1) );
    	jQuery(".price1").change(function() {
    		slider.slider("values", 0, this.value);
    	});
    	jQuery(".price2").change(function() {
    		slider.slider("values", 1, this.value);
    	});
    
     
       jQuery.fn.scroll_off = function(){
         jQuery(".slider-range").slider({
             values: [$('.hidden_min').val(),$('.hidden_max').val()],
         });
       jQuery('.price1').val($('.hidden_min').val());
       jQuery('.price2').val($('.hidden_max').val());            
       }; 
     }    
 }
 
 sliderPrice();
    
 
 
 
 $('.oferta input').click(function(){
    if ($(this).prop("checked")==true)
       $(this).parent('.oferta').next('input[type=button]').addClass('btn2').removeClass('btn3').removeAttr("disabled");
    else   
       $(this).parent('.oferta').next('input[type=button]').addClass('btn3').removeClass('btn2').attr('disabled','');
 });
 
 $('select.subect').change(function(){
    
   if (!$(this).val()) 
     $('.city_mine').val('').show();
   else  
     $('.city_mine').val($(this).val()).hide();  
   
   $('#subect-styler').css('border','none'); 
})


 $('.fir_item').live('mouseover',function(){
    sec_element=$(this).find('.sec_item');
    sec_element.show();
 }).live('mouseout',function(){
    sec_element=$(this).find('.sec_item');
    sec_element.hide();
 });
 
 $('.image').hover(function(){
   $(this).find('.block_models').slideDown(); 
 }, function(){
   $(this).find('.block_models').slideUp();
 });
 
 
 $('.crat_info').click(function(){
    $(this).parent('td').first().find('.full_info').show();
    $(this).hide();
 });

 $('.full_info').click(function(){
    $(this).parent('td').first().find('.crat_info').show();
    $(this).hide();
 });
 
 $(".secondary .item").live('click',function(){
    
    act_li=$('.secondary .item[data-type=1]');
    $('.secondary a.fb-img').attr('rel','fbgr');
    act_li.data('type','0');
    
    $(this).data('type','1').next('a.fb-img').attr('rel','');

    $('.primary img').attr('src',$(this).data('big'));
    $('.primary a').attr('href',$(this).data('href'));
 });

 $('.in_cart').live('click', function(){
    top.location.href='/cart.php';
 });
  
 $('.cl_comp').hover(function(){
    $(this).toggleClass('cl_comp_act');
 }, function(){
    $(this).toggleClass('cl_comp_act');
 }); 


  init_ready();  

   try{
      $(".makers").jCarouselLite({
             btnNext: ".next",
             btnPrev: ".prev",
             vertical: false,
             circular: false,
             mouseWheel: true,
             scroll: 1,
             visible:3,
             width:700
         });
   } catch(e){}

   try{

   } catch(e){}
  
  
  
  
   var ud;
   
  $('.compare2').click(function(){
    $('label[for=prod_compare]').html('<a href="/compare.php">в сравнении</a>')
  }) 
   
  $('.compare,.compare2').live('mouseenter',function(){
    //clearTimeout(ud);
    $(this).find('.hover_').show();
  }).live('mouseleave', function(){
    var hov=$(this).find('.hover_');
    ud=setTimeout(function(){hov.hide();},500);
    //$(this).find('.hover_').hide();
  });
  $('.hover_').live('mouseenter',function(){
    clearTimeout(ud);
  });
  
  $('.favor').live('mouseenter',function(){
    $(this).find('.hover2_').show();
  }).live('mouseleave', function(){
    var hov=$(this).find('.hover2_');
    ud=setTimeout(function(){hov.hide();},500);
  });
  $('.hover2_').live('mouseenter',function(){
    clearTimeout(ud);
  });  
   
  $('.prev').hover(function(){
    $(this).toggleClass('prev_active');
  }, function() {
    $(this).toggleClass('prev_active');
  }); 

  $('.next').hover(function(){
    $(this).toggleClass('next_active');
  }, function() {
    $(this).toggleClass('next_active');
  }); 


       jQuery('.makers_catalog .item').mouseover(function(){
        jQuery('.makers_catalog .item').removeClass('active');
        jQuery(this).addClass('active');
        var subItems = jQuery(this).find('.subitems');
        var offset = jQuery(this).offset();
      }).mouseout(function(){
        jQuery(this).removeClass('active');
      });
      jQuery('.makers_catalog .subitems > .item1').mouseover(function(){
        jQuery('.makers_catalog .subitems > .item1').removeClass('active');
        jQuery(this).addClass('active');
      }).mouseout(function(){
        jQuery(this).removeClass('active');
      });


  $('.inp_bonus').live('keyup', function(){
    $(this).val($(this).val().replace (/\D/, ''));
    if (parseInt($('#limit_bonus').val())<=parseInt($(this).val()))
     $(this).val($('#limit_bonus').val());
  });
 
 
   $('#frm_order,#frm_order2').on('click','.fir, .sec',function(){
     $(this).parents('.inp').find('.numinput').trigger('blur');
   }); 

    
   $('ul.top_menu li.first').hover(function(){
     $(this).addClass('sfHover');
   }, function(){
     $(this).removeClass('sfHover');
   }); 
    
   $('#print').hover(function(){
     $(this).addClass('print_act');
   }, function(){
     $(this).removeClass('print_act');
   }); 
    
 
 $('#sp_dost input[type=radio]').live('change', function(){
    pr=$(this).parents('label').find('span').html();
    $('#delivery_summ').val(pr);
 });
 
  $('.numinput').live('keyup', function(){
    $(this).val($(this).val().replace (/\D/, ''));
  });
      
       $('.numinput').each(function(){
        cur_a=$(this).parent('table').find('.tbl_buy a.fb-ajax');
        $(cur_a).attr('href', $(cur_a).data('href')+'&kol='+$('#kol'+$(cur_a).data('id')).val());
       }); 

      
      $('.numinput').live('change',function(){
        
        cur_a=$(this).parents('table').find('.btn a.fb-ajax');
        $(cur_a).attr('href', $(cur_a).data('href')+'&kol='+$('#kol'+$(cur_a).data('id')).val());
        
        
         $.ajax({
            /* адрес файла-обработчика запроса */
            url: '/inc/action.php?action=delivery',
            method: 'POST',
            data: {
                "quant":$('#kol'+$(cur_a).data('id')).val(),
                "id":$(cur_a).data('id')
                
               },
            /* что нужно сделать до отправки запрса */
            beforeSend: function() {
                //$('#loading').show();
            /* меняем значение флага на true, т.е. запрос сейчас в процессе выполнения */
            inProgress = true;}
            /* что нужно сделать по факту выполнения запроса */
            }).done(function(data){
            
            /* Преобразуем результат, пришедший от обработчика - преобразуем json-строку обратно в массив */
             data = jQuery.parseJSON(data);
             pr1=data["prices"][0];
             pr2=data["prices"][1];
             
             $('#pr1').html(pr1);
             $('#pr2').html(pr2);
             
            /* Если массив не пуст (т.е. статьи там есть) */
            inProgress = false;

          });         
        
        
      });
  
    $(".head-podbor").live('click',function(){
        $('.hidden-podbor').toggle();
    })
  
    $('.placeinput').placeInput();
    
    /*
    $('.auth .block').eq(1).hover(function(){
       $(this).parent('.auth').find('.lk').hide().end().find('.lk.reg').show();  
    });

    $('.auth .block').eq(0).hover(function(){
       $(this).parent('.auth').find('.lk').hide().end().find('.lk.enter').show();  
    });
    
    $('.auth').mouseleave(function(){
       $(this).find('.lk').hide(); 
    }); 
    */
    $('.auth .block').eq(1).click(function(){
       $(this).parent('.auth').find('.lk').hide().end().find('.lk.reg').show();  
    });

    $('.auth .block').eq(0).click(function(){
       $(this).parent('.auth').find('.lk').hide().end().find('.lk.enter').show();  
    });
    
   	 $(document).mouseup(function (e){ // событие клика по веб-документу
		var div = $("#authes"); // тут указываем ID элемента
		if (!div.is(e.target) // если клик был не по нашему блоку
		    && div.has(e.target).length === 0) { // и не по его дочерним элементам
	        $(this).find('.lk').hide();
		}
	});
    
    
   try {
    $('#fqForm').validate();
    $('#fqForm2').validate();
    $('#frm_order').validate();
    $('#frm_order3').validate();
    $('#login_form').validate();
    $('#reg_form').validate({
       rules: {
        pwd2: { // поле, для которого задается правило
              equalTo: ".password" // должно равняться полю с классом password
            }         
       } 
    });
    
  	} catch(e) {}  
    
    $('.first_level_page').hover(function(){
       $(this).find('.sec_level').show(); 
    },function(){
       $(this).find('.sec_level').hide(); 
    });
    
    
    $('#left_brend').hover(function(){
       $(this).addClass('active_l'); 
    },function(){
       $(this).removeClass('active_l'); 
    });

    $('#right_brend').hover(function(){
       $(this).addClass('active_r'); 
    },function(){
       $(this).removeClass('active_r'); 
    });
    

      
/*	
    $('.btn').not('.vis_hid').hover(function(){
	   $(this).addClass('btn_act');
	}, function(){
	   $(this).removeClass('btn_act');
	});
*/

	$('.btn').live('mouseover',function(){
	   $(this).addClass('btn_act');
	});

	$('.btn').live('mouseout',function(){
	   $(this).removeClass('btn_act');
	});
    
	$('.btn_act').live('mousedown',function(){
	   $(this).addClass('btn_hover');
	}).live('mouseup',function(){
	   $(this).removeClass('btn_hover');
	});

	$('.btn2').live('mouseover',function(){
	   $(this).addClass('btn2_act');
	});

	$('.btn2').live('mouseout',function(){
	   $(this).removeClass('btn2_act');
	});
    
	$('.btn2_act').live('mousedown',function(){
	   $(this).addClass('btn2_hover');
	});
    
	$('.h_cat').live('mouseover',function(){
	   $(this).addClass('full_catalog_act');
	});

	$('.h_cat').live('mouseout',function(){
	   $(this).removeClass('full_catalog_act');
	});
    
    
	$('div.goods_tiles > .item  .to_cart').live('mouseover',function(){
	   $(this).addClass('to_cart_act');
	});

	$('div.goods_tiles > .item  .to_cart').live('mouseout',function(){
	   $(this).removeClass('to_cart_act');
	});

	$('div.goods_tiles > .item  .to_cart').live('mousedown',function(){
	   $(this).addClass('to_cart_hover');
	});


	$('div.goods_tiles > .item  .to_zapros').live('mouseover',function(){
	   $(this).addClass('to_zapros_act');
	});

	$('div.goods_tiles > .item  .to_zapros').live('mouseout',function(){
	   $(this).removeClass('to_zapros_act');
	});

	$('div.goods_tiles > .item  .to_zapros').live('mousedown',function(){
	   $(this).addClass('to_zapros_hover');
	});
    
    $('input.numinput').numArr();
	
	$('.navig a:last').addClass('active');

	$('.cbr').each(function(index, element) {
		$(this).find('input:checked').parent().addClass('active');
		$(this).click(function(){ $(this).removeClass('active').siblings().removeClass('active'); $(this).find('input:checked').parent().addClass('active'); });
	});
	
	$('.div_menu0').hover(
		function(){ $(this).find('.div_menu1').stop().css('opacity', 1).fadeIn(); },
		function(){ $(this).find('.div_menu1').stop().css('opacity', 1).fadeOut(); }
	);

	$('.mp0').hover(
		function(){ $(this).find('.mp1').stop().css('opacity', 1).fadeIn(); },
		function(){ $(this).find('.mp1').stop().css('opacity', 1).fadeOut(); }
	);
	
	$('.scroll_by_btn').scrollByBtn();
	
	try{ $('.scroll-pane').addClass('dblock').jScrollPane().removeClass('dblock'); }catch(e){}

	mask();

	try{
		$('#td_menucat').hover(
			function(){ $(this).find('div:first').stop().slideDown(); },
			function(){ $(this).find('div:first').stop().slideUp(); }
		);
	}catch(e){}
});

function integerDivision(x, y){
    return (x-x%y)/y
}

function checkNum(obj)
{
    num=$('#h_'+$(obj).attr('id')).val();
    
    kol=(integerDivision(obj.value,num));
    ost=obj.value%num;
    
    if (ost<(num/2)) itog=kol*num;
    else itog=kol*num + parseInt(num);
    
	//if(isNaN(obj.value) || obj.value<1)
		obj.value = itog;
}

function toCompare(id)
{
	toAjax("/compare.php?action=tocompare&id="+id);	
}

function mask()
{
	// ����� �����
	try {
		for(var i=1; i<6; i++)
			$.mask.definitions[i] = '[0-'+i+']';
		$('.mask_date').mask('39.19.2999');
		$('.mask_datetime').mask('39.19.2999 29:59');
		$('.mask_color').mask('#******');
		$('.mask_phone').mask('+7(999)999-99-99');
	} catch(e) {}
}

function initSlide(sliderID, p1, p2, p1t, p2t)
{
    p1t = p1t*1 || p1;
    p2t = p2t*1 || p2;
    var sliderObj = $("#" + sliderID);
    sliderObj.slider({
        min: p1,
        max: p2,
        values: [p1t,p2t],
        range: true,
        stop: function(event, ui) {
            $("#price1").val(ui.values[0]);
            $("#price2").val(ui.values[1]);
				$("#price1").keyup();
        },
        slide: function(event, ui){
            $("#price1").val(ui.values[0]);
            $("#price2").val(ui.values[1]);
        }
    });
//    $("#price1").val(sliderObj.slider("values",0));
//    $("#price2").val(sliderObj.slider("values",1));
    $('#' + sliderID + ' .ui-slider-handle').each(function (index, handle) {
        if (index % 2 == 0) $(handle).addClass('ui-slider-left-handle');
        else $(handle).addClass('ui-slider-right-handle');
    });
}

function open_login()
{
   $('#no_reg').hide();
   $('#open_login').show(); 
}

function initSlide2(sliderID, p1, p2, p1t, p2t, shag)
{
    p1t = p1t*1 || p1;
    p2t = p2t*1 || p2;
    var sliderObj = $("#" + sliderID);
    sliderObj.slider({
        min: p1,
        max: p2,
        values: [p1t,p2t],
        range: true,
		  step: shag,
        stop: function(event, ui) {
            $("#"+sliderID+'_from').val(ui.values[0]);
            $("#"+sliderID+'_to').val(ui.values[1]);
				$("#"+sliderID+'_from').keyup();
        },
        slide: function(event, ui){
            $("#"+sliderID+'_from').val(ui.values[0]);
            $("#"+sliderID+'_to').val(ui.values[1]);
        }
    });
//    $("#price1").val(sliderObj.slider("values",0));
//    $("#price2").val(sliderObj.slider("values",1));
    $('#' + sliderID + ' .ui-slider-handle').each(function (index, handle) {
        if (index % 2 == 0) $(handle).addClass('ui-slider-left-handle');
        else $(handle).addClass('ui-slider-right-handle');
    });
}

function toggleIndexCat() {
    var ic = $('#index_cat');
    var icd = $('#index_cat_dummy');
    if (ic.css('display') != 'block') {
        icd.hide();
        ic.slideDown(300);
    } else {
        ic.slideUp(300, function(){icd.show();});
    }
}

var subcategoriesTimeout, subcategoriesTimeout2;

function showTopSubcategories(itemID, hidden) 
{
    clearTimeout(subcategoriesTimeout);
    clearTimeout(subcategoriesTimeout2);
	subcategoriesTimeout2 = setTimeout(function() {	 
		 var d1 = $('#dummy1');
		 var cs = $('#top_category_subitems_' + itemID);
		 var ci = $('#top_category_item_' + itemID);
		 if (cs.css('display') != 'block') {
			  var p = ci.offset();
			  var t = parseInt(p.top);
			  var l = parseInt(p.left);
			  //cs.css('top', t + (!hidden ? 0 : -218) + 'px');
			  //cs.css('left', l + (!hidden ? 229 : -112) + 'px');
			  $('div[id^="top_category_item_"]').removeClass('active');
			  ci.addClass('active');
			  $('div[id^="top_category_subitems_"]').hide();
			  cs.show();
			  if (cs.prop('id') != undefined) {
	//            d1.css('top', t + (!hidden ? 1 : -217) + 'px');
	//            d1.css('left', l + (!hidden ? 220 : -82) + 'px');
					d1.css('top', t + 1 + 'px');
					d1.css('left', l + 220 + 'px');
					d1.css('height', ci.height() + 8 + 'px');
					d1.show();
			  } else {
					d1.hide();
			  }
		 }
    }, 300);
}

function hideTopSubcategories(itemID) {
    clearTimeout(subcategoriesTimeout);
    clearTimeout(subcategoriesTimeout2);
    subcategoriesTimeout = setTimeout(function() {
        hideTopSubcategoriesEntirely(itemID);
        $('#top_category_item_' + itemID).removeClass('active');
    }, 500);
}

function hideTopSubcategoriesEntirely(itemID) {
    $('#dummy1').hide();
    $('div[id^="top_category_subitems_"]').hide();
}

function toggleSubcategories(itemID) {
    var cs = $('#category_subitems_' + itemID);
    var ci = $('#category_item_' + itemID);
    if (cs.css('display') != 'block') {
        ci.addClass('active');
        cs.slideDown();
    } else {
        cs.slideUp();
        ci.removeClass('active');
    }
}

function equalizeBlocksHeight(str) {
    var maxHeight = 0;
    var names = $(str);
    names.each(function() {
        if ($(this).height() > maxHeight) {
            maxHeight = $(this).height();
        }
    });
    names.height(maxHeight);
}
