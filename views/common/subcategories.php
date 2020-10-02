<div class="previews bg-light-grey">
    <div class="container">
        <div class="row"><?
            foreach($subcategories as $subcategory){
                ?>
                <div class="col-12 col-xl-6 pb-4">
                    <? include 'subcategory_preview.php' ?>
                </div><?
            }
        ?></div>
    </div>
</div>