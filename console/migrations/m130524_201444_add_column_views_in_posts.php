<?php

use yii\db\Migration;

class m130524_201444_add_column_views_in_posts extends Migration
{
    public function up()
    {
        $this->addColumn('posts', 'views', $this->integer()->defaultValue(0));
    }

    public function down()
    {
        $this->dropColumn('{{%posts}}','views');
    }
}
