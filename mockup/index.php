<?php
ob_start();
include 'views/gallery.php';
include 'views/products.php';
include 'views/about.php';
include 'views/map.php';
include "views/works.php";
include 'views/request.php';

$content = ob_get_clean();

include 'template.php';