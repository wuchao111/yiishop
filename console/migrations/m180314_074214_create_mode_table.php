<?php

use yii\db\Migration;

/**
 * Handles the creation of table `mode`.
 */
class m180314_074214_create_mode_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('mode', [
            'id' => $this->primaryKey(),
            'delivery_id'=>$this->integer()->notNull()->comment('配送方式id'),
            'delivery_name'=>$this->string()->notNull()->comment('配送方式名称'),
            'delivery_price'=>$this->float()->notNull()->comment('配送方式价格'),
//            delivery_id	int	配送方式id
//delivery_name	varchar	配送方式名称
//delivery_price	float	配送方式价格
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('mode');
    }
}
