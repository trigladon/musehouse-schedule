<?php

use yii\db\Migration;

class m170409_015503_add_date_secr_key extends Migration
{
    public function up()
    {
        $this->addColumn('user', 'date_secret_key', $this->dateTime());

    }

    public function down()
    {

        $this->dropColumn('user', 'date_secret_key');

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
