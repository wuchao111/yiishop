<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mode".
 *
 * @property int $id
 * @property int $delivery_id 配送方式id
 * @property string $delivery_name 配送方式名称
 * @property double $delivery_price 配送方式价格
 */
class Mode extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mode';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['delivery_id', 'delivery_name', 'delivery_price'], 'required'],
            [['delivery_id'], 'integer'],
            [['delivery_price'], 'number'],
            [['delivery_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'delivery_id' => '配送方式id',
            'delivery_name' => '配送方式名称',
            'delivery_price' => '配送方式价格',
        ];
    }
}
