<?
/**
* Склонение слов по падежам. С использованием api Яндекса
* @var string $text - текст 
* @var integer $numForm - нужный падеж. Число от 0 до 5
*
* @return - вернет false при неудаче. При успехе вернет нужную форму слова
*/
function getNewFormText($text, $numForm){
    $urlXml = "http://export.yandex.ru/inflect.xml?name=".urlencode($text);
    $result = @simplexml_load_file($urlXml);
    if($result){
        $arrData = array();
        foreach ($result->inflection as $one) {
           $arrData[] = (string) $one;
        }
        return $arrData[$numForm];
    }
    return false;
}

  function getWord($number, $suffix) {
    $keys = array(2, 0, 1, 1, 1, 2);
    $mod = $number % 100;
    $suffix_key = ($mod > 7 && $mod < 20) ? 2: $keys[min($mod % 10, 5)];
    return $suffix[$suffix_key];
  }

function wordsSpan($n = 0, $words) {
    $words    = explode('|', $words);
    $n        = intval($n);
    return $n%10==1 && $n%100!=11 ? $words[0] . $words[1] : ($n%10>=2 && $n%10<=4 && ($n%100<10 || $n%100>=20) ? $words[0] . $words[2] : $words[0] . $words[3]);
}


// отправка SMS через smsdelivery 
function smsTo1($phones, $text)
{  
    
   $username = "STROYKA_RF";
   $password = "251080";
   $name="OOO PORT"; 
   
   $sms = new SMSdelivery($username, $password);
   $phone='7'.cleanPhone($phones);
    
      /**
     * Отправляем СМС-сообщение на указанный номер
     * 1-ый параметр - номер абонента
     * 2-ой параметр - имя отправителя (выдается службой поддержки)
     * 3-ий параметр - текст сообщения. Обратите внимание что для передачи СМС
     *                 кириллицей необходимо чтобы текст был в UTF-8 кодировке
     * 4-ый параметр - является ли сообщение Flash-сообщением (т.е. не сохраняется
     *                 во Входящих, а просто появляется на экране телефона).
     *                 Длина Flash сообщения не может быть больше длины одного СМС
     *                 (160 символов - латиница, 70 - кириллица)
     * 5-ый параметр - максимальный период доставки сообщения (в часах)
     */
    
   
    $res=$sms->SendMessage($phone,$name,$text);

    if (!$res){
        update('sms', "date='".date('Y-m-d H:i:s')."', error='SMSDelivery ошибка - ".$sms->getError().PHP_EOL."'");
    } else {
        update('sms', "date='".date('Y-m-d H:i:s')."', error='SMSDelivery успешно - ".$res['messageId'].PHP_EOL."'");
    }
   
    
    return;
}

// отправка SMS через websms.ru
function smsTo($phones, $text)
{
	if(set('sms_off') || $_SERVER['REMOTE_ADDR'] == '127.0.0.1')
		return;
		
	$login = 'promkontinent-ufa';
	$password = 'siery83bkjhg';
	$u = 'http://www.websms.ru/http_in6.asp';
	// приводим телефоны к нужному виду
	$arr = explode(',', $phones);
	foreach($arr as $key=>$val)
		$arr[$key] = '7'.cleanPhone($val);
	$phones = implode(',', $arr);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_TIMEOUT, 5);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, 'Http_username='.urlencode($login).'&Http_password='.urlencode($password).'&Phone_list='.$phones.'&Message='.urlencode($text));
	curl_setopt($ch, CURLOPT_URL, $u);
	$u = trim(curl_exec($ch));
	curl_close($ch);
	preg_match("/message_id\s*=\s*[0-9]+/i", $u, $arr_id);
	$id = preg_replace("/message_id\s*=\s*/i", "", @strval($arr_id[0]));
	if(!$id)
	{
		update('sms', "date='".date('Y-m-d H:i:s')."', error='websms ошибка'");
	}
}
// отправка SMS через websms.ru для php 5.5 и выше
function smsTo2($phones, $text)
{
	if(set('sms_off') || $_SERVER['REMOTE_ADDR'] == '127.0.0.1')
		return;
	$login = 'alcotown';
	$password = 'qawsed321';
	// приводим телефоны к нужному виду
	$arr = explode(',', $phones);
	foreach($arr as $key=>$val)
		$arr[$key] = '7'.cleanPhone($val);
	$phones = implode(',', $arr);

	$r = new HttpRequest('http://websms.ru/http_in6.asp', HttpRequest::METH_GET); 
	$r->addQueryData(array('Http_username' => urlencode($login), 'Http_password' =>urlencode($password), 'Phone_list' => $phones, 'Message' => ($text)));
	try 
	{
		$r->send(); 
		if($r->getResponseCode() == 200) { 
			//file_put_contents('myresults.log', $r->getResponseBody()); // все удачно
		} 
	}
	catch(HttpException $ex)
	{
		$ex = clean($ex);
		update('sms', "date='".date('Y-m-d H:i:s')."', error='websms - {$ex}'");
	}
}

// отправка SMS c помощью сервиса sms16.ru (старый вариант)
function smsTo3($phones, $text)
{
	if(set('sms_off') || $_SERVER['REMOTE_ADDR'] == '127.0.0.1')
		return;
	foreach(explode(',', $phones) as $phone)
	{
		$phone = cleanPhone($phone);
		$xml = '	<?xml version="1.0" encoding="utf-8" ?>
					<request>
						<message type="sms">
						<sender>Alcotown</sender>
						<text>'.$text.'</text>
						<abonent phone="7'.$phone.'"/>
					</message>
					<security>
					  <login value="alcotown" />
					  <password value="IAapMM" />
					</security>
					</request>';
		//https://my02.sms16.ru/xml/
		$urltopost = 'http://xml.sms16.ru/xml/';
		
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'Content-type: text/xml; charset=utf-8' ) );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_CRLF, true );
		curl_setopt( $ch, CURLOPT_POST, true );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $xml );
		curl_setopt( $ch, CURLOPT_URL, $urltopost );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, true );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, true );
	
		curl_setopt( $ch, CURLOPT_TIMEOUT, 5);
	
		$result = curl_exec($ch);
		
		// обработка ошибки
		if(curl_errno($ch))
		{
			$result = clean('ERROR -> '.curl_errno($ch).': '.curl_error($ch));
			update('sms', "date='".date('Y-m-d H:i:s')."', error='sms16 - {$result}'");
		}
		else
		{
			$returnCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
			switch($returnCode)
			{
				case 404:
					$result = 'ERROR -> 404 Not Found';
					update('sms', "date='".date('Y-m-d H:i:s')."', error='sms16 - {$result}'");
					break;
		
				default:
				break;
			}
		}
		curl_close($ch);
		//errorAlert(set('phone_sms').$result);
	}
	return $result;
}

// отправка SMS c помощью сервиса sms.ru
function smsTo4($phones, $text)
{
	if(set('sms_off') || $_SERVER['REMOTE_ADDR'] == '127.0.0.1')
		return;
	$api_id = 'ed68caa2-0ce4-5a64-11ec-2853094bdc99';

	$arr_phones = makeArrPhones(explode(',', $phones), 100); // приводим массив телефонов к нужному виду
	
	foreach($arr_phones as $phones)
	{		
		//$body = file_get_contents("http://sms.ru/sms/send?api_id={$api_id}&to={$phones}&text=".urlencode($text));		
		$ch = curl_init("http://sms.ru/sms/send");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_POSTFIELDS, array(
		
			"api_id"		=>	$api_id,
			"to"			=>	$phones,
			"text"		=>	$text,
			'from'		=> 'alcotown'
		
		));
		$body = curl_exec($ch);
		curl_close($ch);
		
		$id = (int)$body;
		if($id <> 100)
			update('sms', "date='".date('Y-m-d H:i:s')."', error='sms.ru ошибка - {$id}'");
	}
	return $id;
}

