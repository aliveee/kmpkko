<div class="works projects row"><?
    //print_r($projects);
    if($projects)
    foreach ($projects as $_project)
        if(count($_project['images'])) {
            $i=0;
            foreach($_project['images'] as $_image) {
                if($i==0) {
                    ?><div class="col-12 col-md-6 col-lg-4">
                        <a href="<?=$_image?>" class="works__item" data-fancybox="good-images<?=$_project['id']?>" data-caption="<?=$_project['name']?>">
                            <div class="works__item-image">
                                <img src="<?=$_image?>" title="<?=$_project['name']?>"/>
                            </div>
                            <div class="works__item-title">
                                <div class="w-100">
                                    <?=$_project['name']?>
                                </div><?
                                if($_project['location']) {
                                    ?>
                                    <div class="works__item-location mt-3 w-100">
                                        <?= $_project['location'] ?>
                                    </div><?
                                }
                            ?></div>
                        </a>
                    </div><?
                }else{
                    ?><a href="<?=$_image?>" class="d-none" data-fancybox="good-images<?=$_project['id']?>" data-caption="<?=$_project['name']?>">

                    </a><?
                }
                $i++;
            }
        }
?></div>