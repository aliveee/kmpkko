<?php
ob_start();
include DOCUMENT_ROOT.'/views/common/gallery.php';
include DOCUMENT_ROOT.'/views/common/catalog_menu.php';
include DOCUMENT_ROOT.'/views/common/about.php';
include DOCUMENT_ROOT.'/views/common/map.php';
include DOCUMENT_ROOT.'/views/common/works.php';
include DOCUMENT_ROOT.'/views/common/request.php';

$content = ob_get_clean();

include 'template.php';