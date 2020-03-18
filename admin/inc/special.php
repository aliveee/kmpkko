<?
function show_uzel($uzel='')
{
  global $prx, $number_uzel;
  if (!@$uzel['id']) 
   $uzel['id']=0;
   ?>
    <input type="hidden" value="<?=$uzel['id']?>" name="uzels[<?=$number_uzel?>]" /> 
    <div class="uzel" id="uzel<?=$uzel['id']?>">
       <div class="uzel_add">
        <div class="name-uzel"><input type="text" value="<?=$uzel['name']?>" id="name_uzel<?=$number_uzel?>" placeholder="Название узла" name="name_uzel[<?=$number_uzel?>]" /></div>                          
        <div class="photo-uzel"><?=fileUpload("/uploads/uzel/{$uzel['id']}.jpg", 'name="file_uzel_'.$number_uzel.'" style="width:80%"')?></div>
      </div>  
        <h3>Детали</h3>
        <div class="block-details<?=$number_uzel?>">
          <?
           if (@$uzel['id'])
           { 
            $det=mysql_query("select * from {$prx}uzel_details where id_uzel='{$uzel['id']}'");
            if (mysql_num_rows($det)==0)
            {
              ?>   
                <div class="info-det">      
                 <div class="name-detail"><input type="text" value="" title="" placeholder="Название детали или артикул"  name="name_detail[<?=$number_uzel?>][]" /></div>                          
                 <div class="number-detail"><input type="text" value="" placeholder="Номер на схеме" name="number_detail[<?=$number_uzel?>][]" /></div>
                </div> 
              <?
            }
           else
           {
            while ($det_inf=mysql_fetch_array($det)){
          ?>   
            <div class="info-det">      
             <div class="name-detail"><input type="text" title="<?=$det_inf['full_info']?>" value="<?=$det_inf['full_info']?>" placeholder="Название детали или артикул"  name="name_detail[<?=$number_uzel?>][]" /></div>                          
             <div class="number-detail"><input type="text" value="<?=$det_inf['number']?>" placeholder="Номер на схеме" name="number_detail[<?=$number_uzel?>][]" /></div>
            </div> 
           <?}
            }
           
           }
           else
           {
              ?>   
                <div class="info-det">      
                 <div class="name-detail"><input type="text" title="<?=$det_inf['full_info']?>" value="" placeholder="Название детали или артикул"  name="name_detail[<?=$number_uzel?>][]" /></div>                          
                 <div class="number-detail"><input type="text" value="" placeholder="Номер на схеме" name="number_detail[<?=$number_uzel?>][]" /></div>
                </div> 
              <?            

           }
           
           ?> 
        </div>                          
        <div class="add_detail" data-uzel="<?=$number_uzel?>" style="float:left;"><a href="javascript:void(0)">+ Добавить деталь</a></div>
		 <?	if($uzel['id']) { ?>
			        <div style="float:right;"><a href="javascript:void(0)" style="color:red;" onClick="if(sure()){ $('#name_uzel<?=$number_uzel?>').val('').parent().parent().parent().slideUp(); }">- Удалить узел</a></div>
			<?	}	?>
		  
        
        <?
          if (@$uzel['id']) $number_uzel++;
        ?>
        
    </div>
  <?  
}


// РАСШИРЕНИЕ ФАЙЛА
function getFileExtension($filename) 
{
	return end(explode(".",$filename));
}

function getFileFormat($mask,$array=false)
{
	$images = glob($mask, GLOB_NOSORT);
	if($images)
	{
		if($array)
		{
			$res = array();
			foreach($images as $val)
				if(!is_dir($val))
					$res[] = $val;
					
			return $res;
		}
		else
		{
			//$str = substr(strrchr($images[0],'.'), 1);
			$str = end(explode(".",$images[0]));
			return $str;
		}
	}
	else
		return false;
}

