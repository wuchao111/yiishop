<?php
// 使用表单组件配合表单模型创建表单
$form = \yii\bootstrap\ActiveForm::begin();
// 名称
echo $form->field($model,'name')->textInput();
// 商品分类
echo $form->field($model,'goods_category_id')->hiddenInput();
// ===================== ztree ===============
$this->registerCssFile('@web/head/css/zTreeStyle/zTreeStyle.css');
$this->registerJsFile('@web/head/js/jquery.ztree.core.js',[
    'depends'=>\yii\web\JqueryAsset::className()
]);

// Js  代码
$this->registerJs(
    <<<JS
var zTreeObj;
        // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
        var setting = {
            data: {
                simpleData: {
                    enable: true,
                    idKey: "id",
                    pIdKey: "parent_id",
                    rootPId: 0
                }
            },
            callback: {
                // 点击节点被回调函数
		        onClick:function(event, treeId, treeNod) {
		            // alert(treeNode.tId + "parent_id " + treeNode.name);
		            // 将被点击节点的id写入parend_id 中
		            $('#goods-goods_category_id').val(treeNod.id);
		        }
	        }
        };
        // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
        var zNodes ={$nodes};

            zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
            // 展开全部节点
            // zTreeObj.expandAll(true);
            // 根据id 选中节点数据

            zTreeObj.selectNode(zTreeObj.getNodeByParam("id", "{$model->goods_category_id}", null));
JS

);
// html 代码
echo '<div>
    <ul id="treeDemo" class="ztree"></ul>
</div>';

// ==================== ztree =====================
// 图片
echo $form->field($model,'logo')->hiddenInput();
//echo $form->field($models,'content')->textarea();

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
    $("#goods-logo").val(imgUrl);
    //图片回显
    $("#logo_view").attr('src',imgUrl);
    $("#logo_view").attr('width','120px');
    //$( '#'+file.id ).addClass('upload-state-done');
});
JS
);
echo '<img class="img-circle" width="50px" height="50px" alt="" id="logo_view" src="'.$model->logo.'"/>';
echo '<br>';
// 品牌分类
echo $form->field($model,"brand_id")->dropDownList(backend\models\Brand::allBrand());
// 市场价格
echo $form->field($model,'market_price')->textInput(['type'=>'number']);
// 商品价格
echo $form->field($model,'shop_price')->textInput(['type'=>'number']);
//库存
echo $form->field($model,'stock')->textInput(['type'=>'number']);
// 排序
echo $form->field($model,'sort')->textInput(['type'=>'number']);
// 浏览次数
echo $form->field($model,'view_times')->textInput();


//========图片
//
// 内容
echo $form->field($intro,'content')->widget('kucha\ueditor\UEditor',[]);



if($model->getIsNewRecord()){
    echo '<button type="submit" class="btn btn-primary">确认添加</button>';
}else{
    echo '<button type="submit" class="btn btn-warning">确认更新</button>';
}
\yii\bootstrap\ActiveForm::end(); //</form>