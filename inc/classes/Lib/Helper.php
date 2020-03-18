<?php
/**
 * Created by PhpStorm.
 * User: Павел
 * Date: 21.03.2019
 * Time: 10:42
 */

namespace Lib;


class Helper
{
    public static function cleanXML($str)
    {
        $str = strip_tags($str);
        $str = htmlspecialchars($str, ENT_QUOTES, 'utf-8');
        return $str;
    }

    /*
     * очистка строки переданной с фронта
     */
    public static function clean($str, $strong=false)
    {
        //$str = html_entity_decode($str);
        if(is_array($str)) {
            foreach($str as $_k=>$_v)
            $str[$_k] = self::clean($_v);
        }else {
            $str = trim(rawurldecode((string)$str), " \r\n	"); // пробел, перевод строки, таб
            if (substr_count($str, '"') + substr_count($str, "'") == substr_count($str, '\"') + substr_count($str, "\'")) // убираем слэши, если строка уже пришла со слешами
                $str = stripslashes($str);
            if ($strong) {
                $str = preg_replace('/  +/', ' ', $str); // убираем повторяющиеся пробелы
                $str = htmlspecialchars(htmlspecialchars_decode($str)); //преобразоваваем теги html
                $str = strtr($str, array('"' => '&quot;', "'" => '&#0039;'));
            } else
                $str = addslashes($str);
        }
        return $str;
    }

    // ПОЛУЧИТЬ СИМВОЛ ИЗ СТРОКИ В КОДИРОВКЕ UTF-8
    public static function char($str, $pos)
    {
        return mb_substr($str, $pos, 1, 'UTF-8');
    }

    // ТРАНСЛИТЕРАЦИЯ СТРОКИ
    public static function translit($str)
    {
        $table = array(
            'А'=>'A','Б'=>'B','В'=>'V','Г'=>'G','Д'=>'D','Е'=>'E','Ё'=>'Yo','Ж'=>'Zh','З'=>'Z','И'=>'I','Й'=>'J','К'=>'K','Л'=>'L','М'=>'M','Н'=>'N','О'=>'O','П'=>'P','Р'=>'R','С'=>'S','Т'=>'T','У'=>'U','Ф'=>'F','Х'=>'H','Ц'=>'C','Ч'=>'Ch','Ш'=>'Sh','Щ'=>'Csh','Ь'=>'','Ы'=>'Y','Ъ'=>'','Э'=>'E','Ю'=>'Yu','Я'=>'Ya',
            'а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'yo','ж'=>'zh','з'=>'z','и'=>'i','й'=>'j','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'h','ц'=>'c','ч'=>'ch','ш'=>'sh','щ'=>'csh','ь'=>'','ы'=>'y','ъ'=>'','э'=>'e','ю'=>'yu','я'=>'ya'
        );
        return str_replace(array_keys($table), array_values($table), $str);
    }

    public static function slug($str, $trans=true)
    {
        $s = '  абвгдеёжзийклмнопрстуфхчцшщэюяыъьэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЧЦШЩЭЮЯЫЪЬЭЮЯabcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890-_';
        $str2 = '';
        for($i=0; $i<mb_strlen($str); $i++)
            $str2 .= strpos($s, self::char($str,$i)) ? self::char($str,$i) : '-';
        $str = preg_replace('#-+#', '-', $str2); // убираем повторяющиеся -
        $str = trim($str, '-');
        $str = mb_strtolower($str, 'UTF-8');
        return $trans ? self::translit($str) : $str;
    }

    /**
     * подставить значения в шаблон
     * @param $template
     * @param $map
     * @return mixed
     */
    public static function parseThroughTemplate($template, $map){
        $result = $template;
        foreach($map as $k=>$v){
            $result = str_replace("{".$k."}",$v,$result);
        }
        return $result;
    }

    public static function getIP(){
        $ip = "";
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
}