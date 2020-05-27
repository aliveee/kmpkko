<?
if(count($projects)) {
    ?>
    <div class="works bg-light-grey">
        <div class="container">
            <div class="h">Реализованные проекты</div>
            <div class="works__gallery"><?
                foreach($projects as $_project) {
                    ?>
                    <div class="works__item">
                        <div class="works__item-image">
                            <img src="/uploads/project/<?=$_project['id']?>.jpg"/>
                        </div>
                        <div class="works__item-title">
                            <?=$_project['name']?>
                        </div>
                    </div><?
                }
            ?></div>
        </div>
    </div><?
}