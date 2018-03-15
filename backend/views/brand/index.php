<table class="table">
    <tr>
        <th>图片</th>
        <th>名称</th>
        <th>简介</th>
        <th>排序</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    <?php foreach ($brands as $brand):?>
        <tr>
            <td><img src="<?=$brand->logo?>" class="img-circle" width="50px" alt=""></td>
            <td><?=$brand->name?></td>
            <td><?=$brand->intro?></td>
            <td><?=$brand->sort?></td>
            <td><?=$brand->is_deleted == 0 ? '正常' : '删除'?></td>
            <td>
            <?php if (Yii::$app->user->can('brand/edit')):?>
                <a href="<?=\yii\helpers\Url::to(['brand/edit','id'=>$brand->id])?>" class="btn btn-warning">修改</a>
            <?php endif;?>
            <?php if (Yii::$app->user->can('brand/edit')):?>
                <a href="<?=\yii\helpers\Url::to(['brand/delete','id'=>$brand->id])?>" class="btn btn-danger">删除</a>
            <?php endif;?>
            </td>
        </tr>
    <?php endforeach;?>
</table>

<?php
//分页工具条
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$pager,

]);