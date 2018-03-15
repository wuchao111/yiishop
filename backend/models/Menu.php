<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "menu".
 *
 * @property int $id
 * @property string $name 菜单名称
 * @property int $parent_id 上级菜单
 * @property int $url_id 地址
 * @property int $sort 排序
 */
class Menu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'parent_id', 'sort'], 'required'],
            [[ 'sort'], 'integer'],
            [['name'], 'string', 'max' => 20],
            ['url_id' ,'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '菜单名称',
            'parent_id' => '上级菜单',
            'url_id' => '地址',
            'sort' => '排序',
        ];
    }
    // 路由
    public function getMenu(){
        return $this->hasOne(Rbac::className(),['name'=>'url_id']);
    }
    // 菜单
    public static function getShow(){
        $names = self::find()->where(['parent_id'=>0])->all();
        $arr[] = '顶级分类';
        foreach ($names as $name){
            $arr[$name->id] = $name->name;
        }
        return $arr;
    }
    //获取菜单
    public static function getMenus($menuItems){
        $menus = self::find()->where(['parent_id'=>0])->all();
        foreach ($menus as $menu){
            $items = [];
            $children = self::find()->where(['parent_id'=>$menu->id])->all();
            foreach ($children as $child){
                //只添加有权限的二级菜单
                if(Yii::$app->user->can($child->url_id))
                    $items[] = ['label' => $child->name, 'url' => [$child->url_id]];
            }
            //只显示有子菜单的一级菜单
            if($items)
                $menuItems[] = ['label'=>$menu->name,'items'=>$items];
        }
        return $menuItems;
    }

}
