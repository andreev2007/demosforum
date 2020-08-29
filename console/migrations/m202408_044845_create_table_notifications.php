<?php

use yii\db\Migration;

class m202408_044845_create_table_notifications extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%notifications}}', [
            'id' => $this->primaryKey(),
            'type' => $this->string()->notNull()->comment('Тип'),
            'post_id' => $this->integer()->notNull()->comment('Пост'),
            'is_read' => $this->boolean()->notNull()->defaultValue(false),
            'created_at' => $this->integer()->notNull()->comment('Добавлен'),
            'created_by' => $this->integer()->notNull()->comment('Добавлен'),
            'user_id' => $this->integer()->notNull()->comment('Пользователь'),
        ]);
/*
        // creates index for column `user_id`
        $this->createIndex(
            'idx-user_saved-user_id',
            'user_saved',
            'user_id'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            'fk-user_saved-user_id-id',
            '{{%user_saved}}',
            'user_id',
            "{{%user}}",
            'id',
            'CASCADE'
        );

        // creates index for column `post_id`
        $this->createIndex(
            'idx-user_saved-post_id',
            'user_saved',
            'post_id'
        );

        // add foreign key for table `{{%posts}}`
        $this->addForeignKey(
            'fk-user_saved-post_id-id',
            'user_saved',
            'post_id',
            "posts",
            'id',
            'CASCADE'
        );*/


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      /*  $this->dropForeignKey('fk-user_saved-user_id-id','user_saved');
        $this->dropForeignKey('fk-user_saved-post_id-id','user_saved');*/
        $this->dropTable('{{%notifications}}');
    }

}
