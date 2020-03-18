<?

$API_KEY = 'f13b1e66-d39d-455e-9683-5dc3d3f31b1b';
// инструкция для получения https://tech.yandex.ru/oauth/doc/dg/tasks/get-oauth-token-docpage/
$oauth_token = 'AQAAAAAQOyx2AAVO-4NI8q6s_Udio_Ee2zAvfT8';
$oauth_client_id = 'd547c5e4c6ad4e4792bf3807f5afa7b1';

// данные пользователя
$user_data = array(
	array('Фамилия', 'Имя', 'Отчество', 'Телефон'),
	array('Фамилия', 'Имя', 'Отчество', 'Телефон','Компания','ИНН','КПП','Расчетный счет','Корреспондентский счет','БИК','Наименование банка')
);

// скрытые разделы
$ids_catalog_nohide = getIdChilds("SELECT id, id_parent FROM {$prx}catalog WHERE hide='0'", 0, false);
//$ids_makers_nohide = getArr("SELECT id FROM {$prx}makers WHERE hide='0'", true, false);

/**
* griz
$cat_menu_tree = getTree("SELECT * FROM {$prx}catalog WHERE hide='0'");
$cat_menu_count = getArr("SELECT c.id, COUNT(g.id) AS cg FROM {$prx}catalog AS c LEFT JOIN {$prx}cats_goods CG ON c.id=CG.cat_id LEFT JOIN {$prx}goods AS g ON g.id=CG.good_id WHERE g.hide IS NULL OR g.hide='0' GROUP BY c.id");

foreach ($cat_menu_count as $i=>$v)
 $cat_ids_count[]=$i;

if ($cat_ids_count)
{ 
 $cat_ids_count_i=implode(",",$cat_ids_count);

 if ($cat_ids_count_i)
  $cat_ids_count_k=getField("select GROUP_CONCAT(DISTINCT id_parent) from {$prx}catalog where id IN ({$cat_ids_count_i})");
  
}
*/
$removeFavorites=$_SESSION['favorites'];


function show_cart_block($param='')
{
   $cartTotal = cartItogo();
   $cartTotalKolvo = cartItogoKolvo();
                    
   //$skidka=getSkidka();

    if ($param)
    {
       ?> 
        <div class="cart tbl" onclick="document.location.href='/cart.php'">
              	  <!--div class="hidden-sm hidden-xs"><input type="button" class="button" style="width:162px;" value="ПЕРЕЙТИ В КОРЗИНУ" onclick="document.location.href='/cart.php'"></div>
             	  <div class="hidden-md hidden-lg"><span class="button short vcenter"><i class="fa fa-arrow-circle-o-right"></i></span></div-->
                  <span class="button short vcenter"><i class="fa fa-shopping-cart"><span class="cart_itogo_kolvo"><?=$cartTotalKolvo['quant']?></span></i>
                  </span>
        </div>  
       <?   
    }
    else
    {
 ?>
          <div class="cart tbl" onclick="document.location.href='/cart.php'">
                      <i class="fa fa-lg fa-shopping-cart"></i>
                      <!--div id="cart_izbr" style="display:<?=count($_SESSION['favorites'])>0?'':'none'?>;position: absolute;top:0px;left:-63px;width:70px;color:#70C8D4;background:url('/img/izbr.png') no-repeat top left;height: 75px;text-align: center;" onclick="event.stopPropagation();document.location.href='/favorites/'">
                        <div style="font-size:10px;height:23px;padding-top:3px;margin-top: 22px;background: url('/img/like.png') no-repeat top center;text-align: center;">
                           <span id="cnt_in_favorites"><?=count($_SESSION['favorites'])?></span>
                        </div>
                      </div-->
                      <div class="header_cart_empty <?=$cartTotalKolvo['quant'] == 0?'tbl_cell':'hidden'?>">В корзине пусто</div>
                      <div class="header_cart_full <?=$cartTotalKolvo['quant'] == 0?'hidden':'tbl_cell'?>">
                         <span class="cart_itogo_kolvo"><?=$cartTotalKolvo['quant']?></span>
                         <span class="cart_itogo_kolvo_unit"><?=$cartTotalKolvo['unit_name']?></span><div>на сумму <span class="cart_itogo"><?=number_format($cartTotal-$skidka, 0, ',', ' ')?></span> <span class="rub">a</span></div>                
                      </div>
                	  <div class="hidden-sm hidden-xs"><input type="button" class="button" value="ПЕРЕЙТИ В КОРЗИНУ" onclick="document.location.href='/cart.php'"></div>
                	  <div class="hidden-md hidden-lg"><span class="button short vcenter"><i class="fa fa-arrow-circle-o-right"></i></span></div>
          </div> 
 <?  
  }  
}

function show_user_form($user='')
{
 ?>
    <form action="/cabinet.php?action=register" onSubmit="return toAjax(this)" method="post">
		<div style="margin-top:15px;"><input name="info[ФИО]" placeholder="ФИО *" title="ФИО *" value="<?=$user['ФИО']?>" class="input"></div>
    	<div><input name="info[Телефон]" placeholder="Телефон *" title="Телефон *" value="<?=$user['Телефон']?>" class="input"></div>
    	<div><input name="email" placeholder="E-mail *"  title="E-mail *" value="<?=$user['email']?>" class="input"></div>
        <div class="caption3">Смена пароля</div>
    	<div><input name="pwd" type="password" placeholder="Пароль *" title="Пароль *" value="<?=$user['pwd']?>" class="input"></div>
    	<div><input name="pwd2" type="password" placeholder="Повтор пароля *" title="Повтор пароля *" value="<?=$user['pwd']?>" class="input"></div>
    						
		<div style="margin-top:25px;" align="right">
				<input type="submit" value="Сохранить" class="btn" style="height:36px;">
    	</div>
    </form> 
 <?    
}

function show_user_form_td($user='')
{
 ?>
    <form action="/cabinet.php?action=register" onSubmit="return toAjax(this)" method="post">
	  <table class="form_td">
         <tr>
            <td style="width: 50%;border-right:1px solid #bdbdb8;text-align: center;">
                <div><input name="info[ФИО]" placeholder="ФИО *" title="ФИО *" value="<?=$user['ФИО']?>" class="input"></div>
            	<div><input name="info[Телефон]" placeholder="Телефон *" title="Телефон *" value="<?=$user['Телефон']?>" class="input required phone mask_phone"></div>
            	<div><input name="email" placeholder="E-mail *"  title="E-mail *" value="<?=$user['email']?>" class="input"></div>
            </td>
            <td style="text-align: center;">
                <div class="caption3">Смена пароля</div>
            	<div><input name="pwd" type="password" placeholder="Пароль *" title="Пароль *" value="<?=$user['pwd']?>" class="input"></div>
            	<div><input name="pwd2" type="password" placeholder="Повтор пароля *" title="Повтор пароля *" value="<?=$user['pwd']?>" class="input"></div>
            </td>
         </tr>
      </table>	
    						
		<div style="margin-top:25px;" align="center">
				<input type="submit" value="Сохранить" class="btn" style="height:36px;width:220px;">
    	</div>
    </form> 
 <?    
}

function show_orders($user)
{
    global $prx;
    ?>
       <div class="order_description">
        				  <?
        				  $res1 = sql("SELECT *,DATE_FORMAT(`date`, '%d.%m.%Y') datef FROM {$prx}orders WHERE id_users='{$user['id']}' ORDER BY date DESC");
                          //$res1 = sql("SELECT *,DATE_FORMAT(`date`, '%d.%m.%Y') datef FROM {$prx}orders ORDER BY date DESC");                  
        				  $count = mysql_num_rows($res1);
                          
                          if($count)
        				  {	
                            echo show_orders_cabinet($res1);
                            
                          }                      
                          
        			
        				  else
        				  {	?>
        					 <div style="text-align:center;margin-top:10px;">нет заказов</div>
        				  <?	}	?>                            
                            
                            
        </div>             
                                                
     <?                
}

function mobile_cart_good($key,$row)
{
     global $prx;
  if (!$row['id_catalog'])
   $row['id_catalog']=getField("select cat_id from {$prx}cats_goods where good_id='{$id}'");
    
  ?>
      <div class="for-xs good-row container-fluid tr<?=$key?>" id="tr<?=$key?>">
                <div class="row">
                  <div class="vcenter col-xs-8">
                   <div class="name">
                      <a href="/<?=id2links($row['id_catalog']).$row['link']?>.htm"><?=$row['name']?></a>
                   </div>               
                  </div><!--
          --><div class="vcenter col-xs-4">
                   <div class="del"><a href="javascript:void(0)" onclick="toAjax('/cart.php?action=change&key=<?=$key?>&kol=0'); $('#tr<?=$key?>').fadeOut();"><img src="/img/close.png" style="margin-left: 11px;margin-top:5px;" /></a></div>
                  </div>
                </div>
              
              <div class="row"><!--
          --><div class="vcenter col-xs-3">
                   <div class="image">
                      <a href="/<?=id2links($row['id_catalog']).$row['link']?>.htm"><img class="img_responsive" src="/uploads/goods/56x56x<?=$row['id']?>/<?=$row['link']?>.jpg" width="56" height="56" alt="<?=htmlspecialchars($row['name'])?>"></a>
              </div> </div><!--
          --><div class="vcenter col-xs-5">
                    <div class="price"><span class="sum<?=$key?>"><?=number_format2($row['price']*$row['kol'])?></span></div>
                  </div><!--
          --><div class="vcenter col-xs-4">
                       <div style="position: relative;">
                         <div class="inp">
                          <input type="hidden" value="1" id="h_kol<?=$row['id']?>"  /><input id="kol<?=$row['id']?>" class="numinput" value="<?=$row['kol']?$row['kol']:1?>" onblur="checkNum(this); toAjax('/cart.php?action=change&key=<?=$key?>&kol='+this.value);">
                         </div> 
                        </div>                    
                   </div><!--
          -->
             </div> 
    </div>          
  <?    
}

function cart_good($key,$row)
{
    global $prx;
      if (!$row['id_catalog'])
   $row['id_catalog']=getField("select cat_id from {$prx}cats_goods where good_id='{$id}'");

       ?>
          <table class="tbl_cart hidden-xs">
            <tr class="good-row">
              <td style="width: 15%;">
                   <div class="image">
                      <a href="/<?=id2links($row['id_catalog']).$row['link']?>.htm"><img class="img_responsive" src="/uploads/goods/68x68x<?=$row['id']?>/<?=$row['link']?>.jpg" width="68" height="68" alt="<?=htmlspecialchars($row['name'])?>"></a>
                   </div>               
              </td>
              <td style="width: 40%;"><div class="name"><a href="/<?=id2links($row['id_catalog']).$row['link']?>.htm"><?=$row['name']?></a></div></td>
              <td nowrap style="width:20%;"><div class="price"><span class="sum<?=$key?>"><?=number_format2($row['price']*$row['kol'])?></span></div></td>
              <td>
                  <div style="position: relative;">
                         <div class="inp">
                          <input type="hidden" value="1" id="h_kol<?=$row['id']?>"  /><input id="kol<?=$row['id']?>" class="numinput" value="<?=$row['kol']?$row['kol']:1?>" onblur="checkNum(this); toAjax('/cart.php?action=change&key=<?=$key?>&kol='+this.value);">
                         </div> 
                    </div>               
              </td>
              <td>
                  <div class="del"><a href="javascript:void(0)" onclick="toAjax('/cart.php?action=change&key=<?=$key?>&kol=0'); $('#tr<?=$key?>').fadeOut();"><img src="/img/close.png" style="margin-left: 8px;" /></a></div>
              </td>
            </tr>           
          </table> 
     <?           
}



function mobile_uzel_good($row)
{
     global $prx;
     $id=$row['id'];
      
  if (!$row['id_catalog'])
   $row['id_catalog']=getField("select cat_id from {$prx}cats_goods where good_id='{$id}'");
    
  ?>
      <div class="for-xs good-row container-fluid">
                <div class="row">
                  <div class="vcenter col-xs-8">
                   <div class="name">
                      №<?=$row['number']?>&nbsp;<a href="/<?=id2links($row['id_catalog']).$row['link']?>.htm"><?=$row['name']?></a>
                   </div>               
                  </div><!--
          --><div class="vcenter col-xs-4">
                   <?=$row['article']?>
                  </div>
                </div>
              
              <div class="row" style="margin-top: 10px;"><!--
          --><div class="vcenter col-xs-3">
                   <div class="image">
                    <div class="price"><span class="sum<?=$key?>"><?=$row['price']?number_format2($row['price']):'<span class="pr_">Звоните</span>'?></span></div>
              </div> </div><!--
          --><div class="vcenter col-xs-4">
                       <div style="position: relative;">
                         <div class="inp">
                          <input type="hidden" value="1" id="h_kol<?=$row['id']?>"  /><input id="kol<?=$row['id']?>" class="numinput" value="<?=$row['kol']?$row['kol']:1?>" onblur="checkNum(this); toAjax('/cart.php?action=change&key=<?=$key?>&kol='+this.value);">
                         </div> 
                        </div>
                  </div><!--
          --><div class="vcenter col-xs-5">
                <div class="tbl_buy"><div class="btn" id="a<?=$id?>" style="display:block"><a data-id="<?=$id?>" data-href="/cart.php?show=tocart&amp;id=<?=$id?>" href="/cart.php?show=tocart&amp;id=<?=$id?>" class="fb-ajax" style="color: #fff;">Заказать</a></div></div>                   
                   </div><!--
          -->
             </div> 
    </div>          
  <?    
}

function uzel_good($row, $number=0)
{
     global $prx;
     $id=$row['id'];
     
      if (!$row['id_catalog'])
   $row['id_catalog']=getField("select cat_id from {$prx}cats_goods where good_id='{$id}'");

       ?>
          <table class="tbl_cart hidden-xs">
            <tr class="good-row">
              <td style="width: 5%;"><?=$row['number']?></td>
              <td style="width: 15%;"><?=$row['article']?></td>
              <td style="width: 30%;"><div class="name"><a href="/<?=id2links($row['id_catalog']).$row['link']?>.htm"><?=$row['name']?></a></div></td>
              <td nowrap style="width:15%;"><div class="price"><span class="sum<?=$key?>"><?=$row['price']?number_format2($row['price']):'<span class="pr_">Звоните</span>'?></span></div></td>
              <td style="width:15%;">
                  <div style="position: relative;">
                         <div class="inp">
                          <input type="hidden" value="1" id="h_kol<?=$row['id']?>"  /><input id="kol<?=$row['id']?>" class="numinput" value="<?=$row['kol']?$row['kol']:1?>" onblur="checkNum(this); toAjax('/cart.php?action=change&key=<?=$key?>&kol='+this.value);">
                         </div> 
                    </div>               
              </td>
              <td>
                 <div class="tbl_buy"><div class="btn" id="a<?=$id?>" style="display:block"><a data-id="<?=$id?>" data-href="/cart.php?show=tocart&amp;id=<?=$id?>" href="/cart.php?show=tocart&amp;id=<?=$id?>" class="fb-ajax" style="color: #fff;">Заказать</a></div></div>
              </td>
            </tr>           
          </table> 
     <?           
}



function shops_cart()
{
    global $prx;
   $res=sql("select SHG.nalich, SH.* from {$prx}goods_nalich SHG INNER JOIN {$prx}shops SH ON SH.id=SHG.id_shop where SHG.id_goods='{$id}'");
 ?>
      <div class="zag_h1" style="width: 100%;text-align: center;height: auto;">
        СВОЙ ЗАКАЗ МОЖНО ЗАБРАТЬ В ЛЮБОМ УДОБНОМ ВАМ МАГАЗИНЕ
      </div>
      
 
  <? 
  $i=0;
   while ($row=mysql_fetch_array($res))
   {
    $i++;
     ?>
      <div class="row" style="margin-bottom: 20px;">
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"><input type="radio" name="shops_c" id="shops_c<?=$i?>" class="cb2r" <?=$i==1?'checked':''?> value="<?=$row['shortName']?>" /><span></span><label for="shops_c<?=$i?>"><?=$row['shortName']?></label></div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"><?=$row['phone']?></div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"><?=$row['fullName']?></div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-sm-3 col-xs-12"><?=$row['regim']?></div>
      </div>
     <? 
   }
}

function _showLeftMenu($mobile=0) {
  global $prx,$cat_ids_count_i,$cat_ids_count_k;
  
  ob_start();
  ?>
  <div class="top_menu">
    <?php
    /*
    if ($cat_ids_count_k)
     $sql = "SELECT C.id,C.name,C.link FROM {$prx}catalog C WHERE C.hide = 0 AND C.id_parent = 0 AND id IN ({$cat_ids_count_k}) ORDER BY C.sort,C.id";
    else
     $sql = "SELECT C.id,C.name,C.link FROM {$prx}catalog C WHERE C.hide = 0 AND C.id_parent = 0 AND id IN ('{$cat_ids_count_k}') ORDER BY C.sort,C.id";
   */  
 
    $sql="SELECT C.id,C.name,C.link FROM {$prx}catalog C WHERE C.hide = 0 AND C.id_parent = 0 AND id NOT IN ('{$ids_catalog_nohide}') ORDER BY C.sort,C.id"; 

     
    $levels0 = mysql_query($sql);
    $level0cur = 0;
    $count_fir=mysql_num_rows($levels0);

    while ($level0 = mysql_fetch_assoc($levels0)) {
      ?>
      <div data-settm="<?=$level0['id']?>" class="f_cat item <?=$level0cur == 0 ? 'first' : ''?> <?=$level0cur == ($count_fir-1) ? 'last' : ''?>" >
        <?if ($mobile==0) {?>
        <div class="name" onclick="location.href='/<?=$level0['link']?>/'">
          <a href="/<?=$level0['link']?>/"><?=$level0['name']?></a>
        </div>
        <?} else {
          ?>
        <div class="name">
          <a href="javascript:void(0)"><?=$level0['name']?></a>
        </div>
          <?
        }?>
        <?php
        ++$level0cur;
        $sql = "SELECT id,name,link FROM {$prx}catalog WHERE hide = 0 AND id_parent = '{$level0['id']}' and id IN ({$cat_ids_count_i}) ORDER BY sort";
        $levels1 = mysql_query($sql);
        if (mysql_num_rows($levels1) > 0) {
          ?>
          <div class="subitems">
            <?php
            $level1cur = 0;
            
            while ($level1 = mysql_fetch_assoc($levels1)) {
              $sql = "SELECT id,name,link FROM {$prx}catalog WHERE hide = 0 AND id_parent = '{$level1['id']}' and id IN ({$cat_ids_count_i}) ORDER BY sort";
              $levels2 = mysql_query($sql);
              $hasSubs = mysql_num_rows($levels2);
              ?>
              <div class="item2 <?=$hasSubs ? 'has_subs' : ''?> <?=$level1cur == 0 ? 'first1' : ''?> <?=$level1cur==(mysql_num_rows($levels1)-1)? 'last1' : ''?>" pos="<?=$level1cur?>" onclick="location.href='/<?=$level0['link']?>/<?=$level1['link']?>/'">
                <div class="d2">
                  <a href="/<?=$level0['link']?>/<?=$level1['link']?>/"><?=$level1['name']?></a>
                </div>
                <?php
                if ($hasSubs) {
                  ?>
                  <div class="subitems1" items="<?=$hasSubs?>">
                    <?php
                    $level2cur = 0;
                    while ($level2 = mysql_fetch_assoc($levels2)) {
                      ?>
                      <div class="item2 <?=$level2cur == 0 ? 'first2' : ''?>" onclick="location.href='/<?=$level0['link']?>/<?=$level1['link']?>/<?=$level2['link']?>/'">
                        <div class="d2">
                          <a href="/<?=$level0['link']?>/<?=$level1['link']?>/<?=$level2['link']?>/"><?=$level2['name']?></a>
                        </div>
                      </div>
                      <?php
                      ++$level2cur;
                    }
                    ?>
                  </div>
                  <?php
                }
                ?>
              </div>
              <?php
              ++$level1cur;
                  if ($level1cur%1==0){
                    //echo '<div class="cb"></div>';
                  }

            }
            ?>
           </div>
          <?php
        }
        ?>
      </div>
      <?php
    }
    ?>
    
  </div>
<?
}

