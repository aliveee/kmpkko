<?php

require __DIR__.'/../vendor/autoload.php';
require_once('config-local.php');

if(!DEBUG) {
    error_reporting(0);
}else{
    error_reporting(E_ERROR);
}

define('IS_CONSOLE', PHP_SAPI == 'cli');
if(IS_CONSOLE){
    $dr = substr(__DIR__, 0,strlen(__DIR__)-strlen(DIRECTORY_SEPARATOR.pathinfo(__DIR__,PATHINFO_BASENAME)));
    define('DOCUMENT_ROOT', $dr);
}else {
    define("DOCUMENT_ROOT", $_SERVER["DOCUMENT_ROOT"]);
}

define(
    'IS_AJAX',
    isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == "XMLHttpRequest"
);

spl_autoload_register('AutoLoader');

/**
 * @param $className
 * @throws Exception
 */
function AutoLoader($className)
{
    $class_file_exists = false;
    $className = str_replace('\\',DIRECTORY_SEPARATOR,$className);
    $path = DOCUMENT_ROOT.'/inc/classes/'.$className;
    foreach ( ['','.class','.model','.layout','.action'] as $t )
        if ( file_exists($file = $path.$t.'.php') )
        {
            require_once $file;
            $class_file_exists = true;
            break;
        }

    if ( !$class_file_exists )
        throw new Exception("File for class $className not found.");
}