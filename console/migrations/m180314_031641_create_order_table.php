<?php

use yii\db\Migration;

/**
 * Handles the creation of table `order`.
 */
class m180314_031641_create_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('order', [
            'id' => $this->primaryKey(),
//            member_id	int	用户id
            'name'=>$this->string(50)->notNull()->comment('姓名'),
            'province'=>$this->string(20)->notNull()->comment('省'),
            'city'=>$this->string(20)->notNull()->comment('市'),
            'area'=>$this->string(20)->notNull()->comment('县'),
            'address'=>$this->string()->notNull()->comment('详细地址'),
            'tel'=>$this->string(20)->notNull()->comment('电话号码'),
            'delivery_id'=>$this->integer()->notNull()->comment('配送方式id'),
            'delivery_name'=>$this->string(50)->notNull()->comment('配送方式名称'),
            'payment_id'=>$this->integer(50)->notNull()->comment('支付方式id'),
            'payment_name'=>$this->string(50)->notNull()->comment('支付方式名称'),
            'total'=>$this->decimal()->notNull()->comment('订单金额'),
            'status'=>$this->integer(6)->notNull()->comment('订单状态'),
            'trade_no'=>$this->string()->comment('第三方支付交易号'),
            'create_time'=>$this->integer()->comment('创建时间'),
//name	varchar(50)	收货人
//province	varchar(20)	省
//city	varchar(20)	市
//area	varchar(20)	县
//address	varchar(255)	详细地址
//tel	char(11)	电话号码
//delivery_id	int	配送方式id
//delivery_name	varchar	配送方式名称
//delivery_price	float	配送方式价格
//payment_id	int	支付方式id
//payment_name	varchar	支付方式名称
//total	decimal	订单金额
//status	int	订单状态（0已取消1待付款2待发货3待收货4完成）
//trade_no	varchar	第三方支付交易号
//create_time	int	创建时间
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('order');
    }
}
