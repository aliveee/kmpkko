<?
// ------------------ИНИЦИАЛИЗАЦИЯ--------------------
require('inc/common.php');
$tbl = 'catalog';
$rubric_img = 896;
$rubric = 'Каталог &raquo; Разделы';
$top_menu = 'catalog';
$id = (int)@$_GET['id'];
$ids = $_GET['ids'] ? (array)$_GET['ids'] : array($id);

$sort = setSort('sort,id');
$open_ids = @$_SESSION[$tbl.'open_ids'] ? $_SESSION[$tbl.'open_ids'] : $_SESSION[$tbl.'open_ids'] = '0';

if($search = clean(@$_GET['search']))
{
	$open_ids = 0;
	$res = sql("SELECT id FROM {$prx}{$tbl} WHERE 1 ".($search ? 'AND '.getWhere('name,text') : ''));
	while($row = mysql_fetch_assoc($res))
		$open_ids .= ','.getIdParents("SELECT id,id_parent FROM {$prx}{$tbl}", $row['id'], false);
	$_SESSION[$tbl.'open_ids'] = $open_ids;
}

// -------------------СОХРАНЕНИЕ----------------------
//if($action && !($_SESSION['priv'] == 'admin' || $_SESSION['priv']['red_catalog']))
	//errorAlert('Нет прав для редактирования разделов каталога',1);
