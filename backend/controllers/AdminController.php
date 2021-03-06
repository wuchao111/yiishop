<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\Admin;
use backend\models\Edit;
use backend\models\Login;
use yii\captcha\CaptchaAction;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\Cookie;

class AdminController extends \yii\web\Controller
{

    // 登录
    public function actionLogin(){
        // 登录表单
        $model = new Login();
//        var_dump($model);die;
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                // 验证用户名和密码
                if($model->login()){
                    \Yii::$app->session->setFlash('success','登录成功');
                    return $this->redirect(['admin/index']);
                }
            }else{
                var_dump($model->getErrors());
            }
        }
        return $this->render('login',['model'=>$model]);
    }
    // 验证码
    public function actions(){
        return [
            'captcha'=>[
                'class'=>CaptchaAction::className(),
                'minLength'=>3,
                'maxLength'=>5,
            ]
        ];
    }
    // 用户列表
    public function actionIndex()
    {
        $query = Admin::find()->where(['status' => 0]);
        $pager = new Pagination();
        $pager->totalCount = $query->count();
        $pager->defaultPageSize = 5;
        $admin = $query->offset($pager->offset)->limit($pager->limit)->all();
        return $this->render('index',['admins'=>$admin,'pager'=>$pager]);
    }
    // 添加
    public function actionAdd(){
        $authmanager = \Yii::$app->authManager;
        $request = \Yii::$app->request;
        $model = new Admin();
//        var_dump($_POST);die;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){

//                var_dump($model->password_hash);die;
                $model->save();
                foreach ($model->role as $role){
                    $r= $authmanager->getRole($role);
                    $authmanager->assign($r,$model->id);
                }

                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect(['admin/index']);
            }else{
                var_dump($model->getErrors());die;
            }
        }else{
//            var_dump($model->getErrors());
        }

        $permissions = $authmanager->getRoles();
        $items = [];
        foreach ($permissions as $permission) {
            $items[$permission->name] = $permission->description;
        }
        return $this->render('add', ['model' => $model,'items' => $items]);
    }
    // 修改
    public function actionEdit($id){
        $request = \Yii::$app->request;
        $model = Admin::findOne(['id'=>$id]);
        if($request->isPost){
            $model->load($request->post());
            $model->updated_at = time();
            if($model->validate()){
                $model->save();
                \Yii::$app->session->setFlash('success', '修改成功');
                return $this->redirect(['admin/index']);
            }
       }
        return $this->render('edit', ['model' => $model]);
    }
    // 删除
    public function actionDelete($id){
//        var_dump($id);exit;
        $model = Admin::findOne(['id'=>$id]);
        $res = $model->delete();
        if ($res){
            return json_encode([
                'status'=>1
            ]);
        }else{
            return json_encode([
                'status'=>0
            ]);
        }


    }
    // 回收站
    public function actionShow(){
        $query = Admin::find()->where(['status' => 1 ]);
        $pager = new Pagination();
        $pager->totalCount = $query->count(); // 总条数
        $pager->defaultPageSize = 5; // 每页显示条数
        $admin = $query->offset($pager->offset)->limit($pager->limit)->all();
        return $this->render('show',['admins'=>$admin,'pager'=>$pager]);
    }
    // 恢复
    public function actionRecovery($id){
        $model = Admin::findOne(['id'=>$id]);
        $model->status = 0;
//        $model->old_password = 1;
//        $model->new_password = 1;
//        $model->news_password = 1;
        $model->save();
        \Yii::$app->session->setFlash('success', '恢复成功');
        return $this->redirect(['admin/index']);
    }
    // 注销
    public function actionLogout(){
        \Yii::$app->user->logout();
        \Yii::$app->session->setFlash('success','注销成功');
        return $this->redirect(['admin/login']);
    }
    // 修改密码
    public function actionEdits()
    {
        $user = \Yii::$app->user->identity;
        $model = new Edit();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
               $user->password_hash  = \Yii::$app->security->generatePasswordHash($model->news_password);
//               var_dump($user->password_hash);die;
               $user->save();
                //注销
                \Yii::$app->user->logout();
                \Yii::$app->session->setFlash('success','密码修改成功,请重新登录');
                return $this->redirect(['admin/login']);
            }
        }
        $model->username = $user->username;
        return $this->render('edits', ['model' => $model]);
    }
    //过滤器
    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::class,
                //默认情况对所有操作生效
                //排除不需要授权的操作
                'except'=>['login','logout','chpwd','captcha','index']
            ]
        ];
    }
}