// формируем массив из определенного кол-ва элементов через запятую
function makeArrPhones($arr, $n=50) // кол-во элементов
{
	$i = $j = 0;
	$arr2 = array();
	foreach($arr as $key=>$val)
	{
		if($i < 50)
		{
			$arr2[$j][] = '7'.cleanPhone($val);
			$i++;
		}
		else
		{
			$i = 0;
			$j++;
		}
	}
	foreach($arr2 as $key=>$arr)
		$arr2[$key] = implode(',', $arr);
	return $arr2;
}

// отправка SMS c помощью сервиса sms16.ru
function smsTo5($phones, $text) // телефоны через запятую в любом формате, текст СМС
{
	if(set('sms_off') || $_SERVER['REMOTE_ADDR'] == '127.0.0.1')
		return;
	
	$login = 'alcotown3';
	$api_key = 'a3a6e9b45c2bd5387804e00d7032cbfa65ba3a9a';
	$sender = 'atown';
	
	$arr_phones = makeArrPhones(explode(',', $phones), 50); // приводим массив телефонов к нужному виду
	
	foreach($arr_phones as $phones)
	{
		$time = file_get_contents('https://new.sms16.ru/get/timestamp.php');
		// получаем $signature
		$params = array(
			'login' => $login,
			'timestamp' => $time,
			'phone' => $phones,
			'text' => $text,
			'sender' => $sender
		);
		ksort($params);
		reset($params);
		$signature = md5(implode($params).$api_key);
		
		// отправляем СМС
		$body = file_get_contents("https://new.sms16.ru/get/send.php?signature={$signature}&".http_build_query($params));
	
		$arr = (array)json_decode($body);
		if($arr['error'])
			update('sms', "date='".date('Y-m-d H:i:s')."', error='sms16.ru ошибка - {$arr['error']}'");
	}
	return $body;
}


// ПОДГОТОВКА СТРОКИ К СОХРАНЕНИЮ В ТАБЛИЦЕ
function clean($str, $strong=false) 
{
	if(is_array($str))
		return $str;
	$str = trim((string)$str, " \r\n	"); // пробел, перевод строки, таб
	if(substr_count($str, '"') + substr_count($str, "'") == substr_count($str, '\"') + substr_count($str, "\'")) // убираем слэши, если строка уже пришла со слешами
		$str = stripslashes($str);
	if($strong)
	{
		$str = preg_replace('/  +/', ' ', $str); // убираем повторяющиеся пробелы
		$str = htmlspecialchars(htmlspecialchars_decode($str)); //преобразоваваем теги html
		$str = strtr($str, array('"'=>'&quot;', "'"=>'&#0039;'));
	}
	else
		$str = addslashes($str);
	return $str;
}
// ПОДГОТОВКА МАССИВА К СОХРАНЕНИЮ В ТАБЛИЦЕ
function cleanArr($arr)
{
	foreach((array)$arr as $key=>$val)
	{
		if(is_array($val))
		{
			foreach($val as $key1=>$val1)
            {
            	$val[$key1] = trim(stripslashes($val1));
             }   
		}
		else
			$arr[$key] = trim(stripslashes($val));
	}
	// используем вместо обычного json_encode, иначе русские буквы перефигачацца
	$str = preg_replace_callback(
		'/\\\u([0-9a-fA-F]{4})/', 
		create_function('$_m', 'return mb_convert_encoding("&#" . intval($_m[1], 16) . ";", "UTF-8", "HTML-ENTITIES");'),
		json_encode($arr)
	);
	return clean($str);
}

//ОЧИСТКА СТРОКИ ДЛЯ ВИДА ПРИГОДНОГО К ПЕРЕДАЧИ В JAVASCRIPT
function cleanJS($str) // функцию передавать в '
{
	$str = preg_replace('#[\n\r]+#', '\\n', $str); // убираем переносы строк
	$str = str_replace(array("\'", '\"'), array("'", '"'), $str); //  убираем экранирование
	$str = str_replace("'", "\'", $str); //  экранируем только '
	$str = str_replace('script>', "scr'+'ipt>",$str); // чтобы можно было вставить скрипт
   return $str;
}
// ВОЗВРАЩАЕМ ИМЯ ДОМЕНА ИЗ ССЫЛКИ
function cleanUrl($url, $domen=true) // ссылка, возвращать домен или всю ссылку
{
	if(strpos($url, 'http://') === false)
		$url .= 'http://'.str_replace('//', '', $url);
	$arr = parse_url($url);  //Array ( [scheme] => http [host] => www.example.com [path] => /path [query] => googleguy=googley&a=1 [fragment] => b ) 	
	
	$host = $arr['host'];
	if(strpos($host, 'www.')!==false && !strpos($host, 'www.'))
		$host = substr($host, 4, strlen($host)-4);
	return $domen ? clean($host) : clean($host.$arr['path']);
}
// ОЧИСТКА СТРОКИ ДЛЯ XML
function cleanXML($str)
{
	$str = strip_tags($str);
	$str = htmlspecialchars($str, ENT_QUOTES, 'utf-8');
	return $str;
}
// ОСТАВЛЯЕМ ТОЛЬКО ЦИФРЫ
function cleanNum($num)
{
	return preg_replace('#[^0-9]#isU', '', $num);
}
// ОСТАВЛЯЕМ ТОЛЬКО ПОСЛЕДНИИ 10 ЦИФР
function cleanPhone($phone)
{
	return mb_substr(preg_replace('#[^0-9]#isU','',$phone), -10);
}
// ПРЕДСТАВЛЕН ТЕЛЕФОНА В ПРАВИЛЬНОМ ФОРМАТЕ
function formatPhone($phone_str)
{
	$phone = cleanPhone($phone_str);
	if(mb_strlen($phone) < 10)
		return $phone_str;
	return '+7('.mb_substr($phone,0,3).') '.mb_substr($phone,3,3).'-'.mb_substr($phone,6,2).'-'.mb_substr($phone,8,2);
}
// ПОДГОТОВКА СТРОКИ ДЛЯ CSV
function cleanCSV($str, $quotes=false) // строка, взять строку в кавычки (чтобы эксель не преобразовывал в число)
{
	if($str === '')
		return ';';

	if(!$quotes) // проверяем не нужно ли поставить кавычки принудительно
	{
		if(!strcasecmp($str, 'id')) $quotes = true;
		if($str != '0' && char($str, 0) == '0' && mb_strlen($str) == mb_strlen(cleanNum($str))) $quotes = true;
		if((strpos($str,',') || strpos($str,'.')) && mb_strlen($str) == mb_strlen(cleanNum($str))+1) $quotes = true;
		if(preg_replace('/[^а-яa-z0-9_.\-\ ]/iu', '', $str) != $str) $quotes = true;
	}
	if($quotes)
		$str = '"'.$str.'"';
	return '"'.str_replace('"','""',utf2win($str)).'";';
}

// ВОЗВРАЩАЕМ МАССИВ ИЗ СТРОКИ В ФОРМАТЕ JSON
function arr($str)
{
	if(!$str) return array();
	if(is_array($str)) return $str;
	return json_decode($str) ? (array)json_decode($str,1) : (array)json_decode(stripslashes($str));	
}

