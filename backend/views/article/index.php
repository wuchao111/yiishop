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
        <tr>
            <td><?=$article->name?></td>
            <td><?=$article->intro?></td>
            <td><?=$article->brank['name']?></td>
            <td><?=$article->sort?></td>
            <td><?=$article->is_deleted == 0 ? '正常' : '删除'?></td>
            <td><?=date('Y-m-d H:i:s',$article->create_time)?></td>
            <td>
                <a href="<?=\yii\helpers\Url::to(['article/edit','id'=>$article->id])?>" class="btn btn-warning">修改</a>
                <a href="<?=\yii\helpers\Url::to(['article/delete','id'=>$article->id])?>" class="btn btn-danger">删除</a>
                <a href="<?=\yii\helpers\Url::to(['article/show','id'=>$article->id])?>" class="btn btn-success">查看内容</a>
            </td>
        </tr>
    <?php endforeach;?>
    <tr>
        <td colspan="7">
            <a href="<?=\yii\helpers\Url::to(['article/add'])?>" class="btn btn-success">添加</a>
        </td>
    </tr>

</table>

<?php
//分页工具条
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$pager,

]);

