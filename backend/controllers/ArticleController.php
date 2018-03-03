<?php

namespace backend\controllers;

use backend\models\Article;
use backend\models\ArticleDetail;
use yii\data\Pagination;

class ArticleController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $query = Article::find()->where(['is_deleted' => 0]);
        $pager = new Pagination();
        $pager->totalCount = $query->count();
        $pager->defaultPageSize = 5;
        $article = $query->offset($pager->offset)->limit($pager->limit)->all();
        return $this->render('index',['articles'=>$article,'pager'=>$pager]);
    }
    public function actionAdd(){
        $request = \Yii::$app->request;
        $model = new Article();
        $content = new ArticleDetail();
        if($request->isPost){
            $model->load($request->post());
            $content->load($request->post());
            if($model->validate() && $content->validate()){
                $model->is_deleted = 0;
                $model->create_time = time();
                $model->save();
                $article_id = $model->attributes['id'];
                $content->article_id = $article_id;
                $content->save();
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect(['article/index']);
            }
        }
        return $this->render('add', ['model' => $model,'content'=>$content] );
    }
    public function actionEdit($id){
        $request = \Yii::$app->request;
        $model = Article::findOne(['id'=>$id]);
        $content = ArticleDetail::findOne(['article_id'=>$id]);
        if($request->isPost){
            $content->load($request->post());
            $model->load($request->post());
            if($model->validate() && $content->validate()){
                $model->save();
                $content->save();
                \Yii::$app->session->setFlash('success', '修改成功');
                return $this->redirect(['article/index']);
            }
        }
        return $this->render('add', ['model' => $model,'content'=>$content]);
    }
    public function actionDelete($id){
        $model = Article::findOne(['id'=>$id]);
        $model->is_deleted=1;
        $model->save();
        return $this->redirect(['article/index']);
    }
    public function actionShow($id){
        $model = Article::findOne(['id'=>$id]);
        $models = ArticleDetail::findOne(['article_id'=>$id]);
        return $this->render('show',['model'=>$model,'models'=>$models]);
    }
    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    "imageUrlPrefix"  => "http://www.yiishop.com",//图片访问路径前缀
            ]
                ]
        ];
    }
}