function get_pic_name($id)
{
	$tbl='gallery';
	
	if (!is_dir($_SERVER['DOCUMENT_ROOT']."/uploads/{$tbl}/{$id}"))
    {
      mkdir($_SERVER['DOCUMENT_ROOT']."/uploads/{$tbl}/{$id}",'0755');
      chmod($_SERVER['DOCUMENT_ROOT']."/uploads/{$tbl}/{$id}",0755);
    }
    
//    if(!file_exists($_SERVER['DOCUMENT_ROOT']."/uploads/{$tbl}/{$id}.jpg"))
//		return "{$id}.jpg"; 
	
	$num = array();
	$images = getFileFormat($_SERVER['DOCUMENT_ROOT']."/uploads/{$tbl}/{$id}/*",true);

	if(is_array($images))
	{
		foreach($images as $fname)
		{
			// $fname имеет формат
			// C:/www/sites/s-dom.local/uploads/goods/049489381774_2.jpg
			// нужна лишь 049489381774_2.jpg
			$fname = end(explode('/',$fname));
			preg_match("/^([0-9]+).jpg$/isU",$fname,$mas);
			if($mas[1])
				$num[] = $mas[1];
		}
	}
	
	$new_fname = '';
	
    $size = sizeof($num);
	if($size)
	{
		asort($num);
		for($i=0; $i<$size; $i++)
		{
			if($num[$i]!=$i+1)
			{
				$new_fname = "{$id}/".($i+1).".jpg";
				break;
			}
		}
		
		if(!$new_fname)
		{		
			$n = end($num)+1;
			$new_fname = "{$id}/{$n}.jpg";
		}
	}
	else
		$new_fname = "{$id}/1.jpg";
	
	return $new_fname;
}

// АРХИВИРОВАНИЕ В *.gz
function gzCompressFile($source, $file='') // исходный файл, файл *.gz
{
	if(!$file)
		$file = $source.'.gz';
		
	if($fp_out=gzopen($file,'wb9'))
	{
		if($fp_in=fopen($source,'rb'))
		{
			while(!feof($fp_in))
				gzputs($fp_out,fread($fp_in,1024*512));
			fclose($fp_in);
		}
		gzclose($fp_out);
	}
	return file_exists($file);
}
// УСТАНАВЛИВАЕМ ЗНАЧЕНИЕ ПЕРЕМЕННОЙ СОРТИРОВКИ
function setSort($defaultSort) // поле сортировки по-умолчанию / $sort = setSort('sort,id');
{	
	global $tbl;
	$_SESSION["{$tbl}_sort_default"] = $defaultSort;
	if(!isset($_SESSION["{$tbl}_sort"]))	$_SESSION["{$tbl}_sort"] = $defaultSort;
	if(@$_GET['sort'])	$_SESSION["{$tbl}_sort"] = $_GET['sort'];
	return $_SESSION["{$tbl}_sort"];
}
// ВОЗВРАЩАЕМ СТРОКУ ЗАПРОСА СОРТИРОВКИ
function getSort($sort) // поле сортировки / <th sort="<?=getSort('name')? >">Название</th>
{	
	global $tbl;
	$sortbg = str_replace(' DESC', '', $_SESSION["{$tbl}_sort"]) == $sort
		? (strpos($_SESSION["{$tbl}_sort"], 'DESC') ? '20px -1047px' : '20px -999px')
		: '20px -951px';
	$sort = "{$sort} DESC" == $_SESSION["{$tbl}_sort"] && "{$sort} DESC" != $_SESSION["{$tbl}_sort_default"]
		? $_SESSION["{$tbl}_sort_default"]
		: ($sort == $_SESSION["{$tbl}_sort"] ? "{$sort} DESC" : $sort);
	return '?sort='.$sort.getQS('sort').'" sortbg="'.$sortbg;
}	

