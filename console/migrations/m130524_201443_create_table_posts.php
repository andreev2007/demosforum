<?php

use yii\db\Migration;

class m130524_201443_create_table_posts extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%posts}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->comment('Заголовок'),
            'description' => $this->text()->notNull()->comment('Описание'),
            'image' => $this->string()->comment('Картинка'),
            'status' => $this->integer()->comment('Статус')->defaultValue(10),
            'like_id' => $this->integer()->comment('Лайк'),

            'created_by' => $this->integer()->notNull(),
            'updated_by' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%posts}}');
    }
}
