<?php

use yii\db\Migration;

class m170319_171439_add_column_email extends Migration
{
    public function up()
    {
        $this->addColumn('user', 'email', $this->string(255)->unique()->notNull());

    }

    public function down()
    {

        $this->dropColumn('user', 'email');

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
