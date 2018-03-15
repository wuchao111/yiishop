<?php

namespace frontend\controllers;

use app\models\Ress;
use yii\helpers\Url;

class RessController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;
    public function actionIndex()
    {
        $model = Ress::find()->where(['member_id'=>\Yii::$app->user->id])->all();
        return $this->render('index',['models'=>$model]);
    }
    public function actionAdd(){
        $request = \Yii::$app->request;
        $model = new Ress();
        if($request->isPost) {
            $model->member_id = \Yii::$app->user->id;
            $model->load($request->post(), '');
            if ($model->validate()) {
                if ($model->remember) {
                    $model->status=1;
                }else{
                    $model->status=0;
                }
                $model->save();
                return $this->redirect(['ress/index']);
            }
        }
        return $this->render('add');
    }
    public function actionDelete($id){
        $model = Ress::findOne(['id'=>$id]);
        $model->delete();
        return $this->redirect(['ress/index']);
    }
    public function actionEdit($id=''){
        $model = Ress::findOne(['id'=>$id]);
        $request = \Yii::$app->request;
        if($request->isPost){
            $model = Ress::findOne(['id'=>$request->post('id')]);
            $model->load($request->post(),'');
            if($model->validate()){
                if ($model->remember) {
                    $model->status=1;
                }else{
                    $model->status=0;
                }
                $model->save();
                return $this->redirect(['ress/index']);
            }
        }
        return $this->render('edit',['model'=>$model]);
    }
    public function actionStatues($id){
        $model = Ress::findOne(['id'=>$id]);
        $model->status = 1;
        $model->save();
        return $this->redirect(['ress/index']);
    }
}
