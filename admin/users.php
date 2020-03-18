<?
// ------------------ИНИЦИАЛИЗАЦИЯ--------------------
require('inc/common.php');
$tbl = 'user';
$rubric_img = 2944;
$rubric = 'Учетные записи';
$top_menu = "users";
$id = (int)@$_GET['id'];
$ids = $_GET['ids'] ? (array)$_GET['ids'] : array($id);
$search = clean(@$_GET['search']);
//$top_menu = 'users';

$k = @$_SESSION[$tbl.'_k'] ? $_SESSION[$tbl.'_k'] : 50;
$p = @$_GET['p'] ? $_GET['p'] : 1;
$sort = setSort('date DESC');
$sqlmain = "SELECT * FROM {$prx}{$tbl} WHERE 1 ".($search ? 'AND '.getWhere('phone,email,name') : '')." ORDER BY {$sort}";

// -------------------СОХРАНЕНИЕ----------------------
switch($action)
{
	case "saveall":
            foreach($_POST["id"] as $id=>$none)
			{
				$moder = @$_POST['moder'][$id];
                update($tbl, "moder='{$moder}'", $id);
			}
            
			?><script>
              top.topReload();
             </script><?
			exit;

	case 'red':
		$_POST['login'] = $_POST['email'];
		foreach($_POST as $key=>$val)
			$$key = clean($val);
	
		// проверки
		if(getField("SELECT COUNT(*) AS c FROM {$prx}{$tbl} WHERE id<>'{$id}' AND email='{$email}'"))
			errorAlert('Пользователь с таким E-mail уже зарегистрирован');

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
		//$info = arr($row['info']);
		//$lico = $row['ur'] ? 'ur' : 'phys';
	?>
        <form action="?id=<?=$id?>&action=red" method="post" enctype="multipart/form-data" target="iframe">
		<input type="hidden" name="moder" value="1" />
			<table class="red" width="600">
				<tr>
					<th>Дата регистрации</th>
					<td><?=aInput('DateTime', 'name="date" style="width:50%"', ($row['date']>0 ? date('d.m.Y H:i:s', strtotime($row['date'])) : date('d.m.Y H:i:s')))?></td>
				</tr>
                <tr>
                    <th>Фамилия</th>
                    <td><input name="surname" value="<?=$row['surname']?>"></td>
                </tr>
                <tr>
                    <th>Имя</th>
                    <td><input name="name" value="<?=$row['name']?>"></td>
                </tr>
                <tr>
                    <th>Отчество</th>
                    <td><input name="patronymic" value="<?=$row['patronymic']?>"></td>
                </tr>

				<tr>
					<th>E-mail</th>
					<td><input name="email" value="<?=$row['email']?>"></td>
				</tr>
                <tr>
                    <th>Телефон</th>
                    <td><input name="phone" value="<?=$row['phone']?>"></td>
                </tr>
                <tr>
                    <th>Покупатели</th>
                    <td><a href="/admin/profiles.php?id_user=<?=$row["id"]?>">Смотреть</a></td>
                </tr>
                <!--<tr>
					<th>Пароль</th>
					<td><input name="pwd" value="<?=$row['password']?>"></td>
				</tr>
				<tr>
					<th>Скидка</th>
					<td><input name="discount" value="<?=$row['discount']?>" style="width:auto; text-align:right;">%</td>
				</tr>
				<tr>
					<th></th>
					<td>
						<label><input type="radio" name="customer_type" value="1" id="customer_type1" <?=$row["customer_type"]==1?"checked":""?> oonClick="$('#user_data').html($('#user_data'+this.value).html());" style="width:auto;"> физическое лицо</label> &nbsp; &nbsp;
						<label><input type="radio" name="customer_type" value="2" id="customer_type2" <?=$row["customer_type"]==2?"checked":""?> oonClick="$('#user_data').html($('#user_data'+this.value).html());" style="width:auto;"> юридическое лицо</label>
					</td>
				</tr>
                <tr>
                    <th>Город доставки</th>
                    <td><input name="city" value="<?=$row['city']?>"></td>
                </tr>
                <tr>
                    <th>Улица доставки</th>
                    <td><input name="street" value="<?=$row['street']?>"></td>
                </tr>
                <tr>
                    <th>Дом доставки</th>
                    <td><input name="house" value="<?=$row['house']?>"></td>
                </tr>
                <tr>
                    <th>Корпус доставки</th>
                    <td><input name="block" value="<?=$row['block']?>"></td>
                </tr>
                <tr>
                    <th>Офис/квартира доставки</th>
                    <td><input name="office" value="<?=$row['office']?>"></td>
                </tr>
				<tbody id="user_data"></tbody>-->
				<tr>
					<td align="center" colspan="2"><?=btnAction()?></td>
				</tr>
			</table>
		</form><?

    /*
		?><table style="display:none;">
		<?	foreach($user_data as $ur=>$data)
			{	?>
				<tbody id="user_data<?=$ur+1?>">
				<?	foreach($data as $val) { ?>
						<tr>
							<th><?=$val?></th>
							<td>
							<?	if($val == 'Реквизиты организации') { ?>
									<textarea name="info[<?=$val?>]"><?=$info[$val]?></textarea>
							<?	} else { ?>
									<input name="info[<?=$val?>]" value="<?=$info[$val]?>">
							<?	}	?>
							</td>
						</tr>
				<?	}	?>
				</tbody>
		<?	}	?>
		</table>
		<script>$('#customer_type<?=(int)$row['customer_type']?>').click();</script>
	<?
    */
		break;

	default:	// просмотр
		$users_group = getArr("SELECT id,name FROM {$prx}user_group");
	?>
		<?=lnkAction('Add,Dels,Copy')?>
		<?=showK($k)?>
	<form action="?action=saveall" target="iframe" method="post" id="frmContent">
		<table class="content">
			<tr>
				<th><input type="checkbox" onClick="setCbTable(this)"></th>
				<th sort="<?=getSort('id')?>">ID</th>
				<th sort="<?=getSort('date')?>">Дата регистрации</th>
				<th>Ф.И.О.</th>
				<th sort="<?=getSort('email')?>">E-mail</th>
				<th>Телефон</th>
                <th>Покупатели</th>
				<!--<th sort="<?=getSort('skidks')?>">Скидка</th>-->
				<!--<th>Заказов</th>-->
				<!--<th>Подтвержден</th>
				<th></th>-->
				<th></th>
			</tr>
		<? 
			$res = sql($sqlmain.' LIMIT '.($p-1)*$k.', '.$k);
			while($row = mysql_fetch_assoc($res))
			{
				$id = $row['id'];
				$info = arr($row['info']);
			?>
				<tr id="tr<?=$id?>" onDblClick="location.href='?id=<?=$id?>&show=red'">
					<td><input type="checkbox" name="ids[]" value="<?=$id?>"></td>
					<td><input name="id[<?=$id?>]" type="hidden" ><?=$row['id']?></td>
					<td><?=date('d.m.Y', strtotime($row['date']))?></td>
					<td><?=$row['name']?></td>
					<td><a href="mailto:<?=$row['email']?>"><?=$row['email']?></a></td>
					<td><?=$row['phone']?></td>
					<!--<td align="right"><?=$row['discount']?>%</td>
					<td align="center"><a href="order.php?id_users=<?=$id?>"><?=getField("SELECT COUNT(*) AS c FROM {$prx}order WHERE id_user='{$id}'")?></a></td>-->
                    <!--<td><input type="checkbox" name="moder[<?=$id?>]" value="1" <?=$row['moder']==1?'checked':''?> /></td>
                	<td><a href="/cabinet.php?action=login&login=<?=$row['login']?>&pwd=<?=$row['pwd']?>">войти</a></td>-->
                    <td><a href="/admin/profiles.php?id_user=<?=$row["id"]?>">Смотреть</a></td>
					<td><?=lnkAction('Red,Del')?></td>
				</tr>
		<?	}	?>
			<tr>
				<td colspan="99" align="center"><?=lnkPages($sqlmain, $p, $k, '?p=%s'.getQS())?></td>
			</tr>
              <tr><td colspan="99" align="right"><?=btnAction()?></td></tr>            
		</table>
      </form>  	
	<?	break;
}
$content = ob_get_clean();

require('template.php');
?>