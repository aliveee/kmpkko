<?
// ПРОДВИНУТЫЙ INPUT
function aInput($type, $properties, $value='', $spec='') // "тип" поля ввода, свойства поля, значение, переменная для конкректного типа
{
	global $aInputIncludeJS;
	$rand = mt_rand();
	ob_start();
	switch(strtolower($type))
	{
		// дата время
		case 'datetime':
			$time_prop = array(' hh:ii', ', true');
		// дата
		case 'date':
			if(!in_array('date', (array)$aInputIncludeJS)) 
			{
				$aInputIncludeJS[] = 'date';	?>
				<link type="text/css" rel="stylesheet" href="/admin/inc/advanced/inc/dhtmlgoodies_calendar.css" media="screen"></link>
				<script type="text/javascript" src="/admin/inc/advanced/inc/dhtmlgoodies_calendar.js"></script>
				<script> var dontDisplayCalendar; </script>
		<?	}	?>
			<input type="text" <?=$properties?> value="<?=$value?>" onSelect="dontDisplayCalendar=true; closeCalendar();" onClick="if(dontDisplayCalendar) dontDisplayCalendar=false; else displayCalendar(this,'dd.mm.yyyy<?=$time_prop[0]?>',this<?=$time_prop[1]?>)">
		<?
			break;
		
		// цвет
		case 'color':
			if(!in_array($type, (array)$aInputIncludeJS)) 
			{
				$aInputIncludeJS[] = $type;	?>
				<link rel="stylesheet" href="/admin/inc/advanced/inc/colorPicker.css" type="text/css"></link>
				<script type="text/javascript" language="javascript" src="/admin/inc/advanced/inc/colorPicker.js"></script>
		<?	}	?>
			<style> input.color<?=$rand?> { background-color:<?=$value?>; color:<?=($value && array_sum(html2rgb($value))<500 ? 'white' : 'black')?>; } </style>
			<input type="text" <?=$properties?> class="color<?=$rand?> mask_color" value="<?=$value?>" onClick="startColorPicker(this);">
		<?
			break;
	}
	return ob_get_clean();
}


// ВЫПАДАЮЩЕЕ ГОРИЗОНТАЛЬНОЕ МЕНЮ
function tinyDropdown($sql, $id_parent=0, $depth=0, $level=0, &$rows=NULL) // $sql = "SELECT * FROM {$prx}{$tbl} ORDER BY sort,id", ветка с которой начинаем стоить дерево, "глубина" дерева, текущая глубина - не задается, массив таблицы - не задается
{
	global $startTinyDropdown;
	ob_start();
	if(!$startTinyDropdown)
	{	?>
		<script src="/admin/inc/advanced/menu/TinyDropdown/script.js" type="text/javascript"></script>
		<link href="/admin/inc/advanced/menu/TinyDropdown/style.css" rel="stylesheet" type="text/css">
	<?	
		$rows = array();
		$res = sql($sql);
		while($row = mysql_fetch_assoc($res))
			$rows[$row['id_parent']][] = $row;
		$startTinyDropdown = true;
	}
	if(!$depth || $depth>$level)
	{
		if($rows[$id_parent])
		{
			echo $level ? '<ul>' : '<ul class="tdmenu" id="tdmenu">';
			$i = 0;
			foreach((array)$rows[$id_parent] as $row)
			{	
				$id = $row[0];
			?>	<li<?=$level && !($i++) ? ' class="topline"' : ''?>>
					<a<?=$level ? (tinyDropdown($sql, $id, $depth, $level+1, $rows) ? ' class="sub"' : '') : ' class="menulink"'?> href="/catalog/<?=$id?>/"><?=$row['name']?></a>
					<?=tinyDropdown($sql, $id, $depth, $level+1, $rows)?>
				</li>	<?
			}
			echo '</ul>';
		}
	}
	if(!$level) { ?>
		<script type="text/javascript">
			var tdmenu = new tdmenu.dd("tdmenu");
			tdmenu.init("tdmenu","menuhover");
		</script>
<?	}
	return ob_get_clean();
}


