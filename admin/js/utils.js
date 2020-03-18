$(function(){
	
	//получаем весь элемент
	//var html = $('<div>').append($(this).clone()).remove().html();
	
    $('.input_check').formstyler;
    
	// настройка fancybox
	try {
		$(".fb-img").fancybox({  // картики  (для группы картинок использовать одинаковый rel="group_name")
			openEffect	: 'elastic',
	    	closeEffect	: 'elastic',
			helpers : {
				overlay : {
					locked : false
				}
			}
		});
 
		$(".ff-im").fancybox({  // картики  (для группы картинок использовать одинаковый rel="group_name")
			openEffect	: 'elastic',
	    	closeEffect	: 'elastic',
			helpers : {
				overlay : {
					locked : false
				}
			}
		});
      
		$(".fb-ajax").fancybox({  // контент
			openEffect	: 'fade',
	    	closeEffect	: 'fade',
			type: 'ajax',
			helpers : {
				overlay : {
					locked : false
				}
			}			
		});

		$(".fb-ajax-700").fancybox({  // контент
			openEffect	: 'fade',
	    	closeEffect	: 'fade',
			type: 'ajax',
            maxWidth: 700,
			helpers : {
				overlay : {
					locked : false
				}
			}			
		});

		$(".fb-ajax2").fancybox({  // контент
			openEffect	: 'fade',
	    	closeEffect	: 'fade',
			type: 'ajax',
			helpers : {
				overlay : {
					locked : false
				}
			},
            afterShow: function(){
              $('div.starrating').starRating();
            }
		});
        
        $(".cbox").fancybox({  // контент
			openEffect	: 'fade',
	    	closeEffect	: 'fade',
			type: 'ajax',
			helpers : {
				overlay : {
					locked : false
				}
			}
			
		});

	/*
    	$(".fb-compare").fancybox({  // сравнение
			openEffect	: 'fade',
	    	closeEffect	: 'fade',
			type: 'ajax',
			helpers : {
				overlay : {
					locked : false
				}
			},
			beforeClose: function() {
          location.reload();
        }			
		});
    */     
		$(".fb-media").fancybox({   // youtube и подобные
			openEffect	: 'fade',
	    	closeEffect	: 'fade',
			helpers : {
				media : {},
				overlay : {
					locked : false
				}				
			}		
		});
	} catch(e){}
	
	// вертикальная / горизонтальная прокрутки колесиком мыши
	try {
		$('.vscroll').mousewheel(function(event, intDelta){ $(this).scrollTop($(this).scrollTop() - 30*intDelta); return false; });
		$('.gscroll').mousewheel(function(event, intDelta){ $(this).scrollLeft($(this).scrollLeft() - 30*intDelta); return false; });
	} catch(e){}

	$('[bgpos]').bgpos(); // устанавливаем бэкграун элененту на новедение (и нажатие)
	$('.blink').blink();	// мигающий текст
});


$(window).load(function(){ // initialize after images are loaded

	//$('img[zoom]').imgZoom();

	$('div.starrating').starRating();
/*
	$('div.zak_gr').zak();
	
	$('img.ratio').imgRatio(); // устанавливаем размеры в соответствии с пропорциями
	$('.height100, .h100').height100(); // высота 100% !родительскому элементу высоту класс height100 не ставить!
	$('.oneheight, .oneh').oneheight(); // Делаем элементам одинаковую высоту
	for(var i=1; i<9; i++)
		$('.oneh'+i).oneheight(); // Делаем элементам одинаковую высоту

	$('.oneh_gr').each(function(index, element) { // Делаем элементам в группе одинаковую высоту
		$(this).find('.oneh_4gr').oneheight(); 
	});
	
	$('.scroll_by_btn').scrollByBtn();
  */  
});


// ВЫСОТА 100%
$.fn.height100 = function(){
	this.height(function(){
		return $(this).parent().height() - $(this).outerHeight(true) + $(this).height();
	});
}

