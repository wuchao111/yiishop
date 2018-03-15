<?php
$form= \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'parent_id')->dropDownList(backend\models\Menu::getShow(),['prompt'=>'==请选择上级菜单==']);
echo $form->field($model,'url_id')->dropDownList(backend\models\Rbac::allRbac(),['prompt'=>'==请选择路由==']);
echo $form->field($model,'sort')->textInput(['type'=>'number']);
if($model->getIsNewRecord()){
    echo '<button type="submit" class="btn btn-primary">确认添加</button>';
}else{
    echo '<button type="submit" class="btn btn-warning">确认更新</button>';
}
\yii\bootstrap\ActiveForm::end();