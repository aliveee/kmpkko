<?php
ob_start();
include "views/breadcrumbs.php";
include 'views/product_info.php';
include 'views/product_content.php';
include "views/works.php";
include 'views/request.php';

$content = ob_get_clean();

include 'template.php';