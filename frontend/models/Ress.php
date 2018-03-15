<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ress".
 *
 * @property int $id
 * @property int $member_id 当前登录人
 * @property string $name 姓名
 * @property int $tel 电话
 * @property string $province 省
 * @property string $city 市
 * @property string $county 县
 * @property string $address 详细地址
 * @property int $status 状态
 */
class Ress extends \yii\db\ActiveRecord
{
    public $remember;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ress';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'name', 'tel', 'province', 'city', 'county'], 'required'],
            [['member_id', 'tel', 'status'], 'integer'],
            [['name'], 'string', 'max' => 5],
            [['province'], 'string', 'max' => 6],
            [['city', 'county'], 'string', 'max' => 10],
            [['address'], 'string', 'max' => 255],
            ['remember','safe'],
            ['address','safe'],
            ['member_id','safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => '当前登录人',
            'name' => '姓名',
            'tel' => '电话',
            'province' => '省',
            'city' => '市',
            'county' => '县',
            'address' => '详细地址',
            'status' => '状态',
        ];
    }
}