// ПЕРЕВОД ДАТЫ В ФОРМАТ БАЗЫ ДАННЫХ
function formatDateTime($datetime='00.00.0000 00:00:00') // можно передавать только дату и вообще формат не строгий!
{
	if(!($time = strtotime($datetime)))
		return '';
	$format = date('H:i:s', $time) == '00:00:00' ? 'Y-m-d' : 'Y-m-d H:i:s';
	return date($format, $time);
}

// ПОСТРАНИЧНОЕ РАЗБИЕНИЕ 
function lnkPages($obj, $p=1, $k=40, $get='?p=%s', $pn=9) // запрос или количество записей, текущая страница, кол-во строк на странице, ссылка на номере страницы, максимальное кол-во ссылок на страницы (нечетное число!)
{
	global $user_style;
	$get = str_replace('*ПРОЦЕНТ*s','%s', str_replace('%','*ПРОЦЕНТ*',$get));

	$n = is_array($obj) ? count($obj) : ( (int)$obj || $obj=='0' ? $obj : mysql_num_rows(sql($obj)) );
	if(!$n) return false;
	
	$n = ceil($n/$k); // количество страниц
	if($user_style && $n < 2) return false;
	
	$en = $p+($pn-1)/2<$pn ? $pn : $p+($pn-1)/2; // последний номер страницы (для цикла)
	$en = $en>$n ? $n : $en; 
	$st = $en-($pn-1)>0 ? $en-($pn-1) : 1; // первый номер страницы (для цикла)
	
	ob_start();	?>	
	<table class="paginator" cellpadding=0 cellspacing=0><tr>
    <td><div class="prev"><a href="<?=$p-1<=0 ? 'javascript:void(0)' : sprintf($get,$p-1)?>">Назад</a></div></td>
    <?php
    // рисуем ссылку на первую страницу (если надо)
    /*
    if ($st > 1) {
      ?>
      <td><div class="item"><a href="<?=sprintf($get,1)?>">1</a></div></td>
      <td><div class="item">...</div></td>
      <?php
    }
    */
    // рисуем ссылки на страницы по циклу
		for($i=$st; $i<=$en; $i++) {
      if ($p==$i) {
        ?>
        <td><div class="item current"><?=$i?></div></td>
        <?php
      } else {
        ?>
        <td><div class="item"><a href="<?=sprintf($get,$i)?>"><?=$i?></a></div></td>
        <?php
      }
		}
    // рисуем ссылку на последнюю страницу (если надо)
    /*
    if ($en < $n) {
      ?>
      <td><div class="item">...</div></td>
      <td><div class="item"><a href="<?=sprintf($get,$n)?>"><?=$n?></a></div></td>
      <?php
    }
    */
    ?>
		<td><div class="next"><a href="<?=$p+1>$n ? 'javascript:void(0)' : sprintf($get,$p+1)?>">Вперед</a></div></td>
  </tr></table>
  <?	return str_replace('*ПРОЦЕНТ*', '%', ob_get_clean());
}

// ОПРЕДЕЛЯЕМ НА КАКОЙ СТРАНИЦЕ НАША ЗАПИСЬ
function getPage($sql, $id, $k=40) // запрос, id-записи, кол-во записей на страницу
{	
	$res = sql($sql);
	$n=1; 
	while($row = mysql_fetch_row($res)) 
		if($id==$row[0]) 
			break; 
		else 
			$n++;
	return ceil($n/$k);
}

// ВОЗВРАЩАЕТ ЗНАЧЕНИЕ ПЕРЕМЕННОЙ ИЗ ТАБЛИЦЫ settings
function set($name, $tbl='settings')
{
	global $prx, $sets;
	
	if(!$sets[$tbl])
		$sets[$tbl] = getArr("SELECT id,`value` FROM {$prx}{$tbl}");
	$val = $sets[$tbl][$name];

	return $val=='true' ? true : ($val=='false' ? false : $val);
}	

// ПОЛУЧАЕТ ОДНО ЗНАЧЕНИЕ ИЗ ТАБЛИЦЫ
function getField($sql)
{
	$res = sql($sql); 
	$field = @mysql_result($res,0,0);
	return $field;
}	
// ПОЛУЧАЕТ МАССИВ ПЕРВОЙ СТРОКИ ТАБЛИЦЫ
function getRow($sql)
{
	$res = sql($sql); //echo $sql;
	$row = mysql_fetch_array($res);
	return $row;
}	

// ПОЛУЧАЕТ МАССИВ ВСЕГО ЗАПРОСА
function getArr($sql, $simple=true, $array=true) // $simple=true - возвратит "простой" массив (без привязки к полям запроса)
{
    //echo $sql;
    $arr = array();
	$res = sql($sql);
	if($simple)
	{
		while($row = mysql_fetch_row($res))
			if(mysql_num_fields($res)>1)
				$arr[$row[0]] = $row[1];
			else
				$arr[] = $row[0];
	}
	else
		while($row = mysql_fetch_assoc($res))
			$arr[] = $row;

	return $array ? $arr : "'".implode("','", $arr)."'";
}

// ЗАМЕНА mysql_query - ВЫВОДИТ ТЕКСТ ЗАПРОСА В СЛУЧАИ НЕУДАЧИ
function sql($sql, $debug=false)
{
	global $debugSql, $ajaxSql;
	$res = mysql_query($sql);
	if((!$res && @$_SESSION['priv']) || $debugSql || $debug)
	{
		$text = $sql."\r\n".mysql_error()."\r\n";
		if($ajaxSql) 
		{ ?>
			<script>alert('<?=cleanJS($text)?>');</script>
		<?	exit;
		} 
		else 
		{
			echo nl2br($text);
			?><script>
				if(top.window !== window && <?=(!$debugSql && !$debug ? 'true' : 'false')?>) // если мы во фрейме, то выводим алерт и прерываем фрейм
				{
					alert('<?=cleanJS($text)?>');
					location.href = "/inc/none.html";
				}
			</script><?
		}
	}
	return $res;
}

// ОБНОВЛЕНИЕ / ДОБАВЛЕНИЕ / УДАЛЕНИЕ ЗАПИСИ В ТАБЛИЦЕ
function update($tbl, $set='', $id=0) // таблица, обновляемые поля, id (может быть массивом, строкой через ',' или clean)
{
	global $prx, $cache_set;
	if(is_array($id))
		$id = implode("','",$id);
	$id = str_replace(',', "','", trim(str_replace("','", ',', $id),',')); // приводим id к виду списка с апострофами (сомнительная штука)

	if($tbl == 'settings')
		unset($cache_set[$tbl]);
	
	if(set('logs'))
		logsTbl($tbl, $id, !$set);
	
	if(!$set)
	{
		if($id == 'clean')
			sql("TRUNCATE TABLE {$prx}{$tbl}");
		else
			sql("DELETE FROM {$prx}{$tbl} WHERE id IN ('{$id}')");
		return;
	}
	if($id)
    {
        $sql = "UPDATE {$prx}{$tbl} SET {$set} WHERE id IN ('{$id}')";
        //echo $sql;
		sql($sql);
    }    
	else
	{
		sql("INSERT INTO {$prx}{$tbl} SET {$set}");
		$id = mysql_insert_id();
	}
	return $id;
}

function full_trim($str)                             
{                                                    
    return trim(preg_replace('/\s{2,}/', ' ', $str));
                                                      
}

