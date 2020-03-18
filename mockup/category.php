<?php
ob_start();
include "views/breadcrumbs.php";
include "views/subcategories.php";
include 'views/category_info.php';
include 'views/previews.php';
include "views/works.php";
include 'views/request.php';

$content = ob_get_clean();

include 'template.php';