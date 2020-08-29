<?php

use yii\db\Migration;

class m130524_201444_create_table_comments extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%comments}}', [
            'id' => $this->primaryKey(),
            'name' => $this->text()->notNull()->comment('Название'),
            'post_id' => $this->integer()->comment('Имя'),
            'parent_id' => $this->integer()->defaultValue(null)->comment('Родительский комментарий'),

            'status' => $this->integer()->notNull()->defaultValue(10),
            'created_by' => $this->integer()->notNull(),
            'updated_by' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex(
            'idx-comments-post_id',
            'comments',
            'post_id'
        );

        $this->addForeignKey(
            'fk-comments-post_id',
            'comments',
            'post_id',
            'posts',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-comments-parent_id',
            'comments',
            'parent_id'
        );

        $this->addForeignKey(
            'fk-comments-parent_id',
            'comments',
            'parent_id',
            'comments',
            'id',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropForeignKey(
            'fk-comments-post_id',
            'comments'
        );
        $this->dropForeignKey(
            'fk-comments-parent_id',
            'comments'
        );
        $this->dropTable('{{%comments}}');
    }
}
