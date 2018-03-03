<?php

namespace backend\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "brand".
 *
 * @property integer $id
 * @property string $name
 * @property string $intro
 * @property string $logo
 * @property integer $sort
 * @property integer $is_deleted
 */
class Brand extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $imgFile;
    public static function tableName()
    {
        return 'brand';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','sort'], 'required'],
            [['intro'], 'string'],
            [['sort', 'is_deleted'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['logo'], 'string', 'max' => 255],
            ['imgFile','file','extensions' =>['png','jpg','gif']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '品牌id',
            'name' => '名称',
            'intro' => '简介',
            'logo' => '图片',
            'sort' => '排序',
            'is_deleted' => '状态',
        ];
    }

    public function getBrand(){
        return $this->hasOne(GoodsCategory::className(),['id'=>'brand_id']);
    }
    public static function allBrand(){
        $brand=self::find()->all();

        $arr=[];
        foreach ($brand as $v ){

            $arr[$v->id]=$v->name;

        }

        return $arr;
    }
}
