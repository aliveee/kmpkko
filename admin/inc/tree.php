<?
// ПОЛУЧЕНИЕ ДЕРЕВА
function getTree($sql, $id_parent=0, $depth=0, $level=0, &$rows=NULL) // $sql = "SELECT * FROM {$prx}{$tbl} ORDER BY sort,id", ветка с которой начинаем стоить дерево, "глубина" дерева, текущая глубина - не задается, массив таблицы - не задается
{
	global $trees;
	if(is_null($rows))
	{
		if($trees[$sql])
			return $trees[$sql];
		$rows = $tree = array();
		$res = sql($sql);
        
		while($row = mysql_fetch_assoc($res))
        {
          //--если несколько родителей в одной ячейке ----
          $st=explode(',',$row['id_parent']);
          foreach($st as $r)
            $rows[$r][] = $row;  
        }
	}	
    
	if(!$depth || $depth>$level)
		foreach((array)$rows[$id_parent] as $row)
		{
			$tree[] = array('level'=>$level, 'row'=>$row);
			$tree = array_merge($tree, (array)getTree('', (int)$row['id'], $depth, $level+1, $rows));
		}
        
	$trees[$sql] = $tree;
	return $tree;
}
// префикс относительно уровня вложенности дерева
function getPrefix($level=0, $prefix='&raquo; ') 
{
	$prefix = str_repeat('&mdash; ',$level).$prefix;
	return $prefix;
}
// получаем последний уровень дерева
function getLastLevel($tree)
{
	$lastlevel = 0;
	foreach((array)$tree as $vetka)
		if($vetka['level'] > $lastlevel)
			$lastlevel = $vetka['level'];
	return $lastlevel;
}

// возвращает id ветки и всех ее подветок
function getIdChilds($sql, $id=0, $arr=true) // $sql = "SELECT id,id_parent FROM {$prx}{$tbl}", id ветки, возврещать в виде массива/строки
{
	$childs = array();
	$id = (int)$id;
	$childs[] = $id;
	$tree = getTree($sql); // строим дерево целиком (т.к. оно будет закешировано)
	$level = -1;
	foreach((array)$tree as $vetka)
	{
		if($level > -1 || !$id)
		{
			if($level >= $vetka['level'])
				break;
			else
				$childs[] = $vetka['row']['id'];
		}
		if($id == $vetka['row']['id'])
		{
			$level = $vetka['level'];
		}
	}
	return $arr	? $childs : implode(',', $childs);
}

// возвращает массив ветки и всех ее родителей
function getArrParents($sql, $id=0) // $sql = "SELECT id,id_parent FROM {$prx}{$tbl}", id ветки
{
	$tree = array_reverse(getTree($sql));
	$arr = array();
	$flag = false;
	$level = 9999;
	foreach($tree as $vetka)
	{
		if($vetka['row']['id'] == $id)
			$flag = true;
		if($flag)
		{
			if($vetka['level'] < $level)
			{
				$arr[] = $vetka['row'];
				$level = $vetka['level'];
			}
			if(!$vetka['level'])
				return array_reverse($arr);
		}
	}
}
// возвращает id ветки и всех ее родителей
function getIdParents($sql, $id=0, $arr=true) // $sql = "SELECT id,id_parent FROM {$prx}{$tbl}", id ветки, возврещать в виде массива/строки
{
	$ids = array();
	$parents = getArrParents($sql, $id);
	foreach ($parents as $row)
		if($row['id'])
			$ids[] = $row['id'];
	return $arr	? $ids : implode(',', $ids);
}

// ВЫПАДАЮЩИЙ СПИСОК ДЛЯ ДЕРЕВА
function dllTree($sql, $properties, $value='', $default=NULL, $hidevalue='', $id_parent=0, $depth=0, $prefix=NULL) // $sql = "SELECT id, name, id_parent FROM {$prx}{$tbl} ORDER BY sort,id", св-ва списка, значение (может быть массивом), "пустое" значение(может быть массивом),  значение скрываемой рубрики (и ее подрубрик), id начала веток, глубина дерева, свой префикс
{ 
	ob_start();
?>
	<select <?=$properties?>>
	<?	if($default !== NULL)
			if(is_array($default)) {	?>
				<option value="<?=$default[0]?>"><?=$default[1]?></option>
		<?	} else { ?>
				<option value=""><?=$default?></option>
		<?	}
		if($tree = getTree($sql, $id_parent, $depth))
			foreach ($tree as $vetka) 
			{
				$row = array();
				foreach($vetka['row'] as $val)
					$row[] = $val;
				$level = $vetka['level'];
				
				// не выводим скрываемую рубрику и ее подрубрики
				if($row[0] == $hidevalue)
				{
					$hide_pages_level = $level;
					continue;
				}
				if(isset($hide_pages_level) && $hide_pages_level < $level)
					continue;
				else
					unset($hide_pages_level);
				
				$prx = $prefix===NULL ? getPrefix($level) : str_repeat($prefix, $level);
				$selected = is_array($value) ? in_array($row[0], $value) : $row[0]==$value;
			?>					
				<option value="<?=$row[0]?>"<?=($selected ? ' selected' : '')?>><?=$prx.$row[1]?></option>
		<?	}	?>				
	</select>
<? 	
	return ob_get_clean();
}

// ОБЩЕЕ КОЛИЧЕСТВО ТОВАРОВ В РАЗДЕЛЕ (ВКЛЮЧАЯ ПОДРАЗДЕЛЫ)
// $tree = getTree("SELECT * FROM {$prx}catalog WHERE hide='0'");
// $count = getArr("SELECT id_catalog, COUNT(id) AS c FROM {$prx}goods WHERE hide='0' GROUP BY id_catalog");
function getCountGoods($id_catalog, $tree, $count) // id раздела, дерево каталога, массив кол-ва товаров в каждом разделе
{
	$flag = false;
	foreach($tree as $vetka)
	{
		if($vetka['row']['id']==$id_catalog)
		{
			$flag = true;
			$max_level = $vetka['level'];
			$c = $count[$vetka['row']['id']];
		}
		elseif($flag)
		{
			if($max_level < $vetka['level'])
				$c += $count[$vetka['row']['id']];
			else
				break;
		}
	}
	return (int)$c;
}
?>