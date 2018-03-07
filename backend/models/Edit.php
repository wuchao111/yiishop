<?php

namespace backend\models;

use yii\base\Model;

class Edit extends Model{

    public $username;
    public $old_password;// 旧密码
    public $new_password;// 新密码
    public $news_password;// 确认密码

    public function rules()
    {
      return [
          [['old_password','new_password','news_password'],'required'],
          // 自己制定验证规则
          //两次密码不一致
          ['news_password','vatamePassword'],
          // 旧密码不一样
          ['old_password','vatameOldPassword'],
          ];
    }

    public function vatameOldPassword(){
        $result = \Yii::$app->security->validatePassword($this->old_password,\Yii::$app->user->identity->password_hash);
//        $re = \Yii::$app->user->identity;
//        var_dump($re);exit;
        if($result == false){
            // 设置提示信息
            $this->addError('old_password','旧密码不正确,请重新输入');
        }
    }
    public function vatamePassword(){
        $result = $this->news_password == $this->new_password;
        if($result == false){
            $this->addError('news_password','两次密码不一致,请重新输入');
        }
    }

    public function attributeLabels()
    {
        return [
            'old_password'=>'旧密码',
            'new_password'=>'新密码',
            'news_password'=>'确认密码',
        ];
    }


}