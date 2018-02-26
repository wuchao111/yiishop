<?php
// 使用表单组件配合表单模型创建表单
$form = \yii\bootstrap\ActiveForm::begin();
// 名称
echo $form->field($model,'name')->textInput();
// 简介
echo $form->field($model,'intro')->textarea();
// 排序
echo $form->field($model,'sort')->textInput(['type'=>'number']);
// 状态
echo $form->field($model,'is_deleted',['inline'=>1])->radioList([
    0=>'正常',1=>'删除'
]);
echo '<button type="submit" class="btn btn-primary">提交</button>';
\yii\bootstrap\ActiveForm::end(); //</form>