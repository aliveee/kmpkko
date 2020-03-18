<?
//print_r($_SERVER);exit;
require_once('common.php');
\Lib\App::db();

define("WATERMARK_MIN_WIDTH",600);
define("WATERMARK_MIN_HEIGHT",400);

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

/**
 * @param $src путь к картинки
 * @param $width ширина
 * @param int $height высота
 * @param string $src_save путь для сохранения (если не задан, возвращается контент картинки)
 * @param string $watermark водяной знак (png)
 * @param bool $max делать картинку максимальной относительно сторон
 * @return bool
 * ИЗМЕНЕНИЕ РАЗМЕРОВ КАРТИНКИ
 */
function imgResize($src, $width, $height=0, $src_save='', $watermark='', $max=false, $format="jpg")
{
    $size = @getimagesize($src);
    if($size === false)
        return false;

    $type = $size['mime'];
    $mime_format = mb_strtolower(substr($type, strpos($type, '/')+1));
    if($mime_format == 'bmp')
        include('bmp.php');

    $icfunc = 'imagecreatefrom'.$mime_format;
    if (!function_exists($icfunc))
        return false;

    list($width, $height) = getRatioSize($size, array((int)$width, (int)$height), $max);

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
    /*if($watermark && ($width > WATERMARK_MIN_WIDTH || $height > WATERMARK_MIN_HEIGHT)) // накладываем водяной знак на оригинальную картинку
    {
        $size_wm = $size[0] > 800 ? $size : array(800, round(800*$size[1]/$size[0])); // подготавливаем размер картинки
        $idest = imagecreatetruecolor($size_wm[0], $size_wm[1]);
        imagecopyresampled($idest, $isrc, 0, 0, 0, 0, $size_wm[0], $size_wm[1], $size[0], $size[1]);
        $size = $size_wm;
        $isrc = $idest;
        $znak_wh = getimagesize($watermark); // накладываем знак
        $znak = imagecreatefrompng($watermark);

        $white = imagecolorallocate($isrc,  255, 255, 255);
        imagefilledrectangle($isrc, 0, 0, $width, $height, $white);

        imagecopy($isrc, $znak, round(($size[0]-$znak_wh[0])/2), round(($size[1]-$znak_wh[1])/2), 0, 0, $znak_wh[0], $znak_wh[1]);
        imagedestroy($znak);
    }*/
    $idest = imagecreatetruecolor($width, $height);
    /*switch($type){
        case 'image/jpeg':
       */     //imagefill($idest, 0, 0, hexdec('FFFFFF'));
            if($format=="jpg") {
                if ($type == "image/png") {
                    $white = imagecolorallocate($idest, 255, 255, 255);
                    imagefilledrectangle($idest, 0, 0, $width, $height, $white);
                }
                imagecopyresampled($idest, $isrc, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
            }elseif($format="png"){
                imagealphablending($idest, FALSE);
                imagesavealpha($idest, TRUE);
                imagefill($idest, 0, 0, hexdec('FFFFFF'));
                imagecopyresampled($idest, $isrc, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
            }

            //копируем водяной знак
            if($watermark && ($width > WATERMARK_MIN_WIDTH || $height > WATERMARK_MIN_HEIGHT)) // накладываем водяной знак на оригинальную картинку
            {
                $znak = imagecreatefrompng($watermark);
                $znak_wh = getimagesize($watermark);
                imagecopy($idest, $znak, round(abs($width-$znak_wh[0])/2), round(abs($height-$znak_wh[1])/2), 0, 0, $znak_wh[0], $znak_wh[1]);
                imagedestroy($znak);
            }

            if($format=="jpg")
                $ir = imagejpeg($idest, $src_save, 90);
            else if($format="png")
                $ir = @imagepng($idest,$src_save);
        /*    break;
        /*case 'image/png':
            imagealphablending($idest, FALSE);
            imagesavealpha($idest, TRUE);
            imagefill($idest, 0, 0, hexdec('FFFFFF'));
            imagecopyresampled($idest, $isrc, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
            $ir = @imagepng($idest,$src_save);
            break;
        case 'image/gif':
            imagecopyresampled($idest, $isrc, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
            $background = imagecolorallocate($idest, 0, 0, 0);
            imagecolortransparent($idest, $background);
            $ir = @imagegif($idest,$src_save);
            break;
    }*/

    imagedestroy($isrc);
    imagedestroy($idest);

    return $ir;
}


$src = mysqli_real_escape_string(\Lib\App::get('db'), $_GET['src']);
list($width, $height, $id) = explode('x', strpos($_GET['wh'], 'x') ? $_GET['wh'] : 'xx' . $_GET['wh']);
if ($id)
    $src = str_replace(basename($src), $id . '.' . pathinfo($src, PATHINFO_EXTENSION), $src); // если задано ID, меняем фальш-имя файла на реальное

$max = isset($_GET['max']); // делать картинку максимальной относительно сторон

if(@$_GET['ext']){
    $ext = mysqli_real_escape_string(\Lib\App::get('db'),$_GET['ext']);// настоящее расширение картинки (для случая когда пишим .php)
    $src = str_replace('.php', ".{$ext}", $src);
}

$category = @$_GET["category"];
$good_link = @$_GET["good_link"];

if($category!='catalog') {
    if (!file_exists(DOCUMENT_ROOT . $src)) // изображение отсутствует
    {
        $src = str_replace(".jpg", ".png", $src);
    }
//echo $src;exit;
    if (!file_exists(DOCUMENT_ROOT . $src)) // изображение отсутствует
    {
        $src = str_replace(".png", ".gif", $src);
    }
}

if (!file_exists(DOCUMENT_ROOT . $src)) // изображение отсутствует
{
    if ($category == 'good') {
        $good_model = new \Model\Stp\Good();
        $good = $good_model->getByLink($good_link);
        if ($good) {
        }
        //ищем по старой ссылке
        $src_old = '/uploads/good/' . $good["id"] . ".jpg";
        if (file_exists(DOCUMENT_ROOT . $src_old)) {
            //копируем по новому пути
            mkdir(DOCUMENT_ROOT . '/uploads/good/' . $good_link);
            copy(DOCUMENT_ROOT . $src_old, DOCUMENT_ROOT . $src);
        }
    }
}
if (!file_exists(DOCUMENT_ROOT . $src)) // изображение отсутствует
{
    //echo $category;exit;
    if (($category=='good'||$category=='catalog') && file_exists(DOCUMENT_ROOT . '/uploads/settings/noimg.jpg'))
        $src = $noimg = '/uploads/settings/noimg.jpg';
    else {
        header("HTTP/1.0 404 Not Found");
        exit;
        //header('Content-type:image/gif');
        //die(file_get_contents(DOCUMENT_ROOT . '/img/none.gif'));
    }
}
if ($category == 'good')
    $format = "jpg";
else
    $format = pathinfo($src,PATHINFO_EXTENSION);
//echo $category;
$arr = explode('/', $src);
$dir = '/uploads/thumb/' . $category . "-{$width}x{$height}/";
$file = pathinfo($src,PATHINFO_FILENAME).".".$format;
$size = filesize(DOCUMENT_ROOT . $src);
//echo $src;exit;
if ($category=='good') // watermark для изображений товаров
{
    $dir.=$good_link."/";
    $watermark = file_exists(DOCUMENT_ROOT . '/uploads/settings/watermark.png') && !@$noimg ? DOCUMENT_ROOT . '/uploads/settings/watermark.png' : '';
    if (!$width && !$height || !$watermark)
    {
        header('Content-type:image/gif');
        echo file_get_contents(DOCUMENT_ROOT . $src);
        exit;
    }
}
//echo $dir;exit;
if (!file_exists(DOCUMENT_ROOT . $dir . $size . '-' . $file) || !filesize($DOCUMENT_ROOT . $dir . $size . '-' . $file)) // делаем thumb картинки
{
    if (!is_dir(DOCUMENT_ROOT . $dir)) {
        mkdir(DOCUMENT_ROOT . $dir, 0777);
    }
    else
        chmod(DOCUMENT_ROOT . $dir, 0777);

    if (!imgResize(DOCUMENT_ROOT . $src, $width, $height, DOCUMENT_ROOT . $dir . $size . '-' . $file, @$watermark, $max, $format))
        die();
}

header('Content-type:image/jpeg');
echo file_get_contents(DOCUMENT_ROOT . $dir . $size . '-' . $file);
?>