// ДЕЛАЕМ ЭЛЕМЕНТАМ ОДИНАКОВУЮ ВЫСОТУ
$.fn.oneheight = function(){
	var h = 0;
	this.each(function(){
		if($(this).outerHeight() > h)
			h = $(this).height();
	});
	this.height(h);
}

// УСТАНАВЛИВАЕМ РАЗМЕРЫ В СООТВЕТСТВИИ С ПРОПОРЦИЯМИ
// Пример: <img src="/uploads/goods/<?=$row['id']?>.png" width="100" height="50" class="ratio">
$.fn.imgRatio = function(){
	this.each(function(index, element) {
		var m = [$(this).width(), $(this).height()]; // максимальне размеры
		$(this).removeAttr("width").removeAttr("height").css({ width: "", height: "",  visibility:'hidden' }); // убираем размеры у картинки
		var r = [$(this).width(), $(this).height()]; // настоящие размеры
		var n = [0, 0]; // итоговые размеры
		if(m[0] > r[0]) m[0] = r[0];
		if(m[1] > r[1]) m[1] = r[1];
		if(m[0] > 0)
		{
			n[1] = Math.round(m[0] * r[1] / r[0]);
			n[0] = m[0];
		}
		if(n[0] == 0 || (m[1] > 0 && n[1] > m[1]))
		{
			n[0] = Math.round(m[1] * r[0] / r[1]);
			n[1] = m[1];
		}
		$(this).attr({ width: n[0], height: n[1] }).css({ visibility:'visible' }); // задаем новые размеры
	});
}


// МИГАЮЩИЙ ТЕКСТ
$.fn.blink = function(options)
{
	var defaults = { delay:500 };
	var options = $.extend(defaults, options);
	return this.each(function(){
		var obj = $(this);
		setInterval(function(){	obj.css('visibility', obj.css('visibility')=='visible' ? 'hidden' : 'visible'); }, options.delay);
	});
}

// ЗАКЛАДКИ
/* <script> $('.zak_gr').zak(); </script>
	<div class="zak_gr">
		<div><a href="#" class="zak">Название закладки 1</a><a href="#" class="zak">Название закладки 2</a></div>
		<div><div class="zak">Содержимое закладки 1</div><div class="zak">Содержимое закладки 2</div></div>
	</div> */
// если у ссылки стоит класс "active" - клик будет по ней. Ссылка с классом "noactive" считается не активной
$.fn.zak = function(){
	this.each(function(index, element) {
		var zak_gr = $(this);
		zak_gr.find('a.zak').click(function(){
			if($(this).hasClass('noactive'))
				return false;
			$(this).addClass('active').siblings('a.zak').removeClass('active');
			zak_gr.find('div.zak').removeClass('active').eq(zak_gr.find('a.zak').index(this)).addClass('active');
			if($(this).attr('href') == '#')
				return false;
		});
		if(!zak_gr.find('a.zak.active').length)
			zak_gr.find('a.zak:first').addClass('active');
		zak_gr.find('a.zak.active:first').click();
	});
	$('div.zak:last').after('<style>div.zak { display:none; } div.zak.active { display:block; } </style>');
	$('.zak_gr').css('height', 'auto');
}

// ПОМЕЩАЕМ ЭЛЕМЕНТ В ЦЕНТР ЭКРАНА // Пример: $('#div123').alignCenter();
$.fn.alignCenter = function(){
	var marginLeft = Math.max(40, parseInt($(window).width()/2+scrollPosition().x - $(this).width()/2)) + 'px'; //get margin left
	var marginTop = Math.max(40, parseInt($(window).height()/2+scrollPosition().y - $(this).height()/2)) + 'px'; //get margin top
	return $(this).css({'margin-left':marginLeft, 'margin-top':marginTop}); //return updated element
};		

