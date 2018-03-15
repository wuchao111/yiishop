<table class="display" id="table_id_example">
    <thead>
        <tr>
            <th>名称</th>
            <th>路由</th>
            <th>排序</th>
            <th>操作</th>
        </tr>
    </thead>
        <?php foreach ($menus as $menu):?>
    <tr>
        <td><?=$menu->name?></td>
        <td><?=$menu->url_id?></td>
        <td><?=$menu->sort?></td>
            <?php $menu_child = \backend\models\Menu::find()->where(['parent_id'=>$menu->id])->all()?>
            <?php if (is_array($menu_child)):foreach ($menu_child as $child):?>
                <tr >
                    <td>------<?=$child->name?></td>
                    <td><?=$child->url_id?></td>
                    <td><?=$menu->sort?></td>
                    <td>
                        <?php if (Yii::$app->user->can('menu/edit')):?>
                            <a href="<?=\yii\helpers\Url::to(['menu/edit','id'=>$child->id])?>" class="btn btn-warning">修改</a>
                        <?php endif;?>
                        <?php if (Yii::$app->user->can('menu/delete')):?>
                            <a href="<?=\yii\helpers\Url::to(['menu/delete','id'=>$child->id])?>" class="btn btn-danger">删除</a>
                        <?php endif;?>
            <?php endforeach;?>
            <?php endif;?>
            <td>
    <?php endforeach;?>

</table>

<?php
/**
 * @var $this yii\web\View
 */
$this->registerCssFile('@web/dataTables/media/css/jquery.dataTables.css');
$this->registerJsFile('@web/dataTables/media/js/jquery.dataTables.js',[
    'depends'=>\yii\web\JqueryAsset::className()]);
$this->registerJs(
    <<<JS
    $('.display').DataTable({
    language: {
        "sProcessing": "处理中...",
        "sLengthMenu": "显示 _MENU_ 项结果",
        "sZeroRecords": "没有匹配结果",
        "sInfo": "显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项",
        "sInfoEmpty": "显示第 0 至 0 项结果，共 0 项",
        "sInfoFiltered": "(由 _MAX_ 项结果过滤)",
        "sInfoPostFix": "",
        "sSearch": "搜索:",
        "sUrl": "",
        "sEmptyTable": "表中数据为空",
        "sLoadingRecords": "载入中...",
        "sInfoThousands": ",",
        "oPaginate": {
            "sFirst": "首页",
            "sPrevious": "上页",
            "sNext": "下页",
            "sLast": "末页"
        },
        "oAria": {
            "sSortAscending": ": 以升序排列此列",
            "sSortDescending": ": 以降序排列此列"
        }
    }
});
JS

);

