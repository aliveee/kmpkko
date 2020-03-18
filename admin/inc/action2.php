<?
// ------------------ИНИЦИАЛИЗАЦИЯ--------------------
require('/admin/inc/common.php');

// -------------------СОХРАНЕНИЕ----------------------
switch($action)
{   
  case 'search_city':
		$query = win2utf($_GET['query']);
		$name = clean($query);
		$sql = "SELECT DISTINCT name
				FROM {$prx}city_virt
				WHERE name LIKE '{$name}%'
				ORDER BY name";
		$suggestions = getArr($sql);
		$arr = array(
		  'query' => $query,
		  'suggestions' => $suggestions
		);
		echo json_encode($arr);
		exit;


	case 'city_site':
		// все произойдет в common.php
        if(!isset($_GET['nocheck']) && strcmp(mb_strtoupper($_SESSION['city']['name']), mb_strtoupper(clean(win2utf(@$_GET['city_site'])))))
			exit;
            
		if(!isset($_GET['noreload'])) {
	      ?><script>location.reload();</script><?
		} else {
	      ?><script>location.href = '/';</script><?
		}
		exit;

    case 'geo-diler':
    
       ?>
         <div class="ajaxMapShops dspl_n" style="display: none;">
          <?
           function getCoord($id=0,$name='')
           {
             $xml=simplexml_load_file("http://geocode-maps.yandex.ru/1.x/?results=1&geocode=".urldecode($name));
             $position=$xml->GeoObjectCollection->featureMember->GeoObject->Point->pos;
             $p=explode(" ",$position);
             $coord='['.$p[1].','.$p[0].']';
             
             update('shops',"coord='{$coord}'",$id);
             
             return $coord;
           }
           
           $res=mysql_query("select * from {$prx}shops where hide=0");
           while ($row=mysql_fetch_array($res)){
             $coord=$row['coord']?$row['coord']:getCoord($row['id'],$row['fullName']);
             ?>
              <div class="fs-13 shopPopUp" data-represent="<?=$row['id']?>" data-coords="<?=$coord?>" data-shortname="<?=$row['shortName']?>"> <div class="metroDot  dspl_fl mr-5" style="margin-top:4px;"></div> <div style="width: 180px;color:#000;" class="lh-16 dspl_ib align-l">  <?=$row['fullName']?> <div class="c-gray6 lh-18 mt-7"><?=$row['info']?></div></div> </div>            
             <?  
           }
          ?>
          
          
         </div>       
       <?
    
    exit();
    
	case 'sort':
		$_SESSION['sort'] = mysql_escape_string(@$_GET['sort']);
    ?><script>location.reload();</script><?
    exit;
		
	case 'sort_fb':
		$_SESSION['sort_fb'] = mysql_escape_string(@$_GET['sort']);
		$_SESSION['show_fb'] = true;
    ?><script>location.reload();</script><?
    exit;
		
	case 'fb':
		$field = clean($_GET['field']);
		$id = (int)$_GET['id'];
		$ip_pm = arr(getField("SELECT ip_pm FROM {$prx}otzivy WHERE id='{$id}'"));
		if(in_array($_SERVER['REMOTE_ADDR'], $ip_pm) || !$id)
			exit;
		$ip_pm[] = $_SERVER['REMOTE_ADDR'];
		$ip_pm = cleanArr($ip_pm);
		update('otzivy', "ip_pm='{$ip_pm}', {$field}={$field}+1", $id);
		?><script> $('#fb_usefull').html('<?=cleanJS(showFBUsefull($id))?>'); </script><?
    exit;
		
	case 'k':
		$_SESSION['k'] = mysql_escape_string(@$_GET['k']);
    ?><script>location.reload();</script><?
    exit;
		
	case 'show_as':
		$_SESSION['show_as'] = mysql_escape_string(@$_GET['show_as']);
    ?><script>location.reload();</script><?
    exit;

	case 'search':
		$query = $search = $_GET['query'];
		$name = clean($query);
          
		$suggestions_cats = getArr("SELECT DISTINCT name FROM {$prx}catalog WHERE name LIKE '%{$name}%' and hide=0 ORDER BY name LIMIT 10");
		$suggestions = getArr("SELECT DISTINCT CONCAT(article,':',name) FROM {$prx}goods WHERE (kod = '{$name}' OR name LIKE '%{$name}%' OR article LIKE '%{$name}%') and hide=0 and price>0 ORDER BY name LIMIT 10");
//pr($suggestions);
		if(!$suggestions)
		{
			$suggestions = getArr("SELECT DISTINCT CONCAT(article,':',name) FROM {$prx}goods WHERE ".getWhere('name,article,kod')." and hide=0 and price>0 ORDER BY name LIMIT 10");
//pr("SELECT DISTINCT CONCAT(article,':',name) FROM {$prx}goods WHERE ".getWhere('name,article,kod')." and hide=0 and price>0 ORDER BY name LIMIT 10"); exit;
		}
		
        $sugg2=array();
        //$sugg2[]='<hr style="margin:0px;">';
        
       // echo implode('|',$suggestions);
        //$suggestions[10]="<div style='position:absolute;bottom:0px;'><a href='/search.php?search={$name}' class='a_search'>Все результаты поиска</a></div>";
        
        $suggestions=array_merge($suggestions_cats,$sugg2,$suggestions);
        //$suggestions=array_merge($suggestions_cats,$suggestions);
        
		$arr = array(
			'query' => $query,
			'suggestions' => $suggestions
		);
		echo json_encode($arr);
       
		exit;
  
 	case 'search_onlyg':
		$query = $search = $_GET['query'];
		$name = clean($query);
          
		$suggestions = getArr("SELECT DISTINCT CONCAT(article,':',name) FROM {$prx}goods WHERE ".getWhere('name,article,kod')." and (hide=0 OR hide IS NULL) ORDER BY name LIMIT 10");
		
		$arr = array(
			'query' => $query,
			'suggestions' => $suggestions
		);
		echo json_encode($arr);
       
		exit; 
        
	case 'search_g':
		$query = $_GET['query'];
		$name = clean($query);
        $res=mysql_query("SELECT DISTINCT id,name,price FROM {$prx}goods WHERE (kod = '{$name}' OR name LIKE '%{$name}%') ORDER BY name LIMIT 10");
        while ($row=mysql_fetch_array($res))
        {
           //$mas_values[]=$row['id'].':::'.$row['price'];
           //$mas_data[]=$row['name'];
          $val=$row['id'].':::'.$row['price'];  
          $suggestions[]=array('data'=>$val,'value'=>$row['name']);
        }
        
            
		//$suggestions = getArr("SELECT DISTINCT name,price FROM {$prx}goods WHERE (kod = '{$name}' OR name LIKE '%{$name}%') ORDER BY name LIMIT 10");
		$arr = array(
			'query' => $query,
			'suggestions' => $suggestions
		);
		echo json_encode($arr);
		exit;        
	
	case 'search_with_color':
		$query = $_GET['query'];
		$name = clean($query);
        $res=mysql_query("SELECT DISTINCT id,name,price,color FROM {$prx}goods WHERE (kod = '{$name}' OR name LIKE '%{$name}%') ORDER BY name LIMIT 10");
        while ($row=mysql_fetch_array($res))
        {
           //$mas_values[]=$row['id'].':::'.$row['price'];
           //$mas_data[]=$row['name'];
          $val=$row['id'].':::'.$row['price']; 
          $color=getField("select name from {$prx}color where id='{$row['color']}'"); 
          $suggestions[]=array('data'=>$val,'value'=>$row['name'].($color?'('.$color.')':''));
        }
        
            
		//$suggestions = getArr("SELECT DISTINCT name,price FROM {$prx}goods WHERE (kod = '{$name}' OR name LIKE '%{$name}%') ORDER BY name LIMIT 10");
		$arr = array(
			'query' => $query,
			'suggestions' => $suggestions
		);
		echo json_encode($arr);
		exit;     
    
    
	case 'delivery':
     
      $good_id=$_GET['id'];
      $kol=$_GET['quant'];
      
      $del=delivery_function($good_id,$kol);
      echo json_encode($del);
          
    exit;
   
    
    case 'allmaker':
		$query = $_GET['query'];
		$name = clean($query);
		$suggestions = getArr("SELECT name FROM {$prx}makers ORDER BY name");
		$arr = array(
			'query' => $query,
			'suggestions' => $suggestions
		);
		echo json_encode($arr);
		exit;
	
	case 'cities':
		$id = (int)@$_GET['id'];
		$_SESSION['id_cities'] = getField("SELECT id FROM {$prx}cities WHERE id='{$id}'");
		if(!$_SESSION['id_cities'])
			$_SESSION['id_cities'] = 1;
		?><script> location.reload(); </script><?
		exit;
	
	case 'choose_cities':
		$_SESSION['chooes_cities'] = false;
	?>
		<div style="padding:20px;" align="center">
			<h2>Пожалуйста укажите Ваш город</h2>
			<table style="margin-top:20px;">
			<?	
				$i = 0;
				$res = sql("SELECT * FROM {$prx}cities WHERE id_parent='0' ORDER BY name");
				while($row = mysql_fetch_assoc($res))
				{
					if(++$i%2==1) echo '<tr align="left">';
				?>	<td style="padding:7px 20px;"><a href="javascript:toAjax('/inc/action.php?action=cities&id=<?=$row['id']?>')" class="fs16 <?=$_SESSION['id_cities']==$row['id'] ? 'cr' : ''?>"><?=$row['name']?></a></td>
			<?	}	?>
			</table>
		</div>
	<?
		exit;
	
	case 'newsletter':
		$email = clean($_POST['email']);
		if(!isValidEmail($email))
			errorAlert('E-mail введен не верно',1);
		$emails = explode("\r\n", set('newsletter'));
		$row = getRow("SELECT * FROM {$prx}users WHERE email='{$email}'");
		if(in_array($email, $emails) || $row['f_email'])
			errorAlert('Вы уже подписаны на россылку',1);
		if($row)
			update('users', "f_email='1'", $row['id']);
		else
		{
			$emails[] = $email;
			sort($email);
			$emails = implode("\r\n", $emails);
			update('settings', "value='{$emails}'", 'newsletter');
		}
		?><script> alert('Вы успешно подписаны на рассылку'); </script><?
		exit;
}
?>