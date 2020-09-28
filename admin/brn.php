<?
// ------------------ИНИЦИАЛИЗАЦИЯ--------------------
require('inc/common.php');
$tbl = 'ebnr';
$rubric_img = 64;
$rubric = 'Баннеры';
$id = (int)@$_GET['id'];
$ids = $_GET['ids'] ? (array)$_GET['ids'] : array($id);
$id_parent = (int)@$_GET['id_parent'];

$sort = setSort('sort,id');

// -------------------СОХРАНЕНИЕ----------------------
switch($action)
{
	case 'red':
		$_POST['ids_catalog'] = implode(',', $_POST['ids_catalog']);
		$id = uniUpdate($id);
		if(!$_POST['link'])
			update($tbl, "link=''", $id);
		
		if(@$_POST['del_file1'])
		{
			upfile("../uploads/{$tbl}/".getField("SELECT img FROM {$prx}{$tbl} WHERE id='{$id}'"), $_FILES['file1'], @$_POST['del_file1']);
            update($tbl, "img=''", $id);
		}
        if(@$_POST['del_file1_m'])
        {
            upfile("../uploads/{$tbl}/".getField("SELECT img FROM {$prx}{$tbl} WHERE id='{$id}'"), $_FILES['file1_m'], @$_POST['del_file1_m']);
            update($tbl, "img_m=''", $id);
        }
		
		if($_FILES['file1']['name'])
		{
				$ext=pathinfo($_FILES['file1']['name'], PATHINFO_EXTENSION);
			  
			  upfile("../uploads/{$tbl}/{$id}.{$ext}", $_FILES['file1'], @$_POST['del_file1']);
			  if ($_FILES['file1'])
				update($tbl, "img='{$id}.{$ext}'", $id);
		}
        if($_FILES['file1_m']['name'])
        {
            $ext=pathinfo($_FILES['file1_m']['name'], PATHINFO_EXTENSION);

            upfile("../uploads/{$tbl}/{$id}_m.{$ext}", $_FILES['file1_m'], @$_POST['del_file1_m']);
            if ($_FILES['file1_m'])
                update($tbl, "img_m='{$id}_m.{$ext}'", $id);
        }
		//upfile("../uploads/{$tbl}/{$id}.swf", $_FILES['file2'], @$_POST['del_file2']);

		?><script>top.location.href = "?id=<?=$id?>&id_parent=<?=@$_POST['id_parent'] ? (int)$_POST['id_parent'] : $id?>&rand=<?=mt_rand()?>";</script><?
		exit;
}
// остальные события
if($action)
{
	$_SESSION[$tbl.'_post'] = $_POST;
	$vars = array(
		'tbl' => $tbl,
		'action' => $action,
		'move_group' => 'id_parent'
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
	?>	<form action="?id=<?=$id?>&action=red" method="post" enctype="multipart/form-data" target="iframe">
			<table class="red" width="450">
                <tr>
                    <th>Название</th>
                    <td><input name="name" value='<?=$row['name']?>'></td>
                </tr>
                <tr>
                    <th>На главной</th>
                    <td><input name="vitrina" type="checkbox" <?=$row['vitrina'] ? 'checked' : ''?> style="width:auto;" value="1"></td>
                    <td><?=help('Только для верхних баннеров')?></td>
                </tr>
                <tr>
                    <th>Изображение</th>
                    <td><?=fileUpload("/uploads/{$tbl}/{$row['img']}", 'name="file1" style="width:80%"')?></td>
                </tr>
                <tr>
                    <th>Моб. изображение</th>
                    <td><?=fileUpload("/uploads/{$tbl}/{$row['img_m']}", 'name="file1_m" style="width:80%"')?></td>
                </tr>
                <tr>
                    <th>Название</th>
                    <td><input name="name" value='<?=$row['name']?>'></td>
                </tr>
                <tr>
                    <th>Текст</th>
                    <td><textarea name="text"><?=$row['text']?></textarea></td>
                </tr>
                <tr>
                    <th>Кнопка</th>
                    <td><input name="button" value='<?=$row['button']?>'></td>
                </tr>
                <tr>
                    <th>Ссылка</th>
                    <td><input name="link" value='<?=$row['link']?>'></td>
                </tr>
                <tr>
                    <th>Скрыть</th>
                    <td><input name="hide" type="checkbox" <?=$row['hide'] ? 'checked' : ''?> style="width:auto;" value="1"></td>
                </tr>
				<tr>
					<td colspan="2" align="center"><?=btnAction()?></td>
				</tr>
			</table>
		</form>			  
	<?	break;

	default:	// просмотр
	?>
		<!--a href="?show=red" class="la laAdd">добавить раздел</a> &nbsp; &nbsp;-->
		<a href="?show=red" class="la laAdd">добавить</a> &nbsp; &nbsp;
		<?=lnkAction('Dels,Copy')?>
		<table class="content">
			<tr class="nodrop nodrag">
				<th><input type="checkbox" onClick="setCbTable(this)"></th>
				<th sort="<?=getSort('id')?>">ID</th>
				<th sort="<?=getSort('name')?>">Название</th>
				<th>Баннер</th>
				<th sort="<?=getSort('vitrina')?>">На главной<div style="padding-right:25px;" align="center"><input type="checkbox" onClick="event.cancelBubble=true; if(sure()) { setCbTable(this,true); } else  return false;"></div></th>
				<th sort="<?=getSort('hide')?>">Скрыть<div style="padding-right:25px;" align="center"><input type="checkbox" onClick="event.cancelBubble=true; if(sure()) { setCbTable(this,true); } else  return false;"></div></th>
				<th>Ссылка</th>
				<th></th>
			</tr><?

            $res1 = sql("SELECT * FROM {$prx}{$tbl} ORDER BY {$sort}");
            if(mysql_num_rows($res1))
            {
                echo '<tbody>';
                while($row1 = mysql_fetch_assoc($res1))
                {
                    $id = $row1['id'];	?>
                    <tr id="tr<?=$id?>" onDblClick="location.href='?id=<?=$id?>&show=red'">
                        <td><input type="checkbox" name="ids[]" value="<?=$id?>"></td>
                        <td><?=$id?></td>
                        <td><?=$row1['name']?></td>
                        <td>
                            <?
                            if(file_exists("../uploads/{$tbl}/{$row1['img']}")) { ?>
                                <a href="/uploads/<?=$tbl?>/<?=$row1['img']?>" class="fb-img lupa" rel="fb" title="<?=$row1['name']?>">
                                    <img src="/uploads/<?=$tbl?>/<?=$row1['img']?>" title="увеличить" height="60">
                                </a>
                            <?
                            } ?>
                        </td>
                        <td align="center">
                            <input type="checkbox" <?=($row1['vitrina'] ? 'checked' : '')?> onClick="toAjax('?action=redone&id=<?=$id?>&field=vitrina&value='+(this.checked ? 1 : 0))">
                        </td>
                        <td align="center"><input type="checkbox" <?=($row1['hide'] ? 'checked' : '')?> onClick="toAjax('?action=redone&id=<?=$id?>&field=hide&value='+(this.checked ? 1 : 0))"></td>
                        <td><? if($row1['link']) { ?><a href="<?=$row1['link']?>" title="<?=$row1['link']?>">открыть</a><? } ?></td>
                        <td align="right"><?=lnkAction('Move,Red,Del', "")?></td>
                    </tr>
            <?	}
                echo '</tbody>';
            }

		?></table>
	<?	break;
}
$content = ob_get_clean();

require('template.php');
?>