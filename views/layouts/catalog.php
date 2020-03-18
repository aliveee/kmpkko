<?php

ob_start();
//print_r($goods);
include DOCUMENT_ROOT.'/views/common/breadcrumbs.php';

if(!$catalog_home)
    include DOCUMENT_ROOT.'/views/common/categories.php';

if(!$catalog_home)
    include DOCUMENT_ROOT.'/views/common/category_info.php';

if($subcategories)
    include DOCUMENT_ROOT.'/views/common/subcategories.php';

if($goods)
    include DOCUMENT_ROOT.'/views/common/goods.php';

include DOCUMENT_ROOT.'/views/common/works.php';
include DOCUMENT_ROOT.'/views/common/request.php';

$content = ob_get_clean();

include 'template.php';