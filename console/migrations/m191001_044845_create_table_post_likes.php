<?php

use yii\db\Migration;

class m191001_044845_create_table_post_likes extends Migration
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
        $this->createTable('{{%post_likes}}', [
            'id' => $this->primaryKey(),
            'post_id' => $this->integer()->notNull()->comment('Пост'),
            'user_id' => $this->integer()->notNull()->comment('Пользователь'),

            'created_at' => $this->integer()->notNull()->comment('Добавлен')
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            'idx-post_likes-user_id',
            'post_likes',
            'user_id'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            'fk-post_likes-user_id-id',
            '{{%post_likes}}',
            'user_id',
            "{{%user}}",
            'id',
            'CASCADE'
        );

        // creates index for column `post_id`
        $this->createIndex(
            'idx-post_likes-post_id',
            'post_likes',
            'post_id'
        );

        // add foreign key for table `{{%posts}}`
        $this->addForeignKey(
            'fk-post_likes-post_id-id',
            'post_likes',
            'post_id',
            "posts",
            'id',
            'CASCADE'
        );


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-post_likes-user_id-id','post_likes');
        $this->dropForeignKey('fk-post_likes-post_id-id','post_likes');
        $this->dropTable('{{%post_likes}}');
    }

}
