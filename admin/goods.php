<?

// характеристики
function showFeatures($id_catalog, $id_goods)
{
    global $prx, $tbl;
    if (!$id_catalog) return;
    $ids_features = featuresIds($id_catalog, false);

    $fVals = getArr("SELECT id_feature, GROUP_CONCAT(DISTINCT value SEPARATOR '~|~') AS vals FROM {$prx}feature_good WHERE id_feature IN ({$ids_features}) GROUP BY id_feature ORDER BY value");
    $fGood = getArr("SELECT id_feature, value FROM {$prx}feature_good WHERE id_good='{$id_goods}'");

    ob_start();
    ?>
    <table class="vert" width="100%">
        <?
        $ids_catalog = getIdParents("SELECT id,id_parent FROM {$prx}catalog", $id_catalog);
        $res = sql("SELECT f.* FROM {$prx}feature AS f LEFT JOIN (SELECT * FROM {$prx}feature_catalog WHERE id_catalog='{$ids_catalog[0]}') AS fc ON fc.id_feature=f.id WHERE f.id IN ({$ids_features}) ORDER BY fc.sort,f.sort,f.id");

        while ($row = mysql_fetch_assoc($res)) {
            $id = $row['id'];
            $arr = explode('~|~', $fVals[$id]);
            natsort($arr); ?>
            <tr>
                <th><?= $row['name'] ?></th>
                <td>
                    <?
                    if ($row['type'] == 'мульти') {
                        ?>
                    <input type="hidden" value="<?= $id ?>" name="multi[<?= $id ?>][]"/>
                        <table style="width: 100%;">
                            <?
                            $vals = explode(";;;", $fGood[$id]);
                            $kk = 0;
                            if (count($vals) > 0) {
                                foreach ($vals as $nn => $vl) //for ($kk=1;$kk<=count($vals);$kk++)
                                {
                                    $kk++;
                                    ?>
                                    <tr>
                                        <td>
                                            <input type="hidden" name="multi_feat_id[<?= $id ?>][<?= $kk ?>]"
                                                   value="<?= $id ?>"/>
                                            <input name="features_[<?= $id ?>][<?= $kk ?>]" id="fi<?= $id ?>"
                                                   value='<?= $vl ?>' autocomplete="off">
                                        </td>
                                    </tr>
                                    <?
                                }
                            }

                            for ($i = $kk + 1; $i < 10; $i++) { ?>
                                <tr style="display: <?= $i == $kk ? '' : 'none' ?>;">
                                    <td>
                                        <input type="hidden" name="multi_feat_id[<?= $id ?>][<?= $i ?>]"
                                               value="<?= $id ?>"/>
                                        <input name="features_[<?= $id ?>][<?= $i ?>]" id="fi<?= $id ?>" value=''
                                               autocomplete="off">
                                    </td>
                                </tr>
                            <? } ?>
                            <tr>
                                <td valign="bottom" style="text-align: right;"><a href="javascript://"
                                                                                  onClick="$(this).parents('table').find('tr:hidden:lt(1)').fadeIn();"
                                                                                  class="la16"
                                                                                  style="background-position:0 -128px;"
                                                                                  title="Добавить"></a></td>
                            </tr>
                        </table>
                    <?
                    }
                    else
                    {
                    ?>
                    <input name="features[<?= $id ?>]" id="fi<?= $id ?>" value='<?= $fGood[$id] ?>' autocomplete="off">
                        <script>
                            $(function () {
                                $('#fi<?=$id?>').autocomplete({
                                    //maxHeight: 200, // Максимальная высота списка подсказок, в пикселях
                                    zIndex: 9999, // z-index списка
                                    minChars: 0, // Минимальная длина запроса для срабатывания автозаполнения
                                    lookup: ['<?=implode("','", $arr)?>'] // Список вариантов для локального автозаполнения
                                });
                            });
                        </script>
                    <? } ?>
                </td>
            </tr>
        <? } ?>


    </table>
    <?
    return ob_get_clean();
}

// ------------------ИНИЦИАЛИЗАЦИЯ--------------------
require('inc/common.php');
$tbl = 'good';
$rubric_img = 896;
$rubric = 'Каталог &raquo; Товары';
$top_menu = 'catalog';
$id = (int)@$_GET['id'];
$ids = $_GET['ids'] ? (array)$_GET['ids'] : array($id);
$search = clean(@$_GET['search']);

$id_makers = (int)@$_REQUEST['id_makers'];
$id_catalog = (int)@$_REQUEST['id_catalog'];

