<?php
$form = \yii\bootstrap\ActiveForm::begin();
// 用户名
echo $form->field($model,'username')->textInput();

// 邮箱
echo $form->field($model,'email')->textInput(['type'=>'email']);

    echo '<button type="submit" class="btn btn-warning">确认更新</button>';

\yii\bootstrap\ActiveForm::end();