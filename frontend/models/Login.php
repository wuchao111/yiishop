<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/9
 * Time: 15:10
 */

namespace frontend\models;


use yii\base\Model;

class Login extends Model
{

    public $name;
    public $password_hash;
    public $memme; // 记住我
    public function attributeLabels()
    {
        return [
            'name'=>'用户名',
            'password_hash'=>'密码',
            'memme'=>'记住我'
        ];
    }
    public function rules()
    {
        return [
            [['name','password_hash'],'required'],
            ['memme','safe'],
        ];
    }
    // 登录方法
    public function login(){
        $member = Member::findOne(['name'=>$this->name]);
        if($member){
            // 账号存在
            if(\Yii::$app->security->validatePassword($this->password_hash,$member->password_hash)){
                // 最后登录时间
                $member->last_login_time = time();
                // 最后登录IP
                $member->last_login_ip = ip2long(\Yii::$app->request->userIP);
                $member->save(0);
//                    var_dump($admin->getErrors());die;
                //自动登录  保存时间
                $duration = $this->memme?1*24*3600:0;
                return \Yii::$app->user->login($member,$duration);
            }else{
                $this->addError('password_hash','密码错误');
            }
        }else{
            // 账号不存在
            $this->addError('name','账号不存在');
        }
        return false;
    }
}