<?
// ------------------ИНИЦИАЛИЗАЦИЯ--------------------
require('inc/common.php');
$tbl = 'article';
$rubric_img = 384;
$rubric = 'Статьи';
$id = (int)@$_GET['id'];
$ids = $_GET['ids'] ? (array)$_GET['ids'] : array($id);
$search = clean(@$_GET['search']);

$k = @$_SESSION[$tbl.'_k'] ? $_SESSION[$tbl.'_k'] : 50;
$p = @$_GET['p'] ? $_GET['p'] : 1;
$sort = setSort('date DESC');
$sqlmain = "SELECT * FROM {$prx}{$tbl} WHERE 1 ".($search ? 'AND '.getWhere('name,text1,text2') : '')." ORDER BY {$sort}";

// -------------------СОХРАНЕНИЕ----------------------
switch($action)
{
	case 'red':
	    $_POST["date"] = date('Y-m-d H:i:s');
		$id = uniUpdate($id);
		linkTest($id);

		upfile("../uploads/{$tbl}/{$id}.jpg", $_FILES['file'],@$_POST['del_file']);

		$p = getPage($sqlmain, $id, $k);
		?><script>top.location.href = "?p=<?=$p?>&id=<?=$id?>&rand=<?=mt_rand()?>";</script><?
		exit;
}
// остальные события
if($action)
{
	$_SESSION[$tbl.'_post'] = $_POST;
	$vars = array(
		'tbl' => $tbl,
		'action' => $action,
	);
	go301('inc/action.php?1'.getQS().'&'.http_build_query($vars));
	exit;
}

// ----------------------ВЫВОД------------------------
ob_start();
switch($show)
{
	case 'red': //	редактирование
		$rubric .= ' &raquo; '.($id ? 'Редактирование' : 'Добавление');
		$row = getRow("SELECT * FROM {$prx}{$tbl} WHERE id='{$id}'");
	?>
		<form action="?id=<?=$id?>&action=red" method="post" enctype="multipart/form-data" target="iframe">
			<table class="red" width="800">
				<!--<tr>
					<th>Дата</th>
					<td><?=aInput('DateTime', 'name="date" style="width:25%"', ($row['date']>0 ? date('d.m.Y H:i', strtotime($row['date'])) : date('d.m.Y H:i')))?></td>
				</tr>-->
				<tr>
					<th>Название</th>
					<td><input name="name" value='<?=$row['name']?>'></td>
				</tr>
				<tr>
					<th>Изображение</th>
					<td><?=fileUpload("/uploads/{$tbl}/{$id}.jpg", 'name="file" style="width:80%"')?></td>
				</tr>
				<tr>
					<th>Транслит для ссылки</th>
					<td><input name="link" value='<?=$row['link']?>'></td>
					<td><?=help('Формируется автоматически')?></td>
				</tr>
				<tr>
					<th>Текст</th>
					<td><textarea name="text1" toolbar="basic" rows="10"><?=$row['text1']?></textarea></td>
				</tr>
				<tr>
					<th>Подробнее</th>
					<td><textarea name="text2" toolbar="medium" rows="20"><?=$row['text2']?></textarea></td>
				</tr>
				<tr>
					<th>title</th>
					<td><input name="title" value='<?=$row['title']?>'></td>
				</tr>
				<tr>
					<th>keywords</th>
					<td><textarea name="keywords"><?=$row['keywords']?></textarea></td>
				</tr>
				<tr>
					<th>description</th>
					<td><textarea name="description"><?=$row['description']?></textarea></td>
				</tr>
                <tr>
                    <th>Скрыть</th>
                    <td><input name="hide" type="checkbox" <?=$row['hide'] ? 'checked' : ''?> style="width:auto;" value="1"></td>
                </tr>
                <tr>
                    <th>Главная</th>
                    <td><input name="is_main" type="checkbox" <?=$row['is_main'] ? 'checked' : ''?> style="width:auto;" value="1"></td>
                </tr>
				<tr>
					<td align="center" colspan="2"><?=btnAction()?></td>
				</tr>
			</table>
		</form>			  
	<?	break;

	default:	// просмотр
	?>
		<?=lnkAction('Add,Dels,Copy')?>
		<?=showK($k)?>
		<table class="content">
			<tr>
				<th><input type="checkbox" onClick="setCbTable(this)"></th>
				<th sort="<?=getSort('id')?>">ID</th>
				<th sort="<?=getSort('date')?>">Дата</th>
				<th sort="<?=getSort('name')?>">Название</th>
				<th>Ссылка</th>
                <th sort="<?=getSort('hide')?>">Скрыть<div style="padding-right:25px;" align="center"><input type="checkbox" onClick="event.cancelBubble=true; if(sure()) { setCbTable(this,true); } else  return false;"></div></th>
                <th sort="<?=getSort('is_main')?>">Главная<div style="padding-right:25px;" align="center"><input type="checkbox" onClick="event.cancelBubble=true; if(sure()) { setCbTable(this,true); } else  return false;"></div></th>
				<th></th>
			</tr>
		<? 
			$res = sql($sqlmain.' LIMIT '.($p-1)*$k.', '.$k);
			while($row = mysql_fetch_assoc($res))
			{
				$id = $row['id'];
			?>
				<tr id="tr<?=$id?>" onDblClick="location.href='?id=<?=$id?>&show=red'">
					<td><input type="checkbox" name="ids[]" value="<?=$id?>"></td>
					<td align="right"><?=$id?></td>
					<td><?=$row['date'] > 0 ? date('d.m.Y', strtotime($row['date'])) : ''?></td>
					<td><div class="redone" id="<?=$id?>" name="name"><?=$row['name']?></div></td>
					<td><a href="/articles/<?=$row['link']?>/">открыть</a></td>
                    <td align="center"><input type="checkbox" <?=($row['hide'] ? 'checked' : '')?> onClick="toAjax('?action=redone&id=<?=$id?>&field=hide&value='+(this.checked ? 1 : 0))"></td>
                    <td align="center"><input type="checkbox" <?=($row['is_main'] ? 'checked' : '')?> onClick="toAjax('?action=redone&id=<?=$id?>&field=is_main&value='+(this.checked ? 1 : 0))"></td>
					<td><?=lnkAction('Red,Del')?></td>
				</tr>
		<?	}	?>
			<tr>
				<td colspan="99" align="center"><?=lnkPages($sqlmain, $p, $k, '?p=%s'.getQS())?></td>
			</tr>
		</table>
	<?	break;
}
$content = ob_get_clean();

require('template.php');
?>