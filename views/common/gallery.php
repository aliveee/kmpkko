<div class="gallery"><?
    if($banners)
    foreach ($banners as $_banner)
    ?><div class="gallery__item" style="background-image:url('/uploads/ebnr/<?=$_banner["id"]?>.jpg')">
        <div class="container">
            <div class="row align-items-center">
                <div class="col">
                    <div class="gallery__item-title">
                        <?=$_banner["text"]?>
                    </div>
                    <a class="gallery__item-button" href="<?=$_banner['linkarticles.php']?>">
                        <?=$_banner["button"]?>
                    </a>
                </div>
            </div>
        </div>
    </div><?
?></div>