<?php

namespace backend\controllers;

use backend\models\Brand;
use yii\web\UploadedFile;

class BrandController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;
    // 品牌列表
    public function actionIndex()
    {
        $brand = Brand::find()->where(['is_deleted'=>0])->all();
        return $this->render('index',['brands'=>$brand]);
    }
    // 添加品牌
    public function actionAdd(){
        $request = \Yii::$app->request;
        $model = new Brand();
        if($request->isPost){
//            var_dump($_POST);die;
            $model->load($request->post());
            $model->is_deleted = 0;
            if($model->validate()){
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
            if ($model->validate()) {
                $model->save();
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect(['brand/index']);
            }
        }
        return $this->render('add', ['model' => $model]);
    }
    //删除
    public function actionDelete($id){
        $model = Brand::findOne(['id'=>$id]);
        $model->is_deleted=1;
        $model->save();
        return $this->redirect(['brand/index']);
    }
    // 图片上传
    public function actionLogoUpload(){
        // 实例化上传文件类
        $uploadFile = UploadedFile::getInstanceByName('file');
        $fileName = '/upload/brand/'.uniqid().$uploadFile->extension;
        $result = $uploadFile->saveAs(\Yii::getAlias('@webroot').$fileName);
        if($result){
            return json_encode([
                'url'=>$fileName
            ]);
        }
    }
}
