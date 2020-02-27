<?php

use yii\db\Migration;

/**
 * Class m200222_213011_drop_username
 */
class m200222_213011_drop_username extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('users', 'username');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('users', 'username', $this->string()->defaultValue(null)->after('last_name'));
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200222_213011_drop_username cannot be reverted.\n";

        return false;
    }
    */
}