$childs = @$_GET['childs'];

$k = @$_SESSION[$tbl . '_k'] ? $_SESSION[$tbl . '_k'] : 50;
$p = @$_GET['p'] ? $_GET['p'] : 1;
$where = $id_catalog && $childs
    ? "  AND G.id_catalog IN (" . getIdChilds("SELECT id,id_parent FROM {$prx}catalog", $id_catalog, false) . ")"
    : ($id_catalog ? "AND G.id_catalog='{$id_catalog}'" : '');
$where .= $id_makers ? " AND G.id_maker='{$id_makers}'" : '';
$where .= $search ? ' AND (' . getWhere('G.id,G.article,G.name') . ')' : '';
$sort = setSort('G.name');
$sqlmain = "SELECT G.* FROM {$prx}{$tbl} G WHERE 1 {$where} ORDER BY {$sort}";

$cats = array();
$res = mysql_query("SELECT id_catalog FROM {$prx}good where id={$id}");
if (@mysql_num_rows($res)) {
    while ($arr = mysql_fetch_array($res)) {
        $cats[] = $arr['id_catalog'];
    }
}
$catalogs = getArr("SELECT id as id_good, id_catalog FROM {$prx}good");
// -------------------СОХРАНЕНИЕ----------------------
//if($action && !($_SESSION['priv'] == 'admin' || $_SESSION['priv']['red_goods']))
//errorAlert('Нет прав для редактирования товаров',1);

if (!$id_catalog && $id) $id_catalog = getField("select id_catalog from {$prx}good where id='{$id}'");


switch ($action) {
    case 'red':
        foreach ($_POST as $key => $val)
            $$key = clean($val);

        $id = uniUpdate($id);
        update($tbl, "date_updated=NOW()", $id);
        linkTest($id);

        if (!$_GET['id'])
            reSort("SELECT id FROM {$prx}{$tbl} WHERE id_catalog='{$id_catalog}' ORDER BY sort,id");

        if($_FILES['file']['name']){
            update($tbl, "img='".$_FILES['file']['name']."'", $id);
        }

        upfile("../uploads/{$tbl}/{$id}.jpg", $_FILES['file'], "../uploads/{$tbl}/".@$_POST['del_file'], false, 1200);
        for ($i = 1; $i < 30; $i++)
            upfile("../uploads/{$tbl}/{$id}_{$i}.jpg", $_FILES['file' . $i], "../uploads/{$tbl}/".@$_POST['del_file'. $i], false, 1200);

        // характеристики
        $ids_f = [0];
        foreach ((array)$features as $id_features => $value) {
            $id_feature_good = getField("SELECT id FROM {$prx}feature_good WHERE id_good='{$id}' AND id_feature='{$id_features}'");
            if($value) {
                //echo "=".$id_feature_good;
                $ids_f[] = update('feature_good', "id_good='{$id}', id_feature='{$id_features}', value='" . clean($value) . "'", $id_feature_good);
            }
        }

        sql("DELETE FROM {$prx}feature_good WHERE id_good='{$id}' AND id NOT IN (".implode(',',$ids_f).")");
        sql("UPDATE {$prx}feature_good SET `value`=REPLACE(`value`,',','.') WHERE id_good='{$id}' AND id_feature IN (SELECT id FROM {$prx}feature WHERE `type`='диапазон')");


        $feat = array();

        if(is_array($_POST['multi']))
        foreach ($_POST['multi'] as $num => $m_val) {
            if ($_POST['features_'][$num]) {
                foreach ($_POST['features_'][$num] as $io => $vv) {

                    if ($vv) {
                        $feat[$num][] = $vv;
                    }
                }
                if (count($feat) > 0) {
                    //--записать в базу с разделителем ;;;
                    mysql_query("delete from {$prx}feature_good where id_feature='{$num}' and id_good='{$id}'");
                    update('feature_good', "id_feature='{$num}',id_good='{$id}', value='" . implode(';;;', $feat[$num]) . "'");
                }
            }
        }

        $p = getPage($sqlmain, $id, $k);


        if (isset($_POST['apply'])) {
            ?>
            <script>top.$('#frmRed')[0].action += '&id=<?=$id?>';
                top.topReload(true);
            </script><?
        } else {
            ?>
            <script>top.location.href = "?id_catalog=<?=$id_catalog?>&id=<?=$id?>&p=<?=$p?>&rand=<?=mt_rand()?>";</script><?
        }
        exit;

    // перемещаем товары в другой каталог
    case 'replace':
        if (!$ids) exit;
        update($tbl, "id_catalog='{$id_catalog}'", $ids);
        foreach ($ids as $id) {
            $insert[] = "('{$id}', '$id_catalog')";
        }
        $insert = implode(',', $insert);
        $goods = implode(',', $ids);

        ?>
        <script>location.reload();</script><?
        exit;

    // характеристики
    case 'features':
        ?>
        <script>$('#features').html('<?=cleanJS(showFeatures($id_catalog, $id))?>');</script><?
        exit;

    case 'del':
        $ids = $ids ? implode(',', $ids) : 0;
        sql("DELETE FROM {$prx}feature_good WHERE id_good IN ({$ids})");
        sql("DELETE FROM {$prx}good_catalog WHERE id_good IN ({$ids})");
        break;
}
// остальные события
if ($action) {
    $_SESSION[$tbl . '_post'] = $_POST;
    $vars = array(
        'tbl' => $tbl,
        'action' => $action,
        'move_group' => 'id_catalog'
    );
    go301('/admin/inc/action.php?1' . getQS() . '&' . http_build_query($vars));
    exit;
}


