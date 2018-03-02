<table class="table">
    <tr>
        <th>名称</th>
        <th>内容</th>
        <th>排序</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    <?php foreach ($articles as $article):?>
        <tr>
            <td><?=$article->name?></td>
            <td><?=$article->intro?></td>
            <td><?=$article->sort?></td>
            <td><?=$article->is_deleted == 0 ? '正常' : '删除'?></td>
            <td>
                <a href="<?=\yii\helpers\Url::to(['article-category/edit','id'=>$article->id])?>" class="btn btn-warning">修改</a>
                <a href="<?=\yii\helpers\Url::to(['article-category/delete','id'=>$article->id])?>" class="btn btn-danger">删除</a>
            </td>
        </tr>
    <?php endforeach;?>
    <tr>
        <td colspan="7">
            <a href="<?=\yii\helpers\Url::to(['article-category/add'])?>" class="btn btn-success">添加</a>
        </td>
    </tr>

</table>

<?php
//分页工具条
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$pager,

]);
