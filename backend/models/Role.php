<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/7
 * Time: 19:54
 */

namespace backend\models;


use yii\base\Model;

class Role extends  Model
{
    public $name;
    public $description;
    public $permission;

    const SCENARIO_ADD =  'add';
    const SCENARIO_EDIT =  'edit';
    public function rules(){
        return [
            [['name','description'],'required'],
            ['name','vatimeName','on'=>self::SCENARIO_ADD],
            ['name','changName','on'=>self::SCENARIO_EDIT],
            ['permission','safe'],
        ];
    }
    public function vatimeName()
{
    $authmanager = \Yii::$app->authManager;
    // 获取角色
    if($authmanager->getPermission($this->name)){
        // 角色存在
        $this->addError('name','角色已存在');
    };

}
    public function ChangName(){
        //如果修改了name,需要验证name是否存在
        if(\Yii::$app->request->get('name') != $this->name){
            $this->vatimeName();
        }
        //旧name

        //$this->name;//修改后的name
        //如果没有修改name,则不验证name
    }
    public function attributeLabels(){
        return [
            'name'=>'角色',
            'description'=>'描述',
            'permission'=>'权限',
        ];
    }
}