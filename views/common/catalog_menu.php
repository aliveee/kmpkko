<?php
//print_R($catalog_menu);
?><div class="categories">
    <div class="container">
        <div class="row"><?
            foreach ($catalog_menu as $_menu) {
                ?>
                <div class="col-12 col-sm-6 col-lg-3">
                    <a class="categories__item" href="<?=\Lib\CatalogHelper::GetUrl($_menu["path"],$_menu["link"])?>">
                        <span><img src="/uploads/catalog/256x256/<?=$_menu["id"]?>.jpg" /></span>
                        <span><?=$_menu["name"]?></span>
                    </a>
                </div><?
            }
        ?></div>
    </div>
</div>