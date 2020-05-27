<?

// ------------------ИНИЦИАЛИЗАЦИЯ--------------------
require('inc/common.php');
$tbl = 'project';
$rubric_img = 896;
$rubric = 'Проекты';
$id = (int)@$_GET['id'];
$ids = $_GET['ids'] ? (array)$_GET['ids'] : array($id);
$search = clean(@$_GET['search']);

$k = @$_SESSION[$tbl . '_k'] ? $_SESSION[$tbl . '_k'] : 50;
$p = @$_GET['p'] ? $_GET['p'] : 1;

$where = $search ? ' AND (' . getWhere('name') . ')' : '';
$sort = setSort('sort');

$sqlmain = "SELECT * FROM {$prx}{$tbl} G WHERE 1 {$where} ORDER BY {$sort}";

// -------------------СОХРАНЕНИЕ----------------------

switch ($action) {
    case 'red':
        foreach ($_POST as $key => $val)
            $$key = clean($val);

        $id = uniUpdate($id);
        update($tbl, "date_updated=NOW()", $id);

        upfile("../uploads/{$tbl}/{$id}.jpg", $_FILES['file'], "../uploads/{$tbl}/".@$_POST['del_file'], false, 1200);
        for ($i = 1; $i < 30; $i++)
            upfile("../uploads/{$tbl}/{$id}_{$i}.jpg", $_FILES['file' . $i], "../uploads/{$tbl}/".@$_POST['del_file'. $i], false, 1200);

        $p = getPage($sqlmain, $id, $k);

        if (isset($_POST['apply'])) {
            ?>
            <script>top.$('#frmRed')[0].action += '&id=<?=$id?>';
                top.topReload(true);
            </script><?
        } else {
            ?>
            <script>top.location.href = "?id=<?=$id?>&p=<?=$p?>&rand=<?=mt_rand()?>";</script><?
        }
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
        'action' => $action
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
        ?>
        <form action="?id=<?= $id ?>&action=red" method="post" id="frmRed" enctype="multipart/form-data" target="iframe">
            <input type="hidden" name="use_date" value="1"/>
            <table class="red" width="900">
                <tr>
                    <th>Название</th>
                    <td><input name="name" value='<?= $row['name'] ?>'></td>
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
                        {
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
                    <th>Скрыть</th>
                    <td><input name="hide" type="checkbox" <?= $row['hide'] ? 'checked' : '' ?> style="width:auto;"
                               value="1"></td>
                </tr>
                <tr>
                    <td align="center" colspan="2"><?= btnAction('Save,Apply,Cancel') ?></td>
                </tr>
            </table>
        </form>
        <? break;

    default:    // просмотр
        ?>
        <?= lnkAction('Add,Dels,Copy', "&id_catalog={$id_catalog}&id_makers={$id_makers}") ?>

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
                <th sort="<?= getSort('hide') ?>">Скрыть
                    <div align="center"><input type="checkbox"
                                               onClick="event.cancelBubble=true; if(sure()) { setCbTable(this,true); } else  return false;">
                    </div>
                </th>
                <th></th>
            </tr>
            <?
            $res = sql($sqlmain . ' LIMIT ' . ($p - 1) * $k . ', ' . $k);
            while ($row = mysql_fetch_assoc($res)) {
                $id = $row['id'];
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
                            <?
                        }
                        ?>
                    </td>
                    <td>
                        <div class="redone" id="<?= $id ?>" name="name"><?= $row['name'] ?></div>
                    </td>
                    <td align="center"><input type="checkbox" <?= ($row['hide'] ? 'checked' : '') ?>
                                              onClick="toAjax('?action=redone&id=<?= $id ?>&field=hide&value='+(this.checked ? 1 : 0))">
                    </td>
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