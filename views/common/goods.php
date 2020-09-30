<div class="previews bg-light-grey">
    <div class="container">
        <div class="row"><?
            foreach($goods as $good){
                ?>
                <div class="col-12 col-xl-6 pb-4">
                    <? include 'good_preview.php' ?>
                </div><?
            }
        ?></div>
    </div>
</div>