// ЛОГГИРОВАНИЕ ИЗМЕНЕНИЙ В ТАБЛИЦЕ
function logsTbl($tbl, $id, $del=false) // имя таблицы, id или список id через запятую с апострафами, запись на удаление
{
	if(!$id || $tbl == 'logs')
		return;
	global $prx;
	$res_befor = sql("SELECT * FROM `{$prx}{$tbl}` WHERE id IN ('{$id}')");
	while($befor = mysql_fetch_assoc($res_befor))
	{
		$id_tbl = $befor['id'];
		$after = array();
		if($del)
			$after[] = 'запись удалена';
		else
		{
			$res = sql("SHOW FIELDS FROM `{$prx}{$tbl}`");
			while($row = mysql_fetch_assoc($res))
			{
				$field = $row['Field']; // название поля
				global $$field;
				if($field=='sort' || (!$$field && !isset($_REQUEST[$field])) || clean($befor[$field]) == clean($$field) || (!$befor[$field] && !$$field))
					unset($befor[$field]);
				else
					$after[$field] = $$field;
			}
		}
		if($after)
			update('logs', "date=NOW(), tbl='{$tbl}', id_tbl='{$id_tbl}', manager='".cleanArr($_SESSION['priv'])."', befor='".cleanArr($befor)."', after='".cleanArr($after)."'");
	}
	sql("DELETE FROM {$prx}logs WHERE date < DATE_ADD(NOW(), INTERVAL -2 MONTH)"); // оставляем только данные за последнии два месяца, чтобы не раздувать базу
}

// ВЫПАДАЮЩИЙ СПИСОК/МУЛЬТИСПИСОК для таблицы/массива
function dll($obj, $properties, $value='', $default=NULL, $disabled=array()) // запрос/массив, св-ва списка, значение (может быть массивом), "пустое" значение(может быть массивом), значения с параметром disabled
{ 
	ob_start();
?>
	<select <?=$properties?>>
	<?	if($default !== NULL)
			echo is_array($default)
				? "<option value='{$default[0]}'>{$default[1]}</option>"
				: "<option value=''>{$default}</option>";
		$arr = (is_array($obj) || !$obj) ? $obj : getArr($obj);
		foreach((array)$arr as $key=>$val) { 
			$selected = is_array($value) ? in_array($key, $value) : strcasecmp($key,$value)==0;
		?>	<option value="<?=$key?>"<?=($selected ? ' selected' : '').(in_array($key, (array)$disabled) ? ' disabled' : '')?>><?=$val?></option>
	<? } ?>
	</select>
<? 	
	return ob_get_clean();
}
// ВЫПАДАЮЩИЙ СПИСОК ДЛЯ ПОЛЯ enum
function dllEnum($tbl, $fill, $properties, $value='', $default=NULL) // таблица, поле, св-ва списка, значение
{ 
	global $prx;
	ob_start();
?>
	<select <?=$properties?>>
<?	if($default !== NULL)
		echo is_array($default)
			? "<option value='{$default[0]}'>{$default[1]}</option>"
			: "<option value=''>{$default}</option>";
		$res = sql("SHOW COLUMNS FROM {$prx}{$tbl} LIKE '{$fill}'");
		$val = mysql_result($res,0,1);
		$val = explode("('", $val);
		$val = trim($val[1], "')");
		$arr = explode("','",$val);
		foreach($arr as $val) { ?>
			<option value="<?=$val?>"<?=($val==$value ? ' selected' : '')?>><?=$val?></option>
	<? } ?>
	</select>
<? 	
	return ob_get_clean();
}
// ИНДЕКСЫ МАССИВА ЕГО ЗНАЧЕНИЯ вспомогательная функиця для выпадающих списков
function dllArr($arr)
{
	$arr_new = array();
	foreach($arr as $val)
		$arr_new[$val] = $val;
	return $arr_new;
}

// ОТПРАВКА HTML ПИСЬМА
function mailTo($to, $subject, $message, $from='', $charset='utf-8')
{
	//$subject = '=?koi8-r?B?'.base64_encode(convert_cyr_string($subject, "w","k")).'?='; 	// если возникнут проблемы с темой письма
   //$subject = "=?{$charset}?B?".base64_encode($subject)."?="; 	// если возникнут проблемы с темой письма (более корректный вариант)
	$fr=set('title').' <'.set('email').'>';
    if (!$from) $from=$fr;
    
    $headers  = "Content-type: text/html; charset={$charset} \r\n";
	if($from)
		$headers .= "From: {$fr}\r\nReply-To: {$from}\r\n";
	return @mail($to, $subject, $message, $headers);
}
// ОТПРАВКА HTML ПИСЬМА С ИЗОБРАЖЕНИЯМИ
function mailToImg($to, $subject, $message, $from='', $charset='utf-8')
{
	global $DR;
	require_once($DR.'/inc/simple_html_dom.php');
	$html = str_get_html($message);
	foreach($html->find('img') as $img)
	{
		$img_src = str_replace('"','',$img->src);
		$src[$img_src] = $img->src = basename($img_src);
	}
	$message = $html->innertext;
	$html->__destruct();
	
	require_once($DR.'/inc/advanced/inc/nomad_mimemail.php');
	$mimemail = new nomad_mimemail();
	$mimemail->set_charset($charset);
	$mimemail->set_to($to);
	if($from)	$mimemail->set_from($from);
	$mimemail->set_subject($subject);
	$mimemail->set_html("<HTML><HEAD><link rel='stylesheet' type='text/css' href='style.css'></HEAD><BODY>{$message}</BODY></HTML>");
	$mimemail->add_attachment($_SERVER['DOCUMENT_ROOT'].'/inc/style.css', 'style.css');
	//$mimemail->set_html("<HTML><HEAD></HEAD><BODY>{$message}</BODY></HTML>");
	foreach((array)@$src as $a=>$b)
		$mimemail->add_attachment($_SERVER['DOCUMENT_ROOT'].$a, $b);
	return $mimemail->send();
}
// ОТПРАВКА ПИСЬМА С ВЛОЖЕНИЕМ
function mailToAttach($to, $subject='', $message='', $from='', $files='', $html=true, $charset='utf-8') // получатель, тема, сообщение, отправитель, файл или массив файлов, в HTML, кодировка
{
	global $DR;
	require_once($DR.'/inc/advanced/inc/nomad_mimemail.php');
	$mimemail = new nomad_mimemail();
	
	$mimemail->set_to($to);
	if($from)
		$mimemail->set_from($from);
	$mimemail->set_subject($subject);
	if($html)
	{
		$mimemail->set_html("<HTML><HEAD><link rel='stylesheet' type='text/css' href='none.css'></HEAD><BODY>{$message}</BODY></HTML>");
		$mimemail->add_attachment($DR.'/inc/none.css', 'none.css');
	}
	else
		$mimemail->set_text($message);

	$mimemail->set_charset($charset);

	if($files)
		foreach((array)$files as $file) 
			$mimemail->add_attachment($file, basename($file));
	
	return $mimemail->send();
}

function date_to_sql($date='')
{
  $dt=explode(" ",$date);
  $d1=explode('.',$dt[0]);
  
  return $d1[2].'-'.$d1[1].'-'.$d1[0].' '.$dt[1];  
}

// ПЫТАЕМСЯ ОПРЕДЕЛИТЬ СПАМ
function checkSpam($text)
{
	if(!clean($text)) return false; // пустой текст
	if($text != strip_tags($text)) return true; // в тексте есть тэги
	if(!preg_replace('#[^а-я]#isu','',$text)) return true; // в тексте нет кириллицы
	if(strpos($text, '[')) return true; // прочая шляпа
	//if(strpos($text, '//') || strpos($text, 'www.')) return true; // прочая шляпа 2
	return false;
}

