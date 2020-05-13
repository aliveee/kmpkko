<?php

ob_start();
//print_r($goods);
include DOCUMENT_ROOT.'/views/common/breadcrumbs.php';

?>
<div class="<?=$bg_class??''?> pt-5 pb-5">
    <div class="container">
        <h1 class="h text-center"><?=$page['h1']?></h1>
        <div class='text'><?=$page['text']?></div>
        <?
        if($component) {
            include DOCUMENT_ROOT . "/views/common/{$component}.php";
        }
        ?>
    </div>
</div><?

$content = ob_get_clean();

include 'template.php';