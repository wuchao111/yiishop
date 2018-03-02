<?php
/**
 * @var $this \yii\web\View
 */
// 使用表单组件配合表单模型创建表单
$form = \yii\bootstrap\ActiveForm::begin();
// 名称
echo $form->field($model,'name')->textInput();
// 父ID
echo $form->field($model,'parent_id')->hiddenInput();
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
		            $('#goodscategory-parent_id').val(treeNod.id);
		        }
	        }
        };
        // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
        var zNodes ={$nodes};
      
            zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
            // 展开全部节点
            // zTreeObj.expandAll(true);
            // 根据id 选中节点数据
           
            zTreeObj.selectNode(zTreeObj.getNodeByParam("id", "{$model->parent_id}", null));
JS

);
// html 代码
echo '<div>
    <ul id="treeDemo" class="ztree"></ul>
</div>';
// ==================== ztree =====================
// 简介
echo $form->field($model,'intro')->textarea();
if($model->getIsNewRecord()){
    echo '<button type="submit" class="btn btn-primary">确认添加</button>';
}else{
    echo '<button type="submit" class="btn btn-warning">确认更新</button>';
}
\yii\bootstrap\ActiveForm::end(); //</form>