// БЭКГРАУН ЭЛЕНЕНТУ НА НОВЕДЕНИЕ (И НАЖАТИЕ) МЫШКИ
// Пример: <a href="/" class="bg" style="width:46px; height:46px; background-position:-246px -222px;" bgpos="-246px -222px,-246px -267px,-246px -312px"></a>
$.fn.bgpos = function(){
	this.each(function(index, element){
		var bgpos = explode(',', $(this).attr('bgpos'));
		if(bgpos == null)
			return;
		if($(this).hasClass('active'))
			$(this).css('background-position', bgpos[1]);
		else
			$(this).hover( // наведение мышки
				function(){ $(this).css('background-position', bgpos[1]); },
				function(){ $(this).css('background-position', bgpos[0]); }
			);
		if(isset(bgpos[2])) // нажатие мышки
			$(this).mousedown(function(){ $(this).css('background-position', bgpos[2]); })
					 .mouseup(  function(){ $(this).css('background-position', bgpos[1]); });
	});
};

// СКРОЛЛ С ПОМОЩЬЮ КНОПОК, см.пример
/*	пример:
	<div class="scroll_by_btn">
		<a href="#" class="s_left">назад</a> | <a href="#" class="s_up">вверх</a>
		<div class="s_content" style="widht100px; height:100px; overflow:hidden;"> *** </div>
		<a href="#" class="s_right">вперед</a> | <a href="#" class="s_down">вниз</a>
	</div>
	<script> $('.scroll_by_btn').scrollByBtn(); <//script>	*/
$.fn.scrollByBtn = function(options){
	var options = $.extend({ delay:50, shag:9, fadeOut:true, animate:false }, options); // ЗАДЕРЖКА, ШАГ, СКРЫТЬ КНОПКИ ЕСЛИ НЕ ТРЕБУЮТСЯ, ПРОКРУЧИВАТЬ АНИМАЦИЕЙ (на величину шага)
	var int;
	this.each(function(index, element) {
		var obj = $(this);
		var s_content = obj.find('.s_content:first');

		if(s_content.innerWidth() == s_content[0].scrollWidth)  // скроллирование по горизонтале не требуется
			if(options.fadeOut)
				obj.find('.s_left, .s_right').css('visibility', 'hidden'); // скрываем кнопки если не нужны
			
		if(s_content.innerHeight() == s_content[0].scrollHeight)  // скроллирование по вертикале не требуется
			if(options.fadeOut)
				obj.find('.s_up, .s_down').css('visibility', 'hidden'); // скрываем кнопки если не нужны

		s_content.scrollLeft(0); s_content.scrollTop(0); // задаем исходное состояние
		if(options.fadeOut) obj.find('.s_left, .s_up').hide();
		// влево вправо
		obj.find('.s_left, .s_right').mousedown(function(){
			var left = $(this).hasClass('s_left');
			// код прокрутки
			var to = s_content.scrollLeft() + options.shag * (left ? -1 : 1);
			if(options.animate)
				s_content.animate({scrollLeft:to}, options.delay);
			else
				s_content.scrollLeft(to);
			if((!left && to + s_content.innerWidth() >= s_content[0].scrollWidth) || (to <= 0 && left))
			{
				clearInterval(int);
				if(options.fadeOut) obj.find(left ? '.s_left' : '.s_right').fadeOut();
			}
			int = setInterval(function(){
				// повторяем код прокрутки
				var to = s_content.scrollLeft() + options.shag * (left ? -1 : 1);
				if(options.animate)
					s_content.animate({scrollLeft:to}, options.delay);
				else
					s_content.scrollLeft(to);
				if((!left && to + s_content.innerWidth() >= s_content[0].scrollWidth) || (to <= 0 && left))
				{
					clearInterval(int);
					if(options.fadeOut) obj.find(left ? '.s_left' : '.s_right').fadeOut();
				}
			}, options.delay);
			obj.find(left ? '.s_right' : '.s_left').fadeIn();
		});
		// вверх вниз
		obj.find('.s_up, .s_down').mousedown(function(){
			var up = $(this).hasClass('s_up');
			// код прокрутки
			var to = s_content.scrollTop() + options.shag * (up ? -1 : 1);
			if(options.animate)
				s_content.animate({scrollTop:to}, options.delay);
			else
				s_content.scrollTop(to);
			if((!up && to + s_content.innerHeight() >= s_content[0].innerHeight) || (!to <= 0 && up))
			{
				clearInterval(int);
				if(options.fadeOut) obj.find(up ? '.s_up' : '.s_down').fadeOut();
			}
			int = setInterval(function(){
				// повторяем код прокрутки
				var to = s_content.scrollTop() + options.shag * (up ? -1 : 1);
				if(options.animate)
					s_content.animate({scrollTop:to}, options.delay);
				else
					s_content.scrollTop(to);
				if((!up && to + s_content.innerHeight() >= s_content[0].innerHeight) || (!to <= 0 && up))
				{
					clearInterval(int);
					if(options.fadeOut) obj.find(up ? '.s_up' : '.s_down').fadeOut();
				}
			}, options.delay);
			obj.find(up ? '.s_down' : '.s_up').fadeIn();
		});
		// общее для кнопок
		obj.find('.s_left, .s_right, s_up, s_down').mouseup(function(){ clearInterval(int); }).click(function(){ return false; });
	});
}


