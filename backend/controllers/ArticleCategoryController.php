<?php

namespace backend\controllers;

use app\models\ArticleCategory;

class ArticleCategoryController extends \yii\web\Controller
{
    // 文章列表
    public function actionIndex()
    {
        $article = ArticleCategory::find()->all();
        return $this->render('index',['articles'=>$article]);
    }
    // 添加文章
    public function actionAdd()
    {
        $request = \Yii::$app->request;
        $model = new ArticleCategory();
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                $model->save();
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect(['article-category/index']);
            } else {
                var_dump($model->getErrors());
                die;
            }
        }
        return $this->render('add', ['model' => $model]);

    }
    // 修改
    public function actionEdit($id){
        $request = \Yii::$app->request;
        $model = ArticleCategory::findOne(['id'=>$id]);
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $model->save();
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect(['article-category/index']);
            }
        }
        return $this->render('add', ['model' => $model]);
    }
    // 删除
    public function actionDelete($id){
        $request = \Yii::$app->request;
        $model = ArticleCategory::findOne(['id'=>$id]);
        if($model->validate()){
            if($model->is_deleted == 0){
                \Yii::$app->session->setFlash('success', '正常状态下不能删除,防止误删');
                return $this->redirect(['article-category/index']);die;
            }else{
                $model->delete();
                \Yii::$app->session->setFlash('success', '删除成功');
                return $this->redirect(['article-category/index']);
            }
        }
        return $this->render('add', ['model' => $model]);
    }
}
