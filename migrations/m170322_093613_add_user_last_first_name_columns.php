<?php

use yii\db\Migration;

class m170322_093613_add_user_last_first_name_columns extends Migration
{
    public function up()
    {
        $this->addColumn('user', 'first_name', $this->string(255)->notNull());
        $this->addColumn('user', 'last_name', $this->string(255)->notNull());
        $this->dropColumn('user', 'username');

    }

    public function down()
    {

        $this->dropColumn('user', 'first_name');
        $this->dropColumn('user', 'last_name');
        $this->addColumn('user', 'username', $this->string(255)->notNull()->unique());

    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
