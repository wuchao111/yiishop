<?php
// 使用表单组件配合表单模型创建表单
$form = \yii\bootstrap\ActiveForm::begin();
// 名称
echo $form->field($model,'name')->textInput();
// 简介
echo $form->field($model,'intro')->textarea(['rows'=>'5']);
// 排序
echo $form->field($model,'sort')->textInput(['type'=>'number']);
// 文章分类ID
echo $form->field($model,"article_category_id")->dropDownList(backend\models\ArticleCategory::allArticleCategory());
// 名称
echo $form->field($content,'content')->widget('kucha\ueditor\UEditor',[]);
//echo $form->field($models,'content')->textarea();
if($model->getIsNewRecord()){
    echo '<button type="submit" class="btn btn-primary">确认添加</button>';
}else{
    echo '<button type="submit" class="btn btn-warning">确认更新</button>';
}
\yii\bootstrap\ActiveForm::end(); //</form>