// ПРОВЕРКА КОРРЕКТНОСТИ E-mail
function isValidEmail($email)
{
	return function_exists('filter_var')
		? filter_var($email, FILTER_VALIDATE_EMAIL)
		: true;
}

// ВОЗВРАЩЕТ ТОЛЬКО ЦЫФРЫ
function num($str)
{
	return preg_replace('#[^0-9]#isU', '', $str);
}

// PHP конвертер из Windows-1251 в UTF-8
function win2utf($text, $iconv=true) // текст (в кодировке Windows-1251), флаг что сначала попытаться использовать функцию iconv
{
	if(detectUTF8($text))
		return $text;
		
	if(!$iconv || !function_exists('iconv'))
	{
		for($i=0, $m=strlen($text); $i<$m; $i++)
		{
			$c=ord($text[$i]);
			if($c<=127) {
				@$t.=chr($c); continue; 
			}
			if($c>=192 && $c<=207) {
				@$t.=chr(208).chr($c-48); continue; 
			}
			if($c>=208 && $c<=239) {
				@$t.=chr(208).chr($c-48); continue; 
			}
			if($c>=240 && $c<=255) {
				@$t.=chr(209).chr($c-112); continue; 
			}
			if($c==184) { 
				@$t.=chr(209).chr(209);	continue; 
			}
			if($c==168) { 
				@$t.=chr(208).chr(129);	continue; 
			}
		}
		return $t;
	}
	else
		return iconv('windows-1251', 'utf-8', $text);
}
// PHP конвертер из UTF-8 в Windows-1251
function utf2win($text, $iconv=true) // текст (в кодировке UTF-8), флаг что сначала попытаться использовать функцию iconv
{
	if(!detectUTF8($text))
		return $text;

	if(!$iconv || !function_exists('iconv'))
	{
		$out = $c1 = '';
		$byte2 = false;
		for($c=0; $c<strlen($text); $c++)
		{
			$i = ord($text[$c]);
			if ($i <= 127)
				$out .= $text[$c];
	
			if($byte2) 
			{
				$new_c2 = ($c1 & 3) * 64 + ($i & 63);
				$new_c1 = ($c1 >> 2) & 5;
				$new_i = $new_c1 * 256 + $new_c2;
				$out_i = $new_i == 1025
					? 168
					: ($new_i==1105 ? 184 : $new_i-848);
				$out .= chr($out_i);
				$byte2 = false;
			}
			if(($i >> 5) == 6) 
			{
				$c1 = $i;
				$byte2 = true;
			}
		}
		return $out;
	}
	else
		return iconv('utf-8', 'windows-1251', $text);
}
// ОПРЕДЕЛЕНИЕ ЧТО КОДИРОВКА ТЕКСТА В UTF8
function detectUTF8($str)
{
	return preg_match('//u', $str);
}

// ПОЛУЧИТЬ СИМВОЛ ИЗ СТРОКИ В КОДИРОВКЕ UTF-8
function char($str, $pos) 
{
	return mb_substr($str, $pos, 1, 'UTF-8');
}

// ПРИВОДИМ ТЕКСТ К ПРИГОДНОМУ ДЛЯ ССЫЛКИ
function makeUrl($str, $trans=true)
{
	//$str = ereg_replace("[^А-Яа-яA-Za-z0-9_\-]", "_", $str); // способ хороший, но не работает в utf-8
	$s = '  абвгдеёжзийклмнопрстуфхчцшщэюяыъьэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЧЦШЩЭЮЯЫЪЬЭЮЯabcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890-_';
	$str2 = '';
	for($i=0; $i<mb_strlen($str); $i++)
		$str2 .= strpos($s, char($str,$i)) ? char($str,$i) : '-';
	$str = preg_replace('#-+#', '-', $str2); // убираем повторяющиеся -
	$str = trim($str, '-');
	$str = mb_strtolower($str, 'UTF-8');
	return $trans ? trans($str) : $str; 
}
// ПРИВОДИМ ТЕКСТ К ПРИГОДНОМУ ДЛЯ ССЫЛКИ
function makeUrl_old($str)
{
	$str = trans($str);
	$str = str_replace(array(' ',','), '_', $str);
	$str = mb_strtolower($str);
	$str = preg_replace('#[^a-z0-9_.\-]#isu','',$str); // оставляем только буквы, цифры, - и _
	return $str; 
}
// ТРАНСЛИТЕРАЦИЯ СТРОКИ
function trans($str)
{
	$table = array(
		'А'=>'A','Б'=>'B','В'=>'V','Г'=>'G','Д'=>'D','Е'=>'E','Ё'=>'Yo','Ж'=>'Zh','З'=>'Z','И'=>'I','Й'=>'J','К'=>'K','Л'=>'L','М'=>'M','Н'=>'N','О'=>'O','П'=>'P','Р'=>'R','С'=>'S','Т'=>'T','У'=>'U','Ф'=>'F','Х'=>'H','Ц'=>'C','Ч'=>'Ch','Ш'=>'Sh','Щ'=>'Csh','Ь'=>'','Ы'=>'Y','Ъ'=>'','Э'=>'E','Ю'=>'Yu','Я'=>'Ya',
		'а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'yo','ж'=>'zh','з'=>'z','и'=>'i','й'=>'j','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'h','ц'=>'c','ч'=>'ch','ш'=>'sh','щ'=>'csh','ь'=>'','ы'=>'y','ъ'=>'','э'=>'e','ю'=>'yu','я'=>'ya'
	);
	return str_replace(array_keys($table), array_values($table), $str);
}

// ГЕНЕРАЦИЯ ПАРОЛЯ
function getPwd($n=10) // длина пароля
{
	$str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
	$pass = '';
	for($i=0; $i<$n; $i++) 
		$pass .= $str[mt_rand(0,strlen($str)-1)];
	return $pass;
}

// ВЫВОД ALERT ОБ ОШИБКЕ (и прерывание выполнения)
function errorAlert($msg, $ajax=false, $jAlert=false) // .. $jAlert - больше не используется
{
	global $user_style;
	?><script>
       top.fancybox_info('<?=$msg?>',3000);
	<?	if(!$ajax) {	?>
			top.showLoad(false);
			top.topBack(<?=count(@$_POST)?>);
	<?	}	?>
	</script><?
	exit;
}

// ПОКАЗЫВАЕМ СКРЫТЫЙ ФРЕЙМ (ajax) (использовать для отладки)
function debug($exit=true)
{
	?><script>top.$("#iframe").show();</script><?
	flush();
	if($exit) exit;
}

// ПЕРЕВОД ЦВЕТА ИЗ HTML В RGB
function html2rgb($color='#FFFFFF')
{
	$color = substr($color, 1);
	
	list($r, $g, $b) = strlen($color)==6
		? array($color[0].$color[1], $color[2].$color[3], $color[4].$color[5])
		: array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
	
	return array(hexdec($r), hexdec($g), hexdec($b));
}
// ПЕРЕВОД ЦВЕТА ИЗ RGB В HTML
function rgb2html($r=255, $g=255, $b=255)
{
	foreach(array(dechex($r), dechex($g), dechex($b)) as $c)
		@$color .= (strlen($c)<2 ? '0' : '').$c;

	return '#'.$color;
}

// ВОЗВРАЩАЕТ КУРС ВАЛЮТЫ
function getKurs($valuta='usd')
{
	$arr = array('usd'=>'R01235', 'eur'=>'R01239');
	$xml = @simplexml_load_file('http://www.cbr.ru/scripts/XML_daily.asp?date_req='.date('d/m/Y'));
	if($xml)
		foreach($xml->Valute as $v) 
		{
			if((string)$v['ID'] == $arr[$valuta])
			return str_replace(',', '.', $v->Value);
		}
}