// ПЕРЕДАЕМ URL ВО ФРЕЙМ
function toIframe(url)
{
	//frames["iframe"].document.location.href = url;
	showLoad(true);
	top.$("#iframe").attr('src', url);
}

// ПОКАЗАТЬ/СКРЫТЬ АНИМАШКУ "ЗАГРУЗКА"
function showLoad(visible)
{
	if(visible == null)
		visible = true;
	try {	top.$("#imgLoad").css('visibility', visible ? '' : 'hidden');	} catch(e) {}  
}

// ПЕРЕЗАГРУЗИТЬ СТРАНИЦУ ПОСЛЕ РАБОТЫ ФРЕЙМА
function topReload()
{
	//top.location.href = top.location.href;	return;
	if($.browser.webkit || $.browser.opera)
		location.reload();
	else if($.browser.mozilla)	{
		history.back();
		setTimeout("top.location.reload(true)",500);
	} else {
		history.back();
		location.reload();
	}
}
// ВЫЗОВ ФУНКЦИИ history.back() ПОСЛЕ РАБОТЫ ФРЕЙМА
function topBack(post) // post - страница дергалась формой (иначе - ссылкой)
{
	showLoad(false);
	if(!($.browser.webkit && !post))
		history.back();
}

// ОТКРЫВАЕТ СТРАНИЦУ В ОТДЕЛЬНОМ ОКНЕ   Пример: <a href="page.htm" onClick="return openWindow(this,570,700)">открыть</a>
function openWindow(obj, width, height)
{
	//width	размер в пикселах	ширина нового окна  *  height	размер в пикселах	высота нового окна  *  left	размер в пикселах	абсцисса левого верхнего угла нового окна  *  top	размер в пикселах	ордината левого верхнего угла нового окна  *  toolbar	1 / 0 / yes / no	вывод панели инструменов  *  location	1 / 0 / yes / no	вывод адресной строки  *  directories	1 / 0 / yes / no	вывод панели ссылок  *  menubar	1 / 0 / yes / no	вывод строки меню  *  scrollbars	1 / 0 / yes / no	вывод полос прокрутки  *  resizable	1 / 0 / yes / no	возможность изменения размеров окна  *  status	1 / 0 / yes / no	вывод строки статуса  *  fullscreen	1 / 0 / yes / no	вывод на полный экран
	var left = Math.round((screen.width-width)/2);
	var top = Math.round((screen.height-height)/2)-40;
	var win = window.open(obj.href, '', 'resizable=yes,width='+width+',height='+height+',scrollbars=1,top='+top+',left='+left);
	win.focus();
	return false;
}

