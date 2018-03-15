<div>
    <h1>商品列表</h1>
</div>
<form action="/goods/index" method="get" class="form-inline">
    <input type="text" name="name" class="form-control" placeholder="商品名" value="<?=$_GET['name']??''?>">
    <input type="text" name="sn" class="form-control" placeholder="货号" value="<?=$_GET['sn']??''?>">
    <input type="text" name="moneys" class="form-control" placeholder="￥" value="<?=$_GET['moneys']??''?>">
    <input type="text" name="money" class="form-control" placeholder="￥" value="<?=$_GET['money']??''?>">
    <button type="submit" class="btn btn-default">
        <span class="glyphicon glyphicon-search">搜索</span>
    </button>
</form>


<table class="table">
    <tr>
        <th>图片</th>
        <th>名称</th>
        <th>货号</th>
        <th>商品分类</th>
        <th>品牌分类</th>
        <th>价格</th>
        <th>库存</th>
        <th>是否在售</th>
        <th>状态</th>
        <th>添加时间</th>
        <th>操作</th>
    </tr>
    <?php foreach ($goods as $good):?>
        <tr>
            <td><img src="<?=$good->logo?>" class="img-circle" width="50px" height="50px" alt=""></td>
            <td><?=$good->name?></td>
            <td><?=$good->sn?></td>
            <td><?=$good->goods->name?></td>
            <td><?=$good->brand['name']?></td>
            <td><?=$good->market_price?></td>
            <td><?=$good->stock?></td>
            <td><?=$good->is_on_sale == 0 ? '在售' : '下架'?></td>
            <td><?=$good->status == 0 ? '正常' : '删除'?></td>
            <td><?=date('Y-m-d H:i:s',$good->create_time)?></td>
            <td>
                <a href="<?=\yii\helpers\Url::to(['goods/path','id'=>$good->id])?>" class="btn btn-default">
                    <span class="glyphicon glyphicon-picture">相册</span>
                </a>
                <?php if (Yii::$app->user->can('goods/edit')):?>
                <a href="<?=\yii\helpers\Url::to(['goods/edit','id'=>$good->id])?>" class="btn btn-warning">
                    <span class="glyphicon glyphicon-edit">修改</span>
                </a>
                <?php endif;?>
                <?php if (Yii::$app->user->can('goods/delete')):?>
                <a href="<?=\yii\helpers\Url::to(['goods/delete','id'=>$good->id])?>" class="btn btn-danger">
                    <span class="glyphicon glyphicon-trash">删除</span>
                </a>
                <?endif;?>

                <a href="<?=\yii\helpers\Url::to(['goods/show','id'=>$good->id])?>" class="btn btn-success">
                    <span class="glyphicon glyphicon-film">详情</span>
                </a>

            </td>
        </tr>
    <?php endforeach;?>
    <tr>
        <td colspan="14">
            <a href="<?=\yii\helpers\Url::to(['goods/recycle'])?>" class="btn btn-success">回收站</a>
        </td>
    </tr>

</table>

<?php
//分页工具条
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$pager,

]);
