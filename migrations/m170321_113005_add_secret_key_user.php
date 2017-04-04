<?php

use yii\db\Migration;

class m170321_113005_add_secret_key_user extends Migration
{
    public function up()
    {
        $this->addColumn('user', 'secret_key', $this->string(255));

    }

    public function down()
    {

        $this->dropColumn('user', 'secret_key');

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
