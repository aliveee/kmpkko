<?
class PHPMasterZip
{
	function ZipDir($directory, $zipfilename='')
	{
		if(!@function_exists('gzcompress'))
			return 0;
		
		if(!$zipfilename)
			$zipfilename = $directory.".zip";
		
		$curdir = getcwd();
		if (is_array($directory)) 
			$filelist = $directory;
		else 
			$filelist = $this -> GetFileList($directory);
		
		if ((!empty($directory))&&(!is_array($directory))&&(file_exists($directory)))
			chdir($directory);
		else 
			chdir($curdir);

		if (count($filelist)>0)
		{
			foreach($filelist as $filename)
				if (is_file($filename))
				{
					$fd = fopen ($filename, "r");
					$content = fread ($fd, filesize ($filename));
					fclose ($fd);

					if (is_array($dir)) $filename = basename($filename);
					$this -> addFile($content, $filename);
				}

			$out = $this -> file();

			chdir($curdir);
			$fp = fopen($zipfilename, "w");
			fwrite($fp, $out, strlen($out));
			fclose($fp);
		}
		return 1;
	}

	function ZipFile($filename, $zipfilename="")
	{
		if(!@function_exists('gzcompress') || !file_exists($filename))
			return 0;

		if(!$zipfilename)
			$zipfilename = $filename.".zip";

		$fd = fopen ($filename, "r");
		$content = fread ($fd, filesize ($filename));
		fclose ($fd);

		$filename = basename($filename);
		$this -> addFile($content, $filename);

		$out = $this -> file();

		$fp = fopen($zipfilename, "w");
		fwrite($fp, $out, strlen($out));
		fclose($fp);

		return 1;
	}

	function ZipContent($content, $filename, $zipfilename='')
	{
		if(!@function_exists('gzcompress'))
			return 0;

		if (trim($content)!='')
		{
			$this -> addFile($content, $filename);
			$out = $this -> file();
			if($zipfilename!='')
			{
				$fp = fopen($zipfilename, "w");
				fwrite($fp, $out, strlen($out));
				fclose($fp);
				return 1;
			}
			else
				return $out;
		}
	}

	function GetFileList($dir, $pref="")
	{
		if(file_exists($dir))
		{
			$dh = opendir($dir);
			while($files = readdir($dh))
				if ($files!="." && $files!="..") 
				{
					if (is_dir("$dir/$files")) 
						$file = array_merge((array)$file, (array)$this -> GetFileList("$dir/$files", "$pref$files/"));
					else 
						$file[] = $pref.$files;
				}
			closedir($dh);
		}
		return $file;
	}

	var $datasec      = array();
	var $ctrl_dir     = array();
	var $eof_ctrl_dir = "\x50\x4b\x05\x06\x00\x00\x00\x00";
	var $old_offset   = 0;

	function unix2DosTime($unixtime = 0)
	{
		$timearray = ($unixtime == 0) ? getdate() : getdate($unixtime);
		if($timearray['year'] < 1980)
		{
			$timearray['year'] = 1980;
			$timearray['mon'] = 1;
			$timearray['mday'] = 1;
			$timearray['hours'] = 0;
			$timearray['minutes'] = 0;
			$timearray['seconds'] = 0;
		}
		return (($timearray['year'] - 1980) << 25) | ($timearray['mon'] << 21) | ($timearray['mday'] << 16) |
			($timearray['hours'] << 11) | ($timearray['minutes'] << 5) | ($timearray['seconds'] >> 1);
	}

