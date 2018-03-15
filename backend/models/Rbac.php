<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/7
 * Time: 16:15
 */

namespace backend\models;


use yii\base\Model;

class Rbac extends Model
{
    public $name;
    public $description;
    const SCENARIO_ADD =  'add';
    const SCENARIO_EDIT =  'edit';

    public function rules(){
        return [
            [['name','description'],'required'],
            ['name','vatimeName','on'=>self::SCENARIO_ADD],
            ['name','changName','on'=>self::SCENARIO_EDIT],
        ];
    }
    public function vatimeName()
    {
        $authmanager = \Yii::$app->authManager;
        // 获取权限
        if($authmanager->getPermission($this->name)){
            // 权限存在
            $this->addError('name','权限已存在');
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
            'name'=>'名称(路由)',
            'description'=>'描述',
        ];
    }
    public static function allRbac(){
        $authmanager = \Yii::$app->authManager;
        $rbac = $authmanager->getPermissions();
        $arr=[];
        foreach ($rbac as $v ){
            $arr[$v->name]=$v->name;
        }
        return $arr;
    }

}