// HTTP-АВТОРИЗАЦИЯ
function wwwAuth($login, $pwd)
{
	if(strcasecmp(trim($_SERVER['PHP_AUTH_USER']),$login) || $_SERVER['PHP_AUTH_PW']!=$pwd)
	{
		header('WWW-Authenticate: Basic realm="Private area"');
		header('HTTP/1.0 401 Unauthorized');
		return false;
		exit;
	}
	return true;
}

// ВСТАВКА FLASH
function flash($src, $properties='', $param='') // пусть к флехе, свойства (ширина, высота...), параметры (wmode="transparent" - для прозрачной флешки)
{
	if(!file_exists($_SERVER['DOCUMENT_ROOT'].$src))
		return '';
	$wh = @getimagesize($_SERVER['DOCUMENT_ROOT'].$src);
	
	$strwh = ''; // чтобы флешка выводилась пропорционально при задании ей размеров
	if(strpos($properties, 'width="')!==false || strpos($properties, 'height="')!==false)
	{
		$w = explode('width="', $properties);
		$h = explode('height="', $properties);
		$strwh = getRatioSize($wh, array((int)$w[1], (int)$h[1]));
		$strwh = "width='{$strwh[0]}' height='{$strwh[1]}'";
	}
	ob_start();
	?><object <?=$strwh?> <?=$properties?> <?=$wh[3]?> codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0">
		<param name="quality" value="high"><param name="src" value="<?=$src?>"><?=$param?>
		<embed type="application/x-shockwave-flash" <?=$strwh?> <?=$properties?> <?=$wh[3]?> src="<?=$src?>" quality="high" wmode="opaque"></embed>
	</object><?
	return trim(ob_get_clean());
}

// ВСТАВКА YOUTUBE
function youtube($src, $properties='width="560" height="315"') // ссылка youtube, свойства (ширина, высота...)
{
	ob_start();	
?>
	<iframe <?=$properties?> src="<?=$src?>" frameborder="0" allowfullscreen></iframe>
<?
	return ob_get_clean();
}

// АНАЛОГ ФУНКЦИЯМ strtolower И strtoupper
function strto($case='lower', $string)
{
	$arr = array(
		'А'=>'а','Б'=>'б','В'=>'в','Г'=>'г','Д'=>'д','Е'=>'е','Ё'=>'ё','Ж'=>'ж','З'=>'з','И'=>'и','Й'=>'й','К'=>'к','Л'=>'л','М'=>'м','Н'=>'н','О'=>'о','П'=>'п','Р'=>'р','С'=>'с','Т'=>'т','У'=>'у','Ф'=>'ф','Х'=>'х','Ц'=>'ц','Ч'=>'ч','Ш'=>'ш','Щ'=>'щ','Ь'=>'ь','Ы'=>'ы','Ъ'=>'ъ','Э'=>'э','Ю'=>'ю','Я'=>'я',
		'A'=>'a','B'=>'b','C'=>'c','D'=>'d','E'=>'e','F'=>'f','G'=>'g','H'=>'h','I'=>'i','J'=>'j','K'=>'k','L'=>'l','M'=>'m','N'=>'n','O'=>'o','P'=>'p','Q'=>'q','R'=>'r','S'=>'s','T'=>'t','U'=>'u','V'=>'v','W'=>'w','X'=>'x','Y'=>'y','Z'=>'z'
	);
	return $case == 'lower' 
		? str_replace(array_keys($arr), array_values($arr), $string) 
		: str_replace(array_values($arr), array_keys($arr), $string);
}

// ИЗМЕНЕНИЕ ДАТЫ/ВРЕМЕНИ
function changeDateTime($datetime='0000-00-00 00:00:00', $year=0, $month=0, $day=0, $hour=0, $minute=0, $second=0) // можно передавать только дату
{
	list($date, $time) = explode(' ', $datetime); 
	list($y, $m, $d) = explode('-', $date); 
	list($h, $i, $s) = explode(':', $time); 
	$res = @mktime((int)$h+$hour, (int)$i+$minute, (int)$s+$second, $m+$month, $d+$day, $y+$year);
	if(!$res)
		return '';
	return @$time ? @date('Y-m-d H:i:s', $res) : @date('Y-m-d', $res);
}

// ИЗМЕНЕНИЕ РАЗМЕРОВ КАРТИНКИ
function imgResize($src, $width, $height=0, $src_save='', $watermark='', $max=false) // путь к картинки, ширина, высота, путь для сохранения (если не задан, возвращается контент картинки), водяной знак (png), делать картинку максимальной относительно сторон
{
	$size = @getimagesize($src);
	if($size === false) 
		return false;
	
	$type = $size['mime'];
	$format = mb_strtolower(substr($type, strpos($type, '/')+1));
	if($format == 'bmp')
		include('bmp.php');

	$icfunc = 'imagecreatefrom'.$format;
	if (!function_exists($icfunc))
		return false;
	
//	list($width, $height) = getRatioSize($size, array((int)$width, (int)$height), $max);
	
	if(($width==$size[0] || $height==$size[1]) && !$watermark) // запрашиваемый размер больше или равен оригинальному
	{
		if($src_save)
		{
			copy($src, $src_save);
			@chmod($src_save, 0644);
		}
		return true;
	}

	$isrc = $icfunc($src);
	/*if($watermark && ($width > 249 || $height > 199)) // накладываем водяной знак на оригинальную картинку
	{
		$size_wm = array(800, round(800*$size[1]/$size[0])); // подготавливаем размер картинки
		$idest = imagecreatetruecolor($size_wm[0], $size_wm[1]);
		imagecopyresampled($idest, $isrc, 0, 0, 0, 0, $size_wm[0], $size_wm[1], $size[0], $size[1]);
		$size = $size_wm;
    if ($width > $size_wm[0] || $height > $size_wm[1]) {
      $width = $size_wm[0];
      $height = $size_wm[1];
    }
		$isrc = $idest;
		$znak_wh = getimagesize($watermark); // накладываем знак
		$znak = imagecreatefrompng($watermark);
		imagecopy($isrc, $znak, round(($size[0]-$znak_wh[0])/2), round(($size[1]-$znak_wh[1])/2), 0, 0, $znak_wh[0], $znak_wh[1]);
		imagedestroy($znak);
	}*/
  $x_ratio = $width / $size[0];
  $y_ratio = $height / $size[1];

  $ratio       = min($x_ratio, $y_ratio);
  $use_x_ratio = ($x_ratio == $ratio);

  if($size[0]>$width || $size[1]>$height)
  {
    $new_width   = $use_x_ratio  ? $width  : floor($size[0] * $ratio);
    $new_height  = !$use_x_ratio ? $height : floor($size[1] * $ratio);
    $new_left    = $use_x_ratio  ? 0 : floor(($width - $new_width) / 2);
    $new_top     = !$use_x_ratio ? 0 : floor(($height - $new_height) / 2);
  }
  else
  {
    $new_width   = $size[0];
    $new_height  = $size[1];
    $new_left    = floor(($width - $size[0]) / 2);
    $new_top     = floor(($height - $size[1]) / 2);
  }
	$idest = @imagecreatetruecolor($width, $height);
  @imagefill($idest, 0, 0, 0xffffff);
  @imagecopyresampled($idest, $isrc, $new_left, $new_top, 0, 0, $new_width, $new_height, $size[0], $size[1]);

	$ir = @imagejpeg($idest, $src_save, 90);
	
	@imagedestroy($isrc);
	@imagedestroy($idest);

	return $ir;
}

