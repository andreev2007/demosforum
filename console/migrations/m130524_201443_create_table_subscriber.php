<?php

use yii\db\Migration;

class m130524_201443_create_table_subscriber extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%subscriber}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'subscriber_id' => $this->integer(),

            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex(
            'idx-subscriber-user_id',
            'subscriber',
            'user_id'
        );

        $this->addForeignKey(
            'fk-subscriber-user_id',
            'subscriber',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-subscriber-subscriber_id',
            'subscriber',
            'subscriber_id'
        );

        $this->addForeignKey(
            'fk-subscriber-subscriber_id',
            'subscriber',
            'subscriber_id',
            'user',
            'id',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropForeignKey(
            'fk-subscriber-subscriber_id',
            'subscriber'
        );
        $this->dropForeignKey(
            'fk-subscriber-user_id',
            'subscriber'
        );
        $this->dropTable('{{%subscriber}}');
    }
}
