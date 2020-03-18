<div class="breadcrumbs">
    <div class="container">
        <div class="breadcrumbs__inner"><?
            $i=0;
            foreach($breadcrumbs as $name=>$link){
                $i++;
                if($i>1){
                    ?><span>/</span><?
                }
                if($link){
                    ?><a href="<?=$link?>"><?=$name?></a><?
                }else{
                    ?><span><?=$name?></span><?
                }
            }
        ?></div>
    </div>
</div>