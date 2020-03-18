<?
// ------------------ИНИЦИАЛИЗАЦИЯ--------------------
require('inc/common.php');
$tbl = 'priv';
$rubric_img = 1344;
$rubric = 'Права доступа';
$id = (int)@$_GET['id'];
$ids = $_GET['ids'] ? (array)$_GET['ids'] : array($id);

$sort = setSort('login');

// собираем ссылки из админской менюшки
$privArr = array();
preg_match_all('~<a.*?href="([^"]+)".*?>(.*?)</a>~s', $left_menu, $menu); // левое меню
foreach($menu[0] as $key=>$lnk)
{
	$file = basename(parse_url(trim($menu[1][$key]), PHP_URL_PATH));
	$name = trim($menu[2][$key]);
	$privArr[$file] = $name;
	// верхнее меню
	$topMenu = explode('top_menu="', $lnk);
	$topMenu = explode('"', $topMenu[1]);
	preg_match_all('~<a.*?href="([^"]+)".*?>(.*?)</a>~s', topMenu($topMenu[0]), $menu1);
	foreach($menu1[0] as $key=>$lnk)
	{
		$file1 = basename(parse_url(trim($menu1[1][$key]), PHP_URL_PATH));
		$name1 = trim($menu1[2][$key]);
		$privArr[$file1] = $name == $name1 ? $name : $name.' &raquo; '.$name1;
	}
}


// -------------------СОХРАНЕНИЕ----------------------
switch($action)
{
	case 'red':
		$_POST['priv'] = implode(',', $_POST['priv']);
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
			<table class="red" width="550">
				<tr>
					<th>E-mail</th>
					<td><input name="email" value='<?=$row['email']?>'></td>
					<td><?=help('Не обязательно для заполнения. Нужен для восстановления пароля')?></td>
				</tr>
				<tr>
					<th>Логин</th>
					<td><input name="login" value='<?=$row['login']?>'></td>
				</tr>
				<tr>
					<th>Пароль</th>
					<td><input name="pwd" value='<?=$row['pwd']?>'></td>
				</tr>
				<tr>
					<th>Права доступа</th>
					<td>
						<table class="vert">
						<?	$arr = explode(',', $row['priv']);
							foreach($privArr as $key=>$val)
							{	?>
								<tr>
									<th><input type="checkbox" value="<?=$key?>" <?=in_array($key, $arr) ? 'checked' : ''?> name="priv[]" style="width:auto;"></th>
									<td><?=$val?></td>
								</tr>
						<?	}	?>
						</table>
					</td>
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
			<tr>
				<th><input type="checkbox" onClick="setCbTable(this)"></th>
				<th sort="<?=getSort('email')?>">E-mail</th>
				<th sort="<?=getSort('login')?>">Логин</th>
				<th></th>
				<th></th>
			</tr>
		<? 
			$res = sql("SELECT * FROM {$prx}{$tbl} ORDER BY {$sort}");
			while($row = mysql_fetch_assoc($res))
			{
				$id = $row['id'];
			?>
				<tr id="tr<?=$id?>" onDblClick="location.href='?id=<?=$id?>&show=red'">
					<td><input type="checkbox" name="ids[]" value="<?=$id?>"></td>
					<td><a href="mailto:<?=$row['email']?>"><?=$row['email']?></a></td>
					<td><div class="redone" id="<?=$id?>" name="login"><?=$row['login']?></div></td>
					<td><a href="login.php?action=vhod&login=<?=$row['login']?>&pwd=<?=$row['pwd']?>">войти</a></td>
					<td><?=lnkAction('Red,Del')?></td>
				</tr>
		<?	}	?>
		</table>
	<?	break;
}
$content = ob_get_clean();

require('template.php');
?>