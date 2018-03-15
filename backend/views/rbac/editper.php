<?php
$form = \yii\widgets\ActiveForm::begin();
echo $form->field($model,'name')->textInput(['readonly'=>'readonly']);
echo $form->field($model,'description')->textInput();
echo '<button type="submit" class="btn btn-primary">确认修改</button>';
\yii\widgets\ActiveForm::end();
