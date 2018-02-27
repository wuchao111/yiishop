
<table class="table">
    <tr>
        <th>文章名称</th>
        <th>文章内容</th>

    </tr>
    <?php foreach ($articles as $article):?>
        <tr>
            <td><?=$article->article->name?></td>
            <td><?=$article->content?></td>
        </tr>
    <?php endforeach;?>
    <tr>
        <td colspan="3">
            <a href="<?=\yii\helpers\Url::to(['article/index'])?>" class="btn btn-warning">文章列表</a>
        </td>
    </tr>
</table>