// 
function sure()
{
	return confirm("Уверены?");
}

// ОПРЕДЕЛЕНИЕ КООРДИНАТ ЭЛЕМЕНТА   Пример: x = absPosition(obj).x;
function absPosition(obj) 
{ 
	var offset = $(obj).offset();
	var x = offset.left;
	var y = offset.top;
	return {x:x, y:y};
}

// ОПРЕДЕЛЕНИЕ КООРДИНАТ ПОЛОСЫ ПРОКРУТКИ БРАУЗЕРА
function scrollPosition() 
{
	var scrOfX = 0, scrOfY = 0;
	if( typeof( window.pageYOffset ) == 'number' ) {	//Netscape compliant
		scrOfY = window.pageYOffset;
		scrOfX = window.pageXOffset;
	} else if( document.body && ( document.body.scrollLeft || document.body.scrollTop ) ) {	//DOM compliant
		scrOfY = document.body.scrollTop;
		scrOfX = document.body.scrollLeft;
	} else if( document.documentElement && ( document.documentElement.scrollLeft || document.documentElement.scrollTop ) ) {	//IE6 Strict
		scrOfY = document.documentElement.scrollTop;
		scrOfX = document.documentElement.scrollLeft;
	}
	return { x:scrOfX, y:scrOfY };
}

// ФУНКЦИЯ ПРОВЕРКИ ДАТЫ ВИДА xx.xx.xxxx
function checkDate(val)
{
	return (/^\d{2}\.\d{2}\.\d{4}$/.test(val));
}
// ПРОВЕРКА E-mail
function checkEmail(email)
{
    var reg = new RegExp("^[0-9a-z_^\.]+@[0-9a-z_^\.]+\.[a-z]{2,6}$", 'i');
    return reg.test(email);
}

// ПОЛУЧЕНИЕ GET ПАРАМЕТРОВ
function getQueryVariable(query) //query - можно не передавать
{
	//полачаем строку запроса (?a=123&b=qwe) и удаляем знак ?
	if(!query)
		query = window.location.search.substring(1);  
	//получаем массив значений из строки запроса вида vars[0] = ‘a=123’;
	var vars = query.split("&");
	var arr = new Array(); 
	//переводим массив vars в обычный ассоциативный массив 
	for (var i=0;i<vars.length;i++) 
	{
		var pair = vars[i].split("=");
		arr[pair[0]] = pair[1];
	}
	return arr;
}

// ПРЕДВАРИТЕЛЬНАЯ ЗАГРУЗКА КАРТИНОК
function preloadImg() // в аргументы передаются пути к картинкам
{
	arg = preloadImg.arguments;
	img = new Array();
	for(i=0; i<arg.length; i++)
	{
		img[i] = new Image;
		img[i].src = arg[i];
	}
}

// ЭЛЕМЕНТ ВЫЗВАВШИЙ СОБЫТИЕ
function getObjEvent(e)
{
	var e = e || window.event;
	var obj = e.target || e.srcElement;
	return obj;
}

// ВЫДЕЛЕНИЕ ЧЕКБОКСОВ "ГЛАВНЫМ" ЧЕКБОКСОМ В ТАБЛИЦЕ
function setCbTable(obj, andclick) // checkbox, кликать по измененным чекбоксам
{
	var i = $(obj).parents('th, td').first().index(); // индекс td(th) в которой checkbox
	$(obj).parents('table:first').find('tr:gt(0)').find('td:eq('+i+') input[type="checkbox"]'+(obj.checked ? ':not(:checked)' : ':checked')).each(function(index, element) {
		$(this).attr('checked', obj.checked);
		if(andclick)
			$(this).click().attr('checked', obj.checked);
	});
}
// СОБИРАЕМ ОТМЕЧЕННЫЕ ЧЕКБОКСЫ
function getCbQs(name) // 'id[]'
{
	var qs = '';
	$('input[type="checkbox"][name="'+name+'"]:checked').each(function(index, element) {
		qs += '&'+name+'='+this.value;
	});
	return qs;
}

