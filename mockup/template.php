<?
header("Content-Type: text/html; charset=utf-8");
?>
<!doctype html >
<html lang="en">
<head>
    <title> Title</title>
    <!--Required meta tags-->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!--Bootstrap CSS-->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/css.css">
    <link rel="stylesheet" href="/css/css-xl.css">
    <link rel="stylesheet" href="/css/css-lg.css">
    <link rel="stylesheet" href="/css/css-md.css">
    <link rel="stylesheet" href="/css/css-sm.css">
    <link rel="stylesheet" href="/css/css-xs.css">
</head>
<body>
    <? include 'views/header.php' ?>
    <?=$content?>
    <? include 'views/footer.php' ?>
    <? include 'views/scripts.php'?>
</body>
</html>