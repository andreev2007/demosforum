<?php

use yii\db\Migration;

class m130524_201442_create_table_user extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'avatar' => $this->string()->null()->comment('Аватарка'),
            'first_name' => $this->string()->comment('Имя'),
            'last_name' => $this->string()->comment('Фамилия'),
            'network' => $this->string()->comment('Соц сеть'),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string()->comment('Электронная почта'),
            'phone' => $this->string()->comment('Телефон'),
            'telegram' => $this->string()->null()->comment('Telegram'),
            'whatsapp' => $this->string()->comment('Whatsapp'),

            'status' => $this->smallInteger()->notNull()->defaultValue(9),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%user}}');
    }
}