// ПОЛУЧЕНИЕ ПОТОМКОВ (childNodes) ОПРЕДЕЛННОГО ЭЛЕМЕНТА, БЕЗ МУСОРА
function childNodes(obj)
{
	var i, j, childNodes, _childNodes = new Array();
	childNodes = obj.childNodes;
	j = 0;
	for(i in childNodes)
		if(childNodes[i].nodeType == 1)
			_childNodes[j++] = childNodes[i];
	return _childNodes;
}

// САБМИТИМ ФОРМУ ЛЮБЫМ ЭЛЕМЕНТОМ
function frmSubmit(e) // event
{
	var obj = getObjEvent(e);
	do {
		obj = obj.parentNode;
	}
	while(obj.tagName != "FORM")
	obj.submit();
}

// ПЕРЕДАЕМ URL ВО ФРЕЙМ
function toajax2(url)
{
	//frames["ajax"].document.location.href = url;
//	showLoad(true);
	top.$("#ajax").attr('src',url);
}


// GET/POST AJAX
function toAjax(url_obj) // ссылка или объект формы / на выходе возвращаем javascript код
{
	if(is_object(url_obj)) // постим/гетим форму  <form onSubmit="return toAjax(this)"
	{
		$.ajax({
			type: url_obj.method=='' ? 'GET' : url_obj.method,
			url: url_obj.action,
			async: true,
			data: $(url_obj).serialize(),
			success: function(data) {
				data = str_replace(['<script>','</script>'], '', data);
				try { eval(data); } catch(e){ alert(data); }
			}
		});		
		return false;
	}
	else // гетим ссылку
	{
		$.get(url_obj, '', function(data) {
			data = str_replace(['<script>','</script>'], '', data);
			try { eval(data); } catch(e){ alert(data); }
		});
	}
}



// ============ ============  АНАЛОГИ PHP  ============ ============

function number_format(number, decimals, dec_point, thousands_sep) 
{
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}


function isset()	// пример: if(isset(window.per)) {} где per - глобальная переменная
{
	var a=arguments; var l=a.length; var i=0;
	if (l==0)
		throw new Error('Empty isset'); 
	
	while (i!=l) 
		if (typeof(a[i])=='undefined' || a[i]===null)
			return false; 
		else
			i++; 
	return true;
}


function rand(min, max)
{
	var argc = arguments.length;
	if(argc == 0) 
	{
		min = 0;
		max = 2147483647;
	} 
	else if(argc == 1) 
		throw new Error('Warning: rand() expects exactly 2 parameters, 1 given');

	return Math.floor(Math.random() * (max - min + 1)) + min;
}


function str_replace(search, replace, subject) 
{
	var f = search, r = replace, s = subject;
	var ra = r instanceof Array, sa = s instanceof Array, f = [].concat(f), r = [].concat(r), i = (s = [].concat(s)).length;
	while (j = 0, i--) {
		if (s[i]) {
			while (s[i] = (s[i]+'').split(f[j]).join(ra ? r[j] || "" : r[0]), ++j in f) {};
		}
	}
	return sa ? s : s[0];
}


function is_object(mixed_var) 
{
	if(mixed_var instanceof Array)
		return false;
	return (mixed_var !== null) && (typeof(mixed_var) == 'object');
}


function explode(delimiter, string, limit) 
{
	var emptyArray = { 0: '' };
	// third argument is not required
	if (arguments.length < 2 || typeof arguments[0] == 'undefined' || typeof arguments[1] == 'undefined')
		return null;
	if (delimiter === '' || delimiter === false || delimiter === null)
		return false;
	if (typeof delimiter == 'function' || typeof delimiter == 'object' || typeof string == 'function' || typeof string == 'object')
		return emptyArray;
	if (delimiter === true)
		delimiter = '1';
	if (!limit)
		return string.toString().split(delimiter.toString());
	else 
	{    // support for limit argument
		var splitted = string.toString().split(delimiter.toString());
		var partA = splitted.splice(0, limit - 1);
		var partB = splitted.join(delimiter.toString());
		partA.push(partB);
		return partA;
	}
}


