<div class="product-info">
    <div class="container bg-light-grey p-32 p-md-24">
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="row mb-lg-0 mb-0 mb-md-3">
                    <div class="col-12 product-info__img-wrp">
                        <img src="/uploads/catalog/512x-/<?=$good["id"]?>_info.jpg" />
                    </div>
                    <div class="col-12 d-md-none mt-4">
                        <div class="h"><?=@$good["h1"]?></div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <h1 class="h d-none d-md-block mb-32"><?=@$good["h1"]?></h1>
                <div class="features"><?
                    foreach($features as $_feature) {
                        ?><div class="features__item">
                            <div class="features__key"><?=$_feature["name"]?>:</div>
                            <div class="features__val"><?=$_feature["value"]?> <?=$_feature["izm"]?></div>
                        </div><?
                    }
                    ?>
                </div>
                <a href="#" class="mt-32 btn btn-primary">Отправить запрос</a>
            </div>
        </div>
    </div>
</div>