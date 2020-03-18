<?php

try {
    require_once('./inc/common.php');
    \Lib\App::start(DOCUMENT_ROOT,ROOT_PATH);
} catch ( Exception $e ) {
    if(DEBUG) {
        echo "<pre>" . $e->getMessage()."<br/>";
        echo $e->getTraceAsString() . "</pre>";
    }
}