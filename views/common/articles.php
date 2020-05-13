<?php
/**
 * Created by PhpStorm.
 * User: Павел
 * Date: 21.02.2019
 * Time: 16:25
 */
?>
    <div class="articles">
    <div class="articles__items">
        <div class="row"><?
            foreach($articles as $article) {
                ?><div class="col-12">
                <div class="articles__item articles-item">
                    <div class="articles-item__date">
                        <?=date_format(date_create_from_format("Y-m-d H:i:s",$article["date"]),"d.m.Y")?>
                    </div>
                    <div class="articles-item__title">
                        <a href="/articles/<?=$article["link"]?>/"><?=$article["name"]?></a>
                    </div>
                    <div class="articles-item__text">
                        <?=$article["text1"]?>
                    </div>
                </div>
                </div><?
            }
            ?></div>
    </div>
    </div><?