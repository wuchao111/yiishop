<?php

use yii\db\Migration;

/**
 * Class m180315_073834_update_order_table
 */
class m180315_073834_update_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute('ALTER TABLE `order` ENGINE = innodb');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180315_073834_update_order_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180315_073834_update_order_table cannot be reverted.\n";

        return false;
    }
    */
}
