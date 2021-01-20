<?php

use app\models\User;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%game}}`.
 */
class m210119_041342_create_games_table extends Migration
{
    /**
     * @var string $tableName
     */
    private string $tableName = '{{%games}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'name' => $this->string(),

            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
            'created_by' => $this->integer(),
            'updated_at' => $this->dateTime()
                ->defaultExpression('CURRENT_TIMESTAMP')
                ->append('ON UPDATE CURRENT_TIMESTAMP'),
            'updated_by' => $this->integer(),
        ]);

        $this->addForeignKey('fk_games_created_by', $this->tableName, 'created_by', User::tableName(), 'id');
        $this->addForeignKey('fk_games_updated_by', $this->tableName, 'updated_by', User::tableName(), 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_games_created_by', $this->tableName);
        $this->dropForeignKey('fk_games_updated_by', $this->tableName);
        $this->dropTable($this->tableName);
    }
}
