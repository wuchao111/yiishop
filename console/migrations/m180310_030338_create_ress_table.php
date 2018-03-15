<?php

use yii\db\Migration;

/**
 * Handles the creation of table `ress`.
 */
class m180310_030338_create_ress_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('ress', [
            'id' => $this->primaryKey(),
//            电话,姓名,省,市,县,当前登录人,详细地址,状态,
            'member_id'=>$this->integer()->notNull()->comment('当前登录人'),
            'name'=>$this->string(5)->notNull()->comment('姓名'),
            'tel'=>$this->integer(11)->notNull()->comment('电话'),
            'province'=>$this->string(6 )->notNull()->comment('省'),
            'city'=>$this->string(10)->notNull()->comment('市'),
            'county'=>$this->string(10)->notNull()->comment('县'),
            'address'=>$this->string()->notNull()->comment('详细地址'),
            'status'=>$this->integer(1)->comment('状态'),

        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('ress');
    }
}