function implode(glue, pieces) 
{
	var i = '', retVal = '', tGlue = '';
	if(arguments.length === 1) 
	{
		pieces = glue;
		glue = '';
	}
	if(typeof(pieces) === 'object') 
	{
		if(pieces instanceof Array)
			return pieces.join(glue);
		for(i in pieces) 
		{
			retVal += tGlue + pieces[i];
			tGlue = glue;
		}
		return retVal;
	} 
	else
		return pieces;
}


function strpos(haystack, needle, offset) 
{
	var i = (haystack + '').indexOf(needle, (offset || 0));
	return i === -1 ? false : i;
}


function basename(path, suffix) 
{
	var b = path.replace(/^.*[\/\\]/g, '');
	if (typeof(suffix) == 'string' && b.substr(b.length - suffix.length) == suffix) 
		b = b.substr(0, b.length - suffix.length);
	return b;
}


function nl2br(str, is_xhtml) 
{
	var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
	return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}

function isInt(x) {
    var y = parseInt(x, 10);
    return !isNaN(y) && x == y && x.toString() == y.toString();
}


// ============ ============  ADVANCED  ============ ============

// УВЕЛИЧЕНИЕ ИЗОБРАЖЕНИЯ. 
// Пример: <img src="/uploads/goods/200x-/<?=$row['id']?>.jpg" zoom="100x50;200x100" width="100" style="position:absolute;">
//			  $(window).load(function(){ $('img[zoom]').imgZoom(); });
$.fn.imgZoom = function(){
	return this.each(function(){
		var zoom = explode(';',$(this).attr('zoom'));
		var wh1 = explode('x', zoom[0]);
		var wh2 = explode('x', zoom[1]);
		$(this).css({left:this.offsetLeft, top:this.offsetTop}).attr({left:this.offsetLeft, top:this.offsetTop}).hover(
			function(){
				var left = Math.round((wh2[0]-wh1[0])/2);
				var top = Math.round((wh2[1]-wh1[1])/2);
				$(this).stop().animate({width:wh2[0]+'px', height:wh2[1]+'px', left:(this.offsetLeft-left)+'px', top:(this.offsetTop-top)+'px'}, 200);
			},
			function(){
				$(this).stop().animate({width:wh1[0]+'px', height:wh1[1]+'px', left:$(this).attr('left')+'px', top:$(this).attr('top')+'px'}, 200);
			}
		);
	});
}

$.fn.placeInput = function(){
	return this.each(function(){
       place=$(this).data('place');
       label='<label class="placeinput"></label>';
       
       zv='<div class="place_holder">'+place+'<span>*</span></div>';
       $(this).wrap(label);
       $(zv).insertAfter($(this));
  });   
}



// РЕЙТИНГ ЗВЕЗДОЧКАМИ. Пример: <div class="starrating" onClick="alert($(this).attr('value'))">5.5</div>  // value="5.5"
// необходимы стили:
// 	div.starrating { background-image:url(/inc/advanced/star0.gif); width:170px; height:18px; visibility:hidden; }
// 	div.starrating div { background-image:url(/inc/advanced/star1.gif); height:18px; }
// 	div.starrating div.clear { background:none; width:100%; }
$.fn.starRating = function(){
	return this.each(function(){
		var $this = $(this);
		if($this.hasClass('maked'))
			return;
		$this.addClass('maked')
		var img = new Image;
		img.src = str_replace([['url('],[')'],['"']], '', $this.css('background-image'));
		img.onload = function(){ 
			var starW = img.width;
			$this.html('<div style="width:' + starW * $this.html() + 'px;"><div></div></div>').css('visibility', 'visible');
			if($this.attr('onclick')=='' || $this.attr('onclick')==null) return;
			$this.css('cursor','pointer')
			.mouseover(function(){
				$this.children(':first').addClass('clear').children(':first').removeClass('clear');
			}).mousemove(function(e) { 
				$this.find('div:eq(1)').width( Math.ceil( (e.pageX - $this.offset().left) / starW ) * starW );
			}).mouseleave(function(){
				$this.children(':first').removeClass('clear').children(':first').addClass('clear');
			}).mousedown(function(e) {
				var value = Math.ceil( (e.pageX - $this.offset().left) / starW );
				$this.attr('value', value).children(':first').width( value * starW );
			});
		}
	});
}

