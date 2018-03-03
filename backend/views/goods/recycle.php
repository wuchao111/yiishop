<table class="table">
    <tr>
        <th>图片</th>
        <th>名称</th>
        <th>货号</th>
        <th>商品分类</th>
        <th>品牌分类</th>
        <th>市场价格</th>
        <th>商品价格</th>
        <th>库存</th>
        <th>是否在售</th>
        <th>状态</th>
        <th>排序</th>
        <th>添加时间</th>
        <th>操作</th>
    </tr>
    <?php foreach ($goods as $good):?>
        <tr>
            <td><img src="<?=$good->logo?>" class="img-circle" width="50px" alt=""></td>
            <td><?=$good->name?></td>
            <td><?=$good->sn?></td>
            <td><?=$good->goods->name?></td>
            <td><?=$good->brand['name']?></td>
            <td><?=$good->market_price?></td>
            <td><?=$good->shop_price?></td>
            <td><?=$good->stock?></td>
            <td><?=$good->is_on_sale == 0 ? '在售' : '下架'?></td>
            <td><?=$good->status == 0 ? '正常' : '删除'?></td>
            <td><?=$good->sort?></td>
            <td><?=date('Y-m-d H:i:s',$good->create_time)?></td>
            <td>
                <a href="<?=\yii\helpers\Url::to(['goods/recovery','id'=>$good->id])?>" class="btn btn-success">恢复</a>
            </td>
        </tr>
    <?php endforeach;?>
</table>

<?php
//分页工具条
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$pager,

]);
