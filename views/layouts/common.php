<?php

ob_start();
//print_r($goods);
include DOCUMENT_ROOT.'/views/common/breadcrumbs.php';

?><div class="container mt-5 mb-5">
    <h1><?=$page['h1']?></h1>
    <div class='text'><?=$page['text']?></div>
</div><?

$content = ob_get_clean();

include 'template.php';