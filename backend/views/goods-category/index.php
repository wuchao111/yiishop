<table class="table">
    <tr>
        <th>名称</th>
        <th>上级分类</th>
        <th>简介</th>
        <th>操作</th>
    </tr>
    <?php foreach ($goods as $good):?>
        <tr>
            <td><?=$good->name?></td>
            <td><?=$good->parent_id?></td>
            <td><?=$good->intro?></td>

            <td>
                <a href="<?=\yii\helpers\Url::to(['goods-category/edit','id'=>$good->id])?>" class="btn btn-warning">修改</a>
                <a href="<?=\yii\helpers\Url::to(['goods-category/delete','id'=>$good->id])?>" class="btn btn-danger">删除</a>
            </td>
        </tr>
    <?php endforeach;?>
</table>


<?php
//分页工具条
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$pager,

]);

