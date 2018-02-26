<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article_category`.
 */
class m180226_115610_create_article_category_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article_category', [
            'id' => $this->primaryKey(),
//            id	primaryKey
//name	varchar(50)	名称
            'name'=>$this->string(50)->notNull()->comment('名称'),
            'intro'=>$this->text()->comment('简介'),
            'sort'=>$this->integer()->notNull()->comment('排序'),
            'is_deleted'=>$this->integer(1)->notNull()->comment('状态'),
//intro	text	简介
//sort	int(11)	排序
//is_deleted	int(1)	状态(0正常 1删除)
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article_category');
    }
}
