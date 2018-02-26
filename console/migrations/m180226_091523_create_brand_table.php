<?php

use yii\db\Migration;

/**
 * Handles the creation of table `brand`.
 */
class m180226_091523_create_brand_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('brand', [
            'id' => $this->primaryKey(),
//            id	primaryKey
//name	varchar(50)	名称
            'name'=>$this->string(50)->notNull()->comment('名称'),
            'intro'=>$this->text()->comment('简介'),
            'logo'=>$this->string()->notNull()->comment('图片'),
            'sort'=>$this->integer()->notNull()->comment('排序'),
            'is_deleted'=>$this->integer(1)->notNull()->comment('状态'),
//intro	text	简介
//logo	varchar(255)	LOGO图片
//sort	int(11)	排序
//is_deleted	int(1)	状态(0正常 1删除)

        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('brand');
    }
}