// ВОЗВРАЩАЕТ РАЗМЕРЫ В СООТВЕТСТВИИ С ПРОПОРЦИЯМИ
function getRatioSize($size=array(320,240), $sizeto=array(160,120), $max=false)
{
	if(!$size)
		return array();

	list($width, $height) = $sizeto;
	if(!$width || $width > $size[0])
		$width = $size[0];
	if(!$height || $height > $size[1])
		$height = $size[1];
	
	$x_ratio = $width / $size[0];
	$y_ratio = $height / $size[1];
	
	$ratio = $max ? max($x_ratio, $y_ratio) : min($x_ratio, $y_ratio);
	$use_x_ratio = ($x_ratio == $ratio);
	
	$width   = $use_x_ratio  ? $width  : floor($size[0] * $ratio);
	$height  = !$use_x_ratio ? $height : floor($size[1] * $ratio);
	
	return array($width, $height);
}

// ФОРМИРУЕМ WHERE ДЛЯ ПОИСКА
function getWhere($fields, $all=true) // список полей через запятую, искать все слова или любое слово
{
	global $search;
	if(!$search)
		return 1;
	// общий массив where по каждому полю
	foreach(explode(',', $fields) as $field)
		foreach(explode(' ', $search) as $word)
			$where_field[$field][] = " {$field} LIKE '%{$word}%' ";
	// складываем в строку where по каждому полю
	foreach($where_field as $field)
		$where[] = implode(($all ? 'AND' : 'OR'), $field);
	// складываем в строку where
	return '( ('.implode(') OR (', $where).') )';
}


// ДЕНЕЖНАЯ СУММА ПРОПИСЬЮ
function price2str($inn, $stripkop=false) 
{
	$str[100]= array('','сто','двести','триста','четыреста','пятьсот','шестьсот',	'семьсот', 'восемьсот','девятьсот','тысяча');
	$str[11] = array(10=>'десять',11=>'одиннадцать',12=>'двенадцать',	13=>'тринадцать',14=>'четырнадцать',15=>'пятнадцать',	16=>'шестнадцать',17=>'семнадцать',18=>'восемнадцать', 19=>'девятнадцать');
	$str[10] = array('','','двадцать','тридцать','сорок','пятьдесят',	'шестьдесят','семьдесят','восемьдесят','девяносто','сто');
	$sex[1] = array('','один','два','три','четыре','пять','шесть','семь', 'восемь','девять');
	$sex[2] = array('','одна','две','три','четыре','пять','шесть','семь', 'восемь','девять');
	$forms = array(
		-1=>array('копейка', 'копейки',  'копеек',    2),
		0 =>array('рубль',   'рубля',    'рублей',    1), // 10^0
		1 =>array('тысяча',  'тысячи',   'тысяч',     2), // 10^3
		2 =>array('миллион', 'миллиона', 'миллионов', 1), // 10^6
		3 =>array('миллиард','миллиарда','миллиардов',1), // 10^9
		4 =>array('триллион','триллиона','триллионов',1), // 10^12
	);
	$out = $tmp = array();
	// Поехали!
	$tmp = explode('.', str_replace(',','.', $inn));
	$rub = number_format($tmp[0],0,'','-');
	 // нормализация копеек
	$kop = isset($tmp[1]) ? str_pad(substr($tmp[1],0,2), 2, '0', STR_PAD_RIGHT) : '00';
	$levels = explode('-', $rub);
	$offset = sizeof($levels)-1;
	foreach($levels as $k=>$lev) 
	{
		$lev = str_pad($lev, 3, '0', STR_PAD_LEFT); // нормализация
		$ind = $offset-$k; // индекс для $forms
		if ($lev[0]!='0') $out[] = $str[100][$lev[0]]; // сотни
		$lev = $lev[1].$lev[2];
		$lev = (int)$lev;
		if ($lev > 19)  // больше девятнадцати
		{
			$lev = ''.$lev;
			$out[] = $str[10][$lev[0]];
			$out[] = $sex[$forms[$ind][3]][$lev[1]];
		}
		elseif($lev>9)
			$out[] = $str[11][$lev];
		elseif($lev>0)
			$out[] = $sex[$forms[$ind][3]][$lev];

		if ($lev>0 || $ind==0)
			$out[] = pluralForm($lev, $forms[$ind][0], $forms[$ind][1] ,$forms[$ind][2] );
	}
	if (!$stripkop) 
	{
		$out[] = $kop; // копейки
		$out[] = pluralForm($kop, $forms[-1][0], $forms[-1][1] ,$forms[-1][2] );
	}
	return implode(' ',$out);
}
function pluralForm($n, $f1, $f2, $f5) // вспомогательная функция для - денежная сумма прописью
{
	$n = abs($n) % 100;
	$n1 = $n % 10;
	if ($n > 10 && $n < 20) return $f5;
	if ($n1 > 1 && $n1 < 5) return $f2;
	if ($n1 == 1) return $f1;
	return $f5;
}

// РАЗНИЦА МЕЖДУ ДВУМЯ ДАТАМИ
function dateDiff($datetime1='0000-00-00 00:00:00', $datetime2='0000-00-00 00:00:00', $interval='s') // время от (можно передавать только дату), время до, интервал
{
	for($j=1; $j<3; $j++)
	{
		list($date, $time) = explode(' ', ${"datetime{$j}"}); 
		list($y, $m, $d) = explode('-', $date); 
		list($h, $i, $s) = explode(':', $time); 
		${"res{$j}"} = @mktime((int)$h, (int)$i, (int)$s, $m, $d, $y);
		if(!${"res{$j}"}) return;
	}
	$timedifference = $res2 - $res1;	// получает количество секунд между двумя датами 
	switch ($interval) 
	{
		case 'w': return $timedifference/604800;
		case 'd': return $timedifference/86400;
		case 'h': return $timedifference/3600;
		case 'm': return $timedifference/60;
	}
	return $timedifference;
}

// РАСКЛАДЫВАЕМ НОМЕР ТЕЛЕФОНА НА ПРЕФИКС И НОМЕР
function expPhone($str='(000) 000 00 00') // номер телефона, где префикс отделен пробелом
{
	$phone = explode(' ', $str);
	$phone[1] = str_replace($phone[0].' ', '', $str);
	return $phone; 
}

// ФОРМИРУЕМ СТРОКУ ЗАПРОСА
function getQS($except='', $request=false) // массив или строка через запятую переменных исключаемых из строки запроса, передавать сразу пост и гет
{
	if(!is_array($except))
		$except = explode(',', $except);
	$except = array_merge($except, array('x','y','none','rand','p','action')); // добавляем исключения
	$qs = $request ? $_REQUEST : $_GET;
	foreach($except as $val)
		unset($qs[$val]);
	return http_build_query($qs) ? '&'.http_build_query($qs) : '';
}

// ПЕРЕНАПРАВЛЕНИЕ НА СТРАНИЦУ
function go301($url) // ссылка
{
	ob_clean();
	header("location: {$url}", true, 301);
	exit;
}
// ВЫЗЫВАЕМ 404-УЮ ОШИБКУ
function e404()
{
	global $HH;
	header('HTTP/1.1 404 Not Found');
	header('Status: 404 Not Found');
    //header("Location: /404.htm");
	echo file_get_contents("http://{$HH}/404.htm");
	exit;
}

