<?php

use yii\db\Migration;

class m170327_163611_add_user_activation_status extends Migration
{
    public function up()
    {
        $this->addColumn('user', 'letter_status', $this->integer()->notNull());

    }

    public function down()
    {

        $this->dropColumn('user', 'letter_status');

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
