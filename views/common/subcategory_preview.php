<div class="preview h-100">
    <div class="d-flex w-100">
        <div class="preview__img">
            <a href="<?=\Lib\CatalogHelper::GetUrl($subcategory["path"],$subcategory["link"])?>">
                <img src="/uploads/catalog/180x204/<?=$subcategory["id"]?>.jpg" alt=""></a>
        </div>
        <div class="preview__info">
            <a href="<?=\Lib\CatalogHelper::GetUrl($subcategory["path"],$subcategory["link"])?>" class="preview__title"><?=$subcategory["name"]?></a>
            <div class="preview__features features d-none d-md-block">
                <?=$subcategory["introtext"]?>
            </div>
        </div>
    </div>
</div>