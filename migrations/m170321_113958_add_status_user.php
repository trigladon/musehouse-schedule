<?php

use yii\db\Migration;

class m170321_113958_add_status_user extends Migration
{
    public function up()
    {
        $this->addColumn('user', 'status', $this->integer()->notNull());

    }

    public function down()
    {

        $this->dropColumn('user', 'status');

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
