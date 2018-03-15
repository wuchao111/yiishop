<?php

use yii\db\Migration;

/**
 * Handles the creation of table `admin`.
 */
class m180304_024213_create_admin_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('admin', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique()->comment('用户名'),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull()->comment('密码'),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string()->notNull()->unique()->comment('邮箱'),

            'status' => $this->smallInteger()->notNull()->defaultValue(0),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'last_login_time'=>$this->integer()->comment('最后登录时间'),
            'last_login_ip'=>$this->integer()->comment('最后登录ip')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('admin');
    }
}
