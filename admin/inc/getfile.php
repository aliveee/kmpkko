<?/*
// ПОЛУЧАЕМ СТРАНИЦУ ПЕРЕДАВАЯ POST-ПАРАМЕТРЫ
function file_post_contents($url='http://', $post_arr=array('var'=>'value')) // адрес страницы, массив POST-параметров
{
	$postfields = http_build_query($post_arr);  
	$opts['http'] = array(  
		'method'  => 'POST',  
		'header'  => 'Content-type: application/x-www-form-urlencoded',  
		'content' => $postfields
	);  
	$context  = stream_context_create($opts);  
	return file_get_contents($url, false, $context);  
}

// ПОЛУЧАЕМ СТРАНИЦУ С ПОМОЩЬЮ cURL
function file_curl_contents($url, $post='', $timeout='', $ip='', $header=false, $cookies='') // адрес страницы, строка запроса POST-параметров, лимит времени (сек), подставить ip-адрес, получить заголовки, имя файла с куками
{
	$ch = curl_init(); 
	//curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322)'); // притворяемся браузером
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // не сразу выводить данные, а сохранить их в переменной $result
	if($timeout)
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
	if($post) {
		curl_setopt($ch, CURLOPT_POST, 1); 
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	}
   if($ip)
		curl_setopt($ch, CURLOPT_INTERFACE, $ip);
	if($header)
		curl_setopt($ch, CURLOPT_HEADER, true); // возвратит заголовки
	
	//КУКИ
	if($cookies)
	{
		//curl_setopt($ch, CURLOPT_COOKIE, '');	// сюда можно подсунуть куки
		curl_setopt($ch, CURLOPT_COOKIEJAR,  $_SERVER['DOCUMENT_ROOT'].'/factive/uploads/settings/'.$cookies); // записываются полученные данные cookie
		curl_setopt($ch, CURLOPT_COOKIEFILE, $_SERVER['DOCUMENT_ROOT'].'/factive/uploads/settings/'.$cookies); // читаются данные cookie
	}
	
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // чтоб нормально забирать
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // файлы с https://
	
	//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // если возвращается перенаправление, то curl сам будет по ним ходить
	//$result = curl_redir_exec($ch); // если хостинг ругается на CURLOPT_FOLLOWLOCATION
	
	$result = curl_exec($ch);
	//if(curl_errno($ch)) echo curl_error($ch); // сообщение об ошибке
	curl_close($ch);  
	return $result;
}
// Эмулируем CURLOPT_FOLLOWLOCATION для хостингов, кот. на него ругаются
function curl_redir_exec($ch, $debug='')// использовать вместо curl_exec($ch);, $curl_loops - не передавать!
{ 
	static $curl_loops = 0; 
	static $curl_max_loops = 20; 
	
	if($curl_loops++ >= $curl_max_loops) 
	{ 
		$curl_loops = 0; 
		return false; 
	} 
	curl_setopt($ch, CURLOPT_HEADER, true); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
	$data = curl_exec($ch); 
	$debbbb = $data; 
	list($header, $data) = explode("\n\n", $data, 2); 
	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
	
	if($http_code == 301 || $http_code == 302) 
	{ 
		$matches = array(); 
		preg_match('/Location:(.*?)\n/', $header, $matches); 
		$url = @parse_url(trim(array_pop($matches))); 
		if (!$url) 
		{ 
			$curl_loops = 0; 
			return $data; 
		} 
		$last_url = parse_url(curl_getinfo($ch, CURLINFO_EFFECTIVE_URL)); 
		$new_url = $url['scheme'] . '://' . $url['host'] . $url['path'] . ($url['query']?'?'.$url['query']:''); 
		curl_setopt($ch, CURLOPT_URL, $new_url); 
		return curl_redir_exec($ch); 
	} 
	else 
	{ 
		$curl_loops=0; 
		return $debbbb; 
	} 
} 

// МНОГОПОТОЧНЫЙ curl
function rcurl($urls, $callback, $timeout=10)  // массив ссылок (разумное кол-во), имя функции для вывода потока ($url, $html), таймаут
{
	$cmh = curl_multi_init(); // инициализируем "контейнер" для отдельных соединений (мультикурл)
	$tasks = array(); // массив заданий для мультикурла
	foreach ($urls as $url) // перебираем наши урлы
	{
		$ch = curl_init('http://'.$url); // инициализируем отдельное соединение (поток)
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // если будет редирект - перейти по нему
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // возвращать результат
		curl_setopt($ch, CURLOPT_HEADER, 0); // не возвращать http-заголовок
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout); // таймаут соединения
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout); // таймаут ожидания
		$tasks[$url] = $ch; // добавляем дескриптор потока в массив заданий
		curl_multi_add_handle($cmh, $ch); // добавляем дескриптор потока в мультикурл
	}
	$active = NULL; // количество активных потоков
	do // запускаем выполнение потоков
		$mrc = curl_multi_exec($cmh, $active);
	while($mrc == CURLM_CALL_MULTI_PERFORM);

	while($active && ($mrc == CURLM_OK)) // выполняем, пока есть активные потоки
	{
		if (curl_multi_select($cmh) != -1) // если какой-либо поток готов к действиям
		{
			do // ждем, пока что-нибудь изменится
			{
				$mrc = curl_multi_exec($cmh, $active);
				$info = curl_multi_info_read($cmh); // получаем информацию о потоке
				if ($info['msg'] == CURLMSG_DONE) // если поток завершился
				{
					$ch = $info['handle'];
					$url = array_search($ch, $tasks); // ищем урл страницы по дескриптору потока в массиве заданий
					$callback($url, curl_multi_getcontent($ch)); // выполняем callback-функцию или
					//$tasks[$url] = curl_multi_getcontent($ch); // забираем содержимое
					curl_multi_remove_handle($cmh, $ch); // удаляем поток из мультикурла
					curl_close($ch); // закрываем отдельное соединение (поток)
				}
			}
			while ($mrc == CURLM_CALL_MULTI_PERFORM);
		}
	}
	curl_multi_close($cmh); // закрываем мультикурл
}

// АВТОРИЗАЦИЯ НА ЯНДЕКСЕ, результат работы - формирование файла cookies.txt, который затем используется для доступа к защищенных страницам, функцией file_auth_contents()
function yandexAuth($login, $passwd) 
{
	$url = 'http://passport.yandex.ru/passport?mode=auth'; //УРЛ, куда отправлять данные
	$user_cookie_file = $_SERVER['DOCUMENT_ROOT'].'/uploads/settings/cookies.txt'; //Полный путь до файла, где будем хранить куки
	$idkey = '3121235564020nVDfxvth2';

	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322)');
	curl_setopt($ch, CURLOPT_COOKIEFILE, $user_cookie_file); //Куки раз
	curl_setopt($ch, CURLOPT_COOKIEJAR,  $user_cookie_file); //Куки два
	curl_setopt($ch, CURLOPT_POST, 1); //Будем отправлять POST запрос
	curl_setopt($ch, CURLOPT_POSTFIELDS, "idkey={$idkey}&login={$login}&passwd={$passwd}"); // Формируем и отправляем POST запрос.
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
	$html = curl_exec($ch);
	curl_close($ch);
	return $html; //Возвращаем ответ (на всякий случай)
}
// ПОЛУЧАЕМ СОДЕРЖИМОЕ СТРАНИЦЫ ИСПОЛЬЗУЯ cookies.txt ПОЛУЧЕННЫЕ ПОСЛЕ АВТОРИЗАЦИИ, например функцией yandexAuth()
function file_auth_contents($url, $post=false, $header=false) // ссылка, переменные POST, получить заголовки
{
	$user_cookie_file = $_SERVER['DOCUMENT_ROOT'].'/uploads/settings/cookies.txt'; //Получаем сохраненный после авторизации файл с куками.

	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322)');
	curl_setopt($ch, CURLOPT_COOKIEFILE, $user_cookie_file); //Подставляем куки раз
	curl_setopt($ch, CURLOPT_COOKIEJAR,  $user_cookie_file); //Подставляем куки два
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
	if($post)
	{
		curl_setopt($ch, CURLOPT_POST, 1); //Будем отправлять POST запрос
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post); // Формируем и отправляем POST запрос.
	}
	if($header)
		curl_setopt($ch, CURLOPT_HEADER, true); // возвратит заголовки
	//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // если возвращается перенаправление, то curl сам будет по ним ходить
	$html = curl_exec($ch);
	curl_close($ch);
	return $html; //Возвращаем страницу
}
*/?>