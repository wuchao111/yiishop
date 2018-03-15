<table class="table">
    <tr>
        <th>名称</th>
        <th>简介</th>
        <th>文章分类ID</th>
        <th>排序</th>
        <th>状态</th>
        <th>创建时间</th>
        <th>操作</th>
    </tr>
    <?php foreach ($articles as $article):?>
        <tr delete-id=<?= $article->id ?>>
            <td><?=$article->name?></td>
            <td><?=$article->intro?></td>
            <td><?=$article->brank['name']?></td>
            <td><?=$article->sort?></td>
            <td><?=$article->is_deleted == 0 ? '正常' : '删除'?></td>
            <td><?=date('Y-m-d H:i:s',$article->create_time)?></td>
            <td>
            <?php if (Yii::$app->user->can('article/edit')):?>
                <a href="<?=\yii\helpers\Url::to(['article/edit','id'=>$article->id])?>" class="btn btn-warning">修改</a>
            <?php endif;?>
                <?php if (Yii::$app->user->can('article/delete')):?>
                <a href="#" class="btn btn-danger delete">删除</a>
                <?php endif;?>
                <a href="<?=\yii\helpers\Url::to(['article/show','id'=>$article->id])?>" class="btn btn-success">查看内容</a>
            </td>
        </tr>
    <?php endforeach;?>
</table>

<?php
//分页工具条
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$pager,

]);

$this->registerJs(
    <<<JS
$('.delete').click(function() {
    var tr = $(this).closest("tr");
                var id=tr.attr('delete-id');
layer.confirm('你确定要删除？', {

                btn: ['确定','取消']
            }, function(){
                layer.msg('删除成功', {icon: 1});
                
                
                var data={
                    'id':id
                    };
    $.get("delete",data,function (data) {
                if(data){
                    tr.remove();
                }
        });
            }, function(){
                layer.msg('取消成功', {
                    time: 20000,
                    btn: ['明白了', '知道了']
                });
            });
})
JS

);