// ПЕРЕСТРАИВАЕМ СОРТИРОВКУ
function reSort($sql, $sort_name='sort') // $sql = "SELECT id FROM {$prx}{$tbl} WHERE id_parent={$id_parent} ORDER BY sort,id"; имя поля сортировки
{
	// находим имя таблицы
	$arr = preg_replace('#\s+#', ' ', $sql); // убираем повторяющиеся пробелы		
	$arr = explode(' ',$arr);
	$tbl = array_search('FROM',$arr);
	$tbl = $arr[++$tbl];
	
	$res = sql($sql);
	$sort = 0;
	while($row = mysql_fetch_row($res))
	{
		$sort += 100;
		sql("UPDATE {$tbl} SET {$sort_name}='{$sort}' WHERE id='{$row[0]}'"); // на update($tbl, "{$sort_name}='{$sort}'", $row[0]); не переделывать!
	}
}

// ПЕРЕМЕЩАЕМ ЗАПИСЬ (sort)
function moveSort($tbl, $id, $name_parent='', $step=0, $sort_name='sort') // up/down, имя таблицы, id записи кот двигаем, имя поля группы внутри кот. будет перемещение (если групп несколько - перечислить через запятую), имя поля сортировки 
{
	if(isset($_GET['step']))
		$step = (int)$_GET['step'];
	if(!isset($_GET['noreload']))
		$_SESSION['tr_active'] = $id; // для подсветки перемещаемой записи
	global $prx;
	// where для групп(ы) (если есть)
	$where_parent = '1';
	if($name_parent)
		foreach(explode(',', $name_parent) as $fill)
			$where_parent .= ' AND '.trim($fill)."='".getField("SELECT {$fill} FROM {$prx}{$tbl} WHERE id='{$id}'")."'";
	// перестраиваем сортировку
	reSort("SELECT id FROM {$prx}{$tbl} WHERE {$where_parent} ORDER BY {$sort_name},id", $sort_name);
	// меняем значение сортировки
	$sort = (int)getField("SELECT {$sort_name} FROM {$prx}{$tbl} WHERE id='{$id}'") + $step*100 + ($step>0 ? 50 : -50);
	update($tbl, "{$sort_name} = {$sort}", $id);
}

// ССЫЛКИ ИЗ АДМИНСКОЙ МЕНЮШКИ
function getAdminMenuFileName()
{
	global $left_menu;
	$lnkArr = array();
	// левое меню
	preg_match_all('~<a.*?href="([^"]+)".*?>(.*?)</a>~s', $left_menu, $menu); // левое меню
	foreach($menu[0] as $key=>$lnk)
	{
		$file = basename(parse_url(trim($menu[1][$key]), PHP_URL_PATH));
		$name = trim($menu[2][$key]);
		$lnkArr[$file] = $name;
		// верхнее меню
		$topMenu = explode('top_menu="', $lnk);
		$topMenu = explode('"', $topMenu[1]);
		preg_match_all('~<a.*?href="([^"]+)".*?>(.*?)</a>~s', topMenu($topMenu[0]), $menu1);
		foreach($menu1[0] as $key=>$lnk)
		{
			$file1 = basename(parse_url(trim($menu1[1][$key]), PHP_URL_PATH));
			$name1 = trim($menu1[2][$key]);
			$lnkArr[$file1] = $name == $name1 ? $name : $name.' &raquo; '.$name1;
		}
	}
	return $lnkArr;
}

