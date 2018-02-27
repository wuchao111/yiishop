<?php
/**
 * @var $this \yii\web\View
 */
// 使用表单组件配合表单模型创建表单
$form = \yii\bootstrap\ActiveForm::begin();
// 名称
echo $form->field($model,'name')->textInput();
// 简介
echo $form->field($model,'intro')->textarea();
// 排序
echo $form->field($model,'sort')->textInput(['type'=>'number']);
// 图片
echo $form->field($model,'logo')->hiddenInput();
// 引入css js 文件
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
HTML;

$logo_upload = \yii\helpers\Url::to(['brand/logo-upload']);
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
    $("#brand-logo").val(imgUrl);
    //图片回显
    $("#logo_view").attr('src',imgUrl);
    $("#logo_view").attr('width','120px');
    //$( '#'+file.id ).addClass('upload-state-done');
});
JS
 );
echo '<img id="logo_view" />';
echo '<br>';
//============================ webuploader =========================
if($model->getIsNewRecord()){
    echo '<button type="submit" class="btn btn-primary">确认添加</button>';
}else{
    echo '<button type="submit" class="btn btn-warning">确认更新</button>';
}
\yii\bootstrap\ActiveForm::end(); //</form>