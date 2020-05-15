<div class="preview">
    <div class="d-flex w-100">
        <div class="preview__img">
            <img src="/uploads/good/180x204/<?=$good["id"]?>.jpg" alt="">
        </div>
        <div class="preview__info">
            <a href="<?=\Lib\GoodHelper::GetUrl($good['c_path'],$good['c_link'],$good['link'])?>" class="preview__title"><?=$good['name']?></a>
            <div class="preview__features features d-none d-md-block"><?
                foreach($good["features"] as $_feature) {
                    if(!$_feature['vitrina'])
                        continue;
                    ?>
                    <div class="features__item">
                        <div class="features__key"><?=$_feature["name"]?>:</div>
                        <div class="features__val"><?=$_feature["value"]?> <?=$_feature["izm"]?></div>
                    </div><?
                }
                ?>
            </div>
        </div>
    </div>
</div>