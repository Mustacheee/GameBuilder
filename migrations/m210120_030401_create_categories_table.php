<?php

use app\models\User;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%categories}}`.
 */
class m210120_030401_create_categories_table extends Migration
{
    private string $tableName = '{{%categories}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),

            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
            'created_by' => $this->integer(),
            'updated_at' => $this->dateTime()
                ->defaultExpression('CURRENT_TIMESTAMP')
                ->append('ON UPDATE CURRENT_TIMESTAMP'),
            'updated_by' => $this->integer(),
        ]);

        $this->addForeignKey('fk_categories_created_by', $this->tableName, 'created_by', User::tableName(), 'id');
        $this->addForeignKey('fk_categories_updated_by', $this->tableName, 'updated_by', User::tableName(), 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_categories_created_by', $this->tableName);
        $this->dropForeignKey('fk_categories_updated_by', $this->tableName);
        $this->dropTable($this->tableName);
    }
}
