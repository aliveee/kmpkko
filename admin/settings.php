<?
// ------------------ИНИЦИАЛИЗАЦИЯ--------------------
require('inc/common.php');
$tbl = 'settings';
if(!$rubric_img) $rubric_img = 1088;
if(!$rubric) $rubric = 'Настройки';
if(!$top_menu) $top_menu = 'settings';
$id = mysql_escape_string(@$_GET['id']);
$ids = $_GET['ids'] ? (array)$_GET['ids'] : array($id);

// -------------------СОХРАНЕНИЕ----------------------
switch($action)
{
	case 'redall':
		foreach($_POST as $id=>$value)
		{
			$value = clean($value);
			update($tbl, "value='{$value}'", $id);
		}
		foreach($_FILES as $id=>$file)
		{
			$ext = strtolower(pathinfo($file['name'] ? $file['name'] : set($id), PATHINFO_EXTENSION));
			$value = upfile("../uploads/{$tbl}/".$id.'.'.$ext, $file, @$_POST['del_'.$id]?true:false);
			update($tbl, "value='{$value}'", $id);
		}
		$res = sql("SELECT * FROM {$prx}{$tbl} WHERE type='file_content'");
		while($row = mysql_fetch_assoc($res)) {
            file_put_contents($DR . '/' . $row['name'], $_POST[$row['id']]);
            //echo $row['name']."=".$_POST[$row['name']];
            //print_r($_POST);
        }
		?><script>top.topReload();</script><?
		exit;

	case 'red':
		$id_red = $id;
		foreach($_POST as $key=>$val)
			$$key = clean($val);
		
		update($tbl, "id='{$id}', name='{$name}', type='{$type}', value='{$value}', help='{$help}', hide='{$hide}'", $id_red); // $id = НЕ ПИСАТЬ !
		?><script>top.location.href = "?id=<?=$id?>&show=content&rand=<?=mt_rand()?>";</script><?
		exit;

	case 'del':
		foreach($ids as $id)
			@unlink('../uploads/settings/'.set($id));
		break;	
}
// остальные события
if($action)
{
	$_SESSION[$tbl.'_post'] = $_POST;
	$vars = array(
		'tbl' => $tbl,
		'action' => $action,
		'move_group' => 'hide'
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
			<table class="red" width="400">
				<tr>
					<th>ID</th>
					<td><input name="id" value='<?=$row['id']?>'></td>
				</tr>
				<tr>
					<th>Тип</th>
					<td><?=dllEnum($tbl, 'type', 'name="type"', $row['type'])?></td>
				</tr>
				<tr>
					<th>Название</th>
					<td><input name="name" value='<?=$row['name']?>'></td>
				</tr>
				<tr>
					<th>Значение</th>
					<td><textarea name="value"><?=$row['value']?></textarea></td>
				</tr>
				<tr>
					<th>Подсказка</th>
					<td><input name="help" value='<?=$row['help']?>'></td>
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
	<?	
		break;
	
	case 'content':
	?>
		<?=lnkAction('Add,Dels,Copy')?>
		<table class="content">
			<tr class="nodrop nodrag">
				<th><input type="checkbox" onClick="setCbTable(this)"></th>
				<th>ID</th>
				<th>Тип</th>
				<th>Название</th>
				<th>Скрыть</th>
				<th></th>
			</tr>
		<? $res = sql("SELECT * FROM {$prx}{$tbl} ORDER BY hide,sort,id");
			while($row = mysql_fetch_assoc($res))
			{
				$id = $row['id'];	?>
				<tr id="tr<?=$id?>" onDblClick="location.href='?id=<?=$id?>&show=red'">
					<td><input type="checkbox" name="ids[]" value="<?=$id?>"></td>
					<td><div class="redone" id="<?=$id?>" name="id"><?=$id?></div></td>
					<td><?=$row['type']?></td>
					<td><div class="redone" id="<?=$id?>" name="name"><?=$row['name']?></div></td>
					<td align="center"><input type="checkbox" <?=($row['hide'] ? 'checked' : '')?> onClick="toAjax('?action=redone&id=<?=$id?>&field=hide&value='+(this.checked ? 1 : 0))"></td>
					<td><?=lnkAction('Move,Red,Del')?></td>
				</tr>
		<?	}	?>
		</table>				  
	<?
		break;
	
	default: // просмотр
	?>	<form action="?action=redall" method="post" enctype="multipart/form-data" target="iframe">
			<table class="red" width="900">
			<?	$res = sql("SELECT * FROM {$prx}{$tbl} WHERE ".(@$spec_filter ? $spec_filter : "hide='0'")." ORDER BY sort,id");
				while($row = mysql_fetch_assoc($res))	
				{	
					$id = $row['id'];
					$value = $row['value'];	?>
					<tr id="tr<?=$id?>">
						<th title="<?=$id?>"><?=$row['name']?></th>
						<td>
						<?	switch($row['type'])
							{
								case 'text':	?>
									<input name="<?=$id?>" value='<?=$value?>'>
							<?		break;
			
								case 'password':	?>
									<input name="<?=$id?>" type="password" value='<?=$value?>'>
							<?		break;
			
								case 'checkbox':	?>
									<input name="<?=$id?>" id="<?=$id?>" type="hidden" value='<?=$value?>'>
									<input type="checkbox" <?=($value=='true' ? 'checked' : '')?> onClick="document.getElementById('<?=$id?>').value=this.checked;" style="width:auto;">					
							<?		break;
			
								case 'textarea':	?>
									<textarea name="<?=$id?>"><?=$value?></textarea>
							<?		break;
			
								case 'ck_basic':	?>
									<textarea name="<?=$id?>" toolbar="basic" rows="10"><?=$value?></textarea>
								<?	break;
			
								case 'ck_medium':	?>
									<textarea name="<?=$id?>" toolbar="medium" rows="35" style="width:570px;"><?=$value?></textarea>
								<?	break;
			
								case 'datetime':
								case 'date':
									echo aInput($row['type'], "name='{$id}'", $value);	
									break;
	
								case 'color':
									echo aInput('color', "name='{$id}'", $value);	
									break;
	
								case 'file':
									echo fileUpload("/uploads/{$tbl}/{$value}", "name='{$id}' style='width:80%'");	
									break;

								case 'file_content':	?>
									<textarea name="<?=$id?>"><?=file_get_contents('../'.$row['name'])?></textarea>
							<?		break;
							}	?>
						</td>
						<td style="width:1%;"><?=lnkAction('Move')?></td>
						<?	if($row['help']) { ?>
								<td><?=help($row['help'])?></td>
						<? } ?>
					</tr>
			<?	}	?>		
				<tr class="nodrop nodrag">  
					<td colspan="2" align="center"><?=btnAction('Save')?></td>
					<td onDblClick="location.href='?show=content'" style="border:none;"></td>
				</tr>
			</table>
		</form>			  
	<?	break;
}
$content = ob_get_clean();

if($spec_tbl) $tbl = $spec_tbl;

require('template.php');
?>