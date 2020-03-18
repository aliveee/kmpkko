<? ob_start('ob_gzhandler', 9); ?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<meta http-equiv="Pragma" content="no-cache"/>
	<title>Администрирование</title>
	<link rel="shortcut icon" type="image/x-icon" href="favicon.ico"/>

	<script src="/admin/libs/jquery/jquery.js" type="text/javascript"></script>
	<script src="/admin/libs/jeditable/jquery.jeditable.js" type="text/javascript"></script>
	<script src="/admin/libs/maskedinput/jquery.maskedinput.js" type="text/javascript"></script>
	<script src="/admin/libs/tablednd/jquery.tablednd.js" type="text/javascript"></script>

	<script src="/admin/libs/placeholder/jquery.placeholder.js" type="text/javascript"></script>

	<link href="/admin/libs/autocomplete/styles.css" rel="stylesheet" type="text/css"/>
	<link href="/admin/js/chosen.min.css" rel="stylesheet" type="text/css"/>
	<script src="/admin/js/chosen.jquery.min.js" type="text/javascript"></script>

	<script src="/admin/libs/autocomplete/jquery.autocomplete.js" type="text/javascript"></script>

	<script src="/admin/ckeditor/ckeditor.js" type="text/javascript"></script>
	<script src="/admin/ckfinder/ckfinder.js" type="text/javascript"></script>

	<script src="/admin/libs/jquery-mousewheel/jquery.mousewheel.min.js" type="text/javascript"></script>
	<link rel="stylesheet" href="/admin/libs/fancybox/jquery.fancybox.css" />
	<script src="/admin/libs/fancybox/jquery.fancybox.pack.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" integrity="sha256-EH/CzgoJbNED+gZgymswsIOrM9XhIbdSJ6Hwro09WE4=" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" integrity="sha256-c4gVE6fn+JRKMRvqjoDp+tlG4laudNYrXI1GncbfAYY=" crossorigin="anonymous"></script>
    <script>
        $(function(){
            $(".chosen").chosen({allow_single_deselect: true});
        })
    </script>
	
	<script src="/admin/js/utils.js" type="text/javascript"></script>
	<script src="/admin/js/special.js" type="text/javascript"></script>
	<script src="/admin/inc/special.js" type="text/javascript"></script>


	<script src="/admin/js/fancybox_helper.js" type="text/javascript"></script>

	<link href="/admin/inc/style.css" rel="stylesheet" type="text/css"/>
	
<style>
 .autocomplete-suggestions {width:700px!important;}
</style>
	<?=paintContent().privMenu()?>
</head>

<body style="background:url(/admin/img/bg_body.gif) left repeat-y;">
<iframe src="/admin/inc/none.html" name="iframe" id="iframe" style="display:none; width:100%; height:500px; background-color:white;"></iframe>
<iframe src="/admin/inc/none.html" name="ajax" id="ajax" style="display:none; width:100%; height:500px; background-color:white;"></iframe>
<table width="100%" height="100%">
	<tr>
		<td valign="top">
			<?=showAdminHead(false)?>
			<table width="100%" bgcolor="#FFFFFF">
				<tr>
					<td><div style="background:url(/admin/img/bg_diz.png) 10px -<?=$rubric_img?>px no-repeat; height:29px; padding:3px 0 0 60px; color:#5373AC; font:20px Arial;"><b><?=@$rubric?></b></div></td>
					<td align="right" height="60"><img src="/admin/img/load.gif" id="imgLoad" style="visibility:hidden;" width="50" height="50"></td>
				</tr>
			</table>
			<table width="100%" style="border-top:#5373AC 1px solid;">
				<tr valign="top">
					<td style="padding:25px 5px 10px;">
						<div style="width:190px; border-bottom:1px solid #CCCCCC; padding-bottom:5px;" class="c2"><b>Навигация</b></div>
						<div class="lnk_left" style="padding:5px 0 0 8px; line-height:150%;"><?=@$left_menu?></div>
					<?	if(isset($search))
						{	?>
							<div style="border-bottom:1px solid #CCCCCC; padding:25px 0 5px;" class="c2"><b>Поиск</b></div>
							<form style="margin-top:7px;">
								<input type="hidden" name="childs" value="1">
								<center><input name="search" value="<?=htmlspecialchars(stripslashes($search))?>" style="width:90%;"></center>
								<div style="padding-top:5px;" align="center"><?=btnAction('Search')?></div>
							</form>
					<?	}	?>
					</td>
					<td width="100%" style="padding:16px;">
						<? if($topMenu=topMenu(@$top_menu)) { ?><div class="lnk_top"><?=$topMenu?></div><? } ?>
						<?=@$content?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td valign="bottom">
			<div style="border-top:#CCCCCC 1px solid; padding:15px; background-color:white;" align="right">
				Разработчик: <a href="http://www.beontop.ru" target="_blank">www.beontop.ru</a>
			</div>
		</td>
	</tr>
</table>
<a href="javascript://" style="background:#EAFAFF; border-radius:10px 10px 0 0; padding:5px 10px; border:1px solid #CCC; border-bottom:none; cursor:pointer; position:fixed; bottom:0; left:50px; text-decoration:none;" align="center" onClick="$('html,body').animate({scrollTop:0},500);"><b>^ наверх ^</b></a>
</body>
</html>
  <script type="text/javascript">
    jQuery(function(){
      jQuery('.good_s').autocomplete({
        serviceUrl:'/admin/inc/action2.php?action=search_g',
        deferRequestBy: 100, // задержка между запросами
        //maxHeight: 200, // Максимальная высота списка подсказок, в пикселях
        zIndex: 9999, // z-index списка
        minChars: 3, // Минимальная длина запроса для срабатывания автозаполнения
        onSelect: function(suggestions){
           str=(suggestions.data).split(':::');
           cur_id=str[0]; cur_price=str[1];
           $(this).parents('tr').first().find('.cur_price').html(cur_price);
           
           $cur_good=$(this).data('cur');
           $(this).parents('tr').first().find('input[type=hidden]').val(cur_id);
        }
      });
      
    });
  //$('select').chosen();
  </script>
<?=ob_get_clean()?>