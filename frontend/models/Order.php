<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "order".
 *
 * @property int $id
 * @property string $name 姓名
 * @property string $province 省
 * @property string $city 市
 * @property string $area 县
 * @property string $address 详细地址
 * @property string $tel 电话号码
 * @property int $delivery_id 配送方式id
 * @property string $delivery_name 配送方式名称
 * @property int $payment_id 支付方式id
 * @property string $payment_name 支付方式名称
 * @property string $total 订单金额
 * @property int $status 订单状态
 * @property string $trade_no 第三方支付交易号
 * @property int $create_time 创建时间
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'province', 'city', 'area', 'address', 'tel', 'delivery_id', 'delivery_name', 'payment_id', 'payment_name', 'total', 'status'], 'required'],
            [['delivery_id', 'payment_id', 'status', 'create_time'], 'integer'],
            [['total'], 'number'],
            [['name', 'delivery_name', 'payment_name'], 'string', 'max' => 50],
            [['province', 'city', 'area', 'tel'], 'string', 'max' => 20],
            [['address', 'trade_no'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '姓名',
            'province' => '省',
            'city' => '市',
            'area' => '县',
            'address' => '详细地址',
            'tel' => '电话号码',
            'delivery_id' => '配送方式id',
            'delivery_name' => '配送方式名称',
            'payment_id' => '支付方式id',
            'payment_name' => '支付方式名称',
            'total' => '订单金额',
            'status' => '订单状态',
            'trade_no' => '第三方支付交易号',
            'create_time' => '创建时间',
        ];
    }
}