// УПРАВЛЯЮЩИЕ ССЫЛКИ
function lnkAction($lnk='UpDown,Red,Del', $addition='') // ссылки, дополнительные переменные в строку запроса
{	
	global $id, $sort;
	ob_start();
?>	<span style="white-space:nowrap;">
	<?	$arr = explode(',',$lnk);
		foreach($arr as $lnk)
			switch(strto('lower',(trim($lnk))))
			{
				case 'add': 
					?> <a href="?show=red<?=$addition?>" class="la laAdd">добавить</a> &nbsp; &nbsp; <?
					break;	
				case 'dels': 
					?> <a href="javascript:toAjax('?action=del'+getCbQs('ids[]'))" onClick="return sure();" title="Удалить отмеченные" class="la laDel">удалить</a> &nbsp; &nbsp; <?
					break;	
				case 'copy': 
					?> <a href="javascript:toAjax('?action=copy'+getCbQs('ids[]'))" onClick="return sure();" class="la" style="background-position:0 -1104px;" title="Дублировать отмеченные">дублировать</a> &nbsp; &nbsp; <?
					break;
				case 'link': 
					?> <a href="javascript:toAjax('?action=link')" onClick="return sure();" class="la" style="background-position:0 -384px;" title="Обновить названия ссылок в соответствии с названиями">обновить ссылки</a> &nbsp; &nbsp; <?
					break;
				case 'updown':
				case 'move':
                    if($sort && strpos($sort, 'sort') === false) break;
					if(strto('lower',(trim($lnk))) == 'updown') {
						?> <a href="javascript://" class="la16 laUp" title="вверх" onDblClick="event.cancelBubble=true; clearTimeout(movetimeout_id); if(step=prompt('Введите количество шагов вверх', '10')) { toAjax('?action=move&id=<?=$id?>&step=-'+step); }" onClick="try{clearTimeout(movetimeout_id);}catch(e){} movetimeout_id=setTimeout(function() { toAjax('?id=<?=$id?>&action=move&step=-1') }, 300);"></a><?
						?><a href="javascript://" class="la16 laDown" title="вниз" onDblClick="event.cancelBubble=true; clearTimeout(movetimeout_id); if(step=prompt('Введите количество шагов вниз', '10')) { toAjax('?action=move&id=<?=$id?>&step='+step); }" onClick="try{clearTimeout(movetimeout_id);}catch(e){} movetimeout_id=setTimeout(function() { toAjax('?id=<?=$id?>&action=move&step=1') }, 300);"></a> <?
					} else {
					?> <a href="javascript://" class="la16 laMove" onMouseDown="wasrowupdown=true" id="updown<?=$id?>" title="вверх-вниз" onDblClick="event.cancelBubble=true; if(step=prompt('Введите количество шагов (минус - вверх)', '10')) { toAjax('?action=move&id=<?=$id?><?=$addition?>&step='+step); }" <?=count($arr) > 1 ? 'style="margin-left:22px;"' : ''?>></a> <?
					}
					break;
				case 'red': 
					?> <a href="?show=red&id=<?=$id?><?=$addition?>" class="la16 laRed" title="редактировать"></a> <?
					break;
				case 'del': 
					?> <a href="javascript:toAjax('?action=del&id=<?=$id?>')" onClick="return sure();" class="la16 laDel" title="удалить"></a> <?
					break;	
			}	?>
	</span>
<?
	return ob_get_clean();
}

// КОЛИЧЕСТВО ПОЗИЦИЙ НА СТРАНИЦЕ
function showK($k)
{
	ob_start();
?>
	<span style="white-space:nowrap;">
		выводить по <?=dll(dllArr(array(20,50,100,200,1000)), 'onChange="toAjax(\'?action=set_k&k=\'+this.value)" style="text-align:right;"', $k)?> &nbsp; &nbsp;
	</span>
<?
	return ob_get_clean();	
}

