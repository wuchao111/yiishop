<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "payment".
 *
 * @property int $id
 * @property int $payment_id 支付方式id
 * @property string $payment_name 支付方式名称
 * @property string $total 订单金额
 */
class Payment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['payment_id', 'payment_name', 'total'], 'required'],
            [['payment_id'], 'integer'],
            [['total'], 'number'],
            [['payment_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'payment_id' => '支付方式id',
            'payment_name' => '支付方式名称',
            'total' => '订单金额',
        ];
    }
}
