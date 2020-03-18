var wasrowupdown=false;

$(function(){
      $('.add_uzel').click(function(){
        //$('.uzel').last().clone().appendTo('.out_uzel');
          var element=$('#count-uzel').val();
          element=parseInt(element)+1;
          $('#count-uzel').val(element);
          
          var inf_det=$('.out_uzel').append('<div class="uzel"><div class="uzel_add">\
            <div class="name-uzel"><input type="text" value="" placeholder="Название узла" name="name_uzel['+element+']" /></div>\
            <div class="photo-uzel"><input type="file" value="" name="file_uzel['+element+']" /></div>\
          </div>\
          <h3>Детали</h3>\
          <div class="block-details'+element+'">\
            <div class="info-det">\
              <div class="name-detail"><input type="text" value="" placeholder="Название детали или артикул"  name="name_detail['+element+'][]" /></div>\
              <div class="number-detail"><input type="text" value="" placeholder="Номер на схеме" name="number_detail['+element+'][]" /></div>\
            </div>\
          </div>\
          <div class="add_detail" data-uzel="'+element+'"><a href="javascript:void(0)">+ Добавить деталь</a></div>\
          </div>');
      
      
            $('.name-detail input').autocomplete({
            serviceUrl:'/inc/action.php?action=search_onlyg',
            deferRequestBy: 100, // задержка между запросами
            //maxHeight: 200, // Максимальная высота списка подсказок, в пикселях
            zIndex: 9999, // z-index списка
            minChars: 3, // Минимальная длина запроса для срабатывания автозаполнения
            onSelect: function(value, data){
                console.log(data);
                //$(this).parents('form:eq(0)').submit(); 
           }
      });
      
      });
      $('.add_detail').live('click',function(){
        var uzel=$(this).data('uzel');
        $('.block-details'+uzel).append('<div class="info-det">\
              <div class="name-detail"><input type="text" value="" placeholder="Название детали или артикул"  name="name_detail['+uzel+'][]" /></div>\
              <div class="number-detail"><input type="text" value="" placeholder="Номер на схеме" name="number_detail['+uzel+'][]" /></div>\
            </div>');
        
        //$(this).parent('.uzel').find('.info-det').last().clone().appendTo($('.block-details'+uzel));
        //$('#uzel'+uzel+' .block-details').append()
      
            $('.name-detail input').autocomplete({
        serviceUrl:'/inc/action.php?action=search_onlyg',
        deferRequestBy: 100, // задержка между запросами
        //maxHeight: 200, // Максимальная высота списка подсказок, в пикселях
        zIndex: 9999, // z-index списка
        minChars: 3, // Минимальная длина запроса для срабатывания автозаполнения
        onSelect: function(value, data){
            console.log(data);
            //$(this).parents('form:eq(0)').submit(); 
           }
      });
      })
      
      
      $('.name-detail input').autocomplete({
        serviceUrl:'/inc/action.php?action=search_onlyg',
        deferRequestBy: 100, // задержка между запросами
        //maxHeight: 200, // Максимальная высота списка подсказок, в пикселях
        zIndex: 9999, // z-index списка
        minChars: 3, // Минимальная длина запроса для срабатывания автозаполнения
        onSelect: function(value, data){
            console.log(data);
            //$(this).parents('form:eq(0)').submit(); 
           }
      });


   try{ 
    $('.type_to_form').change(function(){
        if ($(this).val()=='3')
          $(this).parent('td').find('textarea').show();
        else
          $(this).parent('td').find('textarea').hide();
    })
   }
   catch(e){} 

	// CKEditor
	try {
		$('textarea[toolbar]').each(function() { // ПРИМЕР: <textarea name="text" toolbar="basic" rows="10"><?=$row['text']?></textarea>
			this.id = this.name;
			var toolbar = $(this).attr('toolbar');
			var CKEditor = CKEDITOR.replace(this.id,
			{
				scayt_autoStartup: false, // отключаем встроенную проверку арфографии (чтоб юзалась браузерная) 
				disableNativeSpellChecker: false, // отключаем встроенную проверку арфографии (чтоб юзалась браузерная)
				toolbar: toolbar,
				width: $(this).width(),
				height: $(this).height(),
				removePlugins: toolbar == 'Full' ? '' : 'elementspath',
				resize_enabled: toolbar == 'Full',
				contentsCss: '/inc/style.css',
				coreStyles_bold: { element : 'b' },
				coreStyles_italic: { element : 'i' },
				skin: 'v2',
				uiColor: '#E8EBF1',
				toolbar_full:[
					['Source','-','Save','Preview','-','Templates'],
					['Cut','Copy','Paste','PasteText','PasteFromWord'],
					['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
					['Form','Checkbox','Radio','TextField','Textarea','Select','Button','ImageButton','HiddenField'],'/',
					['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
					['NumberedList','BulletedList','-','Outdent','Indent','Blockquote','CreateDiv'],
					['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
					['BidiLtr','BidiRtl'],['Link','Unlink','Anchor'],
					['Image','Flash','Table','HorizontalRule','SpecialChar'],'/',
					['Styles','Format','Font','FontSize'],['TextColor','BGColor'],['ShowBlocks'] 
				],
				toolbar_medium:[
					['Source','-','Save','Preview','-','Templates'],['Cut','Copy','Paste','PasteText','PasteFromWord'],
					['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],'/',
					['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
					['NumberedList','BulletedList'],['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
					['Link','Unlink'],['Image','Flash','Table','HorizontalRule','SpecialChar'],'/',
					['Format','Font','FontSize'],['TextColor','BGColor'],['ShowBlocks'] 
				],
				toolbar_basic:[
					['Source','-','Bold','Italic','Underline','-','TextColor','-','Link','Unlink','-','Image']
				]
			});
			CKFinder.setupCKEditor(CKEditor, '/admin/ckfinder/');
		});
	} catch(e) {}
		
	// перетаскивание строк таблицы
	try {
		$('a.laMove').parent().parent().addClass('drag');
		var ids_befor_drag = new Array();
		$('table.content, table.red').tableDnD({
			dragHandle: 'drag',
			onDrop: function(table, row){
				if(!wasrowupdown) return;
				wasrowupdown = false;
				for(var h=0; h<table.tBodies.length; h++)
				{
					var rows = table.tBodies[h].rows;
					for(var i=0; i<rows.length; i++)
						if(rows[i].id == row.id)
							break;
					for(var j=0; j<ids_befor_drag[h].length; j++)
						if(ids_befor_drag[h][j] == row.id)
							break;
					if(i != j) break;
				}
				if(i == j) return;
				//alert(i-j);
				toAjax('?id='+str_replace('tr','',row.id)+'&action=move&step='+(i-j)+'&noreload');
				paintClassContent();
				$('#'+row.id).addClass('active');
			},
			onDragStart: function(table, row) {
				if(!wasrowupdown) return;
				for(var h=0; h<table.tBodies.length; h++)
				{
					var rows = table.tBodies[h].rows;
					ids_befor_drag[h] = new Array(rows.length);
					for(var i=0; i<rows.length; i++)
						ids_befor_drag[h][i] = rows[i].id;
				}
			}
		});
	} catch(e) {}
		
	// редактибельный DIV
	try {
		$('div[class^="redone"]').editable('?action=redone&show_value', {
			//type: 'textarea',
			tooltip: 'Нажмите для редактирования',
			id: 'field',
			name: 'value',
			height: '20px',
			placeholder: '&nbsp;',
			onblur: 'submit',
			data: function(value, settings) {
				return value.replace(/<br[\s\/]?>/gi, '\n'); // Convert <br> to newline.
			},
			callback: function(data, settings) {
				data = str_replace(['<script>','</script>'], '', data);
				try { eval(data); } catch(e){}
			}
		});
	} catch(e) {}
	
	// запрещаем двойной клик на редактируемом DIV-е (приходится писать так, на jQ это почему-то не прокатывает
	var div = document.getElementsByTagName('div');
	for(var i=0; i<div.length; i++)
		if(div[i].className == 'redone')
			div[i].ondblclick = function(event) { event.cancelBubble=true; }
	
	// сортировка
	$('table.content th[sort]').each(function(){
		$(this).addClass('sort').css('background-position', $(this).attr('sortbg'));
		$(this).click(function(){ location.href = $(this).attr('sort'); });
	});
	
	// маска ввода
	try {
		for(var i=1; i<6; i++)
			$.mask.definitions[i] = '[0-'+i+']';
		$('.mask_date').mask('39.19.2999');
		$('.mask_datetime').mask('39.19.2999 29:59');
		$('.mask_color').mask('#******');
		$('.mask_phone').mask('+7(999)999-99-99');
	} catch(e) {}

	// раскрашиваем таблицы "content"
	paintClassContent();		
});



function add_photo()
{
	var $tab = jQuery('#tab_add_photo');
	var count = $tab.find('tr').size();
	
	var _tr = '<tr><td><input type="file" name="user_photo['+(count+1)+']" /></td>';
	//if(count+1<3)
		_tr += '<td><a href="" onclick="add_photo();return false;">ещё</a></td></tr>';
	//else 
	//	_tr += '<td></td></tr>';
	
	$tab.append(_tr);
	
	// убираем 'ещё' у предыдущей строки
	$tab.find('tr').eq(count-1).find('td').eq(1).html('');
}


// раскрашиваем таблицы "content"
function paintClassContent()
{
	$('table[class="content"] tr').removeClass('second');  // ! не трогать [class="content"] !
	$('table[class="content"]').each(function(index, element) {  // на случий если у нас несколько таких таблиц, делаем цикл
		$(this).find('tr:visible:odd').addClass('second');
	});
	$('table.content tr').removeClass('active');
}
