<?php

namespace backend\controllers;

use app\models\Brand;
use yii\web\UploadedFile;

class BrandController extends \yii\web\Controller
{
    // 品牌列表
    public function actionIndex()
    {
        $brand = Brand::find()->all();
        return $this->render('index',['brands'=>$brand]);
    }
    // 添加品牌
    public function actionAdd(){
        $request = \Yii::$app->request;
        $model = new Brand();
        if($request->isPost){
//            var_dump($_POST);die;
            $model->load($request->post());
            $model->imgFile = UploadedFile::getInstance($model,'imgFile');
            $model->is_deleted = 0;
            if($model->validate()){

                if($model->imgFile){
                    $file = '/upload/brand/'.uniqid().$model->imgFile->extension;
                    if($model->imgFile->saveAs(\Yii::getAlias('@webroot').$file,0)){
                        $model->logo = $file;
                    }
                }
                $model->save();
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect(['brand/index']);
            }else{
                var_dump($model->getErrors());die;
            }
        }
        return $this->render('add', ['model' => $model]);
    }
    public function actionEdit($id){
        $request = \Yii::$app->request;
        $model = Brand::findOne(['id'=>$id]);
        if($request->isPost) {
            $model->load($request->post());
            $model->imgFile = UploadedFile::getInstance($model, 'imgFile');
            if ($model->validate()) {
                if ($model->imgFile) {
                    $file = '/upload/brand/' . uniqid() . $model->imgFile->extension;
                    if ($model->imgFile->saveAs(\Yii::getAlias('@webroot') . $file, 0)) {
                        $model->logo = $file;
                    }
                }
                $model->save();
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect(['brand/index']);
            }
        }
        return $this->render('add', ['model' => $model]);
    }
    //删除
    public function actionDelete($id){
        $request = \Yii::$app->request;
        $model = Brand::findOne(['id'=>$id]);
        if($model->validate()){
            if($model->is_deleted == 0){
                \Yii::$app->session->setFlash('success', '正常状态下不能删除,防止误删');
                return $this->redirect(['brand/index']);die;
            }else{
                $model->delete();
                \Yii::$app->session->setFlash('success', '删除成功');
                return $this->redirect(['brand/index']);
            }
        }
        return $this->render('add', ['model' => $model]);
    }
}
