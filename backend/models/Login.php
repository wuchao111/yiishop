<?php

namespace backend\models;

use yii\base\Model;

class Login extends Model{
    public $username;
    public $password_hash;
    public $code; // 验证码
    public $memme; // 记住我


    public function attributeLabels()
    {
        return [
            'code'=>'验证码',
            'username'=>'用户名',
            'password_hash'=>'密码',
            'memme'=>'记住我'
        ];
    }

    public function rules()
    {
        return [
            [['username','password_hash'],'required'],
            ['code','captcha','captchaAction'=>'admin/captcha'],
            ['memme','safe'],
        ];
    }

    // 登录方法
    public function login(){
        $admin = Admin::findOne(['username'=>$this->username]);
            if($admin){
                // 账号存在
                if(\Yii::$app->security->validatePassword($this->password_hash,$admin->password_hash)){
                    // 最后登录时间
                    $admin->last_login_time = time();
                    // 最后登录IP
                    $admin->last_login_ip = ip2long(\Yii::$app->request->userIP);
                    $admin->save();
                    //自动登录  保存时间
                    $duration = $this->memme?1*24*3600:0;
                    return \Yii::$app->user->login($admin,$duration);
                }else{
                    $this->addError('password_hash','密码错误');
                }
            }else{
                // 账号不存在
                $this->addError('username','账号不存在');
            }


        return false;
    }
}