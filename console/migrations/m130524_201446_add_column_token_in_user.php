<?php

use yii\db\Migration;

class m130524_201446_add_column_token_in_user extends Migration
{
    public function up()
    {
        $this->addColumn('user', 'token', $this->string(255)->null());
    }

    public function down()
    {
        $this->dropColumn('{{%user}}','token');
    }
}
