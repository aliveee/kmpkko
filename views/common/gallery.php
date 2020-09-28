<?
if($banners) {
    ?><div class="container p-0 pl-sm-3 pr-sm-3">
        <div class="gallery d-none d-sm-block"><?
            foreach ($banners as $_banner) {
                ?>
                <div class="gallery__item" style="background-image:url('/uploads/ebnr/<?= $_banner["id"] ?>.jpg')">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="gallery__item-title ml-3">
                                <?= $_banner["text"] ?>
                            </div><?
                            if ($_banner['link']) {
                                ?><a class="gallery__item-button ml-3" href="<?= $_banner['link'] ?>">
                                <?= $_banner["button"] ?>
                                </a><?
                            }
                            ?></div>
                    </div>
                </div><?
            }
        ?></div>
        <div class="gallery d-block d-sm-none"><?
            foreach ($banners as $_banner) {
                ?>
                <div class="gallery__item">
                    <div class="row align-items-center position-absolute">
                        <div class="col">
                            <div class="gallery__item-title ml-3">
                                <?= $_banner["text"] ?>
                            </div><?
                            if ($_banner['link']) {
                                ?><a class="gallery__item-button ml-3" href="<?= $_banner['link'] ?>">
                                <?= $_banner["button"] ?>
                                </a><?
                            }
                            ?></div>
                    </div>
                    <img src="/uploads/ebnr/<?= $_banner["img_m"] ?>" />
                </div><?
            }
        ?></div>
    </div><?
}