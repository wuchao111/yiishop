<?php

use yii\db\Migration;

/**
 * Handles the creation of table `menu`.
 */
class m180308_033838_create_menu_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('menu', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(20)->notNull()->comment('菜单名称'),
            'parent_id'=>$this->integer(5)->notNull()->comment('上级菜单'),
            'url_id'=>$this->integer(5)->notNull()->comment('地址'),
            'sort'=>$this->integer(5)->notNull()->comment('排序'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('menu');
    }
}
