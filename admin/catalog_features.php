<?
// ------------------ИНИЦИАЛИЗАЦИЯ--------------------
require('inc/common.php');
$id_catalog = (int)@$_GET['id_catalog'];

if (!$id_catalog) {
	die();
}

$catalogFeatures = getField("SELECT ids_features FROM {$prx}catalog WHERE id = '{$id_catalog}'");

if (!$catalogFeatures)
{
   $catalogFeatures=getField("select GROUP_CONCAT(id_feature) from {$prx}feature_catalog where id_catalog='{$id_catalog}'");
}

$catalogFeatures = explode(',', $catalogFeatures);


// -------------------СОХРАНЕНИЕ----------------------
switch($action)
{
	case 'save':
		$fs = '';
		if (isset($_POST['f']) && is_array($_POST['f']) && count($_POST['f']) > 0) {
			$fs = implode(',', $_POST['f']);

			sql("delete from {$prx}feature_catalog where id_catalog='$id_catalog'");
			foreach($_POST['f'] as $f){
                update('feature_catalog', "id_feature = '{$f}', id_catalog='$id_catalog'", 0);
            }
		}
		update('catalog', "ids_features = '{$fs}'", $id_catalog);
		?>
		<script type="text/javascript">
			window.close();
		</script>
		<?php
		exit;
}

// ----------------------ВЫВОД------------------------
ob_start();
switch($show)
{
	default:	// просмотр
		?>
		<form method="post" action="catalog_features.php?action=save&id_catalog=<?=$id_catalog?>">
		<table class="content">
			<tr class="nodrop nodrag">
				<th>Характеристика</th>
				<th>&nbsp;</th>
			</tr>
			<?
			$res = sql("SELECT id,name FROM {$prx}feature ORDER BY sort, name");
			while($row = mysql_fetch_assoc($res)) {
				?>
				<tr>
					<td><?=$row['name']?></td>
					<td><input type="checkbox" name="f[<?=$row['id']?>]" value="<?=$row['id']?>" <?=in_array($row['id'], $catalogFeatures) ? 'checked' : ''?>></td>
				</tr>
				<?
			}
			?>
			<tr>
				<td colspan="2" align="center"><input type="submit" value="Сохранить"></td>
			</tr>
		</table>
		</form>
	<?	break;
}
$content = ob_get_clean();

require('tpl_clean.php');
?>