// УПРАВЛЯЮЩИЕ КНОПКИ
function btnAction($btn='Save,Cancel', $value='')
{	
	ob_start();
	$arr = explode(',',$btn);
	foreach($arr as $btn)
		switch(strtolower(trim($btn)))
		{
			case 'save':   ?>&nbsp;<input value="<?=$value ? $value : 'Сохранить'?>" type="submit" style="background-position:0 0; <?=(strlen($value)>9 ? 'width:auto !important;' : '')?>" class="btn" onClick="showLoad(true);">&nbsp;<? break;	
			case 'search': ?>&nbsp;<input value="<?=$value ? $value : 'Искать'?>" type="submit" style="background-position:0 -64px; <?=(strlen($value)>9 ? 'width:auto !important;' : '')?>" class="btn" onClick="showLoad(true);">&nbsp;<? break;	
			case 'cancel': ?>&nbsp;<input value="Отменить" type="button" onClick="showLoad(true);history.back();"  style="background-position:0 -32px;" class="btn">&nbsp;<? break;
			case 'apply':  ?>&nbsp;<input value="Применить" type="submit" onClick="showLoad(true);" name="apply" style="background-position:0 0;" class="btn">&nbsp;<? break;
			case 'reset': 	?>&nbsp;<input value="Отменить" type="reset" class="btn"  style="background-position:0 -32px;">&nbsp;<? break;
			case 'update': ?>&nbsp;<input value="Обновить" type="button" onClick="showLoad(true);location.reload(true);"  style="background-position:0 -96px;" class="btn">&nbsp;<? break;
		}
	return ob_get_clean();
}

// ВЫВОДИМ ПОДСКАЗКУ
function help($str)
{
	$rand = mt_rand();
	ob_start();
?>
	<a href="javascript://" title='<?=$str?>' onClick="alert(this.title)" id="help<?=$rand?>" class="la16" style="background-position:0 -608px; margin:0;"></a>
	<script>$('#help<?=$rand?>').parent().width("1px");</script>
<?
	return ob_get_clean();
}

// ПОДКРАШИВАЕМ КОНТЕНТ ПОСЛЕ ЗАГРУЗКИ СТРАНИЦЫ
function paintContent()
{	
	global $top_menu, $tbl, $show;
	ob_start();
?>
	<script>
		$(function(){
			// подсветка ссылок
			try { $('a[href="<?=basename($_SERVER['PHP_SELF'])?>"], a[top_menu="<?=$top_menu?>"], a[tbl="<?=$tbl?>"]').addClass('active'); } catch(e) {}  
			// подсветка строки
			try { $('#tr<?=@$_SESSION['tr_active'] ? $_SESSION['tr_active'] : @$_GET['id']?>').addClass('active'); } catch(e) {}  
			// переход к отредактированной строке
			try { 
				if(<?=(int)@$_GET['id']?> && <?=(int)@$_GET['id']?>!=<?=(int)@$_SESSION['gotr']?>) 
					$('html,body').animate({scrollTop: $('table.content tr.active:first').offset().top-50},500); 
			} catch(e) {}
		});
	</script>
<?
	$_SESSION['gotr'] = $show=='red' ? 0 : (int)@$_GET['id'];
	unset($_SESSION['tr_active']);
	return ob_get_clean();
}

// УБИРАЕМ ПУНКТЫ МЕНЮ, НА КОТОРЫЕ НЕТ ПРАВ
function privMenu()
{
	if($_SESSION['priv'] == 'admin') return;
	ob_start();
?>
	<script>
		$(function(){
			$('.lnk_left a, .lnk_top a').each(function(index, element) {
				var lnk = new Array(2);
				lnk = explode('?', $(this).attr('href'));
				if(!strpos(",,<?=$_SESSION['priv']['priv']?>,", ","+lnk[0]+","))
					$(this).addClass('cg').attr('href', "javascript:alert('Доступ запрещен')");
			});
		});
	</script>
<?
	return ob_get_clean();
}

