<?php

use yii\db\Migration;

/**
 * Handles the creation of table `payment`.
 */
class m180314_074516_create_payment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('payment', [
            'id' => $this->primaryKey(),
            'payment_id'=>$this->integer()->notNull()->comment('支付方式id'),
            'payment_name'=>$this->string()->notNull()->comment('支付方式名称'),
            'total'=>$this->decimal()->notNull()->comment('订单金额'),
//            payment_name	varchar	支付方式名称
//total	decimal	订单金额
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('payment');
    }
}