switch($action)
{
	case 'red':
		//if(!$id && !$_POST['id_parent'] && getField("SELECT COUNT(*) AS c FROM {$prx}{$tbl} WHERE id_parent='0'") >= 9)
		//	errorAlert("Рубрик первого уровня не может быть больше 9-ти");
		$id = uniUpdate($id);
		update($tbl, "date_red=NOW()", $id);

		//апдейст вспомогательной структуры каталога
		updateCatalogStructure($id);
		
		linkTest($id, 'id_parent');
		upfile("../uploads/{$tbl}/{$id}.jpg", $_FILES['file'], @$_POST['del_file']);
        upfile("../uploads/{$tbl}/{$id}_info.jpg", $_FILES['file_info'],@$_POST['del_file_info']);

		$_SESSION[$tbl.'open_ids'] .= ','.getIdParents("SELECT id,id_parent FROM {$prx}{$tbl}", $id, false);
		
        if (!isset($_POST['apply']))
        {
         ?><script>top.location.href = "?id=<?=$id?>&rand=<?=mt_rand()?>";</script><?
        } 
		exit;

	case 'del_all':
		// мочим разделы с подразделами
		$ids_c = array();
		foreach($ids as $id)
			$ids_c = array_merge($ids_c, getIdChilds("SELECT id,id_parent FROM {$prx}{$tbl}", $id));
		$_GET['ids'] = $ids_c = array_unique($ids_c);
		foreach($ids_c as $id) {
          @unlink("../uploads/{$tbl}/{$id}.png");
          @unlink("../uploads/{$tbl}/{$id}_menu.png");
        }
		update($tbl, '', $ids_c);

		// мочим товары
		$ids_c = $ids_c ? implode(',', $ids_c) : 0;
		$_GET['ids'] = getArr("SELECT id FROM {$prx}good WHERE id_catalog IN ({$ids_c})");
		go301('goods.php?action=del'.getQS('action'));
		exit;

	case 'del':
		if(!$ids) exit;
		$ids = implode(',', $ids);
		if ($ids){
			// проверка
			if(getField("SELECT COUNT(*) AS c FROM {$prx}good WHERE id_catalog IN ({$ids})") || getField("SELECT COUNT(*) AS c FROM {$prx}{$tbl} WHERE id_parent IN ({$ids}) AND id NOT IN ({$ids})"))
			{	?>
				<script>
					if(confirm('Внимение! <?=strpos($ids,',') ? 'Разделы будут удалены' : 'Раздел будет удален'?> полностью, включая подразделы и товары входящии в них. Продолжить ?'))
						toAjax('?action=del_all<?=getQS('action')?>');
				</script>
				<?	exit;
			}
		}
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
	?>
		<form action="?id=<?=$id?>&action=red" method="post" enctype="multipart/form-data" target="iframe">
           <input type="hidden" name="use_date" value="1" />
			<table class="red" width="850">
				<tr>
					<th>Расположение</th>
					<td><?=dllTree("SELECT id,name,id_parent FROM {$prx}{$tbl} ORDER BY sort,id", 'class="chosen" data-placeholder="Выберите раздел" name="id_parent" style="width:auto;"', $row['id_parent'], array(0, 'Корневая'), $id, 0, 0, ".&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;")?></td>
				</tr>
				<tr>
					<th>
                     Изображение
                    </th>
					<td><?=fileUpload("/uploads/{$tbl}/{$id}.jpg", 'name="file" style="width:80%"')?></td>
				</tr>
                <tr>
                    <th>
                        Изображение для описания
                    </th>
                    <td><?=fileUpload("/uploads/{$tbl}/{$id}_info.jpg", 'name="file_info" style="width:80%"')?></td>
                </tr>
				<tr>
					<th>Название</th>
					<td><input name="name" value='<?=$row['name']?>'></td>
				</tr>
				<tr>
					<th>Название на странице (H1)</th>
					<td><input name="name2" value='<?=$row['name2']?>'></td>
					<td><?=help('Если не заполнено, используется поле "Название"')?></td>
				</tr>
				<tr>
					<th>Транслит для ссылки</th>
					<td><input name="link" value='<?=$row['link']?>'></td>
					<td><?=help('Формируется автоматически')?></td>
				</tr>
				<tr>
					<th>Текст</th>
					<td><textarea name="text" toolbar="medium" rows="15"><?=$row['text']?></textarea></td>
				</tr>
                <tr>
                    <th>Текст превью</th>
                    <td><textarea name="introtext" toolbar="medium" rows="15"><?=$row['introtext']?></textarea></td>
                </tr>
				<!--tr>
					<th>Бонус</th>
					<td><input name="bonus" value='<?=$row['bonus']?>'></td>
				</tr-->                
				<?php
				if ($id) {
					?>
					<tr>
						<th>Характеристики</th>
						<td>
							<?php
							$cnt = 0;
							$catalogFeatures = trim(getField("SELECT ids_features FROM {$prx}catalog WHERE id = '{$id}'"));
							if (!$catalogFeatures) {
								//$catalogFeatures = '0';
                                   $catalogFeatures=getField("select GROUP_CONCAT(id_feature) from {$prx}feature_catalog where id_catalog='{$id}'");
							}
                                if (!$catalogFeatures) $catalogFeatures='0';


							$cnt = (int)getField("SELECT COUNT(*) FROM {$prx}feature WHERE id IN($catalogFeatures)");
							?>
							<a href="javascript:void(0)" onclick="openFeatures(<?=$id?>)">Редактировать</a> (<?= $cnt ?>)
						</td>
					</tr>
					<?php
				}
				?>
				<tr>
					<th>Скрыть</th>
					<td><input name="hide" type="checkbox" <?=$row['hide'] ? 'checked' : ''?> style="width:auto;" value="1"></td>
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
					<td colspan="2" align="center"><?=btnAction('Save,Apply,Cancel')?></td>
				</tr>
			</table>
		</form>
		<script type="text/javascript">
			function openFeatures(id) {
				window.open('catalog_features.php?id_catalog=' + id, 'win_features', 'width=400,height=500,left=100,top=100,scrollbars=1');
			}
		</script>
	<?	break;

	default:	// просмотр
    ?>
		<?=lnkAction('Add,Dels,Copy')?>
		<table class="content">
			<tr class="nodrop nodrag">
				<th><input type="checkbox" onClick="setCbTable(this)"></th>
				<th sort="<?=getSort('id')?>">ID</th>
				<th style="padding-right:0;"><a href="javascript:toAjax('?action=open_ids&open_ids=<?=$open_ids=='all' ? 0 : 'all'?>')" class="la16" style="background-position:0 -<?=$open_ids=='all' ? 720 : 672?>px;"></a></th>
				<th sort="<?=getSort('name')?>">Название</th>
				<th sort="<?=getSort('hide')?>">Скрыть<div style="padding-right:25px;" align="center"><input type="checkbox" onClick="event.cancelBubble=true; if(sure()) { setCbTable(this,true); } else  return false;"></div></th>
				<th>Ссылка</th>
				<th></th>
			</tr>
		<? 
			$count = getArr("SELECT id_catalog, COUNT(id_catalog) AS c FROM {$prx}good GROUP BY id_catalog");
			$count_parent = getArr("SELECT c.id, COUNT(c2.id) AS cp FROM {$prx}{$tbl} AS c LEFT JOIN {$prx}{$tbl} AS c2 ON c2.id_parent=c.id GROUP BY c.id");
			$tree = getTree("SELECT * FROM {$prx}{$tbl} ORDER BY {$sort}");
			$lastlevel = getLastLevel($tree);
			$level = 0;
			foreach((array)$tree as $vetka) 
			{
				$row =  $vetka['row'];
				if($open_ids != 'all' && !strpos(",,{$open_ids},", ",{$row['id_parent']},"))
					continue;
				$id = $row['id'];
				$prefix = getPrefix($vetka['level']);
				if($level != $vetka['level'])
				{
					$level = $vetka['level'];
					echo '<tbody>';
				}	?>
				<tr id="tr<?=$id?>" onDblClick="location.href='?id=<?=$id?>&show=red'">
					<td><input type="checkbox" name="ids[]" value="<?=$id?>"></td>
					<td align="right"><?=$id?></td>
					<td style="padding-right:0;">
					<?	if($count_parent[$id]) { ?>
							<a href="javascript:toAjax('?action=open_ids&id=<?=$id?>')" class="la16" style="background-position:0 -<?=$open_ids=='all' || strpos(",,{$open_ids},",",{$id},") ? 720 : 672?>px;"></a>
					<?	}	?>
					</td>
					<td <?=(!$vetka['level'] ? 'style="font-weight:bold;"' : '')?> data-level="<?=$vetka['level']?>" style="padding-left:<?=10+30*$vetka['level']?>px">
						<?
                        if ($vetka['level']==0 && file_exists($_SERVER['DOCUMENT_ROOT'].'/uploads/catalog/'.$id.'_menu.png')) {
						  ?>
                           <img src="/uploads/catalog/<?=$id?>_menu.png" />
                          <?
						}else{
						  ?>
                             <img src="/uploads/catalog/32x32/<?=$row['id']?>.jpg" class="img-responsive" />
                          <?
						}?>
						<a href="goods.php?id_catalog=<?=$id?>"><?=$row['name']?></a> <span class="normal cg" title="Количество товаров в разделе / включая подразделы">(<?=(int)$count[$id].(($c=getCountGoods($id, $tree, $count)) == $count[$id] ? '' : ' / '.(int)$c)?>)</span>
					</td>
					<td align="center"><input type="checkbox" <?=($row['hide'] ? 'checked' : '')?> onClick="toAjax('?action=redone&id=<?=$id?>&field=hide&value='+(this.checked ? 1 : 0))"></td>
					<td><a href="<?=\Lib\CatalogHelper::GetUrl('',$row["link"])?>" title="/<?=id2links($id)?>">открыть</a></td>
					<td align="right"><?=lnkAction(($vetka['level']==$lastlevel ? 'Move' : 'UpDown').',Red,Del')?></td>
				</tr>
		<?	}	?>
		</table>	
	<?	break;
}
$content = ob_get_clean();

require('template.php');
?>