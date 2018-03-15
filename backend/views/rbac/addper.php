<?php
$form = \yii\widgets\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'description')->textInput();
echo '<button type="submit" class="btn btn-primary">确认添加</button>';
\yii\widgets\ActiveForm::end();