function _showLeftMenu2() {
  global $prx,$cat_ids_count_i,$cat_ids_count_k;
  
  ob_start();
  ?>
  <div class="top_menu">
    <?php
    /*
    if ($cat_ids_count_k)
     $sql = "SELECT C.id,C.name,C.link FROM {$prx}catalog C WHERE C.hide = 0 AND C.id_parent = 0 AND id IN ({$cat_ids_count_k}) ORDER BY C.sort,C.id";
    else
     $sql = "SELECT C.id,C.name,C.link FROM {$prx}catalog C WHERE C.hide = 0 AND C.id_parent = 0 AND id IN ('{$cat_ids_count_k}') ORDER BY C.sort,C.id";
   */  
 
    $sql="SELECT C.id,C.name,C.link FROM {$prx}catalog C WHERE C.hide = 0 AND C.id_parent = 0 AND id NOT IN ('{$ids_catalog_nohide}') ORDER BY C.sort,C.id LIMIT 8"; 

     
    $levels0 = mysql_query($sql);
    $level0cur = 0;
    $count_fir=mysql_num_rows($levels0);

    while ($level0 = mysql_fetch_assoc($levels0)) {
      ?>
      <div data-settm="<?=$level0['id']?>" class="f_cat item <?=$level0cur == 0 ? 'first' : ''?> <?=$level0cur == ($count_fir-1) ? 'last' : ''?>">
        <div class="name" onclick="location.href='/<?=$level0['link']?>/'">
          <a href="/<?=$level0['link']?>/"><?=$level0['name']?></a>
        </div>
      </div>
      <?php
    }
    ?>
    
  </div>
<?
}


function getBonus()
{
  foreach ($_SESSION['cart'] as $id=>$good_info)
  {
    //$bonus+=round(getBon($good_info['bonus'],$good_info['id_catalog'])*$good_info['kol']*$good_info['price']/100);
    $bonus+=round(getBon($good_info['bonus'],$good_info['id_catalog'])*$good_info['kol']);
  } 
  
  return $bonus;
}

function getBon($bonus=0,$id_catalog=0)
{
  global $prx;
  if (!$bonus)
     $bonus=getField("select bonus from {$prx}catalog where id='{$id_catalog}'");  
  
  return $bonus;   
}

function getPrice($id=0)
{
    global $prx;
    if (!$id) return;
    $price_r=mysql_query("select * from {$prx}price where id_goods='{$id}' order by name");
    $prices=array();
    while ($price_row=mysql_fetch_array($price_r))
    {
      $prices[]=array('name'=>$price_row['name'],'koef'=>$price_row['koef'],'type'=>$price_row['type']); 
      if ($price_row['type']==1)
       $prices['buy']=array('name'=>$price_row['name'],'koef'=>$price_row['koef'],'type'=>$price_row['type']);
    }
   
   return $prices; 
}

function update_price($id=0)
{
    global $row, $price_id;
  $prices=getPrice($id); 

  $row['price']=$row["price{$price_id}"];

  update_for_sop($id);
  if ($prices['buy']['koef']) 
    {
      $row['price']*=$prices['buy']['koef']; 
      $row['price_old']*=$prices['buy']['koef'];
    } 

  return $row;      
}

function update_for_sop($id=0)
{
  global $row, $prx, $price_id;
 
 if ($id!=0)
 { 
  $sop_price=mysql_query("select id from {$prx}goods where FIND_IN_SET('{$id}',ids_soput)");
  while ($row_sop=mysql_fetch_array($sop_price))
  {
    if (array_key_exists($row_sop['id'],$_SESSION['cart']))
    {
      $row['price_old']=$row['price'];
      $row['price']=$row["dop_price{$price_id}"];
      $row["price{$price_id}"]=$row["dop_price{$price_id}"];
      return;  
    }
    else  //---обновить цены ----
    {
      $cur_pr=getField("select price{$price_id} from {$prx}goods where id='{$id}'");
      $row["price{$price_id}"]=$cur_pr;
    }
  }
 }
 else
 {
    foreach ($_SESSION['cart'] as $i=>$row)
    {
     $pr=getPrice($i);
     update_for_sop($i);
     $_SESSION['cart'][$i]['price']=$row["price{$price_id}"]*$pr['buy']['koef'];
     
     $kol=$_SESSION['cart'][$i]['kol'];
     $_price=$_SESSION['cart'][$i]['price'];
     $c_price=$_SESSION['cart'][$i]['price']*$kol;
     ?>
      <script>
        $("#sum<?=$i?>").html('<?=number_format2($c_price)?>');
        $("#cost<?=$i?>").html('<?=number_format2($_price)?>');
      </script>
      <?     
    } 
 } 
  
  return; 
}

function getInfo($city='')
{
  global $prx;  
  if ($city)
   return getRow("select * from {$prx}city where city='{$city}'");     
}

function ch_status($order_id,$status)
{
  global $prx;  
  if ($status)
  {
    $us_info=arr(getField("select info_user from {$prx}orders where id='{$order_id}'"));
    
    $text=getRow("select * from {$prx}status where name='{$status}' and send=1");
    if (!$text) return;
    
    $text_mail=str_replace('{order_id}',$order_id,$text['shablon_text']);    
    $text_sms=str_replace('{order_id}',$order_id,$text['sms_text']); 

    if ($text_mail && $status!='новый') mailTo($us_info['E-mail'],'Информация по заказу',$text_mail,set('email'));
    if ($text_sms) smsTo($us_info['Телефон'],$text_sms);              
  }  
} 

function ch_remont($order_id,$status)
{
  global $prx;  
  if ($status)
  {
    $rem=getRow("select * from {$prx}zayav where id='{$order_id}'");
    
    /*
    $us_info=arr(getField("select info_user from {$prx}orders where id='{$order_id}'"));
    */
    
    //$text=getRow("select * from {$prx}status where name='{$status}' and send=1");
    //if (!$text) return;
    
    
    
    $text_mail=str_replace('{order_id}',$rem['numRepair'],"Статус ремонта №{order_id} изменен на &quot;".$status."&quot;");    
    //$text_sms=str_replace('{order_id}',$order_id,$text['sms_text']); 

    if ($text_mail && $status!='новый') mailTo($rem['email'],'Информация по ремонту',$text_mail,set('email'));
    //if ($text_sms) smsTo($us_info['Телефон'],$text_sms);              
  }  
} 

function show_counters()
{
  global $prx;  
  $sql = "SELECT html FROM {$prx}counters ORDER BY sort";
  $r = sql($sql);
  while ($a = mysql_fetch_assoc($r)) {
     echo $a['html'];
  }
}

function our_preim()
{
  global $prx;
  $res=mysql_query("select * from {$prx}preim where hide!=1 order by sort");
  $cnt=mysql_num_rows($res);
  if ($cnt>0)
   {?><div class="row preim"><?} 
      $ii=0;
      $count=ceil(12/$cnt);
      while ($row=mysql_fetch_array($res))
      {
        ?>
          <div class="col-md-<?=$count?> col-sm-<?=$count?> col-xs-6">
             <div class="img">
               <?
                if (file_exists($_SERVER['DOCUMENT_ROOT']."/uploads/preim/{$row['id']}.png"))
                {
                  ?><img src="/uploads/preim/<?=$row['id']?>.png" style="height: 61px;" /><?  
                }
               ?>
             </div>
             <div class="nam"><?=$row['name']?></div>             
             <div class="txt"><?=$row['text']?></div>             
          </div>
        <?
        $ii++;
      }
  if ($cnt>0)
   {?></div><?} 
}

function getObl($city)
{
  global $prx;
  return getField("select obl from {$prx}cities where name='{$city}'");  
}


function delivery_function($id=0, $kol=1)
{
  global $prx;  
  $cur_city=$_SESSION['GeoIp']['SELECT']['city']?$_SESSION['GeoIp']['SELECT']['city']:'Москва';  
  
  $del=array();
  
  $zone=getZone($cur_city);
  $zoneP=getZoneP($cur_city);
  $obl=getObl($cur_city);
  
  $totalWeight=getWeight($id, $kol);
 
  if ($cur_city=='Москва')
  {
    $del['type']='moscow';
    $del['prices'][]=0;
    $del['prices'][]=set('delivery_summ');
  }
  else
  {
   $del['type']='ob';
   
   $totalWeight='1';
   if ($totalWeight)
   {
    $del['prices'][]=getField("select E.price from {$prx}delivery_ems E where E.zone='{$zone}' and E.weight>='{$totalWeight}' and E.obl='{$obl}' ORDER BY E.weight LIMIT 1");
    $del['prices'][]=getField("select E.price from {$prx}delivery_pony E where E.zone='{$zoneP}' and E.weight>='{$totalWeight}' ORDER BY weight LIMIT 1");
   }
   else
   {
    $del['prices'][]=0;
    $del['prices'][]=0;
   }
  }
  
  return $del;
  
}




function delivery_function2($id=0)
{
   ?>
	 <label class="cbr" style="margin-bottom: 5px;"><input checked="" type="radio" class="input" name="info_dop[Способ доставки]" value="Курьером по городу" onchange="$('#divAddress').slideDown();">Курьером по городу</label>
 	 <label class="cbr" style="margin-bottom: 5px;"><input type="radio" class="input"  name="info_dop[Способ доставки]" value="Транспортная компания" onchange="$('#divAddress').slideDown();">Транспортная компания</label>
 	 <label class="cbr" style="margin-bottom: 5px;"><input type="radio" class="input"  name="info_dop[Способ доставки]" value="Самовывоз" onchange="$('#divAddress').slideUp();"> Самовывоз</label>
  <?      
}

function delivery_function_good($id=0)
{
    global $city_info;
    if ($city_info['min_dostavka'])
   { 
  ?>
   <div style="margin: 20px 0px;" >
    <div class="fl">Доставка в г.<?=$city_info['city']//=getNewFormText($city_info['city'],2)?>: от&nbsp;<b><?=number_format($city_info['min_dostavka'], 0, ',', ' ')?></b>&nbsp;<span class="rub">a</span></div>
    <div class="fr"><a href="/show_pages.php?action=fancy_dostavka" class="fb-ajax-700" style="text-decoration: underline;">Подробнее о доставке</a></div>
    <div style="clear: both;"></div>
   </div> 
  <?
   }  
}    

function _delivery_function_good($id=0)
{
  $del=delivery_function($id);
  
  if ($del['type']=='moscow')
  {
    $del1=$del['prices'][0];
    $del2=$del['prices'][1];
    ?>
    
   <div class="caption3">Способы доставки:</div>
      <input type="hidden" name="delivery_summ" id="delivery_summ" value="0" />
      <!--input type="radio" value="1" id="lb1" class="jq-radio" style="box-shadow:none;" name="deliv" checked=""><label for="lb1">самовывоз</label>&nbsp;(<span id="pr1"><?=getD?></span>&nbsp;руб.)<br />
      <input type="radio" value="2" id="lb2" class="jq-radio" style="box-shadow:none;" name="deliv"><label for="lb2">курьером в пределах МКАД</label>&nbsp;(<span id="pr2">250</span>&nbsp;руб.)-->
      
 	 <label class="cbr" style="float: left;">Самовывоз:</label><div style="float: right;" class="cbr2">0 руб.</div>
     <div style="clear: both;"></div> 
	 <label class="cbr" style="float:left;">Курьером:</label><div style="float: right;" class="cbr2"><?=set('delivery_summ')?> руб.</div>
     <div style="clear: both;"></div>     
    <?
  }
  else
  {
   if ($del['prices'][0] || $del['prices'][1])
   {
    $del3=$del['prices'][0];
    $del4=$del['prices'][1];
    ?>
   <div style="margin-bottom: 10px;font-weight: bold;">Способы доставки:</div>
      <input type="hidden" name="delivery_summ" id="delivery_summ" value="<?=$del3?>" />
      <!--input type="radio" value="3" id="lb3" class="jq-radio" style="box-shadow:none;" name="deliv" checked=""><label for="lb3">курьерской службой EMS</label>&nbsp;(<span id="pr1"><?=$del3?></span>&nbsp;руб.)<br />
      <input type="radio" value="4" id="lb4" class="jq-radio" style="box-shadow:none;" name="deliv"><label for="lb4">курьерской службой PonyExpress</label>&nbsp;(<span id="pr2"><?=$del4?></span>&nbsp;руб.)-->
      
	 <?if ($del3>0) {?>  
       <label class="cbr" style="float: left;">Курьерской службой EMS:</label><div style="float: right;" class="cbr2"><span id="pr1"><?=$del3?></span>&nbsp;руб.</div>
     <?}?> 
	 <?if ($del4>0) {?>  
      <label class="cbr" style="float:left;margin-bottom: 5px;">Курьерской службой PonyExpress:</label><div style="float: right;" class="cbr2"><span id="pr2"><?=$del4?></span>&nbsp;руб.</div>
     <?}?> 
     <div style="clear: both;"></div>     
    <?
   } 
  }  
  
}

function getWeight($id=0, $kol=1)
{
	global $prx;
	$itogo = 0;

    if(@$_SESSION["cart"] && !$id)
		foreach($_SESSION["cart"] as $row)
         {
            $ves=getField("select weight from {$prx}goods where id={$row['id']}");
           if ($ves)
             $itogo+=$row['kol']*$ves;
         }
    else
     {
        $ves=getField("select weight from {$prx}goods where id={$id}");
        if ($ves)
          $itogo += $ves*$kol;
     }    
         
	return $itogo;
}

function getVolume($id=0, $kol=1)
{
	global $prx;
	$itogo = 0;

    if(@$_SESSION["cart"] && !$id)
		foreach($_SESSION["cart"] as $row)
         {
            $volume=getField("select volume from {$prx}goods where id={$row['id']}");
           if ($volume)
             $itogo+=$row['kol']*$volume;
         }
    else
     {
        $volume=getField("select volume from {$prx}goods where id={$id}");
        if ($volume)
          $itogo += $volume*$kol;
     }    
         
	return $itogo;
}

function getZone($city)
{
  global $prx;  
  return getField("select zone from {$prx}cities where name='{$city}'");  
}

function getZoneP($city)
{
  global $prx;  
  return getField("select zoneP from {$prx}cities where name='{$city}'");  
}

function show_somnenie()
{
  ?>
   <div class="block2">
      <div class="t1">Сомневаетесь в выборе?</div>
      <div class="t1" style="margin-bottom: 14px;">Возникли вопросы?</div>
      <div style="margin-bottom: 8px;">Вы всегда можете просто позвонить</div>
      <div class="phone_zag"><?=set('header_phone1')?></div>
      <div class="phone_text">звонок по РФ бесплатный</div>
      <div class="call" style="margin-bottom: 12px;margin-top: 7px;"><a href="/letter.php?show=phone" class="fb-ajax">или заказать обратный звонок</a></div>
      <div class="t1">Наши специалисты готовы</div>
      <div class="t1">Вас проконсультировать</div>
   </div>
  <?  
}


function show_konsul($t='')
{
  global $main;
  ?>
            <div class="caption" style="margin-top: 0px;">Получить консультацию</div>
             <div class="faq">
                    <form action="/letter.php?action=faq" method="post" onsubmit="return toAjax(this)" id="<?=$t?'fqForm':'fqForm2'?>">
                        <input type="text" id="faq" name="email" class="required"  placeholder="Телефон или email">
                    </form>
                    <div class="sear" onclick="$(this).parents('.faq').eq(0).find('form').submit()">
                      <span class="vcenter"><i class="fa fa-arrow-circle-o-right"></i></span>
                    </div>
              </div>
  <?  
}

function show_search()
{
  global $main;
  ?>
             <div class="search">
                    <form action="/search.php" id="frmSearch">
                        <input type="text" name="search" class="search_str" placeholder="Поиск...">
                    </form>
                    <div class="sear" onclick="$(this).parents('.search').eq(0).find('form').submit()">
                      <img src="/img/search-copy.svg" class="Search-Copy">
                    </div>
              </div>
  <?  
}

