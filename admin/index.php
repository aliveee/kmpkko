<?
// ---------------------ФУНКЦИИ-----------------------

// РАЗМЕР ДИРЕКТОРИИ
function dirsize($dir)
{
	global $size;
	if($dh = opendir($dir))
	{
		while($file = readdir($dh))
			if($file != '.' && $file != '..')
			{
				$f = $dir.'/'.$file; 
				if(filetype($f) == 'dir')
					dirsize($f);
				else
					$size += filesize($f);
			}
		closedir($dh);
	}
	return $size;
}

// ------------------ИНИЦИАЛИЗАЦИЯ--------------------
require('inc/common.php');

$rubric_img = 1344;
$rubric = 'Система управления сайтом';

// -------------------СОХРАНЕНИЕ----------------------
switch($action)
{
	case 'phpinfo':
		echo phpinfo();
		exit;

	case 'clean_thumb':
		undir('../uploads/thumb/');
		@mkdir(utf8_decode('../uploads/thumb/'), 0777);
		?><script>location.reload();</script><?
		exit;
}
if($action)	exit;


// ----------------------ВЫВОД------------------------
ob_start();
switch($show)
{
	default: // просмотр
	?>
		<table class="content">
			<tr>
				<th>Общая информация</th>
			</tr>
			<tr>
				<td style="padding:20px;">
					<a href="settings.php">Название сайта</a>: <b><?=set('title')?></b><br>
					URL: <a href="/"><b>http://<?=$_SERVER['SERVER_NAME']?></b></a><br>
					<!--<br>
					Размер кэша: <b><?=number_format(dirsize('../uploads/thumb/')/1024/1024,2,',',' ')?> Mb</b> (<a href="javascript:toAjax('?action=clean_thumb')">очистить</a>)-->
				</td>
			</tr>
		</table>
		<br><br>

		<a name="phpinfo"></a>
		<table class="content" width="100%">
			<tr>
				<th><a href="?phpinfo#phpinfo">Показать PHP-info</a></th>
			</tr>
		<?	if(isset($_GET['phpinfo'])) {	?>
				<tr>
					<td width="100%"><iframe src="?action=phpinfo" style="width:100%; height:600px;"></iframe></td>
				</tr>
		<?	}	?>
		</table>
	<?	break;
}
$content = ob_get_clean();

require('template.php');
?>