// РАСКЛАДЫВАЕТ СТРОКУ НА МАССИВ 'ключ'=>'значение'
function explode2($r1, $r2, $str) // разделитель между парой, разделитель пары, строка
{
	$arr = array();
	foreach((array)explode($r1, $str) as $val)
	{
		$arr1 = (array)explode($r2, $val);
		if(trim($arr1[0]))
			$arr[trim($arr1[0])] = trim($arr1[1]);
	}
	return $arr;		
}

// КРАСИВЫЙ ПРОСТОЙ ВЫВОД print_r()
function pr($obj)
{
	$bt =  debug_backtrace();
?>
	<div style='font-size:9pt; color:#000; background:#fff; border:1px dashed #000;'>
		<div style='padding:3px 5px; background:#99CCFF; font-weight:bold;'><?=$bt[0]['file']?> [<?=$bt[0]['line']?>]</div>
		<pre style='padding:10px;'><? print_r($obj) ?></pre>
	</div>
<?
}

// ПОКАЗЫВАЕМ ШАПКУ АДМИНКИ
function showAdminHead($ten=true)
{
	global $HH;
	if(!@$_SESSION['priv'])
		return;
	ob_start();
?>
	<noindex>
		<style> 
			a.a_admin_head { text-decoration:none; color:#FFF; font:11px Verdana; } 
			a.a_admin_head:hover { text-decoration:underline; }
		</style>
		<table width="100%" bgcolor="#5373AC" cellpadding="0" cellspacing="0" <?=$ten ? 'style="box-shadow:0 0 4px #333333; position:fixed; z-index:999;"' : ''?> class="fs11">
			<tr>
				<td style="color:#FFF; padding-left:8px;" height="26"><a href="/" rel="nofollow" class="a_admin_head" style="padding:0 0 2px 22px; background:url(/admin/img/bg_icons.png) 0 -896px no-repeat;"><b><?=$HH?></b></a> - <a href="/admin/" class="a_admin_head"><b>Администрирование</b></a></td>
				<td align="right" style="padding-right:8px;">
					<a href="javascript://" rel="nofollow" style="margin-right:15px; background:url(/admin/img/bg_icons.png) 0 -640px no-repeat; display:inline-block; vertical-align:middle; width:16px; height:16px;;" title="Окно отладки" onClick="$('#iframe').slideToggle();"></a>
					<a href="/admin/login.php?action=vyhod" rel="nofollow" class="a_admin_head" style="padding:0 0 2px 22px; background:url(/admin/img/bg_icons.png) 0 -928px no-repeat;"><?=$_SESSION['priv']=='admin' ? 'Администратор' : $_SESSION['priv']['login']?> (выход)</a>
				</td>
			</tr>
		</table>
	<?	if($ten) { ?><div style="height:25px;"></div><? } ?>
	</noindex>
<?
	return ob_get_clean();
}

// ПОЛУЧЕНИЕ ГОРОДА ПО IP-шнику
function getCityByIP()
{
	global $DR;
	include_once($DR.'/admin/inc/getfile.php');
    
	//$xml = file_curl_contents('http://ipgeobase.ru:7020/geo/?ip='.$_SERVER['REMOTE_ADDR'], '', 3);
	$xml = @simplexml_load_string($xml);
	return (string)$xml->ip->city;
}

// ОТПРАВКА ФАЙЛА НА СКАЧИВАНИЕ
function file_force_download($file) 
{
	if(!file_exists($file))
		return;
	header('X-SendFile: '.realpath($file));
	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename='.basename($file));
	exit;
}

// Возвращает массив всех файлов,
// находящихся в $dir
function get_file_list($dir)
{
	$res = array();

	if($dh=@opendir($dir))
	{
		while(($file=@readdir($dh))!==false)
		{
			if($file!=='.' && $file!=='..')
			{
				$current_file = "{$dir}/{$file}";
				if(is_file($current_file))
					$res[] = $file;
			}
		}
		closedir($dh);
	}

	return $res;
}

function createBackup($tbl) {
	global $prx;
	$tblName = $tbl;
	$sql = "SHOW CREATE TABLE `{$prx}{$tblName}`";
	$res = mysql_query($sql);
	$sql = mysql_result($res, 0, 1);
	$date = date('Ymd_His');
	$sql = str_replace("CREATE TABLE `{$prx}{$tblName}`", "CREATE TABLE `{$prx}{$tblName}_backup_{$date}`", $sql);
	mysql_query("DROP TABLE IF EXISTS `{$prx}{$tblName}_backup_{$date}`");
	mysql_query($sql);
	$sql = "INSERT INTO `{$prx}{$tblName}_backup_{$date}` SELECT * FROM `{$prx}{$tblName}`";
	mysql_query($sql);
}


function replaceExt($fn, $to=".jpg"){
    return str_replace(".".pathinfo($fn,PATHINFO_EXTENSION ),$to,$fn);
}





function updateCatalogStructure($id=0){
    global $prx;
    $sql = <<<SQL
		UPDATE {$prx}catalog c INNER JOIN (
SELECT
  `c1`.`id` AS `id`,
  ''        AS `ids_path`,
  ''        AS `path`,
  1         AS `LEVEL`
FROM `{$prx}catalog` `c1`
WHERE (`c1`.`id_parent` = 0)UNION SELECT
                                    `c2`.`id`  AS `id`,
                                    CONCAT(`c1`.`id`) AS `ids_path`,
                                    CONCAT(`c1`.`link`) AS `path`,
                                    2          AS `LEVEL`
                                  FROM (`{$prx}catalog` `c2`
                                         JOIN `{$prx}catalog` `c1`
                                       ON ((`c2`.`id_parent` = `c1`.`id`)))
                                  WHERE (`c1`.`id_parent` = 0)UNION SELECT
                                                                      `c3`.`id`   AS `id`,
                                                                      CONCAT(`c1`.`id`,',',`c2`.`id`) AS `ids_path`,
                                                                      CONCAT(`c1`.`link`,'/',`c2`.`link`) AS `path`,
                                                                      3           AS `LEVEL`
                                                                    FROM ((`{$prx}catalog` `c3`
                                                                        JOIN `{$prx}catalog` `c2`
                                                                          ON ((`c3`.`id_parent` = `c2`.`id`)))
                                                                       JOIN `{$prx}catalog` `c1`
                                                                         ON ((`c2`.`id_parent` = `c1`.`id`)))
                                                                    WHERE (`c1`.`id_parent` = 0)UNION SELECT
                                                                                                        `c4`.`id`    AS `id`,
                                                                                                        CONCAT(`c1`.`id`,',',`c2`.`id`,',',`c3`.`id`) AS `ids_path`,
                                                                                                        CONCAT(`c1`.`link`,'/',`c2`.`link`,'/',`c3`.`link`) AS `path`,
                                                                                                        4            AS `LEVEL`
                                                                                                      FROM (((`{$prx}catalog` `c4`
                                                                                                           JOIN `{$prx}catalog` `c3`
                                                                                                             ON ((`c4`.`id_parent` = `c3`.`id`)))
                                                                                                          JOIN `{$prx}catalog` `c2`
                                                                                                            ON ((`c3`.`id_parent` = `c2`.`id`)))
                                                                                                         JOIN `{$prx}catalog` `c1`
                                                                                                           ON ((`c2`.`id_parent` = `c1`.`id`)))
                                                                                                      WHERE (`c1`.`id_parent` = 0)
) t ON c.id=t.id
                                                                                                        
                                                                                                      SET c.`ids_path`=t.ids_path,c.level=t.level,c.path=t.path
SQL;
    if($id)
        $sql.=" WHERE c.id={$id}";
    sql($sql);
}