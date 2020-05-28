<?
// ------------------ИНИЦИАЛИЗАЦИЯ--------------------
require('inc/common.php');
$tbl = 'page';
$rubric_img = 2561;
$rubric = 'Страницы';
$id = (int)@$_GET['id'];
$ids = $_GET['ids'] ? (array)$_GET['ids'] : array($id);
$top_menu = 'pages';
$sort = setSort('sort,id');

// -------------------СОХРАНЕНИЕ----------------------
switch($action)
{
	case 'red':
		if(!$_POST['link'])
			$_POST['link'] = '/'.makeUrl($_POST['name']).'/';

        foreach ($_POST['forms'] as $i=>$val)
        {
          if ($val=='') continue;
          $elements['name'][$i]=$val;
          $elements['type'][$i]=$_POST['type'][$i];
          if ($_POST['type'][$i]=='3') $elements['values'][$i]=$_POST['values'][$i];
          
        }
        
        $f['form_name']=$_POST['form_name'];
        $f['elements']=$elements;
        
        $el=cleanArr($f);
        //$el=$elements;

		$id = uniUpdate($id);
		update($tbl, "date_red=NOW()", $id);
        
        update($tbl,"form_info='{$el}'",$id);
        

		upfile("../uploads/{$tbl}/{$id}.png", $_FILES['file'],@$_POST['del_file']);

		?><script>top.location.href = "?id=<?=$id?>&rand=<?=mt_rand()?>";</script><?
		exit;

	case 'del':
		$ids = $ids ? implode(',', $ids) : 0;
		sql("UPDATE {$prx}{$tbl} SET id_parent='0' WHERE id_parent IN ({$ids})");
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
					<th>Расположение</th>
					<td><?=dllTree("SELECT id,name,id_parent FROM {$prx}{$tbl} ORDER BY sort,id", 'name="id_parent"', $row['id_parent'], "", $id)?></td>
				</tr>
				<tr>
					<th>Название</th>
					<td><input name="name" value='<?=$row['name']?>'></td>
				</tr>
				<tr>
					<th>H1</th>
					<td><input name="h1" value='<?=$row['h1']?>'></td>
				</tr>
                <tr>
					<th>
                     Иконка для верхнего меню
                    </th>
					<td><?=fileUpload("/uploads/{$tbl}/{$id}.png", 'name="file" style="width:80%"')?></td>
			    </tr>
            	<tr>
					<th>Ссылка</th>
					<td><input name="link" value='<?=$row['link']?>' <?=($row['readonly'] ? 'readonly' : '')?>></td>
					<td><?=help('Формируется автоматически')?></td>
				</tr>
				<tr>
					<th>В меню</th>
					<td><input name="menu" type="checkbox" <?=$row['menu'] ? 'checked' : ''?> style="width:auto;" value="1"></td>
				</tr>
				<tr>
					<th>В нижнее меню</th>
					<td><input name="menu_down" type="checkbox" <?=$row['menu_down'] ? 'checked' : ''?> style="width:auto;" value="1"></td>
				</tr>
				<tr>
					<th>Скрыть</th>
					<td><input name="hide" type="checkbox" <?=$row['hide'] ? 'checked' : ''?> style="width:auto;" value="1"></td>
				</tr>
                <tr>
                    <th>Сервисная</th>
                    <td><input name="is_service" type="checkbox" <?=$row['is_service'] ? 'checked' : ''?> style="width:auto;" value="1"></td>
                </tr>
			</table>
			
			<textarea name="text" toolbar="full" rows="40" style="width:100%;"><?=$row['text']?></textarea>
	        <p>* для  полноразмерного просмотра изображений необходимо проставлять в свойствах изображения класс «ff-img»</p>

			<table class="red" width="50%">
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
			</table>

            <table class="red" width="50%">
                <tr>
                    <th>приоритет sitemap</th>
                    <td><input name="sitemap_priority" value='<?=$row['sitemap_priority']?>'></td>
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
				<th sort="<?=getSort('name')?>">Название</th>
				<th sort="<?=getSort('menu')?>">В меню<div style="padding-right:25px;" align="center"><input type="checkbox" onClick="event.cancelBubble=true; if(sure()) { setCbTable(this,true); } else  return false;"></div></th>
				<th sort="<?=getSort('menu_down')?>">В нижнее меню<div style="padding-right:25px;" align="center"><input type="checkbox" onClick="event.cancelBubble=true; if(sure()) { setCbTable(this,true); } else  return false;"></div></th>
				<th sort="<?=getSort('hide')?>">Скрыть<div style="padding-right:25px;" align="center"><input type="checkbox" onClick="event.cancelBubble=true; if(sure()) { setCbTable(this,true); } else  return false;"></div></th>
				<th>Ссылка</th>
				<th></th>
			</tr>
		<? $tree = getTree("SELECT * FROM {$prx}{$tbl} ORDER BY {$sort}");
			$lastlevel = getLastLevel($tree);
			$level = 0;
			foreach((array)$tree as $vetka) 
			{
				$row = $vetka['row'];
				$id = $row['id'];
				$prefix = getPrefix($vetka['level']);
				if($level != $vetka['level'])
				{
					$level = $vetka['level'];
					echo '<tbody>';
				}	?>
				<tr id="tr<?=$id?>" onDblClick="location.href='?id=<?=$id?>&show=red'">
					<td><? if(!$row['readonly']) { ?><input type="checkbox" name="ids[]" value="<?=$id?>"><? } ?></td>
					<td align="right"><?=$id?></td>
					<td <?=(!$vetka['level'] ? 'style="font-weight:bold;"' : "")?>><span class="cg"><?=$prefix?></span><?=$row['name']?></td>
					<td align="center"><input type="checkbox" <?=($row['menu'] ? 'checked' : '')?> onClick="toAjax('?action=redone&id=<?=$id?>&field=menu&value='+(this.checked ? 1 : 0))"></td>
					<td align="center"><input type="checkbox" <?=($row['menu_down'] ? 'checked' : '')?> onClick="toAjax('?action=redone&id=<?=$id?>&field=menu_down&value='+(this.checked ? 1 : 0))"></td>
					<td align="center"><input type="checkbox" <?=($row['hide'] ? 'checked' : '')?> onClick="toAjax('?action=redone&id=<?=$id?>&field=hide&value='+(this.checked ? 1 : 0))"></td>
					<td><a href="<?=$row['link']?>" title="<?=$row['link']?>">открыть</a></td>
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