// PHP-КАЛЕНДАРЬ
function calendar($month='', $date='', $monthf='?month=%s', $datef='?date=%s') // год-месяц календаря, активная дата, формат строки для перехода календаря, формат строки для даты
{
	if(!$month) $month = date('Y-m');
	//Массив названий месяцев
	$mon_name = array	('Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь');
	//Массив продолжительностей месяцев
	$nod = array (31,28,31,30,31,30,31,31,30,31,30,31);
	//Определение месяца и года для календаря
	list($ac_year,$ac_month) = explode('-', $month);
	if((int)$ac_month != date('n') || $ac_year != date('Y'))
	{
		$ac_j_dom = 1;
		$ac_j_dow = date('w', mktime(0,0,0,$ac_month,1,$ac_year));
	}
	else
	{
		$ac_j_dom = date('j');
		$ac_j_dow = date('w');
	}
	//Корректировка продолжительности февраля в високосном году
	if ($ac_year%4==0) $nod[1] = 29;
	//Определение предыдущих/следующих месяцев/годов
	$ac_month_next = $ac_month+1<13 ? "{$ac_year}-".($ac_month+1) : ($ac_year+1).'-1';
	$ac_month_prev = $ac_month-1>0 ? "{$ac_year}-".($ac_month-1) : ($ac_year-1).'-12';
	$ac_year_next = ($ac_year+1)."-{$ac_month}";
	$ac_year_prev = ($ac_year-1)."-{$ac_month}";
	//Определение названия месяца
	$ac_mon = $mon_name[$ac_month-1];
	//Корректировка номера дня недели из западно-европейской в русскую
	if($ac_j_dow == 0) $ac_j_dow = 7;
	//Определение дня недели первого дня месяца
	$ac_1_dow = $ac_j_dow - ($ac_j_dom%7 - 1);
	if($ac_1_dow < 1) $ac_1_dow += 7;
	if($ac_1_dow > 7) $ac_1_dow -= 7;
	//Определение числа дней месяца
	$ac_nod = $nod[$ac_month-1];
	//Определение количества недель в месяце
	$ac_now = $ac_1_dow-1+$ac_nod<29 ? 4 : ($ac_1_dow-1+$ac_nod>35 ? 6 : 5);
	//Предотвращение вывода текущего дня для нетекущего месяца
	if($ac_month != date("n") || $ac_year != date("Y")) $ac_j_dom = -10;
	ob_start();
?>
	<style>
		table.calendar { border-collapse:collapse; background-color:white; }
		table.calendar th { color:#808080; background-color:#E8EBF1; }
		table.calendar td, table.calendar th { border: 1px solid #CDD7DF; padding: 2px 3px 2px 3px; text-align:center; }
		table.calendar td.weekend { background-color: #F4F4F4; }
		table.calendar td.today { background-color:#80DCFF; }
		table.calendar td.active { background-color: #FFFF80; }	
	</style>
	<table class="calendar">
		<tr><th colspan="7"><?=$ac_mon?> <?=$ac_year?></th></tr>
		<tr><th>Пн</th><th>Вт</th><th>Ср</th><th>Чт</th><th>Пт</th><th>Сб</th><th>Вс</th></tr>
	<?	for ($i=1; $i<=$ac_now*7; $i++)
		{
			if($i%7==1) {	?><tr><?	}	
			$ac_day = $i-$ac_1_dow+1;
			$ac_date = date('Y-m-d', strtotime("{$ac_year}-{$ac_month}-{$ac_day}"));
			$class = '';
			if($i%7==0 || $i%7==6) $class = ' weekend ';
			if($ac_date==date('Y-m-d')) $class = ' today ';
			if($ac_date==$date) $class = ' active ';
		?>	<td <?=$class ? "class='{$class}'" : ''?>>
			<?	if($i>=$ac_1_dow && $i<$ac_nod+$ac_1_dow) { ?>
					<a href="<?=sprintf($datef, $ac_date)?>"><?=$ac_day?></a>
			<?	}	?>
			</td>
		<?	if(!$i%7) {	?></tr><? }	
		}	?>
		<tr>
			<th colspan="7">
				<a href="<?=sprintf($monthf, $ac_year_prev)?>" title="Год назад">&lt;&lt;</a> &nbsp; 
				<a href="<?=sprintf($monthf, $ac_month_prev)?>" title="Месяц назад">&lt;</a> &nbsp; 
				<a href="<?=sprintf($monthf, date('Y-m'))?>" title="Текущий месяц">•</a> &nbsp; 
				<a href="<?=sprintf($monthf, $ac_month_next)?>" title="Месяц вперед">&gt;</a> &nbsp; 
				<a href="<?=sprintf($monthf, $ac_year_next)?>" title="Год вперед">&gt;&gt;</a>
			</th>
		</tr>
	</table>
<?
	return ob_get_clean();
}

function multiuploader()
{
	ob_start();
	?>
    <link href="/admin/inc/advanced/swfuploader/css/default.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="/admin/inc/advanced/swfuploader/swfupload/swfupload.js"></script>
    <script type="text/javascript" src="/admin/inc/advanced/swfuploader/js/swfupload.queue.js"></script>
    <script type="text/javascript" src="/admin/inc/advanced/swfuploader/js/fileprogress.js"></script>
    <script type="text/javascript" src="/admin/inc/advanced/swfuploader/js/handlers.js"></script>
    <script type="text/javascript">
            var swfu;

            window.onload = function() {
                var settings = {
                    flash_url : "/admin/inc/advanced/swfuploader/swfupload/swfupload.swf",
                    upload_url: "/admin/inc/advanced/swfuploader/upload.php",
                    post_params: {"PHPSESSID" : "<?php echo session_id(); ?>"},
                    file_size_limit : "100 MB",
                    file_types : "*.*",
                    file_types_description : "All Files",
                    file_upload_limit : 100,
                    file_queue_limit : 0,
                    custom_settings : {
                        progressTarget : "fsUploadProgress",
                        cancelButtonId : "btnCancel"
                    },
                    debug: false,

                    // Button settings
                    button_image_url: "/admin/inc/advanced/swfuploader/images/TestImageNoText_65x29.png",
                    button_width: "65",
                    button_height: "29",
                    button_placeholder_id: "spanButtonPlaceHolder",
                    button_text: '<span class="theFont">обзор</span>',
                    button_text_style: ".theFont { font-size: 16; }",
                    button_text_left_padding: 10,
                    button_text_top_padding: 3,

                    // The event handler functions are defined in handlers.js
                    file_queued_handler : fileQueued,
                    file_queue_error_handler : fileQueueError,
                    file_dialog_complete_handler : fileDialogComplete,
                    upload_start_handler : uploadStart,
                    upload_progress_handler : uploadProgress,
                    upload_error_handler : uploadError,
                    upload_success_handler : uploadSuccess,
                    upload_complete_handler : uploadComplete,
                    queue_complete_handler : queueComplete	// Queue plugin event
                };

                swfu = new SWFUpload(settings);
             };
        </script>
    </head>
    <body>

    <div id="swfcontent" style="width:500px;">
        <form id="form1" action="index.php" method="post" enctype="multipart/form-data">
        <div class="fieldset flash" id="fsUploadProgress"><span class="legend">Очередь загрузки</span></div>
        <div id="divStatus">0 файлов загружено</div>
        <div>
            <span id="spanButtonPlaceHolder"></span>
            <input id="btnCancel" type="button" value="отменить все загрузки" onclick="swfu.cancelQueue();" disabled="disabled" style="margin-left: 2px; font-size: 8pt; height: 29px;" />
        </div>
        </form>
    </div>
    <?
	return ob_get_clean();
}
