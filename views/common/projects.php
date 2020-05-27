<div class="works row"><?
    //print_r($projects);
    if($projects)
    foreach ($projects as $_project)
        if(count($_project['images'])) {
            $i=0;
            foreach($_project['images'] as $_image) {
                if($i==0) {
                    ?><div class="col-12 col-lg-6">
                        <a href="<?=$_image?>" class="works__item" data-fancybox="good-images<?=$_project['id']?>">
                            <div class="works__item-image">
                                <img src="<?=$_image?>" title="<?=$_project['name']?>"/>
                            </div>
                            <div class="works__item-title">
                                Котельная на отходах спичечного производства в г. лиски
                            </div>
                        </a>
                    </div><?
                }else{
                    ?><a href="<?=$_image?>" class="d-none" data-fancybox="good-images<?=$_project['id']?>">
                        <img src="<?=$_image?>" title="<?=$_project['name']?>"/>
                    </a><?
                }
                $i++;
            }
        }
?></div>