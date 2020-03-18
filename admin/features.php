<?
// ------------------ИНИЦИАЛИЗАЦИЯ--------------------
require('inc/common.php');
$tbl = 'feature';
$rubric_img = 896;
$rubric = 'Каталог &raquo; Характеристики';
$top_menu = 'catalog';
$id = mysql_escape_string(@$_GET['id']);
$ids = $_GET['ids'] ? (array)$_GET['ids'] : array($id);
$id_parent = (int)@$_GET['id_parent'];

$type = clean(@$_GET['type']);
$id_catalog = (int)@$_GET['id_catalog'];

$sort = setSort('sort,name');

// -------------------СОХРАНЕНИЕ----------------------
switch($action)
{
	case 'redone':
		foreach($_REQUEST as $key=>$val)
			$$key = clean($val);
		
		if(strpos($id, '~|~')) // значения
		{
			list($id_features, $val_old) = explode('~|~', $id);
			$val_old = clean($val_old);
			if((string)$value === '')
				errorAlert('Для удаления значения характеристики используйте функцию удаления',1);				
			sql("UPDATE {$prx}feature_good SET value='{$value}' WHERE id_feature='{$id_features}' AND value='{$val_old}'");
		}
		elseif($id)  //
			update($tbl, "`{$field}`='{$value}'", $id);
		if(isset($show_value)) echo nl2br(stripslashes($value));
		exit;

	case 'red':
        if (!$admin_name) $admin_name=$name;
		$id = uniUpdate($id);
		?><script>top.location.href = "?id=<?=$id?>&id_parent=<?=$id?>&rand=<?=mt_rand()?>";</script><?
		exit;

	case 'del':
		foreach($ids as $id)
		{
			if(strpos($id, '~|~')) // значения
			{
				list($id_features, $val_old) = explode('~|~', $id);
				sql("DELETE FROM {$prx}feature_good WHERE id_feature='{$id_features}' AND value='{$val_old}'");
			}
			else // 
			{
				update($tbl, '', $id);
				sql("DELETE FROM {$prx}feature_good WHERE id_feature='{$id}'");
			}
		}
		?><script>location.reload();</script><?
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
	?>	<form action="?id=<?=$id?>&action=red" method="post" enctype="multipart/form-data" target="iframe">
			<table class="red" width="500">
				<tr>
					<th>Название</th>
					<td><input name="name" value='<?=$row['name']?>'></td>
				</tr>
				<tr>
					<th>Внутренне название</th>
					<td><input name="admin_name" value='<?=$row['admin_name']?>'></td>
				</tr>				<tr>
					<th>Тип характеристики</th>
					<td><?=dllEnum($tbl, 'type', 'name="type" style="width:auto;"', $row['type'], '')?></td>
				</tr>
				<tr>
					<th>В превью</th>
					<td><input name="vitrina" type="checkbox" <?=($row['vitrina'] ? 'checked' : '')?> style="width:auto;" value="1"></td>
				</tr>
				<tr>
					<th>Измеряется в</th>
					<td><input name="izm" value='<?=$row['izm']?>'></td>
				</tr>                
				<tr>
					<td colspan="2" align="center"><?=btnAction()?></td>
				</tr>
			</table>
		</form>			  
	<?	break;

	default:	// просмотр
    ?>
		<form>
			<table class="content" style="margin-top:0;">
				<tr>
					<th>Раздел каталога</th>
					<th><?=dllTree("SELECT id,name,id_parent FROM {$prx}catalog ORDER BY sort,id", 'class="chosen" data-placeholder="Выберите раздел" name="id_catalog" onChange="this.form.submit();"', $id_catalog, '', null, 0, 0, ".&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;")?></th>
				</tr>
				<tr>
					<th>Тип в фильтре</th>
					<th colspan="2"><?=dllEnum($tbl, 'type', 'name="type" onChange="this.form.submit();"', $type, '')?></th>
				</tr>
			</table>
		</form>
		<br><br>
		<?=lnkAction('Add,Dels')?>
		<a href="features_catalog.php" class="la laMove" style="cursor:pointer;" onClick="return openWindow(this, 600, 600)">сортировка по разделам</a>
    &nbsp; &nbsp;
		<table class="content">
			<tr class="nodrop nodrag">
				<th><input type="checkbox" onClick="setCbTable(this)"></th>
				<th sort="<?=getSort('id')?>">ID</th>
				<th sort="<?=getSort('name')?>" nowrap>Название <span class="normal">/ значения</span></th>
				<th nowrap>Внутреннее название</th>
                <th sort="<?=getSort('vitrina')?>">В превью<div style="padding-right:25px;" align="center"><input type="checkbox" onClick="event.cancelBubble=true; if(sure()) { setCbTable(this,true); } else  return false;"></div></th>
				<th sort="<?=getSort('type')?>">Тип</th>
				<th></th>
			</tr>
		<? // названия
			$count = getArr("SELECT id_feature, COUNT(DISTINCT value) AS c FROM {$prx}feature_good GROUP BY id_feature");
			
			$where = '1';
			if($id_catalog) $where .= " AND id IN (".featuresIds($id_catalog, false).")";
			if($type) $where .= " AND `type`='{$type}'";
			
			$res = sql("SELECT * FROM {$prx}{$tbl} WHERE {$where} ORDER BY {$sort}");
			while($row = mysql_fetch_assoc($res))
			{	
				$id = $row['id'];	?>
				<tr id="tr<?=$id?>" onDblClick="location.href='?id=<?=$id?>&show=red'">
					<td><input type="checkbox" name="ids[]" value="<?=$id?>"></td>
					<td align="right"><?=$id?></td>
					<td>
						<a href="?id_parent=<?=$id==$id_parent ? 0 : $id?>&id=<?=$id?><?=getQS('id_parent,id')?>" class="la" style="background-position:0 -<?=($id==$id_parent ? 720 : 672)?>px; display:block;">
							<b><?=$row['name']?></b> <span class="cg">(<?=(int)$count[$id]?>)</span>
						</a>
					</td>
					<td>
						<?=$row['admin_name']?>
					</td>
                    <td align="center"><input type="checkbox" <?=($row['vitrina'] ? 'checked' : '')?> onClick="toAjax('?action=redone&id=<?=$id?>&field=vitrina&value='+(this.checked ? 1 : 0))"></td>
					<td align="center"><?=$row['type']?></td>
					<td><?=lnkAction($id_parent ? 'Move,Red,Del' : 'Move,Red,Del')?></td>
				</tr>
			<? // значения
				if($id == $id_parent)
					if($arr = getArr("SELECT DISTINCT value FROM {$prx}feature_good WHERE id_feature='{$id}'"))
					{
						natsort($arr);
						echo '<tbody>';
						foreach($arr as $value)
						{	
							$id = $id_parent.'~|~'.$value;	?>
							<tr>
								<td><input type="checkbox" name="ids[]" value="<?=$id?>"></td>
								<td></td>
								<td style="padding-left:30px;" colspan="5"><div class="redone" name="value" id="<?=$id?>"><?=$value?></div></td>
								<td align="right"><?=lnkAction('Del')?></td>
							</tr>
					<?	}
						echo '</tbody>';
					}
			}	?>
		</table>	
	<?	break;
}
$content = ob_get_clean();

require('template.php');
?>