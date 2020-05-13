<?php
/**
 * Created by PhpStorm.
 * User: Павел
 * Date: 20.06.2019
 * Time: 16:15
 */

?><?='<?xml version="1.0" encoding="UTF-8"?>'?>
    <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"><?

//print_r($goods);exit;

foreach($pages as $page){
    ?>
    <url>
    <loc><?=PROTOCOL."://".SUBDOMAIN.".".DOMAIN.$page['link']?></loc>
    <priority><?=$page['sitemap_priority']?></priority>
    <lastmod><?=($page['date_red']=="0000-00-00 00:00:00" || !$page['date_red'])?date('Y-m-d'):date('Y-m-d', strtotime($page['date_red']))?></lastmod>
    <changefreq>always</changefreq>
    </url><?
}

foreach($catalogs as $catalog){
    ?>
    <url>
        <loc><?=PROTOCOL."://".SUBDOMAIN.".".DOMAIN.\Lib\CatalogHelper::GetUrl($catalog['path'],$catalog["link"])?></loc>
        <priority>1</priority>
        <lastmod><?=($catalog['date_red']=="0000-00-00 00:00:00" || !$catalog['date_red'])?date('Y-m-d'):date('Y-m-d', strtotime($catalog['date_red']))?></lastmod>
        <changefreq>always</changefreq>
    </url><?
}

foreach($goods as $good){
    ?>
    <url>
        <loc><?=PROTOCOL."://".SUBDOMAIN.".".DOMAIN.\Lib\GoodHelper::GetUrl($good["c_path"],$good["c_link"],$good["link"])?></loc>
        <priority>0.8</priority>
        <lastmod><?=($good['date_updated']=="0000-00-00 00:00:00" || !$good['date_updated'])?date('Y-m-d'):date('Y-m-d', strtotime($good['date_updated']))?></lastmod>
        <changefreq>always</changefreq>
    </url><?
}

foreach($articles as $article){
    ?>
    <url>
    <loc><?=PROTOCOL."://".SUBDOMAIN.".".DOMAIN."/articles/".$article['link']."/"?></loc>
    <priority>0.5</priority>
    <lastmod><?=($article['date']=="0000-00-00 00:00:00" || !$article['date'])?date('Y-m-d'):date('Y-m-d', strtotime($article['date']))?></lastmod>
    <changefreq>always</changefreq>
    </url><?
}

?></urlset><?