	function addFile($data, $name, $time = 0)
	{
		$name     = str_replace('\\', '/', $name);
		$dtime    = dechex($this->unix2DosTime($time));
		$hexdtime =	'\x'.$dtime[6].$dtime[7]
				.'\x' . $dtime[4] . $dtime[5]
				.'\x' . $dtime[2] . $dtime[3]
				.'\x' . $dtime[0] . $dtime[1];
		eval('$hexdtime = "' . $hexdtime . '";');

		$fr="\x50\x4b\x03\x04";
		$fr.="\x14\x00";
		$fr.="\x00\x00";
		$fr.="\x08\x00";
		$fr.=$hexdtime;

		$unc_len=strlen($data);
		$crc=crc32($data);
		$zdata=gzcompress($data);
		$c_len=strlen($zdata);
		$zdata=substr(substr($zdata, 0, strlen($zdata) - 4), 2);
		$fr.=pack('V', $crc);
		$fr.=pack('V', $c_len);
		$fr.=pack('V', $unc_len);
		$fr.=pack('v', strlen($name));
		$fr.=pack('v', 0);
		$fr.=$name;
		$fr .= $zdata;

		$fr .= pack('V', $crc);
		$fr .= pack('V', $c_len);
		$fr .= pack('V', $unc_len);

		$this -> datasec[]=$fr;
		$new_offset=strlen(implode('', $this->datasec));

		$cdrec = "\x50\x4b\x01\x02";
		$cdrec .= "\x00\x00";                // version made by
		$cdrec .= "\x14\x00";                // version needed to extract
		$cdrec .= "\x00\x00";                // gen purpose bit flag
		$cdrec .= "\x08\x00";                // compression method
		$cdrec .= $hexdtime;                 // last mod time & date
		$cdrec .= pack('V', $crc);           // crc32
		$cdrec .= pack('V', $c_len);         // compressed filesize
		$cdrec .= pack('V', $unc_len);       // uncompressed filesize
		$cdrec .= pack('v', strlen($name) ); // length of filename
		$cdrec .= pack('v', 0 );             // extra field length
		$cdrec .= pack('v', 0 );             // file comment length
		$cdrec .= pack('v', 0 );             // disk number start
		$cdrec .= pack('v', 0 );             // internal file attributes
		$cdrec .= pack('V', 32 );            // external file attributes - 'archive' bit set

		$cdrec .= pack('V', $this -> old_offset ); // relative offset of local header
		$this -> old_offset = $new_offset;
		$cdrec .= $name;
		$this -> ctrl_dir[] = $cdrec;
	}

	function file()
	{
		$data    = implode('', $this -> datasec);
		$ctrldir = implode('', $this -> ctrl_dir);
		return
			$data.
			$ctrldir.
			$this -> eof_ctrl_dir.
			pack('v', sizeof($this -> ctrl_dir)).
			pack('v', sizeof($this -> ctrl_dir)).
			pack('V', strlen($ctrldir)).
			pack('V', strlen($data)).
			"\x00\x00";
	}
}
/*
ZipDir($directory, $zipfilename="") - эта функция создет архив с директорией $directory и записывает заархивированные данные в архив $zipfilename. 
ZipFile($filename, $zipfilename="") - эта функция создает архив с файлом $filename и записывает заархивированные данные в архив $zipfilename. 
ZipContent($content, $filename, $zipfilename=”) - эта функция архивирует текстовое содержимое $content в файл $filename. Примечание: $filename - это имя файла в архиве! $zipfilename - имя архива, в который надо записать содержимое. Если оно не указывается, то функция возвращает содержимое архива. 

Пример использования функции: 
// Чтобы программа не выдала ошибки 
// или замечания и не запортила архив 
error_reporting(0); 

// Подключаем php-класс 
include(’phpzip.class.php’); 

// Создаём экземпляр класса 
$zip=new PHPMasterZip(); 

// Что мы будем записывать в файл 
$to_write=’Привет! Это файл readme.txt!’; 

// Архивируем содержимое функцией ZipContent() 
// в файл zipped.zip 
$zipdata=$zip->ZipContent($to_write, ‘readme.txt’, ‘zipped.zip’); 

Этот пример архивирует файл readme.txt в файл zipped.zip 
А вот ещё один пример: 

// Чтобы программа не выдала ошибки или замечания и не запортила архив 
error_reporting(0); 

// Подключаем php-класс 
include(’phpzip.class.php’); 

// Создаём экземпляр класса 
$zip=new PHPMasterZip(); 

// Что мы будем записывать в файл 
$to_write=’Привет! Это файл readme.txt!’; 

// Заголовки файла 
header(’Content-Type: application/zip’); 

// Архивируем содержимое функцией ZipContent() в файл zipped.zip 
echo $zipdata=$zip->ZipContent($to_write, ‘readme.txt’);
*/
?>