function showGoodIndex($a)
{
     ob_start();
  global $prx,$categoryCat, $features_names,$features_vals,$features_izm, $price_id;
  
  $id = $a['id'];
  ?>
  <div class="item <?=$first ? 'first' : ''?>">
    
 <?
   $dop_p=(($a['spec']==1 || ($a['price']!=$a['price_old'] && $a['price_old']>0))?'spec':($a['new']==1?'new':''));
  ?>
    
     <div style="border-bottom: 1px solid #E6E6E6;margin-bottom:2px;"> 
       <div class="name" style="height: 54px;border-top:1px solid #E6E6E6;margin:4px 0px;padding:4px 10px;">
         <div style="cursor:pointer;" onClick="location.href='/catalog/<?=id2links($a['id_catalog']).$a['link']?>.htm';"><div><a href="/catalog/<?=id2links($a['id_catalog']).$a['link']?>.htm"><?=$a['name']?></a></div></div>
       </div>
      <div style="float:left;padding-left:10px;height:20px;">
        <?
        $count_otz_dop=getField("select count(id) from {$prx}otzivy where hide=0 and id_goods='{$id}'");
       
        $good_raiting=$row['raiting'];
        if (!$good_raiting && $count_otz_dop>0)
             $good_raiting=round(getField("select sum(rating) from {$prx}otzivy where hide=0 and id_goods='{$id}'")/$count_otz_dop);
        
        //if ($good_raiting>0) 
        {
         echo show_reiting($good_raiting);
        }?> 
      </div>
      <div style="clear: both;"></div>
      
        <div class="image" style="position: relative;">
          <?if ($dop_p) {?> 
           <div style="position: absolute;top:20px;right:0px;z-index: 2;"><img src="/img/ico/<?=$dop_p?>.png" width="70" height="70" /></div>
          <?}?> 
          <a href="/catalog/<?=id2links($a['id_catalog']).$a['link']?>.htm"><img src="/uploads/goods/154x154x<?=$a['id']?>/<?=$a['link']?>.jpg" alt="<?=htmlspecialchars($a['name'])?>"></a>
        </div>
      
      
     </div>


    <div class="to_classifer">
      <?
      echo '<div class="har">'.(@$a['id_makers'] ? getField("select name from {$prx}makers where id='{$a['id_makers']}'") : '&mdash;').'</div>';
      $k=0;
      foreach($features_names as $id_f=>$name_f) {
        $k++;
          if (isset($features_vals[$id_f]) && is_array($features_vals[$id_f]) && count($features_vals[$id_f]) > 0) {
              $val = $features_vals[$id_f][$id];
              $vb=(@$features_vals[$id_f][$id] ? $features_vals[$id_f][$id].' '.$features_izm[$id_f] : '&mdash;');
              
              echo '<div style="background:'.($k%2==1?'#F0EFEF':'').'">';
              echo '<div class="har class_t'.$id_f.'" title="'.$vb.'">'.str_replace(';;;','<br>',$vb).'</div></div>';
            }
        }
      ?>
    
    </div>
    
    <div class="in_bottom">
        <div class="price">
           <!--div style="height: 32px;"-->
             <table style="width: 100%;">
          <?php
            //$prices=getPrice($id);  //  берем цены всех измерений
            
            //$a['price']=$a["price{$price_id}"];
            //$a['price']*=$prices['buy']['koef']; 
            //$a['price_old']*=$prices['buy']['koef'];
           $prices=getPrice($id);  //  берем цены всех измерений
           
           $a['price']=$a["price{$price_id}"];
                     
                  ?><div style="height: 60px;"><? 
                   
                   if (count($prices)>0)
                    { 

                     //update_for_sop($id);
                     
                    update_for_sop($id);
                    
                     foreach ($prices as $i=>$value)
                     {  
                        if (!is_int($i)) continue; 
                         ?>
                         <div style="font-size: <?=$prices['buy']['koef']==$value['koef']?'14':'12'?>px;<?=$prices['buy']['koef']==$value['koef']?'font-weight:bold':''?>;">за 1&nbsp;<?=$value['name']?> - <span class="new_price" style="font-size:<?=$prices['buy']['koef']==$value['koef']?'18':'14'?>px;<?=$prices['buy']['koef']==$value['koef']?'':'font-family:Roboto Regular'?>"><?=number_format2($value['koef']*$a['price'])?></span></div>
                        <?
                     }
                    
                    if ($prices['buy']['koef']) 
                     {
                        $a['price']*=$prices['buy']['koef']; 
                        $a['price_old']*=$prices['buy']['koef'];
                     }
                     
                    }
                  ?></div><?            
          
          
        /* 
          
          if ((float)$a['price_old'] > 0) {
            ?>
            <tr><td><span class="pr_text">Цена: </span></td><td class="old"><?=number_format2($a['price_old'], 2, ',', ' ')?></td></tr>
            <tr><td><span class="pr_text">Скидка: </span></td><td class="skidka"><?=number_format2($a['price_old']-$a['price'], 2, ',', ' ')?></td></tr>
            <?php
          }
          else
          {
            ?>
              <tr class="old"><td style="height: 32px;"></td></tr>
            <?
          } */
          ?>
           <!--/div--> 
          <!--tr><td><span class="pr_text">Ваша цена: </span></td><td class="new_price"><?=number_format2($a['price'], 2, ',', ' ')?></td></tr-->
          </table>
        </div>
        <div style="clear: both;"></div>
        
        <div>
        <?php
        if ($a['nalich']) {
          
          if (!$main){
          ?>
            <!--div style="display: table-cell;">
              <div style="position: relative;">
                <div class="inp">
                 <input type="hidden" value="1" id="h_kol<?=$a['id']?>"  /><input id="kol<?=$a['id']?>" class="numinput" value="<?=$num?$num:1?>" onblur="checkNum(this);">
                </div> 
              </div>                    
            </div-->   
          <?}?>  
             <div class="tbl_buy" style="margin-top:20px;"> 
               <a data-id="<?=$a['id']?>" data-href="/cart.php?show=tocart&id=<?=$a['id']?>" href="/cart.php?show=tocart&id=<?=$a['id']?>" class="fb-ajax"><div title="Добавить в заказ" class="btn" id="a<?=$id?>" style="display:<?=isset($_SESSION['cart'][$a['id']]) ? 'none' : 'block'?>">Купить</div></a>
               <div title="Перейти в корзину" class="btn" id="ac<?=$id?>"  onclick="location.href='/cart.php'"  style="display:<?=isset($_SESSION['cart'][$a['id']]) ? 'block' : 'none'?>">В корзине</div>
             </div> 
          <?php
        }
        ?>
      </div>
        <div style="clear: both;"></div>
   </div> 
  </div>  
  <?php
  echo ob_get_clean();
   
   
   // echo '<div style="max-width:200px;">'.$row['name'].'</div>';
}


// получение ID из цепочки ссылок
function links2id($links, $tbl='catalog')
{
	global $prx;
    
	$links = trim(clean($links), '/');
	if(!$links)
		return 0;
	$links = explode('/', $links);
	$tree = getTree("SELECT id,id_parent,name,link FROM {$prx}{$tbl}");
	$level = $id_catalog = 0;
    
	foreach($tree as $vetka)
	{
		if($level == $vetka['level'] && $links[$level] == $vetka['row']['link'] && isset($links[$level]))
		{
			$level++;
			$id_catalog = $vetka['row']['id'];
		}
    }
    
	return $level == count($links) ? $id_catalog : -1;
}

// получение цепочки ссылок из ID
function id2links($id, $tbl='catalog')
{
	global $prx;
	$arr = getArrParents("SELECT id,id_parent,name,link FROM {$prx}{$tbl}", $id);
	$links = '';
	foreach((array)$arr as $row)
		$links .= $row['link'].'/';
	return $links;
}

// информация о заказе (товар, пользователь, итого)
function getOrderInfo($row, $info='goods')
{
	global $prx, $HH;
    
	if(is_int($row))
		$row = getRow("SELECT * FROM {$prx}order WHERE id='{$row}'");
        
	$order = $row;
	$itogo = 0;
	$weight = 0;
	ob_start();
	switch($info)
	{
		case 'goods': // товар
		    $items = getArr("select * from {$prx}order_good where id_order=".$row["id"],false);
		    //$catalog_model = new \Model\Stp\Catalog();
			foreach($items as $item)
			{
			    $good = json_decode($item["row"],true);
				$itogo += $summ = $item['price'] * $item['n'];
			?>
				<a href="<?=\Lib\GoodHelper::GetUrl('','',$good["link"])?>"><?=$good['name']?> (Код: <?=$good['article']?>)</a>
				(<i>цена: <?=$item['price']?>, кол-во: <?=$item['n']?>, сумма: <?=$summ?> р.</i>)<br>
		<?	}
			if($order['delivery_sum'])
			{	?>
				<b>Доставка:</b> <?=number_format($order['delivery_sum'],2,',',' ')?> р.<br>
		<?	}
			if($order['skidka'])
			{	?>
				<b>Скидка:</b> <?=number_format($order['skidka'],2,',',' ')?> р.<br>
		<?	}
            if($order['use_bonus'])
				{	?>
   				 <b>Бонусами:</b> <?=number_format($order['use_bonus'],2,',',' ')?> р.<br>
			<?	}	?>        
        
			<b>Итого:</b> <?=number_format($itogo+$order['delivery_sum']-$order['skidka']-$order['use_bonus'],2,',',' ')?> р.<br>
		<?
			break;

		case 'goods_tbl': // товар в табличном виде

        ?><style>td,th{padding:5px}</style>
			<table border="1" class="tblcart" cellpadding="5" style="border:none">
				<tr>
					<th>Код</th>
					<th>Товар</th>
					<th colspan="2">Количество</th>
					<th>Цена</th>
					<th>Сумма</th>
					<th>Вес</th>
				</tr>
			<?
                $items = getArr("select * from {$prx}order_good where id_order=".$row["id"],false);
                foreach($items as $item)
                {
                    $good = json_decode($item["row"],true);
					$itogo += $summ = $item['price'] * $item['n'];
					$weight+=$item["weight"]*$item["n"];
                    
				?>
					<tr>
						<td><?=$good['article']?></td>
						<td><a href="http://<?=$HH?>/<?=$good['link']?>/"><?=$good['name']?></a></td>
						<td align="center"><?=$item['n']?></td>
						<td align="center"><?=$good['unit']?></td>
						<td align="center" nowrap><?=number_format($item['price'],2,',',' ')?> р.</td>
						<td align="center" nowrap><?=number_format($summ,2,',',' ')?> р.</td>
						<td align="center" nowrap><?=number_format($item["weight"],2,',',' ')?></td>
					</tr><?
                }
                ?><tr>
                    <td colspan="7" style="border:0">&nbsp;</td>
                </tr>
                <tr>
                    <td  style="border:0" rowspan="5"></td>
                    <td style="border:0" rowspan="5" valign="top">
                        <b><span style="color:red">*</span>Общий вес (бруттто) заказа: <?=$weight?> кг</b>
                        <br/>
                        <em>(*объем веса всех товаров в заказе<br/>является ориентировочным)</em>
                    </td>
                    <th  style="border:0" colspan="2" align="right"><span style="color:red">*</span>Итого</th>
					<td  style="border:0" nowrap colspan="3"><b><?=number_format($itogo,2,',',' ')?> р.</b></td>
                </tr><?
                if($order["customer_type"]!=CUSTOMER_TYPE_PERSON){
                    ?><tr>
						<th  style="border:0" colspan="2" align="right">В т.ч НДС(20%)</th>
						<td  style="border:0" nowrap colspan="3"><?=number_format($itogo*0.2,2,',',' ')?> р.</td>
					</tr><?
                }
				if($order['delivery_sum'])
				{
				    ?><tr>
						<th  style="border:0" colspan="2" align="right">Доставка</th>
						<td  style="border:0" nowrap colspan="3"><?=number_format($order['delivery_sum'],2,',',' ')?> р.</td>
					</tr><?
				}
				 ?><tr>
						<th  style="border:0" colspan="2" align="right"><span style="color:red">*</span>Всего к оплате</th>
						<td  style="border:0" nowrap colspan="3"><?=number_format($itogo+$order['delivery_sum'],2,',',' ')?> р.</td>
					</tr><?
				if(!$order['delivery_sum'] && $order["delivery_type"]!=DELIVERY_TYPE_SELF)
				{
				    ?><tr>
						<td  style="border:0" colspan="5"><em>(* без учета стоимости доставки)</em></td>
					</tr><?
				}
				?>
			</table>
		<?
			break;
		
		case 'user': // пользователь
			//$info = arr($row['info_user']);
			//print_r($row);
            ?>
            <table><?
                if($row["customer_type"]==CUSTOMER_TYPE_PERSON){
                    ?><tr>
                        <th align="right">Получатель</th>
                        <td><?=$row["address_surname"]." ".$row["address_name"]." ".$row["address_patronymic"]?></td>
                    </tr><?
                }else{
                    ?><tr>
                        <th align="right">Получатель</th>
                        <td><?=$row["company_name"]?></td>
                    </tr>
                    <tr>
                        <th align="right">ИНН</th>
                        <td><?=$row["inn"]?></td>
                    </tr>
                    <tr>
                        <th align="right">ФИО представителя</th>
                        <td><?=$row["profile_surname"]." ".$row["profile_name"]." ".$row["profile_patronymic"]?></td>
                    </tr><?
                }
                if($row["delivery_type"]==DELIVERY_TYPE_SELF){
                    ?><tr>
                    <th align="right">Самовывоз</th>
                    <td><?=getField("select full_name from {$prx}shop where id='".$row['id_shop']."'")?></td>
                    </tr><?
                }elseif($row["delivery_type"]==DELIVERY_TYPE_MSK){
                    ?><tr>
                    <th align="right">Доставка по Москве и области</th>
                    <td><?= $row["region"].", ".$row["city"].", ".$row["street"]." ".$row["house"] ?></td>
                    </tr><?
                }elseif($row["delivery_type"]==DELIVERY_TYPE_TK){
                    ?><tr>
                    <th align="right">Доставка ТК</th>
                    <td><?= $row["region"].", ".$row["city"].", ".$row["street"]." ".$row["house"] ?></td>
                    </tr><?
                }
                ?><tr>
                    <th align="right">Телефон</th>
                    <td><?=$row['phone']?></td>
                </tr>
                <tr>
                    <th align="right">E-mail</th>
                    <td><?=$row['email']?></td>
                </tr>
            </table>
            <br/><?
			break;
		
		case 'user_red': // пользователь для админки
			$info = arr($row['info_user']);
		?>
			<b><?=$info['Фамилия']?> <?=$info['Имя']?> <?=$info['Отчество']?></b><br>
			<b>Телефон:</b> <input name="info_user[Телефон]" value='<?=$info['Телефон']?>' style="width:auto;"><br>
			<b>E-mail:</b> <?=$info['E-mail']?><br>
			<b>Способ оплаты:</b> <?=$info['Способ оплаты']?><br>
			<b>Способ доставки:</b> <?=$info['Способ доставки']?><br>
		<?	if($info['Способ доставки'] != 'Самовывоз') { ?>
				<b>Адрес доставки:</b>
				<input style="width:100px" name="info_user[address][Город]" value="<?=$info['address']['Город']?>" placeholder="Город">,
				ул.<input style="width:100px" name="info_user[address][Улица]" value="<?=$info['address']['Улица']?>" placeholder="Улица">,
				д.<input style="width:20px" name="info_user[address][Дом]" value="<?=$info['address']['Дом']?>" placeholder="">/<input placeholder="" style="width:20px" name="info_user[address][Корпус]" value="<?=$info['address']['Корпус']?>">,
				кв.<input style="width:20px" name="info_user[address][Квартира]" value="<?=$info['address']['Квартира']?>" placeholder="">
				<br>
		<?	}
            ?>
            <br>
			<b>Комментарий к заказу:</b> <?=$info['Комментарий к заказу']?><br>
            <?
			break;
		
		case 'itogo': // итого
			ob_get_clean();
			 $items = getArr("select * from {$prx}order_good where id_order=".$row["id"],false);
			foreach($items as $item)
			{

				$itogo += $item['price'] * $item['kol'];
				}
			return $itogo+$order['delivery_sum']-$order['skidka']-$order['use_bonus'];
			exit;
	}
	return ob_get_clean();
}

