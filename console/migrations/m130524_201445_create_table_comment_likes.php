<?php

use yii\db\Migration;

class m130524_201445_create_table_comment_likes extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%comment_likes}}', [
            'id' => $this->primaryKey(),
            'comment_id' => $this->integer()->comment('Комментарий'),
            'user_id' => $this->integer()->comment('Пользователь'),

            'created_at' => $this->integer(),
        ], $tableOptions);

        $this->createIndex(
            'idx-comment_likes-comment_id',
            'comment_likes',
            'comment_id'
        );

        $this->addForeignKey(
            'fk-comment_likes-comment_id',
            'comment_likes',
            'comment_id',
            'comments',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-comment_likes-user_id',
            'comment_likes',
            'user_id'
        );

        $this->addForeignKey(
            'fk-comment_likes-user_id',
            'comment_likes',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropForeignKey(
            'fk-comment_likes-comment_id',
            'comment_likes'
        );
        $this->dropForeignKey(
            'fk-comment_likes-user_id',
            'comment_likes'
        );
        $this->dropTable('{{%comment_likes}}');
    }
}
