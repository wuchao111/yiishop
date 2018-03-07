<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username')->textInput(['readonly'=>'readonly']);
echo $form->field($model,'old_password')->passwordInput( );
echo $form->field($model,'new_password')->passwordInput( );
echo $form->field($model,'news_password')->passwordInput( );

echo '<button type="submit" class="btn btn-primary">确认修改</button>';
\yii\bootstrap\ActiveForm::end();