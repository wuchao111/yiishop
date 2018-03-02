<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "goods".
 *
 * @property int $id
 * @property string $name 货号
 * @property string $logo 图片
 * @property int $goods_category_id 商品分类id
 * @property int $brand_id 品牌分类
 * @property string $market_price 市场价格
 * @property string $shop_price 商品价格
 * @property int $stock 库存
 * @property int $is_on_sale 是否在售
 * @property int $status 状态
 * @property int $create_time 添加时间
 * @property int $sort 排序
 * @property int $view_times 浏览次数
 */
class Goods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'logo', 'goods_category_id', 'brand_id', 'market_price', 'shop_price', 'stock', 'is_on_sale', 'status', 'create_time'], 'required'],
            [['goods_category_id', 'brand_id', 'stock', 'is_on_sale', 'status', 'create_time', 'sort', 'view_times'], 'integer'],
            [['market_price', 'shop_price'], 'number'],
            [['name'], 'string', 'max' => 20],
            [['logo'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '货号',
            'logo' => '图片',
            'goods_category_id' => '商品分类id',
            'brand_id' => '品牌分类',
            'market_price' => '市场价格',
            'shop_price' => '商品价格',
            'stock' => '库存',
            'is_on_sale' => '是否在售',
            'status' => '状态',
            'create_time' => '添加时间',
            'sort' => '排序',
            'view_times' => '浏览次数',
        ];
    }
}
