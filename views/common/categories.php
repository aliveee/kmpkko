<div class="subcategories">
    <div class="container">
        <div class="row"><?
            foreach($catalog_menu as $_catalog) {
                ?>
                <div class="col-12 col-md-6 col-xl-3">
                    <a href="<?=\Lib\CatalogHelper::GetUrl($_catalog["path"],$_catalog["link"])?>" class="subcategorys__item active">
                        <span><img src="/uploads/catalog/80x80/<?=$_catalog['id']?>.jpg"/></span>
                        <span><?=$_catalog['name']?></span>
                    </a>
                </div><?
            }
        ?></div>
    </div>
</div>