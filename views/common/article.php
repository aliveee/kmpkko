<?php
/**
 * Created by PhpStorm.
 * User: Павел
 * Date: 21.02.2019
 * Time: 16:25
 */
?>
    <div class="article__item article-item">
        <div class="article-item__date d-none">
            <?=date_format(date_create_from_format("Y-m-d H:i:s",$article["date"]),"d.m.Y")?>
        </div>
        <div class="article-item__text">
            <?=$article["text2"]?>
        </div>
    </div><?
    if($prev || $next) {
        ?><div class="row article__item article-item justify-content-between"><?
        if ($prev) {
            ?><div class="col-12 col-md-auto article-item__title text-center"><a href="/articles/<?=$prev["link"]?>/">&larr;&nbsp;<?=$prev["name"]?></a></div><?
        }
        if ($next) {
            ?><div class="col-12 col-md-auto article-item__title text-center"><a href="/articles/<?=$next["link"]?>/"><?=$next["name"]?>&nbsp;&rarr;</a></div><?
        }
        ?></div><?
    }
?>
<?