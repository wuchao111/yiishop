<!--<input type="text" name="IP" readonly="readonly"  value="111"/>-->
<table class="table">
    <tr>
        <th>用户名</th>
        <th>邮箱</th>
        <th>状态</th>
        <th>创建时间</th>
        <th>更新时间</th>
        <th>最后登录时间</th>
        <th>最后登录ip</th>
        <th>操作</th>
    </tr>
    <?php foreach ($admins as $admin):?>
        <tr>
            <td><?=$admin->username?></td>
            <td><?=$admin->email?></td>
            <td><?=$admin->status == 0 ? '启用' : '禁用'?></td>
            <td><?=date('Y-m-d H:i:s',$admin->created_at)?></td>
            <td><?=date('Y-m-d H:i:s',$admin->updated_at)?></td>
            <td><?=date('Y-m-d H:i:s',$admin->last_login_time)?></td>
            <td><?=long2ip($admin->last_login_ip)?></td>
            <td>
                <a href="<?=\yii\helpers\Url::to(['admin/edit','id'=>$admin->id])?>" class="btn btn-warning">修改</a>
                <a href="<?=\yii\helpers\Url::to(['admin/delete','id'=>$admin->id])?>" class="btn btn-danger">删除</a>
            </td>
        </tr>
    <?php endforeach;?>
    <tr>
        <td colspan="9">
            <a href="<?=\yii\helpers\Url::to(['admin/add'])?>" class="btn btn-success">添加</a>
            <a href="<?=\yii\helpers\Url::to(['admin/show'])?>" class="btn btn-success">回收站</a>
        </td>
    </tr>

</table>



<?php
//分页工具条
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$pager,

]);
/**
 * @var $this \yii\web\View
 */

$this->registerJs(
        <<<JS
layer.confirm('你确定要删除？', {

btn: ['确定','取消']
}, function(){
layer.msg('删除成功', {icon: 1});
tr.remove();
}, function(){
layer.msg('取消成功', {
time: 20000,
btn: ['明白了', '知道了']
});
});
JS

);