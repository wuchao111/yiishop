<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\Menu;

class MenuController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $menu = Menu::find()->where(['parent_id'=>0])->all();
        return $this->render('index', ['menus' => $menu]);
    }
    public function actionAdd(){
        $request = \Yii::$app->request;
        $model = new Menu();
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){

                $model->save();
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect(['menu/index']);
            }
        }
        return $this->render('add', ['model' => $model]);
    }
    public function actionEdit($id){
        $request = \Yii::$app->request;
        $model = Menu::findOne(['id'=>$id]);
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $model->save();
                \Yii::$app->session->setFlash('success', '修改成功');
                return $this->redirect(['menu/index']);
            }
        }
        return $this->render('add', ['model' => $model]);
    }
    public function actionDelete($id){
//        var_dump($id);exit;
        $model = Menu::findOne(['id'=>$id]);
        $model->delete();
        \Yii::$app->session->setFlash('success', '删除成功');
        return $this->redirect(['menu/index']);
    }
//过滤器
    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::class,
                //默认情况对所有操作生效
                //排除不需要授权的操作
                'except'=>['index']
            ]
        ];
    }

}
