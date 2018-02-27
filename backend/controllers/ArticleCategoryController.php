<?php

namespace backend\controllers;

use backend\models\ArticleCategory;

class ArticleCategoryController extends \yii\web\Controller
{
    // 文章列表
    public function actionIndex()
    {
        $article = ArticleCategory::find()->where(['is_deleted'=>0])->all();
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

        $model = ArticleCategory::findOne(['id'=>$id]);
        $model->is_deleted=1;
        $model->save();
        return $this->redirect(['article-category/index']);
    }
}
