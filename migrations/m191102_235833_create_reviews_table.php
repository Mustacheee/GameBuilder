<?php

use app\models\User;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%reviews}}`.
 */
class m191102_235833_create_reviews_table extends Migration
{
    private $tableName = '{{%reviews}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'rating' => $this->integer(3),
            'content' => $this->text(),
            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
            'created_by' => $this->integer(),
            'updated_at' => $this->dateTime()
                ->defaultExpression('CURRENT_TIMESTAMP')
                ->append('ON UPDATE CURRENT_TIMESTAMP'),
            'updated_by' => $this->integer(),
        ]);

        $this->addForeignKey('fk_reviews_user',  $this->tableName, 'user_id', User::tableName(), 'id');
        $this->addForeignKey('fk_reviews_created_by', $this->tableName, 'created_by', User::tableName(), 'id');
        $this->addForeignKey('fk_reviews_updated_by', $this->tableName, 'updated_by', User::tableName(), 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_reviews_user',  $this->tableName);
        $this->dropForeignKey('fk_reviews_created_by', $this->tableName);
        $this->dropForeignKey('fk_reviews_updated_by', $this->tableName);
        $this->dropTable('{{%reviews}}');
    }
}
