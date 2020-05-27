<?
// ------------------ИНИЦИАЛИЗАЦИЯ--------------------
require('inc/common.php');
$tbl = 'feedback';
$rubric_img = 1408;
$rubric = 'Сообщения';
$id = (int)@$_GET['id'];
$ids = $_GET['ids'] ? (array)$_GET['ids'] : array($id);
$search = clean(@$_GET['search']);
$k = @$_SESSION[$tbl.'_k'] ? $_SESSION[$tbl.'_k'] : 50;
$p = @$_GET['p'] ? $_GET['p'] : 1;
$sort = setSort('date DESC');
$sqlmain = "SELECT * FROM {$prx}{$tbl} WHERE 1 ".($search ? 'AND '.getWhere('date,message') : '')." ORDER BY {$sort}";

// -------------------СОХРАНЕНИЕ----------------------
switch($action)
{
	case 'red':
		$id = uniUpdate($id);
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
	?>	<form action="?id=<?=$id?>&action=red" method="post" enctype="multipart/form-data" target="iframe">
			<table class="red" width="500">
				<tr>
					<th>Дата</th>
					<td><?=date('d.m.Y H:i:s', strtotime($row['date']))?></td>
				</tr>
                <tr>
                    <th>Телефон</th>
                    <td><?=$row['phone']?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?=$row['email']?></td>
                </tr>
				<tr>
					<th>Текст</th>
					<td><?=$row['message']?></td>
				</tr>
				<tr>
					<th>Комментарий</th>
					<td><textarea name="note"><?=$row['note']?></textarea></td>
				</tr>
				<tr>
					<td align="center" colspan="2"><?=btnAction()?></td>
				</tr>
			</table>
		</form>			  
	<?	break;

	default: // просмотр
	?>
		<?=lnkAction('Dels')?>
		<?=showK($k)?>
		<table class="content">
			<tr>
				<th><input type="checkbox" onClick="setCbTable(this)"></th>
				<th sort="<?=getSort('date')?>">Дата</th>
                <th sort="<?=getSort('phone')?>">Телефон</th>
                <th sort="<?=getSort('email')?>">Email</th>
				<th sort="<?=getSort('text')?>">Текст</th>
				<th sort="<?=getSort('note')?>">Комментарий</th>
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
					<td align="center"><?=date('d.m.Y H:i:s', strtotime($row['date']))?></td>
                    <td><?=$row['phone']?></td>
                    <td><?=$row['email']?></td>
					<td><?=$row['message']?></td>
					<td><?=nl2br($row['note'])?></td>
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