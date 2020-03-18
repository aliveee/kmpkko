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
            /* ����� �����-����������� ������� */
            url: '/inc/obrabotchik.php',
            /* ����� �������� ������ */
            method: 'POST',
            /* ������, ������� �� �������� � ����-���������� */
            data: {
                "uzel_id": uzel_id,
                "type": 'uzel'
               },
            /* ��� ����� ������� �� �������� ������ */
            beforeSend: function() {
                //return false;
                $('#loading').show();
            /* ������ �������� ����� �� true, �.�. ������ ������ � �������� ���������� */
            }
            /* ��� ����� ������� �� ����� ���������� ������� */
            }).done(function(data){
                
              
                $('#loading').hide();
            
            /* ����������� ���������, ��������� �� ����������� - ����������� json-������ ������� � ������ */
            //data = jQuery.parseJSON(data);

            /* ���� ������ �� ���� (�.�. ������ ��� ����) */
            if (data.length>0) {

            /* ������ ������ �� ������� ����������, ������������ � �������,
            ��� � index �������� ������ �������� �������� �������, � � data - ���� ������ */
            //$.each(data, function(index, data){

            /* �������� �� �������������� ���� �� �������� � ����������� ��� ������ ������� */
            
            $("#all_goods").html(data);
            $('input.numinput').numArr();
          
          }});    
    }
    
 function update_uzel_info(uzel_id){
        $.ajax({
            /* ����� �����-����������� ������� */
            url: '/inc/obrabotchik.php',
            /* ����� �������� ������ */
            method: 'POST',
            /* ������, ������� �� �������� � ����-���������� */
            data: {
                "uzel_id": uzel_id,
                "type": 'uzel_info'
               },
            /* ��� ����� ������� �� �������� ������ */
            beforeSend: function() {
                //return false;
                //$('#loading').show();
            /* ������ �������� ����� �� true, �.�. ������ ������ � �������� ���������� */
            }
            /* ��� ����� ������� �� ����� ���������� ������� */
            }).done(function(data){
                
              
                //$('#loading').hide();
            
            /* ����������� ���������, ��������� �� ����������� - ����������� json-������ ������� � ������ */
            //data = jQuery.parseJSON(data);

            /* ���� ������ �� ���� (�.�. ������ ��� ����) */
            if (data.length>0) {

            /* ������ ������ �� ������� ����������, ������������ � �������,
            ��� � index �������� ������ �������� �������� �������, � � data - ���� ������ */
            //$.each(data, function(index, data){

            /* �������� �� �������������� ���� �� �������� � ����������� ��� ������ ������� */
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