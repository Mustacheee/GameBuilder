<?php

use app\models\User;
use yii\db\Migration;

/**
 * Class m191106_034307_add_description_to_users_table
 */
class m191106_034307_add_description_to_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(User::tableName(), 'description', $this->text()->after('email'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(User::tableName(), 'description');
    }
}
