<?
// ------------------ИНИЦИАЛИЗАЦИЯ--------------------
require('common.php');

$tbl = clean($_GET['tbl']);
$id = clean($_GET['id']);
$ids = $_GET['ids'] ? (array)$_GET['ids'] : array($id);
$filesExt = array('jpg','png','gif','swf');
$_POST = $_SESSION[$tbl.'_post'];
$_REQUEST = array_merge($_REQUEST, (array)$_SESSION[$tbl.'_post']);

// -------------------СОХРАНЕНИЕ----------------------
switch($action)
{
	case 'redone':
		foreach($_REQUEST as $key=>$val)
			$$key = clean($val);
		if($field == 'price') $value = str_replace(array(' ','р.',','), array('','','.'), $value);
        //if ($field=='status')
        //  ch_status($id,$value);
        if ($field=='remont')
        {
          ch_remont($id,$value);
          $field='status';
        }   
        
		if($id) update($tbl, "`{$field}`='{$value}'", $id);
		if(isset($show_value)) echo $field=='price' ? number_format($value,2,',',' ').' р.' : nl2br(stripslashes($value));
		break;

	// дублирование записи
	case 'copy':
		$res = sql("SELECT * FROM {$prx}{$tbl} WHERE id IN ('".implode("','",$ids)."')");
		while($row = mysql_fetch_assoc($res))
		{
			$set = 'id=NULL';
			if($tbl == 'settings') // таблица настройки
				$set = "id='{$row['id']}_copy'";
			foreach($row as $key=>$val)
            {
				if ($key=='article')
                  $val=$val.'_'.rand();
                if ($key=='link')
                    $val=$val.'_'.rand();
                  
                if(!strpos(',,id,order,visit,', ",{$key},"))
					$set .= ", `{$key}`='".clean($val)."'";
            }        
			
			$id_new = update($tbl, $set);
			
            //$c_link=makeUrl($row['name'].'_'.$id_new);
            //update($tbl, "link='{$c_link}.htm'", $id_new);
            
            /*
            if($row['link'])
            {
        	  if (substr($row['link'],-4)=='.htm')
              {
               $c_link=substr($row['link'],0,strlen($row['link'])-4);
               update($tbl, "link='{$c_link}_{$id_new}.htm'", $id_new);
              }                
            }*/
            
			if($tbl == 'goods')
			{
			    //---размерности и коэффициенты
                $res1 = sql("SELECT * FROM {$prx}price WHERE id_goods='{$row['id']}'");
				while($row1 = mysql_fetch_assoc($res1))
				{
				  $set = "id_goods='{$id_new}'";
                  foreach($row1 as $key=>$val)
						if(!strpos(',,id,id_goods,', ",{$key},"))
							$set .= ", `{$key}`='".clean($val)."'";  
                
                 //update('price', $set);
                }
             
             
				// добавляем хар-ки
				$res1 = sql("SELECT * FROM {$prx}features_vals WHERE id_goods='{$row['id']}'");
				while($row1 = mysql_fetch_assoc($res1))
				{
					$set = "id=NULL, id_goods='{$id_new}'";
					foreach($row1 as $key=>$val)
						if(!strpos(',,id,id_goods,', ",{$key},"))
							$set .= ", `{$key}`='".clean($val)."'";
					update('features_vals', $set);
				}
			}

			foreach($filesExt as $ext)
			{
				@copy("{$DR}/uploads/{$tbl}/{$row['id']}.{$ext}", "{$DR}/uploads/{$tbl}/{$id_new}.{$ext}");
				for($i=1; $i<99; $i++)
					@copy("{$DR}/uploads/{$tbl}/{$row['id']}_{$i}.{$ext}", "{$DR}/uploads/{$tbl}/{$id_new}_{$i}.{$ext}");
			}
		}
		?><script>location.reload();</script><?
		break;

	// обновление ссылок
	case 'link':
		$res = sql("SELECT id,`name` FROM {$prx}{$tbl} WHERE `name`<>'' AND `name` IS NOT NULL");
		//$res = sql("SELECT id,`name` FROM {$prx}{$tbl} WHERE `link`='' OR `link` IS NULL OR link LIKE '% %'");
		while($row = mysql_fetch_assoc($res))
			update($tbl, "link='".makeUrl($row['name'])."'", $row['id']);
		do	// проверка
		{
			$res = sql("SELECT id,link FROM {$prx}{$tbl} GROUP BY link HAVING COUNT(link)>1 AND link<>'' AND link IS NOT NULL");
			while($row = mysql_fetch_assoc($res))
				update($tbl, "link='{$row['link']}_{$row['id']}'", $row['id']);
		}
		while(mysql_num_rows($res));
		?><script>alert('Названия ссылок обновлены'); location.reload();</script><?
		break;

	// перемещение 
	case 'move':
    
		moveSort($tbl, $id, clean(@$_GET['move_group']));
		if(!isset($_GET['noreload']))
			echo '<script>location.reload();</script>';
		break;
		
	// удаление	
	case 'del':
		
      if ($ids)
      {  
        update($tbl, '', $ids);
		foreach($ids as $id)
		{
			foreach($filesExt as $ext)
			{			
				@unlink("{$DR}/uploads/{$tbl}/{$id}.{$ext}");
				for($i=1; $i<99; $i++)
					@unlink("{$DR}/uploads/{$tbl}/{$id}_{$i}.{$ext}");
			}
		}
      }  
		if(!isset($_GET['noreload']))
			echo '<script>location.reload();</script>';
		break;
	
	// кол-во выводимых элементов
	case 'set_k':
		$_SESSION[$tbl.'_k'] = (int)@$_GET['k'];
		?><script>location.reload();</script><?
		break;

	// скрыть изображения в списке
	case 'set_hideimg':
		$_SESSION[$tbl.'_hideimg'] = (int)@$_GET['value'];
		?><script>location.reload();</script><?
		break;
	
	// открывашка для дерева	
	case 'open_ids':
		if(isset($_GET['open_ids']))
			$_SESSION[$tbl.'open_ids'] = clean($_GET['open_ids']);
		else
		{
			$open_ids = $_SESSION[$tbl.'open_ids'];
			if($open_ids == 'all')
				$open_ids = '0,'.implode(',', getArr("SELECT id FROM {$prx}{$tbl}"));
			$open_ids = ",,{$open_ids},";
			$_SESSION[$tbl.'open_ids'] = trim(strpos($open_ids, ",{$id},") ? str_replace(",{$id},", ',', $open_ids) : $open_ids.$id, ',');
		}
		?><script>location.reload();</script><?
		break;
}
unset($_SESSION[$tbl.'_post']);
?>