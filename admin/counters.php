<?
// ------------------ИНИЦИАЛИЗАЦИЯ--------------------
require('inc/common.php');
$tbl = 'counter';
$rubric_img = 512;
$rubric = 'Счетчики';
$id = (int)@$_GET['id'];
$ids = $_GET['ids'] ? (array)$_GET['ids'] : array($id);

// -------------------СОХРАНЕНИЕ----------------------
switch($action)
{
	case 'red':
		$id = uniUpdate($id);
		?><script>top.location.href = "?id=<?=$id?>&rand=<?=mt_rand()?>";</script><?
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
			<table class="red" width="600">
				<tr>
					<th>Код счетчика</th>
					<td><textarea name="html" rows="10"><?=$row['html']?></textarea></td>
				</tr>
				<tr>
					<th>Примечание</th>
					<td><textarea name="note" rows="5"><?=$row['note']?></textarea></td>
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
		<table class="content">
			<tr class="nodrop nodrag">
				<th><input type="checkbox" onClick="setCbTable(this)"></th>
				<th>Счетчик</th>
				<th>Примечание</th>
				<th></th>
			</tr>
		<? $res = sql("SELECT * FROM {$prx}{$tbl} ORDER BY sort,id");
			while($row = mysql_fetch_assoc($res))
			{
				$id = $row['id'];
			?>
				<tr id="tr<?=$id?>" onDblClick="location.href='?id=<?=$id?>&show=red'">
					<td><input type="checkbox" name="ids[]" value="<?=$id?>"></td>
					<td><?=$row['html']?></td>
					<td><?=nl2br($row['note'])?></td>
					<td><?=lnkAction('Move,Red,Del')?></td>
				</tr>
		<?	}	?>
		</table>
	<?	break;
}
$content = ob_get_clean();

require('template.php');
?>