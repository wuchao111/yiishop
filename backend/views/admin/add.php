<?php
$form = \yii\bootstrap\ActiveForm::begin();
// 用户名
echo $form->field($model,'username')->textInput();
// 密码
echo $form->field($model,'password_hash')->passwordInput( );
// 邮箱
echo $form->field($model,'email')->textInput(['type'=>'email']);

    echo '<button type="submit" class="btn btn-primary">确认添加</button>';
\yii\bootstrap\ActiveForm::end();