// ФУНКЦИОНАЛ ЗАГРУЗКИ ФАЙЛА
function fileUpload($path='/img/file1.jpg', $properties='name="file"')  // $path - абсолютный путь
{	
	// находим name в $properties
	preg_match("#name\s*=\s*['\"]+(.*)['\"]+#iU", $properties, $name);
	$name = $name[1];
	ob_start();
?>
	<input type="file" <?=$properties?>>
	<input name="del_<?=$name?>" id="del_<?=$name?>" type="hidden" value="0">
<? if(is_file($_SERVER['DOCUMENT_ROOT'].$path)) { ?>
		<a href="<?=$path?>?rand=<?=mt_rand()?>" onClick="return openWindow(this,800,600)" class="la16" style="background:url(<?=getIcoType($path)?>)" title="Открыть"></a>
		<a href="javascript://" onClick="$(this).fadeOut().prev().fadeOut().prev().val('<?=basename($path)?>')" class="la16 laDel" title="Удалить"></a>
<? }
	return ob_get_clean();
}	
// ВОЗВРАЩАЕТ КАРТИНКУ СООТВЕТСТВУЮЩУЮ ТИПУ ФАЙЛА
function getIcoType($path) // путь к файлу
{
	if(!function_exists('scandir'))
		return 'img/ico/default.icon.gif';
	
	$ext = preg_replace('/.*?\./', '', $path); // расширение файла
	$need_ico = strto('lower',$ext).'.gif';
	$arr_ico = @scandir('img/ico'); // получаем массив файлов директории с картинками "иконок"

	return in_array($need_ico, (array)$arr_ico)
		? 'img/ico/'.$need_ico
		: 'img/ico/default.icon.gif';
}
// ДОБАВЛЕНИЕ / УДАЛЕНИЕ ФАЙЛА
function upfile($file, $post, $del=false, $createFileName=false, $width=0, $height=0) // относительный путь к файлу, переменная $_FILES[''], true - удалить файл, генериться имя файла автоматом, ширина и высота (для картинок)
{
	if($del===true) {
        //echo "1".$file;exit;
        @unlink($file);
        //echo $file;exit;
    }else if($del){
        //echo "2".$del;exit;
        @unlink($del);
    }

	if($post['name'])
	{
		if($createFileName)
		{
			if(is_file($file)) @unlink($file);
			$fn = pathinfo($post['name']);
			$fileName = makeUrl($fn['filename'], true).'.'.$fn['extension'];
			$file = (is_dir($file) ? $file : dirname($file).'/').$fileName;
		}
		else
			$fileName = basename($file);


        $upload_dir=pathinfo($file,PATHINFO_DIRNAME );
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

		move_uploaded_file($post['tmp_name'], $file);
		@chmod($file, 0644);
		
		if($width || $height)
			imgResize($file, $width, $height, $file);
		return $fileName;
	}
	return $del || is_dir($file) ? '' : basename($file);
}	

// УДАЛЕНИЕ ДИРЕКТОРИИ
function undir($directory)
{
	$dir = opendir($directory);
	while($file = readdir($dir))
	{
		if(is_file($directory.'/'.$file))
			unlink($directory.'/'.$file);
		elseif(is_dir($directory.'/'.$file) && ($file != '.') && ($file != '..'))
			undir($directory.'/'.$file);  
	}
	closedir($dir);
	@rmdir($directory);
}

// ОБРАБОТКА ТЕКСТА ДЛЯ ГЛАМУРНОГО УВЕЛИЧЕНИЯ КАРТИНОК ДОБАВЛЕННЫХ В fck
function zoomImgInText($text, $gallery='') // html-текст, id-галлереи (если надо)
{
	$text = stripslashes($text);
	require_once('inc/simple_html_dom.php');
	$html = str_get_html($text);
	foreach($html->find('img') as $obj)
	{
		$continue = false;  // проверка что или на картинке нет ссылки или ссылка ведет не на картинку
		$a = $obj_rep = $obj;
		while(is_object($a = $a->parent()))
			if($a->tag == 'a')
			{
				if(basename($a->href) <> basename($obj->src))
					$continue = true;
				else
					$obj_rep = $a;
				break;
			}
		if($continue)
			continue;
		$obj_rep = (string)$obj_rep;
		$arr = explode2(';',':', $obj->style);
		$w = (int)$obj->width ? (int)$obj->width : (int)$arr['width'];
		$h = (int)$obj->height ? (int)$obj->height : (int)$arr['height'];
		$src = $obj->src;
		$size = @getimagesize($_SERVER['DOCUMENT_ROOT'].$src);
		if(($w && $w<$size[0]) || ($h && $h<$size[1]))
		{
			$title = $obj->alt ? $obj->alt : $obj->title;
			$obj->src = dirname($src).'/'.$w.'x'.$h.'/'.basename($src);
			$text = str_replace($obj_rep, "<a href='{$src}' title='{$title}' class='fb-img' ".($gallery ? "rel='{$gallery}'" : '').">{$obj}</a>", $text);
		}
	}
	$html->__destruct();		
	return $text;
}

