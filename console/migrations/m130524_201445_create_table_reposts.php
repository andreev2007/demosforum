<?php

use yii\db\Migration;

class m130524_201445_create_table_reposts extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        try {
            $this->createTable('{{%reposts}}', [
                'id' => $this->primaryKey(),
                'post_id' => $this->integer()->notNull(),
                'owner_id' => $this->integer(),

                'created_by' => $this->integer()->notNull(),
                'updated_by' => $this->integer()->notNull(),
                'created_at' => $this->integer()->notNull(),
                'updated_at' => $this->integer()->notNull(),
            ], $tableOptions);

            $this->createIndex(
                'idx-reposts-post_id',
                'reposts',
                'post_id'
            );

            $this->addForeignKey(
                'fk-reposts-post_id',
                'reposts',
                'post_id',
                'posts',
                'id',
                'CASCADE'
            );

            $this->createIndex(
                'idx-reposts-owner_id',
                'reposts',
                'owner_id'
            );

            $this->addForeignKey(
                'fk-reposts-owner_id',
                'reposts',
                'owner_id',
                'user',
                'id',
                'CASCADE'
            );
        } catch (\yii\base\Exception $e) {
        }
    }

    public function down()
    {
        $this->dropForeignKey(
            'fk-reposts-owner_id',
            'reposts'
        );
        $this->dropForeignKey(
            'fk-reposts-post_id',
            'reposts'
        );
        $this->dropTable('{{%reposts}}');
    }
}