// ----------------------ВЫВОД------------------------
ob_start();
switch ($show) {
    case 'red': //	редактирование
        $row = getRow("SELECT * FROM {$prx}{$tbl} WHERE id='{$id}'");
        $rubric .= ' &raquo; ' . ($id ? 'Редактирование' : 'Добавление');
        if ($id) {
            //$id_catalog = $row['id_catalog'];
            $id_makers = $row['id_maker'];
            $link = $row["link"];
            $img = $row["img"];
        }

        ?>
        <form action="?id=<?= $id ?>&action=red" method="post" id="frmRed" enctype="multipart/form-data" target="iframe"
              onSubmit="$('#ids_uslugi option').attr('selected', 'true');$('#ids_companions option').attr('selected', 'true');$('#ids_similar option').attr('selected', 'true');$('#ids_model option').attr('selected', 'true');$('#ids_recommend option').attr('selected', 'true');$('#ids_rashodka option').attr('selected', 'true');">
            <input type="hidden" name="use_date" value="1"/>
            <table class="red" width="900">
                <tr>
                    <th>Раздел каталога</th>
                    <td><?=dllTree("SELECT id, name, id_parent FROM {$prx}catalog ORDER BY sort,id", 'class="chosen" data-placeholder="Выберите раздел" name="id_catalog" id="id_catalog" style="width:98%;" onChange="toAjax(\'?action=features&id='.$id.'&id_catalog=\'+this.value)"', $id?$row['id_catalog']:$_GET["id_catalog"], '', null, 0, 0, ".&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;")?></td>
                </tr>
                <tr>
                    <th>Название</th>
                    <td><input name="name" value='<?= $row['name'] ?>'></td>
                </tr>
                <tr>
                    <th>H1</th>
                    <td><input name="h1" value='<?= $row['h1'] ?>'></td>
                </tr>
                <tr>
                    <th>Транслит для ссылки</th>
                    <td><input name="link" value='<?= $row['link'] ?>'></td>
                    <td><?= help('Формируется автоматически') ?></td>
                </tr>
                <tr>
                    <th>Изображение</th>
                    <td>
                        <span class="cw">0.</span> <?= fileUpload("/uploads/{$tbl}/{$id}.jpg", 'name="file" style="width:80%"') ?>
                    </td>
                </tr>
                <tr>
                    <th>Дополнительные<br>изображения</th>
                    <td>
                        <?
                        if($link) {
                            $has_img = 0;
                            $files = glob(DOCUMENT_ROOT . "/uploads/{$tbl}/{$id}_*.*");
                            $i = 0;
                            foreach ($files as $fn) {
                                $fn = basename($fn);
                                if ($fn != $img) {
                                    $i++;
                                    ?>
                                    <div class=""><?= $i ?>. <?= fileUpload("/uploads/{$tbl}/{$fn}", 'name="file' . $i . '" style="width:80%"') ?></div><?
                                }else{
                                    $has_img = 1;
                                }
                            }
                        }
                        for ($i=$files?count($files)-$has_img+1:1; $i<30; $i++) {
                            ?><div style=" <?= $i > 1 ? 'display:none;' : '' ?>">
                            <?= $i ?>. <?= fileUpload("", 'name="file' . $i . '" style="width:80%"') ?>
                            </div><?
                        }
                    ?></td>
                    <td valign="bottom"><a href="javascript://"
                                           onClick="$(this).parent().prev().find('div:hidden:first').fadeIn();"
                                           class="la16" style="background-position:0 -128px;"
                                           title="Добавить изображение"></a></td>
                </tr>
                <tr>
                    <th>Вводное описание</th>
                    <td><textarea name="introtext" toolbar="medium" rows="15"><?= $row['introtext'] ?></textarea></td>
                </tr>
                <tr>
                    <th>Описание</th>
                    <td><textarea name="text" toolbar="medium" rows="15"><?= $row['text'] ?></textarea></td>
                </tr>
                <tr>
                    <th>Характеристики</th>
                    <td id="features"><?= showFeatures((int)$id_catalog, $id) ?></td>
                </tr>
                <tr>
                    <th>Видео</th>
                    <td>
                        <? for ($i = 1; $i < 5; $i++) { ?>
                            <div style=" <?= $i > 1 ? 'margin-top:5px;' : '' ?>">
                                <input name="video_name<?= $i ?>" value="<?= $row['video_name' . $i] ?>"
                                       placeholder="Заголовок"><br>
                                <textarea name="video_kod<?= $i ?>"
                                          placeholder="Код видео"><?= $row['video_kod' . $i] ?></textarea>
                            </div>
                        <? } ?>
                    </td>
                </tr>
                <tr>
                    <th>Спецпредложение</th>
                    <td><input name="spec" type="checkbox" <?= $row['spec'] ? 'checked' : '' ?> style="width:auto;"
                               value="1"></td>
                </tr>

                <tr>
                    <th>Хит</th>
                    <td><input name="hit" type="checkbox" <?= $row['hit'] ? 'checked' : '' ?> style="width:auto;"
                               value="1"></td>
                </tr>

                <tr>
                    <th>Новинка</th>
                    <td><input name="new" type="checkbox" <?= $row['new'] ? 'checked' : '' ?> style="width:auto;"
                               value="1"></td>
                </tr>
                <tr>
                    <th>Скрыть</th>
                    <td><input name="hide" type="checkbox" <?= $row['hide'] ? 'checked' : '' ?> style="width:auto;"
                               value="1"></td>
                </tr>
                <tr>
                    <th>title</th>
                    <td><input name="title" value='<?= $row['title'] ?>'></td>
                </tr>
                <tr>
                    <th>keywords</th>
                    <td><textarea name="keywords"><?= $row['keywords'] ?></textarea></td>
                </tr>
                <tr>
                    <th>description</th>
                    <td><textarea name="description"><?= $row['description'] ?></textarea></td>
                </tr>
                <tr>
                    <td align="center" colspan="2"><?= btnAction('Save,Apply,Cancel') ?></td>
                </tr>
            </table>
        </form>
        <? break;

    default:    // просмотр
        ?>
        <form>
            <table class="content" style="margin-top:0;">
                <tr>
                    <th>Раздел каталога</th>
                    <th>
                        <?= dllTree("SELECT id,name,id_parent FROM {$prx}catalog ORDER BY sort,id", 'class="chosen" style="min-width:300px" data-placeholder="Выберите раздел" name="id_catalog" onChange="this.form.submit();"', $id_catalog, '', null, 0, 0, ".&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;") ?></th>
                    <th class="normal"><label><input type="checkbox" name="childs" <?= ($childs ? 'checked' : '') ?>
                                                     onChange="this.form.submit();"> c подразделами</label></th>
                </tr>
            </table>
        </form>
        <br><br>
        <?= lnkAction('Add,Dels,Copy', "&id_catalog={$id_catalog}&id_makers={$id_makers}") ?>
        <a href="javascript://" onClick="$(this).next().fadeToggle();" class="la" style="background-position:0 -512px;"
           title="Переместить отмеченные товары в другой раздел">переместить</a>
        <span style="display:inline;">в <?= dllTree("SELECT id,name,id_parent FROM {$prx}catalog ORDER BY sort,id", 'class="chosen" data-placeholder="Выберите раздел" onChange="if(sure()) toAjax(\'?action=replace&id_catalog=\'+this.value+getCbQs(\'ids[]\'));"', '', '', null, 0, 0, ".&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;") ?></span> &nbsp; &nbsp;
        <?= showK($k) ?>
        <table class="content">
            <tr class="nodrop nodrag">
                <th><input type="checkbox" onClick="setCbTable(this)"></th>
                <th sort="<?= getSort('id') ?>">ID</th>
                <th class="center">Изображения<br><input
                            type="checkbox" <?= @$_SESSION[$tbl . '_hideimg'] ? '' : 'checked' ?>
                            onClick="toAjax('?action=set_hideimg&value='+(this.checked ? 0 : 1));"
                            title="Скрыть/Показать изображения"></th>
                <th sort="<?= getSort('name') ?>">Название</th>
                <th sort="<?= getSort('price') ?>">Цена</th>
                <th sort="<?= getSort('spec') ?>" title="Спец">Спец
                    <div align="center"><input type="checkbox"
                                               onClick="event.cancelBubble=true; if(sure()) { setCbTable(this,true); } else  return false;">
                    </div>
                </th>
                <th sort="<?= getSort('hit') ?>" title="Хит">Хит
                    <div align="center"><input type="checkbox"
                                               onClick="event.cancelBubble=true; if(sure()) { setCbTable(this,true); } else  return false;">
                    </div>
                </th>
                <th sort="<?= getSort('new') ?>" title="Новинка">Новинка
                    <div align="center"><input type="checkbox"
                                               onClick="event.cancelBubble=true; if(sure()) { setCbTable(this,true); } else  return false;">
                    </div>
                </th>
                <th sort="<?= getSort('hide') ?>">Скрыть
                    <div align="center"><input type="checkbox"
                                               onClick="event.cancelBubble=true; if(sure()) { setCbTable(this,true); } else  return false;">
                    </div>
                </th>
                <th>Ссылка</th>
                <th></th>
            </tr>
            <?
            $res = sql($sqlmain . ' LIMIT ' . ($p - 1) * $k . ', ' . $k);
            while ($row = mysql_fetch_assoc($res)) {
                $id = $row['id'];
                $link = $row['link'];
                $img = $row['img'];
                ?>
                <tr id="tr<?= $id ?>" onDblClick="location.href='?id=<?= $id ?>&show=red'">
                    <td><input type="checkbox" name="ids[]" value="<?= $id ?>"></td>
                    <td align="right"><?= $id ?></td>
                    <td align="center">
                        <? if (!@$_SESSION[$tbl . '_hideimg'] && file_exists($_SERVER['DOCUMENT_ROOT'] . "/uploads/{$tbl}/{$id}.jpg")) { ?>
                            <a href="/uploads/<?= $tbl ?>/<?= $id ?>.jpg<?= $id == (int)$_GET['id'] ? '?rand=' . mt_rand() : '' ?>"
                               class="fb-img lupa" rel="fb<?= $id ?>" title='<?= $row['name'] ?>'>
                                <img src="/uploads/<?=$tbl?>/80x80/<?= $id ?>.jpg<?= $id == (int)$_GET['id'] ? '?rand=' . mt_rand() : '' ?>"
                                     title="увеличить">
                            </a>
                            <br>
                            <?php
                        }
                        ?>
                    </td>
                    <td>
                        <div class="redone" id="<?= $id ?>" name="name"><?= $row['name'] ?></div>
                    </td>
                    <td align="left" nowrap>
                        <div class="redone" id="<?= $id ?>" name="price">
                            <?
                            echo $row['price'];
                            ?>
                        </div>
                    </td>
                    <td align="center"><input type="checkbox" <?= ($row['spec'] ? 'checked' : '') ?>
                                              onClick="toAjax('?action=redone&id=<?= $id ?>&field=spec&value='+(this.checked ? 1 : 0))">
                    </td>
                    <td align="center"><input type="checkbox" <?= ($row['hit'] ? 'checked' : '') ?>
                                              onClick="toAjax('?action=redone&id=<?= $id ?>&field=hit&value='+(this.checked ? 1 : 0))">
                    </td>
                    <td align="center"><input type="checkbox" <?= ($row['new'] ? 'checked' : '') ?>
                                              onClick="toAjax('?action=redone&id=<?= $id ?>&field=new&value='+(this.checked ? 1 : 0))">
                    </td>
                    <td align="center"><input type="checkbox" <?= ($row['hide'] ? 'checked' : '') ?>
                                              onClick="toAjax('?action=redone&id=<?= $id ?>&field=hide&value='+(this.checked ? 1 : 0))">
                    </td>
                    <td><a href="/<?= $row['link'] ?>/">открыть</a></td>
                    <td><?= lnkAction(!$childs && $id_catalog ? 'Red,Del' : 'Red,Del', '&id_catalog=' . $catalogs[$row['id']]) ?></td>
                </tr>
            <? } ?>
            <tr>
                <td colspan="99" align="center"><?= lnkPages($sqlmain, $p, $k, '?p=%s' . getQS()) ?></td>
            </tr>
        </table>
        <? break;
}
$content = ob_get_clean();

require('template.php');