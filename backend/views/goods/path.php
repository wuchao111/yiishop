<?php
// 使用表单组件配合表单模型创建表单
$form = \yii\bootstrap\ActiveForm::begin();


// ==================== ztree =====================
// 图片
echo $form->field($model,'path')->hiddenInput()->label(false);
//                                                                                 默认值
//echo $form->field($model,'goods_id')->textInput(['value'=>$id])->label(false);

$this->registerCssFile('@web/webuploader/webuploader.css');

$this->registerJsFile('@web/webuploader/webuploader.js',[
    //当前js文件依赖jquery(在jquery后面加载)
    'depends'=>\yii\web\JqueryAsset::className()
]);

// ============================ webuploader =======================
echo <<<HTML
<!--dom结构部分-->
<div id="uploader-demo">
    <!--用来存放item-->
    <div id="fileList" class="uploader-list"></div>
    <div id="filePicker">选择图片</div>
</div>
<img id="logo_view" src="" />
HTML;

$logo_upload = \yii\helpers\Url::to(['goods/logo-upload']);
$this->registerJs(
    <<<JS
// 初始化Web Uploader
var uploader = WebUploader.create({

    // 选完文件后，是否自动上传。
    auto: true,

    // swf文件路径
    swf: '/webuploader/Uploader.swf',

    // 文件接收服务端。
    server: '{$logo_upload}',

    // 选择文件的按钮。可选。
    // 内部根据当前运行是创建，可能是input元素，也可能是flash.
    pick: '#filePicker',

    // 只允许选择图片文件。
    accept: {
        title: 'Images',
        extensions: 'gif,jpg,jpeg,bmp,png',
        // mimeTypes: 'image/*'
        // 解决图片上传慢
        mimeTypes: 'image/gif,image/jpeg,image/jpg,image/png'
    }
    });
    //图片上传成功
uploader.on( 'uploadSuccess', function( file,response ) {
    var imgUrl = response.url;
    console.log(imgUrl);
    //将上传成功的文件的路径赋值给logo字段
    $("#goodsgallery-path").val(imgUrl);
    //图片回显
    $("#logo_view").attr('src',imgUrl);
    $("#logo_view").attr('width','120px');
    $( '#'+file.id ).addClass('upload-state-done');
    
});
JS
);
    echo '<button type="submit" class="btn btn-primary">确认添加</button>';
\yii\bootstrap\ActiveForm::end();
?>




<table class="table">

<?php foreach($goods as $good):?>
<tr>
    <td><img src="<?=$good->path?>" width="100px" height="100px"> </td>
    <td><a href="<?=\yii\helpers\Url::to(['goods/off','id'=>$good->id,'goods_id'=>$id])?>" class="btn btn-danger">删除</a></td>
</tr>
<?php endforeach; ?>
</table>


