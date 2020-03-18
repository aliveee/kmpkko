<?php

ob_start();
//print_r($goods);
include DOCUMENT_ROOT.'/views/common/breadcrumbs.php';

include DOCUMENT_ROOT.'/views/common/product_info.php';
include DOCUMENT_ROOT.'/views/common/product_content.php';

include DOCUMENT_ROOT.'/views/common/works.php';
include DOCUMENT_ROOT.'/views/common/request.php';

$content = ob_get_clean();

include 'template.php';