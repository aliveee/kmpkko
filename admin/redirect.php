<?
// ------------------ИНИЦИАЛИЗАЦИЯ--------------------
require('inc/common.php');
$tbl = 'redirect';
$rubric_img = 1920;
$rubric = 'Редиректы';
$id = (int)@$_GET['id'];
$ids = $_GET['ids'] ? (array)$_GET['ids'] : array($id);
$sort = setSort('id');

// -------------------СОХРАНЕНИЕ----------------------
switch($action)
{
	case 'red':
		$id = uniUpdate($id);
		?><script>top.location.href = "?id=<?=$id?>&rand=<?=mt_rand()?>";</script><?
		exit;

	case 'del':
		$ids = $ids ? implode(',', $ids) : 0;
		sql("delete from {$prx}{$tbl} WHERE id IN ({$ids})");
		break;
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
            <input type="hidden" name="use_date" value="1" />
			<table class="red" width="50%">
            	<tr>
					<th>Ссылка старая</th>
					<td><input name="old_url" value='<?=$row['old_url']?>' <?=($row['readonly'] ? 'readonly' : '')?>></td>
				</tr>
            	<tr>
					<th>Ссылка новая</th>
					<td><input name="new_url" value='<?=$row['new_url']?>' <?=($row['readonly'] ? 'readonly' : '')?>></td>
				</tr>
				<tr>
					<th>Скрыть</th>
					<td><input name="hide" type="checkbox" <?=$row['hide'] ? 'checked' : ''?> style="width:auto;" value="1"></td>
				</tr>
			</table>

			<div style="padding-top:10px;" align="center"><?=btnAction()?></div>
		</form>			  
	<?	break;

	default:	// просмотр
	?>
		<?=lnkAction('Add,Dels,Copy')?>
		<table class="content">
			<tr class="nodrop nodrag">
				<th><input type="checkbox" onClick="setCbTable(this)"></th>
				<th sort="<?=getSort('id')?>">ID</th>
				<th sort="<?=getSort('old_url')?>">Старый урл</th>
				<th sort="<?=getSort('new_url')?>">Новый урл</th>
				<th sort="<?=getSort('hide')?>">Скрыть<div style="padding-right:25px;" align="center"><input type="checkbox" onClick="event.cancelBubble=true; if(sure()) { setCbTable(this,true); } else  return false;"></div></th>
				<th>Ссылка</th>
				<th></th>
			</tr>
		<? $tree = sql("SELECT * FROM {$prx}{$tbl} ORDER BY {$sort}");
			while($row=mysql_fetch_array($tree)) 
			{
				$id = $row['id'];
                ?>
				<tr id="tr<?=$id?>" onDblClick="location.href='?id=<?=$id?>&show=red'">
					<td><? if(!$row['readonly']) { ?><input type="checkbox" name="ids[]" value="<?=$id?>"><? } ?></td>
					<td align="right"><?=$id?></td>
                    <td><?=$row['old_url']?></td>
                    <td><?=$row['new_url']?></td>
					<td align="center"><input type="checkbox" <?=($row['hide'] ? 'checked' : '')?> onClick="toAjax('?action=redone&id=<?=$id?>&field=hide&value='+(this.checked ? 1 : 0))"></td>
					<td><a href="<?=$row['old_url']?>" title="<?=$row['old_url']?>">проверить</a></td>
					<td align="right" <?=$row['readonly'] ? 'style="padding-right:34px;"' : ''?> nowrap>
						<?=lnkAction(($vetka['level']==$lastlevel ? 'Move' : 'UpDown').',Red'.($row['readonly'] ? '' : ',Del'))?>
					</td>
				</tr>
		<?	}	?>
		</table>
	<?	break;
}
$content = ob_get_clean();

require('template.php');
?>