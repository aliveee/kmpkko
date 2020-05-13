<?
	// ЛЕВОЕ МЕНЮ
	ob_start();	?>	
	<a href="pages.php" style="background-position:0 -2593px;" top_menu="pages">Страницы</a>
  <a href="catalog.php" style="background-position:0 -928px;" top_menu="catalog">Каталог</a>
  <!--<a href="reviews.php" style="background-position:0 -1632px;">Отзывы</a>-->
  <!--<ba href="index_specials.php" style="background-position:0 -928px;">Спецпредл. на главной</a-->
  <a href="bnr.php" style="background-position:0 -96px;">Баннеры</a>
    <a href="articles.php" style="background-position:0 -96px;">Статьи</a>
  <!--<ba href="articles.php" style="background-position:0 -1312px;">Статьи</a>-->
  <!--<a href="news.php" style="background-position:0 -416px;">Новости</a>
    <a href="articles.php" style="background-position:0 -416px;">Статьи</a>
	<a href="orders.php" top_menu="orders"  style="background-position:0 -352px;">Заказы</a>-->
	<!--<ba href="zayav.php" style="background-position:0 -352px;">Ремонты</a>-->
	<!--<ba href="faq.php" style="background-position:0 -352px;">Вопрос/ответ</a>-->
	<!--<a href="users.php" top_menu="users" style="background-position:0 -2976px;">Клиенты</a>
    <a href="managers.php" style="background-position:0 -2976px;">Менеджеры</a>
    <a href="priv.php" style="background-position:0 -1376px;">Права доступа</a>-->
    <!--<ba href="export_import.php" style="background-position:0 -3040px;">Экспорт/Импорт</a-->
	<!--<ba href="certificates.php" style="background-position:0 -2144px;">Сертификаты</a>-->
  <a href="counters.php" style="background-position:0 -544px;">Счетчики</a>
	<a href="redirect.php" style="background-position:0 -1952px;">Редиректы</a>
  <a href="settings.php" style="background-position:0 -1120px;">Настройки</a>
    <!--<a href="import_goods.php" top_menu="import" style="background-position:0 -1120px;">Импорт/экспорт</a>-->

	<!--<ba href="visit.php" style="background-position:0 -608px;">Статистика</a-->
<?	$left_menu = ob_get_clean();

	// ВЕРХНЕЕ МЕНЮ
	function topMenu($top_menu)
	{	
		ob_start(); 
		switch($top_menu)
		{
			case 'orders':
            ?>
				<a href="orders.php">Заказы</a> 
				&nbsp; | &nbsp; <a href="status.php">Статусы</a>
				&nbsp; | &nbsp; <a href="letter.php">Сообщения</a>
                &nbsp; | &nbsp; <a href="notifications.php">Уведомления</a>
            <?
            break;

            case 'users':
                ?>
                <a href="users.php">Учетные записи</a>
                &nbsp; | &nbsp; <a href="profiles.php">Профили</a>
                &nbsp; | &nbsp; <a href="addresses.php">Адреса</a>
                <?
                break;
            
            case 'catalog':	?>
				<a href="catalog.php">Разделы</a> 
				<!--&nbsp; | &nbsp; <a href="makers.php">Производители</a>
                &nbsp; | &nbsp; <a href="suppliers.php">Поставщики</a>-->
				&nbsp; | &nbsp; <a href="goods.php">Товары</a>
				&nbsp; | &nbsp; <a href="features.php">Характеристики</a>
				<!--&nbsp; | &nbsp; <a href="ymarket.php">Яндекс-Маркет</a>
				&nbsp; | &nbsp; <a href="shops.php">Магазины</a>
                &nbsp; | &nbsp; <a href="catalog_brand.php" style="background-position:0 -928px;" top_menu="catalog">Каталог-бренд</a>-->
				<!--&nbsp; | &nbsp; <ba target="_blank" href="/import_count_new.php">Прогрузить остатки товаров(/import/test.TXT)</a>-->
                <!--
				&nbsp; | &nbsp; <ba href="images_path.php">Загрузить фото товаров</a>
				&nbsp; | &nbsp; <ba href="process_goods_images.php" onclick="return sure()">Обработать фото товаров</a>
                -->
			<?	break;

			case 'pages':	?>
				<a href="pages.php">Страницы</a>
                &nbsp; <!--| &nbsp; <ba href="preim.php">Преимущества</a>-->
			<?	break;

			case 'delivery':	?>
				<a href="delivery_import.php">Справочник городов</a> 
				&nbsp; | &nbsp; <a href="delivery.php">Стоимость доставки</a>
			<?	break;

            case 'import': ?>
                <a href="import_goods.php" >Импорт цен</a>
                &nbsp; | &nbsp;<a href="import_goods_full.php">Универсальный импорт</a>
                &nbsp; | &nbsp;<a href="import_servis.php">Импорт сервисных центров</a>
                &nbsp; | &nbsp;<a href="export.php">Экспорт</a>
<?
                break;
			
		}
		return ob_get_clean();
	}
?>