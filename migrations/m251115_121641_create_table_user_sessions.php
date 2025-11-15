<?php

use yii\db\Migration;

class m251115_121641_create_table_user_sessions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_sessions}}', [
        'id' => $this->primaryKey(),
        'user_id' => $this->integer()->notNull(),
        'session_id' => $this->string(64)->notNull()->unique(),
        'ip_address' => $this->string(45)->null(),
        'user_agent' => $this->string(255)->null(),
        'device_name' => $this->string(100)->null(),
        'login_time' => $this->dateTime()->notNull(),
        'last_activity' => $this->dateTime()->null(),
        'logout_time' => $this->dateTime()->null(),
        'revoked_by_admin' => $this->boolean()->defaultValue(false),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

        $this->createIndex('idx-user_sessions-user_id', '{{%user_sessions}}', 'user_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user_sessions}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m251016_123415_create_table_user_sessions cannot be reverted.\n";

        return false;
    }
    */
}
