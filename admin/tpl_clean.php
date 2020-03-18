<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<title><?=$_SERVER['SERVER_NAME']?> - <?=@$title ? $title : @$rubric?></title>
	<link rel="shortcut icon" type="image/x-icon" href="favicon.ico"/>

	<script src="/admin/libs/jquery/jquery.js" type="text/javascript"></script>
	<script src="/admin/libs/jeditable/jquery.jeditable.js" type="text/javascript"></script>
	<script src="/admin/libs/tablednd/jquery.tablednd.js" type="text/javascript"></script>

	<script src="/admin/libs/fancybox/jquery.fancybox.pack.js"></script>

	<script src="/admin/ckeditor/ckeditor.js" type="text/javascript"></script>
	<script src="/admin/ckfinder/ckfinder.js" type="text/javascript"></script>
	<script src="/admin/js/utils.js" type="text/javascript"></script>
	<script src="/admin/js/special.js" type="text/javascript"></script>
	<script src="/admin/inc/special.js" type="text/javascript"></script>

	<script src="/admin/js/fancybox_helper.js" type="text/javascript"></script>

	<link rel="stylesheet" href="/admin/libs/fancybox/jquery.fancybox.css" />

	<link rel="stylesheet" type="text/css" href="inc/style.css"/>
	<?=paintContent()?>
</head>
<body>
<iframe src="/admin/inc/none.html" name="iframe" id="iframe" style="display:none; width:100%;"></iframe>

<?=@$content?>

</body>
</html>
<?=ob_get_clean()?>