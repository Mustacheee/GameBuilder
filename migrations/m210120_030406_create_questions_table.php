<?php

use app\models\Category;
use app\models\User;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%questions}}`.
 */
class m210120_030406_create_questions_table extends Migration
{
    private string $tableName = '{{%questions}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'category_id' => $this->integer(),

            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
            'created_by' => $this->integer(),
            'updated_at' => $this->dateTime()
                ->defaultExpression('CURRENT_TIMESTAMP')
                ->append('ON UPDATE CURRENT_TIMESTAMP'),
            'updated_by' => $this->integer(),
        ]);

        $this->addForeignKey('fk_questions_category', $this->tableName, 'category_id', Category::tableName(), 'id');
        $this->addForeignKey('fk_questions_created_by', $this->tableName, 'created_by', User::tableName(), 'id');
        $this->addForeignKey('fk_questions_updated_by', $this->tableName, 'updated_by', User::tableName(), 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_questions_category', $this->tableName);
        $this->dropForeignKey('fk_questions_created_by', $this->tableName);
        $this->dropForeignKey('fk_questions_updated_by', $this->tableName);
        $this->dropTable($this->tableName);
    }
}