// УНИВЕРСАЛЬНЫЙ ЗАПРОСТО НА ОБНОВЛЕНИЕ
function uniUpdate($id)
{
	global $prx, $tbl;
    $send_email='';
    	
	foreach($_POST as $key=>$val)
	{
		global $$key;
       
        if ($key=='use_date')
        {
          $date=date('d-m-Y H:i:s');
        }
        elseif ($key=='use_mail')
        {
          $send_email=$val;
        }  
        else  
  		  $$key = clean($val);
	}

	if(isset($link) && !$link) $link = makeUrl($name); // Транслит для ссылки
	
	$set = array();
	$res = sql("SHOW FIELDS FROM `{$prx}{$tbl}`");
	while($row = mysql_fetch_assoc($res))
	{
		if(isset($_POST[$row['Field']]) || strpos($row['Type'], 'tinyint')!==false)
		{
            //echo $row['Field']."=".$_POST[$row['Field']]."; ";
			$field = $row['Field']; // название поля
			if($field == 'id' && !$$field) continue; // пропускаем id'шку
			if(strpos($row['Type'], 'double')!==false) $$field = str_replace(',', '.', $$field); // число
			if(strpos($row['Type'], 'date')!==false || strpos($row['Type'], 'timestamp')!==false) $$field = formatDateTime($$field); // дата
			if(is_array($$field)) $$field = cleanArr($$field); // массив
			$set[] = $row['Null'] == 'YES' && !$$field
				? "`{$field}`=NULL"
				: "`{$field}`='{$$field}'";
		}
	}
    //print_r($set );

	$set = implode(',', $set);
//print_r($set);exit;
	$id = update($tbl, $set, $id);
    
    if ($send_email)
    {
          //---отправка сообщения ----
          $tema="Получен ответ на Ваш вопрос на сайте {$_SERVER['SERVER_NAME']}";
          $text="Здравствуйте<br>На ваш вопрос на сайте {$_SERVER['SERVER_NAME']} дан ответ, ознакомиться с ним вы можете по ссылке: <a href='http://{$_SERVER['SERVER_NAME']}{$_POST['good_link']}#otvet{$id}'>http://{$_SERVER['SERVER_NAME']}{$_POST['good_link']}</a>";   
          mailTo($send_email,$tema,$text,set('email_letter'));        
    }
    
	return $id;
}
// ПРОВЕРКА УНИКАЛЬНОСТИ ССЫЛКИ
function linkTest($id, $group='') // ID записи, имя поля относительно которого проверяем уникальность
{
	global $prx, $tbl;
	$row = getRow("SELECT * FROM {$prx}{$tbl} WHERE id='{$id}'");
	if(getField("SELECT COUNT(*) AS c FROM {$prx}{$tbl} WHERE id<>'{$id}' AND link='".clean($row['link'])."'".($group ? " AND {$group}='".clean($row[$group])."'" : '')))
    {
	  if (substr($row['link'],-4)=='.htm')
      {
       $c_link=substr($row['link'],0,str_len($row['link'])-4);
       update($tbl, "link=CONCAT(".$c_link.",'_',id,'.htm')", $id);
      }
      else 
       update($tbl, "link=CONCAT(link,'_',id)", $id);
    }   
}
?>