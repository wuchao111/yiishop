
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
            <td><?=$admin->last_login_time?></td>
            <td><?=$admin->last_login_ip?></td>
            <td>
                <a href="<?=\yii\helpers\Url::to(['admin/recovery','id'=>$admin->id])?>" class="btn btn-warning">恢复</a>
            </td>
        </tr>
    <?php endforeach;?>

</table>

<?php
//分页工具条
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$pager,

]);