// сумма в корзине
function cartItogo()
{
	$itogo = 0;
  if (isset($_SESSION['cart']) && is_array($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
    foreach($_SESSION['cart'] as $arr) {
      $itogo += $arr['kol'] * (float)$arr['price'];
    }
  }
  
	return $itogo;
}


// сумма в корзине
function cartItogo_Old()
{
	$itogo = 0;
    
  if (isset($_SESSION['cart']) && is_array($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
    foreach($_SESSION['cart'] as $arr) {
      $itogo += $arr['kol'] * (float)($arr['price_old']>0?$arr['price_old']:$arr['price']);
    }
  }
  
    if (isset($_SESSION['nabor']) && is_array($_SESSION['nabor']) && count($_SESSION['nabor']) > 0) {
    
    foreach($_SESSION['nabor'] as $id=>$mass_nabor)
	  foreach ($mass_nabor as $id_nabor=>$arr)
       {
         $itogo += $arr['kol'] * (float)$arr['price'];
       }    
   }
  
	return $itogo;
}




// кол-во в корзине
function cartItogoKolvo()
{
	$itogo = 0;
  if (isset($_SESSION['cart']) && is_array($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
    foreach($_SESSION['cart'] as $arr) {
      $itogo += $arr['kol'];
    }
  }

  
  $totalQuantEnding = substr($itogo, -1, 1);
  $quantUnit = '';
  switch ($totalQuantEnding) {
    case 1:
      $quantUnit = 'товар';
      break;
    case 2:
    case 3:
    case 4:
      $quantUnit = 'товара';
      break;
    case 5:
    case 6:
    case 7:
    case 8:
    case 9:
    case 0:
      $quantUnit = 'товаров';
      break;
  }
  if ($itogo >= 11 && $itogo <= 19) {
    $quantUnit = 'товаров';
  }
	return array('quant' => (count($_SESSION['cart'])+count($_SESSION['nabor'])), 'unit_name' => $quantUnit); //$itogo
}

// id характеристик
function featuresIds($id_catalog=0, $arr=true)
{
	global $prx;
	if ($id_catalog) $ids_catalog = getIdParents("SELECT id,id_parent FROM {$prx}catalog", $id_catalog, false);

    $ids_features = getArr("SELECT ids_features FROM {$prx}catalog WHERE ".($id_catalog ? "id IN ({$ids_catalog})" : '1')." AND ids_features<>''");
    if (!$ids_features)
	$ids_features = getArr("SELECT DISTINCT id_feature FROM {$prx}feature_catalog WHERE ".($id_catalog ? "id_catalog IN ({$ids_catalog})" : '1')." AND id_feature<>''");
    
    //$ids_features=array_merge($ids_features,$ids_features2);
     
	$ids_features = implode(',', $ids_features);
	return $arr ? explode(',', $ids_features) : ($ids_features ? $ids_features : 0);
}

// выводим баннер
function showBnr($row, $width=0, $height=0)
{
	global $DR;
	if(!is_array($row))
		$row = getRow($row);
	if(!$row)
		return;		
	$file = "/uploads/bnr/{$row['id']}";
	$bnr = file_exists($DR.$file.'.swf') ? $file.'.swf' : $file.'.gif';
	if($width || $height)
	{
		$size = getimagesize($DR.$bnr);
		$wh = getRatioSize($size, array($width, $height));
		$wh = "width='{$wh[0]}' height='{$wh[1]}'";
	}
	ob_start();
?>
	<?=$row['link'] ? "<a href='{$row['link']}'>" : ''?>
		<?=strpos($bnr, '.swf')
			? flash($bnr, $wh)
			: "<img src='{$bnr}' {$wh} alt='{$row['name']}'>"?>
	<?=$row['link'] ? '</a>' : ''?>
<?
	return ob_get_clean();	
}

// регистрируем пользователя
function regUser($login='', $pwd='', $identity='')
{
	global $prx;
	if(!$login && !$pwd && !$identity)
	{
		if($_SESSION['user'])
			list($login, $pwd, $identity) = array($_SESSION['user']['login'], md5($_SESSION['user']['pwd']), $_SESSION['user']['identity']);
		elseif(isset($_COOKIE['inUser']))
			list($login,$pwd) = explode('/', $_COOKIE['inUser']);
	}
	unset($_SESSION['user']);
	if(($login && $pwd) || $identity)
	{
		if($row = getRow("SELECT * FROM {$prx}users WHERE ".($identity ? "identity='{$identity}'" : "login='{$login}' and moder=1")))
		{
			if($row && (md5($row['pwd']) == $pwd || $identity))
			{
				$_SESSION['user'] = $row;
				foreach(arr($row['info']) as $key=>$val)
				{
					if($key == 'address')
						continue;
					$_SESSION['user'][$key] = $val;
				}
				if(!@$_SESSION['favorites'])
				{
					$_SESSION['favorites'] = array();
                    if ($row['ids_goods_favorites'])
					foreach(explode(',', $row['ids_goods_favorites']) as $id_goods_favorites)
						$_SESSION['favorites'][$id_goods_favorites] = $id_goods_favorites;
				}
			}
		}
	}
	return $_SESSION['user'];
}

// вывод товаров
function showGoods($sql)
{
	$res = sql($sql);
	if(!mysql_num_rows($res))
		return false;
	ob_start();
  ?>
  <div class="goods_list">
    <?php
    while ($row = mysql_fetch_assoc($res)) {
       echo showGood($row);
    }
    ?>
  </div>
  <?php
	return ob_get_clean();
}

// вывод товарова
function showGood($row)
{
	global $prx, $removeFavorites;
	if(!$row)
		return false;
	$id = $row['id'];
	ob_start();
  ?>
  <div class="item" style="padding-bottom:30px;">
  <?	if($removeFavorites) { ?>
		  	<div align="right"><b class="cr">x</b> <a href="javascript:toAjax('/favorites.php?action=remove&id_goods=<?=$id?>')">удалить из избранного</a></div>
	<?	}	?>
    <div class="name"><a href="/<?=id2links($row['id_catalog']).$row['link']?>.htm"><?=$row['name']?></a></div>
<?	if($row['name3']) { ?>
		 <div class="fs14 cr ffpts" style="margin:-15px 0 10px;"><img src="/img/p5.png" width="8" height="8" style="margin-bottom:3px;"> <?=$row['name3']?></div>
<?	}	?>
    <div class="image">
      <a href="/catalog/<?=id2links($row['id_catalog']).$row['link']?>.htm"><img src="/uploads/goods/159x159x<?=$row['id']?>/<?=$row['link']?>.jpg" alt="<?=htmlspecialchars($row['name'])?>"></a>
		<table width="230">
			<tr>
				<td><div class="compare2" style="margin:0;"><span class="toCompare<?=$id?>" <?=isset($_SESSION['compare'][$id])?'style="display:none"':''?>><a href="#" onClick="toAjax('/compare.php?action=tocompare&id=<?=$id?>');return false;">К&nbsp;сравнению</a></span><span class="compare<?=$id?>" <?=!isset($_SESSION['compare'][$id])?'style="display:none"':''?>><a href="/compare.php" class="fb-compare a_compare"><span>Сравнить (<?=count($_SESSION['compare'])?>)</span></a></span></div></td>
				<td align="right"></td>
			</tr>
		</table>
    </div>
    <div class="features" style="width:250px;">
      <?php
		$fInShort = getArr("SELECT name,inshort FROM {$prx}features WHERE inshort='1'");
      $features = getFeatures($row['id'], $row['id_catalog']);
      if (!is_array($features) || count($features) == 0) {
        echo $row['text1'];
      } else {
			$i = 0;
        foreach ($features as $fName => $fValue) 
		  {
			  if(!$fInShort[$fName])
			  		continue;
          ?>
          <div class="item" style=" <?=$i++ ? 'border-top:1px dashed #D2D2D2; padding-top:4px;' : ''?>">
            <div class="name"><?=$fName?>:</div>
            <div class="value"><?=$fValue?></div>
            <div class="cb"></div>
          </div>
          <?php
			 if($i == 8) break;
        }
      }
      ?>
    </div>
    <div class="right_column">
	 	<div class="cg" style="padding:0 0 7px 10px;"><?=$row['article'] ? 'Арт.: '.$row['article'] : ''?></div>
		<div style="padding:10px; background:#FDF7DA; border:1px solid #D2D2D2; border-radius:5px; width:180px;">
		<? if((int)$row['price_old']) { ?>
				<div style="padding-bottom:10px; margin-bottom:10px; border-bottom:1px dashed #D2D2D2;">
					<span class="fs10">Старая цена:</span> <?=number_format($row['price_old'], 2, ',', ' ')?> <span class="fs10">р.</span>
				</div>
		<? } ?>
			<div class="ffpts fs18"><b>Ваша цена: <span style="font-size:20px;"><?=number_format($row['price'], 2, ',', ' ')?></span> р.</b></div>
			<div style="margin-top:10px; padding-left:18px;">
				<a href="/cart.php?show=tocart&id=<?=$id?>" class="fb-ajax to_cart2" id="a<?=$id?>" style="display:<?=isset($_SESSION['cart'][$id]) ? 'none' : 'block'?>; ">В&nbsp;корзину</a>
				<div class="in_cart2" id="ac<?=$id?>" style="display:<?=isset($_SESSION['cart'][$id]) ? 'block' : 'none'?>">В&nbsp;корзине</div>
			</div>
		 </div>	 
      
    </div>
    <div class="cb"></div>
  </div>
  <?php
  return ob_get_clean();
}


function show_brend()
{
 ob_start();
	global $prx;
    $res=sql("select * from {$prx}makers order by sort, name");

  ?>
            
            <div class="jcarousel-wrapper">
                <div class="jcarousel">
                   <ul>
                  <?php
                ob_start();	
                    while ($row=mysql_fetch_array($res))
                	{
                       $i++;
                		//if (file_exists($_SERVER['DOCUMENT_ROOT']."/uploads/makers/{$row['id']}.png"))
                        {
                        ?><li class="item fl">
                              <a href="/makers/<?=$row['link']?>/" title="<?=htmlspecialchars($row['name'])?>" style="display:block; background:white; min-width:150px; text-align:center;"><img src="<?=file_exists($_SERVER['DOCUMENT_ROOT']."/uploads/makers/{$row['id']}.png") ? "/uploads/makers/{$row['id']}.png" : '/uploads/settings/noimg.jpg'?>" height="60px"/></a>
                        </li><?
                       } 
                	} 
                 $c=ob_get_clean();
                //echo str_repeat($c,20);
                echo $c;                    
                ?>
                  </ul>
                </div>

                <span class="jcarousel-control-prev prev" style="cursor: pointer;"></span>
                <span class="jcarousel-control-next next" style="cursor: pointer;"></span>
            </div>
            <div style="margin-top: 4px;text-align: right;margin-right:53px"><a href="/makers/">Все производители&nbsp;&rarr;</a></div>  
  <?


  return ob_get_clean(); 
}    



function show_orders_cabinet($res)
{    
	global $prx;
	?>
  <div>
   <?
	ob_start();
    $i=0;
	while ($row=mysql_fetch_array($res))
	{
	   $i++;
       $info_goods = arr($row['info_goods']);
       $m_cnt=count($info_goods);
       $amount=0;
      foreach($info_goods as $key=>$arr)
   	   {$amount += $arr['kol'] * $arr['price'];}
		?>
           <div class="item_cab<?=$i==1?' item_cab_active':''?>" data-cab="<?=$row['id']?>">
               <div class="zg2">Заказ от <?=$row['datef']?>:
                <span id="itogo" style="margin-left: 5px;color: #1b1d21;font-family: PTsans-Bold;"><?=$m_cnt?>&nbsp;<?=wordsSpan($m_cnt,'товар||а|ов')?> на сумму <?=number_format2($amount)?></span>
                <span class="btn light fr" style="width: 28px;"><i class="fa fa-2x fa-angle-double-down" aria-hidden="true"></i></span>
              </div>
				<div class="cart_content">
							<table class="tblcart" width="100%">
								<tr>
									<th colspan="2">Наименование товара</th>
									<th>Цена</th>
									<th>Кол-во</th>
								</tr>
                                    <?
                                      $m=0;
                                     	foreach($info_goods as $key=>$arr)
                                 		  {
                                   		      $m++;
                                    ?>
									<tr id="tr<?=$key?>" class="tr_cab" data-cab=<?=$row2['id']?>>
										<td class="article">
                                            <div class="image">
                                              <a href="/catalog/<?=id2links($arr['id_catalog']).$arr['link']?>.htm"><img src="/uploads/goods/100x100x<?=$arr['id']?>/<?=$arr['link']?>.jpg" width="65" height="65" alt="<?=htmlspecialchars($arr['name'])?>"></a>
                                              <?php
                                             /* 
                                              if (!$a['nalich']) {
                                                ?>
                                                <div class="na"><a href="/catalog/<?=id2links($arr['id_catalog']).$arr['link']?>.htm"><img src="/img/na100.png" width="65" height="65"></a></div>
                                              <?php
                                              }*/
                                              ?>
                                            </div>                                        
                                        </td>
										<td>
                                          <div class="name"><a href="/catalog/<?=id2links($arr['id_catalog']).$arr['link']?>.htm"><?=$arr['name']?></a></div>
                                        </td>
										<td class="cost" nowrap><?=number_format2($arr['price'])?></td>
										<td class="quantity" width="70" nowrap>
                                                      <?=$arr['kol']?>
                                        </td>
									</tr>
                                  <?}?>  
        					</table>		
        	   </div>              
           </div> 		
        <?
	}
	$data = ob_get_clean();
	
    echo $data;
    
	?>
  </div>
	<?   
}


function nalich($row)
{
	global $prx;
	$nalich = $row['nalich'] + getField("SELECT SUM(n) AS s FROM {$prx}good_shop WHERE id_good='{$row['id']}'");
	return $nalich > 0 ? $nalich : false;
}

function rating($id_goods, $getCount=false)
{
	global $prx;
	$rating = getRow("SELECT SUM(rating) AS s, COUNT(*) AS c FROM {$prx}otzivy WHERE id_goods='{$id_goods}' AND hide='0' AND rating>0");
	return $getCount
		? $rating['c']
		: $rating['c'] ? round($rating['s'] / $rating['c'] * 10) / 10 : 0;
}

function number_format2($num)
{
    return number_format($num, 0, ',', ' ') . ' <span class="rub">a</span>';
	//return number_format($num*set('evro-rub'), 0, ',', ' ') . ' <span class="rub">a</span>';
}

function number_format3($num)
{
    return number_format($num, 0, ',', ' ');
	//return number_format($num*set('evro-rub'), 0, ',', ' ') . ' <span class="rub">a</span>';
}

function getDataForCatMenu($parentID = 0) {
  global $prx;
  $data = array();
  $sql = "SELECT id, name
              FROM {$prx}catalog
              WHERE id_parent='{$parentID}'
                AND hide='0'
              ORDER BY sort,name";
  $r1 = mysql_query($sql);
  if (mysql_num_rows($r1)) {
    while ($row1 = mysql_fetch_assoc($r1)) {
      $data[$row1['id']] = array(
          'id' => $row1['id'],
          'name' => $row1['name'],
          'url' => '/catalog/' . id2links($row1['id']),
          'subitems' => array()
      );
      $sql = "SELECT id, name
              FROM {$prx}catalog
              WHERE (id_parent='{$row1['id']}' OR CONCAT(',',ids_parent,',') LIKE '%,{$row1['id']},%')
                AND hide='0'
              ORDER BY sort,name";
      $r2 = mysql_query($sql);
      if (mysql_num_rows($r2)) {
        while ($row2 = mysql_fetch_assoc($r2)) {
          $data[$row1['id']]['subitems'][$row2['id']] = array(
              'id' => $row2['id'],
              'name' => $row2['name'],
              'url' => '/catalog/' . id2links($row2['id']),
              'subitems' => array()
          );
          $sql = "SELECT id, name
              FROM {$prx}catalog
              WHERE (id_parent='{$row2['id']}' OR CONCAT(',',ids_parent,',') LIKE '%,{$row2['id']},%')
                AND hide='0'
              ORDER BY sort,name";
          $r3 = mysql_query($sql);
          if (mysql_num_rows($r3)) {
            while ($row3 = mysql_fetch_assoc($r3)) {
              $data[$row1['id']]['subitems'][$row2['id']]['subitems'][$row3['id']] = array(
	              'id' => $row3['id'],
                  'name' => $row3['name'],
                  'url' => '/catalog/' . id2links($row3['id']),
              );
            }
          }
        }
      }
    }
  }
  return $data;
}

function showTopCatMenu($hidden)
{
	global $prx;
	$tree = getTree("SELECT * FROM {$prx}catalog WHERE hide='0'");
	$count = getArr("SELECT id_catalog, COUNT(id) AS c FROM {$prx}goods WHERE hide='0' GROUP BY id_catalog");
	foreach($tree as $vetka)
		$count[$vetka['row']['id']] += getField("SELECT COUNT(*) AS c FROM {$prx}goods WHERE CONCAT(',',ids_catalog,',') LIKE '%,{$vetka['row']['id']},%' AND hide='0'");
	
	ob_start();
  $data = getDataForCatMenu();
  if ($hidden) {
    ?>
    <div style="height:1px;background-color:#d2d2d2;margin-bottom:-1px;width:230px;position:absolute;top:217px;" id="index_cat_dummy"></div>
    <?php
  }
  ?>
  <div class="top_category_menu" id="index_cat" style=" display:<?=!$hidden ? 'block' : 'none'?>;<?=$hidden ? 'position:absolute;top:217px;z-index:5;' : ''?>">
    <?php
    foreach ($data as $itemID => $itemData) 
	 {
      if (count($itemData['subitems']) > 0) {
        ?>
        <div class="subitems" id="top_category_subitems_<?=$itemID?>" style="margin-left:230px;" onmouseover="showTopSubcategories(<?=$itemID?>, <?=$hidden ? 1 : 0?>)" onmouseout="hideTopSubcategories(<?=$itemID?>)">
          <?php
          $itemCur = 0;
          foreach ($itemData['subitems'] as $subItemData) {
            if ($itemCur % 3 == 0) {
              ?>
              <div class="cb"></div>
              <?php
            }
            ?>
            <div class="item">
              <span class="span_pm" onClick="$(this).toggleClass('active').nextAll('div.subitems:first').slideToggle();"></span>
				  <a href="<?=$subItemData['url']?>"><?=$subItemData['name']?></a>&nbsp;<span class="cg" style="font-weight:normal;">(<?=getCountGoods($subItemData['id'], $tree, $count)?>)</span>
              <?php
              if (count($subItemData['subitems']) > 0) {
                ?>
                <div class="subitems" style="display:none; padding-left:8px;">
                  <?php
                  foreach ($subItemData['subitems'] as $subSubItemData) {
                    ?>
                    <div class="item" style="background:url('/img/bg09.gif') no-repeat 0 8px; padding-left:12px;">
						  	<a href="<?=$subSubItemData['url']?>"><?=$subSubItemData['name']?></a>&nbsp;<span class="cg" style="font-weight:normal;">(<?=getCountGoods($subSubItemData['id'], $tree, $count)?>)</span>
						</div>
                  <?php
                  }
                  ?>
                </div>
              <?php
              }
              ?>
            </div>
            <?php
            ++$itemCur;
          }
          ?>
        </div>
      <?php
      }	?>
		<div class="item" id="top_category_item_<?=$itemID?>" onclick="document.location.href='<?=$itemData['url']?>'" onmouseover="showTopSubcategories(<?=$itemID?>, <?=$hidden ? 1 : 0?>)" onmouseout="hideTopSubcategories(<?=$itemID?>)">
		  <div class="image"><img src="/uploads/catalog/<?=$itemID?>_menu.gif" width="20" height="20" alt=""></div>
		  <div class="name"><?=$itemData['name']?></div>
		</div>
  <? } ?>
  </div>
<?
	return ob_get_clean();
}

function showCatMenu($topID, $currentID) {
  global $prx, $catalogs;
  $topName = getField("SELECT name FROM {$prx}catalog WHERE id = '{$topID}' LIMIT 1");
  $parentIDs = array($currentID => $currentID);
  $pss = getArrParents("SELECT id,id_parent FROM {$prx}catalog WHERE hide = 0", $currentID);
  foreach ($pss as $ps) {
    $parentIDs[$ps['id']] = $ps['id'];
  }
  $data = getDataForCatMenu($topID);
  ob_start();
  ?>
  <div class="category_menu">
    <div class="caption"><?=$topName?></div>
    <?php
    foreach ($data as $itemID => $itemData) {
      ?>
      <div class="item <?=in_array($itemID, $parentIDs) ? 'active' : ''?>" id="category_item_<?=$itemID?>" onclick="toggleSubcategories(<?=$itemID?>)"><a href="<?=$itemData['url']?>"><?=$itemData['name']?></a></div>
      <?php
      if (count($itemData['subitems']) > 0) {
        ?>
        <div class="subitems" id="category_subitems_<?=$itemID?>" <?=in_array($itemID, $parentIDs) ? 'style="display:block;"' : ''?>>
          <?php
          foreach ($itemData['subitems'] as $subItemID => $subItemData) {
            if ($subItemID != $catalogs[2]['id']) {
              ?>
              <div class="item"><a href="<?=$subItemData['url']?>"><?=$subItemData['name']?></a></div>
              <?php
            } else {
              ?>
              <div class="item current"><?=$subItemData['name']?></div>
              <?php
            }
          }
          ?>
        </div>
      <?php
      }
    }
  ?>
  </div>
  <?php
  return ob_get_clean();
}

function showAdr($arr)
{
	return 
        //$arr['Субъект РФ'].', '.
        $arr['Город'].', '.$arr['Адрес'];
		/*
        .', ул.'.$arr['Улица']
		.', д.'.$arr['Дом']
		.($arr['Корпус'] ? '/'.$arr['Корпус'] : '')
		.($arr['Квартира'] ? ', кв.'.$arr['Квартира'] : '');
       */ 
}


function showGoodsTiles($sql,$main='',$dop='') {
  ob_start();
  ?>
  <div class="goods_tiles">
    <div class="row">
    <?php
    $cur = 0;
    $r = mysql_query($sql);
    while ($a = mysql_fetch_assoc($r)) {
      $first = ($cur % 4 == 0);
      echo showGoodTiles($a, $first,$dop);
      ++$cur;
    }
    ?>
    </div>
  </div>
  <?php
  
  return ob_get_clean();
}

function showGoodNabor($a, $id_nabor=0, $first = false,$main='') {
  ob_start();
  global $categoryCat;
  
  $id = $a['id'];
  ?>
  <div class="item_pr" style="<?=$first?'display:none;':''?>"></div> 
  <div class="item <?=$first ? 'first' : ''?>">
    <?
      if (!$first){
        ?>
        <div class="toKit"> 
         <input type="checkbox" id="<?=$id_nabor?>_<?=$id?>" checked="checked" name="addToKit<?=$id_nabor?>_<?=$id?>" class="dspl_fl mt-20 addToKit" data-goodid="<?=$id?>"/>
         <label for="<?=$id_nabor?>_<?=$id?>">В комплекте</label>
        </div> 
        <?
      }
      else
      {
       ?> 
        <div class="toKit" style="visibility:hidden;"> 
         <input type="checkbox" id="<?=$id_nabor?>_<?=$id?>"/>
         <label for="<?=$id_nabor?>_<?=$id?>">В комплекте</label>
        </div>
       <?  
      }
    ?>
    <div class="image">
      <a href="/catalog/<?=id2links($a['id_catalog']).$a['link']?>.htm"><img src="/uploads/goods/145x145x<?=$a['id']?>/<?=$a['link']?>.jpg" alt="<?=htmlspecialchars($a['name'])?>"></a>
    </div>
    <?php
    if (!$a['nalich']) {
      ?>
      <div class="na"><a href="/catalog/<?=id2links($a['id_catalog']).$a['link']?>.htm"><img src="/img/na130.png" width="130" height="130"></a></div>
      <?php
    }
    ?>
    <div class="name">
      <div style="cursor:pointer;" onClick="location.href='/catalog/<?=id2links($a['id_catalog']).$a['link']?>.htm';"><div><a href="/catalog/<?=id2links($a['id_catalog']).$a['link']?>.htm"><?=$a['name']?></a></div></div>
    </div>
    <div class="in_bottom">
        <div class="price">
          <div class="new_price">
            <div class="fl"><span class="pr_text">Цена: </span></div>
            <div class="fr"><?=number_format2($a['price'], 2, ',', ' ')?></div>
            <div class="cb"></div>
          </div>
        </div>
    </div> 
  </div> 
  <?php
  return ob_get_clean();
}

function show_svm_model($id_models)
{
  global $prx;
  
  $m = explode(',',$id_models);
  
  $res=mysql_query("SELECT GROUP_CONCAT(`name`) as name, maker, `type` FROM `{$prx}models` where id IN ('".implode("','",$m)."') GROUP BY `type`,maker");
  
  if ($cnt=mysql_num_rows($res)==0) return;
 
  while ($row=mysql_fetch_array($res))
  {
    
   if(($text = getNewFormText($row['type'])) !== false)
    $type=$text[1];
   else
    $type=$row['type'];     
   ?>
     <div class="head_models article" style="margin-bottom: 3px;margin-top:3px;">Совместимые модели <?=$type?> <?=$row['maker']?>:</div>
   <?
    $spisok_ustr=explode(',',$row['name']);
    $itog_str=implode(',<br>',$spisok_ustr);
    ?>
      <div class="sp_models"><?=$itog_str?></div>
    <?
  }
  
  
}

function show_dop_uslugi($uslugi='')
{
  global $prx;
  $res=mysql_query("select * from {$prx}uslugi where hide=0 and id IN ($uslugi) order by sort");
  
  if (mysql_num_rows($res)>0)
  {
    ?><div class="head_usl">Мы предлагаем:</div>
     <table style="width: 100%;">
    <?
      while ($row=mysql_fetch_array($res))
      {
        ?>
         <tr><td style="font-size: 11px;padding-right:10px;"> 
          <input type="checkbox" id="usl<?=$row['id']?>" name="<?=$row['id']?>" class="input_check dop_uslugi" /><label for="usl<?=$row['id']?>"><?=$row['name']?></label>
         </td>
         <td style="padding-right: 5px;" nowrap><?=number_format2($row['price'])?></td> 
        </tr>
        <tr><td colspan="2" style="padding-top: 2px;"></td></tr> 
        <?
      }
    ?></table><?  
  }      
}

function show_c_otz($id=0,$rating=0)
{
    global $prx;
 ?>
              <div style="margin-top: 5px;"> 
                <div style="float:left;padding-right:10px;">
                   <div style="overflow: hidden;" id="raiting<?=$id?>"> 
                    <?
                      for ($i=1;$i<=5;$i++)
                      {
                        ?><div style="float: left;margin-left:5px;"><img src="/img/zv<?=$rating<$i?'2':'1'?>.png" /></div><?
                      } 
                    ?>
                   </div> 
                </div>  
                <div style="float:left;margin:0;color:#1A1A1A;">
                   <?echo (($cnt_otz=getField("select count(*) from {$prx}otzivy where id_goods='{$id}'"))>0)?($cnt_otz.' '.wordsSpan($cnt_otz,'отзыв||а|ов')):''?>
                </div>
                <div style="clear: both;"></div>
              </div> 
 <?    
}

function to_history()
{
  global $prx;  
  if (!$_SESSION['user']['id']) return;  
  
  $cur_id=getField("select id from {$prx}cart_session where user_id='{$_SESSION['user']['id']}'");
  
  if (count($_SESSION['cart'])==0)
  {
   update('cart_session','',$cur_id);
   return;
  }  
  
  $info_goods = $_SESSION['cart'];
  
  if ($cur_id)
   update('cart_session',"cart_info='".cleanArr($info_goods)."', date=NOW()",$cur_id);
  else
   update('cart_session',"cart_info='".cleanArr($info_goods)."', date=NOW(), user_id='{$_SESSION['user']['id']}'");
}

function showGoodTiles($a, $first = false, $sop='') {
  ob_start();
  global $categoryCat, $removeFavorites,$prx, $price_id, $city_info;
  $id = $a['id'];
  $a['id_catalog']=getField("select cat_id from {$prx}cats_goods where good_id='{$id}'");
  
  if ($_SESSION['favorites'][$id]) $removeFavorites=1;
  else unset($removeFavorites);
  
  $a['price']=$a["price{$price_id}"];//getField("select price{$city_info['price_clm']} from {$prx}goods where id='{$id}'");

  $dop_p=$a['hit']==1?'hit':'';
  if (!$dop_p)
   $dop_p=(($a['spec']==1 || ($a['price']!=$a['price_old'] && $a['price_old']>0))?'spec':'');
   
   
     //$dop_p=$a['hit']==1?'hit':'';
  ?>
 <div class="col-md-<?=$sop?'3':'4'?> col-sm-<?=$sop?'4':'4'?> col-xs-6"> 
  <?if ($sop=='del') {?>
      <div class="cl_comp btn light favor" onclick="var i=$(this).parent('div').index(); $(this).parents('.goods_tiles').find('.row>div:eq('+i+')').fadeOut(); $('.f_classifer .row>div:eq('+i+')').fadeOut();toAjax('/favorites.php?action=remove&amp;id_goods=<?=$a['id']?>');">
        <i class="fa fa-times" aria-hidden="true"></i>
      </div>
      <div class="cb"></div>
  <?}?>
  <div class="fir_item item <?=$first ? 'first' : ''?>" style="position: relative;">
     <div class="visual">

    <div class="image">
    <? $curcat =  id2links($a['id_catalog']);?>
      <a href="/<?=id2links($a['id_catalog']).$a['link']?>.htm" style="display: block;margin:0px auto;"><img class="img-responsive" style="margin: 0 auto;" src="/uploads/goods/159x159x<?=$a['id']?>/<?=$a['link']?>.jpg" alt="<?=htmlspecialchars($a['name'])?>"></a>
    </div>

    <div class="name">
      <div style="cursor:pointer;" onClick="location.href='/<?=id2links($a['id_catalog']).$a['link']?>.htm';"><div><a href="/<?=id2links($a['id_catalog']).$a['link']?>.htm" title="<?=$a['name']?>"><?=$a['name']?></a></div></div>
    </div>
    
      <div> 
        <div class="price w100" style="float: none;">
             <div class="old"><?=($a['price_old']>0 && $a['price_old']!=$a['price'] && (int)$a['price_old'] > (int)$a['price'])?number_format2($a['price_old'], 2, ',', ' '):''?></div>
             <div class="new_price"><?=number_format2($a['price'], 2, ',', ' ')?></div>
        </div>
      </div>      
   
    <!--div style="padding-left: 15px;">
      <?
        $count_otz_dop=getField("select count(id) from {$prx}otzivy where hide=0 and id_goods='{$id}'");
        $good_raiting=$a['raiting'];
        if (!$good_raiting && $count_otz_dop>0)
          $good_raiting=round(getField("select sum(rating) from {$prx}otzivy where id_goods='{$id}'")/$count_otz_dop);
      ?>
     <?=show_c_otz($id, $good_raiting)?>
   </div-->  
    <?php
    if (!$a['nalich']) {
      ?>
      <!--div class="na"><a href="/catalog/<?=id2links($a['id_catalog']).$a['link']?>.htm"><img src="/img/na130.png" width="130" height="130"></a></div-->
      <?php
    }
    
    ?>
    </div>
      
</div>  
</div>  
    
  <?php
  return ob_get_clean();
}

function showGoodTiles_compare($a, $first = false) {
  ob_start();
  global $categoryCat, $prx, $price_id;
  $id = $a['id'];
  
  $a['price']=$a["price{$price_id}"];//getField("select price{$city_info['price_clm']} from {$prx}goods where id='{$id}'");
  ?>
 <div class="col-md-2 col-sm-3 col-xs-4"> 
  <div class="cl_comp btn light favor" onclick="var i=$(this).parent('div').index(); $(this).parents('.goods_tiles').find('.row>div:eq('+i+')').fadeOut(); $('.f_classifer .row>div:eq('+i+')').fadeOut();toAjax('/compare.php?action=clean&id=<?=$a['id']?>');">
   <i class="fa fa-times" aria-hidden="true"></i>
  </div>
  <div class="cb"></div>
  <div class="fir_item item <?=$first ? 'first' : ''?>" style="position: relative;">
     <div class="visual">

    <div class="image">
      <a href="/<?=id2links($a['id_catalog']).$a['link']?>.htm" style="display: block;margin:0px auto;"><img class="img-responsive" style="margin: 0 auto;" src="/uploads/goods/159x159x<?=$a['id']?>/<?=$a['link']?>.jpg" alt="<?=htmlspecialchars($a['name'])?>"></a>
    </div>

    <div class="name">
      <div style="cursor:pointer;" onClick="location.href='/<?=id2links($a['id_catalog']).$a['link']?>.htm';"><div><a href="/<?=id2links($a['id_catalog']).$a['link']?>.htm" title="<?=$a['name']?>"><?=$a['name']?></a></div></div>
    </div>
    
      <div> 
        <div class="price w100" style="float: none;">
             <div class="old"><?=($a['price_old']>0 && $a['price_old']!=$a['price'])?number_format2($a['price_old'], 2, ',', ' '):''?></div>
             <div class="new_price"><?=number_format2($a['price'], 2, ',', ' ')?></div>
        </div>
      </div>      
   
    <!--div style="padding-left: 15px;">
      <?
        $count_otz_dop=getField("select count(id) from {$prx}otzivy where hide=0 and id_goods='{$id}'");
        $good_raiting=$a['raiting'];
        if (!$good_raiting && $count_otz_dop>0)
          $good_raiting=round(getField("select sum(rating) from {$prx}otzivy where id_goods='{$id}'")/$count_otz_dop);
      ?>
     <?=show_c_otz($id, $good_raiting)?>
   </div-->  
    <?php
    if (!$a['nalich']) {
      ?>
      <!--div class="na"><a href="/catalog/<?=id2links($a['id_catalog']).$a['link']?>.htm"><img src="/img/na130.png" width="130" height="130"></a></div-->
      <?php
    }
    
    ?>
    </div>
      
</div>  
</div>  
    
  <?php
  return ob_get_clean();
}

function showGoodSOP($a, $first = false) {
  ob_start();
  global $categoryCat;
  
  $id = $a['id'];
  ?>
  <div class="item <?=$first ? 'first' : ''?>">
    <div style="display: table;"> 
        <div class="image" style="display: table-cell;vertical-align: middle;">
          <a href="/catalog/<?=id2links($a['id_catalog']).$a['link']?>.htm"><img src="/uploads/goods/45x45x<?=$a['id']?>/<?=$a['link']?>.jpg" alt="<?=htmlspecialchars($a['name'])?>"></a>
        </div>
        <div class="name" style="display: table-cell;vertical-align: middle;">
          <div style="cursor:pointer;" onClick="location.href='/catalog/<?=id2links($a['id_catalog']).$a['link']?>.htm';"><div><a href="/catalog/<?=id2links($a['id_catalog']).$a['link']?>.htm"><?=$a['name']?></a></div></div>
        </div>
    </div> 
    <div class="in_bottom"  style="display: table;">
        <div class="price"  style="display: table-cellvertical-align: middle;">
          <div class="new_price"><span class="pr_text">Цена: </span><?=number_format2($a['price'], 2, ',', ' ')?></div>
        </div>
        <div style="display: table-cell;vertical-align: middle;text-align:right;">
        <?php
        if ($a['nalich']) {
           ?> 
         <div class="tbl_buy" style="text-align: right;margin-right:10px;"> 
           <a id="a<?=$id?>" data-id="<?=$a['id']?>" data-href="/cart.php?show=tocart&id=<?=$a['id']?>" href="/cart.php?show=tocart&id=<?=$a['id']?>" class="fb-ajax to_cart" style="display:<?=isset($_SESSION['cart'][$a['id']]) ? 'none' : ''?>">Купить</a>
           <span class="in_cart btn_in" id="ac<?=$id?>" style="margin:0px;font-size:13px;display:<?=isset($_SESSION['cart'][$a['id']]) ? 'inline-block' : 'none'?>">В&nbsp;корзине</span>
         </div> 
          <?php
        }
        ?>
      </div>
   </div> 
  </div>  
  <?php
  return ob_get_clean();
}

function showGoodTilesBig($a, $first = false) {
  ob_start();
  $id = $a['id'];
  ?>
  <div class="item <?=$first ? 'first' : ''?>">
    <div class="image">
      <a href="/catalog/<?=id2links($a['id_catalog']).$a['link']?>.htm"><img src="/uploads/goods/210x210x<?=$a['id']?>/<?=$a['link']?>.jpg" width="210" height="210" alt="<?=htmlspecialchars($a['name'])?>"></a>
    </div>
    <?php
    if (!$a['nalich']) {
      ?>
      <div class="na"><a href="/catalog/<?=id2links($a['id_catalog']).$a['link']?>.htm"><img src="/img/na210.png" width="210" height="210"></a></div>
      <?php
    }
    ?>
    <div class="name">
      <div style="cursor:pointer;" onClick="location.href='/catalog/<?=id2links($a['id_catalog']).$a['link']?>.htm';"><div><a href="/catalog/<?=id2links($a['id_catalog']).$a['link']?>.htm"><?=$a['name']?></a></div></div>
    </div>
    <div class="price">
      <div class="caption">Цена</div>
      <?php
      if ((float)$a['price_old'] > 0) {
        ?>
        <div class="old"><?=number_format($a['price_old'], 2, ',', ' ')?> р.</div>
      <?php
      }
      ?>
      <?=number_format($a['price'], 2, ',', ' ')?> р.
    </div>
    <?php
    if ($a['nalich']) {
      ?>
      <div class="to_cart" id="a<?=$id?>" style="display:<?=isset($_SESSION['cart'][$a['id']]) ? 'none' : 'block'?>"><a href="/cart.php?show=tocart&id=<?=$a['id']?>" class="fb-ajax"><img src="/img/to_cart3.png" width="120" height="28"></a></div>
      <div class="in_cart" id="ac<?=$id?>" style="display:<?=isset($_SESSION['cart'][$a['id']]) ? 'block' : 'none'?>"><img src="/img/to_cart4.png" width="120" height="28"></div>
    <?php
    }
    ?>
  </div>
  <?php
  return ob_get_clean();
}


function show_menu()
{
    global $prx;
	
	ob_start();
	
	$addres = $_SERVER['REQUEST_URI'];
    
	$res = mysql_query("SELECT * FROM {$prx}pages WHERE id_parent='0' AND menu='1' and hide=0 ORDER BY sort,id");
    if(@mysql_num_rows($res)>0)
	{
	   $kol_men=@mysql_num_rows($res);
	?>
       <ul class="menu">
		<?	/*
		 	<li class="block">
				<img src="/img/exclusive_72x42.jpg" width="72" height="42" onMouseOver="$(this).next().show();" onMouseOut="$(this).next().hide();">
				<div id="tooltipContentA10" class="cb" style="position:absolute; display:none; background:white; border:3px solid #EF7C00; z-index:999; color:#000; max-width:300px; padding:10px;">
					<div class="width300">
						<h4>STIHL Exclusive</h4>
						<div style="padding-top:10px;">
							<strong>Значком STIHL Exclusive мы отмечаем самые качественные магазины техники STIHL и VIKING. В таком магазине предусмотрено все для максимального комфорта и удобства покупателей:</strong>
						</div>
						<ul style="margin:0; padding:5px 0 0 20px;">
							<li style="padding-top:5px;">профессиональные консультации по продукции</li>
							<li style="padding-top:5px;">большой ассортимент принадлежностей</li>
							<li style="padding-top:5px;">индивидуальный подход к клиенту</li>
							<li style="padding-top:5px;">приятная атмосфера</li>
						</ul>
					</div>
            </div>
			</li>
    <?  */
       $number_page=0;
       
		while($row = mysql_fetch_array($res))
		{
			$number_page++;
            
            $id=$row['id'];
            $link = $row['link'];

            if ($link=='') {$link="page/".$row['id'].".html";}
            $class = '1';
			
            if (strpos($link,".html")!=0 && $link!='shops')
            {
              $link_g='/page/'.$link;  
            }
            else
            {
                $link_g=$link;
            }
            
 			if($addres=='/' && $link=='/' || strpos($addres, $link) || ($addres==$link)) 
             { 
              $class = '2';
             }
             
             $align="center";
             $getChilds=getField("select id from {$prx}pages where id_parent='{$id}' and menu=1 and hide=0");  
			?>
            <li class="block">
              <div style="position: relative;" class="item first_level_page"><a class="<?=$class==2?'menu_a_act':'menu_a'?> <?=$getChilds?'withChilds':''?>" style="display: block;" href="<?=$link_g?>" ><?=$row['name']?></a>
              
              <?if ($getChilds) {?> 
               <div class="sec_level" style="border-radius:4px;width:250px;position: absolute;background: #fff;top:18px;left:20px;padding:0px 10px;border:1px solid #ccc;z-index:1000;text-align: left;display:none;">
               <?
                 $page_dop=mysql_query("select * from {$prx}pages where id_parent='{$id}' and menu=1 and hide=0");
                 $count_p=mysql_num_rows($page_dop);
                 $nn=0;
                 while ($page_d=mysql_fetch_array($page_dop))
                 {
                   
                    $link = $page_d['link'];
        
                    if ($link=='') {$link=$page_d['id'].".html";}
                    $class = '1';
        			
                    if (strpos($link,".html")!=0 && $link!='shops')
                    {
                      $link_g='/page/'.$link;  
                    }
                    else
                    {
                        $link_g=$link;
                    }                   
                   
                    $nn++;
                   ?><div style="padding:5px;padding-bottom:0px;border-bottom:<?=$nn==$count_p?'0':'0'?>px solid #ccc"><a class="menu_a" style="display: block;color:#000;" href="<?=$link_g?>"><?=$page_d['name']?></a></div><? 
                 }
               ?>
                </div>
              <?}?>
              </div>
             </li> 
            <?
		}
     ?>
         </ul>

     <?  
	}
    
    
	return ob_get_clean();
}

function show_kont()
{
  ob_start();
   echo set('footer_phone1')?'<div class="in_footer">'.set('footer_phone1').'</div>':'';
   echo set('footer_phone2')?'<div class="in_footer">'.set('footer_phone2').'</div>':'';
   echo set('footer_email')?'<div class="in_footer">'.set('footer_email').'</div>':'';
   echo set('footer_viber')?'<div class="in_footer">'.set('footer_viber').'</div>':'';
   echo set('footer_address')?'<div class="in_footer">'.set('footer_address').'</div>':'';
   echo set('footer_time')?'<div class="in_footer">'.set('footer_time').'</div>':'';
 echo ob_get_clean();  
}

function show_oplata()
{
  ?>
    <div class="oplata">
       <div class="caption">Принимаем к оплате</div>
       <div><?=set('oplata')?></div>
    </div>
  <?  
}

function show_social()
{
  ?>
    <div class="social">
       <div class="caption">Мы в социальных сетях</div>
       <div><?=set('social')?></div>
    </div>
  <?  
}


function show_rekom()
{
   global $prx;
   $flag='recom';
   $sql = "SELECT A.* FROM {$prx}goods A INNER JOIN {$prx}cats_goods CG ON CG.good_id=A.id INNER JOIN {$prx}catalog C ON C.id=CG.cat_id WHERE A.{$flag}=1 AND A.hide='0' AND C.hide=0 ORDER BY A.price";
   
   //echo showGoodsList_crat($sql);
}


function show_rekom_cabinet()
{
   global $prx;
   $flag='recom';
   $sql = "SELECT A.* FROM {$prx}goods A INNER JOIN {$prx}cats_goods CG ON CG.good_id=A.id INNER JOIN {$prx}catalog C ON C.id=CG.cat_id WHERE A.{$flag}=1 AND A.hide='0' AND C.hide=0 ORDER BY A.price";
   
   echo showGoodsList_crat_stroka($sql);
}

function show_menu_footer()
{
    global $prx;
	
	ob_start();
	
	$addres = $_SERVER['REQUEST_URI'];
    
	$res = mysql_query("SELECT * FROM {$prx}pages WHERE id_parent='0' AND menu='1' and hide=0 ORDER BY sort,id");
    if(@mysql_num_rows($res)>0)
	{
	   $kol_men=@mysql_num_rows($res);
	?>
       <ul class="menu">
       <?
       $number_page=0;
       
		while($row = mysql_fetch_array($res))
		{
			$number_page++;
            
            $id=$row['id'];
            $link = $row['link'];

            if ($link=='') {$link="page/".$row['id'].".html";}
            $class = '1';
			
            if (strpos($link,".html")!=0 && $link!='shops')
            {
              $link_g='/page/'.$link;  
            }
            else
            {
                $link_g=$link;
            }
            
 			if($addres=='/' && $link=='/' || strpos($addres, $link) || ($addres==$link)) 
             { 
              $class = '2';
             }
             
             $align="center";
             $getChilds=getField("select id from {$prx}pages where id_parent='{$id}' and menu=1 and hide=0");  
			?>
            <li class="block">
              <div style="position: relative;" class="item first_level_page"><a class="<?=$class==2?'menu_a_act':'menu_a'?> <?=$getChilds?'withChilds':''?>" style="display: block;" href="<?=$link_g?>" ><?=$row['name']?></a>
              
              <?if ($getChilds) {?> 
               <div class="sec_level" style="border-radius:4px;width:250px;position: absolute;background: #fff;top:18px;left:20px;padding:0px 10px;border:1px solid #ccc;z-index:1000;text-align: left;display:none;">
               <?
                 $page_dop=mysql_query("select * from {$prx}pages where id_parent='{$id}' and menu=1 and hide=0");
                 $count_p=mysql_num_rows($page_dop);
                 $nn=0;
                 while ($page_d=mysql_fetch_array($page_dop))
                 {
                   
                    $link = $page_d['link'];
        
                    if ($link=='') {$link=$page_d['id'].".html";}
                    $class = '1';
        			
                    if (strpos($link,".html")!=0 && $link!='shops')
                    {
                      $link_g='/page/'.$link;  
                    }
                    else
                    {
                        $link_g=$link;
                    }                   
                   
                    $nn++;
                   ?><div style="padding:5px;padding-bottom:0px;border-bottom:<?=$nn==$count_p?'0':'0'?>px solid #ccc"><a class="menu_a" style="display: block;color:#000;" href="<?=$link_g?>"><?=$page_d['name']?></a></div><? 
                 }
               ?>
                </div>
              <?}?>
              </div>
             </li> 
            <?
		}
     ?>
         </ul>

     <?  
	}
    
    
	return ob_get_clean();
}


function showGoodsDetails($sql) {
  ob_start();
  ?>
    <?php
    $r = mysql_query($sql);
    if (mysql_num_rows($r)>0)
     {
        ?>
           <table class="tbl_cart hidden-xs">
            <tr class="good-row-th">
              <th style="width: 5%;">№</th>
              <th style="width: 15%;">Артикул</th>
              <th style="width: 30%;">Наименование</th>
              <th style="width: 15%;">Цена</th>
              <th style="width: 15%;">Количество</th>
              <th></th>
            </tr>
           </table> 
           
           
           <div class="goods_list">
        <?
     }
     $number=0;
    while ($a = mysql_fetch_assoc($r)) {
        $number++;
      echo uzel_good($a,$number);
    }

    if (mysql_num_rows($r)>0)
     {
        ?>
           </div>
        <?
     }

  return ob_get_clean();
}


function showGoodsDetails_mobile($sql) {
    
  ob_start();
  ?>
    <?php
    $r = mysql_query($sql);
    if (mysql_num_rows($r)>0)
     {
        ?>
           <div class="goods_list">
        <?
     }
     $number=0;
    while ($a = mysql_fetch_assoc($r)) {
        $number++;
      echo mobile_uzel_good($a,$number);
    }

    if (mysql_num_rows($r)>0)
     {
        ?>
           </div>
        <?
     }

  return ob_get_clean();
}


function showGoodsList($sql) {
  ob_start();
  ?>
    <?php
    $r = mysql_query($sql);
    if (mysql_num_rows($r)>0)
     {
        ?>
           <div class="goods_list">
        <?
     }
     
    while ($a = mysql_fetch_assoc($r)) {
      echo showGoodList($a);
    }

    if (mysql_num_rows($r)>0)
     {
        ?>
           </div>
        <?
     }

  return ob_get_clean();
}

function showGoodsList_crat_stroka($sql) {
  ob_start();
  ?>
    <?php
    $r = mysql_query($sql);
    if (mysql_num_rows($r)>0)
     {
        ?>
           <div class="caption" style="margin-top:35px;font-size:24px;color:#8d8e8a;">Мы рекомендуем</div>
           <div class="row">
        <?
     }
     
    while ($a = mysql_fetch_assoc($r)) {
      echo 
      "<div class='col-md-4 col-sm-4'><div class='goods_list'>".showGoodList_crat($a)."</div></div>";
    }

    if (mysql_num_rows($r)>0)
     {
        ?>
         </div>
        <?
     }

  return ob_get_clean();
}


function showGoodsList_crat($sql) {
  ob_start();
  ?>
    <?php
      echo showGoodList_crat($sql);

  return ob_get_clean();
}


function show_reiting($cur)
{
 ?>
  <div style="display: table;">
     <div style="display: table-cell;padding-right:10px;">Рейтинг:</div>
    <?
     for ($i=1;$i<=$cur;$i++)
     {
       ?>
        <div style="display: table-cell;" class="raiting star5"></div>  
       <? 
     }
     for ($i=$cur+1;$i<=5;$i++)
     {
       ?>
        <div style="display: table-cell;" class="raiting star0"></div>  
       <? 
     }
    ?> 
   </div>      
 <?
}



function show_nabor($id, $id_nabor)
{
   global $prx;
   
    $sql="select * from {$prx}goods where id='{$id}'";
    $r = mysql_query($sql);
    while ($a = mysql_fetch_assoc($r)) {
      $first = ($cur % 4 == 0);
      echo showGoodNabor($a, $id_nabor, $first,$main);
      ++$cur;
    }

   $sql="select G.* from {$prx}goods G INNER JOIN {$prx}nabor N ON G.id=N.id_good where N.good_m='{$id}' and N.id_nabor='{$id_nabor}'";
    $r = mysql_query($sql);
    while ($a = mysql_fetch_assoc($r)) {
      $first = ($cur % 4 == 0);
      echo showGoodNabor($a, $id_nabor, $first,$main);
      ++$cur;
    }

   ?>
     <div class="item_pr equal"></div>
       
    <div id="priceBlock<?=$id_nabor?>" class="itog_nabor dspl_fl">
      <table class="" style="border-spacing:0;">
        <tbody>
          <tr>
            <td class="title"> Общая сумма: </td>
            <td nowrap>
              <span class="c-price kitPriceAll kitPriceAll1"></span>
              <span class="c-price2 kitPriceAll"><span class="rub">a</span></span>
            </td>
          </tr>
          <tr>
            <td class="title"> Экономия: </td>
            <td  nowrap>
              <span class="c-price kitPriceEconom kitPriceEconom1"></span>
              <span class="c-price2 kitPriceEconom"><span class="rub">a</span></span>
            </td>
          </tr>
          <tr>
            <td style="width: 95px;" class="title">Ваша цена:</td>
            <td  nowrap>
              <span class="c-price kitPriceYour kitPriceYour1"></span>
              <span class="c-price2 kitPriceYour"><span class="rub">a</span></span>
            </td>
          </tr>
        </tbody>
      </table>
      <a title="Добавить набор в заказ" class="to_nabor fb-ajax" data-nabor="<?=$id_nabor?>" data-href="/cart.php?show=tocart&id=<?=$id?>"  id="buyKit<?=$id_nabor?>">
         <div class="btn" style="text-align: center;margin:10px 0px;">Купить&nbsp;набор</div>
      </a>
    </div>   
       
   
     <!--div class="itog_nabor">
        <div class="old_price"></div>
        <div class="skidka"></div>
        <div class="new_price"></div>
        <div class="btn" style="text-align: center;margin:0px;">Купить&nbsp;набор</div>
     </div-->
   <?
    
}


function show_shops($id)
{
   global $prx;
   $res=sql("select SHG.nalich, SH.shortName from {$prx}goods_nalich SHG INNER JOIN {$prx}shops SH ON SH.id=SHG.id_shop where SHG.id_goods='{$id}'");
   
   if (mysql_num_rows($res)>0)
   {
    ?>
       <div class="nalich" itemprop="availability">
            Товар есть в наличии
       </div>
    <?   
   }
   else
   {
    ?>
       <div class="nalich nalich-no" itemprop="availability">
            Под заказ
       </div>
    <?
    $res=sql("select 0 as nalich, shortName from {$prx}shops");
   }

   
   ?><table class="shop_tbl"><?
   while ($row=mysql_fetch_array($res))
   {
     ?>
      <tr>
        <td><?=$row['shortName']?></td>
        <td><?=$row['nalich']?> шт.</td>
      </tr>
     <? 
   } 
   ?></table><?
}

function show_shops_spisok($id)
{
   global $prx;
   $res=sql("select SHG.nalich, SH.shortName from {$prx}goods_nalich SHG INNER JOIN {$prx}shops SH ON SH.id=SHG.id_shop where SHG.id_goods='{$id}'");
   
   if (mysql_num_rows($res)>0)
   {
    ?>
       <div class="nalich">
            Товар есть в наличии<br />
    <?   
   }
   else
   {
    ?>
       <div class="nalich nalich-no">
            Под заказ<br />
    <?
   }
      
   if ($cnt=mysql_num_rows($res)>0 && 1==2)
   {
    ?>
            <span>в <?=$cnt?> <?=$cnt==1?'магазине':'магазинах'?></span>
       
    <?   
   }
   ?></div><?
}


function showGoodList($a,$f=true) {
  global $prx;
  
  if (!$_SESSION['compare']) $_SESSION['compare']=array();
  
  ob_start();
  $id = $a['id'];
   $a['id_catalog']=getField("select cat_id from {$prx}cats_goods where good_id='{$id}'");
	$url = '/'.id2links($a['id_catalog']).$a['link'].'.htm';
  ?>
  <div class="item hidden-xs" style="<?=$f==false?'border:none;':''?>">
     <div class="tbl">
      <div style="width: 160px;">
                <div class="raiting_container vbottom pointer" onClick="location.href='<?=$url?>?otzivy#a_zak';">
				  <?	$rating = getRating($id);
						if($rating)
						{	?>
							<div class="starrating"><?=$rating['val']?></div> &nbsp;
							<img src="/img/page-1@3x.png" height="15" width="15" alt=""> <?=$rating['c']?>
					<?	}	?>
					 
                   <? 
                    //$good_raiting=$a['raiting'];
                    //if ($good_raiting>0) {
                     //echo show_reiting($good_raiting);
                    //}
                   ?>            
                </div>
        <div class="image">
          <a href="<?=$url?>"><img src="/uploads/goods/136x136x<?=$a['id']?>/<?=$a['link']?>.jpg" alt="<?=htmlspecialchars($a['name'])?>" width="136" height="136"></a>
        </div>
       
      </div>  
        <div class="good_name"><a href="<?=$url?>"><?=$a['name']?></a>
          <div style="vertical-align: top;text-align: left;">
           <?echo show_features($a);?>           
          </div> 
          
        </div>
        <div style="width: 160px;">
              <div> 
                  <div class="compare2" style="text-align:left;padding-left:5px;">
                       <input type="checkbox" id="prod_compare" name="com_check" data-id="<?=$id?>" class="input_check <?=$_SESSION['compare']?(in_array($id,@$_SESSION['compare'])?'checked':''):''?>"  /><label for="prod_compare"><?=in_array($id,$_SESSION['compare'])?'<a href="/compare.php">в сравнении</a>':'Сравнить'?></label>
                  </div>              
              
                <div class="article" style="text-align:left;padding-left:5px;"><span style="color:#969a9e">Артикул:</span> <?=$a['article']?></div>
                <?=show_shops_spisok($a['id'])?>
                <div class="price w100" style="float: none;margin-top:20px;">
                     <div class="old"><?=($a['price_old']>0 && $a['price_old']!=$a['price'] && (int)$a['price_old'] > (int)$a['price'])?number_format2($a['price_old'], 2, ',', ' '):''?></div>
                     <div class="new_price"><?=number_format2($a['price'], 2, ',', ' ')?></div>
                </div>
                <div style="margin:20px 0px;text-align: center;">
                    <table style="margin: 0 auto;">
                      <tr>
                        <td>
                            <div class="btn" id="a<?=$id?>" style="display:<?=isset($_SESSION['cart'][$id]) ? 'none' : 'block'?>">
                              <a data-id="<?=$id?>" data-href="/cart.php?show=tocart&id=<?=$id?>" href="/cart.php?show=tocart&id=<?=$id?>" class="fb-ajax" style="border-bottom: none;" onClick="yaCounter25860944.reachGoal('v-korzinu');">Добавить в заказ</a>
                            </div>
                            <div title="Перейти в корзину" class="btn" id="ac<?=$id?>" onclick="location.href='/cart.php'" style="display:<?=isset($_SESSION['cart'][$id]) ? 'block' : 'none'?>">Добавлен в заказ</div>
                        </td>
                      </tr>
                    </table>                
                </div>
              </div>  
             <div class="none cb">
                <?echo show_features($a);?>
             </div>            
        </div>
       
     </div>
  </div>
  
  
  <div class="item for-xs" style="<?=$f==false?'border:none;':''?>">
     <div class="tbl">
        <div class="image">
          <a href="<?=$url?>"><img class="img-responsive" src="/uploads/goods/65x65x<?=$a['id']?>/<?=$a['link']?>.jpg" alt="<?=htmlspecialchars($a['name'])?>"></a>
        </div>
        <div class="good_name"><a href="<?=$url?>"><?=$a['name']?></a></div>
      </div>
      <div class="tbl">  
            <div class="price" style="width: 100px;margin-top:8px;">
                <div class="raiting_container vbottom">
                       <? 
                        $good_raiting=$a['raiting'];
                        //if ($good_raiting>0) {
                         echo show_reiting($good_raiting);
                        //}
                       ?>            
                </div>        
               <?php
                  if ((float)$a['price_old'] > 0) {
                ?>
                  <div class="old"><?=number_format2($a['price_old'], 2, ',', ' ')?></div>
                  <div class="skidka"><span class="pr_text">Скидка: </span><?=number_format2($a['price_old']-$a['price'], 2, ',', ' ')?></div>
                <?php
                  }
              ?>
                 <div class="new_price"><?=number_format2($a['price'], 2, ',', ' ')?></div>
            </div>
            <div style="width: 90px;padding-right:5px;">
                <div class="rown">
                   <div class="col6" style="padding-right: 7px;">
                       <div align="left" class="btn light favor" id="favorite1<?=$id?>" style="display: <?=$removeFavorites?'':'none'?>;"><b class="cbl" style="margin-right: 20px;">x</b>&nbsp;<a href="javascript:toAjax('/favorites.php?action=remove&id_goods=<?=$id?>')">удалить из избранного</a></div>
            		   <a id="favorite2<?=$id?>" style="display: <?=$removeFavorites?'none':''?>;" href="javascript:toAjax('/favorites.php?action=tofavorites&id_goods=<?=$id?>')"><div align="left" class="btn light favor"><i class="fa fa-star cbl  clean"></i></div></a>
                   </div>
                   <div class="col6" style="padding-left: 7px;">
                       <div class=" btn light compare2"><i class="fa fa-balance-scale clean"></i><span class="toCompare<?=$id?>" <?=isset($_SESSION['compare'][$id])?'style="display:none"':''?>><a class="f1" href="javascript:void(0)" onClick="toAjax('/compare.php?action=tocompare&id=<?=$id?>');return false;"></a></span><span class="compare<?=$id?>" <?=!isset($_SESSION['compare'][$id])?'style="display:none"':''?>><a class="f2" href="javascript:toAjax('/compare.php?action=clean&id=<?=$id?>')" onclick="var i=$(this).parents('td:first').index(); $(this).parents('table:first').find('tr.c').each(function(index, element){ $(this).find('td.c:eq('+i+')').fadeOut(); });" title="Убрать из сравнения">Убрать</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="/compare.php" style="text-decoration: underline;" class="a_compare fb-compare">В&nbsp;сравнении (<?=count($_SESSION['compare'])?>)</a></span></div>
                   </div>
                </div>            
            </div>
            <div class="tbl_buy fr" style="margin-top:10px;"> 
                <div class="btn" id="a<?=$id?>" style="display:<?=isset($_SESSION['cart'][$id]) ? 'none' : 'block'?>"><a data-id="<?=$id?>" data-href="/cart.php?show=tocartww&id=<?=$id?>" href="/cart.php?show=tocart&id=<?=$id?>" class="fb-ajax">
                <i class="fa fa-cart-arrow-down fa-lg"></i> Добавить в заказ</a></div>
                <div title="Перейти в корзину" class="btn" id="ac<?=$id?>" onclick="location.href='/cart.php'" style="display:<?=isset($_SESSION['cart'][$id]) ? 'block' : 'none'?>">Добавлен в заказ</div>
            </div> 
        </div>
  </div>  
  <?php
  return ob_get_clean();
}


function showGoodList_crat($a,$f=true) {
  global $prx;
  
  ob_start();
  $id = $a['id'];
  //if (!$a['id_catalog'])
   $a['id_catalog']=getField("select cat_id from {$prx}cats_goods where good_id='{$id}'");

  ?>
  <div class="item crat" style="<?=$f==false?'border:none;':''?>">
     <div class="good_name" style="float: none;"><a href="/<?=id2links($a['id_catalog']).$a['link']?>.htm"><?=$a['name']?></a></div>
     <div class="tbl">
        <div class="image">
          <a href="/<?=id2links($a['id_catalog']).$a['link']?>.htm"><img class="img-responsive" src="/uploads/goods/68x68x<?=$a['id']?>/<?=$a['link']?>.jpg" alt="<?=htmlspecialchars($a['name'])?>"></a>
        </div>
        <div class="price" style="margin-top:8px;">
               <?php
                  if ((float)$a['price_old'] > 0 && $a['price_old']!=$a['price']) {
                ?>
                  <div class="old"><?=number_format2($a['price_old'], 2, ',', ' ')?></div>
                  <!--div class="skidka"><span class="pr_text">Скидка: </span><?=number_format2($a['price_old']-$a['price'], 2, ',', ' ')?></div-->
                <?php
                  }
              ?>
                 <div class="new_price"><?=number_format2($a['price'], 2, ',', ' ')?></div>
         </div>        
      </div>
      
                 <div style="margin:5px 0px;text-align: center;">
                    <table style="margin: 0 auto;">
                      <tr>
                        <td>
                            <div class="btn" id="a<?=$id?>" style="display:<?=isset($_SESSION['cart'][$id]) ? 'none' : 'block'?>">
                              <a data-id="<?=$id?>" data-href="/cart.php?show=tocart&id=<?=$id?>" href="/cart.php?show=tocart&id=<?=$id?>" class="fb-ajax" style="border-bottom: none;" onClick="yaCounter25860944.reachGoal('v-korzinu');">Добавить в заказ</a>
                            </div>
                            <div title="Перейти в корзину" class="btn" id="ac<?=$id?>" onclick="location.href='/cart.php'" style="display:<?=isset($_SESSION['cart'][$id]) ? 'block' : 'none'?>">Добавлен в заказ</div>
                        </td>
                      </tr>
                    </table>                
                </div>
      
  </div>  
  <?php
  return ob_get_clean();
}


function show_history($sop='')
{
     global $prx;
      $linksArr = array();
      ob_start();
      $on_page=set('count_sop');
      
      ?>
       <div class="category_page">  
        <div class="goods_sop">
      <?
         foreach($_SESSION['good_history'] as $h_good) 
          {
            $cnt++;
            if ($cnt>$sop) break;
            
            $row=getRow("select * from {$prx}goods where id='{$h_good}'");
            echo showGoodSOP($row);
          }
          
      ?></div></div><?  
      
      return ob_get_clean();       
}


function _showSopGoods($sop='',$id='',$id_catalog=0){
     global $prx;
      $linksArr = array();
      ob_start();
      $on_page=set('count_sop')?set('count_sop'):'4';
      
      if ($sop)
      {
       $sql = "SELECT A.* FROM {$prx}goods A INNER JOIN {$prx}cats_goods C ON A.id=C.good_id WHERE A.id IN ({$sop}) AND A.hide='0' AND A.price > 0 ORDER BY A.price LIMIT {$on_page}";
       $res = mysql_query($sql);
      } 
      
     if (@mysql_num_rows($res)==0 || !$sql)
      {
       $sql = "SELECT A.* FROM {$prx}goods A INNER JOIN {$prx}cats_goods C ON A.id=C.good_id WHERE C.cat_id='{$id_catalog}' and A.id!='{$id}' AND A.hide='0' AND A.price > 0 ORDER BY A.price LIMIT {$on_page}";
       $res = mysql_query($sql);
       //return false;
      }
      
      ?>
     <div class="goods_tiles">
      <?
       $num=0;
          while ($row=mysql_fetch_array($res)) 
          {
             echo showGoodsList_crat($row,($num%4==0?'first':''),1);
            $num++;
          }                   
      ?></div><?  
     
      
      return ob_get_clean();   
    
}

function _showSopGoods2($sop='',$id='',$id_catalog=0){
     global $prx;
      $linksArr = array();
      ob_start();
      $on_page=set('count_sop')?set('count_sop'):'4';
      
      if ($sop)
      {
       $sql = "SELECT A.* FROM {$prx}goods A INNER JOIN {$prx}cats_goods C ON A.id=C.good_id WHERE A.id IN ({$sop}) AND A.hide='0' AND A.price > 0 ORDER BY A.price LIMIT {$on_page}";
       $res = mysql_query($sql);
      } 
      
     if (@mysql_num_rows($res)==0 || !$sql)
      {
       $sql = "SELECT A.* FROM {$prx}goods A INNER JOIN {$prx}cats_goods C ON A.id=C.good_id WHERE C.cat_id='{$id_catalog}' and A.id!='{$id}' AND A.hide='0' AND A.price > 0 ORDER BY A.price LIMIT {$on_page}";
       $res = mysql_query($sql);
       //return false;
      }
      
      ?>
     <div class="goods_tiles">
      <div class="row"> 
      <?
       $num=0;
          while ($row=mysql_fetch_array($res)) 
          {
             echo showGoodTiles($row,($num%4==0?'first':''),1);
            $num++;
          }                   
      ?>
       </div>
      </div><?  
     
      
      return ob_get_clean();   
    
}

function show_hit($flag='hit')
{
      global $prx;
      $linksArr = array();
      ob_start();
      $on_page=set('count_'.$flag);
      if (!$on_page) $on_page=4; 
      
      $sql = "SELECT A.*, CG.cat_id as id_catalog FROM {$prx}goods A INNER JOIN {$prx}cats_goods CG ON CG.good_id=A.id INNER JOIN {$prx}catalog C ON C.id=CG.cat_id WHERE A.{$flag}=1 AND A.hide='0' AND C.hide=0 ORDER BY rand() LIMIT {$on_page}";
      //$sql = "SELECT A.* FROM {$prx}goods A INNER JOIN {$prx}catalog C ON C.id=A.id_catalog WHERE A.{$flag}=1 AND A.hide='0' AND C.hide=0 ORDER BY A.price LIMIT {$on_page}";
      //echo $sql;
      
      $res = mysql_query($sql);
      
      ?>
       <div class="category_page">  
        <div class="goods_tiles hit" style="margin-left: 0px;">
         <div class="row"> 
      <?
       $num=0;
          while ($row=mysql_fetch_array($res)) 
          {
             echo showGoodTiles($row,($num%4==0?'first':''),'dop');
            $num++;
          }                   
      ?>
      </div>
      </div></div><?  
      
      return ob_get_clean();      
}

function show_faq($id){
   global $prx;

   $quest=mysql_query("select *, DATE_FORMAT(date,'%d.%m.%Y %H:%i') as date_1 from {$prx}faq where id_goods='{$id}' and hide=0 and id_parent=0 order by date DESC");
   
   if (mysql_num_rows($quest))
   {
   ?> 
     <div id="question">
      <div class="w960">
               <div>
                    <?
                      while ($row_faq=mysql_fetch_array($quest))
                      {
                        ?>
                          <div class="quest">
                            <div class="quest-name">
                              <div class="n1"><?=$row_faq['name']?></div>
                              <div class="n2">спрашивает</div>
                              <div class="n3"><?=$row_faq['date_1']?></div>
                            </div>
                            <div class="text-quest">
                              <?=$row_faq['text1']?>
                            </div>
                          </div>
                        <?
                        
                        $otvet=mysql_query("select *, DATE_FORMAT(date,'%d.%m.%Y %H:%i') as date_1 from {$prx}faq where id_parent='{$row_faq['id']}' and hide=0");
                        while ($row_otvet=mysql_fetch_array($otvet))
                        {
                          ?>
                              <div class="quest answer">
                                <div class="quest-name">
                                <a name="otvet<?=$row_otvet['id']?>"></a>
                                  <div class="n1">Специалист магазина Мотор<?//=$row_otvet['name']?></div>
                                  <div class="n2">отвечает</div>
                                  <div class="n3"><?=$row_otvet['date_1']?></div>
                                </div>
                                <div class="text-quest">
                                  <?=$row_otvet['text2']?>
                                </div>
                              </div>                                 
                          <?  
                        }
                      }
                     ?>
                </div>
          
          <div style="clear: both;"></div>
       </div>   
     </div> 
    <?
   }  
}


function show_otz($id, $model='')
{
	global $prx;
	$count_otz_dop = 2;
   ?> 
     <!--div style="margin-top: 30px;"><a href="/letter.php?show=otzivy&id_goods=<?=$id?>" style="font-size:14px;text-decoration: underline;" class="fb-ajax2">оставить отзыв</a></div-->
    
   <? if ($count_otz_dop>0) 
		{ ?>
	     <div id="question" class="ffrri">
				<div class="w960">
						<div style="border-top:1px solid #EEF1F8">
						  <div class="prev" style="display:<?=$count_otz_dop>2?'':'none'?>"></div>
						  <div class="otzivy_sl" style="float: left;padding-left:10px;">
							<ul style="padding:0;">
							  <?
								 $quest=mysql_query("select *, DATE_FORMAT(date,'%d.%m.%Y') as date_1, DATE_FORMAT(date,'%H:%i') as date_2, DATE_FORMAT(date2,'%d.%m.%Y') as date_3, DATE_FORMAT(date2,'%H:%i') as date_4 from {$prx}otzivy where id_goods='{$id}' and hide=0 order by date DESC");
								 while ($row_faq=mysql_fetch_array($quest))
								 {
									?>
									<li style="float: left;" class="item">  
									 <div>
									  <div class="faq_n"><?=$row_faq['name']?> <span style="font-size: 11px;color:#989898;">написал <?=$row_faq['date_1']?> в <?=$row_faq['date_2']?></span><div style="float: left;margin-right:10px;"><?=show_reiting($row_faq['rating']);?></div></div>
									  <div class="faq" style="padding-top:7px;"><?=$row_faq['text1']?></div>
									 
										<? if ($row_faq['text2']) {?> 
										 <div style="margin: 10px 0px 10px 20px;font-style: italic;"> 
										  <div class="faq_n">TOOLHOUSE <span style="font-size: 11px;color:#989898;">написал <?=$row_faq['date_3']?> в <?=$row_faq['date_4']?></span></div>
										  <div class="faq"><?=$row_faq['text2']?></div>
										 </div>
										<?}?> 
									  </div>   
									 </li>   
									<?
								 }
						 		$res = sql("SELECT *, DATE_FORMAT(date,'%d.%m.%Y') as date_1 FROM {$prx}reviews WHERE id_goods='{$id}' and hide=0 order by date DESC");
								while($row_faq = mysql_fetch_assoc($res))
								{	?>
									<li style="float: left;" class="item">  
									 <div>
										  <div class="faq_n"><?=$row_faq['author']?> <span style="font-size: 11px;color:#989898;"><?=$row_faq['date_1']?>, отзыв с Яндекс.Маркета</span><div style="float: left;margin-right:10px;"><?=show_reiting($row_faq['grade']);?></div></div>
										  <div class="faq" style="padding-top:7px;">
											<?=$row_faq['text']?>
										<?	if($row_faq['pro']) { ?>
												<div style="font-weight:bold; padding:3px 0;">Плюсы:</div>
												<?=$row_faq['pro']?>
										<?	}	?>
										<?	if($row_faq['contra']) { ?>
												<div style="font-weight:bold; padding:3px 0;">Минусы:</div>
												<?=$row_faq['contra']?>
										<?	}	?>
										  </div>
									  </div>   
									 </li>   
							<?	}	?>
							</ul>
						 </div>
						 <div class="next" style="display:<?=$count_otz_dop>2?'':'none'?>"></div>
						 </div>
				 
				 <div style="clear: both;"></div>
			 </div>  
	     </div> 
	<?	}	?> 
     
		<div class="caption3">Оставить отзыв<?=$model ? ' об '.$model : ''?></div>
        <form action="/letter.php?action=otzivy" method="post" target="ajax" enctype="multipart/form-data" id="frmLetter">
			<input type="hidden" name="email_sp">
			<input type="hidden" name="id_goods" value="<?=(int)@$_GET['id_goods']?>">
			<table class="tblinfo" style="max-width:400px;width: 100%;">
				<tr>
					<td><input name="name" value="<?=@$user['Ф.И.О.']?>" class="input" placeholder="ФИО" style="margin-bottom: 0px;"></td>
				</tr>
				<tr>
					<td><input name="email" value="<?=@$user['email']?>" class="input" placeholder="email" style="margin-bottom: 0px;"></td>
				</tr>
				<tr>
					<td><textarea rows="8" name="text1" class="input" style="width:100%;padding: 10px;" placeholder="Отзыв..."></textarea></td>
				</tr>                
				<!--tr>
					<th>E-mail <span class="cr">*</span></th>
					<td><input name="email" value="<?=@$user['email']?>" class="input"></td>
				</tr>

				<tr>
					<th>Рейтинг <span class="cr">*</span></th>
					<td>
                      <input type="hidden" value="0" name="raiting" id="cur_raiting" />
                      <div class="starrating" value="0" onClick="$('#cur_raiting').val($(this).attr('value'))"></div>
                    </td>
				</tr-->
				<tr>
					<td align="center"><input type="submit" value="Отправить" class="btn2 light" style="width:100%;color:#fff!important;" onclick="this.form.submit()"></td>				</tr>
			</table>
		</form> 
 <?

}

function show_block_city()
{
    global $prx, $showAlertCity, $cur_city;
  require($_SERVER['DOCUMENT_ROOT'].'/assets/snippets/address/city_list.php');  
}

function show_gallery($page='')
{
    global $prx;
    if ($page) $p="where G.id_album='{$page}'";
    $sql=sql("select G.id, G.name from {$prx}gal G {$p}");
    while ($row=mysql_fetch_assoc($sql))
    {
       ?>
        <div class="gal"><?=$row['name']?></div>
        <div class="row">
       <? 
       $sql2=sql("select * from {$prx}galimg GI where GI.id_gal='{$row['id']}' order by GI.sort");
       while ($row2=mysql_fetch_assoc($sql2))
       {
            $img=$row2['img'];
            $fil=explode('/',$img);
            
            $id=$fil[1];
            $id_n=explode('.',$fil[2]);
            $fname=$fil[2];
		
         ?>
          <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
			<div style="height: 400px;">	
                <a href="/uploads/gallery/<?=$id?>/800x800x<?=$id_n[0]?>/<?=$fname?>" target="_blank" class="fb-img" rel="im_<?=$row['id']?>">
						 <img class="img-responsive" src="/uploads/gallery/<?=$id?>/400x400x<?=$id_n[0]?>/<?=$fname?>"/>
	    		</a>
            </div>    
          </div>
         <?  
       }
       ?>
       </div>
       <?
    }
}

function show_features($a,$feat='')
{
    global $prx;
 ?>
     <div class="features">
          <?php
             
            //$fInShort = getArr("SELECT name,inshort FROM {$prx}features WHERE inshort='1' {$dop}");
            $fInShort = getArr("SELECT name,inshort FROM {$prx}features");
          $features = getFeatures($a['id'], $a['id_catalog']);
          
            if ($feat)
            {
              $b=0;  
              foreach ($feat as $n_f=>$v_f)
              {
               $b++; 
               ?>
                  <div class="item" style="background: <?=$b%2==1?'#EEF1F8':'#fff'?>;">
                    <div class="name"><?=$n_f?></div>
                    <div class="value"><?=$v_f?></div>
                  </div>            
               <? 
              }  
            }
    			$i = 0;
            
            foreach ($features as $fName => $fValue) 
    		  {
                  if(!$fInShort[$fName])
    			  		continue;
                        
       			 $b++;
                        
                 $izm=getRow("select izm, type from {$prx}features where name='{$fName}'");                       
              ?>
              <div class="item">
                <div class="name"><?=$fName?>:</div>
                <div class="value"><?=$izm['type']=='мульти'?str_replace(';;;',', ',$fValue):$fValue?> <?=$izm['izm']?'('.$izm['izm'].')':''?></div>
              </div>
              <?php
    			 if($i == 8) break;
            }
          ?>
     </div>
 <?    
}

function show_features_tbl($a,$type='osn',$feat='')
{
    global $prx;
 ?>
     <div class="features">
       <table style="width: 100%;">
        <tr>
          <?php
            //$fInShort = getArr("SELECT name,inshort FROM {$prx}features WHERE inshort='1' {$dop}");
            $fInShort = getArr("SELECT name,inshort FROM {$prx}features WHERE 1=1");
          $features = getFeatures($a['id'], $a['id_catalog']);
          
          if ((!is_array($features) || count($features) == 0) && !$a['color']) {
            echo $a['text1'];
          } else {
            
            if ($feat)
            {
              foreach ($feat as $n_f=>$v_f)
              {
               ?>
                  <td>
                      <div class="item">
                        <div class="name"><?=$n_f?></div>
                        <div class="value"><?=$v_f?></div>
                      </div>
                 </td>                 
               <? 
              }  
            }
    			$i = 0;
                
            foreach ($features as $fName => $fValue) 
    		  {
    			  if(!$fInShort[$fName])
    			  		continue;
                        
                 $izm=getField("select izm from {$prx}features where name='{$fName}'");                       
              ?>
             <td>
              <div class="item">
                <div class="name"><?=$fName?>:</div>
                <div class="value"><?=$fValue?> <?=$izm?'('.$izm.')':''?></div>
              </div>
             </td>
              <?php
    			 if($i == 8) break;
            }
           
          }
          ?>
          </tr>
       </table>   
     </div>
 <?    
}


function showGoodsTable($sql) {
  ob_start();
  ?>
  <div class="goods_table">
    <?php
    $r = mysql_query($sql);
    while ($a = mysql_fetch_assoc($r)) {
      echo showGoodTable($a);
    }
    ?>
  </div>
  <?php
  return ob_get_clean();
}

function showGoodTable($a) {
  ob_start();
  $id = $a['id'];
  ?>
  <div class="item">
    <div class="article">
      Арт.: <span><?=$a['article']?></span>
    </div>
    <div class="name">
      <a href="/catalog/<?=id2links($a['id_catalog']).$a['link']?>.htm"><?=$a['name']?></a>
    </div>
    <div class="price">
      <?php
      if ((float)$a['price_old'] > 0) {
        ?>
        <div class="old"><?=number_format($a['price_old'], 2, ',', ' ')?> р.</div>
      <?php
      }
      ?>
      <?=number_format($a['price'], 2, ',', ' ')?> р.
    </div>
    <?php
    if ($a['nalich']) {
      ?>
      <div class="to_cart" id="a<?=$id?>" style="display:<?=isset($_SESSION['cart'][$a['id']]) ? 'none' : 'block'?>"><a href="/cart.php?show=tocart&id=<?=$a['id']?>" class="fb-ajax"><img src="/img/to_cart3.png" width="120" height="28"></a></div>
      <div class="in_cart" id="ac<?=$id?>" style="display:<?=isset($_SESSION['cart'][$a['id']]) ? 'block' : 'none'?>"><img src="/img/to_cart4.png" width="120" height="28"></div>
    <?php
    }
    ?>
    <div class="cb"></div>
  </div>
  <?php
  return ob_get_clean();
}

// Вывод сопутствующих товаров
function showGoodsSoput($sql, $buy=true) {
  ob_start();
  $r = mysql_query($sql);
  while ($a = mysql_fetch_assoc($r)) {
    $id = $a['id'];
    ?>
    <div class="item">
      <div class="name">
        <a href="/catalog/<?=id2links($a['id_catalog']).$a['link']?>.htm"><?=$a['name']?></a>
      </div>
      <div class="image">
        <a href="/catalog/<?=id2links($a['id_catalog']).$a['link']?>.htm"><img src="/uploads/goods/100x100x<?=$a['id']?>/<?=$a['link']?>.jpg" alt='<?=$a['name']?>'></a>
      </div>
      <div class="price"><?=number_format2($a['price'])?></div>
	<?	if($buy) { ?>
			<div class="quant">
			  <input id="kol<?=$id?>" value="1" class="numinput" onChange="checkNum(this); $('#a<?=$id?>')[0].href += '&kol='+this.value; ">
			</div>
			<div class="to_cart">
			  <a href="/cart.php?show=tocart&id=<?=$a['id']?>&nohide" class="fb-ajax" id="a<?=$a['id']?>"><img src="/img/to_cart1.gif" width="32" height="30" alt=""></a>
			</div>
	<?	}	?>
      <div class="cb"></div>
    </div>
  <?php
  }
  return ob_get_clean();
}


function showShops($limit)
{
  global $prx;
  ob_start();
  $sql = "SELECT * FROM {$prx}shops ORDER BY `id` LIMIT {$limit}";
  $r = mysql_query($sql);
  if (mysql_num_rows($r) > 0) {
    ?>
    <div class="shops">
      <div class="zag_h1 hidden-xs">Наши магазины</div>
      <?php
      while ($a = mysql_fetch_assoc($r)) {
        ?>
        <div class="item cb">
          <div class="name"><a href="/shops/<?=$a['id']?>.htm"><?=$a['shortName']?></a></div>
          <div class="text">
            <div class="adr"><b>Адрес магазина:</b><br />
              <?=nl2br($a['fullName'])?> 
            </div>
            <div class="ph"><b>Телефон</b><br /><?=nl2br($a['phone'])?></div>
          </div>
        </div>
      <?php
      }
      ?>
    </div>
  <?php
  }
  return ob_get_clean();
}

function showNews($limit)
{
  global $prx;
  ob_start();
  $sql = "SELECT id,DATE_FORMAT(`date`, '%d.%m.%Y') datef, name, link, text1 FROM {$prx}news ORDER BY `date` DESC LIMIT {$limit}";
  $r = mysql_query($sql);
  if (mysql_num_rows($r) > 0) {
    ?>
    <div class="news">
      <div class="zag_h1 hidden-xs">Новости</div>
      <?php
      while ($a = mysql_fetch_assoc($r)) {
        ?>
        <div class="item cb">
          <div class="date"><?=$a['datef']?></div>
          <div class="name"><a href="/news/<?=$a['link']?>.htm"><?=$a['name']?></a></div>
          <?
          if (file_exists($_SERVER['DOCUMENT_ROOT']."/uploads/news/{$a['id']}.png")) {?>
          <a href="/news/<?=$a['link']?>.htm"><img src="/uploads/news/100x100x<?=$a['id']?>/<?=$a['link']?>.png" style="display:block;margin:10px auto;"/></a>
         <?}?>            
          <div class="text"><?=$a['text1']?></div>
        </div>
      <?php
      }
      ?>
    </div>
    <div style="margin-top:10px;" align="right"><a href="/news/" style="text-transform: uppercase;">Все новости</a></div>
  <?php
  }
  return ob_get_clean();
}

function showArticles($limit)
{
  global $prx;
  ob_start();
  $sql = "SELECT id,DATE_FORMAT(`date`, '%d.%m.%Y') datef, name, link, text1 FROM {$prx}articles where rubr=0 and hide=0 ORDER BY `date` DESC LIMIT {$limit}";
  $r = mysql_query($sql);
  if (mysql_num_rows($r) > 0) {
    ?>
    <div class="news" style="margin-top: 28px;">
      <div class="zag_h1 hidden-xs">Статьи</div>
      <?php
      while ($a = mysql_fetch_assoc($r)) {
        ?>
        <div class="item cb">
          <div class="date"><?=$a['datef']?></div>
          <div class="name"><a href="/articles/<?=$a['link']?>.htm"><?=$a['name']?></a></div>
          <?if (file_exists($_SERVER['DOCUMENT_ROOT']."/uploads/articles/{$a['id']}.png")) {?>
          <a href="/articles/<?=$a['link']?>.htm"><img src="/uploads/articles/100x100x<?=$a['id']?>/<?=$a['link']?>.png" style="display:block;margin:10px auto;"/></a>
         <?}?>        
          <div class="text"><?=$a['text1']?></div>
        </div>
      <?php
      }
      ?>
    </div>
    <div style="margin-top:10px;" align="right"><a href="/articles/" style="text-transform: uppercase;">Все статьи</a></div>
  <?php
  }
  return ob_get_clean();
}

function showViewedPages($limit) {
  global $prx;
  ob_start();
  $swp = array_reverse($_SESSION['viewed_pages']);
  $i = 0;
  foreach ($swp as $id) {
    if ($i >= $limit) {
      break;
    }
    $sql = "SELECT id_catalog, link, name, price FROM {$prx}goods WHERE id = '{$id}' AND hide = 0 LIMIT 1";
    $r = mysql_query($sql);
    while ($a = mysql_fetch_assoc($r)) {
      ?>
      <div class="item">
        <div class="name"><a href="/catalog/<?=id2links($a['id_catalog']).$a['link']?>.htm"><?=$a['name']?></a></div>
        <div class="price"><?=number_format2($a['price'])?></div>
      </div>
      <?php
    }
    ++$i;
  }
  $wp = ob_get_clean();
  ob_start();
  if ($wp) {
    ?>
    <div class="viewed_pages">
      <div class="caption2">Вы недавно смотрели</div>
      <?=$wp?>
    </div>
    <?php
  }
  return ob_get_clean();
}

function getFeatures($goodID, $categoryID = 0) {
  global $prx;
  $goodID = (int)$goodID;
  if ($goodID == 0) {
    return array();
  }
  if ($categoryID == 0) {
    //$sql = "SELECT id_catalog FROM {$prx}goods WHERE id = '$goodID'";
    $sql = "SELECT cat_id FROM {$prx}cats_goods WHERE good_id = '$goodID'";
    $r = mysql_query($sql);
    if ($a = mysql_fetch_assoc($r)) {
      $categoryID = $a['cat_id'];
    }
  }
  if ($categoryID == 0) {
    return array();
  }
  $features = array();
  $ids_features = featuresIds($categoryID, false);
  $fGood = getArr("SELECT id_feature, value FROM {$prx}features_vals WHERE id_goods='{$goodID}'");
  $ids_categoryID = getIdParents("SELECT id,id_parent FROM {$prx}catalog", $categoryID);
  $res1 = sql("SELECT f.* FROM {$prx}features AS f LEFT JOIN (SELECT * FROM {$prx}feature_catalog) AS fc ON fc.id_feature=f.id WHERE f.id IN ({$ids_features}) ORDER BY fc.sort,f.sort,f.id");
  
  while($row1 = mysql_fetch_assoc($res1)) {
    if ($value = $fGood[$row1['id']]) {
      $features[$row1['name']] = $value;
      $features_izm[$row1['name']] = $row1['izm'];
    }
  }
  
  return $features;
}

function getTopID($tbl, $id) {
  global $prx;
  $parentID = 0;
  $sql = "SELECT id_parent FROM {$prx}{$tbl} WHERE id = '{$id}' LIMIT 1";
  $r = mysql_query($sql);
  if ($a = mysql_fetch_assoc($r)) {
    $parentID = $a['id_parent'];
  }
  if ($parentID) {
    return getTopID($tbl, $parentID);
  } else {
    return $id;
  }
}

function showFBUsefull($row)
{
	global $prx;
	if(!is_array($row))
		$row = getRow("SELECT * FROM {$prx}otzivy WHERE id='{$row}'");
	$ip_pm = arr($row['ip_pm']);
	ob_start()
?>
	Полезный отзыв? &nbsp;
<?	if(in_array($_SERVER['REMOTE_ADDR'], $ip_pm))
	{	?>
		<span class="fb_plus">Да</span>(<?=$row['plus']?>) / <span class="fb_minus">Нет</span>(<?=$row['minus']?>)
<?	} else { ?>
		<a href="javascript:toAjax('/inc/action.php?action=fb&field=plus&id=<?=$row['id']?>')" class="fb_plus">Да</a>(<?=$row['plus']?>) / 
		<a href="javascript:toAjax('/inc/action.php?action=fb&field=minus&id=<?=$row['id']?>')" class="fb_minus">Нет</a>(<?=$row['minus']?>)
<?	}
	return ob_get_clean();
}

function getSkidka()
{
	global $user, $prx;
	$skidka = (int)getField("SELECT skidka FROM {$prx}users WHERE id='{$user['id']}'");
	$cartItogo = cartItogo();
	return round($cartItogo*$skidka)/100;
}

function xlsOrder($id, $infile=true)
{
	global $DR, $prx;
	$order = getRow("SELECT * FROM {$prx}orders WHERE id='{$id}'");
	$info = arr($order['info_user']);
	$info_goods = arr($order['info_goods']);
	ob_start();
?>	
	<?='<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">'?>
		<?='<head>'?>
			<meta http-equiv="Content-Type" content="application/vnd.ms-excel; charset=utf-8" />
		<?='</head>'?>
		<?='<body>'?>
			
			<table width="800">
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td colspan="5" style="font-size:large;">Заказ №<?=$id?> от <?=date('d.m.Y', strtotime($order['date']))?></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><b>Данные клиента</b></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td>Ф.И.О.</td>
					<td colspan="4" align="left"><?=$info['Фамилия']?> <?=$info['Имя']?> <?=$info['Отчество']?></td>
				</tr>
				<tr>
					<td>Телефон</td>
					<td colspan="4" align="left"><?=$info['Телефон']?></td>
				</tr>
				<tr>
					<td>E-mail</td>
					<td colspan="4" align="left"><?=$info['E-mail']?></td>
				</tr>
				<tr>
					<td>Способ оплаты</td>
					<td colspan="4" align="left"><?=$info['Способ оплаты']?></td>
				</tr>
				<tr>
					<td>Способ доставки</td>
					<td colspan="4" align="left"><?=$info['Способ доставки']?></td>
				</tr>
		<?	if($info['Способ доставки'] != 'Самовывоз') { ?>
				<tr>
					<td>Адрес доставки</td>
					<td colspan="4" align="left"><?=showAdr($info['address'])?></td>
				</tr>
		<?	}
			if (isset($info['Компания']) && $info['Компания'] != '') {
				?>
				<tr>
					<td></td>
					<td colspan="4" align="left"><b>Реквизиты юр. лица</b></td>
				</tr>
				<tr>
					<td>Компания</td>
					<td colspan="4" align="left"><?=$info['Компания']?></td>
				</tr>
				<tr>
					<td>ИНН</td>
					<td colspan="4" align="left"><?=$info['ИНН']?></td>
				</tr>
				<tr>
					<td>КПП</td>
					<td colspan="4" align="left"><?=$info['КПП']?></td>
				</tr>
				<tr>
					<td>Расчетный счет</td>
					<td colspan="4" align="left"><?=$info['Расчетный счет']?></td>
				</tr>
				<tr>
					<td>Корреспондентский счет</td>
					<td colspan="4" align="left"><?=$info['Корреспондентский счет']?></td>
				</tr>
				<tr>
					<td>БИК</td>
					<td colspan="4" align="left"><?=$info['БИК']?></td>
				</tr>
				<tr>
					<td>Наименование банка</td>
					<td colspan="4" align="left"><?=$info['Наименование банка']?></td>
				</tr>
<?php		}	?>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><b>Содержание заказа</b></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td style="border:1px dotted #000;"><b>Код заказа</b></td>
					<td style="border:1px dotted #000;"><b>Номенклатура</b></td>
					<td style="border:1px dotted #000;"><b>Цена за единицу</b></td>
					<td style="border:1px dotted #000;"><b>Кол-во</b></td>
					<td style="border:1px dotted #000;"><b>Сумма</b></td>
				</tr>
			<?
				foreach($info_goods as $row)
				{
					$itogo += $summ = $row['price'] * $row['kol'];
				?>
					<tr>
						<td style="border:1px dotted #000;"><?=$row['kod']?></td>
						<td style="border:1px dotted #000;"><?=$row['name']?></td>
						<td align="center" style="border:1px dotted #000;"><?=$row['price']?></td>
						<td align="center" style="border:1px dotted #000;"><?=$row['kol']?></td>
						<td align="center" nowrap style="border:1px dotted #000;"><?=$summ?></td>
					</tr>
			<?	}
				if($order['delivery_sum'])
				{	?>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td>Доставка</td>
						<td align="center"><?=$order['delivery_sum']?></td>
					</tr>
			<?	}
				if($order['skidka'])
				{	?>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td>Скидка</td>
						<td align="center"><?=$order['skidka']?></td>
					</tr>
			<?	}	?>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td>Итог</td>
					<td align="center"><?=($itogo+$order['delivery_sum']-$order['skidka'])?></td>
				</tr>
			</table>
		<?='</body>'?>
	<?='</html>'?>
<?
	$content = ob_get_clean();
	if($infile)
	{
		$file = $DR."/uploads/orders/order{$id}.xls";
		file_put_contents($file, $content);
		return $file;
	}
	else
	{
		return $content;
	}
}


function show_slider_banner($row)
{
  ob_start();
    if ($row['link']!='')
    {
     ?><li><a href="<?=$row['link']?>" title="<?=$row['title']!=''?$row['title']:$row['name']?>"><img src="/uploads/bnr/<?=$row['img']?>" alt="<?=$row['alt']!=''?$row['alt']:$row['name']?>" title="<?=$row['title']!=''?$row['title']:$row['name']?>" height="345px" /></a></li><?
    }
    else
    {
     ?><li><img src="/uploads/bnr/<?=$row['img']?>" alt="<?=$row['alt']!=''?$row['alt']:$row['name']?>" title="<?=$row['title']!=''?$row['title']:$row['name']?>" height="345px" /></li><?
    } 

 return ob_get_clean();	
}

function our_works()
{
  global $prx,$db;
  
  $count=0;
  $res=mysql_query("select * from {$prx}gal where main=1 order by sort");
  $count=mysql_num_rows($res);
 if ($count==0) return;  
  ?>
   <div id="our_works">
      <div class="caption2">Наши работы</div>
      <div id="tm_photo">
          <div>
            <?
             $nn=0;
             
             while ($row=mysql_fetch_array($res))
             //foreach (glob("uploads/alb_gallery/min/*") as $filename)
             {
                ?>
                 <a class="fb-img" rel="fan2" href="/uploads/alb_gallery/<?=$row['id']?>.jpg"><div class="good_img3" style="background: #fff url('/uploads/alb_gallery/min/280x280/<?=$row['id']?>.jpg') no-repeat center center;"></div></a>
                <?
             } 
            ?>
          </div>
      </div>
      <div style="clear: both;"></div>
       <div style="float:right;margin:25px 5px;"><a href="/gallery/">ВСЕ РАБОТЫ</a></div>
       <div style="clear: both;"></div>
   </div>
  <?  
}


function showBnrTop($row)
{
	ob_start();
?><a href="<?=$row['link']?>" style="display:inline-block; width:320px; height:200px; background:url(/img/p1.png) 20px 155px <?=$row['color']?> no-repeat;">
		<div style="padding:25px 20px;">
			<div style="float:left; color:white;">
				<div style="font-size:16px;"><?=$row['name']?></div>
				<div style="margin-top:10px;"><?=nl2br($row['text'])?></div>
			</div>
			<div style="float:right; display:table-cell; vertical-align:central;">
				<img src="/uploads/bnr/<?=$row['id']?>.gif" width="124" height="160" class="ratio">
			</div>
			<div style="clear:both;"></div>
		</div>
	</a><?
	return ob_get_clean();	
}

function showBnrR($row)
{
	ob_start();
?><a href="/<?=$row['link']?>" style="display:inline-block; width:213px;">
		<div style="">
			<!--div style="color:white;" align="left">
				<div style="font-size:16px;"><?=$row['name']?></div>
				<div style="margin-top:10px;"><?=nl2br($row['text'])?></div>
			</div-->
			<div style="margin-top:10px;" align="center">
				<img src="/uploads/bnr/<?=$row['img']?>" class="ratio" style="max-width: 213px;">
			</div>
		</div>
	</a><?
	return ob_get_clean();	
}

function show_izbr()
{
    global $prx, $ids_catalog_nohide;
    ob_start();
    $ids=getField("select ids_goods_favorites from {$prx}users where id='{$_SESSION['user']['id']}'");
    ?>
     <div class="category_page">
       <?=showGoodsTiles("SELECT * FROM {$prx}goods WHERE id_catalog IN ({$ids_catalog_nohide}) AND hide='0' AND id IN (".($ids ? $ids : 0).")",'','del');?>
     </div>
   <?  
   return ob_get_clean();
}

function showCertificates(){
    global $prx;
    $res = sql("SELECT * FROM {$prx}sertificates");
    if (mysql_num_rows($res)){
    ob_start();
        ?>
        <div class="zag_h1 hidden-xs" style="width:auto;margin-top:20px;text-align: center;">Сертификаты</div>
        <div class="hidden-xs page_text">
          <table style="width: 100%;">
            <tr>
            <? while ($row = mysql_fetch_assoc($res)){?>
              <td style="text-align: center;width:25%;"><a class="fb-img" rel="cert" href="/uploads/sertificates/<?=$row['id']?>.jpg"><img src="/uploads/sertificates/193x269/<?=$row['id']?>.jpg"/></a></td>
            <?}?>
            </tr>
          </table>
        </div>
        <?
        return ob_get_clean();
    } else {
        return '';
    }
}



// ОТЗЫВЫ ИЗ ЯНДЕКС-МАРКЕТ
function parse_reviews_market($id=0,$model_id=0)
{
    global $prx, $API_KEY, $oauth_token, $oauth_client_id;
    
    $sq=getRow("select id,DATEDIFF(date, NOW()) as days from {$prx}reviews where id_goods='{$id}'");
     
    if (($sq['id'] && $sq['days']<30) || !$id)
    { 
     return 'base';
    }

    //$id='10972528';
   $model_id=$model_id?$model_id:'';

  //-----ищем через партнера маркета ------------------- 
  
  if(!$model_id)
  {
  	$model_id = getYModelId($id);
  }
  
   if (!$model_id)
   {

   }
  //--------------------------------------------------- 
   
    
   if ($model_id)
   {  

     for ($page=1;$page<=50;$page++)
     {
      
        $url = "https://api.content.market.yandex.ru/v1/model/{$model_id}/opinion.json?page={$page}";
               $headers = array(
                    "Host: api.content.market.yandex.ru",
                    "Accept: */*",
                    "Authorization: {$API_KEY}"
                );
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                $data = curl_exec($ch);
                curl_close($ch);   


          update('log',"log='{$url}{$data}', date=NOW(), id_model='{$model_id}'");


       if (!$data) break;
           
           //$reviews='base';
           
       if ($data!='base')
       {    
           
           $rev=json_decode($data);
           
            if (!$rev->errors)
              update_reviews($id,$rev);
            elseif ($rev->errors[0]=="Model {$id} not found")
              update_reviews($id);
            else
              break;  
       }
       else
       {
          //echo "1";
       }

    }
    
     update('goods',"model_market_id='{$model_id}'",$id);
    
   }  
      
     // print_r($data);
      
  return $data;
}

function update_reviews($id=0,$rev='')
{
  global $prx;
  
  if (!$rev)
  {
    $set="date_update=NOW(),id_goods='{$id}'";
    update('reviews',$set);
  }     
  else 
  foreach($rev->modelOpinions->opinion as $review)
  {
    $dt=substr($review->date,0,-3);
    
    $dt=date('Y-m-d',$dt);
    $review->grade=$review->grade+3;
    $set="author='{$review->author}',date_update=NOW(),id_goods='{$id}',text='{$review->text}',contra='{$review->contra}',pro='{$review->pro}',grade='{$review->grade}',date='{$dt}'";
     
     update('reviews',$set);   
    //print_r($review->text);    
  }
  if($rating = getField("SELECT SUM(grade)/COUNT(*) AS rating FROM {$prx}reviews WHERE id_goods='{$id}'"))
	  update('goods', "rating='".cleanArr(array('127.0.0.1'=>round($rating*100)/100))."'", $id);
}

function getYModelId($id)
{
	return; // !!!!!!!!!!!!!!!!!!! ОТКЛЮЧИЛ !!!!!!!!!!!!!!!!!!!!!!
	
   global $prx, $API_KEY, $oauth_token, $oauth_client_id;

	 $sql=getRow("select id, name from {$prx}goods where id='{$id}'");
	 if(!$sql)
	 	return;
	 
	 $cur_id=$sql['id'];
	 $cur_name=$sql['name']; 
	 
		 $query=urlencode($cur_name);
		 $url='https://api.partner.market.yandex.ru/v2/models.json?query='.$query.'&regionId=213&oauth_token='.$oauth_token.'&oauth_client_id='.$oauth_client_id;  
//pr($url);
		 //$url = "https://api.partner.market.yandex.ru/v2/models.json?query=Snap&oauth_token=AQAAAAAJgJccAASQcoXtOO90WkzZuBl3FK-ELtE&oauth_client_id=b408e3886e9f4209bccaf1d71bb1a66c";
	  
	  $headers = array(
			 "Host: api.partner.market.yandex.ru",
			 "Accept: */*"
		);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$data = curl_exec($ch);
		curl_close($ch);
//pr($data);
		$dt=json_decode($data,true);
	  
		 $mm=0;
		 
	 if (!$dt['models'])
	  {
	  }
	  else
	  { 
		 
		 foreach($dt['models'] as $nom=>$object)
		 {
			$mm++;
			if ($mm>1) continue;
			
			//if ($object['name']==$cur_name)
			 update('goods','model_market_id="'.$object['id'].'"',$cur_id);
			return $object['id'];       
		 }
	  }
}

function getRating($id_goods)
{
	global $prx;
	$row = getRow("SELECT SUM(grade) s, COUNT(id) c FROM {$prx}reviews WHERE id_goods='{$id_goods}'");
	if(!$row['s'])
		return array();
	$row['val'] = $row['s'] / $row['c'];
	return $row;
}

?>