<?php

namespace backend\controllers;

use backend\models\GoodsCategory;
use Codeception\Module\Yii2;
use yii\data\Pagination;

class GoodsCategoryController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $query = GoodsCategory::find();
        $pager = new Pagination();
        $pager->totalCount = $query->count();
        $pager->defaultPageSize = 3;
        $good = $query->offset($pager->offset)->limit($pager->limit)->all();
        return $this->render('index',['goods'=>$good,'pager'=>$pager]);
    }

    public function actionAdd()
    {
        $request = \Yii::$app->request;
        $model = new GoodsCategory();
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                // 添加 根节点  子节点
                if($model->parent_id){
                    // 子节点
                    // 根据子节点id找到他的父id
                    $parent = GoodsCategory::findOne(['id'=>$model->parent_id]);
                    $model->prependTo($parent);
                }else{
                    // 根节点
                    $model->makeRoot();
                }
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect(['goods-category/index']);
            }
        }
        // ['id'=>0,'parent_id'=>0,'name'=>顶级分类]
        $nodes = GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        $nodes[] = ['id'=>0,'parent_id'=>0,'name'=>'顶级分类'];
        return $this->render('add', ['model' => $model,'nodes'=>json_encode($nodes)]);
    }
    public function actionEdit($id){
        $request = \Yii::$app->request;
        $model = GoodsCategory::findOne(['id'=>$id]);
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                if($model->parent_id){
                    $parent = GoodsCategory::findOne(['id'=>$model->parent_id]);
                    $model->prependTo($parent);
                }else{
                    $model->makeRoot();
                }
                \Yii::$app->session->setFlash('success', '修改成功');
                return $this->redirect(['goods-category/index']);
            }
        }
        $nodes = GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        $nodes[] = ['id'=>0,'parent_id'=>0,'name'=>'顶级分类'];
        return $this->render('add', ['model' => $model,'nodes'=>json_encode($nodes)]);
    }
    public function actionDelete($id){
         $model = GoodsCategory::findOne(['id'=>$id]);
            $model->deleteWithChildren();
            \Yii::$app->session->setFlash('success', '删除成功');
            return $this->redirect(['goods-category/index']);
    }
    // 测试
    public function actionTest(){
        // 添加根节点
//        $countries = new GoodsCategory();
//        $countries->name = '家用电器';
//        $countries->parent_id = '0';
//        $countries->makeRoot();
        // 添加子节点
//        $countries = GoodsCategory::findOne(['id'=>1]);
//        $russia = new GoodsCategory();
//        $russia->name = '电视';
//        $russia->parent_id = $countries->id;
//
//        $russia->prependTo($countries);
//        echo '添加成功';
    }
    //测试
    public function actionZtree(){
        $nodes = GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
//        var_dump($nodes);die;
        return $this->renderPartial('ztree',['nodes'=>$nodes]);
    }
}
