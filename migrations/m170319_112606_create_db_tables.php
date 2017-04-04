<?php

use yii\db\Migration;

class m170319_112606_create_db_tables extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if($this->db->driverName === 'mysql'){
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

//----------------START_TABLE----------------

        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'username' => $this->string(255)->notNull()->unique(),
            'password' => $this->string(255),
            'auth_key' => $this->string(255)->notNull(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

//----------------START_TABLE----------------
        $this->createTable('instricon', [
            'id' => $this->primaryKey(),
            'icon' => $this->string(255)->notNull(),
            'instr_name' => $this->string(100)->notNull()->unique(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

//----------------START_TABLE----------------
        $this->createTable('userinstr', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'instricon_id' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

//----------------START_TABLE----------------
        $this->createTable('statusschedule', [
            'id' => $this->primaryKey(),
            'icon' => $this->string(255)->notNull()->unique(),
            'color' => $this->string(100)->notNull(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

//----------------START_TABLE----------------
        $this->createTable('schedinstricon', [
            'id' => $this->primaryKey(),
            'instricon_id' => $this->integer(),
            'userschedule_id' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

//----------------START_TABLE----------------
        $this->createTable('userschedule', [
            'id' => $this->primaryKey(),
            'lesson_start' => $this->dateTime(),
            'lesson_finish' => $this->dateTime(),
            'user_id' => $this->integer(),
            'instricon_id' => $this->integer(),
            'statusschedule_id' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);


//----------------END_TABLE----------------

//----------------START_FK----------------
        $this->addForeignKey(
            'fk-userinstr-user_id',
            'userinstr',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-userinstr-instricon_id',
            'userinstr',
            'instricon_id',
            'instricon',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-userschedule-user_id',
            'userschedule',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-userschedule-instricon_id',
            'userschedule',
            'instricon_id',
            'instricon',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-userschedule-statusschedule_id',
            'userschedule',
            'statusschedule_id',
            'statusschedule',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-schedinstricon-instricon_id',
            'schedinstricon',
            'instricon_id',
            'instricon',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-schedinstricon-userschedule_id',
            'schedinstricon',
            'userschedule_id',
            'userschedule',
            'id',
            'CASCADE'
        );
//----------------END_FK----------------

}



    public function down()
    {

        $this->dropForeignKey(
            'fk-userinstr-user_id',
            'userinstr'
        );
        $this->dropForeignKey(
            'fk-userinstr-instricon_id',
            'userinstr'
        );

        $this->dropForeignKey(
            'k-schedinstricon-instricon_id',
            'schedinstricon'
        );
        $this->dropForeignKey(
            'fk-schedinstricon-userschedule_id',
            'schedinstricon'
        );

        $this->dropForeignKey(
            'fk-userschedule-user_id',
            'userschedule'
        );
        $this->dropForeignKey(
            'fk-userschedule-instricon_id',
            'userschedule'
        );
        $this->dropForeignKey(
            'fk-userschedule-statusschedule_id',
            'userschedule'
        );

        $this->dropTable('user');
        $this->dropTable('instricon');
        $this->dropTable('userinstr');
        $this->dropTable('statusschedule');
        $this->dropTable('schedinstricon');
        $this->dropTable('userschedule');

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
