<?php

use app\models\User;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%access_tokens}}`.
 */
class m190831_153536_create_access_token_table extends Migration
{
    private $tableName = '{{%access_tokens}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(11)->notNull(),
            'token' => $this->text()->notNull(),
            'expires_at' => $this->dateTime()->notNull(),
            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP')->append('ON UPDATE CURRENT_TIMESTAMP'),
        ]);

        $this->addForeignKey('fk_access_tokens_user', $this->tableName, 'user_id', User::tableName(),'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
