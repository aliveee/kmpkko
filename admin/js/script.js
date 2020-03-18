$(document).ready(function(){
   
	try{
   var hash=window.location.hash;
   if ($('ul.tabs15').length>0 && hash)
     {
       $('ul li.tabs15.last').click();
       console.log(hash.length);
       
       var top=$('a[name='+hash.substr(1,hash.length-1)+']').offset().top;
       $('html, body').animate({scrollTop: top}, 1000);
        
     }
	}catch(e){}
   
   console.log(location.hash);
   
   $('.search_by').click(function(){
     $('.main-label').html($(this).data('val'));
     if ($(this).data('num')==2)
     {
       $('input.main-number').addClass('phone').addClass('mask_phone');
       mask();
     }
     else
     {
       $('input.main-number').removeClass('phone').removeClass('mask_phone').removeClass('error').removeAttr('aria-invalid').removeAttr('aria-required').unmask();
     }  
   })
   
   
   $('#scroller').click(function () {
        $('body,html').animate({
            scrollTop: 0
        }, 400);
        return false;
    });
	
	$('.add_img li').click(function(){
		
		$(this).addClass('active').siblings().removeClass('active');
	
	});
	

    try {
     
     if ($('#default_uzel').length>0)
     {
      var cur_uzel=$('#default_uzel').val();  
      detail_update(cur_uzel);
      update_uzel_info(cur_uzel); 
      $('.uzel[data-id='+cur_uzel+']').addClass('active').siblings('.uzel').removeClass('active');
     } 
    }
    catch(e) {}

  function detail_update(uzel_id){
    
        $.ajax({
            /* адрес файла-обработчика запроса */
            url: '/inc/obrabotchik.php',
            /* метод отправки данных */
            method: 'POST',
            /* данные, которые мы передаем в файл-обработчик */
            data: {
                "uzel_id": uzel_id,
                "type": 'uzel'
               },
            /* что нужно сделать до отправки запрса */
            beforeSend: function() {
                //return false;
                $('#loading').show();
            /* меняем значение флага на true, т.е. запрос сейчас в процессе выполнения */
            }
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
            
            $("#all_goods").html(data);
            $('input.numinput').numArr();
          
          }});    
    }
    
 function update_uzel_info(uzel_id){
        $.ajax({
            /* адрес файла-обработчика запроса */
            url: '/inc/obrabotchik.php',
            /* метод отправки данных */
            method: 'POST',
            /* данные, которые мы передаем в файл-обработчик */
            data: {
                "uzel_id": uzel_id,
                "type": 'uzel_info'
               },
            /* что нужно сделать до отправки запрса */
            beforeSend: function() {
                //return false;
                //$('#loading').show();
            /* меняем значение флага на true, т.е. запрос сейчас в процессе выполнения */
            }
            /* что нужно сделать по факту выполнения запроса */
            }).done(function(data){
                
              
                //$('#loading').hide();
            
            /* Преобразуем результат, пришедший от обработчика - преобразуем json-строку обратно в массив */
            //data = jQuery.parseJSON(data);

            /* Если массив не пуст (т.е. статьи там есть) */
            if (data.length>0) {

            /* Делаем проход по каждому результату, оказвашемуся в массиве,
            где в index попадает индекс текущего элемента массива, а в data - сама статья */
            //$.each(data, function(index, data){

            /* Отбираем по идентификатору блок со статьями и дозаполняем его новыми данными */
            //console.log(data);
            
            data = $.parseJSON(data);
            
            
            $('.uzel_info .cur-img img').attr('src',data['img']);
            $('.uzel_info .cur-name').html(data['name']);
            
          }});    
    }    


   $('.goods_list .uzel').click(function(){
    
    var uzel_id=$(this).data('id');
    detail_update(uzel_id);
    update_uzel_info(uzel_id);
    $(this).addClass('active').siblings('.uzel').removeClass('active');
    
   });
	

	try{
		if(a_otzivy_click)
 			$('#a_otzivy').click();	
	}catch(e){}


});