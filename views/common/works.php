<?
if(count($projects)) {
    ?>
    <div class="works projects bg-light-grey">
        <div class="container">
            <div class="h text-center mb-4"><?=$projects_title??'Выполненные проекты'?></div>
            <div class="works__gallery"><?
                foreach($projects as $_project) {
                    ?>
                    <div class="pl-3 pr-3">
                        <div class="works__item">
                            <div class="works__item-image">
                                <img src="/uploads/project/<?=$_project['id']?>.jpg"/>
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
                                ?>
                            </div>
                        </div>
                    </div><?
                }
            ?></div>
            <div class="row justify-content-end align-items-center" style="height:40px;">
                <a class="col-auto works__all mr-3" href="/projects/">Все проекты</a>
            </div>
        </div>
    </div><?
}