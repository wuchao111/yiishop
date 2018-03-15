<?php

namespace frontend\controllers;

use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use frontend\models\Login;
use frontend\models\Member;
use yii\captcha\CaptchaAction;

class MemberController extends \yii\web\Controller
{
    // 首页
    public function actionIndex()
    {
        $goods = GoodsCategory::find()->where(['parent_id'=>0])->all();
//        var_dump($goods);die;
        $goodss = Goods::find()->where(['<','id',6])->all();
        return $this->render('index',['goods'=>$goods,'goodss'=>$goodss]);
    }
    //  商品详情
    public function actionShow($id){
        $good = Goods::findOne(['id'=>$id]);
//        var_dump($good);die;
        $path = GoodsGallery::find()->where(['goods_id'=>$id])->all();
//        var_dump($path);die;
        return $this->render('shows',['goods'=>$good,'paths'=>$path]);
    }
    // 商品列表
    public function actionGoods($id){
        $cate = GoodsCategory::findOne(['id'=>$id]);
        //处理分类不存在的情况
        switch ($cate->depth){
            case 0://1级分类
            case 1://2级分类
                $ids = $cate->children()->select(['id'])->andWhere(['depth'=>2])->asArray()->column();
                break;
            case 2://3级分类
                $ids = [$id];
                break;
        }
        $goods = Goods::find()->where(['in','goods_category_id',$ids])->all();

//        var_dump($goods);die;
        return $this->render('list',['goodes'=>$goods]);
    }
    // 注册
    public function actionRegister(){
        $request = \Yii::$app->request;
        $model = new Member();
        if($request->isPost){
            $model->load($request->post(),'');
            if($model->validate()){
                $model->save(0);
                return $this->redirect(['member/login']);
            }else{
                var_dump($model->getErrors());
            }
        }
        return $this->render('register');
    }
    // 验证用户名是否存在
    public function actionValidate(){
        $request = \Yii::$app->request;
        $model = new Member();
        if($model->name == $request->post('name')){
            return "true";
        }else{
            return "false";
        }
    }
    //接收短信  用redis保存
    public function actionValidateSms($tel,$code){
        // 开启redis
        $redis = new \Redis();
        $redis->connect('127.0.0.1');
        $c = $redis->get('code_'.$tel);
//        var_dump($c);
        if($c == $code){
            return 'true';
        }
        return 'false';
    }
    // 发送短信
    public function actionSms(){
        $request = \Yii::$app->request;
        $tel = $request->get('tel');
        $code = rand(100000,999999);
        $redis = new \Redis();
        $redis->connect('127.0.0.1');
        $redis->set('code_'.$tel,$code,5*60);

        $r = \Yii::$app->sms->setTel($tel)
            ->setParams(['code'=>$code])
            ->send();
        if($r){
            return 'success';
        }
        return 'fail';
    }
    // 验证用户,
    public function actionValidateTel(){
        $request = \Yii::$app->request;
        if($request->isGet){
            $tel = Member::findOne(['tel'=>$request->get('tel')]);
            if($tel){}
            return "false";
        }else{
            return "true";
        }
    }
    // 登录
    public function actionLogin(){
        $model = new Login();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post(),'');
            if($model->validate()){
                if($model->login()){
                    \Yii::$app->session->setFlash('success','登录成功');
                    return $this->redirect(['member/index']);
                }
            }else{
                var_dump($model->getErrors());
            }
        }
        return $this->render('login');
    }
    // 退出
    public function actionLogout(){
        \Yii::$app->user->logout();
        return $this->redirect(['member/login']);
    }
    // 收货地址
    public function actionAddress(){

        return $this->render('address');
    }
}
