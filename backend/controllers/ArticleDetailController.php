<?php

namespace backend\controllers;

use backend\models\ArticleDetail;

class ArticleDetailController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $article  = ArticleDetail::find()->all();
        return $this->render('index',['articles'=>$article]);
    }
}