// ПОЛЕ СО СТРЕЛКАМИ ВВЕРХ/ВНИЗ
/*	<style>
		.numinput { height:17px; border:1px solid #B4B4B4; border-right:none; text-align:center; }
		.numarr { width:20px; display:inline-block; vertical-align:middle; cursor:pointer; }
		.numarr div:first-child { height:11px; background:url(/inc/advanced/img/numarr.png) 0 0 no-repeat; }
		.numarr div:first-child:hover { background-position:-20px 0; }
		.numarr div:last-child { height:10px; background:url(/inc/advanced/img/numarr.png) 0 -11px no-repeat; }
		.numarr div:last-child:hover { background-position:-20px -11px; }
	</style>	*/
$.fn.numArr = function(){
	return this.each(function() {
	    $(this).addClass('active_num');
		var numarr = '<span style="white-space:nowrap;">';
		numarr +=		$('<div>').append($(this).clone()).remove().html();
		numarr +=		'<div onClick="if(isNaN($(this).prev().val()) || $(this).prev().val()<'+$('#h_'+$(this).attr('id')).val()+') $(this).prev().val('+$('#h_'+$(this).attr('id')).val()+'); $(this).prev().change()" class="numarr">';
		numarr += 			'<div class="fir" onClick="$(this).parent().prev().val($(this).parent().prev().val()*1+'+$('#h_'+$(this).attr('id')).val()+')"></div>';
		numarr +=			'<div class="sec" onClick="$(this).parent().prev().val($(this).parent().prev().val()*1-'+$('#h_'+$(this).attr('id')).val()+')"></div>';
		numarr +=	 	'</div>';
		numarr +=    '</span>';
		$(this).replaceWith(numarr);
	});
}

  function getMax(arr) {
    var arrLen = arr.length,
        maxEl = arr[0];
    for (var i = 0; i < arrLen; i++) {
      if (maxEl < arr[i]) {
        maxEl = arr[i];
      }
    }
    return maxEl;
  }
// ПОЛЕ СО СТРЕЛКАМИ ВЛЕВО/ВПРАВО
/*	<style>
		.numinput { width:35px; height:34px; background:url(/img/bg.png) -371px -104px no-repeat; padding:0; border:none; font:bold 21px Arial; color:#1f262d; text-align:center; }
		.numarr span:first-child { width:30px; height:34px; background:url(/img/bg.png) -341px -104px no-repeat; display:inline-block; vertical-align:middle; cursor:pointer; }
		.numarr span:last-child { width:30px; height:34px; background:url(/img/bg.png) -406px -104px no-repeat; display:inline-block; vertical-align:middle; cursor:pointer; }
	</style>	*/
$.fn.numArr2 = function(){
	return this.each(function() {
		var numarr = '<span style="white-space:nowrap;" class="numarr" onClick="var input=$(this).find(\'input:first\'); if(isNaN(input.val()) || input.val()<1) input.val(1); input.change();">';
		numarr +=       '<span onClick="$(this).next().val($(this).next().val()*1-1);"></span>';
		numarr +=       $('<div>').append($(this).clone()).remove().html();
		numarr +=       '<span onClick="$(this).prev().val($(this).prev().val()*1+1);"></span>';
		numarr +=    '</span>';
		$(this).replaceWith(numarr);
	});
}